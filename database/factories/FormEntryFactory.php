<?php

namespace Database\Factories;

use App\Enums\FieldType;
use App\Models\Form;
use App\Models\FormEntry;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<FormEntry>
 */
class FormEntryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'form_id' => Form::factory(),
            'answers' => [],
            'meta' => [
                'ip' => fake()->ipv4(),
                'user_agent' => fake()->userAgent(),
            ],
        ];
    }

    /**
     * Generate plausible answers for every input field on the given form.
     */
    public function forForm(Form $form): static
    {
        return $this->state(function (array $attributes) use ($form) {
            $answers = [];

            foreach ($form->inputFields() as $field) {
                $answers[$field['id']] = $this->fakeAnswer($field);
            }

            return ['form_id' => $form->id, 'answers' => $answers];
        });
    }

    /**
     * @param  array<string, mixed>  $field
     */
    protected function fakeAnswer(array $field): mixed
    {
        return match (FieldType::from($field['type'])) {
            FieldType::ShortText => fake()->name(),
            FieldType::LongText => fake()->paragraph(),
            FieldType::Email => fake()->safeEmail(),
            FieldType::Phone => fake()->phoneNumber(),
            FieldType::Url => 'https://'.fake()->domainName(),
            FieldType::Number => (string) fake()->numberBetween(1, 500),
            FieldType::Select, FieldType::MultipleChoice => fake()->randomElement($field['options'] ?? ['—']),
            FieldType::Checkboxes => fake()->randomElements($field['options'] ?? ['—'], rand(1, max(1, count($field['options'] ?? ['—'])))),
            FieldType::Date => fake()->dateTimeBetween('now', '+2 months')->format('Y-m-d'),
            FieldType::Rating => (string) fake()->numberBetween(1, $field['max'] ?? 5),
            FieldType::Statement => null,
        };
    }
}
