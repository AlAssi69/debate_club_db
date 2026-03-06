<?php

namespace App\Models;

use App\Enums\PersonRole;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    protected $fillable = [
        'name',
    ];

    protected function casts(): array
    {
        return [
            'name' => PersonRole::class,
        ];
    }

    public function persons(): BelongsToMany
    {
        return $this->belongsToMany(Person::class, 'person_role')
            ->withTimestamps();
    }
}
