<?php

namespace App\Services\NextCloud;

use App\Utilities\XMLExtractor;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;

class NextCloudWebDav
{
    protected string $url;

    public function __construct()
    {
        $this->url = rtrim(config('services.nextcloud.url'), '/');
    }

    public function listFiles(string $path, string $username, string $password): ?array
    {
        $response = Http::withBasicAuth($username, $password)
            ->withHeaders([
                'Depth' => '1',
            ])
            ->send(
                'PROPFIND',
                $this->url . '/remote.php/dav/files/' . $username . '/' . ltrim($path, '/')
            );

        if ($response->status() !== 207) {
            throw new \Exception(
                'Failed to list files: ' . $response->body()
            );
        }

        $result = XMLExtractor::extractXML($response->body());

        return $result;
    }

    public function uploadFile(string $path, string $username, string $password, UploadedFile $uploadedFile): bool
    {
        $response = Http::withBasicAuth($username, $password)
            ->withBody(
                file_get_contents($uploadedFile->getRealPath()),
                $uploadedFile->getMimeType() ?? 'application/octet-stream'
            )
            ->put(
                $this->url . '/remote.php/dav/files/'
                . rawurlencode($username) . '/'
                . implode('/', array_map(
                    'rawurlencode',
                    explode('/', ltrim($path, '/'))
                )),
            );

        if(!$response->successful()) {
            return false;
        }

        return $response->successful();
    }

    public function deleteFile(string $path, string $username, string $password): bool
    {
        $response = Http::withBasicAuth($username, $password)
            ->delete(
                $this->url . '/remote.php/dav/files/' . $username . '/' . ltrim($path, '/'),
            );

        if(!$response->successful()) {
            return false;
        }

        return $response->successful();
    }

    public function createFolder(string $path, string $username, string $password): bool
    {
        $response = Http::withBasicAuth($username, $password)
            ->send(
                'MKCOL',
                $this->url . '/remote.php/dav/files/' . $username . '/' . ltrim($path, '/'),
            );

        if(!$response->successful()) {
            return false;
        }

        return $response->successful();
    }

    public function getFileContents(string $path, string $username, string $password): ?array
    {
        $response = Http::withBasicAuth($username, $password)
            ->get(
                $this->url . '/remote.php/dav/files/' . $username . '/' . ltrim($path, '/'),
            );

        if(!$response->successful()) {
            return null;
        }

        return [
            "name" => basename($path),
            "content" => $response->body(),
            "mime_type" => $response->header('Content-Type'),
        ];
    }
}