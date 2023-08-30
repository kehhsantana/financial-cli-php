<?php

namespace App\Service;

class GainService
{
    public function getGainByWeightedAverage($operation, $weightedAverage): float
    {
        return ($operation['unit-cost'] - $weightedAverage) * $operation['quantity'];
    }
}