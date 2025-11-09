<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockRequest extends Model
{
    protected $table = 'stock_requests';
    protected $primaryKey = 'request_id';
    public $timestamps = false;

    protected $fillable = [
        'rq_requested_by',
        'user_id',
        'item_id',
        'rq_quantity_requested',
        'rq_qty_approved',
        'rq_status',
        'rq_date_requested',
        'rq_date_approved',
        'rq_approved_by',
        'rq_remarks',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id', 'item_id');
    }
    public function requestedBy()
    {
        return $this->belongsTo(User::class, 'rq_requested_by', 'user_id');
    }
        public function approvedByUser()
    {
        return $this->belongsTo(User::class, 'rq_approved_by', 'user_id');
    }

}
