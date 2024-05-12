<?php

namespace App\UserModule\Services;
use App\UserModule\Models\User;
use App\UserModule\Models\UserOtp;
use Illuminate\Support\Facades\Storage;

class UserService
{

    public function createUser()
    {
        User::create();
    }
    public function createOtp($userId, $type, $expires_at, $otp)
    {
        UserOtp::create([
            'user_id' => $userId,
            'type' => $type,
            'expires_at' => $expires_at,
            'otp' => $otp
        ]);
    }

    public static function getUserDetail(User $userModel = null, $token = null, $user_id = null)
    {

        if ($userModel) {
            $user = $userModel;
        } else {
            $user = User::where("id", $user_id)->first();
        }

        $result = [
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'mobile_number' => $user->mobile_number,
            'profile_picture' => Storage::url("uploads/user/" . $user->profile_picture),
            'email_verified' => (bool) $user->email_verified_at,
        ];
        $token ? $result["bearer_token"] = $token : null;

        return $result;
    }
}
