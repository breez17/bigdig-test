<?php

namespace App\Http\Controllers;

use App\Repository\UserRepositoryInterface;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Carbon\Carbon;

class AuthController extends Controller
{
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * @var Hasher
     */
    private $hasher;

    /**
     * AuthController constructor.
     * @param UserRepositoryInterface $userRepository
     * @param Hasher $hasher
     */
    public function __construct(UserRepositoryInterface $userRepository, Hasher $hasher)
    {
        $this->userRepository = $userRepository;
        $this->hasher = $hasher;
    }

    /**
     * Create user
     *
     * @param Request $request
     * @return JsonResponse [string] message
     * @throws \Illuminate\Validation\ValidationException
     */
    public function signup(Request $request): JsonResponse
    {
        $this->validate($request, [
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed'
        ]);

        $name = $request->request->get('name');
        $email = $request->request->get('email');
        $password = $this->hasher->make($request->request->get('password'));

        $user = $this->userRepository->create($name, $email, $password);

        $user->save();

        return new JsonResponse([
            'message' => 'Successfully created user!',
            'user' => $user,
        ], Response::HTTP_CREATED);
    }

    /**
     * Login user and create token
     *
     * @param Request $request
     * @return JsonResponse [string] access_token
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request): JsonResponse
    {
        $this->validate($request, [
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);

        $credentials = request(['email', 'password']);

        $user = $this->userRepository->findByEmail($credentials['email']);

        if ($user === null || $this->hasher->check($credentials['password'], $user->password) === false) {
            return new JsonResponse([
                'message' => 'Unauthorized'
            ], Response::HTTP_UNAUTHORIZED);
        }

        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;

        if ($request->request->get('remember_me')) {
            $token->expires_at = Carbon::now()->addWeeks(1);
        }

        $token->save();

        return new JsonResponse([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString()
        ]);
    }

    /**
     * Logout user (Revoke the token)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse [string] message
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->token()->revoke();

        return new JsonResponse([
            'message' => 'Successfully logged out'
        ]);
    }

    /**
     * Get the authenticated User
     *
     * @param Request $request
     * @return JsonResponse [json] user object
     */
    public function user(Request $request): JsonResponse
    {
        return new JsonResponse($request->user());
    }
}
