<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function Register(Request $request){
        $validate = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'confirm_password' => 'required|same:password'
        ]);

        if($validate->fails()){
            return response()->json([
                'success' => false,
                'message' => 'Invalid request params!',
                'data' => $validate->errors()
            ], 400);
        }

        $payload = $request->all();
        $payload['password'] = bcrypt($payload['password']);
        $user = User::create($payload);

        $success['email'] = $user->email;
        $success['name'] = $user->name;

        return response()->json([
            'success' => true,
            'message' => 'User registration success!',
            'data' => $success
        ], 200);
    }

    public function Login(Request $request){
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            $auth = Auth::user();
            $success['token'] = $auth->createToken('access_token')->plainTextToken;
            $success['name'] = $auth->name;

            return response()->json([
                'success' => true,
                'message' => 'Login success!',
                'data' => $success
            ], 200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Incorrect username or password. Please try again.',
                'data' => null
            ], 400);
        }
    }
}
