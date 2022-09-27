<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\ChangePasswordRequest;
use App\Http\Requests\Api\EmailVerifyRequest;
use App\Http\Requests\Api\ForgotPasswordRequest;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\ResetPasswordRequest;
use App\Http\Requests\Api\SignupRequest;
use App\Http\Requests\g2fverifyRequest;
use App\Http\Services\AuthService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use PragmaRX\Google2FA\Google2FA;

class AuthController extends Controller
{
    protected $service;
    function __construct()
    {
        $this->service = new AuthService();
    }

    // sign up process
    public function signUp(SignupRequest $request)
    {
        if (!filter_var($request['email'], FILTER_VALIDATE_EMAIL)) {
            $data = ['success' => false, 'data' => [], 'message' => __('Invalid email address')];
            return response()->json($data);
        }
        $response = $this->service->signUpProcess($request);

        return response()->json($response);
    }

    // log in process
    public function login(LoginRequest $request)
    {
        $response = $this->service->loginProcess($request);

        return response()->json($response);
    }

    // email verify
    public function emailVerify(EmailVerifyRequest $request)
    {
        $response = $this->service->emailVerifyProcess($request);

        return response()->json($response);
    }

    // forgot password process
    public function sendResetCode(ForgotPasswordRequest $request)
    {
        if (!filter_var($request['email'], FILTER_VALIDATE_EMAIL)) {
            $data = ['success' => false, 'data' => [], 'message' => __('Invalid email address')];
            return response()->json($data);
        }
        $response = $this->service->sendForgotPasswordCode($request);

        return response()->json($response);
    }

    // reset password process
    public function resetPassword(ResetPasswordRequest $request)
    {
        if (!filter_var($request['email'], FILTER_VALIDATE_EMAIL)) {
            $data = ['success' => false, 'data' => [], 'message' => __('Invalid email address')];
            return response()->json($data);
        }
        $response = $this->service->resetPasswordProcess($request);

        return response()->json($response);
    }

    public function changePassword(ChangePasswordRequest $request){
        return $this->service->changePasswordApp($request);
    }

    public function g2fVerifyApp(g2fverifyRequest $request){
        $google2fa = new Google2FA();
        $google2fa->setAllowInsecureCallToGoogleApis(true);
        $valid = $google2fa->verifyKey(Auth::user()->google2fa_secret, $request->code, 8);
        if ($valid){
            $user = Auth::user();
            Cache::put('g2f_checked',true);
            $user->photo = show_image($user->id,'user');
            $data = ['success' => true, 'data' => ['user'=>$user,'g2f_verify'=>false], 'message' => __('Login successful')];
        }
        else{
            $data = ['success' => false, 'data' => ['user'=>null,'g2f_verify'=>true], 'message' => __('Code not matched!')];
        }
        return response()->json($data);

    }

    public function logOutApp()
    {
        Cache::forget('g2f_checked');
        $user = Auth::user()->token();
        $user->revoke();
        return response()->json(['success' => true, 'data' => [], 'message' => __('Logout successfully!')]);
    }
}
