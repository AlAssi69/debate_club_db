<?php

namespace App\Models;

use App\Contracts\Syncable;
use App\Enums\AttendanceStatus;
use App\Enums\DebateParticipantRole;
use App\Enums\SessionRole;
use App\Traits\HasGoogleSheetSync;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Person extends Model implements Syncable
{
    use HasFactory, HasGoogleSheetSync;

    protected $table = 'persons';

    protected $fillable = [
        'uuid',
        'first_name',
        'last_name',
        'contact_info',
        'join_date',
        'synced_at',
    ];

    protected function casts(): array
    {
        return [
            'join_date' => 'date',
            'synced_at' => 'datetime',
        ];
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'person_role')
            ->withTimestamps();
    }

    public function trainingSessions(): BelongsToMany
    {
        return $this->belongsToMany(TrainingSession::class, 'training_session_person')
            ->withPivot(['role', 'status'])
            ->withTimestamps();
    }

    public function trainerSessions(): BelongsToMany
    {
        return $this->trainingSessions()
            ->wherePivot('role', SessionRole::Trainer->value);
    }

    public function traineeSessions(): BelongsToMany
    {
        return $this->trainingSessions()
            ->wherePivot('role', SessionRole::Trainee->value);
    }

    public function debates(): BelongsToMany
    {
        return $this->belongsToMany(Debate::class, 'debate_person')
            ->withPivot(['role'])
            ->withTimestamps();
    }

    public function hasRole(string $roleName): bool
    {
        return $this->roles()->where('name', $roleName)->exists();
    }

    public function getSheetName(): string
    {
        return 'Persons';
    }

    public function toSheetRow(): array
    {
        return [
            $this->uuid,
            $this->first_name,
            $this->last_name,
            $this->contact_info,
            $this->join_date?->toDateString(),
            $this->updated_at?->toIso8601String(),
        ];
    }

    public static function fromSheetRow(array $row): static
    {
        return static::updateOrCreate(
            ['uuid' => $row[0]],
            [
                'first_name' => $row[1],
                'last_name' => $row[2],
                'contact_info' => $row[3] ?? null,
                'join_date' => $row[4],
            ]
        );
    }
}
