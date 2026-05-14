<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'job_post_id',
        'status',
        'interview_details',
        'offer_details',
        'offer_letter_path',
        'rejection_reason',
    ];

    protected $casts = [
        'interview_details' => 'array',
        'offer_details' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jobPost()
    {
        return $this->belongsTo(JobPost::class);
    }
}
