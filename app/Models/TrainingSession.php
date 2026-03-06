<?php

namespace App\Models;

use App\Contracts\Syncable;
use App\Enums\AttendanceStatus;
use App\Enums\SessionRole;
use App\Traits\HasGoogleSheetSync;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TrainingSession extends Model implements Syncable
{
    use HasFactory, HasGoogleSheetSync;

    protected $fillable = [
        'uuid',
        'title',
        'category',
        'scheduled_date',
        'time',
        'duration_minutes',
        'synced_at',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_date' => 'date',
            'synced_at' => 'datetime',
        ];
    }

    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(Person::class, 'training_session_person')
            ->withPivot(['role', 'status'])
            ->withTimestamps();
    }

    public function trainers(): BelongsToMany
    {
        return $this->participants()
            ->wherePivot('role', SessionRole::Trainer->value);
    }

    public function trainees(): BelongsToMany
    {
        return $this->participants()
            ->wherePivot('role', SessionRole::Trainee->value);
    }

    public function getSheetName(): string
    {
        return 'TrainingSessions';
    }

    public function toSheetRow(): array
    {
        return [
            $this->uuid,
            $this->title,
            $this->category,
            $this->scheduled_date?->toDateString(),
            $this->time,
            $this->duration_minutes,
            $this->updated_at?->toIso8601String(),
        ];
    }

    public static function fromSheetRow(array $row): static
    {
        return static::updateOrCreate(
            ['uuid' => $row[0]],
            [
                'title' => $row[1],
                'category' => $row[2] ?? null,
                'scheduled_date' => $row[3],
                'time' => $row[4],
                'duration_minutes' => (int) $row[5],
            ]
        );
    }
}
