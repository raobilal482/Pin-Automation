<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\PinterestAccount;
use Illuminate\Support\Facades\Auth;

class PinterestAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('pinterest')
            ->scopes(['boards:read', 'boards:write', 'pins:read', 'pins:write'])
            ->redirect();
    }

    public function callback()
    {
        try {
            $socialUser = Socialite::driver('pinterest')->user();
            $user = Auth::user();

            $account = PinterestAccount::updateOrCreate(
                ['pinterest_user_id' => $socialUser->id],
                [
                    'user_id' => $user->id,
                    'nickname' => $socialUser->nickname ?? $socialUser->name,
                    'username' => $socialUser->nickname,
                    'avatar_url' => $socialUser->avatar,
                    'access_token' => $socialUser->token,
                    'refresh_token' => $socialUser->refreshToken,
                    'token_expires_at' => now()->addSeconds($socialUser->expiresIn),
                ]
            );

            return redirect()->route('dashboard')->with('success', 'Connected!');

        } catch (\Exception $e) {
            return redirect()->route('dashboard')->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
