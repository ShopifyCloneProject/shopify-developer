<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyCurrencyRequest;
use App\Http\Requests\StoreCurrencyRequest;
use App\Http\Requests\UpdateCurrencyRequest;
use App\Models\Currency;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class CurrencyController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('currency_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Currency::query()->select(sprintf('%s.*', (new Currency())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'currency_show';
                $editGate = 'currency_edit';
                $deleteGate = 'currency_delete';
                $crudRoutePart = 'currencies';

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
            $table->editColumn('currency', function ($row) {
                return $row->currency ? $row->currency : '';
            });
            $table->editColumn('status', function ($row) {
                return ( $row->status || $row->status == 0 ) ? Currency::STATUS_RADIO[$row->status] : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['name' => trans('cruds.currency.title_singular')." ".trans('global.listing') ]];
        return view('admin.currencies.index',compact('breadcrumbs'));
    }

    public function create()
    {
        abort_if(Gate::denies('currency_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('admin.currencies.create');
    }

    public function store(StoreCurrencyRequest $request)
    {
        $currency = Currency::create($request->all());

        return $this->successResponse(
            __('constants.SUCCESS_STATUS'),
            __('constants.messages.CURRENCY_ADDED_SUCCESSFULLY.code'),
            __('constants.messages.CURRENCY_ADDED_SUCCESSFULLY.msg'),
            $currency
        );
    }

    public function edit(Currency $currency)
    {
        abort_if(Gate::denies('currency_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.currencies.edit', compact('currency'));
    }

    public function update(UpdateCurrencyRequest $request, Currency $currency)
    {
        $currency->update($request->all());

        return $this->successResponse(
            __('constants.SUCCESS_STATUS'),
            __('constants.messages.CURRENCY_UPDATE_SUCCESSFULLY.code'),
            __('constants.messages.CURRENCY_UPDATE_SUCCESSFULLY.msg'),
        );
    }

    public function show(Currency $currency)
    {

        return view('admin.currencies.show', compact('currency'));
    }

    public function destroy(Currency $currency)
    {
        abort_if(Gate::denies('currency_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $currency->delete();

        return $this->successResponse(
            __('constants.SUCCESS_STATUS'),
            __('constants.messages.CURRENCY_DELETE_SUCCESSFULLY.code'),
            __('constants.messages.CURRENCY_DELETE_SUCCESSFULLY.msg'),
        );
    }

    public function massDestroy(MassDestroyCurrencyRequest $request)
    {
        Currency::whereIn('id', request('ids'))->delete();

        return $this->successResponse(
            __('constants.SUCCESS_STATUS'),
            __('constants.messages.CURRENCY_DELETE_SUCCESSFULLY.code'),
            __('constants.messages.CURRENCY_DELETE_SUCCESSFULLY.msg'),
        );
    }
}
