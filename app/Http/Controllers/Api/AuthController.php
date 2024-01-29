<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ErrorResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login(Request $request): JsonResource
    {
        // TODO : CREATE FILE REQUEST
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

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

    public function register(Request $request): JsonResource
    {
        // TODO : CREATE FILE REQUEST
        $credentials = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', 'min:8'],
            'role_id' => ['required'],
        ]);

        $user = User::create([
            'id' => Str::uuid(),
            'name' => $credentials['name'],
            'email' => $credentials['email'],
            'password' => bcrypt($credentials['password']),
            'credit' => 0,
            'role_id' => $credentials['role_id'],
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
