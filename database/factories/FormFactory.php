<?php

namespace Database\Factories;

use App\Enums\FieldType;
use App\Models\Form;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Form>
 */
class FormFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => fake()->randomElement(['Feedback', 'Survey', 'Signup', 'Application', 'RSVP']).' — '.fake()->words(2, true),
            'status' => Form::STATUS_DRAFT,
            'fields' => [
                array_merge(Form::makeField(FieldType::ShortText), ['label' => 'What is your name?', 'required' => true]),
                array_merge(Form::makeField(FieldType::Email), ['label' => 'What is your email?', 'required' => true]),
                array_merge(Form::makeField(FieldType::LongText), ['label' => 'Anything else to share?']),
            ],
            'settings' => null,
        ];
    }

    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Form::STATUS_PUBLISHED,
            'published_at' => now(),
        ]);
    }

    public function closed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Form::STATUS_CLOSED,
            'published_at' => now()->subWeek(),
        ]);
    }
}
