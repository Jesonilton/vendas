<?php
namespace App\Businness\Commission;

//strategy pattern
class SalesCommission implements CommissionContract
{
    function __construct(protected float $amount){}

    public function calculate(): float 
    {
        return $this->amount * 0.085;
    }
}