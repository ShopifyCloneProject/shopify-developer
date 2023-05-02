<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyOrderRequest;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Address;
use App\Models\Currency;
use App\Models\Order;
use App\Models\OrderFinancialStatus;
use App\Models\PaymentMethod;
use App\Models\ShippingMethod;
use App\Models\User;
use App\Models\Country;
use App\Models\State;
use App\Models\OrderProduct;
use App\Models\OrderLocation;
use App\Models\Product;
use App\Models\Refund;
use App\Models\Shipping;
use App\Models\ShippingProduct;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use Gate;
use Exception;
use Config;

class OrdersController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('order_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        if ($request->ajax()) {
            $query = Order::with(['financial_status', 'user',  'payment_method'])->select(sprintf('%s.*', (new Order())->table));

            $table = Datatables::of($query);

            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'order_show';
                $editGate = 'order_edit';
                $deleteGate = 'order_delete';
                $crudRoutePart = 'orders';

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

            $table->editColumn('order_nr', function ($row) {
                return $row->order_nr ? $row->order_nr : '';
            });

            $table->editColumn('created_at', function ($row) {
                return $row->created_at ? date('d-m-y h:i a', strtotime($row->created_at)) : '';
            });

            $table->editColumn('paid_at', function ($row) {
                    return $row->paid_at ? date('d-m-y, h:i a', strtotime($row->paid_at)) : '';
            });

            $table->addColumn('customer', function ($row) {
                return $row->user ? $row->user->email : '';
            });

            $table->editColumn('total', function ($row) {
                return $row->total ? $row->total : '';
            });


            $table->editColumn('financial_status', function ($row) {
                return Order::PAYMENT_STATUS[$row->financial_status];
            });

            $table->editColumn('fulfillment_status', function ($row) {
                return $row->fulfillment_status ? Order::FULFILLMENT_STATUS_SELECT[$row->fulfillment_status] : '';
            });

            $table->editColumn('status', function ($row) {
                return $row->status ? Order::STATUS[$row->status] : '';
            });

            $table->editColumn('admin_approve', function ($row) {
                return $row->admin_approve ? $row->admin_approve : '';
            });

            $table->addColumn('method', function ($row) {
                return  $row->payment_method->title;
            });

               
            $table->rawColumns(['actions', 'placeholder', 'customer','method']);

            return $table->make(true);
            }

            $users = User::get();
            $paymentMethods = PaymentMethod::get();
            $fulfillmentstatus = Order::FULFILLMENT_STATUS_SELECT;
            $status = Order::STATUS;
            $paymentStatus = Order::PAYMENT_STATUS;
            $orderProduct = OrderProduct::get();
            $shippingMethod = ShippingMethod::get();
            $financial_status = OrderFinancialStatus::get();
            $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['name' => trans('cruds.order.title') .' '.trans('global.listing') ]];

            return view('admin.orders.index', compact('shippingMethod',  'financial_status','paymentStatus','paymentMethods','breadcrumbs'));
        }

    public function create()
    {
        abort_if(Gate::denies('order_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $list = $data = [];
        $type = 'Add';

        $list['financialStatuses'] = OrderFinancialStatus::all()->pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '0');

        $list['currencies'] = Currency::all()->pluck('currency', 'id')->prepend(trans('global.pleaseSelect'), '0');
        $list['shippingMethods'] = ShippingMethod::all()->pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '0');

        $list['users'] = User::all()->pluck('email', 'id')->prepend(trans('global.pleaseSelect'), '0');

        $list['billingAddresses'] = OrderLocation::all()->pluck('address', 'id')->prepend(trans('global.pleaseSelect'), '0');

        $list['shippingAddresses'] = $list['billingAddresses'];

        $list['paymentMethods'] = PaymentMethod::all()->pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '0');
        $list['fulfillmentstatus'] = Order::FULFILLMENT_STATUS_SELECT;
        $list['status'] = Order::STATUS;
        $list['paymentstatus'] = Order::PAYMENT_STATUS;

        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => 'Home'], ['link'=>route('admin.orders.index'),'name' => trans('cruds.order.title') .' '.trans('global.listing') ], ['name' => trans('global.add') .' '.trans('cruds.order.title_singular') ]];

        return view('admin.orders.createupdate', compact('list','type','breadcrumbs','data'));
    }

    public function store(StoreOrderRequest $request)
    {
        try 
        {
            $orders = Order::create($request->all());
            $order_id = $orders->id;
            $url = route('admin.orders.edit' , ['order' => $order_id]);
            return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.ORDER_ADDED_SUCCESSFULLY.code'),
                __('constants.messages.ORDER_ADDED_SUCCESSFULLY.msg'),
                    ['url'=>$url]
            );
        } 
        catch (Exception $e) 
        {
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
        abort_if(Gate::denies('order_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $type = 'Edit';
        $billingAddress = $shippingAddress = [];

        $order = Order::with([
            'order_products' => function($q){
                $q->select('id', 'title', 'src', 'slug', 'quantity', 'product_variant_options_id', 'product_id', 'price', 'order_id');
            },
        ])->where('id',$id)->first();

        $data['order'] = $order;
        $objParentOrder = Order::whereId($id)->first()->parent_order_id;
        if($objParentOrder != null){
            $order = Order::whereId($objParentOrder)->first();
        }
        $list['financialStatuses'] = OrderFinancialStatus::all()->pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '0');
        $list['fulfillmentstatus'] = Order::FULFILLMENT_STATUS_SELECT;
        $list['status'] = Order::STATUS;
        $list['currencies'] = Currency::all()->pluck('currency', 'id')->prepend(trans('global.pleaseSelect'), '0');
        $list['shippingMethods'] = ShippingMethod::all()->pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '0');
        $list['users'] = User::all()->pluck('email', 'id')->prepend(trans('global.pleaseSelect'), '0');
        $data['billingAddress'] = OrderLocation::where(['order_id' => $order->id, 'status' => 1])->first();

        $data['shippingAddress'] = OrderLocation::where(['order_id' => $order->id, 'status' => 0])->first();
        $list['paymentMethods'] = PaymentMethod::all()->pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '0');
        $list['paymentstatus'] = Order::PAYMENT_STATUS;
        $shippingCountryId =  $billingCountryId = Config::get('DEFAULT_COUNTRY');
        if(!empty($data['shippingAddress'])){
            $shippingCountryId = $data['shippingAddress']->country_id;
        }
        if(!empty($data['billingAddress'])){
            $billingCountryId = $data['billingAddress']->country_id;
        }
        if(empty($data['billingAddress'])){
            $billingAddress['first_name'] = NULL;
            $billingAddress['last_name'] = NULL;
            $billingAddress['email'] = NULL;
            $billingAddress['mobile'] = NULL;
            $billingAddress['address'] = NULL;
            $billingAddress['address_2'] = NULL;
            $billingAddress['country_id'] = config::get('DEFAULT_COUNTRY');
            $billingAddress['state_id'] = NULL;
            $billingAddress['city_name'] = NULL;
            $billingAddress['postal_code'] = NULL;
            $data['billingAddress'] = $billingAddress;
        }

        if(empty($data['shippingAddress'])){
            $shippingAddress['first_name'] = NULL;
            $shippingAddress['last_name'] = NULL;
            $shippingAddress['email'] = NULL;
            $shippingAddress['mobile'] = NULL;
            $shippingAddress['address'] = NULL;
            $shippingAddress['address_2'] = NULL;
            $shippingAddress['country_id'] = config::get('DEFAULT_COUNTRY');
            $shippingAddress['state_id'] = NULL;
            $shippingAddress['city_name'] = NULL;
            $shippingAddress['postal_code'] = NULL;
            $data['shippingAddress'] = $shippingAddress;
        }

        $list['shippingStates'] = State::where(['country_id' => $shippingCountryId])->get()->pluck('name', 'id');
        $list['billingStates'] = State::where(['country_id' => $billingCountryId])->get()->pluck('name', 'id');
        $list['countries'] = Country::get()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '0');
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => 'Home'], ['link'=>route('admin.orders.index'),'name' => trans('cruds.order.title') .' '.trans('global.listing')], ['name' => trans('global.edit') .' '.trans('cruds.order.title_singular') ]];
        return view('admin.orders.createupdate', compact('data', 'list','breadcrumbs','type'));
    }

    public function update(UpdateOrderRequest $request, $id)
    {
        try
        {
            $params = collect($request->all());
            $orderId = $params['id'];

            $objOrder = Order::where('id', $orderId)->first();
            $objOrder->currency_id = $params['currency_id'];
            $objOrder->shipping_method_id = $params['shipping_method_id'];
            $objOrder->gateway = $params['gateway'];
            $objOrder->user_id = $params['user_id'];
            $objOrder->email = $params['email'];
            $objOrder->mobile = $params['mobile'];
            $objOrder->payment_method_id = $params['payment_method_id'];
            $objOrder->paid_at = $params['paid_at'];
            $objOrder->fulfillment_status = $params['fulfillment_status'];
            $objOrder->fulfilled_at = $params['fulfilled_at'];
            $objOrder->accepts_marketing = $params['accepts_marketing'];
            $objOrder->sub_total = $params['sub_total'];
            $objOrder->shipping_cost = $params['shipping_cost'];
            $objOrder->taxes = $params['taxes'];
            $objOrder->total = $params['total'];
            $objOrder->financial_status = $params['financial_status'];
            $objOrder->status = $params['status'];
            $objOrder->discount_code = $params['discount_code'];
            $objOrder->discount_amount = $params['discount_amount'];
            $objOrder->risk_level = $params['risk_level'];
            $objOrder->source = $params['source'];
            $objOrder->tax_1_name = $params['tax_1_name'];
            $objOrder->tax_1_value = $params['tax_1_value'];
            $objOrder->tax_2_name = $params['tax_2_name'];
            $objOrder->tax_2_value = $params['tax_2_value'];
            $objOrder->tax_3_name = $params['tax_3_name'];
            $objOrder->tax_3_value = $params['tax_3_value'];
            $objOrder->tax_4_name = $params['tax_4_name'];
            $objOrder->tax_4_value = $params['tax_4_value'];
            $objOrder->tax_5_name = $params['tax_5_name'];
            $objOrder->tax_5_value = $params['tax_5_value'];
            $objOrder->receipt_number = $params['receipt_number'];
            $objOrder->note = $params['note'];
            $objOrder->save();

            if(isset($params['shippingAddress']['id']))
            {   
                $objShippingParameter = $params['shippingAddress'];
                $objShippingLocation = OrderLocation::where(['order_id'=>$params['id'],'user_id'=>$params['user_id'],'status'=>0])->first();
                if(empty($objShippingLocation)){
                    $objShippingLocation = new OrderLocation;
                }
                $objShippingLocation->first_name = $objShippingParameter['first_name'];
                $objShippingLocation->last_name = $objShippingParameter['last_name'];
                $objShippingLocation->email = $objShippingParameter['email'];
                $objShippingLocation->address = $objShippingParameter['address'];
                $objShippingLocation->address_2 = $objShippingParameter['address_2'];
                $objShippingLocation->mobile = $objShippingParameter['mobile'];
                $objShippingLocation->postal_code = $objShippingParameter['postal_code'];
                $objShippingLocation->country_id = $objShippingParameter['country_id'];
                $objShippingLocation->state_id = $objShippingParameter['state_id'];
                $objShippingLocation->city_name = $objShippingParameter['city_name'];
                $objShippingLocation->save();
            }

            if(isset($params['billingAddress']['id']))
            {   
                $objBillingParameter = $params['billingAddress'];
                $objBillingLocation = OrderLocation::where(['order_id'=>$params['id'],'user_id'=>$params['user_id'],'status'=>1])->first();
                if(empty($objBillingLocation)){
                    $objBillingLocation = new OrderLocation;
                }
                $objBillingLocation->first_name = $objBillingParameter['first_name'];
                $objBillingLocation->last_name = $objBillingParameter['last_name'];
                $objBillingLocation->email = $objBillingParameter['email'];
                $objBillingLocation->address = $objBillingParameter['address'];
                $objBillingLocation->address_2 = $objBillingParameter['address_2'];
                $objBillingLocation->mobile = $objBillingParameter['mobile'];
                $objBillingLocation->postal_code = $objBillingParameter['postal_code'];
                $objBillingLocation->country_id = $objBillingParameter['country_id'];
                $objBillingLocation->state_id = $objBillingParameter['state_id'];
                $objBillingLocation->city_name = $objBillingParameter['city_name'];
                $objBillingLocation->save();

            }
             /*$total = $params['total']; 
            $subTotal = $params['sub_total']; 
            $orderDetail = $params['data']; 

            $order = Order::where('id', $orderId)->first();
            $order->total = $total;
            $order->sub_total = $subTotal;
            $order->save();

            foreach($orderDetail as $key=>$order){
                $orderProduct = OrderProduct::where('id', $order['id'])->first();
                $orderProduct->quantity = $order['quantity'];
                $orderProduct->save();
            }*/
            $url = route('admin.orders.index' , ['order' => $orderId]);
            return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.ORDER_UPDATE_SUCCESSFULLY.code'),
                __('constants.messages.ORDER_UPDATE_SUCCESSFULLY.msg'),
                ['url'=>$url]
            );
        } catch (Exception $e) {
            return $this->errorResponse(
                __('constants.ERROR_STATUS'),
                __('constants.errors.SOMETHING_WRONG.code'),
                __('constants.errors.SOMETHING_WRONG.msg'),
                $e->getMessage()
            );

        }

        $order->update($request->all());

        return redirect()->route('admin.orders.index');
    }

    public function show($id)
    {
        abort_if(Gate::denies('order_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $list = $data = $shipping_link = [];
        $order = Order::select('id','order_nr','currency_id','user_id','email','mobile','billing_address_id','shipping_address_id','payment_method_id','sub_total','shipping_cost','taxes','total','financial_status','discount_code','discount_amount','note','admin_approve','fulfillment_status','paid_at','created_at')->with([
            'user' => function($q){
                $q->select('id', 'name', 'last_name', 'password', 'total_orders', 'mobile', 'email');
            },
        ])->with('shipping_address', 'billing_address','payment_method','payment')->where('id', $id)->first();


        $data['objPayment'] = null;

        if(!empty($order->payment))
        {
            $data['objPayment'] = $order->payment;
        }
   
        $countries = Country::get();
        $list = [
            'countries' => $countries,
            'payment_status' => Order::PAYMENT_STATUS,
            'fulfillment_status' => Order::FULFILLMENT_STATUS_SELECT,
            'btn_shipping_order_access' => !Gate::denies('btn_shipping_order_access'), 
            'btn_delete_order_access' => !Gate::denies('btn_delete_order_access'), 
            'btn_delete_action_access' => !Gate::denies('btn_delete_action_access'), 
            'btn_shipping_action_access' => !Gate::denies('btn_shipping_action_access'), 
            'btn_download_invoice_access' => !Gate::denies('btn_download_invoice_access'),
            'btn_delete_invoice_access' => !Gate::denies('btn_delete_invoice_access'),
                
        ];
        // $order->load('order_products');
        $shipping_address = $order->shipping_address;
        $billing_address = $order->billing_address;
        $shippingAddress = [];
        $billingAddress = [];

        if(isset($shipping_address) && $shipping_address != '')
        {
            $shippingAddress = $shipping_address->toArray();
            $shippingAddress['countryName'] = $shipping_address->country;
            $shippingAddress['shortCode'] =  $shipping_address->short_code;
            $shippingAddress['stateName'] = $shipping_address->state;
        }

        if(isset($billing_address) && $billing_address != '')
        {
            $billingAddress = $billing_address->toArray();
            $billingAddress['countryName'] = $billing_address->country;
            $billingAddress['shortCode'] =  $billing_address->short_code;
            $billingAddress['stateName'] =$billing_address->state;
        }

        $data['order'] = $order;
        $data['shipping_address'] = $shippingAddress;
        $data['billing_address'] = $billingAddress;
        $searchProductLimit = Config::get('SEARCH_PRODUCT_LIMIT');
        $objAddress = Address::where(['store_status'=>1,'user_id'=>Config::get('client_id'),'is_default'=>1])->first();
        $objOrderDetails = OrderProduct::where('order_id',$id)->get();
        $data['objOrderProduct'] = $objOrderDetails;
        $data['order_id'] = $id;
        $objOrderDetailsProductId = $objOrderDetails->pluck('product_id')->toArray();
        $objOrderDetailsProductVariantId = $objOrderDetails->pluck('product_variant_options_id')->toArray();
        $objShippings = Shipping::where('order_id',$id)->get();
        $objShipOrderDetailsProductId = $objShipOrderDetailsProductVariantId = null;

        if(!empty($objShippings)){
            $objShippingId = $objShippings->pluck('id');
            $objShipOrderDetails = ShippingProduct::where('order_id',$data['order_id'])->get();
            $objShipOrderDetailsProductId = $objShipOrderDetails->pluck('product_id')->toArray();
            $objShipOrderDetailsProductVariantId = $objShipOrderDetails->pluck('product_variant_options_id')->toArray();
            $data['objShipOrderDetails'] = $objShipOrderDetails;
            $data['shipping_link'] = [];
            foreach($objShippings as $intkey => $objShipping){
                $data['shipping_link'][] = Route('admin.shippingproducts.show', ['shippingproduct' => $objShipping->id]);
                }
            }
        $addressId = Order::where('id',$id)->first()->billing_address_id;
        $shippingAddressId = Order::where('id',$id)->first()->shipping_address_id;
            
        if(!$id)
        {
            $data['address'] = $data['shippingAddress'] = '';
        }

        $data['objRefunds'] = Refund::where('order_id',$id)->get();
            
        $objSelectionProducts = Product::select('id','title','quantity','slug','price','compare_at_price','is_product_variant','description','sku','barcode','weight','weight_type_id','length','length_type_id','width','width_type_id','height','height_type_id','hs_code','is_product_charge','is_special_product','special_price','is_track','cost_per_item')
                ->with([
                'medias' => function($media){
                    $media->select('client_id','product_id','src');
                    }, 
                'product_variant_options' => function ($variant) use($objOrderDetailsProductVariantId) {
                    $variant->select('id','product_id','variant_option_1_id','variant_option_2_id','variant_option_3_id','price','compare_at_price','sku','barcode','weight','weight_type_id','length','length_type_id','width','width_type_id','height','height_type_id','hs_code','is_product_charge','is_special_product','special_price','is_track','cost_per_item');
                            $variant->whereIn('id', $objOrderDetailsProductVariantId);
                    }, 
                'product_variant_options.variant_media' => function ($variantmedia) {
                    $variantmedia->select('client_id','product_variant_id','src'); 
                    },
                    'product_variant_options.variant_option_1','product_variant_options.variant_option_2','product_variant_options.variant_option_3'
                ])->whereIn('id',$objOrderDetailsProductId)
                  ->limit($searchProductLimit)
                  ->get();

        $objSelectedProductId = $objSelectionProducts->pluck('id');
        $data['cost_per_item'] = $data['totalPrice'] = $totalCosting = 0;

        foreach($objOrderDetails as $objOrderDetail){
            $data['costing'] = $objOrderDetail['quantity']*$objOrderDetail['cost_per_item'];
            $totalCosting += $data['costing'];
            $price = $objOrderDetail['price']*$objOrderDetail['quantity'];
            $data['totalPrice'] += $price;
            $data['cost_per_item'] += $objOrderDetail['cost_per_item'];
        }
        $data['totalCosting'] =  (string) number_format((float)$totalCosting, 2, '.', '');

        $totalOrderProduct = count($objOrderDetailsProductId);
        $paidByCustomer = ($data['order']['sub_total'] + $data['order']['taxes'] + $data['order']['shipping_cost']) - $data['order']['discount_amount'];
        $data['paidByCustomer'] =  (string) number_format((float)$paidByCustomer, 2, '.', '');
        $profit = $data['paidByCustomer']-$data['totalCosting'];
        $data['profit'] =  (string) number_format((float)$profit, 2, '.', '');

        $data['objSelectionProducts'] = [];
        foreach($objSelectionProducts as $objSelectionProduct)
        {
            $title = $objSelectionProduct->title;
            $slug = $objSelectionProduct->slug;
            $img_src = (!empty($objSelectionProduct->medias[0])) ? $objSelectionProduct->medias[0]->image_src[2] : '';
            if($objSelectionProduct->is_product_variant){

                if($objSelectionProduct->product_variant_options->isNotEmpty())
                    {
                        foreach($objSelectionProduct->product_variant_options as $objProductVariantOptions)
                            {   
                                $variantOption = $this->getVariantOptions($objProductVariantOptions->variant_option_1,$objProductVariantOptions->variant_option_2,$objProductVariantOptions->variant_option_3);
                                $title = $title. " (" .$variantOption.")";
                                $img_src = (!empty($objProductVariantOptions->variant_media[0])) ? $objProductVariantOptions->variant_media[0]->image_src[2] : $img_src;

                                $orderIndex = $objOrderDetails->search(function($objOrderDetail) use($objProductVariantOptions) {
                                return $objOrderDetail->product_id == $objProductVariantOptions->product_id && $objOrderDetail->product_variant_options_id == $objProductVariantOptions->id;

                                });
                                $boolShipping = false;
                                if(!empty($objShipOrderDetailsProductVariantId)){
                                    if(in_array($objProductVariantOptions->id,$objShipOrderDetailsProductVariantId)){
                                        $boolShipping = true;
                                    }
                                }
                                if(!empty($objShipOrderDetails))
                                {
                                    $objShipping = $objShipOrderDetails->filter(function($objShipOrderDetail) use($objProductVariantOptions){
                                        return $objShipOrderDetail->product_id == $objProductVariantOptions->product_id && $objShipOrderDetail->product_variant_options_id == $objProductVariantOptions->id;
                                        })->first();
                                    $intShippingId = null;
                                    if(!empty($objShipping)){
                                        $intShippingId =  Route('admin.shippingproducts.show', ['shippingproduct' => $objShipping->shipping_id]);
                                    }
                                }
                        array_push($data['objSelectionProducts'],
                            [
                                'id' => $objOrderDetails[$orderIndex]['id'],
                                'product_variant_option_id' => $objOrderDetails[$orderIndex]['product_variant_options_id'],
                                'product_id' => $objOrderDetails[$orderIndex]['product_id'],
                                'title' => $title,
                                'quantity' => $objOrderDetails[$orderIndex]['quantity'],
                                'slug' => $slug,
                                'price' => $objOrderDetails[$orderIndex]['price'],
                                'img_src'=>$img_src ,
                                'compareprice' => $objProductVariantOptions->compare_at_price,
                                'stock_status'=>$objProductVariantOptions->stock_status,
                                'order_details_id'=>$objOrderDetails[$orderIndex]['id'],
                                'costing_price'=>$objOrderDetails[$orderIndex]['cost_per_item'],
                                'sub_total'=>$data['order']['sub_total'],
                                'taxes'=>$data['order']['taxes'],
                                'sku'=>$objOrderDetails[$orderIndex]['sku'],
                                'isChecked' => $boolShipping,
                                'isShipping'=> $boolShipping,
                                'shipping_link'=> $intShippingId,
                            ]);
                        }        
                    }
                }
                else{
                    //NonVariant Product
                    $orderIndex = $objOrderDetails->search(function($objOrderDetail) use($objSelectionProduct) {
                        return $objOrderDetail->product_id == $objSelectionProduct->id;
                    });
                    $boolShipping = false;
                    if(!empty($objShipOrderDetailsProductId)){
                        if(in_array($objSelectionProduct['id'],$objShipOrderDetailsProductId)){
                            $boolShipping = true;
                        }
                    }
                    if(!empty($objShipOrderDetails));
                        {
                            $objShipping = $objShipOrderDetails->filter(function($objShipOrderDetail) use($objSelectionProduct){
                            return $objShipOrderDetail->product_id == $objSelectionProduct->id;
                            })->first();
                            $intShippingId = null;
                            if(!empty($objShipping)){
                                $intShippingId = Route('admin.shippingproducts.show', ['shippingproduct' => $objShipping->shipping_id]);
                                    
                            }
                        }
                    array_push($data['objSelectionProducts'],
                        [
                            'id' => $objOrderDetails[$orderIndex]['id'],
                            'product_variant_option_id' => $objOrderDetails[$orderIndex]['product_variant_options_id'],
                            'product_id' => $objOrderDetails[$orderIndex]['product_id'],
                            'title' => $title,
                            'quantity' => $objOrderDetails[$orderIndex]['quantity'],
                            'slug' => $slug,
                            'price' => $objOrderDetails[$orderIndex]['price'],
                            'img_src'=>$img_src,
                            'compareprice' => $objSelectionProduct->compare_at_price,
                            'stock_status'=>$objSelectionProduct->stock_status,
                            'order_details_id'=>$objOrderDetails[$orderIndex]['id'],
                            'costing_price'=>$objOrderDetails[$orderIndex]['cost_per_item'],
                            'sub_total'=>$data['order']['sub_total'],
                            'taxes'=>$data['order']['taxes'],
                            'sku'=>$objOrderDetails[$orderIndex]['sku'],
                            'isChecked' => $boolShipping,
                            'isShipping'=>$boolShipping,
                            'shipping_link'=> $intShippingId
                        ]);
                    }
                }
        $data['objSelectionProducts'] = collect($data['objSelectionProducts'])->sortBy('order_details_id')->values()->toArray();
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => 'Home'], ['link'=>route('admin.orders.index'),'name' => trans('cruds.order.title') .' '.trans('global.listing')], ['name' => trans('global.show') .' '.trans('cruds.order.title_singular') ]];
        return view('admin.orders.show', compact('data', 'list','breadcrumbs'));
    }

    public function destroy($id)
    {
        try
        {    
            abort_if(Gate::denies('order_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
            Order::where('id',$id)->delete();
        }
        catch (\Exception $e) 
        {
            return $this->errorResponse(
            __('constants.ERROR_STATUS'),
            __('constants.errors.SOMETHING_WRONG.code'),
            __('constants.errors.SOMETHING_WRONG.msg'),
            $e->getMessage()
            );
        }
        $url = route('admin.orders.index');
            return $this->successResponse(
            __('constants.SUCCESS_STATUS'),
            __('constants.messages.ORDER_DELETED_SUCCESSFULLY.code'),
            __('constants.messages.ORDER_DELETED_SUCCESSFULLY.msg'),
            ['url' => $url]
            );
    }

    public function massDestroy(MassDestroyOrderRequest $request)
    {
        try
        {
            Order::whereIn('id', request('ids'))->delete();
        }
        catch (\Exception $e) 
        {
            return $this->errorResponse(
                __('constants.ERROR_STATUS'),
                __('constants.errors.SOMETHING_WRONG.code'),
                __('constants.errors.SOMETHING_WRONG.msg'),
                $e->getMessage()
                );
        }
        return $this->successResponse(
            __('constants.SUCCESS_STATUS'),
            __('constants.messages.ORDER_DELETED_SUCCESSFULLY.code'),
            __('constants.messages.ORDER_DELETED_SUCCESSFULLY.msg'),
            );
    }

    public function updateContactInformation(Request $request)
    {
        try{
            $params = collect($request->all());
            $required = ['id', 'email', 'phone', 'isUpdateProfile'];
            $this->validateRequiredParams($required,$params->keys()->toArray());

            $id = $params['id'];
            $email = $params['email'];
            $phone = $params['phone'];
            $isUpdateProfile = $params['isUpdateProfile'];

            $order = Order::where('id', $id)->first();
            $order->email = $email;
            $order->mobile = $phone;
            $order->save();

            if($isUpdateProfile){
                if( $order ){
                    $userId = $order->user_id;
                    $user = User::where('id', $userId)->first();
                    $user->email = $email;
                    $user->mobile = $phone;
                    $user->save();
                }
            }
               
            return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.CUSTOMER_INFORMATION_UPDATE_SUCCESSFULLY.code'),
                __('constants.messages.CUSTOMER_INFORMATION_UPDATE_SUCCESSFULLY.msg')
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

    public function updateShippingAddress(Request $request)
    {
        try{
                $params = collect($request->all());
                $required = ['first_name', 'last_name','address','address_2','mobile','phone_code','country_id','state_id','city_name','postal_code'];
                $this->validateRequiredParams($required,$params->keys()->toArray());

                $address = new OrderLocation;
                if(isset($params['id'])){ // check for address id
                    $address = OrderLocation::where('id',$params['id'])->first();
                }
                $address->first_name = $params['first_name'];
                $address->last_name = $params['last_name'];
                $address->address = $params['address'];
                $address->address_2 = $params['address_2'];
                $address->mobile =$params['mobile'];
                $address->phone_code = $params['phone_code'];
                $address->country_id =$params['country_id'];
                $address->state_id = $params['state_id'];
                $address->city_name = $params['city_name'];
                $address->postal_code = $params['postal_code'];
                $address->save();

                return $this->successResponse(
                    __('constants.SUCCESS_STATUS'),
                    __('constants.messages.ADDRESS_UPDATED_SUCCESFULLY.code'),
                    __('constants.messages.ADDRESS_UPDATED_SUCCESFULLY.msg')
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

    public function updateOrderNote(Request $request)
    {
        try{
            $params = collect($request->all());
            $required = ['id', 'note'];
            $this->validateRequiredParams($required,$params->keys()->toArray());

            $id = $params['id'];
            $note = $params['note'];

            $order = Order::where('id', $id)->first();
            $order->note = $note;
            $order->save();

            return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.DATA_UPDATE_SUCCESSFULLY.code'),
                __('constants.messages.DATA_UPDATE_SUCCESSFULLY.msg')
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

    public function markAsComplete($id)
    {
        try{
            $order = Order::where('id', $id)->first();
            $order->financial_status = 'paid';
            $order->save();

            return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.MARK_PAID_SUCCESSFULLY.code'),
                __('constants.messages.MARK_PAID_SUCCESSFULLY.msg')
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

    public function fulfilledOrder($id)
    {
        try{
            $order = Order::where('id', $id)->first();
            $order->fulfillment_status = 'fulfilled';
            $order->save();

            return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.ORDER_FULFILL_SUCCESSFULLY.code'),
                __('constants.messages.ORDER_FULFILL_SUCCESSFULLY.msg')
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

    public function deleteOrder($id)
    {
        try{
            $data = $this->getOrderData($id);
            $this->emailService->orderCancelled($data);
            Order::whereId($id)->delete();
            $url = route('admin.orders.index');
            return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.ORDER_DELETED_SUCCESSFULLY.code'),
                __('constants.messages.ORDER_DELETED_SUCCESSFULLY.msg'),
                ['url' => $url]
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

    public function deleteOrderProduct($orderProductId){
        try{
            OrderProduct::where('id',$orderProductId)->delete();
            return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.ORDER_PRODUCTS_DELETE_SUCCESSFULLY.code'),
                __('constants.messages.ORDER_PRODUCTS_DELETE_SUCCESSFULLY.msg'),
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

    public function performAction(Request $request)
    {
        try{
            $params = collect($request->all());
            $required = ['ids', 'type'];
            $this->validateRequiredParams($required,$params->keys()->toArray());
            $ids = $params['ids'];
            $type = $params['type'];

            if($type == 'fulfilled'){
                $order = Order::whereIn('id', $ids)->update(['fulfillment_status' => 'fulfilled']);
                return $this->successResponse(
                    __('constants.SUCCESS_STATUS'),
                    __('constants.messages.ORDER_FULFILL_SUCCESSFULLY.code'),
                    __('constants.messages.ORDER_FULFILL_SUCCESSFULLY.msg')
                );
            } else if($type == 'archive'){
                $order = Order::whereIn('id', $ids)->update(['status' => 'archived']);
                return $this->successResponse(
                    __('constants.SUCCESS_STATUS'),
                    __('constants.messages.ORDER_ARCHIVE_SUCCESSFULLY.code'),
                    __('constants.messages.ORDER_ARCHIVE_SUCCESSFULLY.msg')
                );
            } else if($type == 'unarchive'){
                $order = Order::whereIn('id', $ids)->update(['status' => 'open']);
                return $this->successResponse(
                    __('constants.SUCCESS_STATUS'),
                    __('constants.messages.ORDER_UNARCHIVE_SUCCESSFULLY.code'),
                    __('constants.messages.ORDER_UNARCHIVE_SUCCESSFULLY.msg')
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
}
