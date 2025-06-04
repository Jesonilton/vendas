<?php

namespace App\Services\Contracts;

use App\Models\Commission;
use Illuminate\Support\Collection;
use App\Businness\Commission\CommissionContract;

interface CommissionServiceInterface
{
    public function create(int $sellerId, int $saleId, CommissionContract $commission): Commission;
    public function update(int $sellerId, int $saleId, CommissionContract $commission): Commission;
    public function deleteAllCommissionsFromSale(int $saleId): void;
    public function list(): Collection;
    public function existsBySaleAndSeller(int $saleId, int $sellerId): bool;
    public function listBySeller(int $sellerId): Collection;
    public function sendCommissionStatementToSellersEmail(int $sellerId): void;
    public function sendDailyEmailReportToAllSellers(): void;
}