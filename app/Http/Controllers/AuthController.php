<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\UnauthorizedException;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        return response()->json($this->authService->register($request->all()));
    }

    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        return response()->json($this->authService->login($request->all()));
    }

    public function profile(Request $request): JsonResponse
    {
        if(!$this->hasPermission('view users')) throw new UnauthorizedException();

        return response()->json($this->authService->profile($request->user()));
    }

    public function logout(Request $request): JsonResponse
    {
        return response()->json($this->authService->logout($request->user()));
    }
}
