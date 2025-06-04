<?php
namespace App\Services\Contracts;

use App\Models\Sale;
use Illuminate\Support\Collection;

interface SaleServiceInterface
{
    public function create(string $description, int $sellerId, string $saleDate, float $amount): Sale;
    public function update(int $saleId, string $description, int $sellerId, string $saleDate, float $amount): Sale;
    public function delete(int $saleId): void;
    public function list(): Collection;
    public function listBySeller(int $sellerId): Collection;
    public function findById(int $saleId): Sale;
    public function sendManagerDailySaleReportEmail(): void;
}