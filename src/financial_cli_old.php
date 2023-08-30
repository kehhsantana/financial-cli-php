<?php

//está funcional
function calculateTaxes($operations): array
{
    $taxes = [];

    $totalStockQuantity = 0;
    $weightedAveragePrice = 0;
    $accumulatedLoss = 0;

    foreach ($operations as $operation) {
        if ($operation['operation'] == 'buy') {
            $totalStockQuantity += $operation['quantity'];
            $remainingStocks = $totalStockQuantity - $operation['quantity'];
            $newStocksWeightedAverage = $operation['quantity'] * $operation['unit-cost'];
            $weightedAveragePrice = number_format(
                (($remainingStocks * $weightedAveragePrice) + $newStocksWeightedAverage) / $totalStockQuantity,
                2,
                '.',
                ''
            );
            $tax =  number_format(0, 2, '.', '');
            $taxes[] = ['tax' => $tax];
        }
        if ($operation['operation'] == 'sell') {
            $totalStockQuantity -= $operation['quantity'];
            $gain = number_format(($operation['unit-cost'] - $weightedAveragePrice) * $operation['quantity'], 2, '.', '');

            if ($gain > 0 && $gain > $accumulatedLoss) {
                $gain = $gain - $accumulatedLoss;
                $tax = 0.2 * $gain;
                $tax = number_format($tax, 2, '.', '');
            } else {
                $tax =  number_format(0, 2, '.', '');
                $accumulatedLoss -= $gain;
            }

            if ($operation['unit-cost'] * $operation['quantity'] <= 20000) {
                $tax =  number_format(0, 2, '.', '');
            }

            $taxes[] = ['tax' => $tax];
        }
    }

    return $taxes;
}

while ($line = trim(fgets(STDIN))) {
    if (empty($line)) {
        break;
    }

    $operationsList = json_decode($line, true);

    $taxes = calculateTaxes($operationsList);
    echo json_encode($taxes);
}
