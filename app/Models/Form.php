<?php

namespace App\Models;

use App\Enums\FieldType;
use Database\Factories\FormFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Form extends Model
{
    /** @use HasFactory<FormFactory> */
    use HasFactory;

    public const STATUS_DRAFT = 'draft';

    public const STATUS_PUBLISHED = 'published';

    public const STATUS_CLOSED = 'closed';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'status',
        'fields',
        'settings',
        'published_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'fields' => 'array',
            'settings' => 'array',
            'published_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Form $form) {
            $form->slug ??= static::generateSlug();
            $form->fields ??= [];
        });
    }

    /**
     * A short, URL-safe public identifier (e.g. "3kVbn9pQwZ").
     */
    public static function generateSlug(): string
    {
        do {
            $slug = Str::random(10);
        } while (static::where('slug', $slug)->exists());

        return $slug;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function entries(): HasMany
    {
        return $this->hasMany(FormEntry::class);
    }

    public function isPublished(): bool
    {
        return $this->status === self::STATUS_PUBLISHED;
    }

    public function isClosed(): bool
    {
        return $this->status === self::STATUS_CLOSED;
    }

    public function isDraft(): bool
    {
        return $this->status === self::STATUS_DRAFT;
    }

    /**
     * Public fill URL for this form.
     */
    public function publicUrl(): string
    {
        return route('forms.fill', ['slug' => $this->slug]);
    }

    /**
     * Read a setting with the template defaults applied.
     */
    public function setting(string $key): mixed
    {
        return data_get(array_merge(static::defaultSettings(), $this->settings ?? []), $key);
    }

    /**
     * @return array<string, mixed>
     */
    public static function defaultSettings(): array
    {
        return [
            'submit_label' => 'Submit',
            'success_title' => 'Thanks — your response is in!',
            'success_message' => 'We have received your submission. You can close this window now.',
            'closed_message' => 'This form is no longer accepting responses.',
            'show_branding' => true,
            'notify_email' => null,
        ];
    }

    /**
     * Blocks that collect an answer (excludes display-only blocks).
     *
     * @return list<array<string, mixed>>
     */
    public function inputFields(): array
    {
        return array_values(array_filter($this->fields ?? [], function (array $field) {
            $type = FieldType::tryFrom($field['type'] ?? '');

            return $type !== null && $type->isInput();
        }));
    }

    /**
     * A new block of the given type with a unique id.
     *
     * @return array<string, mixed>
     */
    public static function makeField(FieldType $type): array
    {
        return array_merge([
            'id' => 'fld_'.Str::lower(Str::random(8)),
            'type' => $type->value,
        ], $type->defaults());
    }
}
