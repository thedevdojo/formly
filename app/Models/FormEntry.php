<?php

namespace App\Models;

use Database\Factories\FormEntryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FormEntry extends Model
{
    /** @use HasFactory<FormEntryFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'form_id',
        'answers',
        'meta',
        'read_at',
        'created_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'answers' => 'array',
            'meta' => 'array',
            'read_at' => 'datetime',
        ];
    }

    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class);
    }

    /**
     * The stored answer for a given field id, formatted for display.
     */
    public function answerFor(string $fieldId): ?string
    {
        $value = $this->answers[$fieldId] ?? null;

        if ($value === null || $value === '' || $value === []) {
            return null;
        }

        return is_array($value) ? implode(', ', $value) : (string) $value;
    }
}
