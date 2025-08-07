<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;
    protected $guarded = [];

    //relations
    public function stocks() {
        return $this->hasMany(Stock::class);
    }

    public function items() {
        return $this->belongsToMany(InventoryItem::class, 'stocks')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }

}
