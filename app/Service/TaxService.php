<?php

namespace App\Service;

class TaxService
{
    private const NO_TAX = 0;
    private const ZERO = 0;
    private const LIMIT_TAX_EXEMPTION = 20000;
    private const TWENTY_PER_CENT = 0.2;

    private GainService $gainService;

    public function __construct(GainService $gainService) {
        $this->gainService = $gainService;
    }

    public function getTaxBuyOperation(): array
    {
        return ['tax' => self::NO_TAX];
    }

    public function getTaxSellOperation($operation, $gain, $accumulatedLoss): array
    {
        $tax = self::ZERO;

        if ($operation['unit-cost'] * $operation['quantity'] <= self::LIMIT_TAX_EXEMPTION) {
            $tax = self::NO_TAX;
        }

        $accumulatedLoss -= $gain;
        if ($gain > self::ZERO && $gain > $accumulatedLoss) {
            $gain = $gain - $accumulatedLoss;
            $tax = $this->calculateTaxGain($gain);
        }

        return ['tax' => $tax];
    }

    public function calculateTaxGain($gain): float
    {
        return self::TWENTY_PER_CENT * $gain;
    }
}