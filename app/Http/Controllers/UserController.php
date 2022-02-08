<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $user = User::with('roles')->latest()->get();
        return UserResource::collection($user);
    }

    public function show(User $user)
    {
        return UserResource::make($user);
    }
}
