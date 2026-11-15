<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\NextCloudUser;
use App\Models\UserProfile;
use App\Services\Auth\NextcloudUserProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

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

            Cache::forget("all_users_data");

            if(auth()->user() === null){
                return redirect()->route('login');
            }
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
        if(auth()->user()->id !== "admin"){
            return view("app.user.profile", ["user" => auth()->user()]);
        }
        return view("app.admin.profile");
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
        if(auth()->user()->id !== "admin"){
           $oldUser = UserProfile::where('username', $id)->first();
           try{ 

                $this->nextcloudUserProvider->updateUserUsername($id, $request->input("username"));
                $user = $this->nextcloudUserProvider->createNextcloudUserObject($request->input("username"), $oldUser);

                Cache::forget("all_users_data");
                Auth::login($user);

                return redirect()->back()->with('success', 'Username updated successfully.');
            }catch(\Exception $e){
                return redirect()->back()->with('error', 'Failed to update username: ' . $e->getMessage());
            }
        }else{
            try{ 

                $this->nextcloudUserProvider->updateUserUsername($id, $request->input("username"));
                Cache::forget("all_users_data");

                return redirect()->back()->with('success', 'Username updated successfully.');
            }catch(\Exception $e){
                return redirect()->back()->with('error', 'Failed to update username: ' . $e->getMessage());
            }
        }

        try{
            $this->nextcloudUserProvider->updateUserUsername($id, $request->input("username"));
            return redirect()->back()->with('success', 'Username updated successfully.');
        }catch(\Exception $e){
            return redirect()->back()->with('error', 'Failed to update username: ' . $e->getMessage());
        }
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
