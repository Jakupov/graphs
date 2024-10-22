<?php

namespace Jakupov\Graphs;

use Jakupov\Graphs\Containers\Queue;

class Algorithms
{
    /**
     * @param Node $current
     * @param Graph $visited
     * @param callable|null $startingCallback called at the beginning of the function. Passed params: Node $current
     * @param callable|null $depthCallback called when recursive call executed. Passed params: Node $current, result of recursive call
     * @param callable|null $endingCallback called at the end of the function. Passed params: Node current
     * @return mixed|void
     */
    public static function DFS(
        Node $current,
        Graph $visited,
        callable $startingCallback = null,
        callable $depthCallback = null,
        callable $endingCallback = null,
    )
    {
        if ($startingCallback) {
            $result = $startingCallback($current);
            if ($result !== null) {
                return $result;
            }
        }

        $visited->getOrAddNode($current);

        /** @var Edge $edge */
        foreach ($current->getEdges() as $edge) {
            $adjacent = $edge->getAdjacent();
            if (!isset($visited[$edge->getAdjacent()->getValue()])) {
                if ($depthCallback) {
                    $result = $depthCallback($current, self::DFS($adjacent, $visited, $startingCallback, $depthCallback, $endingCallback));
                    if ($result !== null) {
                        return $result;
                    }
                } else {
                    self::DFS($adjacent, $visited, $startingCallback, $depthCallback, $endingCallback);
                }
            }
        }

        if ($endingCallback) {
            $result = $endingCallback($current);
            if ($result !== null) {
                return $result;
            }
        }
    }

    /**
     * @param Node $starting
     * @param Node $needle
     * @return BacktrackNode|null
     */
    public static function BFS(
        Node $starting,
        Node $needle,
    ): ?BacktrackNode
    {
        $visited = new Graph();
        $queue = new Queue();
        $queue->enqueue(new BacktrackNode($starting, null));
        $visited->getOrAddNode($starting);
        while (!$queue->isEmpty()) {
            /** @var BacktrackNode $currentRow */
            $currentRow = $queue->dequeue();
            $current = $currentRow->getCurrent();

            if ($current->getValue() === $needle->getValue()) {
                return $currentRow;
            }

            foreach ($current->getEdges() as $edge) {
                $adjacent = $edge->getAdjacent();
                if (!isset($visited[$adjacent->getValue()])) {
                    if ($adjacent->getValue() === $needle->getValue()) {
                        return new BacktrackNode($adjacent, $currentRow);
                    }
                    $queue->enqueue(new BacktrackNode($adjacent, $currentRow));
                }
            }

            $visited->getOrAddNode($current);
        }
        return null;
    }

    public static function Dijkstra(
        Graph $graph,
        Node $starting,
        Node $needle,
    ): ?Path
    {
        $notVisited = clone $graph;
        $totalWeight = [];
        foreach ($graph as $node) {
            $totalWeight[$node->getValue()] = PHP_INT_MAX - 1;
        }

        $totalWeight[$starting->getValue()] = 0;

        self::dijkstraCalculateWeight($notVisited, $totalWeight);

        if ($totalWeight[$needle->getValue()] === PHP_INT_MAX - 1) {
            return null;
        }

        return self::dijkstraGetShortestPath($graph, $starting, $needle, $totalWeight);
    }

    /**
     * @param Graph $notVisited
     * @param int[] $totalWeight
     * @return void
     */
    private static function dijkstraCalculateWeight(Graph $notVisited, array &$totalWeight): void
    {
        while (count($notVisited) > 0) {
            $node = self::dijkstraGetClosestNode($notVisited, $totalWeight);

            if ($totalWeight[$node->getValue()] === PHP_INT_MAX - 1) {
                return;
            }

            foreach ($node->getEdges() as $edge) {
                $adjacent = $edge->getAdjacent();
                if (isset($notVisited[$adjacent->getValue()])) {
                    $weightToNode = $totalWeight[$node->getValue()] + $edge->getWeight();
                    if ($weightToNode < $totalWeight[$adjacent->getValue()]) {
                        $totalWeight[$adjacent->getValue()] = $weightToNode;
                    }
                }
            }

            unset($notVisited[$node->getValue()]);
        }
    }

    /**
     * @param Graph $graph
     * @param Node $starting
     * @param Node $needle
     * @param int[] $totalWeight
     * @return Path
     */
    private static function dijkstraGetShortestPath(Graph $graph, Node $starting, Node $needle, array &$totalWeight): Path
    {
        $path = new Path();
        $node = $needle;
        while ($node->getValue() !== $starting->getValue()) {
            $minTime = $totalWeight[$node->getValue()];
            $path->unshift($node);
            /**
             * @var int|string $parent
             * @var Edge $parentEdge
             */
            foreach ($node->getParents() as $parent => $parentEdge) {
                if (!isset($totalWeight[$parent])) {
                    continue;
                }
                $isPrevious = $parentEdge->getWeight() + $totalWeight[$parent] === $minTime;
                if ($isPrevious) {
                    unset($totalWeight[$node->getValue()]);
                    $node = $graph[$parent];
                    break;
                }
            }
        }

        $path->unshift($node);

        return $path;
    }

    /**
     * @param Graph $notVisited
     * @param int[] $totalWeight
     * @return Node
     */
    private static function dijkstraGetClosestNode(Graph $notVisited, array $totalWeight): Node
    {
        $closestNode = new Node('');
        $minTime = PHP_INT_MAX;

        /** @var Node $node */
        foreach ($notVisited as $node) {
            $time = $totalWeight[$node->getValue()];
            if ($time < $minTime) {
                $closestNode = $node;
                $minTime = $time;
            }
        }

        return $closestNode;
    }
}