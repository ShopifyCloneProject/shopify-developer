<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyOrderFinancialStatusRequest;
use App\Http\Requests\StoreOrderFinancialStatusRequest;
use App\Http\Requests\UpdateOrderFinancialStatusRequest;
use App\Models\OrderFinancialStatus;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use Gate;

class OrderFinancialStatusController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('order_financial_status_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        if ($request->ajax()) {
            $query = OrderFinancialStatus::query()->select(sprintf('%s.*', (new OrderFinancialStatus())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'order-financial-status';
                $editGate = 'order-financial-status';
                $deleteGate = 'order-financial-status';
                $crudRoutePart = 'order-financial-statuses';

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
            $table->addColumn('title', function ($row) {
                return $row->title ? $row->title : '';
            });
            $table->editColumn('status', function ($row) {
                return OrderFinancialStatus::STATUS_RADIO[$row->status];
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }
        $orderFinancialStatuses = OrderFinancialStatus::all();
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['name' => trans('cruds.orderFinancialStatus.title')." ".trans('global.listing') ]];
        return view('admin.orderFinancialStatuses.index', compact('orderFinancialStatuses','breadcrumbs'));
    }

    public function create()
    {
        abort_if(Gate::denies('order_financial_status_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['link'=>route('admin.order-financial-statuses.index'),'name' => trans('cruds.orderFinancialStatus.title') ],['name' => trans('locale.Add')." ".trans('cruds.orderFinancialStatus.title_singular') ]];
        return view('admin.orderFinancialStatuses.create',compact('breadcrumbs'));
    }

    public function store(StoreOrderFinancialStatusRequest $request)
    {
        try {
            $orderFinancialStatus = OrderFinancialStatus::create($request->all());
            return redirect()->route('admin.order-financial-statuses.index')->with('success','Orderfinancial status added successfully !');
        } catch (Exception $e) {
            return redirect()->route('admin.order-financial-statuses.index')->with('error','Something went wrong !');
        }
    }

    public function edit(OrderFinancialStatus $orderFinancialStatus)
    {
        abort_if(Gate::denies('order_financial_status_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['link'=>route('admin.order-financial-statuses.index'),'name' => trans('cruds.orderFinancialStatus.title') ],['name' => trans('global.edit')." ".trans('cruds.orderFinancialStatus.title_singular') ]];
        return view('admin.orderFinancialStatuses.edit', compact('orderFinancialStatus','breadcrumbs'));
    }

    public function update(UpdateOrderFinancialStatusRequest $request, OrderFinancialStatus $orderFinancialStatus)
    {
        try {
            $orderFinancialStatus->update($request->all());
            return redirect()->route('admin.order-financial-statuses.index')->with('success','Orderfinancial status updated successfully !');
        } catch (Exception $e) {
            return redirect()->route('admin.order-financial-statuses.index')->with('error','Something went wrong !');
        }
    }

    public function show($id)
    {
        abort_if(Gate::denies('order_financial_status_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $data = [];
        $orderstatus = OrderFinancialStatus::where('id',$id)->first();
        $orders = Order::where('financial_status_id',$orderstatus->id)->get();
        foreach($orders as $order){
            $data['orderProducts'] = OrderProduct::where('order_id',$order->id)->get();
            foreach ($data['orderProducts'] as $orderProduct) {
                $data['productTitle'] = Product::where('id',$orderProduct->product_id)->first()->title;
            }
        }
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['link'=>route('admin.order-financial-statuses.index'),'name' => trans('cruds.orderFinancialStatus.title') ],['name' => trans('global.show')." ".trans('cruds.orderFinancialStatus.title_singular') ]];

        return view('admin.orderFinancialStatuses.show', compact('breadcrumbs','orderstatus'));
    }

    public function destroy(OrderFinancialStatus $orderFinancialStatus,$id)
    {
        try {
              abort_if(Gate::denies('order_financial_status_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
              OrderFinancialStatus::where('id',$id)->delete();   
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
            __('constants.messages.ORDER_FINANCIAL_STATUS_DELETE_SUCCESSFULLY.code'),
            __('constants.messages.ORDER_FINANCIAL_STATUS_DELETE_SUCCESSFULLY.msg'),
        );
    }

    public function massDestroy(MassDestroyOrderFinancialStatusRequest $request)
    {
        try {
              OrderFinancialStatus::whereIn('id', request('ids'))->delete(); 
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
            __('constants.messages.ORDER_FINANCIAL_STATUS_DELETE_SUCCESSFULLY.code'),
            __('constants.messages.ORDER_FINANCIAL_STATUS_DELETE_SUCCESSFULLY.msg'),
        );
    }
}
