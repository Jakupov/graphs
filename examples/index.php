<?php

use Jakupov\Graphs\Graph;
use Jakupov\Graphs\Functions;

const ROOT_DIR = __DIR__ . '/../';

require_once ROOT_DIR . 'vendor/autoload.php';

$data = [
    [7,5,1],
    [7,6,1],
    [6,4,1],
    [8,null,1],
    [5,9,1],
    [9,4,1],
    [10,11,1],
];

$graph = new Graph();
$graph->create($data);

$helper = new Functions();
$start = 7;
$needle = 4;

echo 'Find any path from start to needle' . PHP_EOL;
$result = $helper->findPath($graph, $start,  $needle);

echo $result . PHP_EOL;

echo 'Find all paths from start to needle' . PHP_EOL;
$result = $helper->findAllPaths($graph, $start, $needle);

foreach ($result as $path) {
    echo $path . PHP_EOL;
}

echo 'Find shortest path from start to needle' . PHP_EOL;
$result = $helper->findShortestPath($graph, $start, $needle);

echo $result . PHP_EOL;
