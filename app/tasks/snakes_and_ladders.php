<?php

class Solution {
    /**
     * @param Integer[][] $board
     * @return Integer
     */
    function snakesAndLadders(array $board): int
    {
        $end = count($board) * count($board[0]) - 1;

        $line = $this->decodeToOneLine($board);
        $line[$end] = 0;

        if ($this->isCycle($line)) {
            return -1;
        }

        $line = $this->countSteps($line);

        $steps = $this->createNodesSteps($line);
        $conditions = $this->createNodesConditions(count($steps));

        foreach ($conditions as $condition) {
            $condition = str_split($condition);
            $result[] = $this->calculate($line, $end, $condition);
        }
        var_dump(json_encode($line));
//        $result[] = $this->calculate($line, $end, str_split($conditions[6]));

        return min($result);
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

    function calculate(array $line, int $end, array $condition): int
    {
        $count = $indexCondition = 0;
        $countSquare = $end;

        for ($countCube = $index = 0; $index < $countSquare - 1; $index++) {
//            var_dump("---------------");
//            var_dump('countCube: ' . $countCube);
//            var_dump('index: ' . $index);
//            var_dump('value: ' . $line[$index]);
//            var_dump('count: ' . $count);
//            var_dump('countCube: ' . $countCube);

            if ($line[$index] != -1) {
                if (key_exists($indexCondition, $condition) && (int) $condition[$indexCondition]) {
//                    $index -= $line[$index] < 0 ? $line[$index] + 1 : $line[$index];
                    $index -= $line[$index] + 1;

                    $count += 1;
            var_dump('index: ' . $index);
            var_dump('count: ' . $count);

                    $countCube = 0;
                }
                $indexCondition++;
            }

            if ($countCube == 6) {
                $countCube = 1;
                $count++;
            } else {
                $countCube++;
            }

            if (($index + 1) === ($countSquare - 1)) {
                $count++;
            }
//            var_dump('count: ' . $count);
        }
        var_dump($count);
        return $count;
    }

    public function createNodesSteps(array $lines): array
    {
        $nodes = [];

        foreach ($lines as $key => $value) {
            if ($value != -1 && $value != 0) {
                $node['current'] = $key;
                $node['next'] = $value;
                $nodes[] = $node;
            }
        }

        return $nodes;
    }

    public function createNodesConditions(int $countNodes): array
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

    public function isCycle(array $board): bool
    {
        for ($repeat = 0, $i = 1; $i < count($board) - 1; $i++) {
            if ($board[$i] == $board[$i - 1] && $board[$i] > 0) {
                $repeat += 1;
            } else {
                $repeat = 0;
            }

            if ($repeat == 5) {
                return true;
            }
        }

        return false;
    }
}

//$board = [[-1,7,-1],[-1,6,9],[-1,-1,2]];
//$board = [[-1,4,-1],[6,2,6],[-1,3,-1]]; // output: 2
//$board = [[-1,-1,-1],[-1,9,8],[-1,8,9]]; // output: 1
//$board = [[1,1,-1],[1,1,1],[-1,1,1]]; // output: -1
$board = [[-1,-1],[-1,3]]; // output: 1
//$board = [[-1,-1,-1,-1,-1,-1],[-1,-1,-1,-1,-1,-1],[-1,-1,-1,-1,-1,-1],[-1,35,-1,-1,13,-1],[-1,-1,-1,-1,-1,-1],[-1,15,-1,-1,-1,-1]];
//$board = [[-1,-1,27,13,-1,25,-1],[-1,-1,-1,-1,-1,-1,-1],[44,-1,8,-1,-1,2,-1],[-1,30,-1,-1,-1,-1,-1],[3,-1,20,-1,46,6,-1],[-1,-1,-1,-1,-1,-1,29],[-1,29,21,33,-1,-1,-1]]; // output: 4
//$board = [[-1,-1,-1,-1,-1,-1,-1,-1,-1,79,-1,-1,-1],[-1,-1,-1,-1,109,-1,-1,-1,-1,-1,86,-1,-1],[-1,-1,115,148,-1,-1,25,-1,10,-1,16,7,25],[-1,-1,61,-1,91,75,-1,-1,-1,-1,-1,-1,79],[-1,42,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1],[-1,-1,-1,-1,-1,56,-1,34,-1,13,-1,-1,-1],[-1,110,-1,96,105,-1,-1,-1,40,-1,-1,-1,-1],[-1,-1,114,-1,-1,12,-1,32,-1,-1,-1,-1,-1],[-1,-1,-1,-1,-1,-1,-1,44,-1,-1,13,25,49],[-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1],[166,59,3,-1,27,-1,-1,-1,-1,-1,-1,-1,-1],[-1,99,-1,-1,-1,-1,-1,-1,70,-1,-1,-1,-1],[-1,-1,146,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1]];

$result = (new Solution())->snakesAndLadders($board);

var_dump($result);