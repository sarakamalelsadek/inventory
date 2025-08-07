<?php

namespace App\Services;

use App\Models\Stock;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class WarehouseInventoryService
{
    protected int $cacheTtl = 60; // 1 minute

    /**
     *
     * @param int $warehouseId
     * @param array $filters ['search'=>..., 'min_price'=>..., 'max_price'=>...]
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getInventory(int $warehouseId, array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $page = (int) request('page', 1);
        $cacheKey = $this->buildCacheKey($warehouseId, $filters, $perPage, $page);

        //store paginated result in cache.
        $paginator = Cache::remember($cacheKey, $this->cacheTtl, function () use ($warehouseId, $filters, $perPage) {
            return $this->queryInventory($warehouseId, $filters)->paginate($perPage);
        });

        $this->storeCacheKeyForWarehouse($warehouseId, $cacheKey);


        return  $paginator;
    }

  
    protected function buildCacheKey(int $warehouseId, array $filters, int $perPage, int $page): string
    {
        $normalizedFilters = [
            'search' => $filters['search'] ?? null,
            'min_price' => isset($filters['min_price']) ? (string)$filters['min_price'] : null,
            'max_price' => isset($filters['max_price']) ? (string)$filters['max_price'] : null,
        ];

        $filtersHash = md5(json_encode($normalizedFilters));

        return "warehouse:{$warehouseId}:inventory:page:{$page}:per:{$perPage}:f:{$filtersHash}";
    }

    
    protected function queryInventory(int $warehouseId, array $filters)
    {
        $query = Stock::with(['item'])
            ->where('warehouse_id', $warehouseId);

        if (!empty($filters['search'])) {
            $keyword = $filters['search'];
            $query->whereHas('item', function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                   ->orWhere('sku', 'like', "%{$keyword}%");
            });
        }

        if (isset($filters['min_price']) || isset($filters['max_price'])) {
            $min = $filters['min_price'] ?? null;
            $max = $filters['max_price'] ?? null;

            $query->whereHas('item', function ($q) use ($min, $max) {
                if ($min !== null) $q->where('price', '>=', $min);
                if ($max !== null) $q->where('price', '<=', $max);
            });
        }

        return $query->join('inventory_items', 'stocks.inventory_item_id', '=', 'inventory_items.id')
                 ->select('stocks.*')
                 ->orderBy('inventory_items.name');
    }

    
    public function flushWarehouseCache(int $warehouseId): void
    {
        $registryKey = $this->getRegistryKey($warehouseId);
        $keys = Cache::get($registryKey, []);

        foreach ($keys as $k) {
            Cache::forget($k);
        }

        Cache::forget($registryKey);
    }


    public function storeCacheKeyForWarehouse(int $warehouseId, string $cacheKey): void
    {
        $registryKey = $this->getRegistryKey($warehouseId);
        $keys = Cache::get($registryKey, []);

        if (!in_array($cacheKey, $keys, true)) {
            $keys[] = $cacheKey;
            Cache::put($registryKey, $keys, now()->addHours(6));
        }
    }

    protected function getRegistryKey(int $warehouseId): string
    {
        return "warehouse:{$warehouseId}:cache_keys_registry";
    }
}
