<?php

namespace App\Http\Controllers\Api\Dashboard\Auth;

use App\Enums\TokenAbility;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{

    public function create(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);
        $credentials = $request->only('email', 'password');
        if (!Auth::attempt($credentials)) {

            return response()->json(['message' => 'password or Email is incorrect'], 401);
        }

        $admin = Auth::user();

        $accessToken = $admin->createToken(
            'access_token',
            [TokenAbility::ACCESS_API->value],
            Carbon::now()->addMinutes(config('sanctum.expiration'))
        );

        $refreshToken = $admin->createToken(
            'refresh_token',
            [TokenAbility::ISSUE_ACCESS_TOKEN->value],
            Carbon::now()->addDays(7)
        );

        return response()->json([
            'message' => 'logged in successfully.',
            'user'=>$admin,
            'access_token' => $accessToken->plainTextToken,
            'refresh_token'=>$refreshToken->plainTextToken,
        ]);

    }

    public function destroy()
    {

        Auth::user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully.']);
    }
}
