<?php

namespace App\Services\Nextcloud;
use App\Models\UserProfile;
use Illuminate\Support\Facades\Http;

class NextcloudClient
{
    public function authenticate(
        string $username,
        string $password
    ): ?array {

        $response = Http::withBasicAuth(
                $username,
                $password
            )
            ->withHeaders([
                'OCS-APIRequest' => 'true',
                'Accept' => 'application/json',
            ])
            ->get(
                config('services.nextcloud.url')
                . '/ocs/v2.php/cloud/user'
            );

        if (!$response->successful()) {
            return null;
        }

        $json = $response->json();

        if (
            ($json['ocs']['meta']['status'] ?? null) !== 'ok'
        ) {
            return null;
        }

        $profile = UserProfile::firstOrCreate(
            ['username' => $username],
            [
                'premium' => false,
                'current_quota' => 10,
                'is_active'=>true
            ],
        );

        $appPassword = $this->getUserAppPassword($username, $password);
        session(['nextcloud_app_password' => $appPassword]);

        return $json['ocs']['data'] ?? null;
    }

    public function getUserInfo(string $username): ?array
    {
        $response = Http::withBasicAuth(
            config('services.nextcloud.admin_username'),
            config('services.nextcloud.admin_password')
        )
        ->withHeaders([
            'OCS-APIRequest' => 'true',
            'Accept' => 'application/json',
        ])
        ->get(
            config('services.nextcloud.url')
            . '/ocs/v2.php/cloud/users/' . $username
        );

        if (!$response->successful()) {
            return null;
        }

        $json = $response->json();

        if (($json['ocs']['meta']['status'] ?? null) !== 'ok') {
            return null;
        }

        return $json['ocs']['data'] ?? null;
    }

    public function getUserAppPassword(string $username, string $password): ?string
    {
        $response = Http::withBasicAuth(
            $username,
            $password
        )
        ->withHeaders([
            'OCS-APIRequest' => 'true',
            'Accept' => 'application/json',
        ])
        ->get(
            config('services.nextcloud.url')
            . '/ocs/v2.php/core/getapppassword'
        );

        if (!$response->successful()) {
            return null;
        }

        $result = $response->json();

        return $result["ocs"]["data"]["apppassword"] ?? null;
    }

    public function getUsers(): ?array
    {
        $response = Http::withBasicAuth(
            config('services.nextcloud.admin_username'),
            config('services.nextcloud.admin_password')
        )
        ->withHeaders([
            'OCS-APIRequest' => 'true',
            'Accept' => 'application/json',
        ])
        ->get(
            config('services.nextcloud.url')
            . '/ocs/v2.php/cloud/users'
        );

        if (!$response->successful()) {
            return null;
        }

        $json = $response->json();

        if (($json['ocs']['meta']['status'] ?? null) !== 'ok') {
            return null;
        }

        return $json['ocs']['data'] ?? null;
    }

    public function addUser(string $username, string $password): ?array
    {
        $response = Http::withBasicAuth(
            config('services.nextcloud.admin_username'),
            config('services.nextcloud.admin_password')
        )
        ->withHeaders([
            'OCS-APIRequest' => 'true',
            'Accept' => 'application/json',
        ])
        ->post(
            config('services.nextcloud.url')
            . '/ocs/v2.php/cloud/users',
            [
                'userid' => $username,
                'password' => $password,
                'quota' => '10GB',
            ]
        );

        $json = $response->json();

        if (!$response->successful()) {
            throw new \Exception($response["ocs"]["meta"]["message"] ?? 'Unknown error');
        }

        if (($json['ocs']['meta']['status'] ?? null) !== 'ok') {
            return null;
        }

        return $json['ocs']['data'] ?? null;
    }

    public function deleteUser(string $username): bool
    {
        $response = Http::withBasicAuth(
            config('services.nextcloud.admin_username'),
            config('services.nextcloud.admin_password')
        )
        ->withHeaders([
            'OCS-APIRequest' => 'true',
            'Accept' => 'application/json',
        ])
        ->delete(
            config('services.nextcloud.url')
            . '/ocs/v2.php/cloud/users/' . $username
        );

        if (!$response->successful()) {
            return false;
        }

        $json = $response->json();

        if (($json['ocs']['meta']['status'] ?? null) !== 'ok') {
            return false;
        }

        return true;
    }

    public function updateUser(string $username, string $property, string $value): bool{
        
        $response = Http::withBasicAuth(
            config('services.nextcloud.admin_username'),
            config('services.nextcloud.admin_password')
        )
        ->withHeaders([
            'OCS-APIRequest' => 'true',
            'Accept' => 'application/json',
        ])
        ->put(
            config('services.nextcloud.url')
            . '/ocs/v2.php/cloud/users/' . $username,
            [
                'key' => $property,
                'value' => $value,
            ]
        );

        if (!$response->successful()) {
            return false;
        }

        $json = $response->json();

        if (($json['ocs']['meta']['status'] ?? null) !== 'ok') {
            return false;
        }

        return true;
    }

    public function addUserQuota(string $username, string $property, int $initialValue, int $value): bool{
        
        $response = Http::withBasicAuth(
            config('services.nextcloud.admin_username'),
            config('services.nextcloud.admin_password')
        )
        ->withHeaders([
            'OCS-APIRequest' => 'true',
            'Accept' => 'application/json',
        ])
        ->put(
            config('services.nextcloud.url')
            . '/ocs/v2.php/cloud/users/' . $username,
            [
                'key' => $property,
                'value' => (string)($initialValue + $value) . "GB",
            ]
        );

        if (!$response->successful()) {
            return false;
        }

        $json = $response->json();

        if (($json['ocs']['meta']['status'] ?? null) !== 'ok') {
            return false;
        }

        return true;
    }
}