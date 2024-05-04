<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\SocialAccount;
use Laravel\Socialite\Facades\Socialite;
use Exception;


class SocialLoginController extends Controller
{
    // Googleの認証ページヘユーザーを転送するためのルート
    public function redirectToProvider(String $provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    // Googleの認証後に戻るルート
    public function providerCallback(String $provider)
    {
        // エラーならトップページに遷移
        try {
            $social_user = Socialite::driver($provider)->user();
        } catch (Exception $e) {
            return redirect('/');
        }

        // nameかnickNameをuserNameにする
        if ($social_user->getName()) {
            $user_name = $social_user->getName();
        } else {
            $user_name = $social_user->getNickName();
        }

        // userテーブルに保存
        $auth_user = User::firstOrCreate([
            'email' => $social_user->getEmail(),
            'name' => $user_name
        ]);

        // social accountテーブルに保存
        $auth_user->socialAccounts()->firstOrCreate([
            'provider_id'=>$social_user->getId(),
            'provider_name'=>$provider
        ]);

        // ログイン
        auth()->login($auth_user);

        // homeページに転送
        return redirect()->intended('mypage')->with('message', 'ログインしました');
    }
}
