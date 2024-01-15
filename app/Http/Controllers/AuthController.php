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
        
        // 取得經過驗證的用戶輸入數據
        $validateData = $request->validated();

        // 將經過驗證的數據填充到模型。
        $user = new User([
            'name' => $validateData['name'],
            'email' => $validateData['email'],

            // 使用 bcrypt 函數對密碼進行加密。
            'password' => bcrypt($validateData['password']),
        ]);
        $user->save();
        return response('success', 201);
    }

    public function login(Request $request)
    {

        // 驗證用戶輸入的電子郵件和密碼。
        $validateData = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        // 嘗試進行用戶身份驗證。
        if (!Auth::attempt($validateData)){
            return response('授權失敗', 401);
        }

        // 取得已經登入的用戶
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
