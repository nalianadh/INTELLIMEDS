<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $primaryKey = 'item_id';

    public function receiveNotes()
    {
        return $this->hasMany(\App\Models\ReceiveNote::class, 'item_id', 'item_id');
    }
    protected $fillable = [
        'i_name',
        'i_description',
        'i_reorderLevel',
        'i_quantity_in_stock',
        'i_stockID',
        'i_minLevel',
        'i_maxLevel',
        'i_unit', 
    ];
}
