<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyCountryRequest;
use App\Http\Requests\StoreCountryRequest;
use App\Http\Requests\UpdateCountryRequest;
use App\Models\Country;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class CountriesController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('country_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Country::query()->select(sprintf('%s.*', (new Country())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'country_show';
                $editGate = 'country_edit';
                $deleteGate = 'country_delete';
                $crudRoutePart = 'countries';

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
                return $row->name ? $row->name : '';
            });
            $table->editColumn('short_code', function ($row) {
                return $row->short_code ? $row->short_code : '';
            });
            $table->editColumn('phone_code', function ($row) {
                return $row->phone_code ? $row->phone_code : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['name' => trans('cruds.country.title_singular')." ".trans('global.listing') ]];
        return view('admin.countries.index',compact('breadcrumbs'));
    }

    public function create()
    {
        abort_if(Gate::denies('country_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('admin.countries.create');
    }

    public function store(StoreCountryRequest $request)
    {
        $country = Country::create($request->all());

        return $this->successResponse(
            __('constants.SUCCESS_STATUS'),
            __('constants.messages.COUNTRY_ADDED_SUCCESSFULLY.code'),
            __('constants.messages.COUNTRY_ADDED_SUCCESSFULLY.msg'),
            $country
        );
    }

    public function edit(Country $country)
    {
        abort_if(Gate::denies('country_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('admin.countries.edit', compact('country'));
    }

    public function update(UpdateCountryRequest $request, Country $country)
    {
        $country->update($request->all());

        return $this->successResponse(
            __('constants.SUCCESS_STATUS'),
            __('constants.messages.COUNTRY_UPDATE_SUCCESSFULLY.code'),
            __('constants.messages.COUNTRY_UPDATE_SUCCESSFULLY.msg'),
        );
    }

    public function show(Country $country)
    {
        abort_if(Gate::denies('country_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.countries.show', compact('country'));
    }

    public function destroy(Country $country)
    {
        abort_if(Gate::denies('country_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $country->delete();

        return $this->successResponse(
            __('constants.SUCCESS_STATUS'),
            __('constants.messages.COUNTRY_DELETE_SUCCESSFULLY.code'),
            __('constants.messages.COUNTRY_DELETE_SUCCESSFULLY.msg'),
        );
    }

    public function massDestroy(MassDestroyCountryRequest $request)
    {
        Country::whereIn('id', request('ids'))->delete();

        return $this->successResponse(
            __('constants.SUCCESS_STATUS'),
            __('constants.messages.COUNTRY_DELETE_SUCCESSFULLY.code'),
            __('constants.messages.COUNTRY_DELETE_SUCCESSFULLY.msg'),
        );
    }
}
