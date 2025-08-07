<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\UnauthorizedException;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    public function index(): JsonResponse
    {
        if(!$this->hasPermission('view users')) throw new UnauthorizedException();

        return response()->json($this->userService->list());
    }
}
