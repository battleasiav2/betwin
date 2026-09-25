<?php

namespace App\Http\Controllers\User\Auth;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Lib\SocialLogin;

class SocialiteController extends Controller
{

    public function socialLogin($provider)
    {
        if ($provider !== 'google' || @gs('socialite_credentials')->google->status != Status::ENABLE) {
            $notify[] = ['error', 'Google login is not available'];
            return back()->withNotify($notify);
        }

        $socialLogin = new SocialLogin($provider);
        return $socialLogin->redirectDriver();
    }


    public function callback($provider)
    {
        if ($provider !== 'google') {
            $notify[] = ['error', 'Invalid social login provider'];
            return to_route('home')->withNotify($notify);
        }

        $socialLogin = new SocialLogin($provider);
        try {
            return $socialLogin->login();
        } catch (\Exception $e) {
            $notify[] = ['error', $e->getMessage()];
            return to_route('home')->withNotify($notify);
        }
    }
}
