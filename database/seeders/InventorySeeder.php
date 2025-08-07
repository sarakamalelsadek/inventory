<?php

namespace Database\Seeders;

use App\Models\InventoryItem;
use App\Models\Stock;
use App\Models\Warehouse;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create two warehouses
        $warehouse1 = Warehouse::create([
            'name' => 'Warehouse A',
            'location' => 'Cairo',
        ]);

        $warehouse2 = Warehouse::create([
            'name' => 'Warehouse B',
            'location' => 'Alexandria',
        ]);

        // Create an inventory item
        $item = InventoryItem::create([
            'name' => 'Test Product',
            'sku' => 'TP-001',
            'price' => 150.00,
            'description' => 'Just a test item',
        ]);

        // Add stock to both warehouses
        Stock::create([
            'warehouse_id' => $warehouse1->id,
            'inventory_item_id' => $item->id,
            'quantity' => 20,
        ]);

        Stock::create([
            'warehouse_id' => $warehouse2->id,
            'inventory_item_id' => $item->id,
            'quantity' => 15,
        ]);
    }
}
