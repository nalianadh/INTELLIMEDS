<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockTransfer extends Model
{
    protected $primaryKey = 'transfer_id';
    protected $table = 'stock_transfers';
    protected $fillable = [
        'item_id',
        'tr_from_unit',
        'tr_destination',
        'tr_quantity',
        'tr_transfer_status',
        'tr_requested_by',
        'tr_received_by',
        'tr_date_requested',
        'tr_date_received',
        'tr_remarks',
        'user_id',
    ];
    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id', 'item_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
