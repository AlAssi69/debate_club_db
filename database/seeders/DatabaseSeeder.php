<?php

namespace Database\Seeders;

use App\Enums\AttendanceStatus;
use App\Enums\DebateParticipantRole;
use App\Enums\SessionRole;
use App\Models\Debate;
use App\Models\Person;
use App\Models\Role;
use App\Models\TrainingSession;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
        ]);

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $roles = Role::all();
        $persons = Person::factory(30)->create();

        $persons->each(function (Person $person) use ($roles) {
            $person->roles()->attach(
                $roles->random(rand(1, 2))->pluck('id')->toArray()
            );
        });

        $trainers = $persons->random(5);
        $trainees = $persons->diff($trainers);

        $pastSessions = TrainingSession::factory(10)->past()->create();
        $upcomingSessions = TrainingSession::factory(8)->upcoming()->create();
        $allSessions = $pastSessions->merge($upcomingSessions);

        $allSessions->each(function (TrainingSession $session) use ($trainers, $trainees) {
            $session->participants()->attach(
                $trainers->random(rand(1, 2))->pluck('id')->toArray(),
                ['role' => SessionRole::Trainer->value, 'status' => AttendanceStatus::Present->value]
            );

            $selectedTrainees = $trainees->random(rand(3, 8));
            foreach ($selectedTrainees as $trainee) {
                $session->participants()->attach($trainee->id, [
                    'role' => SessionRole::Trainee->value,
                    'status' => fake()->randomElement(AttendanceStatus::cases())->value,
                ]);
            }
        });

        $recentDebates = Debate::factory(8)->recent()->create();
        $upcomingDebates = Debate::factory(4)->upcoming()->create();
        $allDebates = $recentDebates->merge($upcomingDebates);

        $allDebates->each(function (Debate $debate) use ($persons) {
            $pool = $persons->shuffle();

            $debaters = $pool->take(rand(4, 6));
            $pool = $pool->diff($debaters);

            $judges = $pool->take(rand(1, 3));
            $pool = $pool->diff($judges);

            $moderator = $pool->take(1);

            foreach ($debaters as $p) {
                $debate->participants()->attach($p->id, ['role' => DebateParticipantRole::Debater->value]);
            }
            foreach ($judges as $p) {
                $debate->participants()->attach($p->id, ['role' => DebateParticipantRole::Judge->value]);
            }
            foreach ($moderator as $p) {
                $debate->participants()->attach($p->id, ['role' => DebateParticipantRole::Moderator->value]);
            }
        });
    }
}
