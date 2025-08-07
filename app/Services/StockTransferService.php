<?php
namespace App\Services;

use App\Models\Stock;
use App\Models\StockTransfer;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Cache;


class StockTransferService
{
    protected WarehouseInventoryService $inventoryService;

    public function __construct(WarehouseInventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    public function transferStock(array $data, $userId): StockTransfer
    {
        return DB::transaction(function () use ($data, $userId) {

            // Lock source stock row
            $fromStock = Stock::where('warehouse_id', $data['from_warehouse_id'])
                ->where('inventory_item_id', $data['inventory_item_id'])
                ->lockForUpdate()
                ->first();

            if (!$fromStock) {
                throw ValidationException::withMessages([
                    'from_warehouse_id' => 'Source warehouse does not contain this item.'
                ]);
            }

            if (!$fromStock || $fromStock->quantity < $data['quantity']) {
                throw ValidationException::withMessages([
                    'quantity' => 'Not enough stock in the source warehouse.',
                ]);
            }

            // Decrease from source warehouse
            $fromStock->decrement('quantity', $data['quantity']);
            $fromStock->refresh();

            // Increase in target warehouse
            $toStock = Stock::firstOrCreate(
                [
                    'warehouse_id' => $data['to_warehouse_id'],
                    'inventory_item_id' => $data['inventory_item_id']
                ],
                ['quantity' => 0]
            );
            $toStock->increment('quantity', $data['quantity']);
            $toStock->refresh();

            // Log the transfer
            return StockTransfer::create([
                'from_warehouse_id' => $data['from_warehouse_id'],
                'to_warehouse_id'   => $data['to_warehouse_id'],
                'inventory_item_id' => $data['inventory_item_id'],
                'quantity'          => $data['quantity'],
                'created_by'        => $userId,
                'status'            => 'COMPLETED'
            ]);

            // Dispatch low stock event if needed (after decrement)
            $threshold = config('inventory.low_stock_threshold', 10);
            if ($fromStock->quantity < $threshold) {
                event(new LowStockDetected($fromStock));
            }

             //Flush caches for affected warehouses
            try {
                $this->inventoryService->flushWarehouseCache((int)$data['from_warehouse_id']);
            } catch (\Throwable $e) {
                \Log::warning("Failed to flush cache for warehouse {$data['from_warehouse_id']}: " . $e->getMessage());
            }

            try {
                $this->inventoryService->flushWarehouseCache((int)$data['to_warehouse_id']);
            } catch (\Throwable $e) {
                \Log::warning("Failed to flush cache for warehouse {$data['to_warehouse_id']}: " . $e->getMessage());
            }

            return $transfer;
        },5);
    }


}
