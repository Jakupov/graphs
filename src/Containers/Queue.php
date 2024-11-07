<?php

namespace Jakupov\Graphs\Containers;

class Queue
{
    /**
     * @var mixed[]
     */
    private array $queue = [];

    /**
     * Adds given element to the end of queue
     *
     * @param mixed $data Element to be added to queue
     *
     * @return void
     */
    public function enqueue(mixed $data): void
    {
        $this->queue[] = $data;
    }

    /**
     * Returns first element from queue and removes it
     *
     * @return false|mixed Returns false if queue is empty, otherwise returns first element in queue
     */
    public function dequeue(): mixed
    {
        $a = current($this->queue);
        unset($this->queue[key($this->queue)]);

        return $a;
    }

    /**
     * Checks if queue is empty
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty($this->queue);
    }
}