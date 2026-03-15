<?php

namespace App\Services\Workout;

class MatchAwarePlanGenerator
{
    // Define weekdays as constants for clarity
    const MONDAY = 'LUNDI';
    const TUESDAY = 'MARDI';
    const WEDNESDAY = 'MERCREDI';
    const THURSDAY = 'JEUDI';
    const FRIDAY = 'VENDREDI';
    const SATURDAY = 'SAMEDI';
    const SUNDAY = 'DIMANCHE';

    const WEEKDAYS = [
        self::MONDAY, self::TUESDAY, self::WEDNESDAY, self::THURSDAY,
        self::FRIDAY, self::SATURDAY, self::SUNDAY
    ];

    // Session types
    const T_MATCH = 'match';
    const T_RECOVERY = 'recovery';
    const T_REST = 'rest';
    const T_MOBILITY = 'mobility';
    const T_PRE_MATCH_BONUS = 'pre_match_bonus'; // 24h before: bonus only (abdos/gainage/pompes), no gym/cardio
    const T_HIGH_INTENSITY = 'high_intensity';
    const T_STRENGTH = 'strength';

    /**
     * Maps French day abbreviations to full day names.
     * Handles both 3-letter abbreviations and full names.
     */
    const DAY_ABBREVIATIONS = [
        'LUN' => 'LUNDI',
        'MAR' => 'MARDI',
        'MER' => 'MERCREDI',
        'JEU' => 'JEUDI',
        'VEN' => 'VENDREDI',
        'SAM' => 'SAMEDI',
        'DIM' => 'DIMANCHE',
        // Also handle English abbreviations
        'MON' => 'LUNDI',
        'TUE' => 'MARDI',
        'WED' => 'MERCREDI',
        'THU' => 'JEUDI',
        'FRI' => 'VENDREDI',
        'SAT' => 'SAMEDI',
        'SUN' => 'DIMANCHE',
    ];

    /**
     * Values meaning "no match day" — treat as null.
     */
    const NO_MATCH_VALUES = ['AUCUN', 'NONE', 'N/A', 'NULL', ''];

    public static function generate(?string $matchDay, array $preferredDays): array
    {
        $sessions = [];
        $week = self::WEEKDAYS;

        // Normalize match day
        $matchDay = $matchDay ? strtoupper(trim($matchDay)) : null;
        if ($matchDay && in_array($matchDay, self::NO_MATCH_VALUES)) {
            $matchDay = null;
        }
        if ($matchDay) {
            $matchDay = self::normalizeDay($matchDay);
        }

        // Normalize preferred days (convert abbreviations to full names)
        $preferredDays = array_map(function ($d) {
            return self::normalizeDay(strtoupper(trim($d)));
        }, $preferredDays);

        foreach ($week as $day) {
            if ($matchDay && $day === $matchDay) {
                $sessions[] = ['day' => $day, 'type' => self::T_MATCH];
                continue;
            }

            if ($matchDay) {
                $dayMinus1 = self::previousDay($matchDay);       // J-1 (24h before)
                $dayMinus2 = self::previousDay($dayMinus1);      // J-2 (48h before)
                $dayPlus1  = self::nextDay($matchDay);           // J+1 (day after)

                // J-1: Pre-match day — bonus only (abdos/gainage/pompes), no gym/cardio
                if ($day === $dayMinus1) {
                    $sessions[] = ['day' => $day, 'type' => self::T_PRE_MATCH_BONUS];
                    continue;
                }
                // J-2: Recovery/mobility — light session, privilegier la récupération
                if ($day === $dayMinus2) {
                    $sessions[] = ['day' => $day, 'type' => self::T_MOBILITY];
                    continue;
                }
                // J+1: Post-match recovery
                if ($day === $dayPlus1) {
                    $sessions[] = ['day' => $day, 'type' => self::T_RECOVERY];
                    continue;
                }
            }

            if (in_array($day, $preferredDays)) {
                $type = self::T_HIGH_INTENSITY; // Default for preferred day
                if ($matchDay) {
                    $distance = self::dayDistance($day, $matchDay);
                    if ($distance <= 2) { // Within 48h of match
                        $type = self::T_STRENGTH;
                    }
                }
                $sessions[] = ['day' => $day, 'type' => $type];
                continue;
            }

            // Not a match day, not a recovery day, not a preferred day
            $sessions[] = ['day' => $day, 'type' => self::T_REST];
        }

        return $sessions;
    }

    /**
     * Normalizes a day value (abbreviation or full name) to the full French weekday name.
     */
    private static function normalizeDay(string $day): string
    {
        $upper = strtoupper(trim($day));

        // Already a full day name?
        if (in_array($upper, self::WEEKDAYS)) {
            return $upper;
        }

        // Try abbreviation map
        return self::DAY_ABBREVIATIONS[$upper] ?? $upper;
    }

    private static function getDayIndex(string $day): int
    {
        return array_search(strtoupper($day), self::WEEKDAYS);
    }

    private static function nextDay(string $day): string
    {
        $idx = self::getDayIndex($day);
        return self::WEEKDAYS[($idx + 1) % 7];
    }

    private static function previousDay(string $day): string
    {
        $idx = self::getDayIndex($day);
        return self::WEEKDAYS[($idx - 1 + 7) % 7];
    }

    private static function dayDistance(string $dayA, string $dayB): int
    {
        $idxA = self::getDayIndex($dayA);
        $idxB = self::getDayIndex($dayB);
        $diff = abs($idxA - $idxB);
        return min($diff, 7 - $diff);
    }
}
