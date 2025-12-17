<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubdepartmentStock extends Model
{
    protected $table = 'subdepartment_stocks';

    protected $fillable = [
        'user_id',
        'item_id',
        'sd_batchNumber',
        'sd_expiryDate',
        'sd_quantityInHand',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id', 'item_id');
    }
}
