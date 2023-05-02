<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use App\Models\User;

class UserApiController extends ApiController
{
    public function getSearchUsers(Request $request){
        try{
            $params = collect($request->all());
            $search = $params['search'];
            $response = [];
            if($search != ''){
                $objUsers = User::select('id','name','last_name','email','mobile')
                                ->where('name', 'LIKE', $search.'%')
                                ->orWhere('mobile', 'LIKE', $search.'%')
                                ->orWhere('email', 'LIKE', $search.'%')
                                ->get();
            }

            return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.USER_GET_SUCCESSFULLY.code'),
                __('constants.messages.USER_GET_SUCCESSFULLY.msg'),
                $objUsers,
            );
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
