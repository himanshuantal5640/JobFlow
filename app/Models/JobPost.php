<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'company',
        'skills',
        'match',
        'description',
        'location',
        'salary',
        'recruiter_id',
        'department',
        'work_mode',
        'experience',
        'job_type',
        'status'
    ];

    protected $casts = [
        'skills' => 'array',
    ];

    public function recruiter()
    {
        return $this->belongsTo(User::class, 'recruiter_id');
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }
}
