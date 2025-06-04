<?php

namespace App\Services\Contracts;

use App\Models\Seller;
use Illuminate\Support\Collection;

interface SellerServiceInterface
{
    public function create(string $name, string $email): Seller;
    public function update(int $sellerId, string $name, string $email): Seller;
    public function delete(int $sellerId): void;
    public function list(): Collection;
    public function listWithSalesAndCommissionsTotal(): Collection;
    public function findById(int $sellerId): Seller;
}