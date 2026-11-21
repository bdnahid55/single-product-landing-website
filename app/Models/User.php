<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['name', 'email', 'phone', 'password', 'is_admin', 'two_factor_code', 'two_factor_expires_at', 'two_factor_attempts'])]
#[Hidden(['password', 'remember_token', 'two_factor_code'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at'     => 'datetime',
            'two_factor_expires_at' => 'datetime',
            'password'              => 'hashed',
            'is_admin'              => 'boolean',
            'two_factor_attempts'   => 'integer',
        ];
    }

    /**
     * Generate a new 6-digit two-factor authentication code.
     */
    public function generateTwoFactorCode(): string
    {
        $code = sprintf('%06d', random_int(100000, 999999));

        $this->forceFill([
            'two_factor_code'       => $code,
            'two_factor_expires_at' => now()->addMinutes(10),
            'two_factor_attempts'   => 0,
        ])->save();

        return $code;
    }

    /**
     * Clear the two-factor authentication code and attempts.
     */
    public function resetTwoFactorCode(): void
    {
        $this->forceFill([
            'two_factor_code'       => null,
            'two_factor_expires_at' => null,
            'two_factor_attempts'   => 0,
        ])->save();
    }

    /**
     * Mask email for secure visual display (e.g. ad***@modhu.com).
     */
    public function getMaskedEmailAttribute(): string
    {
        $email = $this->email;
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $email;
        }

        [$name, $domain] = explode('@', $email);
        $len = strlen($name);
        if ($len <= 2) {
            $maskedName = substr($name, 0, 1) . '*';
        } else {
            $maskedName = substr($name, 0, 2) . str_repeat('*', max(1, $len - 3)) . substr($name, -1);
        }

        return $maskedName . '@' . $domain;
    }

    /**
     * Mask phone number for secure visual display (e.g. 017*****000).
     */
    public function getMaskedPhoneAttribute(): ?string
    {
        if (empty($this->phone)) {
            return null;
        }

        $phone = $this->phone;
        $len = strlen($phone);
        if ($len <= 4) {
            return $phone;
        }

        return substr($phone, 0, 3) . str_repeat('*', max(1, $len - 6)) . substr($phone, -3);
    }
}
