<?php

namespace App\Http\Controllers\Admin\Login;

use App\Models\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function login(Request $request)
    {   
        $data = $request->all();
        $username = $request->username;
        $password = $request->password;
        $user = Admin::where('username', $username)->first();
        if(!$user){
          return  $this->failed('用户不存在');
        }

        if (!Hash::check($password,$user->password)) {
            return  $this->failed('密码不正确');
        }
        
        if (! $token = auth('admin')->attempt($data)) {
            return  $this->failed();
        }
        $xToken['token'] = $token;
        return response()->json([
            'code' => 200,
            'data' => $xToken
        ]);
    }

    public function info()
    {
        $user= auth('admin')->user();
        $data['roles'] =['editor'];
        $data['introduction'] = 'I am an editor';
        $data['avatar'] ='https://wpimg.wallstcn.com/f778738c-e4f8-4870-b634-56703b4acafe.gif';
        $data['name'] = 'admin';
        $data['user'] = $user->name;

        return response()->json([
            'code' => 200,
            'message' => '成功',
            'data' => $user
        ]);
    }

    public function logout()
    {
        return response()->json([
            'code' => 200,
            'data' => 'success'
        ]);
    }
}
