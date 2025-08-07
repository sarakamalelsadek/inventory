<?php

namespace App\Http\Controllers;

use App\Http\Requests\InventoryIndexRequest;
use App\Http\Requests\WarehouseInventoryRequest;
use App\Models\InventoryItem;
use App\Services\WarehouseInventoryService;
use Illuminate\Http\JsonResponse;

class InventoryController extends Controller
{
    protected WarehouseInventoryService $inventoryService;

    public function __construct(WarehouseInventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    public function index(InventoryIndexRequest $request): JsonResponse
    {
        $perPage = (int) ($request->get('per_page', 15));

        $query = InventoryItem::with('stocks');

        $filters = $request->validatedFilters();

        if ($filters['search']) {
            $query->search($filters['search']);
        }
        if ($filters['min_price'] !== null || $filters['max_price'] !== null) {
            $query->priceBetween($filters['min_price'], $filters['max_price']);
        }

        $items = $query->paginate($perPage);
    
        return response()->json([
            'message' => 'success.',
            'data' => $items
        ], 200);
    }

   
    public function warehouseInventory($id, WarehouseInventoryRequest $request): JsonResponse
    {
        $perPage = (int) ($request->get('per_page', 15));
        $filters = $request->validatedFilters();

        $paginator = $this->inventoryService->getInventory((int)$id, $filters, $perPage);

        return response()->json($paginator);
    }
}
