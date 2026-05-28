<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'category',
        'priority',
        'status',
        'image',
    ];

    /**
     * Get the user who submitted the complaint.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
