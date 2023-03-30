<?php


/**
 * @param Integer[][] $board
 * @return Integer
 */
function snakesAndLadders($board) {
    $line = decodeToOneLine($board);
var_dump($line);
    $passed = [];

    $countTry = 0;

    for ($i = 0; $i < count($line);) {
        for ($j = 0; $j < 7 && $i < count($line); $j++) {
            if (in_array($line[$i], $passed)) {
                $i++;
                continue;
//                return -1;
            }

            if ($line[$i] != -1) {
                if (abs($line[$i] - 1 - $i) != 1) {
                    $j = 0;
                    $countTry++;
                }
                $i = $line[$i] - 1;
            } else {
                $i++;
            }

            $passed[] = $i;
        }
        $countTry++;
    }

    return $countTry;
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


$mapQueues = [
    [[-1,-1,-1,-1,-1,-1],[-1,-1,-1,-1,-1,-1],[-1,-1,-1,-1,-1,-1],[-1,35,-1,-1,13,-1],[-1,-1,-1,-1,-1,-1],[-1,15,-1,-1,-1,-1]],
    [[-1,-1],[-1,3]],
    [[-1,-1,-1],[-1,9,8],[-1,8,9]],
    [[-1,-1],[-1,1]],
];

$mapOutput = [
    4, 1, 1, 1,
//    4
//    1
];

$result = snakesAndLadders($mapQueues[2]);

var_dump($result);