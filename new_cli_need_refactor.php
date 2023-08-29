<?php

//Começando a estrutura - não está funcionando ainda
use App\Service\GainService;
use App\Service\OperationTaxService;
use App\Service\TaxService;

$gainService = new GainService();
$taxService = new TaxService();
$operationTaxService = new OperationTaxService($taxService, $gainService);

while ($line = trim(fgets(STDIN))) {

    if (empty($line)) {
        break;
    }

    $operationsList = [];
    $operationsList = json_decode($line, true);

    $taxes = $operationTaxService->calculateTax($operationsList);
    echo json_encode($taxes);
}