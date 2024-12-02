<?php
function parseDataFile(string $filePath): array
{
    if (!file_exists($filePath)) {
        throw new InvalidArgumentException("File not found: $filePath");
    }

    $fileContent = trim(file_get_contents($filePath));
    $lines = explode("\n", $fileContent);

    $firstList = [];
    $secondList = [];

    foreach ($lines as $line) {
        $result = explode('   ', $line);
        if (count($result) < 2) {
            error_log("Invalid line format: $line");
            continue;
        }
        $firstList[] = (int) $result[0];
        $secondList[] = (int) $result[1];
    }

    sort($firstList);
    sort( $secondList);

    return [$firstList, $secondList];
}

$data = parseDataFile("input.txt");

[$firstList, $secondList] = $data;

$apparitions = array_count_values($secondList);

$sum = array_reduce($firstList, function ($carry, $item) use ($apparitions) {
    return $carry + $item * ($apparitions[$item] ?? 0);
}, 0);

echo $sum;