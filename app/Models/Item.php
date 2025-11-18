<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $primaryKey = 'item_id';

    protected $fillable = [
        'i_name',
        'i_description',
        'i_reorderLevel',
        'i_quantity_in_stock', // Optional now, can keep for legacy
        'i_stockID',
        'i_minLevel',
        'i_maxLevel',
        'i_unit',
    ];

    // Relationships
    public function receiveNotes()
    {
        return $this->hasMany(\App\Models\ReceiveNote::class, 'item_id', 'item_id');
    }

    public function supplyTransactions()
    {
        return $this->hasMany(\App\Models\SupplyTransaction::class, 'Stock_ID', 'i_stockID');
    }

    // Accessor: compute i_quantity_in_stock dynamically
    public function getQuantityInStockAttribute()
    {
        // Sum all available quantities from GRN / Receive Notes
        return $this->receiveNotes()->sum('grn_available_qty');
    }
}
