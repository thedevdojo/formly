<?php

use App\Enums\FieldType;
use App\Models\Form;
use Livewire\Livewire;

function publishedForm(array $fields = []): Form
{
    return Form::factory()->published()->create(
        $fields === [] ? [] : ['fields' => $fields]
    );
}

test('a published form is publicly visible', function () {
    $form = publishedForm();

    $this->get(route('forms.fill', ['slug' => $form->slug]))
        ->assertOk()
        ->assertSee($form->name);
});

test('a draft form is hidden from the public', function () {
    $form = Form::factory()->create();

    $this->get(route('forms.fill', ['slug' => $form->slug]))->assertNotFound();
});

test('a draft form is visible to its owner as a preview', function () {
    $form = Form::factory()->create();

    $this->actingAs($form->user)
        ->get(route('forms.fill', ['slug' => $form->slug]))
        ->assertOk()
        ->assertSee('Draft preview');
});

test('a closed form shows the closed message', function () {
    $form = Form::factory()->closed()->create();

    $this->get(route('forms.fill', ['slug' => $form->slug]))
        ->assertOk()
        ->assertSee($form->setting('closed_message'));
});

test('a visitor can submit a published form', function () {
    $form = publishedForm();
    [$name, $email] = $form->inputFields();

    Livewire::test('form-fill', ['form' => $form, 'preview' => false])
        ->set('answers.'.$name['id'], 'Ada Lovelace')
        ->set('answers.'.$email['id'], 'ada@example.com')
        ->call('submit')
        ->assertSet('submitted', true);

    $entry = $form->entries()->first();
    expect($entry)->not->toBeNull()
        ->and($entry->answers[$name['id']])->toBe('Ada Lovelace')
        ->and($entry->answers[$email['id']])->toBe('ada@example.com');
});

test('required fields are validated', function () {
    $form = publishedForm();
    [$name, $email] = $form->inputFields();

    Livewire::test('form-fill', ['form' => $form, 'preview' => false])
        ->call('submit')
        ->assertHasErrors(['answers.'.$name['id'] => 'required'])
        ->assertSet('submitted', false);

    expect($form->entries()->count())->toBe(0);
});

test('email fields must contain a valid email', function () {
    $form = publishedForm();
    [$name, $email] = $form->inputFields();

    Livewire::test('form-fill', ['form' => $form, 'preview' => false])
        ->set('answers.'.$name['id'], 'Ada')
        ->set('answers.'.$email['id'], 'not-an-email')
        ->call('submit')
        ->assertHasErrors(['answers.'.$email['id'] => 'email']);
});

test('choice answers must be one of the offered options', function () {
    $choice = array_merge(Form::makeField(FieldType::MultipleChoice), [
        'label' => 'Pick one',
        'required' => true,
        'options' => ['Red', 'Blue'],
    ]);

    $form = publishedForm([$choice]);

    Livewire::test('form-fill', ['form' => $form, 'preview' => false])
        ->set('answers.'.$choice['id'], 'Green')
        ->call('submit')
        ->assertHasErrors(['answers.'.$choice['id']]);

    Livewire::test('form-fill', ['form' => $form, 'preview' => false])
        ->set('answers.'.$choice['id'], 'Blue')
        ->call('submit')
        ->assertHasNoErrors();
});

test('rating answers are bounded by the scale', function () {
    $rating = array_merge(Form::makeField(FieldType::Rating), [
        'label' => 'Rate us',
        'required' => true,
        'max' => 5,
    ]);

    $form = publishedForm([$rating]);

    Livewire::test('form-fill', ['form' => $form, 'preview' => false])
        ->set('answers.'.$rating['id'], '9')
        ->call('submit')
        ->assertHasErrors(['answers.'.$rating['id']]);
});

test('draft previews never store submissions', function () {
    $form = Form::factory()->create();
    [$name] = $form->inputFields();

    Livewire::actingAs($form->user)
        ->test('form-fill', ['form' => $form, 'preview' => true])
        ->set('answers.'.$name['id'], 'Test')
        ->call('submit')
        ->assertSet('submitted', false);

    expect($form->entries()->count())->toBe(0);
});

test('optional fields may be left blank', function () {
    $optional = array_merge(Form::makeField(FieldType::LongText), ['label' => 'Anything else?']);
    $form = publishedForm([$optional]);

    Livewire::test('form-fill', ['form' => $form, 'preview' => false])
        ->call('submit')
        ->assertHasNoErrors()
        ->assertSet('submitted', true);
});
