<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Auth\NextcloudUserProvider;
use Illuminate\Http\Request;

class FilesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct(
        private NextcloudUserProvider $nextcloudUserProvider,
    ){}

    public function index()
    {
        //
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $response = $this->nextcloudUserProvider->uploadFile(auth()->user()->id, session('nextcloud_app_password'), $request->file('file'));

        if(!$response) {
            return back()->with('error', 'Failed to upload file.');
        }

        return back()->with('success', 'File uploaded successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $result = $this->nextcloudUserProvider->getContent(auth()->user()->id, session('nextcloud_app_password'), $id);
        if(auth()->user()->id === "admin"){
            return view('app.admin.show_file', ['fileContent' => $result['content'], 'mimeType' => $result['mime_type'], 'fileName' => $result["name"]]);
        }else{
            return view('app.user.show_file', ['fileContent' => $result['content'], 'mimeType' => $result['mime_type'], 'fileName' => $result["name"]]);
        } 

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $response = $this->nextcloudUserProvider->deleteFile(auth()->user()->id, session('nextcloud_app_password'), $id);

        if(!$response) {
            return back()->with('delete_error', 'Failed to delete file.');
        }

        return back()->with('delete_success', 'File deleted successfully.');
    }

    public function download(string $filePath)
    {
        $result = $this->nextcloudUserProvider->getContent(auth()->user()->id, session('nextcloud_app_password'), $filePath);

        if (!$result) {
            return back()->with('error', 'Failed to download file.');
        }

        return response($result['content'])
            ->header('Content-Type', $result['mime_type'])
            ->header('Content-Disposition', 'attachment; filename="' . basename($filePath) . '"');
    }
}
