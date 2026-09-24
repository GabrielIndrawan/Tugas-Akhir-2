<?php

namespace App\Services\Auth;

use App\Models\NextcloudUser;
use App\Services\Nextcloud\NextcloudClient;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use App\Models\UserProfile;

class NextcloudUserProvider implements UserProvider
{
    public function __construct(
        protected NextcloudClient $nextcloud
    ) {
    }

    public function retrieveById($identifier)
    {

        if (!$identifier) {
            return null;
        }

        return new NextcloudUser(
            id: $identifier,
            premium: false,
        );
    }

    public function retrieveByToken($identifier, $token)
    {
        return null;
    }

    public function updateRememberToken(
        Authenticatable $user,
        $token
    ) {
        // Not implemented.
    }

    public function retrieveByCredentials(array $credentials)
    {
        //only for loging in
        $data = $this->nextcloud->authenticate(
            $credentials['username'],
            $credentials['password']
        );

        if ($data === null) {
            return null;
        }

        return new NextcloudUser(
            id: $data['id'],
            premium: false,
        );
    }

    public function getUserProfile(string $username){
        $result = $this->nextcloud->getUserInfo($username);

        return $result;
    }

    public function getAllUserwithProfileData(){
        $results = $this->nextcloud->getUsers();
        $users = $results["users"];
        $userInfos = [];

        foreach ($users as $user) {
            $userInfo = $this->nextcloud->getUserInfo($user);

            UserProfile::firstOrCreate(
                ['username' => $userInfo['id']],
                [ 'premium' => false]
            );

            $profile = UserProfile::where('username', $user)->first();

            $isPremium= $profile?->premium ?? false;

            $userInfos[] = new NextcloudUser(
                id: $userInfo['id'],
                premium: $isPremium,
                storageUsed: $userInfo["quota"]['used'] ?? 0,
                quotaLeft: $userInfo['quota']["free"] ?? 0,
                storageQuota: $userInfo['quota']["total"] ?? 0,
            );
        }
        
        return $userInfos;
    }

    public function validateCredentials(
        Authenticatable $user,
        array $credentials
    ): bool {

        if (
            !isset($credentials['username']) ||
            !isset($credentials['password'])
        ) {
            return false;
        }

        $nextcloudUser = $this->nextcloud->authenticate(
            $credentials['username'],
            $credentials['password']
        );

        if ($nextcloudUser === null) {
            return false;
        }

        return true;
    }

    public function createUser(string $username, string $password): ?NextcloudUser
    {
        $userData = $this->nextcloud->addUser($username, $password);

        if ($userData === null) {
            return null;
        }

        UserProfile::firstOrCreate(
            ['username' => $username],
            ['premium' => false]
        );

        $newUser = new NextcloudUser(
            id: $userData['id'],
            premium: false,
        );

        return $newUser;
    }

    public function deleteUser(string $username): bool
    {
        $deleted = $this->nextcloud->deleteUser($username);

        if ($deleted) {
            UserProfile::where('username', $username)->delete();
        }

        return $deleted;
    }

    public function rehashPasswordIfRequired(
        Authenticatable $user,
        array $credentials,
        bool $force = false
    ): bool {
        return false;
    }
}