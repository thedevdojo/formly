<?php

use App\Models\Form;
use App\Models\FormEntry;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->form = Form::factory()->published()->for($this->user)->create();
});

test('the responses page renders for the owner', function () {
    FormEntry::factory()->forForm($this->form)->create();

    $this->actingAs($this->user)
        ->get(route('forms.responses', ['form' => $this->form]))
        ->assertOk()
        ->assertSee('Responses');
});

test('the responses page is hidden from other users', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('forms.responses', ['form' => $this->form]))
        ->assertNotFound();
});

test('opening an entry marks it as read', function () {
    $entry = FormEntry::factory()->forForm($this->form)->create(['read_at' => null]);

    Livewire::actingAs($this->user)
        ->test('form-entries', ['form' => $this->form])
        ->call('select', $entry->id);

    expect($entry->refresh()->read_at)->not->toBeNull();
});

test('an entry can be deleted', function () {
    $entry = FormEntry::factory()->forForm($this->form)->create();

    Livewire::actingAs($this->user)
        ->test('form-entries', ['form' => $this->form])
        ->call('deleteEntry', $entry->id);

    expect(FormEntry::find($entry->id))->toBeNull();
});

test('responses can be exported as csv', function () {
    $entry = FormEntry::factory()->forForm($this->form)->create();

    $response = Livewire::actingAs($this->user)
        ->test('form-entries', ['form' => $this->form])
        ->call('export');

    $response->assertFileDownloaded();
});

test('answers display formats arrays nicely', function () {
    $entry = FormEntry::factory()->create([
        'form_id' => $this->form->id,
        'answers' => ['fld_x' => ['One', 'Two']],
    ]);

    expect($entry->answerFor('fld_x'))->toBe('One, Two')
        ->and($entry->answerFor('missing'))->toBeNull();
});
