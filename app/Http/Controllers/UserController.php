<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return User::all()->toJson();
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
    public function store(UserRequest $request)
    {
        //
        $validated = $request->validated();

        $user=User::create([
          'firstname' => $validated['firstname'],
          'lastname' => $validated['lastname'],
          'email' => $validated['email'],
          'phone' => $validated['phone'],
          'password' => $validated['password'],
          'email_verified_at' => now()
        ]);

        return response()->json([
            'message'=>'User created successfully',
            "User"=>$user,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
        return response()->json([
            'User'=>$user,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
        return response()->json([
            'User'=>$user,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserUpdateRequest $request, User $user)
    {
        //
        $validated = $request->validated();

        $user->fill($validated)->save();

        return response()->json([
            'message'=>'User updated successfully',
            'User'=>$user,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
        $user->delete();

        return response()->json([
            'message'=>'User deleted successfully',
        ]);
    }
}
