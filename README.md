## Graphs
This library is made for simple operations on graphs, such as finding a path from one node to another.

Example usage is shown in `./examples/index.php`

## Classes

**Graph** - Nodes container. Nodes to store must have unique values. It's required to have `O(1)`
complexity to access any node in graph and also for more easy definition (just by
their value)

**Node** - Graph node. By default, only value is required. Can have edges and links to parent nodes.

**Edge** - Nodes connector. Always directed and has link to adjacent node. Weight is represented by 
positive integer.

**Path** - Iterable nodes list. Used as a result presentation of path finding functions.

## Testing

`vendor/bin/phpunit -testdox tests`