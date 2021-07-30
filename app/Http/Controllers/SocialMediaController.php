<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;

class SocialMediaController extends Controller
{
    protected $acceptableProvider = ['google', 'github', 'linkedin'];

    public function redirectToProvider($provider){
        foreach($this->acceptableProvider as $social){
            if ($provider == $social){
                return Socialite::driver($provider)->redirect();
            }
        }
        return redirect('/login');
    }

    public function handleProviderCallback($provider){
        $user = Socialite::driver($provider)->user();
        $this->_registerOrLoginUser($user);
        return redirect()->route('home');
    }

    protected function _registerOrLoginUser($data) {
        $user = User::where('email', '=', $data->email)->first();
        if (!$user){
            $user = new User();
            $user->name = $data->name;
            $user->email = $data->email;
            $user->save();
        }
        Auth::login($user);
    }
}
