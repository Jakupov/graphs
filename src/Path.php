<?php

namespace Jakupov\Graphs;

use Iterator;
use Stringable;

/**
 * @implements Iterator<Node>
 */
class Path implements Iterator, Stringable
{
    protected int $pointer = 0;

    /**
     * @var array<Node>
     */
    protected array $path = [];

    public function push(Node ...$node): void
    {
        array_push($this->path, ...$node);
    }

    public function pop(): ?Node
    {
        $this->pointer = 0;

        return array_pop($this->path);
    }

    public function shift(): ?Node
    {
        $this->pointer = 0;

        return array_shift($this->path);
    }

    public function unshift(Node ...$node): void
    {
        $this->pointer = 0;

        array_unshift($this->path, ...$node);
    }

    /**
     * @return array<Node>
     */
    public function asArray(): array
    {
        return $this->path;
    }

    public function current(): Node
    {
        return $this->path[$this->pointer];
    }

    public function next(): void
    {
        ++$this->pointer;
    }

    public function key(): int
    {
        return $this->pointer;
    }

    public function valid(): bool
    {
        return isset($this->path[$this->pointer]);
    }

    public function rewind(): void
    {
        $this->pointer = 0;
    }

    public function __toString(): string
    {
        $values = [];

        foreach ($this->path as $node) {
            $values[] = $node->getValue();
        }

        return implode(' > ', $values);
    }
}