<?php

const WIDTH = 101;
const HEIGHT = 103;
const INPUT_PATH = '14/input.txt';
const TIME = 100;

$input = trim(file_get_contents(INPUT_PATH));

$robots = array_map(
    function (string $robot) {
        preg_match_all('/\d+|-\d+/', $robot, $matches);

        return array_map('intval', $matches[0]);
    },
    explode("\n", $input)
);

$quadrants = [
    'topLeft' => 0,
    'topRight' => 0,
    'bottomLeft' => 0,
    'bottomRight' => 0,
];

foreach ($robots as [&$x, &$y, &$speedX, &$speedY]) {
    $x = ($x + TIME * ($speedX + WIDTH)) % WIDTH;
    $y = ($y + TIME * ($speedY + HEIGHT)) % HEIGHT;

    $centerRow = (HEIGHT - 1) / 2;
    $centerCol = (WIDTH - 1) / 2; 

    // Skip items on the center horizontal and vertical lines
    if ($y == floor($centerRow) || $y == ceil($centerRow) || $x == floor($centerCol) || $x == ceil($centerCol)) {
        continue;
    }

    $isTop = $y < $centerRow;
    $isLeft = $x < $centerCol;

    $quadrant = ($isTop ? 'top' : 'bottom') . ($isLeft ? 'Left' : 'Right');
    $quadrants[$quadrant]++;
}

$result = array_product($quadrants);

echo "Résultat: $result";
