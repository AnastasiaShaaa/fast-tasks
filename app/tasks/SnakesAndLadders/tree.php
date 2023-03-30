<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/../vendor/autoload.php';

use App\SnakesAndLadders\Graph;

$mapQueues = [
    [[-1,-1,-1,-1,-1,-1],[-1,-1,-1,-1,-1,-1],[-1,-1,-1,-1,-1,-1],[-1,35,-1,-1,13,-1],[-1,-1,-1,-1,-1,-1],[-1,15,-1,-1,-1,-1]],
    [[-1,-1],[-1,3]],
    [[-1,-1],[-1,1]],
];

$mapOutput = [
    4, 1, 1,
//    4
//    1
];

$queueLast = [];

$queueRaw = [];

/**
 * @param array[][] $raw
 * @return array
 */
function prepareIntoLine(array $raw): array
{
    return decodeToOneLine($raw);
}

function decodeToOneLine(array $board): array
{
    $isCh = !(count($board) & 1);

    for ($i = count($board) - 1, $lines = []; $i != -1; $i--) {
        if ($isCh) {
            $line = $i & 1 ? $board[$i] : array_reverse($board[$i]);
        } else {
            $line = ($i & 1) ? array_reverse($board[$i]) : $board[$i];
        }
        $lines = array_merge($lines, $line);
    }

    return $lines;
}

function countSteps(array $lines): array
{
    return array_map(
        fn ($index, $square) => $square > 0 ? $index - $square + 1 : $lines[$index], array_keys($lines), $lines
    );
}

function prepareQueue(array $raw): array
{
    $queue = [];

    $line = prepareIntoLine($raw);
    $line = countSteps($line);

    foreach ($line as $index => $step) {
        $queue[] = prepareGraph($index, $step);
    }
//var_dump($queue);
    return $queue;
}

function prepareGraph(int $index, int $step): Graph
{
    return new Graph($index, $step);
}

/**
 * @param Graph[] $queue
 * @return int
 */
function passThroughQueue(array $queue, int $step, array $condition): int
{
    $countSteps = 0;

    for ($i = 0, $indexCondition = 0; $i < count($queue);) {
        $graph = $queue[$i];

//        var_dump($i);
//        var_dump($graph->getStep());
//        var_dump($graph->getNextIndex());



        if ($graph->isPassed()) {
            return $countSteps;
        }

        $queue[$i] = $graph->setPassed();

        if ($graph->getStep() != -1) {
            if (key_exists($indexCondition, $condition) && (int) $condition[$indexCondition]) {
                $i = $graph->getNextIndex();
                $countSteps++;
                continue;
            }
        }

        $i = $i + 1;

//        if (count($queue) === $i + 1) {
//            var_dump('TEST ' . $i);
//            $countSteps++;
//            continue;
//        }

        if ($i % $step == 0) {
            $countSteps++;
        }
    }

    if ($countSteps === 0 && count($queue) == $i) {
        $countSteps++;
    }

    return $countSteps != 0 ? $countSteps : -1;
}

function isLast(int $index): bool
{
    return true;
}

/**
 * @return int[]
 */
function passThroughQueues(array $map): array
{
    $output = [];

    foreach ($map as $case) {
        $test = [];

        $queue = prepareQueue($case);

        $countConditions = calculateConditions($queue);
        $conditions = createNodesConditions($countConditions);

//        for ($step = 1; $step <= 6 && $step < count($queue); $step++) {
            foreach ($conditions as $condition) {
                $condition = str_split($condition);

                $test[] = passThroughQueue($queue, $step = 6, $condition);
                $queue = clearPassed($queue);
            }
//        }
var_dump($test);

        $t = 0;
        foreach ($test as $value) {
            if ($value == -1) {
                $t++;
            }
        }

        $output[] = $t == count($test) ? -1 : min(array_filter($test, fn ($value) => $value != -1));
    }

    return $output;
}

function checkOutput(array $real, array $expected): array
{
    $result = [];

    foreach ($real as $key => $value) {
        $result[] = $expected[$key] === $value;
    }

    return $result;
}

/**
 * @param Graph[] $queue
 * @return array
 */
function clearPassed(array $queue): array
{
    return array_map(fn (Graph $graph) => $graph->clearPassed(), $queue);
}

/**
 * @param Graph[] $queue
 * @return array
 */
function calculateConditions(array $queue): int
{
    $countConditions = 0;

    foreach ($queue as $graph) {
        if ($graph->getStep() != -1) {
            $countConditions++;
        }
    }

    return $countConditions;
}

function createNodesConditions(int $countNodes): array
{
    $result = $tempArr = [];

    for ($j = $countNodes - 1; $j > -1; $j--) {
        $temp = decbin(1 << $j);
        $result[] = strlen($temp) < $countNodes
            ? str_pad($temp, $countNodes, '0', STR_PAD_LEFT)
            : $temp;
    }

    for ($i = 0; $i < count($result); $i++) {
        for ($j = count($result) - 1; $j > $i; $j--) {
            $test = decbin(bindec($result[$i]) | bindec($result[$j]));

            $tempArr[] = strlen($test) < $countNodes
                ? str_pad($test, $countNodes, '0', STR_PAD_LEFT)
                : $test;
        }
    }

    $result = array_merge($result, $tempArr);
    $result[] = str_pad('', $countNodes, '1');
    $result[] = str_pad('', $countNodes, '0');

    return $result;
}

$result = passThroughQueues($mapQueues);
var_dump(checkOutput($result, $mapOutput));

var_dump($result);
