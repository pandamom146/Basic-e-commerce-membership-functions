<!-- 處理用戶身份驗證相關操作的控制器。它包含了處理用戶註冊、登入、登出和資訊獲取 -->
<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatUser;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function signup(CreatUser $request)
    {
        $validateData = $request->validated();
        $user = new User([
            'name' => $validateData['name'],
            'email' => $validateData['email'],
            'password' => bcrypt($validateData['password']),
        ]);
        $user->save();
        return response('success', 201);
    }

    public function login(Request $request)
    {
        $validateData = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);
        if (!Auth::attempt($validateData)){
            return response('授權失敗', 401);
        }
        $user = $request->user();
        $tokenResult = $user->createToken('Token');
        $tokenResult->token->save();
        return response(['token'=> $tokenResult->accessToken]);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response(
            ["message" => "成功登出"]
        );
    }

    public function user(Request $request)
    {
        return response(
            $request->user()
        );
    }
}
