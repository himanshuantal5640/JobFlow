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
        'min_salary',
        'max_salary',
        'recruiter_id',
        'department',
        'work_mode',
        'experience',
        'job_type',
        'status',
    ];

    protected $casts = [
        'skills' => 'array',
        'min_salary' => 'integer',
        'max_salary' => 'integer',
    ];

    public function recruiter()
    {
        return $this->belongsTo(User::class, 'recruiter_id');
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    /**
     * @return list<string>
     */
    public function skillsList(): array
    {
        $skills = $this->skills;

        if (is_array($skills)) {
            return array_values(array_filter($skills, fn ($s) => is_string($s) && $s !== ''));
        }

        if (is_string($skills)) {
            $decoded = json_decode($skills, true);

            return is_array($decoded)
                ? array_values(array_filter($decoded, fn ($s) => is_string($s) && $s !== ''))
                : [];
        }

        return [];
    }

    public function syncSalaryDisplay(): void
    {
        if ($this->min_salary && $this->max_salary) {
            $this->salary = self::formatSalaryRange((int) $this->min_salary, (int) $this->max_salary);
        }
    }

    public static function formatSalaryRange(int $min, int $max): string
    {
        $fmt = fn (int $n) => '₹' . ($n / 100000) . ' LPA';

        return $fmt($min) . ' – ' . $fmt($max);
    }

    /**
     * Pipeline progress: shortlisted + interview + offer vs total applications.
     * Uses eager-loaded `applications_count` and `pipeline_applications_count` when present.
     */
    public function fillRatePercent(): int
    {
        $total = (int) ($this->applications_count ?? $this->applications()->count());
        if ($total === 0) {
            return 0;
        }

        $pipeline = isset($this->pipeline_applications_count)
            ? (int) $this->pipeline_applications_count
            : (int) $this->applications()->whereIn('status', ['shortlisted', 'interview', 'offer'])->count();

        return (int) min(100, (int) round(($pipeline / $total) * 100));
    }
}
