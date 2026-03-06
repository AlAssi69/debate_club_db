<?php

namespace Database\Factories;

use App\Models\TrainingSession;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TrainingSession>
 */
class TrainingSessionFactory extends Factory
{
    protected $model = TrainingSession::class;

    private const CATEGORIES = [
        'Public Speaking',
        'Argumentation',
        'Rebuttal Techniques',
        'Research Methods',
        'Cross-Examination',
        'Persuasion',
        'Logical Fallacies',
        'Impromptu Speaking',
    ];

    private const TITLES = [
        'Introduction to %s',
        'Advanced %s Workshop',
        '%s Masterclass',
        '%s Practice Session',
        '%s Fundamentals',
        'Weekly %s Drill',
    ];

    public function definition(): array
    {
        $category = fake()->randomElement(self::CATEGORIES);
        $title = sprintf(fake()->randomElement(self::TITLES), $category);

        return [
            'uuid' => Str::uuid()->toString(),
            'title' => $title,
            'category' => $category,
            'scheduled_date' => fake()->dateTimeBetween('-3 months', '+3 months'),
            'time' => fake()->time('H:i'),
            'duration_minutes' => fake()->randomElement([30, 45, 60, 90, 120]),
        ];
    }

    public function upcoming(): static
    {
        return $this->state(fn () => [
            'scheduled_date' => fake()->dateTimeBetween('now', '+3 months'),
        ]);
    }

    public function past(): static
    {
        return $this->state(fn () => [
            'scheduled_date' => fake()->dateTimeBetween('-6 months', '-1 day'),
        ]);
    }
}
