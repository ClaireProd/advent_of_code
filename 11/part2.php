<?php

function blink($frequencies) {
    $new_frequencies = [];

    foreach ($frequencies as $stone => $count) {
        if ($stone == 0) {
            if (!isset($new_frequencies[1])) {
                $new_frequencies[1] = 0;
            }
            $new_frequencies[1] += $count;
        }

        elseif (strlen((string)$stone) % 2 == 0) {
            $s = (string)$stone;
            $mid = strlen($s) / 2;
            $left = (int)substr($s, 0, $mid);
            $right = (int)substr($s, $mid);
            
            if (!isset($new_frequencies[$left])) {
                $new_frequencies[$left] = 0;
            }
            if (!isset($new_frequencies[$right])) {
                $new_frequencies[$right] = 0;
            }
            $new_frequencies[$left] += $count;
            $new_frequencies[$right] += $count;
        }

        else {
            $new_stone = $stone * 2024;
            if (!isset($new_frequencies[$new_stone])) {
                $new_frequencies[$new_stone] = 0;
            }
            $new_frequencies[$new_stone] += $count;
        }
    }

    return $new_frequencies;
}

$filename = "11/input.txt";
$stones = explode(" ", trim(file_get_contents($filename)));

$frequencies = [];
foreach ($stones as $stone) {
    $stone = (int)$stone;
    if (!isset($frequencies[$stone])) {
        $frequencies[$stone] = 0;
    }
    $frequencies[$stone]++;
}

for ($i = 0; $i < 75; $i++) {
    $frequencies = blink($frequencies);
}

$total_stones = array_sum($frequencies);
echo "Total stones after 75 blinks: $total_stones\n";

?>
