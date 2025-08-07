<?php

namespace Tests\Feature;

use App\Models\Stock;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class StockTransferTest extends TestCase
{
    use DatabaseTransactions;

    public function test_can_create_a_successful_stock_transfer()
    {

        $source = Warehouse::first();
        $destination = Warehouse::skip(1)->first();

        $stock = Stock::first();

        $admin = User::first();
        // Log in the user
        $this->actingAs($admin, 'sanctum');

        $response = $this->postJson('/api/stock-transfers', [
            'from_warehouse_id' => $source->id,
            'to_warehouse_id' => $destination->id,
            'inventory_item_id' =>  $stock->inventory_item_id,
            'quantity' => 1,
        ]);

        $response->assertStatus(201);

    }
}
