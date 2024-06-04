<?php

namespace App\Http\Controllers;

use App\Mail\verifyEmail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tymon\JWTAuth\Facades\JWTAuth;

class AccountController extends Controller
{
    // Đăng ký
    public function register(Request $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Mail::to($user->email)->send(new verifyEmail($user));

        return response()->json([
            'status' => true,
            'message' => "Đăng kí thành công",
            'users' => $user
        ]);
    }

    // Gửi mail xác thực
    public function verifyEmail($email)
    {
        $account = User::where('email', $email)->whereNull('email_verified_at')->firstOrFail();
        $account->email_verified_at = Carbon::now();
        $account->save();
    }

    // Đăng nhập
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json([
                'status' => false,
                'message' => 'Đăng nhập thất bại',
            ], 401);
        }
        $user = auth()->user();
        if (is_null($user->email_verified_at)) {
            return response()->json([
                'status' => 401,
                'message' => 'Tài khoản chưa được xác thực',
            ]);
        }
        return response()->json([
            'status' => 200,
            'message' => 'Đăng nhập thành công',
            'token' => $token,
        ]);
    }

    // Lấy thông tin cá nhân
    public function profile()
    {
        $user = auth()->user();
        return response()->json([
            'status' => true,
            'data' => auth()->user(),
        ]);
    }

    // Làm mới token
    public function refreshToken()
    {
        $newToken = auth()->refresh();
        return response()->json([
            'status' => true,
            'token' => $newToken,
        ]);
    }

    // Đăng xuất
    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'Đăng xuất thành công']);
    }
}
