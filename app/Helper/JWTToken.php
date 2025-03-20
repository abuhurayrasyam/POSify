<?php
namespace App\Helper;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
class JWTToken{
    public static function createToken($userEmail, $userId){
        $key = env('JWT_KEY');
        $payload = [
            'iss' => 'laravel-token',
            'iat' => time(),
            'exp' => time() + 60 * 60 * 24,
            'userEmail' => $userEmail,
            'userId' => $userId,
        ];

        return JWT::encode($payload, $key, 'HS256');
    }//end method

    public static function verifyToken($token):string | object{
        try{
            if($token == null){
                return 'unauthorized';
            }else{
                $key = env('JWT_KEY');
                $decoded = JWT::decode($token, new Key($key, 'HS256'));
                return $decoded;
            }
        }catch(\Exception $e){
            return 'unauthorized';
        }
    }//end method

    public static function createTokenForSetPassword($userEmail){
        $key = env('JWT_KEY');
        $payload = [
            'iss' => 'laravel-token',
            'iat' => time(),
            'exp' => time() + 60 * 60 * 24,
            'userEmail' => $userEmail,
            'userId' => '0',
        ];

        return JWT::encode($payload, $key, 'HS256');
    }//end method
}