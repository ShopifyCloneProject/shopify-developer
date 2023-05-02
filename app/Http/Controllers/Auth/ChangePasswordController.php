<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UpdateProfileRequest;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use Auth;
use Hash;

class ChangePasswordController extends Controller
{
    public function edit()
    {
        abort_if(Gate::denies('profile_password_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('auth.passwords.edit');
    }

    public function update(UpdatePasswordRequest $request)
    {
        auth()->user()->update($request->validated());

        return redirect()->route('profile.password.edit')->with('message', __('global.change_password_success'));
    }

    public function updateProfile(UpdateProfileRequest $request)
    {
        $user = auth()->user();

        $user->update($request->validated());

        return redirect()->route('profile.password.edit')->with('message', __('global.update_profile_success'));
    }

    public function destroy()
    {
        $user = auth()->user();

        $user->update([
            'email' => time() . '_' . $user->email,
        ]);

        $user->delete();

        return redirect()->route('login')->with('message', __('global.delete_account_success'));
    }

     /** Change client and Freelancer Password */
    public function ChangePassword(Request $request)
    {
        try {
            $user = Auth::user();
            $user_id = $user->id;
            $params = collect($request->all());
            if($user->password == ''){
                $required = ['current_password','new_password'];
            } else {
                $required = ['new_password'];
            }
            $this->validateRequiredParams($required,$params->keys()->toArray());
            
            if($user->password != '')
            {
                if (Hash::check($params['current_password'], $user->password)) {
                    if(Hash::check($params['new_password'], $user->password))
                    {
                        return $this->errorResponse(
                            __('constants.ERROR_STATUS'),
                            __('constants.errors.PASSWORD_MUST_DIFFERENT.code'),
                            __('constants.errors.PASSWORD_MUST_DIFFERENT.msg')
                        );
                    }
                    else
                    {
                        $user->password = bcrypt($params['new_password']);
                        $user->save();
                        return $this->successResponse(
                            __('constants.SUCCESS_STATUS'),
                            __('constants.messages.PASSWORD_UPDATE_SUCCESS.code'),
                            __('constants.messages.PASSWORD_UPDATE_SUCCESS.msg')
                        );
                    }
                   
                } else {
                    return $this->errorResponse(
                        __('constants.ERROR_STATUS'),
                        __('constants.errors.BACKEND_OLD_PASSWORD_WRONG.code'),
                        __('constants.errors.BACKEND_OLD_PASSWORD_WRONG.msg')
                    );
                }
            }
            else
            {
               $user->password = bcrypt($params['new_password']);
                $user->save();
                return $this->successResponse(
                    __('constants.SUCCESS_STATUS'),
                    __('constants.messages.PASSWORD_UPDATE_SUCCESS.code'),
                    __('constants.messages.PASSWORD_UPDATE_SUCCESS.msg')
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
}