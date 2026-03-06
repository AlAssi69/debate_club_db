<?php

namespace Database\Factories;

use App\Enums\DebateType;
use App\Models\Debate;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Debate>
 */
class DebateFactory extends Factory
{
    protected $model = Debate::class;

    private const TOPICS = [
        'AI Should Be Regulated by Governments',
        'Social Media Does More Harm Than Good',
        'Universal Basic Income Is Necessary',
        'Climate Change Requires Immediate Action',
        'Privacy vs National Security',
        'Standardized Testing Should Be Abolished',
        'Space Exploration Should Be Prioritized',
        'Free Speech Has Limits',
        'Nuclear Energy Is the Future',
        'Education Should Be Fully Funded by Government',
        'Technology Improves Quality of Life',
        'Globalization Benefits Developing Countries',
        'Democracy Is the Best Form of Government',
        'Renewable Energy Can Replace Fossil Fuels',
        'Censorship Is Never Justified',
    ];

    private const LOCATIONS = [
        'Main Hall',
        'Conference Room A',
        'Conference Room B',
        'Auditorium',
        'Library Hall',
        'Online (Zoom)',
        'Community Center',
        'University Campus',
    ];

    private const OUTCOMES = [
        'Proposition won by majority',
        'Opposition won by majority',
        'Draw - judges split',
        'Proposition won unanimously',
        'Opposition won unanimously',
        null,
    ];

    public function definition(): array
    {
        return [
            'uuid' => Str::uuid()->toString(),
            'title' => fake()->randomElement(self::TOPICS),
            'type' => fake()->randomElement(DebateType::cases()),
            'date' => fake()->dateTimeBetween('-6 months', '+2 months'),
            'location' => fake()->randomElement(self::LOCATIONS),
            'outcome' => fake()->randomElement(self::OUTCOMES),
        ];
    }

    public function recent(): static
    {
        return $this->state(fn () => [
            'date' => fake()->dateTimeBetween('-2 months', 'now'),
        ]);
    }

    public function upcoming(): static
    {
        return $this->state(fn () => [
            'date' => fake()->dateTimeBetween('now', '+3 months'),
            'outcome' => null,
        ]);
    }
}
