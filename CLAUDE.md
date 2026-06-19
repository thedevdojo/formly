<laravel-boost-guidelines>
=== foundation rules ===

# Laravel Boost Guidelines

The Laravel Boost guidelines are specifically curated by Laravel maintainers for this application. These guidelines should be followed closely to ensure the best experience when building Laravel applications.

## Foundational Context

This application is a Laravel application and its main Laravel ecosystems package & versions are below. You are an expert with them all. Ensure you abide by these specific packages & versions.

- php - 8.4
- laravel/folio (FOLIO) - v1
- laravel/framework (LARAVEL) - v13
- laravel/prompts (PROMPTS) - v0
- laravel/socialite (SOCIALITE) - v5
- livewire/livewire (LIVEWIRE) - v4
- livewire/volt (VOLT) - v1
- laravel/boost (BOOST) - v2
- laravel/mcp (MCP) - v0
- laravel/pail (PAIL) - v1
- laravel/pint (PINT) - v1
- laravel/sail (SAIL) - v1
- pestphp/pest (PEST) - v4
- phpunit/phpunit (PHPUNIT) - v12
- tailwindcss (TAILWINDCSS) - v4

## Skills Activation

This project has domain-specific skills available in `**/skills/**`. You MUST activate the relevant skill whenever you work in that domain—don't wait until you're stuck.

## Conventions

- You must follow all existing code conventions used in this application. When creating or editing a file, check sibling files for the correct structure, approach, and naming.
- Use descriptive names for variables and methods. For example, `isRegisteredForDiscounts`, not `discount()`.
- Check for existing components to reuse before writing a new one.

## Verification Scripts

- Do not create verification scripts or tinker when tests cover that functionality and prove they work. Unit and feature tests are more important.

## Application Structure & Architecture

- Stick to existing directory structure; don't create new base folders without approval.
- Do not change the application's dependencies without approval.

## Frontend Bundling

- If the user doesn't see a frontend change reflected in the UI, it could mean they need to run `npm run build`, `npm run dev`, or `composer run dev`. Ask them.

## Documentation Files

- You must only create documentation files if explicitly requested by the user.

## Replies

- Be concise in your explanations - focus on what's important rather than explaining obvious details.

=== boost rules ===

# Laravel Boost

## Tools

- Laravel Boost is an MCP server with tools designed specifically for this application. Prefer Boost tools over manual alternatives like shell commands or file reads.
- Use `database-query` to run read-only queries against the database instead of writing raw SQL in tinker.
- Use `database-schema` to inspect table structure before writing migrations or models.
- Use `get-absolute-url` to resolve the correct scheme, domain, and port for project URLs. Always use this before sharing a URL with the user.
- Use `browser-logs` to read browser logs, errors, and exceptions. Only recent logs are useful, ignore old entries.

## Searching Documentation (IMPORTANT)

- Always use `search-docs` before making code changes. Do not skip this step. It returns version-specific docs based on installed packages automatically.
- Pass a `packages` array to scope results when you know which packages are relevant.
- Use multiple broad, topic-based queries: `['rate limiting', 'routing rate limiting', 'routing']`. Expect the most relevant results first.
- Do not add package names to queries because package info is already shared. Use `test resource table`, not `filament 4 test resource table`.

### Search Syntax

1. Use words for auto-stemmed AND logic: `rate limit` matches both "rate" AND "limit".
2. Use `"quoted phrases"` for exact position matching: `"infinite scroll"` requires adjacent words in order.
3. Combine words and phrases for mixed queries: `middleware "rate limit"`.
4. Use multiple queries for OR logic: `queries=["authentication", "middleware"]`.

## Artisan

- Run Artisan commands directly via the command line (e.g., `php artisan route:list`). Use `php artisan list` to discover available commands and `php artisan [command] --help` to check parameters.
- Inspect routes with `php artisan route:list`. Filter with: `--method=GET`, `--name=users`, `--path=api`, `--except-vendor`, `--only-vendor`.
- Read configuration values using dot notation: `php artisan config:show app.name`, `php artisan config:show database.default`. Or read config files directly from the `config/` directory.

