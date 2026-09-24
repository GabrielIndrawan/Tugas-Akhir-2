<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use App\Models\Role;


class NextCloudUser implements Authenticatable
{

    public function __construct(
        public string $id,
        public Role $role = Role::User,
        public bool $premium = false,
        public int $storageUsed = 0,
        public int $storageQuota = 0,
        public int $quotaLeft = 0,
        public string $displayName = "",
        public? string $email = null,
    ){}

    public function getAuthIdentifierName()
    {
        return 'id';
    }

    public function getAuthIdentifier()
    {
        return $this->id;
    }

    public function getProperties(): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
        ];
    }

    public function getAuthPassword()
    {
        return '';
    }

    public function getAuthPasswordName()
    {
        return 'password';
    }

    public function getRememberToken()
    {
        return null;
    }

    public function setRememberToken($value)
    {
        // We don't store remember tokens in Nextcloud.
    }

    public function getRememberTokenName()
    {
        return 'remember_token';
    }
}
