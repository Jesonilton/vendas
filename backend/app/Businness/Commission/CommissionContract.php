<?php
namespace App\Businness\Commission;

interface CommissionContract
{
    public function calculate(): float;
}