<?php

namespace App\Http\Controllers\FrontAuth;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;

use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator as FacadesValidator;

use App\Models\User;

use Auth;
use Redirect;
use DB;
use Hash;

class ForgotPasswordController extends ApiController
{
    use SendsPasswordResetEmails, ResetsPasswords {
        SendsPasswordResetEmails::broker insteadof ResetsPasswords;
        ResetsPasswords::credentials insteadof SendsPasswordResetEmails;
    }

    public function index()
    {   
        if(Auth::check()){
            return Redirect::to('/');
        } else {
            $data = [
                'page'        => 'resetpassword',
                'askEmail'    => true,
                'askMobile'   => true,
                'user'        => [],
            ];
            if(false){

            }
            else{
                return view('theme.default.pages.resetpassword', compact('data'));
            }
        }
    }

    /**
    * Send password reset link. 
    */
    public function sendPasswordResetLink(Request $request)
    {
        try 
        {
            $params = collect($request->all());
            $required = ['email'];
            $this->validateRequiredParams($required,$params->keys()->toArray());

            $email = $params['email'];
            $user = User::where('email', $email)->where('role_id', 3)->first();

            if($user){
                return $this->sendResetLinkEmail($request);
            } else {
                return $this->errorResponse(
                    __('constants.SUCCESS_STATUS'),
                    __('constants.messages.RESET_DETAIL_NOT_FOUND.code'),
                    __('constants.messages.RESET_DETAIL_NOT_FOUND.msg')
                );
            }

        } catch (\Throwable $e) {
            return $this->errorResponse(
                __('constants.ERROR_STATUS'),
                __('constants.errors.SOMETHING_WRONG.code'),
                __('constants.errors.SOMETHING_WRONG.msg'),
                $e->getMessage()
            );
        }
    }

    // /**
    //  * Get the response for a successful password reset link.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @param  string  $response
    //  * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
    //  */
    protected function sendResetLinkResponse(Request $request, $response)
    {
        return $this->successResponse(
            __('constants.SUCCESS_STATUS'),
            __('constants.messages.RESET_MAIL_SEND_SUCCESSFULLY.code'),
            __('constants.messages.RESET_MAIL_SEND_SUCCESSFULLY.msg'),
            $response
        );
    }

    // /**
    // * Get the response for a failed password reset link.
    // *
    // * @param  \Illuminate\Http\Request  $request
    // * @param  string  $response
    // * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
    // */
    protected function sendResetLinkFailedResponse(Request $request, $response)
    {
        return $this->successResponse(
            __('constants.SUCCESS_STATUS'),
            __('constants.messages.RESET_EMAIL_FAILED.code'),
            __('constants.messages.RESET_EMAIL_FAILED.msg')
        );
    }

    public function showResetPasswordForm($token){
        if(Auth::check()){
            return Redirect::to('/');
        } else {
            $email = '';
            if(isset($_REQUEST['email'])){
                $email = $_REQUEST['email'];
            }
            
            $data = [
                'page'        => 'resetpasswordform',
                'token'       => $token,
                'email'       => $email,
                'user'        => [],
            ];
            if(false){

            }
            else{
                return view('theme.default.pages.resetpasswordform', compact('data'));
            }
        }
    }

    // /**
    // * Handle reset password 
    // */
    public function callResetPassword(Request $request)
    {
        return $this->reset($request);
    }

    // /**
    // * Reset the given user's password.
    // *
    // * @param  \Illuminate\Contracts\Auth\CanResetPassword  $user
    // * @param  string  $password
    // * @return void
    // */
    protected function resetPassword($user, $password)
    {
        $user->password = Hash::make($password);
        $user->save();
        event(new PasswordReset($user));
    }

    // /**
    // * Get the response for a successful password reset.
    // *
    // * @param  \Illuminate\Http\Request  $request
    // * @param  string  $response
    // * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
    // */
    protected function sendResetResponse(Request $request, $response)
    {
        return $this->successResponse(
            __('constants.SUCCESS_STATUS'),
            __('constants.messages.PASSWORD_RESET_SUCCESSFULLY.code'),
            __('constants.messages.PASSWORD_RESET_SUCCESSFULLY.msg'),
            $response
        );
    }

    // /**
    // * Get the response for a failed password reset.
    // *
    // * @param  \Illuminate\Http\Request  $request
    // * @param  string  $response
    // * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
    // */
    protected function sendResetFailedResponse(Request $request, $response)
    {
        return $this->successResponse(
            __('constants.SUCCESS_STATUS'),
            __('constants.messages.RESET_PASSWORD_FAILED.code'),
            __('constants.messages.RESET_PASSWORD_FAILED.msg')
        );
    }
}
