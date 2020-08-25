<?php

function maxProfit(array $pricesAndPurchases): int
{
    $maxProfit = 0;

    if (empty($pricesAndPurchases)) {
        return $maxProfit;
    }

    $days_count = count($pricesAndPurchases);
    $last_day = $days_count - 1;

    $selling_price = $pricesAndPurchases[$last_day]['price'];
    $purchased = $pricesAndPurchases[$last_day]['purchased'];
    $spent = $selling_price * $purchased;

    for ($day = $last_day - 1; $day >= 0; $day--) {
        if ($pricesAndPurchases[$day]['price'] > $selling_price) {
            $maxProfit += ($purchased * $selling_price) - $spent;
            $selling_price = $pricesAndPurchases[$day]['price'];
            $purchased = $pricesAndPurchases[$day]['purchased'];
            $spent = $selling_price * $purchased;
        } else {
            $purchased += $pricesAndPurchases[$day]['purchased'];
            $spent += $pricesAndPurchases[$day]['price'] * $pricesAndPurchases[$day]['purchased'];
        }
    }

    $maxProfit += ($purchased * $selling_price) - $spent;

    return $maxProfit;
}

function stringCost(string $src, string $tgt, int $insertionCost, int $deletionCost, int $replacementCost): int
{
    $height = strlen($src);
    $width = strlen($tgt);

    $matrix = [];
    $row = [0];
    for ($x = 1; $x <= $width; $x++) {
        $row[$x] = $row[$x-1] + $insertionCost;
    }
    $matrix[0] = $row;
    for ($y = 1; $y <= $height; $y++) {
        $row = [$matrix[$y-1][0] + $deletionCost];
        for ($x = 1; $x <= $width; $x++) {
            $row[$x] = min(
                $matrix[$y-1][$x] + $deletionCost,
                $row[$x-1] + $insertionCost,
                $matrix[$y-1][$x-1] + ($src[$y-1] === $tgt[$x-1] ? 0 : $replacementCost)
            );
        }
        $matrix[$y] = $row;
    }

    return $matrix[$height][$width];
}


function incrementalMedian(array $values): array
{
    $medians = [];

    if (empty($values)) {
        return [];
    }

    $values_count = count($values);
    $min_heap = new SplMinHeap();
    $max_heap = new SplMaxHeap();
    $value = $values[0];
    $min_heap->insert($value);
    $median = $value;
    $medians[] = $median;

    for ($i = 1; $i < $values_count; $i++) {
        $value = $values[$i];
        if ($value < $median) {
            $max_heap->insert($value);
        } else {
            $min_heap->insert($value);
        }
        $min_heap_size = $min_heap->count();
        $max_heap_size = $max_heap->count();
        if (abs($max_heap_size - $min_heap_size) > 1) {
            if ($max_heap_size > $min_heap_size) {
                $extracted = $max_heap->extract();
                $min_heap->insert($extracted);
                $min_heap_size++;
                $max_heap_size--;
            } else {
                $extracted = $min_heap->extract();
                $max_heap->insert($extracted);
                $max_heap_size++;
                $min_heap_size--;
            }
        }
        if ($max_heap_size >= $min_heap_size) {
            $median = $max_heap->top();
        } else {
            $median = $min_heap->top();
        }
        $medians[] = $median;
    }

    return $medians;
}