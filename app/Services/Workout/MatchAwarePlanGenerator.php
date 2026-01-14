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
    const T_HIGH_INTENSITY = 'high_intensity';
    const T_STRENGTH = 'strength';

    public static function generate(?string $matchDay, array $preferredDays): array
    {
        $sessions = [];
        $week = self::WEEKDAYS;
        $matchDay = $matchDay ? strtoupper($matchDay) : null;
        $preferredDays = array_map('strtoupper', $preferredDays);

        foreach ($week as $day) {
            if ($matchDay && $day === $matchDay) {
                $sessions[] = ['day' => $day, 'type' => self::T_MATCH];
                continue;
            }

            if ($matchDay) {
                if (self::previousDay($matchDay) === $day) {
                    $sessions[] = ['day' => $day, 'type' => self::T_MOBILITY]; // Day before match
                    continue;
                }
                if (self::nextDay($matchDay) === $day) {
                    $sessions[] = ['day' => $day, 'type' => self::T_RECOVERY]; // Day after match
                    continue;
                }
            }

            if (in_array($day, $preferredDays)) {
                $type = self::T_HIGH_INTENSITY; // Default for preferred day
                if ($matchDay) {
                    $distance = self::dayDistance($day, $matchDay);
                    if ($distance < 2) { // Too close to match
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
