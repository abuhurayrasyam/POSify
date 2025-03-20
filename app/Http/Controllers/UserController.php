<?php

namespace App\Http\Controllers;

use App\Helper\JWTToken;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\OTPMail;

class UserController extends Controller
{
    public function userRegistration(Request $request){
        try{
            $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required',
            ]);
            $user = User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'), 
                'password' => $request->input('password')
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'User created successfully',
                'data' => $user
            ]);
        }catch(\Exception $e){
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }//end method

    public function userLogin(Request $request){
        $count = User::where('email', $request->input('email'))->where('password', $request->input('password'))->select('id')->first();
        if($count !== null){ 
            //user login JWT token issue
            $token = JWTToken::createToken($request->input('email'), $count->id);
            return response()->json([
                'status' => 'success',
                'message' => 'User login successfully',
                'token' => $token
            ],200)->cookie('token', $token, 60 * 24 * 30);
        }else{
            return response()->json([
                'status' => 'fail',
                'message' => 'unauthorized'
            ],200);
        }
    } //end method

    public function dashboard(Request $request){
        $user = $request->header('userEmail');
        return response()->json([
            'status' => 'success',
            'message' => 'User login successfully',
            'user' => $user
        ],200);
    } //end method

    public function userLogout(Request $request){
        return response()->json([
            'status' => 'success',
            'message' => 'User logout successfully',
        ],200)->cookie('token', '', -1);
    } //end method

    public function sentOtp(Request $request){
        $email = $request->input('email');
        $otp = rand(1000,9999);
        $count = User::where('email', $email)->count();
        if($count == 1){
            Mail::to($email)->send(new OTPMail($otp));
            User::where('email', $email)->update(['otp' => $otp]);
            return response()->json([
                'status' => 'success',
                'message' => "4 digit {$otp} OTP sent successfully",
            ],200);
        }else{
            return response()->json([
                'status' => 'fail',
                'message' => 'unauthorized'
            ],200);
        }
    } //end method

    public function verifyOtp(Request $request){
        $email = $request->input('email');
        $otp = $request->input('otp');
        $count = User::where('email', $email)->where('otp', $otp)->count();
        if($count == 1){
            User::where('email', $email)->update(['otp' => 0]);
            $token = JWTToken::createTokenForSetPassword($request->input('email'));

            return response()->json([
                'status' => 'success',
                'message' => 'OTP verified successfully',
            ],200)->cookie('token', $token, 60 * 24 * 30);
        }else{
            return response()->json([
                'status' => 'fail',
                'message' => 'unauthorized'
            ],200);
        }
    } //end method

    public function resetPassword(Request $request){
        try{
            $request->validate([
                'password' => 'required',
            ]);
            $email = $request->header('userEmail');
            $password = $request->input('password');
            User::where('email', $email)->update(['password' => $password]);
            return response()->json([
                'status' => 'success',
                'message' => 'Password reset successfully',
            ],200);
        }catch(\Exception $e){
            return response()->json([
                'status' => 'fail',
                'message' => $e->getMessage()
            ]);
        }
    }
}
