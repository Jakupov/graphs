<?php

namespace Jakupov\Graphs;

class BacktrackNode
{
    public function __construct(protected Node $current, protected ?BacktrackNode $previous = null)
    {
    }
    public function getCurrent(): Node
    {
        return $this->current;
    }
    public function getPrevious(): ?BacktrackNode
    {
        return $this->previous;
    }
}