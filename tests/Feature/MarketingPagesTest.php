<?php

use App\Models\User;
use Database\Seeders\PlanSeeder;
use Livewire\Livewire;

test('the home page renders', function () {
    $this->get('/')
        ->assertOk()
        ->assertSee('results that matter');
});

test('the pricing page renders the billing plans', function () {
    $this->seed(PlanSeeder::class);

    $this->get('/pricing')
        ->assertOk()
        ->assertSee('Free')
        ->assertSee('Pro')
        ->assertSee('Business');
});

test('the templates page renders the gallery', function () {
    $this->get('/templates')
        ->assertOk()
        ->assertSee('Contact form')
        ->assertSee('Job application');
});

test('the changelog page renders', function () {
    $this->get('/changelog')
        ->assertOk()
        ->assertSee('Changelog');
});

test('using a template as a guest redirects to registration', function () {
    Livewire::test('template-gallery')
        ->call('useTemplate', 'contact')
        ->assertRedirect(url('/auth/register'));
});

test('using a template creates a draft form for the user', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test('template-gallery')
        ->call('useTemplate', 'contact')
        ->assertRedirect();

    $form = $user->forms()->first();
    expect($form)->not->toBeNull()
        ->and($form->name)->toBe('Contact form')
        ->and($form->fields)->not->toBeEmpty();
});

test('settings pages render', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->get(route('settings.account'))->assertOk();
    $this->actingAs($user)->get(route('settings.security'))->assertOk();
    $this->actingAs($user)->get(route('settings.billing'))->assertOk();
});
