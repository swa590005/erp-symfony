<?php
declare(strict_types=1);
namespace App\Service;

final class RoundedLineTotalCalculator implements LineTotalCalculatorInterface
{
    /**
     * Line totals rounded up to whole euro, for budget estimates.
     */
    public function addLinesTotal( array $lines):array
    {
        // Take each line, keep its data, and add lineTotal (quantity × unitPrice) into a new array
        return array_map(fn(array $total) =>[...$total, 'lineTotal' => ceil($total['quantity']*$total['unitPrice']) ] , $lines);
    }
}
