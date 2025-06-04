<?php 
namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

abstract class AbstractRepository 
{
    /**
     * return the result of the ModelName::class
     *
     * @return string
     */
    protected abstract function getModelClass(): string;

    public function create(array $data): Model
    {
        return $this->getModelClass()
                    ::create($data);
    }

    public function update(int $id, array $data): Model
    {
        $register = $this->getModelClass()::findOrFail($id);
        $register->update($data);

        return $register->refresh();
    }

    public function delete(int $id): int
    {
        $register = $this->getModelClass()::findOrFail($id);
        return $register->delete();
    }

    public function findById($id): ?Model 
    {
        return $this->getModelClass()
                    ::find($id);
    }
}