<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\MailResetPasswordRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:115',
            'email' => 'required|string|max:191|email|unique:users',
            'password' => [
                'required',
                'max:150',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised()
            ]
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => 'user',
            'password' => bcrypt($request->password),
        ]);
        if ($user) {
            return response()->json(['message' => 'Registration successfuly ! please try to login'], 200);
        } else {
            return response()->json(['message' => 'Some error occurred please try to again'], 500);
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|max:191',
            'password' => 'required',
        ]);
        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return response()->json(['message' => 'Invalid User email and password'], 401);
        }
        $user = $request->user();
        $user->tokens()->delete();
        

        if ($user->role == 'admin') {
            $token = $request->user()->createToken('Personal Access Token', ['admin']);
        } else {
            $token = $request->user()->createToken('Personal Access Token', ['user']);
        }
        return response()->json([
            'user' => $user,
            'access_token' => $token->plainTextToken,
            'access_type' => 'Bearer',
            'abilities' => $token->accessToken->abilities,
            'message' => 'Login hasbenn successfuly'
        ], 200);
    }

    public function PasswordRequesst(Request $request)
    {

        $request->validate([
            'email' => 'required|string|max:191',
        ]);
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['message' => 'we have sent a varifaction code to your provided email'], 200);
        }
        $code = rand(111111, 999999);
        $user->verified_code = $code;

        if ($user->save()) {
            $emailData = array(
                'heading' => 'Reset password request',
                'name' => $user->name,
                'email' => $user->email,
                'code' => $user->verified_code
            );
            Mail::to($emailData['email'])->queue(new MailResetPasswordRequest($emailData));
            return response()->json(['message' => 'we have sent a varifaction code to your provided email'], 200);
        } else {
            return response()->json(['message' => 'Some error occurred please try to again'], 500);
        }
    }


    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'verified_code' => 'required',
            'new_password' => [
                'required',
                'max:150',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised()
            ]
        ]);

        $user = User::where('email', $request->email)->where('verified_code', $request->verified_code)->first();
        if (!$user) {
            return response()->json(['message' => 'User not found/Invalid code'], 404);
        }
        $user->password = $request->new_password;
        $user->verified_code = null;
        $user->save;
        return response()->json(['message' => 'password change successfuly'], 200);
    }

    public function profile(Request $request)
    {
        $user = $request->user();
        return response()->json($user, 200);
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => [
                'required',
                'max:150',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised()
            ]
        ]);

        $user = $request->user();
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['message' => 'Current password is wrong'], 401);
        }
        $user->password = bcrypt($request->new_password);
        $user->save();
        return response()->json(['password change successfuly'], 200);
    }


    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $user = $request->user();

        $oldPhoto = $request->photo;
        if ($request->hasFile('photo')) {
            $request->validate([
                'photo' => 'image|mimes:jpg,jpeg,png|max:5120',
            ]);
            $path = $request->file('photo')->store('profile');
            $user->photo = $path;
        }


        $user->name = $request->name;
        $user->about = $request->about;

        if ($user->save()) {
            if($oldPhoto != $user->photo){
                Storage::delete($oldPhoto);
            }
            return response()->json($user, 200);
        } else {
            return response()->json(['message' => 'Some errors'], 500);
        }
    }

    public function logout(Request $request)
    {
        if($request->user()->tokens()->delete()) {
            return response()->json(['message' => 'Logout successfuly'], 200);
        } else {
            return response()->json(['message' => 'Some errors'], 500);
        }
    }
}
