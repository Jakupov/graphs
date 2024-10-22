<?php

namespace Jakupov\Graphs;

class Node
{
    protected int|string $value;

    /**
     * @var Edge[]
     */
    protected mixed $edges = [];

    /**
     * @var array<int|string, Edge>
     */
    protected mixed $parents = [];

    public function __construct(int|string $value)
    {
        $this->value = $value;
    }

    public function getValue(): int|string
    {
        return $this->value;
    }

    public function addEdge(Edge $edge): static
    {
        $this->edges[] = $edge;

        return $this;
    }

    /**
     * @return array<Edge>
     */
    public function getEdges(): array
    {
        return $this->edges;
    }

    public function addParent(Node $parent, Edge $parentEdge): static
    {
        $this->parents[$parent->getValue()] = $parentEdge;

        return $this;
    }

    /**
     * @return array<int|string, Edge>
     */
    public function getParents(): array
    {
        return $this->parents;
    }
}