<?php

namespace App\Http\Controllers\Api\Mobile\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\ResetPasswordNotification;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class ResetPasswordController extends Controller
{

    use ResponseTrait;

    public function sendResetCode(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);
        $resetCode = mt_rand(100000, 999999);
        try {
            DB::beginTransaction();
            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $request->email],
                ['token' => $resetCode, 'expires_at' => now()->addSeconds(600), 'created_at' => now()]
            );
            $user = User::where('email', $request->email)->first();
            Notification::send($user, new ResetPasswordNotification($resetCode));
            DB::commit();
            return $this->showMessage('تم إرسال رمز إعادة تعيين كلمة المرور إلى بريدك الإلكتروني', 200);
        } catch (Exception $e) {
            report($e);
            DB::rollBack();
            return $this->showError($e, 'فشل إرسال رمز إعادة التعيين. يرجى المحاولة لاحقًا', 500);
        }
    }
    public function verifyResetCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'code' => 'required|digits:6'
        ]);

        $user = DB::table('password_reset_tokens')->select()
            ->where('email', $request->email)
            ->where('token', $request->code)
            ->where('expires_at', '>', now())
            ->first();

        if (!$user) {
            return $this->showMessage('رمز التحقق غير صالح أو منتهي الصلاحية', 422, false);
        }

        return $this->showMessage('تم التحقق', 200);
    }
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'code' => 'required|numeric|digits:6',
            'password' => 'required|min:6|confirmed',
        ]);

        $reset = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->code)
            ->first();

        if (!$reset) {
            return $this->showMessage('رمز التحقق غير صالح', 422, false);
        }

        DB::beginTransaction();
        try {
            User::where('email', $request->email)->update([
                'password' => bcrypt($request->password),
            ]);

            // Delete the reset code
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            DB::commit();
            return $this->showMessage('تم إعادة تعيين كلمة المرور بنجاح', 200);
        } catch (Exception $e) {
            report($e);
            return $this->showError($e, 'فشل إعادة تعيين كلمة المرور. يرجى المحاولة مرة أخرى', 500);
        }
    }

}

