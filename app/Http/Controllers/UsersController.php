<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Auth\NextcloudUserProvider;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function __construct(
        private NextcloudUserProvider $nextcloudUserProvider
    ){}

    public function index()
    {
        //
        return view("app.admin.profile");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view("app.admin.add_user");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        try{
            $this->nextcloudUserProvider->createUser($request->input("username"),$request->input("password"));
            return redirect()->back()->with('success', 'User created successfully.');
        }catch(\Exception $e){
            return redirect()->back()->with('error', 'Failed to create user: ' . $e->getMessage());
        } 
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $user = $this->nextcloudUserProvider->getUserProfile($id);

        return view("app.admin.edit_user", ["user" => $user]);
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

        $this->nextcloudUserProvider->deleteUser($id);
        
        return redirect()->back()->with('success', 'User deleted successfully.');
    }
}
