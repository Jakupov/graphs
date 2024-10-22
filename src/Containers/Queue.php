<?php

namespace Jakupov\Graphs\Containers;

class Queue
{
    /**
     * @var mixed[]
     */
    private array $queue = [];

    public function enqueue(mixed $data): void
    {
        $this->queue[] = $data;
    }

    public function dequeue(): mixed
    {
        return array_shift($this->queue);
    }

    public function isEmpty(): bool
    {
        return empty($this->queue);
    }
}