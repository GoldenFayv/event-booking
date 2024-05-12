<?php

namespace App\UserModule\Controllers;

use App\UserModule\Models\User;
use App\UserModule\Services\UserService;

class UserController
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
