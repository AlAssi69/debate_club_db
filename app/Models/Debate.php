<?php

namespace App\Models;

use App\Contracts\Syncable;
use App\Enums\DebateParticipantRole;
use App\Enums\DebateType;
use App\Traits\HasGoogleSheetSync;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Debate extends Model implements Syncable
{
    use HasFactory, HasGoogleSheetSync;

    protected $fillable = [
        'uuid',
        'title',
        'type',
        'date',
        'location',
        'outcome',
        'synced_at',
    ];

    protected function casts(): array
    {
        return [
            'type' => DebateType::class,
            'date' => 'date',
            'synced_at' => 'datetime',
        ];
    }

    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(Person::class, 'debate_person')
            ->withPivot(['role'])
            ->withTimestamps();
    }

    public function debaters(): BelongsToMany
    {
        return $this->participants()
            ->wherePivot('role', DebateParticipantRole::Debater->value);
    }

    public function judges(): BelongsToMany
    {
        return $this->participants()
            ->wherePivot('role', DebateParticipantRole::Judge->value);
    }

    public function moderators(): BelongsToMany
    {
        return $this->participants()
            ->wherePivot('role', DebateParticipantRole::Moderator->value);
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (blank($term)) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($term) {
            $q->where('id', $term)
                ->orWhere('title', 'like', "%{$term}%")
                ->orWhere('location', 'like', "%{$term}%");
        });
    }

    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query
            ->when($filters['type'] ?? null, fn (Builder $q, string $type) => $q->where('type', $type)
            )
            ->when($filters['date_from'] ?? null, fn (Builder $q, string $date) => $q->whereDate('date', '>=', $date)
            )
            ->when($filters['date_to'] ?? null, fn (Builder $q, string $date) => $q->whereDate('date', '<=', $date)
            );
    }

    public function getSheetName(): string
    {
        return 'Debates';
    }

    public function toSheetRow(): array
    {
        return [
            $this->uuid,
            $this->title,
            $this->type->value,
            $this->date?->toDateString(),
            $this->location,
            $this->outcome,
            $this->updated_at?->toIso8601String(),
        ];
    }

    public static function fromSheetRow(array $row): static
    {
        return static::updateOrCreate(
            ['uuid' => $row[0]],
            [
                'title' => $row[1],
                'type' => $row[2],
                'date' => $row[3],
                'location' => $row[4] ?? null,
                'outcome' => $row[5] ?? null,
            ]
        );
    }
}
