<?php
namespace App\Helpers\User;

use Throwable;
use App\Helpers\Venturo;
use App\Models\UserModel;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\User\UserResource;
use Tymon\JWTAuth\Exceptions\JWTException;

/**
 * Helper untuk manajemen user
 * Mengambil data, menambah, mengubah, & menghapus ke tabel user_auth
 *
 */
class AuthHelper extends Venturo
{
    public static function login($email, $password)
    {
        try {
            $credentials = ['email' => $email, 'password' => $password];

            if (!$token = JWTAuth::attempt($credentials)) {
                return [
                    'status' => false,
                    'error' => ['Kombinasi email dan password yang kamu masukkan salah']
                ];
            }
        } catch (JWTException $e) {
            return [
                'status' => false,
                'error' => ['Could not create token.']
            ];
        }

        return [
            'status' => true,
            'data' => self::createNewToken($token)
        ];
    }

    protected static function createNewToken($token)
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'user' => new UserResource(auth()->user())
        ];
    }
}