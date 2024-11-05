<?php

namespace Jakupov\Graphs\Tests;

use Jakupov\Graphs\Functions;
use Jakupov\Graphs\Graph;
use Jakupov\Graphs\Node;
use Jakupov\Graphs\Path;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProviderExternal;

final class FunctionsTest extends TestCase
{
    /**
     * @param Path $path
     * @return array<int|string>
     */
    private function getPathValues(Path $path): array
    {
        $values = [];
        foreach ($path->asArray() as $node) {
            $values[] = $node->getValue();
        }

        return $values;
    }

    /**
     * @param array<array{Node|int|string|null, Node|int|string|null, int}> $data
     * @param array<int|string> $exists
     * @param int $shortest
     * @param array<array<int|string>> $list
     * @return void
     * @throws \Exception
     */
    #[DataProviderExternal(ExternalGraphProvider::class, 'unweightedDataProvider')]
    function testFindPath($data, $exists, $shortest, $list): void
    {
        $graph = new Graph();
        $graph->create($data);
        list($from, $to) = $exists;
        $functions = new Functions();

        $result = $functions->findPath($graph, $from, $to);

        self::assertInstanceOf(Path::class, $result);

        $values = $this->getPathValues($result);

        self::assertContains($values, $list);
        self::assertSame($from, $values[0]);
        self::assertSame($to, $values[count($values) - 1]);
    }

    /**
     * @param array<array{Node|int|string|null, Node|int|string|null, int}> $data
     * @param array<int|string> $exists
     * @param int $shortest
     * @param array<array<int|string>> $list
     * @return void
     * @throws \Exception
     */
    #[DataProviderExternal(ExternalGraphProvider::class, 'unweightedDataProvider')]
    function testFindShortestPathInUnweightedGraph($data, $exists, $shortest, $list): void
    {
        $graph = new Graph();
        $graph->create($data);
        list($from, $to) = $exists;
        $functions = new Functions();

        $result = $functions->findShortestPath($graph, $from, $to);

        self::assertInstanceOf(Path::class, $result);

        $values = $this->getPathValues($result);

        foreach ($result as $node) {
            self::assertInstanceOf(Node::class, $node);
        }

        self::assertContains($values, $list);
        self::assertSame($from, $values[0]);
        self::assertSame($to, $values[count($values) - 1]);
        self::assertCount($shortest, $values);
    }

    /**
     * @param array<array{Node|int|string|null, Node|int|string|null, int}> $data
     * @param array<int|string> $exists
     * @param int $shortest
     * @param array<array<int|string>> $list
     * @return void
     * @throws \Exception
     */
    #[DataProviderExternal(ExternalGraphProvider::class, 'weightedDataProvider')]
    function testFindShortestPathInWeightedGraph($data, $exists, $shortest, $list): void
    {
        $this->testFindShortestPathInUnweightedGraph($data, $exists, $shortest, $list);
    }

    /**
     * @param array<array{Node|int|string|null, Node|int|string|null, int}> $data
     * @param array<int|string> $exists
     * @param int $shortest
     * @param array<array<int|string>> $list
     * @return void
     * @throws \Exception
     */
    #[DataProviderExternal(ExternalGraphProvider::class, 'unweightedDataProvider')]
    function testFindAllPaths($data, $exists, $shortest, $list): void
    {
        $graph = new Graph();
        $graph->create($data);
        list($from, $to) = $exists;
        $functions = new Functions();

        $result = $functions->findAllPaths($graph, $from, $to);

        self::assertIsArray($result);
        self::assertCount(count($list), $result);

        foreach ($result as $path) {
            self::assertInstanceOf(Path::class, $path);

            $values = $this->getPathValues($path);

            self::assertContains($values, $list);
            self::assertSame($from, $values[0]);
            self::assertSame($to, $values[count($values) - 1]);
        }
    }
}