<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
class LoginController extends Controller {

    // メディア側へのリダイレクト
    public function redirectToProvider(Request $request) {
        $provider = $request->provider;
        return Socialite::driver($provider)->redirect();
    }

    public function redirectToGoogle()
    {
        // Google へのリダイレクト
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        $gUser = Socialite::driver('google')->stateless()->user();
        // email が合致するユーザーを取得
        $user = User::where('email', $gUser->email)->first();
        // 見つからなければ新しくユーザーを作成
        if ($user == null) {
            $user = $this->createUserByGoogle($gUser);
        }
        // ログイン処理
        \Auth::login($user, true);
        return redirect('/home');
    }

    public function createUserByGoogle($gUser)
    {
        $user = User::create([
            'name'     => $gUser->name,
            'email'    => $gUser->email,
            'password' => \Hash::make(uniqid()),
        ]);
        return $user;
    }

    // メディア側から返されるユーザー情報
    public function handleProviderCallback(Request $request) {
        $provider = $request->provider;
        $sns_user = Socialite::driver($provider)->user();
        $sns_email = $sns_user->getEmail();
        $sns_name = $sns_user->getName();

        // 登録済ならログイン。未登録ならアカウント登録してログイン
        if(!is_null($sns_email)) {
            $user = User::firstOrCreate(   // Userモデルに、レコードがあれば取得、なければ保存
                [ 'email' => $sns_email ],
                [ 'email' => $sns_email, 'name' => $sns_name, 'password' => Hash::make(Str::random())
            ]);
            auth()->login($user);
            session()->flash('oauth_login', $provider.'でログインしました。');
            return redirect('/');
        }
        return '情報が取得できませんでした。';
    }
}


