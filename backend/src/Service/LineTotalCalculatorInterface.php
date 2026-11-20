<?php
declare(strict_types=1);
namespace App\Service;

interface LineTotalCalculatorInterface
{
    /**
     * Adds a 'lineTotal' (quantity × unitPrice) to every order line.
     *
     * @param array<array{quantity: int, unitPrice: float}> $lines other keys are kept
     * @return array the same lines, each with an extra 'lineTotal' key
     */
    public function addLinesTotal( array $lines):array;
}
