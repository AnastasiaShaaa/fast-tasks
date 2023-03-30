<?php

declare(strict_types=1);

namespace App\SnakesAndLadders;

class Graph
{
    public function __construct(
        private int $index,
        private int $step = -1,
        private bool $passed = false,
    ) {}

    public function getIndex(): int
    {
        return $this->index;
    }

    public function getStep(): int
    {
        return $this->step;
    }

    public function isPassed(): bool
    {
        return $this->passed;
    }

    public function setPassed(): self
    {
        $this->passed = true;

        return $this;
    }

    public function getNextIndex(): int
    {
        return $this->getIndex() - $this->getStep();
    }

    public function clearPassed(): self
    {
        $this->passed = false;

        return $this;
    }
}