## Tinker

- Execute PHP in app context for debugging and testing code. Do not create models without user approval, prefer tests with factories instead. Prefer existing Artisan commands over custom tinker code.
- Always use single quotes to prevent shell expansion: `php artisan tinker --execute 'Your::code();'`
  - Double quotes for PHP strings inside: `php artisan tinker --execute 'User::where("active", true)->count();'`

=== php rules ===

# PHP

- Always use curly braces for control structures, even for single-line bodies.
- Use PHP 8 constructor property promotion: `public function __construct(public GitHub $github) { }`. Do not leave empty zero-parameter `__construct()` methods unless the constructor is private.
- Use explicit return type declarations and type hints for all method parameters: `function isAccessible(User $user, ?string $path = null): bool`
- Use TitleCase for Enum keys: `FavoritePerson`, `BestLake`, `Monthly`.
- Prefer PHPDoc blocks over inline comments. Only add inline comments for exceptionally complex logic.
- Use array shape type definitions in PHPDoc blocks.

=== deployments rules ===

# Deployment

- Laravel can be deployed using [Laravel Cloud](https://cloud.laravel.com/), which is the fastest way to deploy and scale production Laravel applications.

=== herd rules ===

# Laravel Herd

- The application is served by Laravel Herd at `https?://[kebab-case-project-dir].test`. Use the `get-absolute-url` tool to generate valid URLs. Never run commands to serve the site. It is always available.
- Use the `herd` CLI to manage services, PHP versions, and sites (e.g. `herd sites`, `herd services:start <service>`, `herd php:list`). Run `herd list` to discover all available commands.

=== tests rules ===

# Test Enforcement

- Every change must be programmatically tested. Write a new test or update an existing test, then run the affected tests to make sure they pass.
- Run the minimum number of tests needed to ensure code quality and speed. Use `php artisan test --compact` with a specific filename or filter.

=== folio/core rules ===

# Laravel Folio

- Laravel Folio is a powerful page-based router that simplifies routing in Laravel applications.
- Routes are generated automatically by creating Blade templates in `resources/views/pages`.
- IMPORTANT: Activate 'folio-routing' when working with Folio, pages, routes, route parameters, model binding, middleware, or `resources/views/pages`.

=== laravel/core rules ===

# Do Things the Laravel Way

- Use `php artisan make:` commands to create new files (i.e. migrations, controllers, models, etc.). You can list available Artisan commands using `php artisan list` and check their parameters with `php artisan [command] --help`.
- If you're creating a generic PHP class, use `php artisan make:class`.
- Pass `--no-interaction` to all Artisan commands to ensure they work without user input. You should also pass the correct `--options` to ensure correct behavior.

### Model Creation

- When creating new models, create useful factories and seeders for them too. Ask the user if they need any other things, using `php artisan make:model --help` to check the available options.

## APIs & Eloquent Resources

- For APIs, default to using Eloquent API Resources and API versioning unless existing API routes do not, then you should follow existing application convention.

## URL Generation

- When generating links to other pages, prefer named routes and the `route()` function.

## Testing

- When creating models for tests, use the factories for the models. Check if the factory has custom states that can be used before manually setting up the model.
- Faker: Use methods such as `$this->faker->word()` or `fake()->randomDigit()`. Follow existing conventions whether to use `$this->faker` or `fake()`.
- When creating tests, make use of `php artisan make:test [options] {name}` to create a feature test, and pass `--unit` to create a unit test. Most tests should be feature tests.

## Vite Error

- If you receive an "Illuminate\Foundation\ViteException: Unable to locate file in Vite manifest" error, you can run `npm run build` or ask the user to run `npm run dev` or `composer run dev`.

=== livewire/core rules ===

# Livewire

- Livewire allow to build dynamic, reactive interfaces in PHP without writing JavaScript.
- You can use Alpine.js for client-side interactions instead of JavaScript frameworks.
- Keep state server-side so the UI reflects it. Validate and authorize in actions as you would in HTTP requests.

=== volt/core rules ===

# Livewire Volt

- Single-file Livewire components: PHP logic and Blade templates in one file.
- Always check existing Volt components to determine functional vs class-based style.
- IMPORTANT: Always use `search-docs` tool for version-specific Volt documentation and updated code examples.
- IMPORTANT: Activate `volt-development` every time you're working with a Volt or single-file component-related task.

=== pint/core rules ===

# Laravel Pint Code Formatter

- If you have modified any PHP files, you must run `vendor/bin/pint --dirty --format agent` before finalizing changes to ensure your code matches the project's expected style.
- Do not run `vendor/bin/pint --test --format agent`, simply run `vendor/bin/pint --format agent` to fix any formatting issues.

=== pest/core rules ===

## Pest

- This project uses Pest for testing. Create tests: `php artisan make:test --pest {name}`.
- The `{name}` argument should not include the test suite directory. Use `php artisan make:test --pest SomeFeatureTest` instead of `php artisan make:test --pest Feature/SomeFeatureTest`.
- Run tests: `php artisan test --compact` or filter: `php artisan test --compact --filter=testName`.
- Do NOT delete tests without approval.

</laravel-boost-guidelines>

<!--
  The section below is project-specific documentation maintained by hand.
  It lives OUTSIDE the <laravel-boost-guidelines> block so it is preserved when
  Laravel Boost regenerates its guidelines (`php artisan boost:update`).
-->

# The DevDojo Platform

## What this project is

This is the **DevDojo Platform** — the host Laravel application a developer installs and
builds their own SaaS on. On its own it's a deliberately thin app; its features come from the
**DevDojo feature packages**, which are bundled and coordinated by the single
**`devdojo/foundation`** metapackage.

The core idea: install one package (`devdojo/foundation`) and you get the whole feature set —
authentication, billing, blog, changelog, notifications, profiles — with a **runtime feature
toggle** deciding which parts are active. Turning a feature on or off never requires a Composer
operation.

The packages are developed in-repo, monorepo-style, under `packages/devdojo/*`, and the app
loads them from there via a Composer **path repository**.

> **Scope of this repo.** This is the **self-install app** — the codebase a developer clones,
> wires up, and builds on. The managed/hosted version of DevDojo is a separate product and is
> **not** covered here. Everything in this file is about helping you (and Claude) build a great
> app on top of `devdojo/foundation` and the feature packages in this folder.

## The mental model (read this first)

One boundary governs everything else in this project. Internalize it before writing code:

- **Your app code lives in `app/`, `routes/`, and `resources/views/`.** This is yours — add
  controllers, Folio pages, Livewire/Volt components, and routes freely.
- **Platform features live in the packages**, loaded into `vendor/` from `packages/devdojo/*`.
  Treat them as upstream: don't edit `vendor/` to change behavior.
- **To customize a package**, publish its views/config and edit the *published* copy in your
  app — never fork the package.
- **To move the platform forward**, bump `devdojo/foundation`; the feature set updates without
  you touching app code.

Keeping that boundary clean is what lets you take upstream improvements without merge pain. The
moment feature logic leaks into `app/`, the upgrade path erodes.

## How it's organized

```
platform/                          ← this app (Laravel 13 + Livewire 4, PHP 8.4)
├── app/  bootstrap/  config/  …   ← a standard Laravel application — YOUR code
├── composer.json                  ← path repository → packages/devdojo/*
└── packages/devdojo/              ← the DevDojo packages (developed in place)
    ├── foundation/                ← devdojo/foundation — the metapackage / mothership
    ├── auth/                      ← devdojo/auth          (Devdojo\Auth)
    ├── billing/                   ← devdojo/billing       (Devdojo\Billing)
    ├── blog/                      ← devdojo/blog          (Devdojo\Blog)
    ├── changelog/                 ← devdojo/changelog     (Devdojo\Changelog)
    ├── notifications/             ← devdojo/notifications (Devdojo\Notifications)
    ├── profiles/                  ← devdojo/profiles      (Devdojo\Profiles)
    └── database/                  ← devdojo/database      (DevDojo\Database)
```

### The packages

| Package | Namespace | Responsibility |
| --- | --- | --- |
| `devdojo/foundation` | `Devdojo\Foundation` | Metapackage — requires the feature packages, adds feature toggles, the `foundation:install` command, and the `/foundation/setup` screen. |
| `devdojo/auth` | `Devdojo\Auth` | Authentication, registration, sessions, social login, 2FA. |
| `devdojo/billing` | `Devdojo\Billing` | Subscriptions, plans, checkout (Stripe/Paddle), feature limits. |
| `devdojo/blog` | `Devdojo\Blog` | Posts & categories + Filament admin. |
| `devdojo/changelog` | `Devdojo\Changelog` | Product changelog with per-user read tracking. |
| `devdojo/notifications` | `Devdojo\Notifications` | In-app notifications + notification preferences. |
| `devdojo/profiles` | `Devdojo\Profiles` | Public user profiles, dynamic profile fields, social links, privacy. |
| `devdojo/database` | `DevDojo\Database` | Livewire database browser/editor (standalone utility; **not** bundled by foundation). |

> Note the namespace casing: the feature packages use `Devdojo\…`, while `database` uses
> `DevDojo\…`. That's intentional/historical — match each package's existing casing exactly.

Each package has its own `README.md` with full docs and is the source of truth for that
feature's API. They follow a consistent shape: an auto-discovered service provider, models
under `src/Models`, **publish-only** migrations, optional Filament resources exposed via a
`*Plugin`, and (where relevant) traits you add to the host `User` model.

## How the packages are linked (path repository)

`composer.json` registers a path repository so any `devdojo/*` requirement resolves to the
local folder instead of Packagist:

```json
"repositories": [
    { "type": "path", "url": "packages/devdojo/*", "options": { "symlink": true } }
]
```

With this in place, requiring a DevDojo package symlinks it from `packages/devdojo/<name>` into
`vendor/`, so edits in `packages/` are live immediately. The single intended top-level
requirement is the metapackage:

```json
"require": {
    "devdojo/foundation": "*"
}
```

`devdojo/foundation` then pulls in `auth`, `billing`, `blog`, `changelog`, `notifications`, and
`profiles` from the same path repository.

> The local `auth` and `database` packages have no `version` field, so they resolve as
> `dev-main`. To require them you need `"minimum-stability": "dev"` (with `"prefer-stable":
> true`) **or** a `version` field added to those packages.

## Stack & compatibility

Runs on **Laravel 13 + Livewire 4** (PHP 8.4); `devdojo/foundation` is installed, discovered,
and booting on this stack. The local `packages/devdojo/*` constraints were widened to span
Laravel 11–13 (`^11.0|^12.0|^13.0`) and Livewire 3–4 (`^3.0|^4.0`).

Resolved third-party versions: **Livewire 4.3, Volt 1.10, Folio 1.1, Socialite 5.27,
spatie/laravel-permission 8.0**. `minimum-stability` is `dev` (with `prefer-stable: true`)
because the local `auth`/`database` packages resolve as `dev-main`.

## Installing & wiring a fresh app

`foundation` boots out of the box, but a few **app-level** steps make every feature functional
end-to-end. Run these once on a fresh install:

1. **Database** — package migrations are publish-only, and several assume a Wave-style `users`
   table (columns like `username`, `avatar`, `role_id`, `trial_ends_at`) plus Spatie's
   permission tables. Publish them, add the missing user columns, then migrate:

   ```bash
   php artisan vendor:publish --tag=auth:migrations --tag=billing:migrations \
       --tag=blog:migrations --tag=changelog:migrations \
       --tag=notifications:migrations --tag=profiles:migrations
   php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
   php artisan foundation:install   # publishes foundation config, migrates, seeds flags
   ```

2. **User model** — add the package traits to `App\Models\User` (and the matching columns):
   `Devdojo\Billing\Traits\{HasSubscriptions, HasPlanFeatures}`,
   `Devdojo\Changelog\Traits\HasChangelogs`,
   `Devdojo\Notifications\Traits\HasNotificationPreferences`,
   `Devdojo\Profiles\Traits\{HasProfileKeyValues, HasDynamicFields}`, plus Spatie's `HasRoles`.
   See each package's `README.md` for exact wiring.

3. **Admin (optional)** — install **Filament 5** (the Livewire 4-compatible major) and register
   the plugins in your panel (`BillingPlugin`, `BlogPlugin`, `ChangelogPlugin`). The resources
   were authored for Filament 4 and may need Filament 4 → 5 tweaks.

## Feature toggles

`devdojo/foundation` decides which feature packages are **active** at runtime — independent of
what's installed:

- Defaults live in `config/foundation.php` under `features` (all on by default).
- Per-app overrides are stored in the `foundation_settings` table and merged over the defaults
  at boot; **`/foundation/setup`** is the UI for flipping them.
- Each feature package self-gates in its service provider via
  `config('foundation.features.<name>', true)` — default-on when no foundation config is
  present, so every package still works standalone.
- Dependencies are enforced (`config('foundation.depends')`): e.g. enabling `billing`, `blog`,
  or `profiles` auto-enables `auth`.

Read the effective state in code with `Devdojo\Foundation\Foundation::features()` /
`Foundation::enabled('billing')`.

## Building your app with the packages

Practical guidance for shipping features on top of the platform:

- **Use the packages' features instead of rebuilding them.** Auth (login, registration, social,
  2FA), billing (subscriptions, plans, checkout, feature limits), profiles, blog, changelog, and
  notifications already exist — wire them in rather than reinventing.
