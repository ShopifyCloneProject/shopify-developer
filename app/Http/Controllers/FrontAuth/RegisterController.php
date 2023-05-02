<?php

namespace App\Http\Controllers\FrontAuth;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;

use App\Services\EmailService;

use App\Models\User;
use Hash;

class RegisterController extends ApiController
{
   
    protected $emailService;

    public function __construct(){
        $this->emailService = new EmailService;
    }

    public function register(Request $request)
    {
        try{
            $params = collect($request->all());
            $boolNewUser = true;
            if(isset($params['email']) &&  $params['email'] != null && $params['email']!= '')
            {
                $user = User::where('email', $params['email'])->first();
                if(!empty($user))
                {
                    if($user->password == null)
                    {
                        $boolNewUser = false;
                        $user->password = bcrypt($params['password']);
                        $user->role_id = 3;
                        $user->save();

                        $maildata['email'] = $user->email;
                        $maildata['name'] = $user->name;
                        $this->emailService->sendWelcomeMail($maildata);

                        return $this->successResponse(
                            __('constants.SUCCESS_STATUS'),
                            __('constants.messages.USER_REGISTER_SUCCESSFULLY.code'),
                            __('constants.messages.USER_REGISTER_SUCCESSFULLY.msg'),
                             []
                        );
                    }

                     return $this->successResponse(
                        __('constants.SUCCESS_STATUS'),
                        __('constants.messages.USER_EMAIL_ALREADY_REGISTER.code'),
                        __('constants.messages.USER_EMAIL_ALREADY_REGISTER.msg'),
                         []
                    );
                }
            }
            if(isset($params['mobile']) &&  $params['mobile'] != null && $params['mobile']!= '')
            {
                $user = User::where('mobile', $params['mobile'])->first();
                if(!empty($user))
                {
                    if($user->password == null)
                    {
                        $boolNewUser = false;
                        $user->password = bcrypt($params['password']);
                        $user->role_id = 3;
                        $user->save();
                         return $this->successResponse(
                            __('constants.SUCCESS_STATUS'),
                            __('constants.messages.USER_REGISTER_SUCCESSFULLY.code'),
                            __('constants.messages.USER_REGISTER_SUCCESSFULLY.msg'),
                             []
                        );
                    }
                    
                     return $this->successResponse(
                        __('constants.SUCCESS_STATUS'),
                        __('constants.messages.USER_MOBILE_ALREADY_REGISTER.code'),
                        __('constants.messages.USER_MOBILE_ALREADY_REGISTER.msg'),
                         []
                    );
                }
            }
            if($boolNewUser)
            {
                    $user = new User;
                    $user->username = trim($params['username']);
                    $user->name = trim($params['firstname']);
                    if(isset($params['email']) &&  $params['email'] != null && $params['email']!= '')
                    {
                        $user->email = trim($params['email']);
                    }
                    if(isset($params['mobile']) &&  $params['mobile'] != null && $params['mobile']!= '')
                    {
                        $user->mobile = trim($params['mobile']);
                    }
                    $user->password = bcrypt($params['password']);
                    $user->role_id = 3;
                    $user->save();

                    if(isset($params['email']) &&  $params['email'] != null && $params['email']!= '')
                    {
                        $maildata['email'] = $user->email;
                        $maildata['name'] = $user->name;
                        $this->emailService->sendWelcomeMail($maildata);
                    }

                    return $this->successResponse(
                        __('constants.SUCCESS_STATUS'),
                        __('constants.messages.USER_REGISTER_SUCCESSFULLY.code'),
                        __('constants.messages.USER_REGISTER_SUCCESSFULLY.msg'),
                         []
                    );
            }
        } catch (Exception $e) {
             return $this->errorResponse(
                __('constants.ERROR_STATUS'),
                __('constants.errors.SOMETHING_WRONG.code'),
                __('constants.errors.SOMETHING_WRONG.msg'),
                $e->getMessage()
            );
        }
   }
}
