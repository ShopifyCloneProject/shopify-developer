<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Address;
use App\Models\Currency;
use App\Models\Country;
use App\Models\Variant;
use App\Models\Product;
use App\Models\VariantOption;
use App\Models\ProductVariantOption;
use App\Models\ShippingRate;
use App\Models\ShippingDetail;
use Gate;
use Auth;
use DB;

class ShippingSettingsController extends Controller
{
	public function index()
    {
        abort_if(Gate::denies('shipping_access_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $data['addresses'] = $this->getAddresses();
        $data['addRateUrl'] = Route('admin.settings.shipping.add-rates');
        $data['shippingRates'] = ShippingRate::get();
        $data['roundValue'] = ShippingDetail::first()->round_value;
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['link'=>route('admin.settings.index'),'name' => trans('cruds.settings.title')], ['name' => trans('cruds.shippingSettings.shipping_and_delivery')]];

        return view('admin.settings.shipping.index', compact('data','breadcrumbs'));
	}

    public function localDelivery($id)
    {
        $data['address'] =  $this->getAddress($id);
        $currencies = Currency::get();
        $data['currencies'] = $currencies;

        return view('admin.settings.shipping.local_delivery', compact('data'));
    }

    public function localPickup($id)
    {
        $data['address'] = $this->getAddress($id);
        $data['currencies'] = $currencies;

        return view('admin.settings.shipping.local_pickup', compact('data'));
    }

    public function createRates()
    {
        //products
        $products = Product::get();
        $objProducts = [];
        foreach($products as $key => $value){
            $objProducts[$key]['id'] = $value->id;
            $objProducts[$key]['text'] = $value->title;
            $objProducts[$key]['expanded'] = true;

            $productVariants = ProductVariantOption::where('product_id', $value->id)->get();
            foreach($productVariants as $key1 => $value1){
                $objProducts[$key]['items'][$key1]['id'] = $value->id.'_'.$value1->id;
                $objProducts[$key]['items'][$key1]['text'] =  $value1->variant_name;
                $objProducts[$key]['items'][$key1]['expanded'] =  false;
            }
        }
        $data['products'] = $objProducts;

        //address
        $data['addresses'] = $this->getAddresses();

        //country
        $countries = Country::where('name', 'India')->get();
        $coutryRegions = [];
        // $coutryRegions[0]['id'] = 0;
        // $coutryRegions[0]['text'] = 'Rest of the world';
        // $coutryRegions[0]['expanded'] = false;

        foreach($countries as $key => $value){
            $coutryRegions[$key]['id'] = $value->id;
            $coutryRegions[$key]['text'] = $value->name;
            $coutryRegions[$key]['expanded'] = true;
            foreach($value->states as $key1 => $value1){
                $coutryRegions[$key]['items'][$key1]['id'] = $value->id.'_'.$value1->id;
                $coutryRegions[$key]['items'][$key1]['text'] =  $value1->name;
                $coutryRegions[$key]['items'][$key1]['expanded'] =  false;
            }
        }
        $data['countries'] = $coutryRegions;
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['link'=>route('admin.settings.shipping'),'name' => trans('cruds.shippingSettings.shipping_and_delivery')], ['name' => trans('cruds.shippingSettings.custom_shipping_rates')]];

        return view('admin.settings.shipping.create_rates', compact('data','breadcrumbs'));
    }


    public function manageRates($id)
    {
        $data['addresses'] = $this->getAddresses();
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['link'=>route('admin.settings.shipping'),'name' => trans('cruds.shippingSettings.shipping_and_delivery')], ['name' => trans('cruds.shippingSettings.manage_rates')]];

