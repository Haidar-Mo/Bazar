<?php

namespace App\Http\Controllers\Api\Mobile\Auth;

use App\Enums\TokenAbility;
use App\Http\Controllers\Controller;
use App\Models\PendingUser;
use App\Models\User;
use App\Models\FavoriteList;
use App\Notifications\VerificationCodeNotification;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class RegistrationController extends Controller
{

    /**
     * Register an email into the application
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'unique:users,email', 'email'],
            'password' => ['required', 'min:6']
        ]);

        $verificationCode = random_int(100000, 999999);
        $expirationTime = Carbon::now()->addMinutes(10);
        try {
            DB::beginTransaction();

            $user = PendingUser::updateOrCreate(
                ['email' => $data['email']],
                [
                    'email' => $data['email'],
                    'password' => $data['password'],
                    'verification_code' => $verificationCode,
                    'verification_code_expires_at' => $expirationTime
                ]
            );

            $user->notify(new VerificationCodeNotification($verificationCode));

            DB::commit();
            return response()->json(['message' => "Email registration done \n Verification email sent "], 200);
        } catch (Exception $e) {
            DB::rollback();
            report($e);
            return response()->json(['message' => "Error: " . $e->getMessage()], 500);
        }
    }

    /**
     * Resend verification code for an deactivated existing email
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function resendVerificationCode(Request $request)
    {
        $request->validate([
            'email' => ['required', 'exists:users,email'],
        ]);
        try {
            DB::beginTransaction();
            $user = PendingUser::where('email', $request->email)->firstOrFail();
            if ($user->verified_at != null)
                return response()->json(['message' => 'Email is already verified'], 405);
            $verificationCode = str::random(6);
            $expirationTime = Carbon::now()->addMinutes(10);
            $user->update([
                'verification_code' => $verificationCode,
                'verification_code_expires_at' => $expirationTime
            ]);
            $user->notify(new VerificationCodeNotification($verificationCode));
            DB::commit();
            return response()->json(['message' => 'Verification code has been re-sended'], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'something goes wrong...'], 500);
        }
    }


    /**
     * Verify the previously registered email.
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function verifyEmail(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email', 'exists:pending_users,email'],
            'verification_code' => ['required', 'string']
        ]);
        $user = PendingUser::where('email', $data['email'])->firstOrFail();

        if ($user->email_verified_at != null) {
            return response()->json(['message' => 'Your email is already verified'], 422);
        }
        if ($user->verification_code_expires_at <= now()) {
            return response()->json(['message' => 'Your verification code is expired'], 422);
        }
        if ($user->verification_code != $data['verification_code']) {
            return response()->json(['message' => 'Your verification code is incorrect'], 422);
        }

        try {
            $new_user = DB::transaction(function () use ($user) {
                $user->update([
                    'email_verified_at' => now(),
                    'verification_code_expires_at' => null
                ]);
                $newUser = User::create($user->only('email', 'password', 'email_verified_at'));

                $favoriteListExists = FavoriteList::where('user_id', $newUser->id)
                    ->where('name', 'all')
                    ->exists();

                if (!$favoriteListExists) {
                    FavoriteList::create([
                        'user_id' => $newUser->id,
                        'name' => 'all'
                    ]);
                }

                return $newUser;
            });

            $accessToken = $new_user->createToken(
                'access_token',
                [TokenAbility::ACCESS_API->value],
                Carbon::now()->addMinutes(config('sanctum.expiration'))
            );

            $refreshToken = $new_user->createToken(
                'refresh_token',
                [TokenAbility::ISSUE_ACCESS_TOKEN->value],
                Carbon::now()->addDays(7)
            );

            return response()->json([
                'message' => 'Email verified successfully',
                'access_token' => $accessToken->plainTextToken,
                'refresh_token' => $refreshToken->plainTextToken,
                'user' => $new_user,
            ], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Something went wrong, please try again later', 'error:' => $e->getMessage()], 500);
        }
    }


    /**
     * Register the rest of the information to the previously registered email.
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function informationRegistration(Request $request)
    {
        $request->validate([
            "first_name" => ['required', 'string'],
            "last_name" => ['required', 'string'],
            'birth_date' => ['required', 'date'],
            'description' => ['sometimes', 'string'],
            "gender" => ['required', 'in:male,female'],
            "address" => ['required'],
            "device_token" => ['nullable'],
        ]);

        try {
            $user = DB::transaction(function () use ($request) {
                $user = Auth::user();
                if ($user->is_full_registered)
                    throw new Exception("your account is already full registered ", 422);
                $user->update($request->all());

                $user->assignRole(Role::where('name', 'client')->where('guard_name', 'api')->first());

                return $user;
            });

            return response()->json([
                'message' => 'Registration is completely done',
                'user' => $user,
            ], 200);
        } catch (Exception $e) {
            report($e);
            return response()->json([
                'message' => 'some thing goes wrong...!',
                'error' => $e->getMessage(),
            ], 422);
        }

    }
}
