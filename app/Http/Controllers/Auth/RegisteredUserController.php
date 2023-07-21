<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

use App\Http\Requests\Auth\RegisterRequest;
use App\Services\AuthService;
use App\Http\Resources\RegisterResource;

class RegisteredUserController extends Controller
{
    public function __construct(private AuthService $authService)
    {
        $this->authService = $authService;
    }
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(RegisterRequest $request)
    {
        try {
            $data = $this->authService->register($request);

            $result = new RegisterResource($data, 'User registered successfully!');

            return $this->respond($result);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
    }
}
