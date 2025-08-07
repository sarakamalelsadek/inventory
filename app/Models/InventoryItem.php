<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryItem extends Model
{
    use HasFactory;
    protected $guarded = [];

    //relations
    public function stocks() {
        return $this->hasMany(Stock::class);
    }

    // scopes for search
    public function scopeSearch($query, $term) {
        if (!$term) return $query;
        return $query->where('name', 'like', "%{$term}%")
                     ->orWhere('sku', 'like', "%{$term}%");
    }

    public function scopePriceBetween($query, $min = null, $max = null) {
        if ($min !== null) $query->where('price', '>=', $min);
        if ($max !== null) $query->where('price', '<=', $max);
        return $query;
    }

}
