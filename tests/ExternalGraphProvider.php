<?php

namespace Jakupov\Graphs\Tests;

final class ExternalGraphProvider
{
    /**
     * @return mixed[]
     */
    public static function unweightedDataProvider(): array
    {
        return [
            'octopus' => [
                'data'     => [
                    [0, 1, 1],
                    [0, 2, 1],
                    [0, 3, 1],
                    [0, 4, 1],
                ],
                'exists'   => [0, 3],
                'shortest' => 2,
                'list'     => [[0, 3]],
            ],
            'spider' => [
                'data'     => [
                    [0, 1, 1],
                    [1, 2, 1],
                    [0, 3, 1],
                    [3, 4, 1],
                    [0, 5, 1],
                    [5, 6, 1],
                ],
                'exists'   => [0, 4],
                'shortest' => 3,
                'list'     => [[0, 3, 4]],
            ],
            'egg' => [
                'data'     => [
                    [0, 1, 1],
                    [1, 2, 1],
                    [2, 5, 1],
                    [0, 3, 1],
                    [3, 4, 1],
                    [4, 5, 1],
                ],
                'exists'   => [0, 5],
                'shortest' => 4,
                'list'     => [[0, 1, 2, 5], [0, 3, 4, 5]],
            ],
            'long leg' => [
                'data'     => [
                    [0, 1, 1],
                    [1, 2, 1],
                    [2, 3, 1],
                    [3, 4, 1],
                    [0, 4, 1],
                ],
                'exists'   => [0, 4],
                'shortest' => 2,
                'list'     => [[0, 1, 2, 3, 4], [0, 4]],
            ],
            'upside down' => [
                'data'     => [
                    [5, null, 1],
                    [4, 5, 1],
                    [3, 4, 1],
                    [2, 3, 1],
                    [1, 2, 1],
                ],
                'exists'   => [1, 5],
                'shortest' => 5,
                'list'     => [[1, 2, 3, 4, 5]],
            ],
            'circle' => [
                'data'     => [
                    [0, 1, 1],
                    [1, 2, 1],
                    [3, 4, 1],
                    [2, 3, 1],
                    [4, 0, 1],
                ],
                'exists'   => [0, 3],
                'shortest' => 4,
                'list'     => [[0, 1, 2, 3]],
            ],
        ];
    }

    /**
     * @return mixed[]
     */
    public static function weightedDataProvider(): array
    {
        return [
            'simple' => [
                'data'     => [
                    [0, 1, 1],
                    [0, 2, 2],
                    [1, 3, 1],
                    [2, 3, 1],
                ],
                'exists'   => [0, 3],
                'shortest' => 3,
                'list'     => [[0, 1, 3], [0, 2, 3]],
            ],
            'same weight' => [
                'data'     => [
                    [0, 1, 1],
                    [0, 2, 2],
                    [1, 3, 3],
                    [2, 3, 2],
                ],
                'exists'   => [0, 3],
                'shortest' => 3,
                'list'     => [[0, 1, 3], [0, 2, 3]],
            ],
            'traffic' => [
                'data'     => [
                    [0, 4, 8],
                    [0, 1, 1],
                    [1, 2, 2],
                    [2, 3, 2],
                    [3, 4, 2],
                ],
                'exists'   => [0, 4],
                'shortest' => 5,
                'list'     => [[0, 1, 2, 3, 4], [0, 4]],
            ],
        ];
    }
}