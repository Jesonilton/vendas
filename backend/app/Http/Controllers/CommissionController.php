<?php

namespace App\Http\Controllers;

use App\Services\Contracts\CommissionServiceInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CommissionController extends Controller
{
    /**
     * @param \App\Services\CommissionService $service
     */
    public function __construct(protected CommissionServiceInterface $service) {}

    public function sendCommissionEmail($sellerId)
    {
        try {
            $this->service->sendCommissionStatementToSellersEmail($sellerId);
        } catch (ModelNotFoundException $th) {
            return response()->json(['error' => 'Vendedor não encontrado']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao enviar email. Tente novamente mais tarde']);
        }

        return response()->json(['message' => 'Email enviado com sucesso.']);
    }
}