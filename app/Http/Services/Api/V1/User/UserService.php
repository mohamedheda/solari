<?php

namespace App\Http\Services\Api\V1\User;

use App\Http\Resources\V1\User\SystemResource;
use App\Http\Resources\V1\User\UserDetailsResource;
use App\Http\Resources\V1\User\UserResource;
use App\Http\Resources\V1\User\UserSimpleResource;
use App\Http\Services\Mutual\FileManagerService;
use App\Http\Traits\Responser;
use App\Repository\UserRepositoryInterface;
use Illuminate\Support\Facades\DB;

class UserService
{
    use Responser;
    public function __construct(
        private readonly UserRepositoryInterface $userRepository ,
        private readonly FileManagerService $fileManagerService ,
    )
    {
    }
    public function index(){
        $users=auth('api')->user()?->load('technicians')->systems;
        return $this->responseSuccess(data: UserSimpleResource::collection($users));
    }
    public function getProfile(){
        $user=auth('api')->user();
        return $this->responseSuccess(data: UserDetailsResource::make($user,false));
    }
    public function updateProfile($request){
        DB::beginTransaction();
        try {
            $data = $request->validated();
            if ($request->image)
                $data['image']=$this->fileManagerService->upload('image','users');
            $this->userRepository->update(auth('api')->id(),$data);
            DB::commit();
            return $this->responseSuccess(message: __('messages.updated successfully'));
        } catch (\Exception $e) {
            DB::rollBack();
//            dd($e);
            return $this->responseFail(message: __('messages.Something went wrong'));
        }
    }


    public function store($request){
        DB::beginTransaction();
        try {
            $data = $request->validated();
            $user = $this->userRepository->create($data);
            $user->addRole('technician');
            DB::commit();
            return $this->responseSuccess(message: __('messages.created successfully'), data: new UserResource($user, false));
        } catch (\Exception $e) {
            DB::rollBack();
//            dd($e);
            return $this->responseFail(message: __('messages.Something went wrong'));
        }
    }
}
