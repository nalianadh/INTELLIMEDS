<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReadStatus extends Model
{
    use HasFactory;

    // The name of the database table associated with the model.
    protected $table = 'read_status';

    /**
     * The attributes that are mass assignable.
     * This is crucial for using firstOrCreate() in the controller.
     * messageable_type and messageable_id are for the polymorphic relationship.
     */
    protected $fillable = [
        'user_id',
        'messageable_id',
        'messageable_type',
    ];

    /**
     * Define the relationship to the User model.
     * Assuming your user ID column is 'user_id' as per your foreign key definition.
     */
    public function user()
    {
        // Adjust App\Models\User if your user model is located elsewhere
        return $this->belongsTo(User::class, 'user_id'); 
    }
}
