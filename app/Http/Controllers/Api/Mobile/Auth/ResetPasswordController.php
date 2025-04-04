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
            return $this->showMessage('Reset code sent successfully');
        } catch (Exception $e) {
            report($e);
            DB::rollBack();
            return $this->showError($e, 'An Error occur while sending reset password code !!');
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
            return $this->showMessage('Invalid or expired code', 422, false);
        }

        return $this->showMessage('Code verified');
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
            return $this->showMessage('Invalid code.', 422);
        }

        DB::beginTransaction();
        try {
            User::where('email', $request->email)->update([
                'password' => bcrypt($request->password),
            ]);

            // Delete the reset code
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            DB::commit();
            return $this->showMessage('Password reset successfully');
        } catch (Exception $e) {
            report($e);
            return $this->showError($e, 'An error occur while reset your password , Please try again');
        }
    }

}

