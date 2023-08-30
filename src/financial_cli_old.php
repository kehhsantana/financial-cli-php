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
            $weightedAveragePrice = (
                ($remainingStocks * $weightedAveragePrice) +
                $newStocksWeightedAverage
            ) / $totalStockQuantity;
            $tax = 0;
            $taxes[] = ['tax' => $tax];
        }
        if ($operation['operation'] == 'sell') {
            $totalStockQuantity -= $operation['quantity'];
            $gain = ($operation['unit-cost'] - $weightedAveragePrice) * $operation['quantity'];

            if ($gain > 0 && $gain > $accumulatedLoss) {
                $gain = $gain - $accumulatedLoss;
                $tax = 0.2 * $gain;
            } else {
                $tax = 0;
                $accumulatedLoss -= $gain;
            }

            if ($operation['unit-cost'] * $operation['quantity'] <= 20000) {
                $tax = 0;
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
