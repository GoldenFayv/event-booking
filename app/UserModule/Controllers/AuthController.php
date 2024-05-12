<?php

namespace App\Http\Controllers\Api\V1\User;

use Throwable;
use App\Enums\MailTypes;
use Illuminate\Http\Request;
use App\Enums\UserTokenTypes;
use Illuminate\Http\Response;
use App\UserModule\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\UserModule\Services\UserService;

class AuthController extends Controller
{
    /**
     * @var User $user
     */
    public $user;
    public function __construct(private UserService $userService) {
        if (auth()->user()) {
            $this->user = auth()->user();
        }
    }
    public function register()
    {
        request()->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required',
            'password' => 'required',
            'address' => 'required',
            'username' => 'required',
            'profile_picture' => 'required',
        ]);
        $data = request()->all();

        try {
            $file_name = uploadFile(request()->profile_picture, 'user');

            $data['profile_picture'] = $file_name;

            $user = User::create($data);


            [$code, $expires_at] = generateCode();

            $this->userService->createOtp($user->id, UserTokenTypes::EMAIL_VERIFICATION(), $expires_at, $code);

            // sendMail($user->email, MailTypes::ACCOUNT_CREATION(), 'account_creation', [
            //     'name' => $user->first_name,
            //     'code' => $code,
            // ]);
            $this->send_email_verification();

            $token = auth()->attempt(['email' => request()->email, 'password' => request()->password]);
            $userDetails = $this->userService->getUserDetail($user, $token);
            if (!$token) {
                return failureResponse('Invalid Credential');
            } else {
                return successResponse('Logged in', $userDetails);
            }
        } catch (Throwable $th) {
            Log::warning($th);
            return failureResponse("Unable to log you in", $th->getMessage());
        }
    }

    public function login()
    {
        request()->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        try {
            $credentials = ['email' => request()->email, 'password' => request()->password];
            $user = User::where('email', $credentials['email'])->first();
            if (!$user || $user->deleted_at || !Hash::check($credentials['password'], $user->password)) {
                return failureResponse('Invalid credentials');
            } else {

                $token = auth()->attempt($credentials);

                if ($token) {
                    return successResponse("Logged in", $this->userService->getUserDetail($user, $token));
                } else {
                    return failureResponse("Unable to log you in");
                }
            }
        } catch (Throwable $th) {
            Log::warning($th);
            return failureResponse("Unable to log you in", $th->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function send_email_verification()
    {
        // Send verification email
        [$otp, $expires_at] = generateCode();
        $this->userService->createOtp($this->user->id, UserTokenTypes::EMAIL_VERIFICATION(), $expires_at, $otp);

        sendMail($this->user->email, MailTypes::EMAIL_VERIFICATION, 'email_verification',[
            'name' => $this->user->first_name,
            'code' => $otp,
        ]);

        return successResponse("OTP sent");
    }

    public function verify_email(Request $request)
    {
        $request->validate([
            'code' => 'required|numeric|digits:6' // Validate that 'code' must be exactly 6 digits long and contain only numeric characters
        ]);

        $otpCheck = $this->user->userOtp()->where([
            'type' => UserTokenTypes::EMAIL_VERIFICATION,
            'otp' => $request->code
        ])->first();

        if (!$otpCheck) {
            return failureResponse('Invalid OTP');
        }

        if ($otpCheck->expires_at < now()) {
            return failureResponse('OTP has expired');
        }
        /**
         * @var User
         */
        $this->user->update([
            'email-verified_at' => now(),
        ]);
        $this->user->userOtp()->where('type', UserTokenTypes::EMAIL_VERIFICATION())->delete();

        return successResponse('E-Mail verified');
    }

    public function delete_account()
    {
        $this->user->update([
            'deleted_at' => now(),
            'email' => null
        ]);

        return successResponse('Account Deleted');
    }

    public function update()
    {
        $userData = request()->all();

        $userData = array_filter($userData, function ($value) {
            return !is_null($value);
        });

        if (count($userData) === 0) {
            return failureResponse('No fields provided for update');
        }

        $this->user->update($userData);

        return successResponse('Updated');
    }


}