        return view('admin.settings.shipping.manage_rates', compact('data','breadcrumbs'));
    }

    public function getAddresses(){
        $user = Auth::user();

        $addresses = [];
        foreach ($user->addresses as $key => $address) {
            $addresses[$key]['id'] =  $address->id;
            $addresses[$key]['locationName'] =  $address->location_name;
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

        return  $addresses;
    }

    public function getAddress($id){
        $address = Address::where('id', $id)->first();

        $addresses = [];
        if( $address ) {
            $addresses['id'] =  $address->id;
            $addresses['locationName'] =  $address->location_name;
            $addresses['address1'] =  $address->address;
            $addresses['address2'] =  $address->address_2;
            $addresses['phone'] =  $address->mobile;
            $addresses['city'] =  $address->city_name;
            $addresses['country'] =  $address->country_id;
            $addresses['state'] =  $address->state_id;
            $addresses['pincode'] =  $address->postal_code;
            $addresses['phoneCode'] =  ($address->country) ? $address->country->phone_code : '';
        }

        return  $addresses;
    }

     public function addRates()
    {
        abort_if(Gate::denies('add_rates_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $data = [];
        $data['shipping_rates'] = [];
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['link'=>route('admin.settings.shipping'),'name' => trans('cruds.shippingSettings.shipping_and_delivery')], ['name' => trans('cruds.shippingSettings.add_rates')]];
        return view('admin.settings.shipping.addeditrates', compact('data','breadcrumbs'));
    }

     public function editRates($id)
    {
        abort_if(Gate::denies('add_rates_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $data = [];
        $data['shipping_rates'] = ShippingRate::where('id',$id)->first();
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['link'=>route('admin.settings.shipping'),'name' => trans('cruds.shippingSettings.shipping_and_delivery')], ['name' => trans('cruds.shippingSettings.edit_rate')]];

        return view('admin.settings.shipping.addeditrates', compact('data','breadcrumbs'));
    }

    public function addEditRate(Request $request){
        try{
            $params = collect($request->all());
            if(isset($params['id'])){
                $objShippingRate = ShippingRate::where('id',$params['id'])->first();
            }
            if(empty($objShippingRate)){
                $objShippingRate = new ShippingRate;
            }
                $objShippingRate->rate_status = $params['rate_status'];
                $objShippingRate->name = $params['name'];
                $objShippingRate->price = $params['price'];
                $objShippingRate->conditions = $params['conditions'];
                $objShippingRate->weight_or_price = $params['weight_or_price'];
                $objShippingRate->min = $params['min'];
                $objShippingRate->max = $params['max'];
                $objShippingRate->save();
                $url = Route("admin.settings.shipping");

                if(isset($params['id'])){
                return $this->successResponse(
                    __('constants.SUCCESS_STATUS'),
                    __('constants.messages.RATE_UPDATED_SUCCESSFULLY.code'),
                    __('constants.messages.RATE_UPDATED_SUCCESSFULLY.msg'),
                    ['url'=>$url]
                );
            } else {
                 return $this->successResponse(
                    __('constants.SUCCESS_STATUS'),
                    __('constants.messages.RATE_SAVED_SUCCESSFULLY.code'),
                    __('constants.messages.RATE_SAVED_SUCCESSFULLY.msg'),
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

    public function deleteRates($rateId){
        try{
            ShippingRate::whereId($rateId)->delete();
            $url = Route("admin.settings.shipping");
            return $this->successResponse(
                    __('constants.SUCCESS_STATUS'),
                    __('constants.messages.RATE_DELETED_SUCCESSFULLY.code'),
                    __('constants.messages.RATE_DELETED_SUCCESSFULLY.msg'),
                    ['url'=>$url]
                );
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

    public function shippingCharge(Request $request){
        try{
            $params = collect($request->all());
            shippingDetail::query()->update(['round_value' => $params['isChecked']]);
            return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.SHIPPING_ROUND_CHARGE_SAVED_SUCCESSFULLY.code'),
                __('constants.messages.SHIPPING_ROUND_CHARGE_SAVED_SUCCESSFULLY.msg'),
            );
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

    
}
