<?php

class Solution {
    /**
     * @param Integer[][] $board
     * @return Integer
     */
    function snakesAndLadders($board) {
        $start = 1;
        $end = count($board) * count($board[0]) - 1;

        $line = $this->decodeToOneLine($board);
        $line[$end] = 0;
        return json_encode($line);
        $result = $this->calculate($line, $end);

        return json_encode($result > 0 ? $result : (int) round(($end + 1) / 6));
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

        return $this->countSteps($lines);
    }

    function countSteps(array $lines): array
    {
        foreach ($lines as $index => $square) {
            if ($square > 0) {
                $lines[$index] = $index >= $square ? $index - $square + 1 : $index - $square;
            }
        }

        return $lines;
    }

    function calculate(array $line, int $end): int
    {
        $count = 0;

        for ($countCube = 0, $index = 0; $index < $end - 1 && $end > 0; $index++) {
            // var_dump('begin: ' . $index);
            $end += $line[$index];

            if ($line[$index] != -1) {
                $index -= $line[$index] < 0 ? $line[$index] + 1 : $line[$index];

                $count += $countCube > 1 ? $countCube : 1;
                $countCube = 0;
            }
            // var_dump('end: ' . $index);
            if ($countCube > 6) {
                $index += 6;
                $countCube = 0;
                $count++;
            }

            $countCube++;
        }

        return $count;
    }
}

$board = [[-1,7,-1],[-1,6,9],[-1,-1,2]];

$result = (new Solution())->snakesAndLadders($board);