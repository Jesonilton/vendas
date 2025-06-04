<?php 
namespace App\Repositories;

use App\Models\Seller;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;

class SellerRepository extends AbstractRepository
{
    public function create(array $data): Seller
    {
        return $this->getModelClass()
                    ::create($data);
    }

    protected function getModelClass(): string
    {
        return Seller::class;
    }

    public function all(): Collection
    {
        return $this->getModelClass()
                    ::with('sales')
                    ->get();
    }

    public function hasAnySales(int $sallerId): bool
    {
        return $this->getModelClass()
                    ::whereHas('sales')
                    ->where('id', $sallerId)
                    ->exists();
    }

    public function allWithSalesAndCommissionsTotal(): Collection
    {
        return $this->getModelClass()
                    ::select(
                        'sellers.id', 
                        'sellers.name', 
                        'sellers.email', 
                        DB::raw('count(s.id) as sales_count'), 
                        DB::raw('coalesce(sum(s.amount), 0) as sales_amount'), 
                        DB::raw('coalesce(sum(c.commission), 0) as commissions_amount')
                    )
                    ->leftJoin('sales as s', function($q) {
                        return $q->on('sellers.id', 's.seller_id')
                                ->whereNull('s.deleted_at');
                    })
                    ->leftJoin('commissions as c', function($q) {
                        return $q->on('c.seller_id', 'sellers.id')
                                ->on('c.sale_id', 's.id')
                                ->whereNull('c.deleted_at');
                    })
                    ->groupBy('sellers.id')
                    ->get();
    }

    public function getTrashedSallerByEmail(string $email)
    {
        return $this->getModelClass()
                    ::onlyTrashed()
                    ->where('email', $email)
                    ->first();
    }
}