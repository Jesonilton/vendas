<?php 
namespace App\Services;

use App\Models\Seller;
use App\Repositories\SellerRepository;
use App\Exceptions\SellerCreationException;
use App\Exceptions\UnableToDeleteException;
use App\Services\Contracts\SellerServiceInterface;
use Illuminate\Database\Eloquent\Collection;

class SellerService implements SellerServiceInterface
{
    public function __construct(protected SellerRepository $repository) {}

    public function create(string $name, string $email): Seller
    {
        $this->validateBeforeCreating($name, $email);

        $seller = $this->repository->getTrashedSallerByEmail($email);

        if($seller) {
            $seller->restore();
            return $seller;
        }

        return $this->repository->create([
            'name' => $name, 
            'email' => $email
        ]);
    }

    public function update(int $sellerId, string $name, string $email): Seller
    {
        $this->validateBeforeCreating($name, $email);

        return $this->repository->update($sellerId, [
            'name' => $name, 
            'email' => $email
        ]);
    }
    
    public function delete(int $sellerId): void 
    {
        if($this->repository->hasAnySales($sellerId)) {
            throw new UnableToDeleteException('Saller has sales');
        }

        $this->repository->delete($sellerId);
    }

    public function list(): Collection
    {
        return $this->repository->all();
    }

    public function listWithSalesAndCommissionsTotal(): Collection
    {
        return $this->repository->allWithSalesAndCommissionsTotal();
    }

    public function findById($sellerId): Seller 
    {
        return $this->repository->findById($sellerId);
    }

    protected function validateBeforeCreating(string $name, string $email): void
    {
        if(empty($name)) {
            throw new SellerCreationException('The name parameter cannot be empty');
        }

        if(empty($email) || filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            throw new SellerCreationException('The email parameter must contain a valid email address.');
        }
    }
}