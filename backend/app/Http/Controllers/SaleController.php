<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaleFormRequest;
use App\Services\Contracts\SaleServiceInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SaleController extends Controller
{
    /**
     * @param \App\Services\SaleService $service
     */
    public function __construct(protected SaleServiceInterface $service) {}

    public function index()
    {
        return response()->json($this->service->list());
    }

    public function store(SaleFormRequest $request)
    {
        return response()->json($this->service->create($request->description, $request->seller_id, $request->sale_date, $request->amount), 201);
    }

    public function update($saleId, SaleFormRequest $request)
    {
        return response()->json($this->service->update($saleId, $request->description, $request->seller_id, $request->sale_date, $request->amount), 201);
    }

    public function destroy($saleId)
    {
        try {
            $this->service->delete($saleId);
        } catch (ModelNotFoundException $th) {
            return response()->json(['error' => 'Venda não encontrada']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao deletar venda. Tente novamente mais tarde']);
        }

        return response()->json(['success' => 'Venda deletada com sucesso']);
    }

    public function bySeller($sellerId)
    {
        return response()->json($this->service->listBySeller($sellerId));
    }
}
