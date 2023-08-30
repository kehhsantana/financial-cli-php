<?php

namespace App\Application\Service;

class OperationTaxService
{
    private const BUY_OPERATION = 'buy';
    private const SELL_OPERATION = 'sell';

    private TaxService $taxService;
    private GainService $gainService;

    public function __construct(
        TaxService $taxService,
        GainService $gainService
    ) {
        $this->taxService = $taxService;
        $this->gainService = $gainService;
    }

    public function calculateTax($operations)
    {
        $taxes = [];

        $totalStockQuantity = 0;
        $weightedAverage = 0;
        $accumulatedLoss = 0;

        foreach ($operations as $operation) {
            if ($operation['operation'] == self::BUY_OPERATION) {
                $totalStockQuantity += $operation['quantity'];
                $weightedAverage = $this->getWeightedAverage($weightedAverage, $operation, $totalStockQuantity);
                $tax = $this->taxService->getTaxBuyOperation();
                $taxes[] = $tax;
            }
            if ($operation['operation'] == self::SELL_OPERATION) {
                $totalStockQuantity -= $operation['quantity'];
                $gain = $this->gainService->getGainByWeightedAverage($operation, $weightedAverage);
                $tax = $this->taxService->getTaxSellOperation($operation, $gain, $accumulatedLoss);
                $taxes[] = $tax;
            }

            return $taxes;
        }
    }

    public function getWeightedAverage($weightedAverage, $operation, $totalStockQuantity): float
    {
        $remainingQuantity = $totalStockQuantity - $operation['quantity'];
        $weightedAverageNewStocks = $operation['quantity'] * $operation['unit-cost'];

        return (
            ($remainingQuantity * $weightedAverage) +
            $weightedAverageNewStocks
        ) / $totalStockQuantity;
    }
}