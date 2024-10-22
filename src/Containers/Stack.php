<?php

namespace Jakupov\Graphs\Containers;

class Stack
{
    /**
     * @var mixed[]
     */
    private array $stack = [];
    public function push(mixed $item): void
    {
        array_unshift($this->stack, $item);
    }
    public function pop(): mixed
    {
        if ($this->isEmpty()) {
            throw new \RuntimeException('Stack is empty');
        }

        return array_shift($this->stack);
    }

    public function peek(): mixed
    {
        return current($this->stack);
    }

    public function isEmpty(): bool
    {
        return empty($this->stack);
    }
}