<?php

namespace App\Http\Controllers\API;

use App\Constants\Status_Responses;
use App\Http\Controllers\Controller;
use App\Http\Resources\Events\EventResource;
use App\Http\Resources\User\UserResource;
use App\Models\Event;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Facades\JWTAuth;


class AuthController extends Controller
{
    /**
     * Get a JWT via given credentials.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws ValidationException
     */

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {
            return bad_request($validator->errors(),$validator->errors()->first());
        }

        $credentials = $validator->validated();

        $existingUser = User::where('email', $credentials['email'])->first();

        if ($existingUser && $existingUser->provider_id !== null && $existingUser->provider !== null) {
            return unauthorized_response(__("This account was registered using a social media provider $existingUser->provider"));
        }

        if (! $token =  Auth::guard('api')->attempt($validator->validated()))
        {
            return unauthorized_response(__('auth.failed'));
        }

        $isRemembered = $request->input('remember_me') === 'true';
        $ttl = $isRemembered ? config('jwt.ttl_remember') : config('jwt.ttl');
        config(['jwt.ttl' => $ttl]);

        return $this->createNewToken($token,Auth::user(), $ttl);
    }

    /**
     * Register a User.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function register(Request $request)
    {
        $validator = $this->validateRegistration($request);

        if ($validator->fails()) {
            return bad_request($validator->errors(), $validator->errors()->first());
        }

        $userData = $validator->validated();
        $userData['password'] = Hash::make($request->password);

        $user = User::create($userData);

        $token = JWTAuth::fromUser($user);
        $ttl = config('jwt.ttl');
        return $this->createNewToken($token,$user,$ttl);

    }

    protected function validateRegistration(Request $request)
    {
        return Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|confirmed|min:6',
        ]);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return JsonResponse
     */
    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'User successfully signed out']);
    }

    /**
     * Refresh a token.
     *
     * @return JsonResponse
     */
    public function refresh()
    {
        return $this->createNewToken(auth()->refresh());
    }
    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return JsonResponse
     */
    protected function createNewToken($token,$user = null,$ttl = null)
    {
        $expires_at =    now()->addSeconds($ttl);
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_at' => $expires_at ,
            'user' => new UserResource($user ?? Auth::guard('api')->user())
        ]);
    }

}
