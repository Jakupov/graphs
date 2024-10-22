<?php

namespace Jakupov\Graphs;

class Edge
{
    protected Node $adjacent;
    protected int $weight;

    public function __construct(Node $node, int $weight)
    {
        $this->adjacent = $node;
        $this->weight = $weight;
    }

    public function getAdjacent(): Node
    {
        return $this->adjacent;
    }

    public function getWeight(): int
    {
        return $this->weight;
    }
}