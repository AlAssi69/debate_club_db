<?php

namespace Database\Seeders;

use App\Enums\PersonRole;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        foreach (PersonRole::cases() as $personRole) {
            Role::firstOrCreate(['name' => $personRole->value]);
        }
    }
}
