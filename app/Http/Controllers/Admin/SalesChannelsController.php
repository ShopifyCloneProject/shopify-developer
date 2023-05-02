<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroySalesChannelRequest;
use App\Http\Requests\StoreSalesChannelRequest;
use App\Http\Requests\UpdateSalesChannelRequest;
use App\Models\Product;
use App\Models\SalesChannel;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class SalesChannelsController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('sales_channel_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = SalesChannel::with(['product'])->select(sprintf('%s.*', (new SalesChannel())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'sales_channel_show';
                $editGate = 'sales_channel_edit';
                $deleteGate = 'sales_channel_delete';
                $crudRoutePart = 'sales-channels';

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
            $table->addColumn('product_title', function ($row) {
                return $row->product ? $row->product->title : '';
            });

            $table->editColumn('status', function ($row) {
                return SalesChannel::STATUS_RADIO[$row->status];
            });

            $table->rawColumns(['actions', 'placeholder', 'product']);

            return $table->make(true);
        }

        $products = Product::get();
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['name' => trans('cruds.salesChannel.title_singular')." ".trans('global.listing') ]];
        return view('admin.salesChannels.index', compact('products','breadcrumbs'));
    }

    public function create()
    {
        abort_if(Gate::denies('sales_channel_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $products = Product::all()->pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['link'=>route('admin.sales-channels.index'),'name' => trans('cruds.salesChannel.title') ],['name' => trans('locale.Add')." ".trans('cruds.salesChannel.title_singular') ]];
        return view('admin.salesChannels.create', compact('products','breadcrumbs'));
    }

    public function store(StoreSalesChannelRequest $request)
    {
        try {
            $salesChannel = SalesChannel::create($request->all());
            return redirect()->route('admin.sales-channels.index')->with('success','Saleschannel added successfully!!');
        } catch (\Exception $e) {
            return redirect()->route('admin.sales-channels.index')->with('error','Something went wrong');
        }
    }

    public function edit(SalesChannel $salesChannel)
    {
        abort_if(Gate::denies('sales_channel_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $products = Product::all()->pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');

        $salesChannel->load('product');
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['link'=>route('admin.sales-channels.index'),'name' => trans('cruds.salesChannel.title') ],['name' => trans('global.edit')." ".trans('cruds.salesChannel.title_singular') ]];

        return view('admin.salesChannels.edit', compact('products', 'salesChannel','breadcrumbs'));
    }

    public function update(UpdateSalesChannelRequest $request, SalesChannel $salesChannel)
    {
        try {
            $salesChannel->update($request->all());  
            return redirect()->route('admin.sales-channels.index')->with('success','Saleschannel updated successfully!!');
        } catch (\Exception $e) {
            return redirect()->route('admin.sales-channels.index')->with('error','Something went wrong');
        }
    }

    public function show(SalesChannel $salesChannel)
    {
        abort_if(Gate::denies('sales_channel_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $salesChannel->load('product');
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['link'=>route('admin.sales-channels.index'),'name' => trans('cruds.salesChannel.title') ],['name' => trans('global.show')." ".trans('cruds.salesChannel.title_singular') ]];

        return view('admin.salesChannels.show', compact('salesChannel','breadcrumbs'));
    }

    public function destroy(SalesChannel $salesChannel,$id)
    {
        try {
              abort_if(Gate::denies('sales_channel_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
              SalesChannel::where('id',$id)->delete();   
        } catch (\Exception $e) {
              return $this->errorResponse(
                __('constants.ERROR_STATUS'),
                __('constants.errors.SOMETHING_WRONG.code'),
                __('constants.errors.SOMETHING_WRONG.msg'),
                $e->getMessage()
            );
        }
        return $this->successResponse(
            __('constants.SUCCESS_STATUS'),
            __('constants.messages.SALES_CHANNEL_DELETE_SUCCESSFULLY.code'),
            __('constants.messages.SALES_CHANNEL_DELETE_SUCCESSFULLY.msg'),
        );
    }

    public function massDestroy(MassDestroySalesChannelRequest $request)
    {
        try {
              SalesChannel::whereIn('id', request('ids'))->delete(); 
        } catch (\Exception $e) {
              return $this->errorResponse(
                __('constants.ERROR_STATUS'),
                __('constants.errors.SOMETHING_WRONG.code'),
                __('constants.errors.SOMETHING_WRONG.msg'),
                $e->getMessage()
            );
        }
        return $this->successResponse(
            __('constants.SUCCESS_STATUS'),
            __('constants.messages.SALES_CHANNEL_DELETE_SUCCESSFULLY.code'),
            __('constants.messages.SALES_CHANNEL_DELETE_SUCCESSFULLY.msg'),
        );
        // return response(null, Response::HTTP_NO_CONTENT);
    }
}
