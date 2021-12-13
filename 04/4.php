<?php

class BingoCard {
    public array $rows;
    public array $columns;
    public array $allNumbers;
    public function bingo() {
        foreach (array_merge($this->rows, $this->columns) as $set) {
            if ($this->checkSet($set)) {
                return true;
            }
        }
    }

    function checkSet(array $set) {
        return array_reduce(
            $set,
            function ($setState, BingoCardNumber $bingoCardNumber) {
                return $setState && $bingoCardNumber->marked;
            },
            true
        );
    }

    public function getUnmarkedSum() {
        return array_reduce(
            $this->allNumbers,
            function ($sum, BingoCardNumber $bingoCardNumber) {
                return $sum + ($bingoCardNumber->marked ? 0 : $bingoCardNumber->number);
            },
            0
        );
    }
}

class BingoCardNumber {
    public function __construct(int $number, bool $marked = false) {
        $this->number = $number;
        $this->marked = $marked;
    }
    public bool $marked;
    public int $number;
}

$inputFile = file_get_contents('input');
$input = explode(PHP_EOL.PHP_EOL, $inputFile);

$draw = explode(',', array_shift($input));
$index = [];
$draw = array_map(
    function ($number) use (&$index) {
        $cardNumber = new BingoCardNumber((int) $number);
        $index[(int) $number] = $cardNumber;
        return $cardNumber;
    },
    $draw
);

foreach ($input as $boardNumber => $boardData) {
    $boards[$boardNumber] = new BingoCard();
    $rows = explode(PHP_EOL, $boardData);
    foreach ($rows as $rowNumber => $row) {

        $numbers = preg_split('/\s+/', $row, null, 1);
        foreach ($numbers as $colNumber => $number) {
            $boards[$boardNumber]->rows[$rowNumber][] = $index[(int) $number];
            $boards[$boardNumber]->columns[$colNumber][] = $index[(int) $number];
            $boards[$boardNumber]->allNumbers[] = $index[(int) $number];
        }
    }
}

foreach ($draw as $drawnNumber) {
    $drawnNumber->marked = true;
    foreach ($boards as $board) {
        if ($board->bingo()) {
            echo "bingo!".PHP_EOL;
            echo $board->getUnmarkedSum().PHP_EOL;
            echo $drawnNumber->number.PHP_EOL;
            echo "result: " . $board->getUnmarkedSum() * $drawnNumber->number.PHP_EOL;
            break 2;
        }
    }
}

$boardsWon = [];
foreach ($draw as $drawnNumber) {
    $drawnNumber->marked = true;
    foreach ($boards as $boardNumber => $board) {
        if ($board->bingo()) {
            unset($boards[$boardNumber]);
            $boardsWon[] = $board;
        }
        if (empty($boards)) {
            break 2;
        }
    }
}

$board = end($boardsWon);
echo "bingo!".PHP_EOL;
echo $board->getUnmarkedSum().PHP_EOL;
echo $drawnNumber->number.PHP_EOL;
echo "result: " . $board->getUnmarkedSum() * $drawnNumber->number.PHP_EOL;

