<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        $avatarPath = $request->user()->avatar ?: asset('/images/no_avatar.png');

        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
            'avatarPath' => $avatarPath,
        ]);
    }


    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        // 現在認証されているユーザーのインスタンスの属性をバリデーションを通過したデータで更新
        $request->user()->fill($request->validated());

        // email属性が元の値から変更されているかどうかをチェック
        if ($request->user()->isDirty('email')) {
            // email_verified_at属性をnullに設定して、ユーザーが新しいメールアドレスを再認証する必要性を強制
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit');
    }

    public function updateAvatar(ProfileUpdateRequest $request) : RedirectResponse
    {
        $request->validate([
                'avatar' => ['file', 'mimes:gif,png,jpg,webp', 'max:1024'],
        ]);
    
        if ($request->hasFile('avatar')) {
            $disk = Storage::disk('s3');

            // 現在のアバターを削除
            if($currentAvatar = $request->user()->avatar){
               $oldFileName = basename($currentAvatar);
                $disk->delete('avatar/' . $oldFileName);
            }
            // 新しいアバターをアップロード
            $fileName = $disk->putFile('avatar', $request->file('avatar'));
            $url = $disk->url($fileName);
            $request->user()->avatar = $url;
            $request->user()->save();
    
            return redirect()->route('profile.edit')->with('status', '画像が更新されました。');
        }
    
        return redirect()->route('profile.edit')->with('status', '画像のアップロードに失敗しました。');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current-password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
