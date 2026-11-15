<?php

namespace App\Services\Auth;

use App\Models\NextcloudUser;
use App\Services\Nextcloud\NextcloudClient;
use App\Services\NextCloud\NextCloudWebDav;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use App\Models\UserProfile;
use Illuminate\Http\UploadedFile;

class NextcloudUserProvider implements UserProvider
{
    public function __construct(
        protected NextcloudClient $nextcloud,
        protected NextCloudWebDav $nextcloudWebDav
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
            storageUsed: $data['quota']['used'] ?? 0,
            storageQuota: $data['quota']['total'] ?? 0,
            quotaLeft: $data['quota']['free'] ?? 0,
        );
    }

    public function getUserProfile(string $username){
        $result = $this->nextcloud->getUserInfo($username);

        return $result;
    }

    public function createNextcloudUserObject(string $username, UserProfile $profile): NextcloudUser
    {
        $userData = $this->getUserProfile($username);

        return new NextcloudUser(
            id: $userData['id'],
            premium: $profile->premium,
            storageUsed: $userData['quota']['used'] ?? 0,
            storageQuota: $userData['quota']['total'] ?? 0,
            quotaLeft: $userData['quota']['free'] ?? 0,
        );
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

            $isPremium = $profile?->premium ?? false;
            $isActive = $profile?->is_active ?? false;

            $userInfos[] = new NextcloudUser(
                id: $userInfo['id'],
                premium: $isPremium,
                storageUsed: $userInfo["quota"]['used'] ?? 0,
                quotaLeft: $userInfo['quota']["free"] ?? 0,
                storageQuota: $userInfo['quota']["total"] ?? 0,
                isActive: $isActive
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
            [
                'username' => $username,
            
            ],
            [
                'premium' => false,
                'current_quota' => 10,
                'is_active'=> false
            ]
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

    public  function updateUserUsername(string $oldUsername, string $newUsername): bool
    {
        $updated = $this->nextcloud->updateUser($oldUsername, 'username', $newUsername);

        if ($updated) {
            UserProfile::where('username', $oldUsername)->update(['username' => $newUsername]);
        }

        return $updated;
    }

    public function rehashPasswordIfRequired(
        Authenticatable $user,
        array $credentials,
        bool $force = false
    ): bool {
        return false;
    }

    public function getUserFiles(string $username, string $password): ?array
    {
        try{
            $files = $this->nextcloudWebDav->listFiles(
                '/',
                $username,
                $password
            );
        }catch(\Exception $e){
            return null;
        }
        
        return $files;
    }

    public function uploadFile(string $username, string $password, UploadedFile $file): ?bool{
        try{
            $this->nextcloudWebDav->uploadFile(
                $file->getClientOriginalName(),
                $username,
                $password,
                $file
            );
        }catch(\Exception $e){
            return null;
        }

        return true;
    }

    public function getContent(string $username, string $password, string $filePath): ?array{
        try{
            $result = $this->nextcloudWebDav->getFileContents(
                $filePath,
                $username,
                $password
            );
        }catch(\Exception $e){
            return null;
        }

        return $result;
    }

    public function deleteFile(string $username, string $password, string $filePath): ?bool{
        try{
            $this->nextcloudWebDav->deleteFile(
                $filePath,
                $username,
                $password
            );
        }catch(\Exception $e){
            return null;
        }

        return true;
    }
}