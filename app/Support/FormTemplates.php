<?php

namespace App\Support;

use App\Enums\FieldType;
use App\Models\Form;

class FormTemplates
{
    /**
     * The built-in form templates shown in the gallery.
     *
     * @return array<string, array{name: string, description: string, icon: string, category: string, fields: list<array<string, mixed>>}>
     */
    public static function all(): array
    {
        return [
            'contact' => [
                'name' => 'Contact form',
                'description' => 'A simple way for anyone to reach you — name, email and a message.',
                'icon' => 'mail',
                'category' => 'Essentials',
                'fields' => [
                    self::field(FieldType::ShortText, ['label' => 'Your name', 'placeholder' => 'Jane Doe', 'required' => true]),
                    self::field(FieldType::Email, ['label' => 'Your email', 'placeholder' => 'jane@example.com', 'required' => true]),
                    self::field(FieldType::Select, ['label' => 'What is this about?', 'options' => ['General question', 'Support', 'Partnership', 'Something else']]),
                    self::field(FieldType::LongText, ['label' => 'Your message', 'placeholder' => 'How can we help?', 'required' => true]),
                ],
            ],
            'feedback' => [
                'name' => 'Product feedback',
                'description' => 'Measure satisfaction and find out what to build next.',
                'icon' => 'message',
                'category' => 'Essentials',
                'fields' => [
                    self::field(FieldType::Rating, ['label' => 'How satisfied are you overall?', 'required' => true, 'max' => 5]),
                    self::field(FieldType::MultipleChoice, ['label' => 'How often do you use the product?', 'options' => ['Daily', 'Weekly', 'Monthly', 'Rarely']]),
                    self::field(FieldType::LongText, ['label' => 'What could we improve?', 'placeholder' => 'Be as honest as you like…']),
                    self::field(FieldType::Email, ['label' => 'Email (if we can follow up)', 'placeholder' => 'you@example.com']),
                ],
            ],
            'rsvp' => [
                'name' => 'Event RSVP',
                'description' => 'Collect registrations, ticket choices and dietary needs.',
                'icon' => 'calendar',
                'category' => 'Events',
                'fields' => [
                    self::field(FieldType::ShortText, ['label' => 'Full name', 'required' => true]),
                    self::field(FieldType::Email, ['label' => 'Email', 'required' => true]),
                    self::field(FieldType::Select, ['label' => 'Will you attend?', 'required' => true, 'options' => ['In person', 'Online', "Can't make it"]]),
                    self::field(FieldType::MultipleChoice, ['label' => 'Dietary preference', 'options' => ['Everything', 'Vegetarian', 'Vegan', 'Gluten-free']]),
                    self::field(FieldType::LongText, ['label' => 'Anything we should know?']),
                ],
            ],
            'job-application' => [
                'name' => 'Job application',
                'description' => 'A clean application flow with portfolio link and cover letter.',
                'icon' => 'user',
                'category' => 'Hiring',
                'fields' => [
                    self::field(FieldType::ShortText, ['label' => 'Full name', 'required' => true]),
                    self::field(FieldType::Email, ['label' => 'Email', 'required' => true]),
                    self::field(FieldType::Phone, ['label' => 'Phone number']),
                    self::field(FieldType::Url, ['label' => 'Portfolio or LinkedIn', 'placeholder' => 'https://', 'required' => true]),
                    self::field(FieldType::Number, ['label' => 'Years of relevant experience']),
                    self::field(FieldType::Date, ['label' => 'Earliest start date']),
                    self::field(FieldType::LongText, ['label' => 'Why do you want this role?', 'required' => true]),
                ],
            ],
            'newsletter' => [
                'name' => 'Newsletter signup',
                'description' => 'The shortest path from visitor to subscriber.',
                'icon' => 'send',
                'category' => 'Marketing',
                'fields' => [
                    self::field(FieldType::Email, ['label' => 'Your email', 'placeholder' => 'you@example.com', 'required' => true]),
                    self::field(FieldType::ShortText, ['label' => 'First name', 'placeholder' => 'So we can say hi properly']),
                ],
            ],
            'onboarding' => [
                'name' => 'Customer onboarding',
                'description' => 'Learn who your new users are and what they need.',
                'icon' => 'rocket',
                'category' => 'Product',
                'fields' => [
                    self::field(FieldType::ShortText, ['label' => "What's your role?", 'placeholder' => 'e.g. Founder, PM, Engineer']),
                    self::field(FieldType::Select, ['label' => 'How big is your team?', 'options' => ['Just me', '2–10', '11–50', '51–200', '200+']]),
                    self::field(FieldType::Checkboxes, ['label' => 'What do you want to achieve?', 'options' => ['Collect leads', 'Run surveys', 'Hire people', 'Plan events', 'Something else']]),
                    self::field(FieldType::LongText, ['label' => 'Anything else we should know?']),
                ],
            ],
            'bug-report' => [
                'name' => 'Bug report',
                'description' => 'Structured reports your engineers will actually thank you for.',
                'icon' => 'alert',
                'category' => 'Product',
                'fields' => [
                    self::field(FieldType::ShortText, ['label' => 'Summarize the bug in one line', 'required' => true]),
                    self::field(FieldType::Select, ['label' => 'How severe is it?', 'required' => true, 'options' => ['Blocking — I cannot continue', 'Major — workaround exists', 'Minor — cosmetic']]),
                    self::field(FieldType::LongText, ['label' => 'Steps to reproduce', 'placeholder' => "1. Go to…\n2. Click…\n3. See error", 'required' => true]),
                    self::field(FieldType::Url, ['label' => 'Link to a screenshot or recording']),
                    self::field(FieldType::Email, ['label' => 'Your email (for follow-up)']),
                ],
            ],
            'poll' => [
                'name' => 'Quick poll',
                'description' => 'One question, instant pulse-check. Perfect for socials.',
                'icon' => 'chart',
                'category' => 'Marketing',
                'fields' => [
                    self::field(FieldType::MultipleChoice, ['label' => 'Tabs or spaces?', 'required' => true, 'options' => ['Tabs', 'Spaces', 'Whatever the formatter says']]),
                ],
            ],
        ];
    }

    /**
     * @return array{name: string, description: string, icon: string, category: string, fields: list<array<string, mixed>>}|null
     */
    public static function find(string $key): ?array
    {
        return static::all()[$key] ?? null;
    }

    /**
     * Create a new draft form for the user from a template.
     */
    public static function createForUser(string $key, int $userId): ?Form
    {
        $template = static::find($key);

        if ($template === null) {
            return null;
        }

        return Form::create([
            'user_id' => $userId,
            'name' => $template['name'],
            'status' => Form::STATUS_DRAFT,
            'fields' => $template['fields'],
        ]);
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    protected static function field(FieldType $type, array $overrides = []): array
    {
        return array_merge(Form::makeField($type), $overrides);
    }
}
