<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\UpdateProfileRequest;
use App\Http\Requests\Api\V1\User\UserRequest;
use App\Http\Services\Api\V1\User\UserService;
use App\Http\Traits\FirebaseTrait;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use FirebaseTrait;
    public function __construct(private readonly UserService $userService)
    {

    }
    public function index(){
        return $this->userService->index();
    }
    public function getProfile(){
        return $this->userService->getProfile();
    }
    public function store(UserRequest $request){
        return $this->userService->store($request);
    }
    public function updateProfile(UpdateProfileRequest $request){
        return $this->userService->updateProfile($request);
    }

    public function sendNotifications(){
        $fcms=User::query()->pluck('fcm');
        return $this->sendNotification($fcms);
    }

}
