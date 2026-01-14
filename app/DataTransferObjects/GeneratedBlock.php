<?php

namespace App\DataTransferObjects;

class GeneratedBlock
{
    public string $title;
    /** @var \App\DataTransferObjects\GeneratedExercise[] */
    public array $exercises;

    public function __construct(string $title, array $exercises)
    {
        $this->title = $title;
        $this->exercises = $exercises;
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'exercises' => array_map(fn($ex) => $ex->toArray(), $this->exercises),
        ];
    }
}
