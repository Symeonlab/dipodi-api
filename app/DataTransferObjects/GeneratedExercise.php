<?php

namespace App\DataTransferObjects;

class GeneratedExercise
{
    public string $name;
    public int $durationMinutes;
    public string $intensityDescription;
    public float $met;
    public float $caloriesBurned;

    public function __construct(string $name, int $durationMinutes, string $intensityDescription, float $met, float $weightKg)
    {
        $this->name = $name;
        $this->durationMinutes = $durationMinutes;
        $this->intensityDescription = $intensityDescription;
        $this->met = $met;

        // Use the formula from your PDF
        // Kcal/minute = (MET X 3,5 X Poids en Kg) / 200
        $caloriesPerMinute = ($met * 3.5 * $weightKg) / 200;
        $this->caloriesBurned = $caloriesPerMinute * $durationMinutes;
    }

    /**
     * Helper to convert to an array for JSON responses.
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'duration_minutes' => $this->durationMinutes,
            'intensity' => $this->intensityDescription,
            'met' => $this->met,
            'calories_burned' => round($this->caloriesBurned, 2),
        ];
    }
}
