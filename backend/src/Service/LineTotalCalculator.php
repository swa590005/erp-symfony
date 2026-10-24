<?php
declare(strict_types=1);
namespace App\Service;

final class LineTotalCalculator
{
    public function addLinesTotal( array $lines):array
    {
        // Take each line, keep its data, and add lineTotal (quantity × unitPrice) into a new array
        return array_map(fn(array $total) =>[...$total, 'lineTotal' => $total['quantity']*$total['unitPrice']] , $lines);
    }
}
