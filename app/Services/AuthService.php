<?php

namespace App\Services;

use App\Events\UserRegistered;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function register(array $data): array
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $user->assignRole('User');

        $token = $user->createToken('auth_token')->plainTextToken;

        event(new UserRegistered($user));


        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    public function login(array $data): array
    {
      
        $password =$data['password'];
        $user = User::select('*')->where('email',$data['email'])->first();
        if ($user && (Hash::check($password, $user->password))) {
            if ($user->status != User::STATUS_ACTIVE) {
                throw ValidationException::withMessages([
                    'data' => ['Your account is inactive.'],
                ]);    

            }
 
          $token = $user->createToken('auth_token')->plainTextToken;

            return [
                'user' => $user,
                'token' => $token,
            ];

        }
        throw ValidationException::withMessages([
            'data' => ['invalid creadintials.'],
        ]);  

    }

    public function profile(User $user): User
    {
        return $user;
    }

    public function logout(User $user): array
    {
        $user->tokens()->delete();
        return ['message' => 'Logged out successfully'];
    }
}
