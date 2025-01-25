<?php

namespace App\Http\Services\Api\V1\Auth\Otp;

use App\Http\Resources\V1\Otp\OtpResource;
use App\Http\Traits\Responser;
use App\Jobs\SendingEmail;
use App\Mail\GenerateOtp;
use App\Mail\ResetPassword;
use App\Repository\OtpRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class OtpService
{
    use Responser;

    public function __construct(
        private readonly OtpRepositoryInterface $otpRepository,
    )
    {

    }

    public function generate($user = null)
    {
        $otp = $this->otpRepository->generateOtp($user);
        auth('api')->user()?->update([
            'otp_verified' => false
        ]);
        // TODO :Sending OTP in email
        return $this->responseSuccess(message: __('messages.OTP_Is_Send'), data: OtpResource::make($otp));
    }

    public function verify($request)
    {
        try {
            DB::beginTransaction();
            $data = $request->validated();
            if (!$this->otpRepository->check($data['otp'], $data['otp_token']))
                return $this->responseFail(message: __('messages.Wrong OTP code or expired'));

            auth('api')->user()?->otps()?->delete();
            auth('api')->user()?->update([
                'otp_verified' => true
            ]);
            DB::commit();
            return $this->responseSuccess(message: __('messages.Your account has been verified successfully'));
        } catch (\Exception $e) {
            DB::rollBack();
//            return $e;
            return $this->responseFail(message: __('messages.Something went wrong'));
        }
    }

}
