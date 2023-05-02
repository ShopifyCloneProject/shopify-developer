<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Country;
use App\Models\State;
use App\Models\CountryTax;
use App\Models\StateTax;
use Gate;
use Auth;
use Config;
use DB;
use Str;

class TaxesSettingsController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('tax_access_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $data = $list = [];
        $list['btn_select_taxes'] = !Gate::denies('btn_select_taxes');
        $list['countries'] = Country::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $data['countryTax'] = CountryTax::with('country')->where('default',1)->first();
        $data['stateTaxUrl'] = Route('admin.settings.statetaxes', ['short_code'=>$data['countryTax']->country->short_code]);
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['link'=>route('admin.settings.index'),'name' => trans('cruds.settings.title')], ['name' => trans('cruds.settings.taxes') ]];
            return view('admin.settings.taxes.index', compact('data','list','breadcrumbs'));
    }

    public function saveSelectedCountry(Request $request){
        try
        {
            $params = collect($request->all());
            $objCountryDefaultTax = CountryTax::where('default',1)->first();
            if($objCountryDefaultTax->country_id != $params['country_id']){
                $objCountryDefaultTax->default = 0;
                $objCountryDefaultTax->save();
            }
            $objCountryTax = CountryTax::with('country')->where('country_id',$params['country_id'])->first();
            $objCountryTax->country_id = $params['country_id'];
            $objCountryTax->default = 1;
            $objCountryTax->include_tax = $params['include_tax'];
            $objCountryTax->exclude_tax = $params['exclude_tax'];
            $objCountryTax->charge_on_shipping = $params['charge_on_shipping'];
            $objCountryTax->charge_vat_digital_goods = $params['charge_vat_digital_goods'];
            $objCountryTax->round_value = $params['round_value'];
            $objCountryTax->save();

            $url = Route('admin.settings.taxes');
                return $this->successResponse(
                        __('constants.SUCCESS_STATUS'),
                        __('constants.messages.COUNTRY_TAX_SAVED_SUCCESSFULLY.code'),
                        __('constants.messages.COUNTRY_TAX_SAVED_SUCCESSFULLY.msg'),
                        ['url' => $url]
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

    public function showStateTax($short_code)
    {
        abort_if(Gate::denies('state_tax_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $data = $list = [];

        $data['objCountry'] = Country::with('country_tax')->select('id','name')->where('short_code',$short_code)->first();
        $list['objStates'] = State::with('state_tax')->select('id','name')->where('country_id',$data['objCountry']->id)->get();
        $taxAdditional = StateTax::TAX_ADDITIONAL;
        $list['taxAdditional'] = [
            Str::replace('12', $data['objCountry']->country_tax->tax_percentage, $taxAdditional[0]),
            Str::replace('12', $data['objCountry']->country_tax->tax_percentage, $taxAdditional[1]),
            Str::replace('12', $data['objCountry']->country_tax->tax_percentage, $taxAdditional[2]),
        ];

        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['link'=>route('admin.settings.index'),'name' => trans('cruds.settings.title')], ['link'=>route('admin.settings.taxes'),'name' => trans('cruds.settings.taxes') ],['name' => trans('global.show') .' '.trans('cruds.taxes.state_tax') ]];
        return view('admin.settings.taxes.statetaxes', compact('data','list','breadcrumbs'));
    }

    public function saveStateTax(Request $request)
    {
        try
        {
            $params = collect($request->all());
            $objCountryTax = CountryTax::where('country_id',$params['country_id'])->first();
            if(!empty($objCountryTax)){
                $objCountryTax->tax_percentage = $params['tax_percentage'];
                $objCountryTax->save();
            }

            foreach($params['newStateTax'] as $stateTax){
                $objStateTaxes = StateTax::where('id',$stateTax['id'])->first();
                $objStateTaxes->country_taxes_id = $stateTax['state_tax']['country_taxes_id'];
                $objStateTaxes->state_id = $stateTax['id'];
                $objStateTaxes->state_tax_percentage = $stateTax['state_tax']['state_tax_percentage'];
                $objStateTaxes->text = $stateTax['state_tax']['text'];
                $objStateTaxes->tax_additional = $stateTax['state_tax']['tax_additional'];
                $objStateTaxes->save();
            }
            return $this->successResponse(
                    __('constants.SUCCESS_STATUS'),
                    __('constants.messages.STATE_TAX_SAVED_SUCCESSFULLY.code'),
                    __('constants.messages.STATE_TAX_SAVED_SUCCESSFULLY.msg'),
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