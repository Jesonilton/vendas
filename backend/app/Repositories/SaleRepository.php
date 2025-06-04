<?php 
namespace App\Repositories;

use App\Models\Sale;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class SaleRepository extends AbstractRepository
{
    protected function getModelClass(): string
    {
        return Sale::class;
    }

    public function all(): Collection
    {
        return $this->getModelClass()
                    ::with('seller')
                    ->get();
    }

    public function bySeller($sellerId): Collection
    {
        return $this->getModelClass()
                    ::where('seller_id', $sellerId)
                    ->with('seller')->get();
    }
}