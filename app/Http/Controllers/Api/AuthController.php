<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\ErrorResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login(LoginRequest $request): JsonResource
    {
        $credentials = $request->only('email', 'password');
        if (! Auth::attempt($credentials)) {
            $res = [
                'status' => false,
                'message' => 'Invalid credentials',
            ];
            return new ErrorResource($res, 401);
        }

        $user = Auth::user();

        return (new UserResource(resource: $user, message: 'OK!'))->additional([
            'token' => $user->createToken('auth_token')->plainTextToken,
        ]);
    }

    public function register(RegisterRequest $request): JsonResource
    {
        $data = $request->all();

        $user = User::create([
            'id' => Str::uuid(),
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'credit' => 0,
            'role_id' => $data['role_id'],
        ]);

        return (new UserResource(resource: $user, message: 'OK!'))->additional([
            'token' => $user->createToken('auth_token')->plainTextToken,
        ]);
    }

    public function logout(Request $request): JsonResource
    {
        $request->user()->tokens()->delete();

        return new UserResource($request->user(), message: 'OK!');
    }
}
