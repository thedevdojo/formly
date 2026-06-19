<?php

namespace Database\Seeders;

use App\Enums\FieldType;
use App\Models\Form;
use App\Models\FormEntry;
use App\Models\User;
use Devdojo\Changelog\Models\Changelog;
use Illuminate\Database\Seeder;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        $demo = User::factory()->create([
            'name' => 'Demo User',
            'username' => 'demo',
            'email' => 'demo@formly.test',
            'title' => 'Maker',
        ]);
        $demo->assignRole('admin');

        $this->seedProductFeedback($demo);
        $this->seedEventRegistration($demo);
        $this->seedJobApplication($demo);
        $this->seedNewsletterSignup($demo);
        $this->seedOnboardingDraft($demo);
        $this->seedChangelog();
    }

    protected function seedProductFeedback(User $user): void
    {
        $form = Form::create([
            'user_id' => $user->id,
            'name' => 'Product Feedback',
            'status' => Form::STATUS_PUBLISHED,
            'published_at' => now()->subDays(34),
            'fields' => [
                $this->field(FieldType::Statement, [
                    'label' => 'Help us make Formly better',
                    'body' => 'This takes about 60 seconds. Every answer is read by a real human on the product team.',
                ]),
                $this->field(FieldType::ShortText, ['label' => "What's your name?", 'placeholder' => 'Ada Lovelace', 'required' => true]),
                $this->field(FieldType::Email, ['label' => "What's your email?", 'placeholder' => 'you@example.com', 'required' => true]),
                $this->field(FieldType::Rating, ['label' => 'How satisfied are you with Formly?', 'required' => true, 'max' => 5]),
                $this->field(FieldType::Checkboxes, [
                    'label' => 'Which features do you use the most?',
                    'options' => ['Form builder', 'Response inbox', 'CSV export', 'Sharing links', 'Templates'],
                ]),
                $this->field(FieldType::MultipleChoice, [
                    'label' => 'How did you hear about us?',
                    'options' => ['Twitter / X', 'A friend', 'Search', 'Newsletter', 'Other'],
                ]),
                $this->field(FieldType::LongText, ['label' => 'What could we improve?', 'placeholder' => 'Be as honest as you like…']),
            ],
        ]);

        $this->entries($form, 48, 34);
    }

    protected function seedEventRegistration(User $user): void
    {
        $form = Form::create([
            'user_id' => $user->id,
            'name' => 'Launch Week — RSVP',
            'status' => Form::STATUS_PUBLISHED,
            'published_at' => now()->subDays(18),
            'settings' => array_merge(Form::defaultSettings(), [
                'success_title' => "You're on the list! 🎉",
                'success_message' => 'We just sent a calendar invite to your inbox. See you at Launch Week.',
            ]),
            'fields' => [
                $this->field(FieldType::ShortText, ['label' => 'Full name', 'placeholder' => 'Grace Hopper', 'required' => true]),
                $this->field(FieldType::Email, ['label' => 'Work email', 'placeholder' => 'you@company.com', 'required' => true]),
                $this->field(FieldType::ShortText, ['label' => 'Company', 'placeholder' => 'Acme Inc.']),
                $this->field(FieldType::Select, [
                    'label' => 'Ticket type',
                    'required' => true,
                    'options' => ['General admission', 'VIP', 'Online only'],
                ]),
                $this->field(FieldType::MultipleChoice, [
                    'label' => 'Dietary preference',
                    'options' => ['Everything', 'Vegetarian', 'Vegan', 'Gluten-free'],
                ]),
                $this->field(FieldType::LongText, ['label' => 'Anything we should know?']),
            ],
        ]);

        $this->entries($form, 31, 18);
    }

    protected function seedJobApplication(User $user): void
    {
        $form = Form::create([
            'user_id' => $user->id,
            'name' => 'Senior Designer — Application',
            'status' => Form::STATUS_PUBLISHED,
            'published_at' => now()->subDays(12),
            'fields' => [
                $this->field(FieldType::Statement, [
                    'label' => 'Senior Product Designer',
                    'body' => 'Remote (anywhere ±4h UTC) · Full-time · We review every application within a week.',
                ]),
                $this->field(FieldType::ShortText, ['label' => 'Full name', 'required' => true]),
                $this->field(FieldType::Email, ['label' => 'Email', 'required' => true]),
                $this->field(FieldType::Phone, ['label' => 'Phone number']),
                $this->field(FieldType::Url, ['label' => 'Portfolio or website', 'placeholder' => 'https://', 'required' => true]),
                $this->field(FieldType::Number, ['label' => 'Years of product design experience', 'required' => true]),
                $this->field(FieldType::Date, ['label' => 'Earliest start date']),
                $this->field(FieldType::LongText, ['label' => "Why Formly? Tell us what you'd bring to the team.", 'required' => true]),
            ],
        ]);

        $this->entries($form, 14, 12);
    }

    protected function seedNewsletterSignup(User $user): void
    {
        $form = Form::create([
            'user_id' => $user->id,
            'name' => 'Newsletter Signup',
            'status' => Form::STATUS_CLOSED,
            'published_at' => now()->subDays(60),
            'settings' => array_merge(Form::defaultSettings(), [
                'closed_message' => 'Signups are closed for now — back soon with a fresh issue!',
            ]),
            'fields' => [
                $this->field(FieldType::Email, ['label' => 'Your email', 'placeholder' => 'you@example.com', 'required' => true]),
                $this->field(FieldType::ShortText, ['label' => 'First name', 'placeholder' => 'So we can say hi properly']),
            ],
        ]);

        $this->entries($form, 67, 55);
    }

    protected function seedOnboardingDraft(User $user): void
    {
        Form::create([
            'user_id' => $user->id,
            'name' => 'Customer Onboarding Survey',
            'status' => Form::STATUS_DRAFT,
            'fields' => [
                $this->field(FieldType::ShortText, ['label' => "What's your role?", 'placeholder' => 'e.g. Founder, PM, Engineer']),
                $this->field(FieldType::Select, [
                    'label' => 'How big is your team?',
                    'options' => ['Just me', '2–10', '11–50', '51–200', '200+'],
                ]),
                $this->field(FieldType::LongText, ['label' => 'What are you hoping to build with Formly?']),
            ],
        ]);
    }

    /**
     * Create $count entries for the form, spread over the last $days days.
     */
    protected function entries(Form $form, int $count, int $days): void
    {
        for ($i = 0; $i < $count; $i++) {
            $createdAt = now()->subDays(rand(0, $days))->subMinutes(rand(0, 1440));

            $entry = FormEntry::factory()->forForm($form)->create([
                'read_at' => rand(0, 3) > 0 ? $createdAt->copy()->addHours(rand(1, 48)) : null,
            ]);

            $entry->forceFill(['created_at' => $createdAt, 'updated_at' => $createdAt])->save();
        }
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    protected function field(FieldType $type, array $overrides = []): array
    {
        return array_merge(Form::makeField($type), $overrides);
    }

    protected function seedChangelog(): void
    {
        $releases = [
            [
                'title' => 'Ratings, dates & phone fields',
                'description' => 'Three new field types land in the builder today.',
                'body' => "You can now collect star ratings, dates and phone numbers on any form. Find them in the add-block menu under Data and Contact.\n\nAs always, new field types work instantly on every published form — no re-publishing required.",
            ],
            [
                'title' => 'CSV export for responses',
                'description' => 'Download every response on a form with one click.',
                'body' => "Head to any form's Responses tab and hit Export CSV. Columns map 1:1 to your form's questions, and the export respects your current filters.",
            ],
            [
                'title' => 'A faster, calmer builder',
                'description' => 'The form builder got a full redesign.',
                'body' => "The editor is now a true document: click any question to edit it inline, drag blocks to reorder, and use the + menu to insert anything anywhere.\n\nWe also cut page weight by 40% — publishing a form now feels instant.",
            ],
        ];

        foreach ($releases as $i => $release) {
            $changelog = Changelog::create($release);
            $changelog->forceFill([
                'created_at' => now()->subDays(7 * ($i + 1)),
                'updated_at' => now()->subDays(7 * ($i + 1)),
            ])->save();
        }
    }
}
