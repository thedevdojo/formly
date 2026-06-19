# Formly

**A simple, beautiful form builder template for the DevDojo platform.** Build forms like writing a doc, share a link, and watch the responses roll in — think Tally or Typeform, ready for you to make your own.

Built with Laravel 13, Livewire 4, Alpine.js, Tailwind CSS 4, Folio, and the DevDojo Foundation packages (auth, billing, changelog, notifications).

## What's included

- **Marketing site** — landing page, pricing (wired to `devdojo/billing` plans), template gallery, and changelog.
- **Form builder** — a Tally-style document editor: click to type, press `/` to add a question, drag blocks to reorder, autosaves as you type. 12 field types (short/long text, email, phone, link, number, dropdown, multiple choice, checkboxes, date, rating, text block).
- **Public forms** — every form gets a clean `/f/{slug}` link with validation, a custom thank-you screen, closed states, and draft previews for the owner.
- **Response inbox** — table view per form, unread indicators, detail drawer, delete, and one-click CSV export.
- **Templates** — 8 ready-made form templates anyone can start from (`app/Support/FormTemplates.php`).
- **Accounts** — registration/login/2FA via `devdojo/auth`, account/security/billing settings, feature flags via `/foundation/setup`.

## Getting started

```bash
composer run setup     # install, .env, key, migrate, npm build
php artisan migrate:fresh --seed   # demo user + sample forms & responses
```

Visit the site (e.g. `http://formly.test` with Laravel Herd) and log in with the seeded demo account:

- **Email:** `demo@formly.test`
- **Password:** `password`

## Where things live

```
app/Enums/FieldType.php        ← field types, icons, validation rules
app/Models/Form.php            ← form + JSON field blocks + settings
app/Models/FormEntry.php       ← stored responses
app/Support/FormTemplates.php  ← the template gallery definitions
resources/views/pages/         ← Folio pages (marketing, dashboard, /f/{slug})
resources/views/livewire/      ← Livewire components (builder, fill, entries…)
resources/views/components/    ← layouts, icons, shared UI
```

Run the test suite with `php artisan test --compact`.
