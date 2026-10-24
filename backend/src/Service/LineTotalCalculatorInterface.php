<?php
declare(strict_types=1);
namespace App\Service;

interface LineTotalCalculatorInterface
{
    public function addLinesTotal( array $lines):array;
}
