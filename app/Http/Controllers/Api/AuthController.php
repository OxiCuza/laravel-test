<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');
        if (! Auth::attempt($credentials)) {

            return response()->api(false, 'Invalid credentials', null, 400);
        }

        $user = Auth::user();
        $data = (new UserResource($user, $user->createToken('auth_token')->plainTextToken));

        return response()->api(true, 'OK!', $data);
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $data = $request->all();

        $user = User::create([
            'id' => Str::uuid(),
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'credit' => $data['credit'],
            'role_id' => $data['role_id'],
        ]);

        $data = (new UserResource($user, $user->createToken('auth_token')->plainTextToken));

        return response()->api(true, 'OK!', $data);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->tokens()->delete();

        return response()->api(true, 'OK!');
    }
}
