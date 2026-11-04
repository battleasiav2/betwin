<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use App\Models\PasswordReset;
use App\Models\User;
use Illuminate\Http\Request;
use Throwable;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        $pageTitle = "Account Recovery";
        return view('Template::user.auth.passwords.email', compact('pageTitle'));
    }

    public function sendResetCodeEmail(Request $request)
    {
        $request->validate([
            'value' => 'required|email'
        ], [
            'value.required' => 'Email is required',
            'value.email' => 'Enter a valid email address',
        ]);

        if (!verifyCaptcha()) {
            $notify[] = ['error', 'Invalid captcha provided'];
            return back()->withNotify($notify);
        }

        $email = strtolower(trim($request->value));
        $user = User::where('email', $email)->first();

        if (!$user) {
            $notify[] = ['error', 'No account found with this email'];
            return back()->withNotify($notify);
        }

        PasswordReset::where('email', $user->email)->delete();
        $code = verificationCode(6);
        $password = new PasswordReset();
        $password->email = $user->email;
        $password->token = $code;
        $password->created_at = \Carbon\Carbon::now();
        $password->save();

        try {
            sendPasswordResetCodeMail($user, $code);
        } catch (Throwable $e) {
            \Log::error('password_reset_mail_failed', [
                'email' => $user->email,
                'error' => $e->getMessage(),
            ]);
            $notify[] = ['error', 'Could not send code to your email. Please try again in a minute.'];
            return back()->withNotify($notify);
        }

        session()->put('pass_res_mail', $user->email);
        $notify[] = ['success', 'Password reset code sent to your email'];
        return to_route('user.password.code.verify')->withNotify($notify);
    }

    public function findFieldType()
    {
        $input = request()->input('value');

        $fieldType = filter_var($input, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        request()->merge([$fieldType => $input]);
        return $fieldType;
    }

    public function codeVerify(Request $request)
    {
        $pageTitle = 'Verify Email';
        $email = $request->session()->get('pass_res_mail');
        if (!$email) {
            $notify[] = ['error', 'Oops! session expired'];
            return to_route('user.password.request')->withNotify($notify);
        }
        return view('Template::user.auth.passwords.code_verify', compact('pageTitle', 'email'));
    }

    public function verifyCode(Request $request)
    {
        $request->validate([
            'code' => 'required',
            'email' => 'required'
        ]);
        $code =  str_replace(' ', '', $request->code);

        if (PasswordReset::where('token', $code)->where('email', $request->email)->count() != 1) {
            $notify[] = ['error', 'Verification code doesn\'t match'];
            return to_route('user.password.request')->withNotify($notify);
        }
        $notify[] = ['success', 'You can change your password'];
        session()->flash('fpass_email', $request->email);
        return to_route('user.password.reset', $code)->withNotify($notify);
    }
}
