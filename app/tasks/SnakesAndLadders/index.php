<?php

$mapSquares = [
    [[-1,-1,-1,-1,-1,-1],[-1,-1,-1,-1,-1,-1],[-1,-1,-1,-1,-1,-1],[-1,35,-1,-1,13,-1],[-1,-1,-1,-1,-1,-1],[-1,15,-1,-1,-1,-1]],
    [[-1,-1],[-1,3]],
    [[-1,-1],[-1,1]],
];

$mapOutputs = [
    4,
    1,
    1,
];

function checkOutput(array $real, array $expected): array
{
    $result = [];

    foreach ($real as $key => $value) {
        $result[] = $expected[$key] === $value;
    }

    return $result;
}


function passThroughSquares(array $mapSquares): array
{
    $output = [];

    foreach ($mapSquares as $case) {
        $output[] = 1;
    }

    return $output;
}

$result = passThroughSquares($mapSquares);
$checked = checkOutput($result, $mapOutputs);

var_dump($result);
var_dump($checked);