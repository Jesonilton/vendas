<?php
namespace App\Repositories;

use App\Models\Commission;
use Illuminate\Database\Eloquent\Collection;

class CommissionRepository extends AbstractRepository
{
    protected function getModelClass(): string
    {
        return Commission::class;
    }

    public function all(): Collection
    {
        return $this->getModelClass()
                    ::with('seller')
                    ->get();
    }

    public function bySeller(int $sellerId): Collection
    {
        return $this->getModelClass()
                    ::where('seller_id', $sellerId)
                    ->with('sale')
                    ->get();
    }

    public function deleteAllFromSale(int $saleId): int
    {
        return $this->getModelClass()
                    ::where('sale_id', $saleId)
                    ->delete();
    }

    public function existsBySaleAndSeller($saleId, $sellerId): bool
    {
        return $this->getModelClass()
                    ::where('sale_id', $saleId)
                    ->where('seller_id', $sellerId)
                    ->exists();
    }

    public function findBySaleAndSeller($saleId, $sellerId): ?Commission
    {
        return $this->getModelClass()
                    ::where('sale_id', $saleId)
                    ->where('seller_id', $sellerId)
                    ->first();
    }
}