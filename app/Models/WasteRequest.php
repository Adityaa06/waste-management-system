<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WasteRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'waste_type',
        'image',
        'status',
        'assigned_to',
        'address',
        'latitude',
        'longitude',
    ];

    /**
     * Get the user who created the request.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the worker assigned to the request.
     */
    public function worker()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
