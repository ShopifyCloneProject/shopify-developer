<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\OtpVerificationRequest;
use App\Http\Requests\UpdateProfileSettingsRequest;
use App\Http\Requests\UpdateAccountInformationRequest;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use App\Models\UserDetail;
use App\Models\Country;
use App\Services\EmailService;
use Gate;
use Auth;
use Config;

class AccountController extends Controller
{
      protected $emailService;
      public function __construct()
      {
          $this->emailService = new EmailService;
      }

      public function account_settings()
      {
        abort_if(Gate::denies('manage_account_acccess'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $userData = User::whereId(Auth::user()->id)->first();
        $userDetail = UserDetail::where('user_id',$userData->id)->first();

        $countries = Country::get();
        $breadcrumbs = [['link' => "/", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Pages"], ['name' => "Account Settings"]];
        return view('admin/accounts/page-account-settings', compact('userData','userDetail','countries','breadcrumbs'));
      }

      // Profile
      public function profile()
      {
        $breadcrumbs = [['link' => "/", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Pages"], ['name' => "Profile"]];
        return view('admin/accounts/page-profile', ['breadcrumbs' => $breadcrumbs]);
      }

      public function resendotp(Request $request)
      {
        try {
              $data = [];
              $token = random_int(100000,999999);
              Auth::user()->update(['otp'=>$token]);
              if(!is_null($token) && !is_null($request->email)){
                    $data['otp'] = $token;
                    $data['email'] = Auth::user()->email;
                    $this->emailService->sendOtpVerifyMail($data);

                    if ($request->ajax()) {
                        $url = route('admin.accounts.page-account-settings');
                    return $this->successResponse(
                        __('constants.SUCCESS_STATUS'),
                        __('constants.messages.OTP_SEND_SUCCESSFULLY.code'),
                        __('constants.messages.OTP_SEND_SUCCESSFULLY.msg'),
                        ['url'=>$url]
                    );
                  }
            }
              else{
                    return $this->successResponse(
                        __('constants.SUCCESS_STATUS'),
                        __('constants.messages.PLEASE_ENTER_EMAIL.code'),
                        __('constants.messages.PLEASE_ENTER_EMAIL.msg'),
                    );
              }
        }
        catch (\Exception $e) {
         return $this->errorResponse(
                __('constants.ERROR_STATUS'),
                __('constants.errors.SOMETHING_WRONG.code'),
                __('constants.errors.SOMETHING_WRONG.msg'),
                $e->getMessage()
            );
        }
      }

      public function verifyotp(OtpVerificationRequest $request)
      {
        try {
              $authUser = Auth::user();
              $otp = (int) $request->otp;
              $oldotp = $authUser->otp;
              if($otp === $oldotp){
                  $objUser = User::where('id',$authUser->id)->first();
                  $objUser->email_verified_at = date("Y-m-d h:i:s");
                  $objUser->save();
                  return $this->successResponse(
                      __('constants.SUCCESS_STATUS'),
                      __('constants.messages.OTP_MATCHED_SUCCESSFULLY.code'),
                      __('constants.messages.OTP_MATCHED_SUCCESSFULLY.msg'),
                  );
              }
              else{
                  return $this->successResponse(
                    __('constants.SUCCESS_STATUS'),
                    __('constants.messages.OTP_NOT_MATCHED.code'),
                    __('constants.messages.OTP_NOT_MATCHED.msg'),
                  );
                }
            }
        catch (\Exception $e) {
         return $this->errorResponse(
                __('constants.ERROR_STATUS'),
                __('constants.errors.SOMETHING_WRONG.code'),
                __('constants.errors.SOMETHING_WRONG.msg'),
                $e->getMessage()
            );
        }
      }

      public function updateProfile(UpdateProfileSettingsRequest $request){
        try {  
              $params = collect($request->all());
              $userData = Auth::user();
              $user_id = $userData->id;
              $client_id = Config::get('client_id');
              if(!empty($userData))
              {
                  $userData->username = $params['username'];
                  $userData->name = $params['name'];
                  $userData->company = $params['company'];

                  if($params['resetProfile'] == 1){
                            $userData->image = null; 
                        }

                  if($request->hasFile('image')){
                    $path = "public/users";
                    $image = $request->file('image');
                    $this->checkFolder($path);
                    $this->checkFolder($path .'/'.$user_id);
                    $refrence_id = mt_rand( 1000, 9999);
                    $imagename = time().$refrence_id.".".$image->getClientOriginalExtension();
                    Storage::disk('public')->put("$client_id/users/$user_id/$imagename", File::get($image),'public');
                    $userData->image = $imagename;
                  }
                  $userData->save(); 
                  return redirect()->route('admin.manage-account')->with('success','Profile updated successfully !');
                }
        }
        catch (\Exception $e) {
              return $this->errorResponse(
                  __('constants.ERROR_STATUS'),
                  __('constants.errors.SOMETHING_WRONG.code'),
                  __('constants.errors.SOMETHING_WRONG.msg'),
                  $e->getMessage()
              );
        }
      }

      public function account_information(UpdateAccountInformationRequest $request){
        try {
              $authUser = Auth::user();
              $accountInfo = UserDetail::where('user_id',$authUser->id)->first();
              if(empty($accountInfo)){
                  $accountInfo = new UserDetail;
              }
                  $accountInfo->user_id = $authUser->id;
                  $accountInfo->bio = $request->bio;
                  $accountInfo->birth_date = $request->birth_date;
                  $accountInfo->country_id = $request->country_id;
                  $accountInfo->website = $request->website;
                  $accountInfo->phone = $request->phone;
                  $accountInfo->save();
                  return redirect()->route('admin.manage-account')->with('success','Account Information updated successfully !');
            }
        catch (Exception $e){
              return $this->errorResponse(
                  __('constants.ERROR_STATUS'),
                  __('constants.errors.SOMETHING_WRONG.code'),
                  __('constants.errors.SOMETHING_WRONG.msg'),
                  $e->getMessage()
              );
        }
      }

      public function social_links(Request $request){
        try {
              $authUser = Auth::user();
              $add_editLinks = UserDetail::where('user_id',$authUser->id)->first();
              if(empty($add_editLinks)){
                  $add_editLinks = new UserDetail;
                }
              $add_editLinks->twitter = $request->twitter;
              $add_editLinks->facebook = $request->facebook;
              $add_editLinks->google = $request->google;
              $add_editLinks->website = $request->website;
              $add_editLinks->linkedin = $request->linkedin;
              $add_editLinks->instagram = $request->instagram;
              $add_editLinks->quora = $request->quora;
              $add_editLinks->save();
              return redirect()->route('admin.manage-account')->with('success','Social links updated successfully !');
            }
        catch (Exception $e){
              return $this->errorResponse(
                __('constants.ERROR_STATUS'),
                __('constants.errors.SOMETHING_WRONG.code'),
                __('constants.errors.SOMETHING_WRONG.msg'),
                $e->getMessage()
            );
            }
      }
}
