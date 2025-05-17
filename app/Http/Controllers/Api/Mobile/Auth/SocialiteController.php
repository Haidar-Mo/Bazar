<?php

namespace App\Http\Controllers\Api\Mobile\Auth;

use App\Enums\TokenAbility;
use App\Http\Controllers\Controller;
use App\Traits\ResponseTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class SocialiteController extends Controller
{
    use ResponseTrait;
    /*
        public function redirect($provider)
        {
            return response()->json([
                'redirect_url' => Socialite::driver($provider)->stateless()->redirect()->getTargetUrl(),
            ]);
        }

        public function callback($provider)
        {
            try {
                $socialUser = Socialite::driver($provider)->stateless()->user();

                $fullName = $socialUser->getName();
                $firstName = Str::beforeLast($fullName, ' ') ?: $fullName;
                $lastName = Str::afterLast($fullName, ' ') ?: '';

                $user = User::where('email', $socialUser->getEmail())->first();

                if (!$user) {
                    $user = User::create([
                        'first_name' => $firstName,
                        'last_name' => $lastName,
                        'email' => $socialUser->getEmail(),
                        'password' => bcrypt(Str::random(16)),
                        'provider' => $provider,
                        'provider_id' => $socialUser->getId(),
                    ]);
                }

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

                $user->load('roles');
                return response()->json([
                    'access_token' => $accessToken->plainTextToken,
                    'refresh_token' => $refreshToken->plainTextToken,
                    'user' => $user,
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'error' => 'Failed to authenticate with ' . ucfirst($provider),
                ], 401);
            }
        }*/


    //-

    /*public function loginWithSocial(Request $request, $provider)
{
    try {
        // Validate request
        $request->validate([
            'id_token' => 'required|string',
            'device_token' => 'sometimes'
        ]);

        // Verify the ID token with Google
        $googleResponse = Http::get("https://oauth2.googleapis.com/tokeninfo?id_token={$request->id_token}");

        if ($googleResponse->failed()) {
            return $this->showMessage('الرقم التعريفي غير صالح', 401, false);
        }

        $socialUser = $googleResponse->json();

        // Extract user details
        $email = $socialUser['email'];
        $googleId = $socialUser['sub'];
        $nameParts = explode(' ', trim($socialUser['name']), 2);
        $first_name = $nameParts[0] ?? '';
        $last_name = $nameParts[1] ?? '';

        // Find or create user
        $user = User::where('email', $email)->first();
        if (!$user) {
            $user = User::create([
                'email' => $email,
                'first_name' => $first_name,
                'last_name' => $last_name,
                'password' => bcrypt(Str::random(16)),
                'provider' => $provider,
                'provider_id' => $googleId,
            ]);
            $user->assignRole(Role::where('name', 'client')->where('guard_name', 'api')->first());
        }

        $user->update([
            'device_token' => $request->device_token ?? null
        ]);
        $user->tokens()->delete();

        // Generate access and refresh tokens
        $accessToken = $user->createToken('access_token', [TokenAbility::ACCESS_API->value], Carbon::now()->addMinutes(config('sanctum.expiration')));
        $refreshToken = $user->createToken('refresh_token', [TokenAbility::ISSUE_ACCESS_TOKEN->value], Carbon::now()->addDays(7));

        return response()->json([
            'access_token' => $accessToken->plainTextToken,
            'refresh_token' => $refreshToken->plainTextToken,
            'user' => $user,
        ]);
    } catch (Exception $e) {
        return response()->json([
            'error' => 'فشلت عملية المصادقة',
            'message' => $e->getMessage(),
        ], 401);
    }
}*/


    public function loginWithSocial(Request $request, $provider)
    {
        try {
            $request->validate([
                'id_token' => 'required|string', // Google: id_token, Facebook: access_token
                'device_token' => 'sometimes|string',
            ]);

            $socialUser = [];

            if ($provider === 'google') {
                $googleResponse = Http::get("https://oauth2.googleapis.com/tokeninfo?id_token={$request->id_token}");

                if ($googleResponse->failed()) {
                    return $this->showMessage('الرمز التعريفي من Google غير صالح', 401, false);
                }

                $googleData = $googleResponse->json();
                $socialUser = [
                    'email' => $googleData['email'] ?? null,
                    'id' => $googleData['sub'],
                    'name' => $googleData['name'] ?? '',
                ];
            } 
            
            elseif ($provider === 'facebook') {
                // Get user info from Facebook
                $fbResponse = Http::get("https://graph.facebook.com/me", [
                    'fields' => 'id,name,email,birthday,gender',
                    'access_token' => $request->id_token,
                ]);

                if ($fbResponse->failed()) {
                    return $this->showMessage('رمز الدخول من Facebook غير صالح', 401, false);
                }

                $fbData = $fbResponse->json();
                $socialUser = [
                    'email' => $fbData['email'] ?? null,
                    'id' => $fbData['id'],
                    'name' => $fbData['name'] ?? '',
                ];
            } 
            
            else {
                return $this->showMessage('مزود المصادقة غير مدعوم', 422, false);
            }

            // Parse name
            $nameParts = explode(' ', trim($socialUser['name']), 2);
            $first_name = $nameParts[0] ?? '';
            $last_name = $nameParts[1] ?? '';

            // Find or create user
            $user = User::where('provider', $provider)->where('provider_id', $socialUser['id'])->orWhere('email', $socialUser['email'])->first();

            if (!$user) {
                $user = User::create([
                    'email' => $socialUser['email'],
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'password' => bcrypt(Str::random(16)),
                    'provider' => $provider,
                    'provider_id' => $socialUser['id'],
                ]);
                $user->assignRole(Role::where('name', 'client')->where('guard_name', 'api')->first());
            }

            $user->update([
                'device_token' => $request->device_token ?? null,
            ]);

            $user->tokens()->delete();

            $accessToken = $user->createToken('access_token', [TokenAbility::ACCESS_API->value], now()->addMinutes(config('sanctum.expiration')));
            $refreshToken = $user->createToken('refresh_token', [TokenAbility::ISSUE_ACCESS_TOKEN->value], now()->addDays(7));

            return response()->json([
                'access_token' => $accessToken->plainTextToken,
                'refresh_token' => $refreshToken->plainTextToken,
                'user' => $user,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'فشلت عملية المصادقة',
                'error' => $e->getMessage(),
            ], 401);
        }
    }

}