<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyAddressRequest;
use App\Http\Requests\StoreAddressRequest;
use App\Http\Requests\UpdateAddressRequest;
use App\Services\ShiprocketService;
use App\Services\IthinklogisticsService;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Address;
use App\Models\Country;
use App\Models\State;
use App\Models\User;
use Gate;
use Auth;
use Config;

class AddressesController extends Controller
{
    protected $shipService;
    protected $ithinkService;

    public function __construct()
    {
        $this->shipService = new ShiprocketService;
        $this->ithinkService = new IthinklogisticsService;
    }


    public function index(Request $request)
    {   
        /*$data = $this->shipService->handleShipping('setAWB',['shipment_id' => '284693889', 'courier_id' => 58]);*/
        abort_if(Gate::denies('address_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $objAddress = Address::where('store_status', 1)->get();
       
        $addresses = [];
        foreach ($objAddress as $key => $address) {
            $addresses[$key]['id'] =  $address->id;
            $addresses[$key]['locationName'] =  $address->location_name;
            $addresses[$key]['address1'] =  $address->address;
            $addresses[$key]['address2'] =  $address->address_2;
            $addresses[$key]['phone'] =  $address->mobile;
            $addresses[$key]['email'] =  $address->email;
            $addresses[$key]['city'] =  $address->city_name;
            $addresses[$key]['country'] =  $address->country_id;
            $addresses[$key]['shortCode'] =  ($address->country) ? $address->country->short_code : '';
            $addresses[$key]['state'] =  $address->state_id;
            $addresses[$key]['stateName'] = ($address->state) ? $address->state->name : '';
            $addresses[$key]['pincode'] =  $address->postal_code;
            $addresses[$key]['is_default'] =  $address->is_default;
            $addresses[$key]['phoneCode'] =  ($address->country) ? $address->country->phone_code : '';
        }

        $countries = Country::get();
        $list = [
            'countries' =>  $countries,
        ];

        $data['addresses'] = $addresses;
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')],['link'=>route('admin.settings.index'),'name' => trans('cruds.settings.title')], ['name' => trans('cruds.settings.locations')]];
        return view('admin.settings.location.index', compact('list', 'data','breadcrumbs'));
    }

    public function create()
    {
        abort_if(Gate::denies('address_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $list = $data = [];
        $countries = Country::get();
        $type = "add";
        $list = [
            'countries' =>  $countries,
        ];
        $defaultcountry = Config::get('globalSettings')['DEFAULT_COUNTRY'];
        $phoneCode = Country::where('id',$defaultcountry)->first()->phone_code;

        $address['id'] =  null;
        $address['locationName'] =  null;
        $address['address1'] =  null;
        $address['address2'] =  null;
        $address['phone'] =  null;
        $address['city'] =  null;
        $address['country'] =  $defaultcountry;
        $address['state'] =  null;
        $address['pincode'] =  null;
        $address['phoneCode'] =   $phoneCode;

        $data['address'] = $address;

        return view('admin.settings.location.create', compact('list','data','type'));
    }

    public function addEditLocation(Request $request)
    {
        try
        {
            //add or edit update
            $params = collect($request->all());
            $userId = Auth::user()->id;
            $address = new Address;
            if(isset($params['id'])){
                //check if location id is set then update recored
                $address = Address::where('id', $params['id'])->first();
            }

            if(!Address::where('user_id', $userId)->where('is_default', 1)->first()){
                $address->is_default = 1;
            }

            $address->store_status = 1;
            $address->user_id = $userId;
            $address->location_name = $params['locationName'];
            $address->address = $params['address1'];
            $address->address_2 = $params['address2'];
            $address->email = $params['email'];
            $address->mobile = $params['phone'];
            $address->city_name = $params['city'];
            $address->state_id = $params['state'];
            $address->country_id = $params['country'];
            $address->postal_code = $params['pincode'];
            $address->phone_code = $params['phoneCode'];
            $address->save();

            $this->shipService->handleShipping('setPickupLocation',$address);
            $this->ithinkService->handleShipping('setPickupLocation',$address);

            if(isset($params['id'])){
                 $url = route("admin.settings.locations");
                return $this->successResponse(
                    __('constants.SUCCESS_STATUS'),
                    __('constants.messages.DATA_UPDATE_SUCCESSFULLY.code'),
                    __('constants.messages.DATA_UPDATE_SUCCESSFULLY.msg'),
                    ['url'=>$url]
                );
            } else {
                $url = route("admin.settings.locations");
                 return $this->successResponse(
                    __('constants.SUCCESS_STATUS'),
                    __('constants.messages.DATA_SAVED_SUCCESSFULLY.code'),
                    __('constants.messages.DATA_SAVED_SUCCESSFULLY.msg'),
                    ['url'=>$url]
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

    public function edit($id)
    {
       
        abort_if(Gate::denies('address_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $type = "edit";
        $address = Address::where('id', $id)->first();
        $defaultcountry = Config::get('globalSettings')['DEFAULT_COUNTRY'];
        $phoneCode = Country::where('id',$address->country_id)->first()->phone_code;
        if( $address ) {
            $address['id'] =  $address->id;
            $address['locationName'] =  $address->location_name;
            $address['address1'] =  $address->address;
            $address['address2'] =  $address->address_2;
            $address['phone'] =  $address->mobile;
            $address['city'] =  $address->city_name;
            $address['country'] =  $address->country_id;
            $address['state'] =  $address->state_id;
            $address['pincode'] =  $address->postal_code;
            $address['phoneCode'] =  ($address->country) ? $phoneCode : '';
        }

        $countries = Country::get();
        $list = [
            'countries' =>  $countries,
        ];

        $data['address'] = $address;

        return view('admin.settings.location.edit', compact('list', 'data','type'));
    }

    public function destroy(Address $address)
    {
        abort_if(Gate::denies('address_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $address->delete();

        return back();
    }

    public function massDestroy(MassDestroyAddressRequest $request)
    {
        Address::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}