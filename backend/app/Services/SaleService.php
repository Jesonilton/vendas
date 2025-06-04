<?php 
namespace App\Services;

use App\Models\Sale;
use Illuminate\Support\Facades\DB;
use App\Services\CommissionService;
use App\Repositories\SaleRepository;
use Illuminate\Support\Facades\Mail;
use App\Mail\DailyManagerSalesReport;
use App\Repositories\SellerRepository;
use App\Exceptions\SaleCreationException;
use App\Exceptions\SellerNotFoundException;
use Illuminate\Database\Eloquent\Collection;
use App\Businness\Commission\SalesCommission;
use App\Services\Contracts\SaleServiceInterface;
use App\Exceptions\CommissionAlreadyAppliedException;

class SaleService implements SaleServiceInterface
{
    public function __construct(protected SaleRepository $repository, protected SellerRepository $sellerRepository, protected CommissionService $commissionService) {}

    public function create(string $description, int $sellerId, string $saleDate, float $amount): Sale
    {
        $saleData = $this->prepareToSave($description, $sellerId, $saleDate, $amount);        
        $sale = DB::transaction(function() use($saleData){
            $sale = $this->repository->create($saleData);
            $this->generateCommissionToThisSale($sale);

            return $sale;
        });

        return $sale;
    }

    public function update(int $saleId, string $description, int $sellerId, string $saleDate, float $amount): Sale
    {
        $saleData = $this->prepareToSave($description, $sellerId, $saleDate, $amount);
        $sale = DB::transaction(function() use($saleId, $saleData){
            $sale = $this->repository->update($saleId, $saleData);
            $this->generateCommissionToThisSale($sale);

            return $sale;
        });

        return $sale;
    }
    
    public function delete($saleId): void 
    {
        DB::transaction(function() use($saleId){
            $this->repository->delete($saleId);
            $this->commissionService->deleteAllCommissionsFromSale($saleId);
        });
    }

    public function list(): Collection
    {
        return $this->repository->all();
    }

    public function listBySeller($sellerId): Collection
    {
        return $this->repository->bySeller($sellerId);
    }

    public function findById($saleId): Sale 
    {
        return $this->repository->findById($saleId);
    }

    public function sendManagerDailySaleReportEmail(): void 
    {
        $salers = $this->repository->all();

        Mail::to(env('MANAGER_EMAIL'))->queue(new DailyManagerSalesReport(
            env('MANAGER_NAME'),
            $salers
        ));
    }

    protected function prepareToSave(string $description, int $sellerId, string $saleDate, float $amount): array
    {
        $this->validateBeforeSave($description, $sellerId, $saleDate, $amount);

        return [
            'description' => $description,
            'seller_id' => $sellerId,
            'amount' => $amount,
            'sale_date' => $saleDate,
        ];
    }

    protected function generateCommissionToThisSale(Sale $sale)
    {
        try {
            $this->commissionService->create($sale->seller_id, $sale->id, new SalesCommission($sale->amount));
        } catch (CommissionAlreadyAppliedException $e) {
            $this->commissionService->update($sale->seller_id, $sale->id, new SalesCommission($sale->amount));
        }
    }

    protected function validateBeforeSave(string $description, int $sellerId, string $saleDate, float $amount): void
    {
        if(empty($description)) {
            throw new SaleCreationException('The description parameter cannot be empty');
        }

        if(is_null($this->sellerRepository->findById($sellerId))) {
            throw new SellerNotFoundException();
        }

        try {
            new \DateTime($saleDate);
        } catch (\Exception $e) {
            throw new SaleCreationException('The saleDate parameter is invalid');
        }

        if($amount <= 0) {
            throw new SaleCreationException('The amount parameter must be greater than zero');
        }
    }
}