<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;
use Laravel\Socialite\Facades\Socialite;

class UserController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        $user = Socialite::driver('google')->user();
        $existingUser = User::where('email', $user->getEmail())->first();

        if ($existingUser) {
            auth()->login($existingUser, true);
        } else {
            $newUser = new User();
            $newUser->google_id = $user->getId();
            $newUser->name = $user->getName();
            $newUser->email = $user->getEmail();
            $newUser->password = bcrypt(Str::random(16));
            $newUser->email_verified_at = Carbon::now();
            $newUser->save();

            auth()->login($newUser, true);
        }
        return redirect('/dashboard');
    }

    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleFacebookCallback()
    {
        $user = Socialite::driver('facebook')->user();
        $existingUser = User::where('email', $user->getEmail())->first();

        if ($existingUser) {
            auth()->login($existingUser, true);
        } else {
            $newUser = new User();
            $newUser->name = $user->getName();
            $newUser->email = $user->getEmail();
            $newUser->password = bcrypt(Str::random(16));
            $newUser->save();

            auth()->login($newUser, true);
        }

        return redirect('/dashboard');
    }

    public function dashboard()
    {
        return view('frontend.dashboard');
    }

    public function profileUpdate(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'profile_photo' => 'nullable|image|mimes:png,jpg',
            'phone_number' => 'nullable|digits:11',
        ]);

        User::find(Auth::user()->id)->update([
            'name' => $request->name,
            'gender' => $request->gender,
            'phone_number' => $request->phone_number,
            'date_of_birth' => $request->date_of_birth,
            'address' => $request->address,
        ]);

        if($request->hasFile('profile_photo')){
            if(Auth::user()->profile_photo != "default_profile_photo.png"){
                unlink(base_path("public/uploads/profile_photo/").Auth::user()->profile_photo);
            }
            $profile_photo_name =  "Customer-Profile-Photo-".Auth::user()->id.".". $request->file('profile_photo')->getClientOriginalExtension();
            $upload_link = base_path("public/uploads/profile_photo/").$profile_photo_name;
            Image::make($request->file('profile_photo'))->resize(300,300)->save($upload_link);
            User::find(Auth::user()->id)->update([
                'profile_photo' => $profile_photo_name
            ]);
        }
        return back()->with('success', 'Profile Change Success.');
    }

    public function passwordUpdate(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|confirmed|min:8',
            'password_confirmation' => 'required',
        ]);
        if($request->current_password == $request->password){
            return back()->withErrors(['password_error' => 'New password can not same as old password']);
        }
        if(Hash::check($request->current_password, Auth::guard('admin')->user()->password)){
            User::find(Auth::user()->id)->update([
                'password' => Hash::make($request->password)
            ]);
            return back()->with('success', 'Password Change Success.');
        }else{
            return back()->withErrors(['password_error' => 'Your Old Password is Wrong!']);
        }
    }
}
