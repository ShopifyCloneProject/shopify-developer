<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Support\Carbon;
use App\Models\Role;
use App\Models\User;
use App\Models\Country;
use App\Models\Address;
use App\Models\Ctag;
use App\Models\Cart;
use App\Models\Order;
use App\Models\CartDetail;
use App\Models\PaymentMethod;
use App\Models\OrderProduct;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\File;
use Auth;
use Redirect;
use DB;
use Config;
use Storage;

class UsersController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('user_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = User::with(['addresses','role'])->select(sprintf('%s.*', (new User())->table)); //2 for User. make it dynamic if required
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'user_show';
                $editGate = 'user_edit';
                $deleteGate = 'user_delete';
                $crudRoutePart = 'users';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name.' '.$row->last_name : '';
            });
            $table->editColumn('mobile', function ($row) {
                return $row->mobile;
            });
            $table->editColumn('username', function ($row) {
                return $row->username;
            });
            $table->editColumn('email', function ($row) {
                return $row->email;
            });
            $table->editColumn('gender', function ($row) {
                return $row->gender ? User::GENDER_RADIO[$row->gender] : '';
            });
            $table->editColumn('blocked', function ($row) {
                return $row->blocked ? User::BLOCKED_RADIO[$row->blocked] : '';
            });
            $table->editColumn('email_notification_status', function ($row) {
                return $row->email_notification_status ? User::EMAIL_NOTIFICATION_STATUS_RADIO[$row->email_notification_status] : '';
            });
            $table->editColumn('sms_notification_status', function ($row) {
                return $row->sms_notification_status ? User::SMS_NOTIFICATION_STATUS_RADIO[$row->sms_notification_status] : '';
            });
            $table->editColumn('image', function($row){
                return $row->image;
            });
             $table->editColumn('created_at', function ($row) {
                return $row->created_at;
            });
            $table->rawColumns(['actions','role']);

            return $table->make(true);
        }
        $emailStatus = User::EMAIL_NOTIFICATION_STATUS_RADIO;
        $smsStatus = User::SMS_NOTIFICATION_STATUS_RADIO;
        $blocked = User::BLOCKED_RADIO;
        $roles = Role::get();
        $users = User::get();
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['name' => trans('cruds.order.customer')." ".trans('global.listing') ]];
        return view('admin.customers.index',compact('breadcrumbs','emailStatus','smsStatus','blocked','roles','users'));
    }

    public function create()
    {
        abort_if(Gate::denies('user_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $type = "add";
        $data = $list = [];
        $list['emailStatus'] = User::EMAIL_NOTIFICATION_STATUS_RADIO;
        $list['smsStatus'] = User::SMS_NOTIFICATION_STATUS_RADIO;
        $list['blocked'] = User::BLOCKED_RADIO;
        $list['roles'] = Role::get()->pluck('title','id');

        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => 'Home'], ['link'=>route('admin.users.index'),'name' => trans('cruds.order.customer') ],['name' => trans('locale.Add')." ".trans('cruds.order.customer') ]];

        return view('admin.customers.createupdate', compact('list','breadcrumbs','type','data'));
    }

    public function store(Request $request)
    {
        try{
            abort_if(Gate::denies('user_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
            $params = collect($request->all());
            $required = ['firstName', 'lastName', 'email', 'phone'];
            $this->validateRequiredParams($required,$params->keys()->toArray());

            $firstName = $params['firstName'];
            $lastName = $params['lastName'];
            $email = $params['email'];
            $phone = $params['phone'];
            $phoneCode = $params['phoneCode'];
            $is_agree = $params['is_agree'];
            $is_collect_tax = $params['is_collect_tax'];
            $note = $params['note'];
            $tags = '';
            if(!empty($tags)){
                $tags = implode(', ', $tags);
            }

            $user = new user;
            $user->name = $firstName;
            $user->last_name = $lastName;
            $user->email = $email;
            $user->mobile = $phone;
            $user->phone_code = $phoneCode;
            $user->accept_marketing = $is_agree;
            $user->tax_exempt = $is_collect_tax;
            $user->note = $note;
            $user->tags = $tags;
            $user->role_id = 3; //temporary set 2 for user
            $user->save();
            $userId = $user->id;

             //for address
            $firstName1 = $params['address']['firstName'];
            $lastName1 = $params['address']['lastName'];
            $phone1 = $params['address']['phone'];
            $address1 = $params['address']['address1'];
            $address2 = $params['address']['address2'];
            $city = $params['address']['city'];
            $country = $params['address']['country'];
            $state = $params['address']['state'];
            $pincode = $params['address']['pincode'];
            $company = $params['address']['company'];
            $phoneCode1 = $params['phoneCode'];
            
            if( $firstName1 != '' || $lastName1 != '' || $phone1 != '' ||  $address1 != '' || $address2 != '' || $city != '' || $country != '' || $state != '' || $pincode != '' || $company != '')
            {
                //creste address only if one of the address details is available
                $address = new Address;
                $address->user_id = $userId;
                $address->first_name = $firstName1 != '' ? $firstName1 : $firstName;
                $address->last_name = $lastName1 != '' ? $lastName1 : $lastName;
                $address->company_name = $company;
                $address->address = $address1;
                $address->address_2 = $address2;
                $address->mobile = $phone1;
                $address->country_id = $country;
                $address->state_id = $state;
                $address->city_name = $city;
                $address->postal_code = $pincode;
                $address->is_default = 1; //1=default, 0=not default
                $address->phone_code = $phoneCode1; //need to remove if note required
                $address->save();
            }

            return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.CUSTOMER_ADD_SUCCESSFULLY.code'),
                __('constants.messages.CUSTOMER_ADD_SUCCESSFULLY.msg')
            );

        } catch (Exception $e) {
            return $this->errorResponse(
                __('constants.ERROR_STATUS'),
                __('constants.errors.SOMETHING_WRONG.code'),
                __('constants.errors.SOMETHING_WRONG.msg'),
                $e->getMessage()
            );
        }
        $user = User::create($request->all());
        $user->roles()->sync($request->input('roles', []));
        if ($request->input('pics', false)) {
            $user->addMedia(storage_path('tmp/uploads/' . basename($request->input('pics'))))->toMediaCollection('pics');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $user->id]);
        }   
    }

    public function edit(User $user,$id)
    {
        abort_if(Gate::denies('user_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $type = "edit";
        $data = $list = [];
        $list['emailStatus'] = User::EMAIL_NOTIFICATION_STATUS_RADIO;
        $list['smsStatus'] = User::SMS_NOTIFICATION_STATUS_RADIO;
        $list['blocked'] = User::BLOCKED_RADIO;
        $list['roles'] = Role::get()->pluck('title','id');

       /* $countries = Country::get();
        $tags = Ctag::all()->pluck('title', 'id');

        $list = [
            'countries' => $countries,
            'tags' => $tags
        ];
        $user->addresses->load('state');
        $user->addresses->load('country');

        $addresses = [];
        foreach ($user->addresses as $key => $address) {
            $addresses[$key]['id'] =  $address->id;
            $addresses[$key]['firstName'] =  $address->first_name;
            $addresses[$key]['lastName'] =  $address->last_name;
            $addresses[$key]['company'] =  $address->company_name;
            $addresses[$key]['address1'] =  $address->address;
            $addresses[$key]['address2'] =  $address->address_2;
            $addresses[$key]['phone'] =  $address->mobile;
            $addresses[$key]['city'] =  $address->city_name;
            $addresses[$key]['country'] =  $address->country_id;
            $addresses[$key]['shortCode'] =  ($address->country) ? $address->country->short_code : '';
            $addresses[$key]['state'] =  $address->state_id;
            $addresses[$key]['stateName'] = ($address->state) ? $address->state->name : '';
            $addresses[$key]['pincode'] =  $address->postal_code;
            $addresses[$key]['is_default'] =  $address->is_default;
            $addresses[$key]['phoneCode'] =  ($address->country) ? $address->country->phone_code : '';
        }

        if(!empty($addresses)){
          $addressesSort = collect($addresses)->sortByDesc('is_default');
          $addresses = $addressesSort->values()->all();
        }*/ 
        $data['user'] = User::whereId($id)->first()->toArray();
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => 'Home'], ['link'=>route('admin.customers.index'),'name' => 'Customer' ],['name' => 'Edit Customer' ]];
        return view('admin.customers.createupdate', compact('list', 'data','breadcrumbs','type'));
    }

    public function updateCustomerDetails(StoreUserRequest $request)
    {
       try{
            $client_id = Config::get('client_id');
            $params = collect($request->all());
            if(isset($params['id'])){
                $objUser = User::where('id',$params['id'])->first();
            }
            if(empty($objUser)){
                $objUser = new User;
            }
            $objUser->name = $params['name'];
            $objUser->last_name = $params['last_name'];
            $objUser->mobile = $params['mobile'];
            $objUser->username = $params['username'];
            $objUser->email = $params['email'];
            if(isset($params['password'])){
                $objUser->password = bcrypt($params['password']);
            }
            $objUser->gender = $params['gender'];
            $objUser->blocked = $params['blocked'];
            $objUser->email_notification_status = $params['email_notification_status'];
            $objUser->sms_notification_status = $params['sms_notification_status'];
            $objUser->role_id = $params['role_id'];
            $objUser->save();
            $userId= $objUser->id;

            $path = "public/".$client_id;
            if(isset($params['userimage']) && $params['userimage']!= "null"){                
                $this->checkFolder($path);
                $this->checkFolder($path.'/users');
                $this->checkFolder($path.'/users'.$userId);
                $refrence_id = mt_rand(1000,9999);
                $imagename = time().$refrence_id.".png";
                $image = file_get_contents($params['userimage']);
                Storage::disk('public')->put("$client_id/users/$userId/$imagename",$image,'public');
                $objUser->image = $imagename;
            }
            $objUser->save();

            if(isset($params['id'])){
                 $url = route('admin.customers.index');
            return $this->successResponse(
                    __('constants.SUCCESS_STATUS'),
                    __('constants.messages.CUSTOMER_EDIT_SUCCESSFULLY.code'),
                    __('constants.messages.CUSTOMER_EDIT_SUCCESSFULLY.msg'),
                    ['url'=>$url]
                );
            } else {
                $url = route('admin.customers.edit' , ['customer',$userId]);
                 return $this->successResponse(
                    __('constants.SUCCESS_STATUS'),
                    __('constants.messages.CUSTOMER_ADD_SUCCESSFULLY.code'),
                    __('constants.messages.CUSTOMER_ADD_SUCCESSFULLY.msg'),
                    ['url'=>$url]
                );
            }
        }
        catch (Exception $e) {
            return $this->errorResponse(
                __('constants.ERROR_STATUS'),
                __('constants.errors.SOMETHING_WRONG.code'),
                __('constants.errors.SOMETHING_WRONG.msg'),
                $e->getMessage()
            );
    }
}

    public function addEditCustomerAddress(Request $request)
    {
       try{
            abort_if(Gate::denies('user_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
            
            $params = collect($request->all());
            $required = ['firstName', 'lastName'];
            $this->validateRequiredParams($required,$params->keys()->toArray());

            $userId = $params['uid'];
            //get customer details
            $user = User::where('id', $userId)->first();
            $addressLength = $user->addresses->count();

            //set default user first name and last name if not available while add or edit address
            $firstName = $params['firstName'] != '' ? $params['firstName'] : $user->name;
            $lastName = $params['lastName'] != '' ? $params['lastName'] : $user->last_name;
            $phone = $params['phone'];
            $address1 = $params['address1'];
            $address2 = $params['address2'];
            $city = $params['city'];
            $country = $params['country'];
            $state = $params['state'];
            $pincode = $params['pincode'];
            $company = $params['company'];
            $phoneCode = $params['phoneCode'];
            $is_default =  $addressLength == 0 ? 1 : 0; //check default address already set or not

            $address = new Address;
            if(isset($params['id'])){ // check for address id
                $address = Address::where('id', $params['id'])->first();
                $is_default = $params['is_default'];
            }
            
            if(isset($userId)){
                $address->user_id = $userId;
            }

            $address->first_name = $firstName;
            $address->last_name = $lastName;
            $address->company_name = $company;
            $address->address = $address1;
            $address->address_2 = $address2;
            $address->mobile = $phone;
            $address->phone_code = $phoneCode;
            $address->country_id = $country;
            $address->state_id = $state;
            $address->city_name = $city;
            $address->postal_code = $pincode;
            $address->is_default = $is_default; //1=default, 0=not default
            $address->save();

            if(isset($params['id'])){
                //edit recored
                 return $this->successResponse(
                    __('constants.SUCCESS_STATUS'),
                    __('constants.messages.CUSTOMER_ADDRESS_EDIT_SUCCESSFULLY.code'),
                    __('constants.messages.CUSTOMER_ADDRESS_EDIT_SUCCESSFULLY.msg'),
                );
            } else {
                //add new record block
                $addresses['id'] =  $address->id;
                $addresses['firstName'] =  $address->first_name;
                $addresses['lastName'] =  $address->last_name;
                $addresses['company'] =  $address->company_name;
                $addresses['address1'] =  $address->address;
                $addresses['address2'] =  $address->address_2;
                $addresses['phone'] =  $address->mobile;
                $addresses['city'] =  $address->city_name;
                $addresses['country'] =  $address->country_id;
                $addresses['shortCode'] =  ($address->country) ? $address->country->short_code : '';
                $addresses['state'] =  $address->state_id;
                $addresses['stateName'] = ($address->state) ? $address->state->name : '';
                $addresses['pincode'] =  $address->postal_code;
                $addresses['is_default'] =  $address->is_default;
                $addresses['phoneCode'] =  $address->phone_code;

                return $this->successResponse(
                    __('constants.SUCCESS_STATUS'),
                    __('constants.messages.CUSTOMER_ADDRESS_ADD_SUCCESSFULLY.code'),
                    __('constants.messages.CUSTOMER_ADDRESS_ADD_SUCCESSFULLY.msg'),
                    $addresses
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

    public function defaultAddress($id){
        try{
            // $params = collect($request->all());
            // $required = ['id', 'firstName', 'lastName', 'email', 'phone'];
            // $this->validateRequiredParams($required,$params->keys()->toArray());

            /*$address = Address::where('id', $id)->first();
            $address->is_default = 1;
            $address->save();*/

            $userId =  $address->user_id;

            Address::where('user_id', $userId)->where('id','!=', $id)->update(['is_default' =>  0]);

            return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.CUSTOMER_STATUS_CHANGE_SUCCESSFULLY.code'),
                __('constants.messages.CUSTOMER_STATUS_CHANGE_SUCCESSFULLY.msg')
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

    public function changeTaxStatus(Request $request){
        try{

            $params = collect($request->all());
            $id = $params['id'];
            $taxStatus = $params['taxStatus'];
            
            $user = User::where('id', $id)->first();
            $user->tax_exempt = $taxStatus;
            $user->save();

            return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.CUSTOMER_TAX_UPDATE_SUCCESSFULLY.code'),
                __('constants.messages.CUSTOMER_TAX_UPDATE_SUCCESSFULLY.msg')
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

    public function changeSubscriptionStatus(Request $request){
        try{
            
            $params = collect($request->all());
            $id = $params['id'];
            $subscriptionStatus = $params['subscriptionStatus'];
            
            $user = User::where('id', $id)->first();
            $user->email_notification_status = $subscriptionStatus;
            $user->save();

            return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.CUSTOMER_EMAIL_STATUS_UPDATE_SUCCESSFULLY.code'),
                __('constants.messages.CUSTOMER_EMAIL_STATUS_UPDATE_SUCCESSFULLY.msg')
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

     public function addNote(Request $request){
        try{
            
            $params = collect($request->all());
            $id = $params['id'];
            $note = $params['note'];
            
            $user = User::where('id', $id)->first();
            $user->note = $note;
            $user->save();

            return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.CUSTOMER_EDIT_SUCCESSFULLY.code'),
                __('constants.messages.CUSTOMER_EDIT_SUCCESSFULLY.msg')
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

    public function show(User $user,$id)
    {
        abort_if(Gate::denies('user_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $searchProductLimit = Config::get('SEARCH_PRODUCT_LIMIT');
        $list = $data = [];

        $data['user'] = User::where('id',$id)->first(); 

        // $collection = Order::select('id','order_nr','paid_at','financial_status','sub_total','taxes','total','payment_method_id','shipping_cost','discount_amount','created_at','user_id')->where('user_id',$id); 
        // $collection = $collection->whereYear('created_at','=',$year);
        // dd($collection->get());
       
        $data['customerOrder'] = Order::select('id','order_nr','paid_at','financial_status','sub_total','taxes','total','payment_method_id','shipping_cost','discount_amount','user_id','currency_id')->where('user_id',$id)->whereBetween('created_at',[Carbon::now()->subMonth(3), Carbon::now()])->orderBy('order_nr','desc')->get()->toArray();
        $payment_method_id = Order::where('user_id',$id)->get()->pluck('payment_method_id')->toArray();
        $list['paymentMethod'] = PaymentMethod::withTrashed()->get()->pluck('title','id');
        $list['emailStatus'] = User::EMAIL_NOTIFICATION_STATUS_RADIO;
        $list['smsStatus'] = User::SMS_NOTIFICATION_STATUS_RADIO;
        $list['blocked'] = User::BLOCKED_RADIO;

        foreach($data['customerOrder'] as $key=>$customerorder){
            $orderId = Order::where('id',$customerorder['id'])->first()->toArray();
            $data['customerOrder'][$key]['total'] = ($orderId['sub_total'] + $orderId['taxes'] + $orderId['shipping_cost']) - $orderId['discount_amount'];
            $data['customerOrder'][$key]['quantity'] = OrderProduct::where('order_id',$customerorder['id'])->get()->sum('quantity');
        }
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => 'Home'], ['link'=>route('admin.customers.index'),'name' => trans('cruds.order.title') .' '.trans('global.listing')], ['name' => trans('global.show') .' '.trans('cruds.order.title_singular') ]];
        return view('admin.customers.show', compact('user','data','list','breadcrumbs'));
    }

    public function destroy(User $user,$id)
    {
        try{
            abort_if(Gate::denies('user_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
            User::where('id',$id)->delete();

            return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.CUSTOMER_DELETE_SUCCESSFULLY.code'),
                __('constants.messages.CUSTOMER_DELETE_SUCCESSFULLY.msg')
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

    public function massDestroy(MassDestroyUserRequest $request)
    {
        try{
            User::whereIn('id', request('ids'))->delete();

            return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.CUSTOMER_DELETE_SUCCESSFULLY.code'),
                __('constants.messages.CUSTOMER_DELETE_SUCCESSFULLY.msg')
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

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('user_create') && Gate::denies('user_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new User();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }

    public function logoutAdmin(Request $request)
    {
      Auth::logout();
      return Redirect::to('/admin');
    }

    public function changeDataType()
    {
        DB::statement("ALTER TABLE `oauth_auth_codes` CHANGE COLUMN `user_id` `user_id` CHAR(50)  NULL DEFAULT ''");
        DB::statement("ALTER TABLE `oauth_access_tokens` CHANGE COLUMN `user_id` `user_id` CHAR(50)  NULL DEFAULT ''");
        DB::statement("ALTER TABLE `oauth_clients` CHANGE COLUMN `user_id` `user_id` CHAR(50)  NULL DEFAULT ''");

        dd("complete");
    }

     public function getSortOrders(Request $request){
        try{
            $params = collect($request->all());
            $data['customerOrder'] = Order::select('id','order_nr','paid_at','financial_status','sub_total','taxes','total','payment_method_id','shipping_cost','discount_amount','created_at','user_id')->orderBy('order_nr','desc')->where('user_id',$params['user_id']); 
            
            if(empty($params['selectFilterOrder'])){
                return $this->errorResponse(
                __('constants.ERROR_STATUS'),
                __('constants.errors.NOT_FOUND.code'),
                __('constants.errors.NOT_FOUND.msg'),
                $e->getMessage()
            );
            }elseif($params['selectFilterOrder'] == 'All'){
                $data['customerOrder'] = $data['customerOrder'];
            }elseif($params['selectFilterOrder'] == 'Last_Month'){
                $data['customerOrder'] = $data['customerOrder']->whereBetween('created_at',[Carbon::now()->subMonth(1), Carbon::now()]);
            } elseif($params['selectFilterOrder'] == 'Last_3_Month'){
                $data['customerOrder'] = $data['customerOrder']->whereBetween('created_at',[Carbon::now()->subMonth(3), Carbon::now()]);
            } elseif($params['selectFilterOrder'] == 'Last_6_Month'){
                $data['customerOrder'] = $data['customerOrder']->whereBetween('created_at',[Carbon::now()->subMonth(6), Carbon::now()]);
            } elseif($params['selectFilterOrder'] == 'Last_Year'){
                $data['customerOrder'] = $data['customerOrder']->whereYear('created_at',now()->year-1);
            } else{
                $data['customerOrder'] = $data['customerOrder']->whereYear('created_at','=',$params['selectFilterOrder']);
            } 

            if(!empty($params['filterOrderId'])){
                $data['customerOrder'] = $data['customerOrder']->where('id',$params['filterOrderId']);
            }
            if(!empty($params['filterOrderNo'])){
                $data['customerOrder'] = $data['customerOrder']->where('order_nr',$params['filterOrderNo']);
            }

            $data['customerOrder'] = $data['customerOrder']->get();
            // dd($data['customerOrder']->toArray());

            return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.CUSTOMER_ORDER_FILTERED_SUCCESSFULLY.code'),
                __('constants.messages.CUSTOMER_ORDER_FILTERED_SUCCESSFULLY.msg'),
                $data['customerOrder']
            );
        } catch (Exception $e) {
             return $this->errorResponse(
                __('constants.ERROR_STATUS'),
                __('constants.errors.SOMETHING_WRONG.code'),
                __('constants.errors.SOMETHING_WRONG.msg'),
                $e->getMessage()
            );
        }
        return view('admin.customers.show', compact('data','list'));
    }

}
