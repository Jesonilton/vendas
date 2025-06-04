<?php

namespace App\Http\Controllers;

use Exception;
use App\Http\Requests\SellerFormRequest;
use App\Exceptions\UnableToDeleteException;
use App\Services\Contracts\SellerServiceInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SellerController extends Controller
{
    /**
     * @param \App\Services\SellerService $service
     */
    public function __construct(protected SellerServiceInterface $service) {}

    public function index()
    {
        return response()->json($this->service->listWithSalesAndCommissionsTotal());
    }

    public function store(SellerFormRequest $request)
    {
        return response()->json($this->service->create($request->name, $request->email), 201);
    }

    public function update($sellerId, SellerFormRequest $request)
    {
        return response()->json($this->service->update($sellerId, $request->name, $request->email), 201);
    }

    public function destroy($sellerId)
    {
        try {
            $this->service->delete($sellerId);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Vendedor não encontrado']);
        } catch (UnableToDeleteException $e) {
            return response()->json(['error' => 'Não é possível deletar. O Vendedor possui vendas']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao deletar vendedor. Tente novamente mais tarde']);
        }

        return response()->json(['success' => 'Vendedor deletado com sucesso']);
    }
}
