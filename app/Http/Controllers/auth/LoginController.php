<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Services\CustomErrorMessages;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');
        $validator = Validator::make($credentials, [
            'username' => 'required',
            'password' => 'required|string|min:5|max:50'
        ]);
        if ($validator->fails()) {
            return ['status' => 'error', 'message' => $validator->errors()->first()];
        }
        try {
            // this authenticates the user details with the database and generates a token
            if (!$token = JWTAuth::attempt($credentials)) {
                return ['status' => 'error', 'message' => 'Invalid login credentials'];
            }
            $user= Auth::user();
        } catch (JWTException $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }

        return ['status' => 'success', 'token' => $token, 'userDetails' => $user, 'message'=>'Welcome..'];
    }

    public function logout()
    {
        try {
            if (JWTAuth::getToken()) {

                JWTAuth::invalidate(JWTAuth::getToken());
            }
            return response()->json(['status' => 'success','message' => 'Logged out successfully']);
        } catch (\Exception $e) {
            $message = CustomErrorMessages::getCustomMessage($e);
            return response()->json(['status' => 'error', 'message' => $message], 500);
        }
    }
}
