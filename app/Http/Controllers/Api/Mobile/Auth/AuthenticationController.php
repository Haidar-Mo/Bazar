<?php

namespace App\Http\Controllers\Api\Mobile\Auth;

use App\Enums\TokenAbility;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AuthenticationController extends Controller
{


    /**
     *  Handle an authentication attempt for a user.
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', Rule::exists('users', 'email')->whereNotNull('email_verified_at')],
            'password' => ['required', 'string'],
            'device_token' => ['sometimes']
        ], [
            'email.exists' => 'البريد الإلكتروني غير مسجل بعد'
        ]);
        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {

            return response()->json(['message' => ' كلمة المرور غير صحيحة'], 401);
        }
        $user = User::find(Auth::user()->id);
        if ($request->has('device_token')) {
            $user->update(['device_token' => $request->device_token]);
        }
        $user->tokens()->delete();

        $accessToken = $user->createToken(
            'access_token',
            [TokenAbility::ACCESS_API->value],
            Carbon::now()->addMinutes(config('sanctum.expiration'))
        );

        $refreshToken = $user->createToken(
            'refresh_token',
            [TokenAbility::ISSUE_ACCESS_TOKEN->value],
            Carbon::now()->addDays(7)
        );

        return response()->json([
            'access_token' => $accessToken->plainTextToken,
            'refresh_token' => $refreshToken->plainTextToken,
            'user' => $user,
        ], 200);
    }


    /**
     * Delete all user's access token and log the user out of the application.
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function delete()
    {
        Auth::user()->tokens()->delete();
        return response()->json(null, 204);
    }


    /**
     * Refresh an out of date token.
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function refreshToken(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete();
        $accessToken = $user->createToken(
            'access_token',
            [TokenAbility::ACCESS_API->value, 'role:' . $user->roles->first()->name],
            Carbon::now()->addMinutes(config('sanctum.expiration'))
        );

        $refreshToken = $user->createToken(
            'refresh_token',
            [TokenAbility::ISSUE_ACCESS_TOKEN->value],
            Carbon::now()->addMinutes(2 * config('sanctum.expiration'))
        );
        return response()->json([
            'message' => 'Token created successfully!',
            'access_token' => $accessToken->plainTextToken,
            'refresh_token' => $refreshToken->plainTextToken,
            'user' => $user,
        ]);
    }

}
