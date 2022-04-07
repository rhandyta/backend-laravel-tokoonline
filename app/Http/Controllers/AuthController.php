<?php

namespace App\Http\Controllers;

use App\Http\Resources\CityResource;
use App\Http\Resources\UserResource;
use App\Models\City;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    public function getProvince()
    {
        $province = DB::table('provinces')->select('id', 'name')->get();
        return response()->json($province);
    }

    public function getCity(Request $request)
    {
        $city = DB::table('cities')->where('province_id', $request->cities)->select('id', 'type', 'name')->get();
        return response()->json($city);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:3',
            'email' => 'required|email|unique:users',
            'address' => 'required',
            'phone' => 'required',
            'password' => 'required|min:6',
            'province_id' => 'required',
            'city_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()], 409);
        }

        $newUser = User::create([
            'name' => ucwords($request->name),
            'email' => $request->email,
            'address' => $request->address,
            'phone' => $request->phone,
            'slug' => \Str::slug(strtolower($request->name)),
            'password' => Hash::make($request->password),
            'province_id' => $request->province_id,
            'city_id' => $request->city_id,
        ]);
        if ($newUser) {
            $token = $newUser->createToken('auth_token')->plainTextToken;
            return response()->json([
                'success' => true, 'user' => $newUser, 'token' => $token, 'type_token' => 'Bearer'
            ], 201);
        }

        return response()->json(['success' => false, 'message' => 'register failed'], 409);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:3',
        ]);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()],  401);
        }
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['success' => false, 'message' => 'you unathorized'], 401);
        }
        User::where('email', $request->email)->firstOrFail();
        $user = UserResource::make($request->user());
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json(['success' => true, 'user' => $user, 'token' => $token, 'type_token' => 'Bearer'], 200);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();
        return response()->json(['success' => true, 'message' => 'you are loggout'], 200);
    }
}
