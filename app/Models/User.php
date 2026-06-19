<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Devdojo\Auth\Models\User as AuthUser;
use Devdojo\Billing\Traits\HasPlanFeatures;
use Devdojo\Billing\Traits\HasSubscriptions;
use Devdojo\Changelog\Traits\HasChangelogs;
use Devdojo\Notifications\Traits\HasNotificationPreferences;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasRoles;

class User extends AuthUser
{
    /** @use HasFactory<UserFactory> */
    use HasChangelogs;

    use HasFactory;
    use HasNotificationPreferences;
    use HasPlanFeatures;
    use HasRoles;
    use HasSubscriptions;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'avatar',
        'title',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'two_factor_confirmed_at',
        'email_verified_at',
        'notification_preferences',
        'trial_ends_at',
    ];

    /**
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'notification_preferences' => 'array',
            'trial_ends_at' => 'datetime',
        ];
    }

    public function forms(): HasMany
    {
        return $this->hasMany(Form::class);
    }

    /**
     * Two-letter initials derived from the user's name.
     */
    public function initials(): string
    {
        $initials = Str::of($this->name ?: $this->email)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');

        return $initials !== '' ? strtoupper($initials) : 'U';
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }
}
