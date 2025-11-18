<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceiveNote extends Model
{
    use HasFactory;

    protected $table = 'receive_notes';
    protected $primaryKey = 'grn_id';

    protected $fillable = [
        'grn_received_by',
        'item_id',
        'grn_quantity_received',
        'grn_date_received',
        'grn_available_qty',
        'grn_supplier',
        'grn_po_number',
        'grn_remarks',
        'grn_itemExpiredDate',
        'grn_itemBatchNumber',
    ];

    // Relationship to Item
    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id', 'item_id');
    }
}

