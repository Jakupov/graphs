<?php

namespace Jakupov\Graphs;

use ArrayAccess;
use Iterator;

/**
 * @implements ArrayAccess<Node|string|int, Node>
 * @implements Iterator<Node|string|int, Node>
 */
class Graph implements ArrayAccess, Iterator, \Countable
{

    public function __construct(protected bool $directed = true)
    {
    }

    /**
     * @var array<Node|string|int, Node>
     */
    protected array $nodes = [];
    protected bool $weighted = false;

    /**
     * Creates a graph from provided data
     *
     * @param array<array{Node|int|string|null, Node|int|string|null, int}> $data
     * @return void
     */
    public function create(array $data = []): void
    {
        foreach ($data as $nodeData) {
            list($from, $to, $weight) = $nodeData + [null, null, 1];

            if ($from === null) {
                throw new \RuntimeException("Node cannot be null");
            }

            $current = $this->getOrAddNode($from);

            if ($to === null) {
                continue;
            }

            $adjacent = $this->getOrAddNode($to);
            $edge = new Edge($adjacent, $weight);

            if ($weight !== 1) {
                $this->weighted = true;
            }

            $current->addEdge($edge);
            $adjacent->addParent($current, $edge);

            if (!$this->isDirected()) {
                $backEdge = new Edge($current, $weight);
                $adjacent->addEdge($backEdge);
                $current->addParent($adjacent, $backEdge);
            }
        }
    }

    public function getOrAddNode(Node|string|int $value): Node
    {
        if ($value instanceof Node) {
            $value = $value->getValue();
        }

        if (isset($this->nodes[$value])) {
            return $this->nodes[$value];
        }

        $this->nodes[$value] = new Node($value);

        return $this->nodes[$value];
    }

    public function isWeighted(): bool
    {
        return $this->weighted;
    }

    public function isDirected(): bool
    {
        return $this->directed;
    }

    public function current(): false|Node
    {
        return current($this->nodes);
    }

    public function next(): void
    {
        next($this->nodes);
    }

    public function key(): string|int|null
    {
        return key($this->nodes);
    }

    public function valid(): bool
    {
        return key($this->nodes) !== null;
    }

    public function rewind(): void
    {
        reset($this->nodes);
    }

    public function offsetExists(mixed $offset): bool
    {
        $this->validateOffset($offset);

        /** @var string|int $offset */
        return isset($this->nodes[$offset]);
    }

    public function offsetGet(mixed $offset): Node
    {
        $this->validateOffset($offset);

        /** @var string|int $offset */
        return $this->nodes[$offset];
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->validateOffset($offset);

        /** @var string|int $offset */
        if (!$value instanceof Node) {
            throw new \InvalidArgumentException("Only {Node::class} values allowed");
        }

        $this->nodes[$offset] = $value;
    }

    public function offsetUnset(mixed $offset): void
    {
        $this->validateOffset($offset);

        /** @var string|int $offset */
        if (isset($this->nodes[$offset])) {
            unset($this->nodes[$offset]);
        }
    }

    public function count(): int
    {
        return count($this->nodes);
    }

    protected function validateOffset(mixed $offset): void
    {
        if (!is_string($offset) && !is_int($offset)) {
            throw new \InvalidArgumentException("Invalid offset");
        }
    }
}