- **The User-model traits are the supported integration surface.** Reach for
  `HasSubscriptions`, `HasPlanFeatures`, `HasChangelogs`, `HasNotificationPreferences`,
  `HasProfileKeyValues`, `HasDynamicFields` (and Spatie's `HasRoles`) rather than querying
  package tables directly.
- **Gate features and authorize accordingly.** Before exposing a feature's UI or relying on it,
  check `Foundation::enabled('<name>')` so a disabled feature degrades cleanly. Use the same
  pattern (`config('foundation.features.<name>', true)`) if you add your own toggleable feature.
- **Pages & routing:** Folio is active — add Blade files under `resources/views/pages` for page
  routes, and prefer named routes + `route()` for links.
- **Interactivity:** Livewire 4 + Volt. Check existing Volt components for functional vs.
  class-based style before adding one, and keep state server-side.
- **Customize, don't fork:** publish a package's views/config and edit the published copy in your
  app. Leave `packages/devdojo/*` and `vendor/` alone unless you're working on the packages
  themselves.
- **Read the package README before integrating a feature** — it's the authoritative API doc.

## Filament admin

Feature packages ship their admin UIs as Filament plugins (e.g.
`Devdojo\Billing\Filament\BillingPlugin`, `Devdojo\Blog\Filament\BlogPlugin`,
`Devdojo\Changelog\Filament\ChangelogPlugin`). Register them in your panel provider once
Filament is installed and the packages are wired:

```php
->plugin(\Devdojo\Billing\Filament\BillingPlugin::make())
->plugin(\Devdojo\Blog\Filament\BlogPlugin::make())
->plugin(\Devdojo\Changelog\Filament\ChangelogPlugin::make())
```

Plugin registration is also gated by the feature flags, so a disabled feature contributes no
admin resources.

## Working in this repo

- **Edit packages in place** under `packages/devdojo/<name>` — they're symlinked into `vendor/`,
  so changes are live (run `composer dump-autoload` after adding new classes).
- Each package's migrations are **publish-only**; `foundation:install` runs the published set.
- **Adding a brand-new feature package:** drop it under `packages/devdojo/<name>`, add it to
  `devdojo/foundation`'s `require` block and to the `features`/`depends` maps, and gate its
  provider on `config('foundation.features.<name>', true)`.
- Before finalizing PHP changes, run `vendor/bin/pint --dirty --format agent`, prefer Pest
  feature tests over verification scripts, and use the Boost `search-docs` tool for
  version-specific docs.
