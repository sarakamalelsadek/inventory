<?php

namespace App\Http\Controllers;

use App\Http\Requests\StockTransferRequest;
use App\Services\StockTransferService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StockTransferController extends Controller
{
    
    protected StockTransferService $service;

    public function __construct(StockTransferService $service)
    {
        $this->service = $service;
    }

    public function store(StockTransferRequest $request): JsonResponse
    {
        $transfer = $this->service->transferStock($request->validated(), Auth::id());

        return response()->json([
            'message' => 'Stock transferred successfully.',
            'data' => $transfer
        ], 201);
    }
}
