<?php

namespace App\Models;

use App\Enums\RoleEnum;
use App\Models\Concerns\District;
use App\Models\Concerns\Region;
use BezhanSalleh\FilamentShield\Traits\HasPanelShield;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasRoles;
    use HasPanelShield;

    protected $fillable = [
        'is_active',
        'name',
        'surname',
        'patronymic',
        'organization_id',
        'region_id',
        'district_id',
        'email',
        'password',
        'password_changed_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    protected $appends = [
        'profile_photo_url',
    ];

    protected $casts = [
        /*'name'       => 'encrypted',
        'surname'    => 'encrypted',
        'patronymic' => 'encrypted',
        'email'      => 'encrypted',*/
        'password'   => 'hashed',
        'email_verified_at' => 'datetime',
    ];

    public function verifyTwoFactorCode($code): bool
    {
        return app(TwoFactorAuthenticatable::class)->verifyCode($this->two_factor_secret, $code);
    }

    public function verifyRecoveryCode($code): bool
    {
        $recoveryCodes = json_decode(decrypt($this->two_factor_recovery_codes), true);

        if (in_array($code, $recoveryCodes)) {
            $this->replaceRecoveryCode($code);
            return true;
        }

        return false;
    }

    public function replaceRecoveryCode($code): void
    {
        $recoveryCodes = collect(json_decode(decrypt($this->two_factor_recovery_codes), true))
            ->map(fn($recoveryCode) => hash_equals($recoveryCode, $code) ? bin2hex(random_bytes(4)) : $recoveryCode)
            ->toArray();

        $this->forceFill([
            'two_factor_recovery_codes' => encrypt(json_encode($recoveryCodes)),
        ])->save();
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->hasRole(['super_admin', 'admin', /*'editor', 'panel_user'*/]);
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function classrooms(): BelongsToMany
    {
        return $this->belongsToMany(Classroom::class, 'classroom_user');
    }

    public function previousPasswords(): HasMany
    {
        return $this->hasMany(UserPreviousPassword::class);
    }

    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn() => trim(
                ucfirst($this->surname) . ' ' .
                ucfirst($this->name) . ' ' .
                ($this->patronymic ? ucfirst($this->patronymic) : '')
            )
        );
    }

    protected function role(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->getRoleNames()
                ->map(fn($role) => RoleEnum::tryFrom($role)?->label() ?? $role)
        );
    }
}
