<?php

namespace App\DataTransferObjects;

class GeneratedSession
{
    public int $day; // Or string, depending on preference
    public string $name;
    /** @var \App\DataTransferObjects\GeneratedBlock[] */
    public array $blocks;

    public function __construct(int $day, string $name, array $blocks)
    {
        $this->day = $day;
        $this->name = $name;
        $this->blocks = $blocks;
    }

    public function toArray(): array
    {
        return [
            'day' => $this->day,
            'name' => $this->name,
            'blocks' => array_map(fn($b) => $b->toArray(), $this->blocks),
        ];
    }
}
