<?php

namespace App\Repositories\Auth;

use App\Models\User;
use App\Repositories\Auth\AuthRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Prettus\Repository\Eloquent\BaseRepository;

class AuthRepositoryEloquent extends BaseRepository implements AuthRepository
{
    /**
     * @inheritDoc
     */
    public function model()
    {
        return User::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        //
    }

    public function login(array $payload)
    {
        if (!$token = Auth::attempt($payload)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $this->createNewToken($token);
    }

    public function register(array $payload)
    {
        $user = User::create(array_merge(
            $payload,
            ['password' => bcrypt($payload['password'])]
        ));

        $token = Auth::login($user);
        return response()->json([
            'status' => 'success',
            'message' => 'User successfully registered',
            'user' => $user,
            'authorization' => [
                'access_token' => $token,
                'token_type' => 'bearer',
            ],
        ], 201);
    }

    public function logout()
    {
        Auth::logout();
        return response()->json(['message' => 'User successfully signed out']);
    }

    public function refresh()
    {
        return $this->createNewToken(Auth::refresh());
    }

    public function getUserProfile()
    {
        return response()->json(Auth::user());
    }

    public function createNewToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60,
            'user' => Auth::user()
        ]);
    }
}
