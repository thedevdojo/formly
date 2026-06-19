<?php

namespace App\Enums;

use Illuminate\Validation\Rule;

enum FieldType: string
{
    case ShortText = 'short_text';
    case LongText = 'long_text';
    case Email = 'email';
    case Phone = 'phone';
    case Url = 'url';
    case Number = 'number';
    case Select = 'select';
    case MultipleChoice = 'multiple_choice';
    case Checkboxes = 'checkboxes';
    case Date = 'date';
    case Rating = 'rating';
    case Statement = 'statement';

    public function label(): string
    {
        return match ($this) {
            self::ShortText => 'Short answer',
            self::LongText => 'Long answer',
            self::Email => 'Email',
            self::Phone => 'Phone',
            self::Url => 'Link',
            self::Number => 'Number',
            self::Select => 'Dropdown',
            self::MultipleChoice => 'Multiple choice',
            self::Checkboxes => 'Checkboxes',
            self::Date => 'Date',
            self::Rating => 'Rating',
            self::Statement => 'Text block',
        };
    }

    /**
     * Icon name rendered by the <x-icon> component.
     */
    public function icon(): string
    {
        return match ($this) {
            self::ShortText => 'text',
            self::LongText => 'paragraph',
            self::Email => 'at',
            self::Phone => 'phone',
            self::Url => 'link',
            self::Number => 'hash',
            self::Select => 'chevron-down-circle',
            self::MultipleChoice => 'radio',
            self::Checkboxes => 'checkbox',
            self::Date => 'calendar',
            self::Rating => 'star',
            self::Statement => 'heading',
        };
    }

    /**
     * Whether this field type presents a list of options.
     */
    public function hasOptions(): bool
    {
        return in_array($this, [self::Select, self::MultipleChoice, self::Checkboxes], true);
    }

    /**
     * Whether this block collects an answer (vs. display-only content).
     */
    public function isInput(): bool
    {
        return $this !== self::Statement;
    }

    /**
     * Validation rules for a submitted answer to a field of this type.
     *
     * @param  array{required?: bool, options?: list<string>, max?: int}  $field
     * @return list<mixed>
     */
    public function rules(array $field): array
    {
        $rules = [($field['required'] ?? false) ? 'required' : 'nullable'];

        $rules = [...$rules, ...match ($this) {
            self::ShortText => ['string', 'max:255'],
            self::LongText => ['string', 'max:10000'],
            self::Email => ['string', 'email', 'max:255'],
            self::Phone => ['string', 'max:40'],
            self::Url => ['string', 'url', 'max:2048'],
            self::Number => ['numeric'],
            self::Select, self::MultipleChoice => ['string', Rule::in($field['options'] ?? [])],
            self::Checkboxes => array_filter(['array', ($field['required'] ?? false) ? 'min:1' : null]),
            self::Date => ['date'],
            self::Rating => ['integer', 'min:1', 'max:'.($field['max'] ?? 5)],
            self::Statement => [],
        }];

        return $rules;
    }

    /**
     * Default state for a freshly added block of this type.
     *
     * @return array<string, mixed>
     */
    public function defaults(): array
    {
        return array_merge([
            'label' => '',
            'help' => '',
            'placeholder' => '',
            'required' => false,
        ], match ($this) {
            self::Select, self::MultipleChoice, self::Checkboxes => ['options' => ['Option 1', 'Option 2']],
            self::Rating => ['max' => 5],
            self::Statement => ['required' => false, 'body' => ''],
            default => [],
        });
    }

    /**
     * Grouped types for the builder's "add block" menu.
     *
     * @return array<string, list<self>>
     */
    public static function grouped(): array
    {
        return [
            'Text' => [self::ShortText, self::LongText, self::Statement],
            'Contact' => [self::Email, self::Phone, self::Url],
            'Choices' => [self::MultipleChoice, self::Checkboxes, self::Select],
            'Data' => [self::Number, self::Date, self::Rating],
        ];
    }
}
