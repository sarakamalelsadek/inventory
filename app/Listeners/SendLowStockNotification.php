<?php

namespace App\Listeners;

use App\Events\LowStockDetected;
use App\Mail\LowStockMail;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendLowStockNotification implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(LowStockDetected $event)
    {
        $adminEmail = User::where('email','superAdmin@example.com')->value('email');
        if($adminEmail){
            Mail::to($adminEmail)->send(new LowStockMail($event->stock));
        }
        Log::info("Low stock detected for item {$event->stock->inventory_item_id} in warehouse {$event->stock->warehouse_id}. Current qty: {$event->stock->quantity}");
    }
}
