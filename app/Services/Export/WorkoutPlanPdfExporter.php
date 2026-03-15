<?php

namespace App\Services\Export;

use App\Models\User;
use App\Models\WorkoutSession;
use Illuminate\Support\Collection;

class WorkoutPlanPdfExporter
{
    private User $user;
    private Collection $sessions;

    public function __construct(User $user)
    {
        $this->user = $user;
        $this->sessions = WorkoutSession::where('user_id', $user->id)
            ->with('exercises')
            ->get();
    }

    /**
     * Generate HTML content for the workout plan PDF.
     */
    public function generateHtml(): string
    {
        $profile = $this->user->profile;
        $userName = $this->user->name;
        $position = $profile->position ?? 'Non spécifié';
        $discipline = $profile->discipline ?? 'Non spécifié';
        $level = $profile->level ?? 'Non spécifié';
        $generatedAt = now()->format('d/m/Y');

        $sessionsHtml = $this->generateSessionsHtml();

        return <<<HTML
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Programme d'Entraînement - {$userName}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #2563eb;
        }
        .header h1 {
            color: #2563eb;
            font-size: 24px;
            margin-bottom: 10px;
        }
        .header .subtitle {
            color: #666;
            font-size: 14px;
        }
        .profile-info {
            background-color: #f8fafc;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 25px;
        }
        .profile-info h2 {
            color: #1e40af;
            font-size: 16px;
            margin-bottom: 10px;
        }
        .profile-grid {
            display: flex;
            flex-wrap: wrap;
        }
        .profile-item {
            width: 50%;
            padding: 5px 0;
        }
        .profile-item strong {
            color: #374151;
        }
        .day-section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }
        .day-header {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: white;
            padding: 12px 15px;
            border-radius: 8px 8px 0 0;
        }
        .day-header h3 {
            font-size: 16px;
            margin-bottom: 5px;
        }
        .day-header .theme {
            font-size: 13px;
            opacity: 0.9;
        }
        .day-content {
            border: 1px solid #e5e7eb;
            border-top: none;
            border-radius: 0 0 8px 8px;
            padding: 15px;
        }
        .warmup, .finisher {
            background-color: #fef3c7;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 15px;
            font-size: 11px;
        }
        .warmup strong, .finisher strong {
            color: #92400e;
        }
        .exercises-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .exercises-table th {
            background-color: #f3f4f6;
            padding: 10px;
            text-align: left;
            font-size: 11px;
            color: #374151;
            border-bottom: 2px solid #e5e7eb;
        }
        .exercises-table td {
            padding: 10px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 11px;
        }
        .exercises-table tr:last-child td {
            border-bottom: none;
        }
        .rest-day {
            text-align: center;
            padding: 30px;
            color: #6b7280;
            font-style: italic;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            color: #9ca3af;
            font-size: 10px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
        }
        .logo {
            font-weight: bold;
            color: #2563eb;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🏋️ Programme d'Entraînement Personnalisé</h1>
        <p class="subtitle">Généré le {$generatedAt}</p>
    </div>

    <div class="profile-info">
        <h2>📋 Profil Athlète</h2>
        <div class="profile-grid">
            <div class="profile-item"><strong>Nom:</strong> {$userName}</div>
            <div class="profile-item"><strong>Position:</strong> {$position}</div>
            <div class="profile-item"><strong>Discipline:</strong> {$discipline}</div>
            <div class="profile-item"><strong>Niveau:</strong> {$level}</div>
        </div>
    </div>

    <h2 style="color: #1e40af; margin-bottom: 15px; font-size: 18px;">📅 Plan Hebdomadaire</h2>

    {$sessionsHtml}

    <div class="footer">
        <p>Généré par <span class="logo">DIPODI</span> - Votre coach sportif personnel</p>
        <p>Ce programme est personnalisé selon votre profil. Consultez un professionnel de santé avant de commencer tout nouveau programme d'exercice.</p>
    </div>
</body>
</html>
HTML;
    }

    /**
     * Generate HTML for all workout sessions.
     */
    private function generateSessionsHtml(): string
    {
        if ($this->sessions->isEmpty()) {
            return '<div class="rest-day">Aucun programme généré. Veuillez d\'abord générer votre plan d\'entraînement via l\'application.</div>';
        }

        $daysOrder = ['LUNDI', 'MARDI', 'MERCREDI', 'JEUDI', 'VENDREDI', 'SAMEDI', 'DIMANCHE'];
        $html = '';

        foreach ($daysOrder as $day) {
            $session = $this->sessions->firstWhere('day', $day);

            if ($session) {
                $html .= $this->generateDayHtml($session);
            }
        }

        return $html;
    }

    /**
     * Generate HTML for a single day's session.
     */
    private function generateDayHtml(WorkoutSession $session): string
    {
        $dayName = $session->day;
        $theme = $session->theme ?? 'Repos';
        $warmup = $session->warmup;
        $finisher = $session->finisher;
        $exercises = $session->exercises;

        $dayNameFr = $this->translateDay($dayName);

        // Check if it's a rest or match day
        if (in_array($theme, ['Repos', 'Jour de Match'])) {
            $icon = $theme === 'Repos' ? '🛌' : '⚽';
            return <<<HTML
            <div class="day-section">
                <div class="day-header">
                    <h3>{$dayNameFr}</h3>
                    <span class="theme">{$icon} {$theme}</span>
                </div>
                <div class="day-content">
                    <div class="rest-day">{$theme}</div>
                </div>
            </div>
            HTML;
        }

        // Active training day
        $warmupHtml = $warmup ? "<div class='warmup'><strong>🔥 Échauffement:</strong> {$warmup}</div>" : '';
        $finisherHtml = $finisher ? "<div class='finisher'><strong>💪 Finisher:</strong> " . nl2br(e($finisher)) . "</div>" : '';

        $exercisesHtml = '';
        if ($exercises->count() > 0) {
            $exercisesHtml = '<table class="exercises-table"><thead><tr><th>#</th><th>Exercice</th><th>Séries</th><th>Répétitions</th><th>Récup.</th></tr></thead><tbody>';

            $i = 1;
            foreach ($exercises as $exercise) {
                $exercisesHtml .= "<tr><td>{$i}</td><td>{$exercise->name}</td><td>{$exercise->sets}</td><td>{$exercise->reps}</td><td>{$exercise->recovery}</td></tr>";
                $i++;
            }

            $exercisesHtml .= '</tbody></table>';
        }

        return <<<HTML
        <div class="day-section">
            <div class="day-header">
                <h3>{$dayNameFr}</h3>
                <span class="theme">🏋️ {$theme}</span>
            </div>
            <div class="day-content">
                {$warmupHtml}
                {$exercisesHtml}
                {$finisherHtml}
            </div>
        </div>
        HTML;
    }

    /**
     * Translate day name to French display format.
     */
    private function translateDay(string $day): string
    {
        $translations = [
            'LUNDI' => 'Lundi',
            'MARDI' => 'Mardi',
            'MERCREDI' => 'Mercredi',
            'JEUDI' => 'Jeudi',
            'VENDREDI' => 'Vendredi',
            'SAMEDI' => 'Samedi',
            'DIMANCHE' => 'Dimanche',
        ];

        return $translations[$day] ?? $day;
    }

    /**
     * Get the sessions collection.
     */
    public function getSessions(): Collection
    {
        return $this->sessions;
    }
}
