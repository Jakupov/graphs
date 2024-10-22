<?php

namespace Jakupov\Graphs;

class Functions
{
    /**
     * @throws \Exception
     */
    public function findPath(Graph $graph, string|int $from, string|int $to): Path
    {
        if (!isset($graph[$from])) {
            throw new \Exception("Start point {$from} missing in presented graph");
        }

        if (!isset($graph[$to])) {
            throw new \Exception("Value {$to} missing in presented graph");
        }

        $startNode = $graph[$from];
        $endNode = $graph[$to];

        return $this->getPath($startNode, $endNode);
    }

    /**
     * @throws \Exception
     */
    public function findShortestPath(Graph $graph, string|int $from, string|int $to): ?Path
    {

        if (!isset($graph[$from])) {
            throw new \Exception("Start point {$from} missing in presented graph");
        }

        if (!isset($graph[$to])) {
            throw new \Exception("Value {$to} missing in presented graph");
        }

        $startNode = $graph[$from];
        $endNode = $graph[$to];

        if ($graph->isWeighted()) {
            $path = Algorithms::Dijkstra($graph, $startNode, $endNode);
        } else {
            $path = new Path();
            $result = Algorithms::BFS($startNode, $endNode);

            while (isset($result)) {
                $path->unshift($result->getCurrent());
                $result = $result->getPrevious();
            }
        }

        return $path;
    }

    /**
     * @return Path[]
     * @throws \Exception
     */
    public function findAllPaths(Graph $graph, string|int $start, string|int $needle): array
    {
        if (!isset($graph[$start])) {
            throw new \Exception("Start point {$start} missing in presented graph");
        }

        if (!isset($graph[$needle])) {
            throw new \Exception("Value {$needle} missing in presented graph");
        }

        $startNode = $graph[$start];
        $endNode = $graph[$needle];

        return $this->getAllPaths($startNode, $endNode);
    }

    protected function getPath(Node $startNode, Node $endNode): Path
    {
        $path = new Path;
        $visited = new Graph();
        Algorithms::DFS(
            $startNode,
            $visited,
            function (Node $current) use ($endNode, $path) {
                if ($current->getValue() === $endNode->getValue()) {
                    $path->unshift($current);
                    return true;
                }
            },
            function ($current, $result) use ($path) {
                if ($result) {
                    $path->unshift($current);
                    return true;
                }
            },
            function () {
                return false;
            },
        );

        return $path;
    }

    /**
     * @param Node $startNode
     * @param Node $endNode
     * @return Path[]
     */
    private function getAllPaths(Node $startNode, Node $endNode): array
    {
        $paths = [];
        $visited = new Graph();

        Algorithms::DFS(
            $startNode,
            $visited,
            function (Node $current) use ($endNode, $visited, &$paths) {
                if ($current->getValue() === $endNode->getValue()) {
                    $path = new Path();
                    foreach ($visited as $node) {
                        $path->push($node);
                    }
                    $path->push($current);
                    $paths[] = $path;
                }
            },
            null,
            function (Node $current) use ($visited) {
                unset($visited[$current->getValue()]);
            },
        );
        return $paths;
    }

    public function traverseGraph(
        Graph $graph,
        callable $startingCallback = null,
        callable $depthCallback = null,
        callable $endingCallback = null,
    ): void
    {
        $visited = new Graph();

        /** @var Node $node */
        foreach ($graph as $node) {
            if (!isset($visited[$node->getValue()])) {
                Algorithms::DFS($node, $visited, $startingCallback, $depthCallback, $endingCallback);
            }
        }
    }
}