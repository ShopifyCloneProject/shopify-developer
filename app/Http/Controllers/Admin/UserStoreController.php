<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyUserStoreRequest;
use App\Http\Requests\StoreUserStoreRequest;
use App\Http\Requests\UpdateUserStoreRequest;
use App\Models\Address;
use App\Models\UserStore;
use App\Models\Weightmanage;
use App\Models\Country;
use App\Models\State;
use App\Models\TimeZone;
use App\Models\Currency;
use App\Models\UserStoreIndustry;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

use Auth;
use Redirect;
use DB;
use Config;

class UserStoreController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('user_store_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $userId = Config::get('client_id');

        $weights = Weightmanage::get();
        $countries = Country::all()->pluck('name', 'id');
        $timezones = TimeZone::all()->pluck('title', 'id');
        $currencies = Currency::get();
        $industries = UserStoreIndustry::get();

        $userStore = UserStore::where('user_id', $userId)->first();

        $list = [
            'weight_units' => $weights,
            'unit_system' => UserStore::UNIT_SYSTEM_SELECT,
            'countries' =>  $countries,
            'timezones' =>  $timezones,
            'currencies' =>  $currencies,
            'industries' =>  $industries,
        ];

        $data = $userStore;

        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['link'=>route('admin.settings.index'),'name' => trans('cruds.settings.title')], ['name' => trans('cruds.userStore.store_detail')]];

        return view('admin.settings.general.index', compact('list', 'data','breadcrumbs'));
    }

    public function getStates($id)
    {
        try{

            $states = State::where('country_id', $id)->pluck('name', 'id');

            return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.STATES_GET_SUCCESSFULLY.code'),
                __('constants.messages.STATES_GET_SUCCESSFULLY.msg'),
                $states
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

    public function create()
    {
        abort_if(Gate::denies('user_store_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $addresses = Address::all()->pluck('address', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.settings.userStores.create', compact('addresses'));
    }

    public function store(Request $request)
    {   
        try{
            abort_if(Gate::denies('user_store_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

            $params = collect($request->all());
            $required = ['store_name'];
            // $this->validateRequiredParams($required,$params->keys()->toArray());
            
            $userId = Config::get('client_id');
            $userStore = UserStore::where('user_id', $userId)->first();

            if(!$userStore){
                $userStore = new UserStore;
                $userStore->user_id = $userId;
            } 

            $industry =  $params['industry'];
            $storeName =  $params['storeName'];
            $contactEmail =  $params['contactEmail'];
            $senderEmail =  $params['senderEmail'];
            $company =  $params['company'];
            $address1 =  $params['address1'];
            $address2 =  $params['address2'];
            $phone =  $params['phone'];
            $city =  $params['city'];
            $country =  $params['country'];
            $state =  $params['state'];
            $pincode =  $params['pincode'];
            $timezone =  $params['timezone'];
            $unitSyatem =  $params['unitSyatem'];
            $unitWeight =  $params['unitWeight'];
            $prefix =  $params['prefix'];
            $suffix =  $params['suffix'];
            $currency =  $params['currency'];
            $sysmbol =  $params['sysmbol'];

            $userStore->timezone_id = $timezone;
            $userStore->user_store_industry_id = $industry;
            $userStore->store_name = $storeName;
            $userStore->store_contact_email = $contactEmail;
            $userStore->sender_email = $senderEmail;
            $userStore->company =  $company;
            $userStore->unit_system = $unitSyatem;
            $userStore->unit_weight = $unitWeight;
            $userStore->prefix = $prefix;
            $userStore->suffix = $suffix;
            $userStore->address = $address1;
            $userStore->address_2 = $address2;
            $userStore->mobile = $phone;
            $userStore->city = $city;
            $userStore->state_id = $state;
            $userStore->country_id =  $country;
            $userStore->postal_code = $pincode;
            $userStore->currency_id = $currency;
            $userStore->symbol = $sysmbol;
            $userStore->save();
            
            $address = Address::where('user_id', $userId)->first();
            if(!empty($address)){
                $address = new Address;
                $address->user_id = $userId;
                $address->location_name = $address1;
                $address->address = $address1;
                $address->address_2 = $address2;
                $address->mobile = $phone;
                $address->city_name = $city;
                $address->state_id = $state;
                $address->country_id =  $country;
                $address->postal_code = $pincode;
                $address->is_default = 1;
                $address->save();
            }

            return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.GENERAL_SETTINGS_SAVED_SUCCESSFULLY.code'),
                __('constants.messages.GENERAL_SETTINGS_SAVED_SUCCESSFULLY.msg')
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

    public function edit(UserStore $userStore)
    {
        abort_if(Gate::denies('user_store_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $addresses = Address::all()->pluck('address', 'id')->prepend(trans('global.pleaseSelect'), '');

        $userStore->load('address');

        return view('admin.settings.userStores.edit', compact('addresses', 'userStore'));
    }

    public function update(UpdateUserStoreRequest $request, UserStore $userStore)
    {
        $userStore->update($request->all());

        return redirect()->route('admin.user-stores.index');
    }

    public function show(UserStore $userStore)
    {
        abort_if(Gate::denies('user_store_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $userStore->load('address');

        return view('admin.settings.userStores.show', compact('userStore'));
    }

    public function destroy(UserStore $userStore)
    {
        abort_if(Gate::denies('user_store_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $userStore->delete();

        return back();
    }

    public function massDestroy(MassDestroyUserStoreRequest $request)
    {
        UserStore::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
