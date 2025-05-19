<?php

namespace App\Http\Controllers\Api\Mobile\Auth;

use App\Enums\TokenAbility;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\PendingUser;
use App\Models\User;
use App\Models\FavoriteList;
use App\Notifications\VerificationCodeNotification;
use App\Traits\ResponseTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Traits\FirebaseNotificationTrait;

class RegistrationController extends Controller
{
    use FirebaseNotificationTrait, ResponseTrait;

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
            return response()->json(['message' => "تم التسجيل بنجاح. تم إرسال رمز التحقق إلى البريد الإلكتروني."], 200);
        } catch (Exception $e) {
            DB::rollback();
            report($e);
            return $this->showError($e, 'حدث خطأ أثناء إنشاء الحساب', 500);
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
            'email' => ['required', 'exists:pending_users,email'],
        ]);
        try {
            DB::beginTransaction();
            $user = PendingUser::where('email', $request->email)->firstOrFail();
            if ($user->email_verified_at != null)
                return response()->json(['message' => 'هذا البريد الإلكتروني مفعل بالفعل'], 400);
            $verificationCode = mt_rand(100000, 999999);
            $expirationTime = Carbon::now()->addMinutes(10);
            $user->update([
                'verification_code' => $verificationCode,
                'verification_code_expires_at' => $expirationTime
            ]);
            $user->notify(new VerificationCodeNotification($verificationCode));
            DB::commit();
            return response()->json(['message' => 'تم إعادة إرسال رمز التحقق بنجاح'], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->showError($e, 'حدث خطأ أثناء إعادة إرسال رمز التحقق', 500);
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
            return response()->json(['message' => 'هذا البريد الإلكتروني مفعل بالفعل'], 400);
        }
        if ($user->verification_code_expires_at <= now()) {
            return response()->json(['message' => 'انتهت صلاحية رمز التحقق'], 410);
        }
        if ($user->verification_code != $data['verification_code']) {
            return response()->json(['message' => 'رمز التحقق غير صحيح'], 403);
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
            $new_user->assignRole(Role::where('name', 'client')->where('guard_name', 'api')->first());

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
                'message' => 'تم التحقق من البريد الإلكتروني بنجاح',
                'access_token' => $accessToken->plainTextToken,
                'refresh_token' => $refreshToken->plainTextToken,
                'user' => $new_user,
            ], 200);
        } catch (Exception $e) {
            return $this->showError($e, 'حدث خطأ أثناء التحقق من الحساب', 500);
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
            'description' => ['nullable', 'string'],
            "gender" => ['required', 'in:male,female'],
            "address" => ['required'],
            "device_token" => ['nullable'],
        ]);


        try {
            $user = DB::transaction(function () use ($request) {
                $user = Auth::user();
                if ($user->is_full_registered) {
                    throw new Exception("حسابك مفعل ومكتمل بالفعل", 400);
                }
                $user->update($request->all());
                $categories = Category::whereNull('parent_id')->get();
                foreach ($categories as $category) {
                    $user->notificationSettings()->create([
                        'category_id' => $category->id,
                        'is_active' => true,
                    ]);
                    if ($user->device_token) {
                    $this->subscribeToTopic($user->device_token,$category->id);
                }

                }

                return $user;
            });

            $user->append('is_full_registered');
            return response()->json([
                'message' => 'تم إكمال التسجيل بنجاح',
                'user' => $user,
            ], 200);
        } catch (Exception $e) {
            report($e);
            if (is_int($e->getCode()))
                return $this->showError($e, 'حدث خطأ أثناء إكمال التسجيل', $e->getCode());
            return $this->showError($e, 'حدث خطأ أثناء إكمال التسجيل', 500);
        }
    }

}
