<?php

use App\Enums\FieldType;
use App\Models\Form;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Livewire\Livewire;

beforeEach(function () {
    $this->user = User::factory()->create();
});

test('dashboard requires authentication', function () {
    $this->get('/dashboard')->assertRedirect();
});

test('dashboard lists the users forms', function () {
    $form = Form::factory()->for($this->user)->create(['name' => 'Customer Survey']);
    Form::factory()->create(['name' => 'Someone Elses Form']);

    $this->actingAs($this->user)
        ->get('/dashboard')
        ->assertOk()
        ->assertSee('Customer Survey')
        ->assertDontSee('Someone Elses Form');
});

test('a user can create a new form from the dashboard', function () {
    Livewire::actingAs($this->user)
        ->test('forms-index')
        ->call('createForm')
        ->assertRedirect();

    expect($this->user->forms()->count())->toBe(1)
        ->and($this->user->forms()->first()->name)->toBe('Untitled form');
});

test('a user can duplicate a form', function () {
    $form = Form::factory()->for($this->user)->create(['name' => 'Original']);

    Livewire::actingAs($this->user)
        ->test('forms-index')
        ->call('duplicateForm', $form->id);

    expect($this->user->forms()->count())->toBe(2)
        ->and($this->user->forms()->where('name', 'Original (copy)')->exists())->toBeTrue();
});

test('a user can delete a form', function () {
    $form = Form::factory()->for($this->user)->create();

    Livewire::actingAs($this->user)
        ->test('forms-index')
        ->call('deleteForm', $form->id);

    expect(Form::find($form->id))->toBeNull();
});

test('a user cannot delete another users form', function () {
    $form = Form::factory()->create();

    expect(fn () => Livewire::actingAs($this->user)
        ->test('forms-index')
        ->call('deleteForm', $form->id)
    )->toThrow(ModelNotFoundException::class);

    expect(Form::find($form->id))->not->toBeNull();
});

test('the builder page renders for the owner', function () {
    $form = Form::factory()->for($this->user)->create(['name' => 'My RSVP']);

    $this->actingAs($this->user)
        ->get(route('forms.edit', ['form' => $form]))
        ->assertOk()
        ->assertSee('My RSVP');
});

test('the builder page is hidden from other users', function () {
    $form = Form::factory()->create();

    $this->actingAs($this->user)
        ->get(route('forms.edit', ['form' => $form]))
        ->assertNotFound();
});

test('fields can be added removed and reordered in the builder', function () {
    $form = Form::factory()->for($this->user)->create(['fields' => []]);

    $component = Livewire::actingAs($this->user)
        ->test('form-builder', ['form' => $form])
        ->call('addField', FieldType::ShortText->value)
        ->call('addField', FieldType::Email->value);

    $fields = $form->refresh()->fields;
    expect($fields)->toHaveCount(2)
        ->and($fields[0]['type'])->toBe('short_text')
        ->and($fields[1]['type'])->toBe('email');

    // Move the email field to the top.
    $component->call('sortFields', $fields[1]['id'], 0);
    expect($form->refresh()->fields[0]['type'])->toBe('email');

    // Remove the email field.
    $component->call('removeField', $fields[1]['id']);
    $remaining = $form->refresh()->fields;
    expect($remaining)->toHaveCount(1)
        ->and($remaining[0]['type'])->toBe('short_text');
});

test('options can be managed on choice fields', function () {
    $form = Form::factory()->for($this->user)->create([
        'fields' => [array_merge(Form::makeField(FieldType::MultipleChoice), ['label' => 'Pick one'])],
    ]);

    Livewire::actingAs($this->user)
        ->test('form-builder', ['form' => $form])
        ->call('addOption', 0);

    expect($form->refresh()->fields[0]['options'])->toHaveCount(3);
});

test('publishing requires at least one input field', function () {
    $form = Form::factory()->for($this->user)->create(['fields' => []]);

    Livewire::actingAs($this->user)
        ->test('form-builder', ['form' => $form])
        ->call('publish');

    expect($form->refresh()->isDraft())->toBeTrue();
});

test('a form with questions can be published and unpublished', function () {
    $form = Form::factory()->for($this->user)->create();

    $component = Livewire::actingAs($this->user)
        ->test('form-builder', ['form' => $form])
        ->call('publish');

    expect($form->refresh()->isPublished())->toBeTrue()
        ->and($form->published_at)->not->toBeNull();

    $component->call('unpublish');
    expect($form->refresh()->isDraft())->toBeTrue();
});

test('the form name autosaves from the builder', function () {
    $form = Form::factory()->for($this->user)->create(['name' => 'Before']);

    Livewire::actingAs($this->user)
        ->test('form-builder', ['form' => $form])
        ->set('name', 'After');

    expect($form->refresh()->name)->toBe('After');
});
