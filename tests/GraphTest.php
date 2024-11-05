<?php

namespace Jakupov\Graphs\Tests;

use Jakupov\Graphs\Graph;
use Jakupov\Graphs\Node;
use PHPUnit\Framework\TestCase;

final class GraphTest extends TestCase
{
    /**
     * @param Graph $graph
     * @param array<int|string> $nodes
     * @return void
     */
    private static function checkNodesInGraph($graph, $nodes): void
    {
        foreach ($nodes as $value) {
            self::assertArrayHasKey($value, $graph);
            self::assertInstanceOf(Node::class, $graph[$value]);
        }
    }

    /**
     * @param Node $node
     * @param array<int|string> $adjacentValues
     * @param int[] $weights
     * @return void
     */
    private static function checkEdgesInNode(Node $node, $adjacentValues, $weights): void
    {
        foreach ($node->getEdges() as $index => $edge) {
            self::assertEquals($weights[$index], $edge->getWeight());
            self::assertEquals($adjacentValues[$index], $edge->getAdjacent()->getValue());
        }
    }

    function testDirectedGraphCreation(): void
    {
        $data = [
            ['test1', 'test2', 1],
            ['test1', 'test3', 2],
            ['test4', null, 1],
        ];

        $graph = new Graph();
        $graph->create($data);

        $valuesToCompare = array_filter($data, function ($row) {
            return $row[0] === 'test1';
        });

        self::checkNodesInGraph($graph, ['test1','test2','test3','test4']);
        self::assertCount(2, $graph['test1']->getEdges());
        self::checkEdgesInNode($graph['test1'], array_column($valuesToCompare, 1), array_column($valuesToCompare, 2));

        self::assertEmpty($graph['test2']->getEdges());
        self::assertEmpty($graph['test3']->getEdges());
        self::assertEmpty($graph['test4']->getEdges());
    }

    function testUndirectedGraphCreation(): void
    {
        $data = [
            ['test1', 'test2', 1],
            ['test1', 'test3', 2],
        ];

        $graph = new Graph(false);
        $graph->create($data);

        $valuesToCompare = array_filter($data, function ($row) {
            return $row[0] === 'test1';
        });

        self::checkNodesInGraph($graph, ['test1','test2','test3']);
        self::assertCount(2, $graph['test1']->getEdges());
        self::checkEdgesInNode($graph['test1'], array_column($valuesToCompare, 1), array_column($valuesToCompare, 2));

        self::assertNotEmpty($graph['test2']->getEdges());
        self::assertEquals('test1', $graph['test2']->getEdges()[0]->getAdjacent()->getValue());
        self::assertNotEmpty($graph['test3']->getEdges());
        self::assertEquals('test1', $graph['test3']->getEdges()[0]->getAdjacent()->getValue());
    }

    function testNodeAddition(): void
    {
        $graph = new Graph();
        $node1 = new Node('test');

        $node2 = $graph->getOrAddNode($node1);
        $node3 = $graph->getOrAddNode('test');

        self::assertEquals($node1, $node2);
        self::assertEquals($node1, $graph['test']);
        self::assertEquals($node1, $node3);
    }
}