<?php 
namespace App\Services;

use App\Models\Commission;
use App\Mail\SellerCommissionEmail;
use App\Repositories\SaleRepository;
use Illuminate\Support\Facades\Mail;
use App\Repositories\SellerRepository;
use App\Exceptions\SaleNotFoundException;
use App\Mail\DailySellersCommissionReport;
use App\Repositories\CommissionRepository;
use App\Exceptions\SellerNotFoundException;
use Illuminate\Database\Eloquent\Collection;
use App\Businness\Commission\CommissionContract;
use App\Exceptions\CommissionAlreadyAppliedException;
use App\Services\Contracts\CommissionServiceInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CommissionService implements CommissionServiceInterface
{
    public function __construct(protected CommissionRepository $repository, protected SaleRepository $saleRepository, protected SellerRepository $sellerRepository) {}

    public function create(int $sellerId, int $saleId, CommissionContract $commission): Commission
    {
        $this->validateBeforeCreating($sellerId, $saleId);
        $this->deleteAllCommissionsFromSale($saleId);

        return $this->repository->create([
            'seller_id' => $sellerId,
            'sale_id' => $saleId,
            'commission' => $commission->calculate(),
        ]);
    }

    public function update(int $sellerId, int $saleId, CommissionContract $commission): Commission
    {
        $commissionRegister = $this->repository->findBySaleAndSeller($saleId, $sellerId);

        if(!$commissionRegister) {
            throw new ModelNotFoundException('Commission not found');
        }

        $commissionRegister->update([
            'commission' => $commission->calculate(),
        ]);

        return $commissionRegister->refresh();
    }

    public function deleteAllCommissionsFromSale(int $saleId): void
    {
        $this->repository->deleteAllFromSale($saleId);
    }

    public function list(): Collection
    {
        return $this->repository->all();
    }

    public function existsBySaleAndSeller(int $saleId, int $sellerId): bool
    {
        return $this->repository->existsBySaleAndSeller($saleId, $sellerId);
    }

    public function listBySeller(int $sellerId): Collection
    {
        return $this->repository->bySeller($sellerId);
    }

    public function sendCommissionStatementToSellersEmail($sellerId): void
    {
        $seller = $this->sellerRepository->findById($sellerId);

        if(!$seller) {
            throw new ModelNotFoundException('Seller not found');
        }

        $commissions = $this->listBySeller($seller->id);
        $commissionAmount = $commissions->sum('commission');

        Mail::to($seller->email)->queue(new SellerCommissionEmail(
            $seller,
            $commissions,
            $commissionAmount
        ));
    }

    public function sendDailyEmailReportToAllSellers(): void 
    {
        $sellers = $this->sellerRepository->allWithSalesAndCommissionsTotal();
        foreach ($sellers as $seller) {
            Mail::to($seller->email)->queue(new DailySellersCommissionReport(
                $seller->name,
                $seller->email,
                $seller->sales_count, 
                $seller->sales_amount, 
                $seller->commissions_amount
            ));
        }
    }

    protected function validateBeforeCreating(int $sellerId, int $saleId): void
    {
        if(is_null($this->sellerRepository->findById($sellerId))) {
            throw new SellerNotFoundException();
        }

        if(is_null($this->saleRepository->findById($saleId))) {
            throw new SaleNotFoundException();
        }

        if($this->repository->existsBySaleAndSeller($saleId, $sellerId)) {
            throw new CommissionAlreadyAppliedException('The commission already exists');
        }
    }
}