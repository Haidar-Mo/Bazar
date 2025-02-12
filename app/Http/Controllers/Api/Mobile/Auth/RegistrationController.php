<?php

namespace App\Http\Controllers\Api\Mobile\Auth;

use App\Enums\TokenAbility;
use App\Http\Controllers\Controller;
use App\Models\PendingUser;
use App\Models\User;
use App\Notifications\VerificationCodeNotification;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
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
            'email' => ['required', 'unique:users,email', 'email']
        ]);
        try {
            DB::beginTransaction();

            $verificationCode = Str::random(6);
            $expirationTime = Carbon::now()->addMinutes(10);

            $user = PendingUser::updateOrCreate(
                ['email' => $data['email']],
                [
                    'email' => $data['email'],
                    'verification_code' => $verificationCode,
                    'verification_code_expires_at' => $expirationTime
                ]
            );

            $user->notify(new VerificationCodeNotification($verificationCode));
            DB::commit();
            return response()->json(['message' => 'Email registration done. Verification email sent.'], 200);
        } catch (Exception $e) {
            DB::rollback();
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
            return response()->json(['message' => 'your Email is already verified'], 422);
        }
        if ($user->verification_code_expires_at <= now()) {
            return response()->json(['message' => 'your verification code is expired'], 422);
        }
        if ($user->verification_code != $data['verification_code']) {
            return response()->json(['message' => 'your verification code is incorrect'], 422);
        }
        try {
            DB::transaction(function () use ($user) {
                $user->update([
                    'verified_at' => now(),
                    'verification_code_expires_at' => null
                ]);
            });
            return response()->json(['message' => 'Email verified successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Something went wrong, please try again later'], 500);
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
            "email" => ['required', 'email', 'exists:pending_users,email'],
            "password" => ['required', 'confirmed', 'string', 'min:6'],
            'birth_date' => ['required', 'date'],
            "gender" => ['required', 'in:male,female'],
            "device_token" => ['nullable'],
        ]);
        $pendingUser = PendingUser::where('email', $request->email)->firstOrFail();

        if ($pendingUser->verified_at == null)
            return response()->json([
                'message' => 'your Email must be verified first'
            ], 401);

        try {
            $user = DB::transaction(function () use ($request, $pendingUser) {
                $user = User::create($request->all());
                $pendingUser->delete();
                $user->assignRole(Role::where('name', 'client')->where('guard_name', 'api')->first());

                return $user;
            });

            $accessToken = $user->createToken(
                'access_token',
                [TokenAbility::ACCESS_API->value],
                Carbon::now()->addMinutes(config('sanctum.expiration'))
            );

            $refreshToken = $user->createToken(
                'refresh_token',
                [TokenAbility::ISSUE_ACCESS_TOKEN->value],
                Carbon::now()->addMinutes(2 * config('sanctum.expiration'))
            );

            $user->load('roles');

            return response()->json([
                'message' => 'Registration is completely done',
                'access_token' => $accessToken->plainTextToken,
                'refresh_token' => $refreshToken->plainTextToken,
                'user' => $user,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 422);
        }

    }
}
