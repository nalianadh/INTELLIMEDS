<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Item;

class SupplyTransaction extends Model
{
    protected $table = 'supply_transaction';
    public $timestamps = false;

    protected $fillable = [
        'Date',
        'Stock_ID',
        'Stock',
        'Brand',
        'Site_Supplier',
        'Activity',
        'Quantity',
        'Unit'
    ];

    // Relation: Stock in supply_transaction matches i_name in items
    public function item()
    {
        return $this->belongsTo(Item::class, 'Stock', 'i_name');
        // 'Stock' column in supply_transaction corresponds to 'i_name' in items
    }
}
