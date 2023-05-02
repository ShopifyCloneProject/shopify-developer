<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use Illuminate\Http\Request;
use App\Http\Requests\MassDestroyShippingProductRequest;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use App\Services\ShiprocketService;
use App\Services\IthinklogisticsService;
use App\Services\EmailService;
use Illuminate\Support\Carbon;
use App\Models\User;
use App\Models\Order;
use App\Models\Address;
use App\Models\Dimension;
use App\Models\Weightmanage;
use App\Models\Courier;
use App\Models\ShippingMethod;
use App\Models\Product;
use App\Models\Shipping;
use App\Models\ShippingProduct;
use App\Models\ShippingDetail;
use App\Models\Shipments;
use App\Models\OrderProduct;
use App\Models\Payment;
use App\Models\Warehouse;
use Gate;
use Config;
use File;
use Storage;

class ShippingController extends Controller
{
    use MediaUploadingTrait;
    protected $shipService;
    protected $ithinkService;
    protected $emailService;

    public function __construct()
    {
        $this->shipService = new ShiprocketService;
        $this->ithinkService = new IthinklogisticsService;
        $this->emailService = new EmailService;
    }

    public function index(Request $request)
    {
        abort_if(Gate::denies('shipping_product_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            try{

                    $query = Shipping::with(['user'])->select(sprintf('%s.*', (new Shipping())->table));
                    $table = Datatables::of($query);

                    $table->addColumn('actions', ' ');

                    $table->editColumn('id', function ($row) {
                        return $row->id ? $row->id : '';
                    });

                    $table->editColumn('shipment_order_number', function ($row) {
                        return $row->shipment_order_number ? $row->shipment_order_number : '';
                    });

                    $table->addColumn('email', function ($row) {
                        return $row->user ? $row->user->email : '';
                    });

                    $table->addColumn('mobile', function ($row) {
                        return $row->user ? $row->user->mobile : '';
                    });

                    $table->addColumn('shipping_method', function ($row) {
                        return $row->shipping_method ? $row->shipping_method->title : '';
                    }); 

                    $table->editColumn('admin_approve', function ($row) {
                        return $row->admin_approve ? $row->admin_approve : '';
                    });
                    $table->editColumn('order_id', function ($row) {
                        return $row->order_id;
                    });


                    $table->rawColumns(['actions', 'user','shipping_method']);
                    return $table->make(true);

                }catch (Exception $e) {
                 return $this->errorResponse(
                    __('constants.ERROR_STATUS'),
                    __('constants.errors.SOMETHING_WRONG.code'),
                    __('constants.errors.SOMETHING_WRONG.msg'),
                    $e->getMessage()
                    );
                } 
        }
        $shippings = ShippingMethod::get();
        $objShippingMethods = ShippingMethod::all()->pluck('title','id');
        // dd($objShippingMethods);
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['name' => trans('cruds.shippingproducts.title_singular')." ".trans('global.listing') ]];
        return view('admin.shippingproducts.index', compact('breadcrumbs','shippings','objShippingMethods'));
    }

     public function show($id){
       abort_if(Gate::denies('shipping_product_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
       $list = $data =  $breadcrumbs = [];
       $objShipping = Shipping::where('id',$id)->first();
       if(!empty($objShipping ))
       {
           $list = [
            'btn_save_shipping_access' => !Gate::denies('btn_save_shipping_access'),
            'btn_save_shipping_order_access' => !Gate::denies('btn_save_shipping_order_access'),
            'btn_delete_shipping_access' => !Gate::denies('btn_delete_shipping_access'),
            'btn_cancel_shipping_access' => !Gate::denies('btn_cancel_shipping_access'),
            'btn_delete_product_action_access' => !Gate::denies('btn_delete_product_action_access'),
            'btn_generate_manifest_access' => !Gate::denies('btn_generate_manifest_access'),
            'btn_print_manifest_access' => !Gate::denies('btn_print_manifest_access'),
            'btn_generate_label_access' => !Gate::denies('btn_generate_label_access'),
            'btn_generate_invoice_access' => !Gate::denies('btn_generate_invoice_access'),
            'btn_pickup_access' => !Gate::denies('btn_pickup_access'),
            'btn_track_url_access' => !Gate::denies('btn_track_url_access'),
            'btn_shipping_delivered_access' => !Gate::denies('btn_shipping_delivered_access'),
            'btn_paid_order_access' => !Gate::denies('btn_paid_order_access'),
           ];
           $list['dimensions'] = Dimension::get()->pluck('short_code','id')->prepend(trans('global.pleaseSelect'),0);
           $list['weightmanages'] = Weightmanage::get()->pluck('short_code','id')->prepend(trans('global.pleaseSelect'),0);
           $list['shippingmethods'] = ShippingMethod::get()->pluck('title','id');
           $list['couriers'] = Courier::get()->pluck('name','id');
           $list['pickup_location'] = Warehouse::where('shipping_method_id', array_key_first($list['shippingmethods']->toArray()))->pluck('pickup_code','pickup_id');
           $searchProductLimit = Config::get('SEARCH_PRODUCT_LIMIT');

           if(!empty($objShipping)){        
           $data['order_id'] = $objShipping->order_id;
           if($objShipping->pickup_id > 0)
           {
            $list['pickup_location'] = Warehouse::where('shipping_method_id', $objShipping->shipping_method_id)->pluck('pickup_code','pickup_id');
           }
           $objShippingOrderDetails = ShippingProduct::where('shipping_id',$id)->get();
           $objShippingOrderDetailsProductId = $objShipping->shipping_products->pluck('product_id')->toArray();
           $objShippingOrderDetailsProductVariantId = $objShipping->shipping_products->pluck('product_variant_options_id')->toArray();
           $objWeightManageDetails = $list['weightmanages']->toArray();
           $objDimensionDetails = $list['dimensions']->toArray();
           $objShipments = Shipments::where(['order_id'=>$data['order_id'],'shipment_order_number'=>$objShipping->shipment_order_number])->first();
               $foreachData = $objResponse = [];
               $objCourierResponse = $objShipping->rate_data;
               $objCourierResponse = json_decode($objCourierResponse,true);
           if(!empty($objShipments))
           {
                
                $objShippingMethod = ShippingMethod::whereId($objShipments->shipping_method_id)->first();
                if($objShippingMethod->title == 'ShipRocket') // shiprocket
                {
                    foreach(collect($objCourierResponse['data']['available_courier_companies'])->sortBy('rate') as $intKey => $objShippingTrackData)
                    {
                        $objTempData = [];
                        $objTempData['id'] = $objShippingTrackData['courier_company_id'];
                        $objTempData['logistics_name'] = $objShippingTrackData['courier_name'];
                        $objTempData['rating'] = $objShippingTrackData['rating'];
                        $objTempData['rate'] = $objShippingTrackData['rate'];
                        $objTempData['delivery_days'] = $objShippingTrackData['estimated_delivery_days'];
                        $objResponse[] = $objTempData;
                    }
                } 
                else if($objShippingMethod->title == 'Ithinklogistics') // ithinklogistcs
                {
                    foreach(collect($objCourierResponse['data'])->sortBy('rate') as $intKey => $objShippingTrackData)
                    {
                        $objTempData = [];
                        $objTempData['id'] = $objShippingTrackData['logistic_name'];
                        $objTempData['logistics_name'] = $objShippingTrackData['logistic_name'];
                        $objTempData['rating'] = "-";
                        $objTempData['rate'] = $objShippingTrackData['rate'];
                        $objTempData['delivery_days'] = $objCourierResponse['expected_delivery_date'];
                        $objResponse[] = $objTempData;
                    }
                }
           
                $data['objCourierResponse'] = collect($objResponse)->sortBy('rate')->toArray();
            }
            else if(empty($objShipments))
           {
                $objShipments['pickup_status'] = 0;
                $objShipments['shipment_id'] = null;
           }
           $objOrder = Order::with('payment_method')->where('id',$data['order_id'])->first();
           $payment_mode = 'Prepaid';
           if(!empty($objOrder)){
                if($objOrder->payment_method->title == 'COD')
                {
                    $payment_mode = 'COD';
                }
           }

           $order = Order::with('shipping_address', 'billing_address')->where('id', $data['order_id'])->first();
           $order_link = Route('admin.orders.show', ['order' => $data['order_id']]); 
           if($order)
           {
                $shipping_address = $order->shipping_address;
                $billing_address = $order->billing_address;
           }
            $shippingAddress = [];
            $billingAddress = [];

            if(isset($shipping_address) && $shipping_address != ''){
                $shippingAddress['id'] =  $shipping_address->id;
                $shippingAddress['first_name'] =  $shipping_address->first_name;
                $shippingAddress['last_name'] =  $shipping_address->last_name;
                $shippingAddress['address'] =  $shipping_address->address;
                $shippingAddress['address_2'] =  $shipping_address->address_2;
                $shippingAddress['city_name'] =  $shipping_address->city_name;
                $shippingAddress['country_id'] =  $shipping_address->country_id;
                $shippingAddress['countryName'] = $shipping_address->country;
                $shippingAddress['state_id'] =  $shipping_address->state_id;
                $shippingAddress['stateName'] = $shipping_address->state;
                $shippingAddress['mobile'] =  $shipping_address->mobile;
                $shippingAddress['shortCode'] =  $shipping_address->short_code;
                $shippingAddress['postal_code'] =  $shipping_address->postal_code;
                $shippingAddress['phone_code'] =  $shipping_address->phone_code;
            }

            if(isset($billing_address) && $billing_address != ''){
                $billingAddress['id'] =  $billing_address->id;
                $billingAddress['first_name'] =  $billing_address->first_name;
                $billingAddress['last_name'] =  $billing_address->last_name;
                $billingAddress['address'] =  $billing_address->address;
                $billingAddress['address_2'] =  $billing_address->address_2;
                $billingAddress['city_name'] =  $billing_address->city_name;
                $billingAddress['country_id'] =  $billing_address->country_id;
                $billingAddress['countryName'] = $billing_address->country;
                $billingAddress['state_id'] =  $billing_address->state_id;
                $billingAddress['stateName'] =$billing_address->state;
                $billingAddress['mobile'] =  $billing_address->mobile;
                $billingAddress['shortCode'] =  $billing_address->short_code;
                $billingAddress['postal_code'] =  $billing_address->postal_code;
                $billingAddress['phone_code'] =  $billing_address->phone_code;
            }
           
            $data = [
                'order' => $order,
                'shipping_address' => (!empty($shipping_address)) ? $shippingAddress : null,
                'billing_address' => (!empty($billing_address)) ? $billing_address : null,
                'objShipping' => $objShipping,
                'payment_mode' => $payment_mode,
                'objShipments' => $objShipments,
                'objShippingOrderDetails' => $objShippingOrderDetails,
                'order_link' => $order_link,
                'objCourierResponse' => $objResponse,
            ];

            $objSelectionProducts = Product::select('id','title','quantity','slug','price','compare_at_price','is_product_variant','description','sku','barcode','weight','weight_type_id','length','length_type_id','width','width_type_id','height','height_type_id','hs_code','is_product_charge','is_special_product','special_price','is_track','cost_per_item')
                ->with([
                'medias' => function($media){
                    $media->select('client_id','product_id','src');
                    }, 
                'product_variant_options' => function ($variant) use($objShippingOrderDetailsProductVariantId) {
                        $variant->select('id','product_id','variant_option_1_id','variant_option_2_id','variant_option_3_id','price','compare_at_price','sku','barcode','weight','weight_type_id','length','length_type_id','width','width_type_id','height','height_type_id','hs_code','is_product_charge','is_special_product','special_price','is_track','cost_per_item');
                        $variant->whereIn('id', $objShippingOrderDetailsProductVariantId);
                    }, 
                'product_variant_options.variant_media' => function ($variantmedia) {
                        $variantmedia->select('client_id','product_variant_id','src'); 
                },
                'product_variant_options.variant_option_1','product_variant_options.variant_option_2','product_variant_options.variant_option_3'
            ])->whereIn('id',$objShippingOrderDetailsProductId)
                ->limit($searchProductLimit)
                ->get();

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

                                $orderIndex = $objShippingOrderDetails->search(function($objShippingOrderDetail) use($objProductVariantOptions) {
                                return $objShippingOrderDetail->product_id == $objProductVariantOptions->product_id && $objShippingOrderDetail->product_variant_options_id == $objProductVariantOptions->id;

                                });
                                array_push($data['objSelectionProducts'],
                                [
                                    'id' => $objShippingOrderDetails[$orderIndex]['id'],
                                    'product_variant_option_id' => $objShippingOrderDetails[$orderIndex]['product_variant_options_id'],
                                    'product_id' => $objShippingOrderDetails[$orderIndex]['product_id'],
                                    'title' => $title,
                                    'quantity' => $objShippingOrderDetails[$orderIndex]['quantity'],
                                    'slug' => $slug,
                                    'price' => $objShippingOrderDetails[$orderIndex]['selling_price'],
                                    'img_src'=> $img_src,
                                    'barcode'=> $objProductVariantOptions->barcode,
                                    'weight'=> $objProductVariantOptions->weight,
                                    'weight_type_id' => $objProductVariantOptions->weight_type_id,
                                    'weight_type' => ($objProductVariantOptions->weight_type_id > 0)  ?$objWeightManageDetails[$objProductVariantOptions->weight_type_id] : null,
                                    'width'=> $objProductVariantOptions->width,
                                    'width_type_id' => $objProductVariantOptions->width_type_id,
                                    'dimension_width_type' => ($objProductVariantOptions->width_type_id > 0) ? $objDimensionDetails[$objProductVariantOptions->width_type_id] : null,
                                    'height'=> $objProductVariantOptions->height,
                                    'height_type_id' => $objProductVariantOptions->height_type_id,
                                    'dimension_height_type' => ($objProductVariantOptions->height_type_id > 0) ? $objDimensionDetails[$objProductVariantOptions->height_type_id] : null,
                                    'length'=> $objProductVariantOptions->length,
                                    'length_type_id' => $objProductVariantOptions->length_type_id,
                                    'dimension_length_type' => ($objProductVariantOptions->length_type_id > 0) ? $objDimensionDetails[$objProductVariantOptions->length_type_id] : null,
                                    'compareprice' => $objProductVariantOptions->compareprice,
                                    'stock_status'=>$objProductVariantOptions->stock_status,
                                    'costing_price'=>$objProductVariantOptions->cost_per_item,
                                    'sku'=>$objShippingOrderDetails[$orderIndex]['sku'],
                                    'hs_code'=> $objProductVariantOptions->hs_code,
                                    'is_product_charge'=> $objProductVariantOptions->is_product_charge,
                                    'is_special_product'=> $objProductVariantOptions->is_special_product,
                                    'special_price'=> $objProductVariantOptions->special_price,
                                    'is_track'=> $objProductVariantOptions->is_track,
                                ]);
                            }        
                        }
                    }
                    else
                    {
                        $orderIndex = $objShippingOrderDetails->search(function($objShippingOrderDetail) use($objSelectionProduct) {
                            return $objShippingOrderDetail->product_id == $objSelectionProduct->id;
                        });
                        array_push($data['objSelectionProducts'],
                        [
                            'id' => $objShippingOrderDetails[$orderIndex]['id'],
                            'product_variant_option_id' => $objShippingOrderDetails[$orderIndex]['product_variant_options_id'],
                            'product_id' => $objShippingOrderDetails[$orderIndex]['product_id'],
                            'title' => $title,
                            'quantity' => $objShippingOrderDetails[$orderIndex]['quantity'],
                            'slug' => $slug,
                            'price' => $objShippingOrderDetails[$orderIndex]['selling_price'],
                            'img_src'=> $img_src,
                            'barcode'=> $objSelectionProduct->barcode,
                            'weight'=> $objSelectionProduct->weight,
                            'weight_type_id' => $objSelectionProduct->weight_type_id,
                            'weight_type' => ($objSelectionProduct->weight_type_id > 0)  ?$objWeightManageDetails[$objSelectionProduct->weight_type_id] : null,
                            'width'=> $objSelectionProduct->width,
                            'width_type_id' => $objSelectionProduct->width_type_id,
                            'dimension_width_type' => ($objSelectionProduct->width_type_id > 0) ? $objDimensionDetails[$objSelectionProduct->width_type_id] : null,
                            'height'=> $objSelectionProduct->height,
                            'height_type_id' => $objSelectionProduct->height_type_id,
                            'dimension_height_type' => ($objSelectionProduct->height_type_id > 0) ? $objDimensionDetails[$objSelectionProduct->height_type_id] : null,
                            'length'=> $objSelectionProduct->length,
                            'length_type_id' => $objSelectionProduct->length_type_id,
                            'dimension_length_type' => ($objSelectionProduct->length_type_id > 0) ? $objDimensionDetails[$objSelectionProduct->length_type_id] : null,
                            'compareprice' => $objSelectionProduct->compareprice,
                            'stock_status'=>$objSelectionProduct->stock_status,
                            'costing_price'=>$objSelectionProduct->cost_per_item,
                            'sku'=>$objShippingOrderDetails[$orderIndex]['sku'],
                            'hs_code'=> $objSelectionProduct->hs_code,
                            'is_product_charge'=> $objSelectionProduct->is_product_charge,
                            'is_special_product'=> $objSelectionProduct->is_special_product,
                            'special_price'=> $objSelectionProduct->special_price,
                            'is_track'=> $objSelectionProduct->is_track,
                        ]);
                    }
                }
                $data['objSelectionProducts'] = collect($data['objSelectionProducts'])->sortBy('id')->values()->toArray();
            }
       }
       
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => 'Home'], ['link'=>route('admin.shippingproducts.index'),'name' => trans('cruds.shippingproducts.title') .' '.trans('global.listing')], ['name' => trans('global.show') .' '.trans('cruds.shippingproducts.title_singular') ]];
        return view('admin.shippingproducts.show', compact('breadcrumbs','list','data'));    
     }

     public function saveShippingOrder(Request $request){
        try{
            abort_if(Gate::denies('btn_shipping_order_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
            $params = collect($request->all());
            $required = ['order_id', 'user_id','objFinalSelectionProducts'];
            $this->validateRequiredParams($required,$params->keys()->toArray());

            $data['shipping_product'] = false;
            $objOrder = Order::where('id',$params['order_id'])->first();
            if(!empty($objOrder))
            {
                $objOrder->admin_approve = 1;
                $objOrder->save();
            }

            $objShipping = new Shipping;
            $objShipping->order_id = $params['order_id'];
            $objShipping->user_id = $params['user_id'];
            $objShipping->save();
            
            foreach($params['objFinalSelectionProducts'] as $selectionProducts){
                $objShippingProduct = new ShippingProduct;
                $objShippingProduct->shipping_id = $objShipping->id;
                $objShippingProduct->title = trim($selectionProducts['title']);
                $objShippingProduct->selling_price = $selectionProducts['price'];
                $objShippingProduct->order_id = $params['order_id'];
                $objShippingProduct->product_id = $selectionProducts['product_id'];
                $objShippingProduct->product_variant_options_id = $selectionProducts['product_variant_option_id'];
                $objShippingProduct->quantity = $selectionProducts['quantity'];
                $objShippingProduct->sku = trim($selectionProducts['sku']);
                $objShippingProduct->save();
            }

            if($request->ajax()){
                $url = route('admin.shippingproducts.show', ['shippingproduct' => $objShipping->id]);
                return $this->successResponse(
                        __('constants.SUCCESS_STATUS'),
                        __('constants.messages.SHIPPING_ORDER_CREATED_SUCCESSFULLY.code'),
                        __('constants.messages.SHIPPING_ORDER_CREATED_SUCCESSFULLY.msg'),
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

     public function saveShippingProduct(Request $request){
        try{
            abort_if(Gate::denies('btn_save_shipping_order_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
            $params = collect($request->all());
            $url = route('admin.shippingproducts.index');
             $objOrder = Order::whereId($params['order_id'])->latest()->first();
            if(empty($objOrder)){
                     return $this->successResponse(
                    __('constants.SUCCESS_STATUS'),
                    __('constants.messages.SHIPPED_EXCHANGE_CANCELED.code'),
                    __('constants.messages.SHIPPED_EXCHANGE_CANCELED.msg'),
                      ['url'=>$url]
                    );
            }
            $objShipping = Shipping::where('id',$params['id'])->first();
            
            $orderNumber = $this->getShippingOrderNumber();
            if(empty($objShipping)){
                $objShipping = new Shipping;
            }
            $objShipping->shipment_order_number = $orderNumber;
            $objShipping->pickup_id = $params['pickup_id'];
            $objShipping->title = trim($params['title']);
            $objShipping->quantity = $params['quantity'];
            $objShipping->selling_price = $params['selling_price'];
            $objShipping->discount = $params['discount'];
            $objShipping->tax = $params['tax'];
            $objShipping->shipping_charges = $params['shipping_charges'];
            $objShipping->giftwrap_charges = $params['giftwrap_charges'];
            $objShipping->transaction_charges = $params['transaction_charges'];
            $objShipping->total_discount = $params['total_discount'];
            $objShipping->sub_total = $params['sub_total'];        
            $objShipping->weight_type_id = $params['weight_type_id'];
            $objShipping->weight = $params['weight'];
            $objShipping->length_type_id = $params['length_type_id'];
            $objShipping->length = $params['length'];
            $objShipping->width_type_id = $params['width_type_id'];
            $objShipping->width = $params['width'];
            $objShipping->height_type_id = $params['height_type_id'];
            $objShipping->height = $params['height'];
            $objShipping->shipping_method_id = $params['shipping_method_id'];
            $objShipping->courier_id = $params['courier_id'];
            $objShipping->payment_mode = $params['payment_mode'];
            $objShipping->save();

            if ($request->ajax()) {
                if($params['shippingStatus'] == 1){
                    $objShippingMethod = ShippingMethod::whereId($objShipping->shipping_method_id)->first();
                    if($objShippingMethod->title == 'ShipRocket')
                    {
                        $this->shipService->handleShipping('createShipping', $objShipping->id);
                    }
                    elseif($objShippingMethod->title == 'Ithinklogistics')
                    {
                        $this->ithinkService->handleShipping('createShipping', $objShipping->id);
                    }
                    $objShipping->admin_approve = 1;
                    $objShipping->save();
                    return $this->successResponse(
                    __('constants.SUCCESS_STATUS'),
                    __('constants.messages.PRODUCT_SHIPPED_SUCCESSFULLY.code'),
                    __('constants.messages.PRODUCT_SHIPPED_SUCCESSFULLY.msg'),
                      ['url'=>$url]
                    );
                }
                else
                {
                    return $this->successResponse(
                    __('constants.SUCCESS_STATUS'),
                    __('constants.messages.SHIPPING_SAVED_SUCCESSFULLY.code'),
                    __('constants.messages.SHIPPING_SAVED_SUCCESSFULLY.msg'),
                    );

                }
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

     public function deleteShippingOrder(Request $request,$id){
        try{
            abort_if(Gate::denies('btn_delete_shipping_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
            Shipping::where('id',$id)->delete();
            ShippingProduct::where('shipping_id',$id)->delete();
            if ($request->ajax()) {
                $url = route('admin.shippingproducts.index');
                return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.SHIPPING_ORDER_DELETE_SUCCESSFULLY.code'),
                __('constants.messages.SHIPPING_ORDER_DELETE_SUCCESSFULLY.msg'),
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

     public function deleteShippingOrderProduct(Request $request,$id){
        try{
            abort_if(Gate::denies('btn_delete_product_action_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
            $objShippingProduct = ShippingProduct::where('id',$id)->first();
            $objShippingId = $objShippingProduct->shipping_id;
            ShippingProduct::where('id',$id)->delete();
            $shipProductCount = ShippingProduct::where('shipping_id',$objShippingId)->count();
            if($shipProductCount == 0){
               Shipping::where('id',$objShippingId)->delete();
            }
            if ($request->ajax()) {
                $url = route('admin.shippingproducts.index');
                return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.SHIPPED_ORDERPRODUCT_CANCELLED_SUCCESSFULLY.code'),
                __('constants.messages.SHIPPED_ORDERPRODUCT_CANCELLED_SUCCESSFULLY.msg'),
                ['url' => $url],
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

     public function getShippingOrderNumber(){
        $latestOrder = Shipping::where('shipment_order_number','!=',null)->orderBy('shipment_order_number','DESC')->withTrashed()->first();
            if(!empty($latestOrder)){
                $orderNumber = $latestOrder->shipment_order_number;
                $orderNumber = $orderNumber + 1;
            } else {
                $orderNumber = Config::get('shipment_order_number');
            }

        return $orderNumber;
    }

     public function handleShippingActions(Request $request){
        try{
            $params = collect($request->all());
            $url = null;

            $objShipping = Shipping::whereId($params['shipping_id'])->first();
            if(!empty($objShipping))
            {
                $objShippingMethod = ShippingMethod::whereId($objShipping->shipping_method_id)->first();
                $objShipments = Shipments::where('shipment_order_number', $objShipping->shipment_order_number)->first();
                if($params['status'] == 'generate_manifest_url'){
                    if($objShippingMethod->title == 'ShipRocket')
                    {
                        $this->shipService->handleShipping('generateManifest', [$objShipments->shipment_id]);
                    }
                    $objShipments = Shipments::where('shipment_order_number', $objShipping->shipment_order_number)->first();
                    $url = $objShipments->generate_manifest_url;
                }elseif($params['status'] == 'print_manifest_url'){
                   if($objShippingMethod->title == 'ShipRocket')
                    {
                        $this->shipService->handleShipping('printManifest', [$objShipments->shipment_order_id]);
                    }
                    $objShipments = Shipments::where('shipment_order_number', $objShipping->shipment_order_number)->first();
                    $url = $objShipments->print_manifest_url;
                }elseif($params['status'] == 'label_url'){
                    $objShipments = Shipments::where('shipment_order_number', $objShipping->shipment_order_number)->first();
                    if($objShippingMethod->title == 'ShipRocket')
                    {
                        $this->shipService->handleShipping('generateLabel', [$objShipments->shipment_id]);
                    }
                    elseif($objShippingMethod->title == 'Ithinklogistics')
                    {
                        $this->ithinkService->handleShipping('generateLabel', [$objShipments->shipment_id]);
                    }
                    $objNewShipments = Shipments::where('shipment_order_number', $objShipping->shipment_order_number)->first();
                    $url = $objNewShipments->label_url;
                } elseif($params['status'] == 'invoice_url'){
                   $objShipments = Shipments::where('shipment_order_number', $objShipping->shipment_order_number)->first();
                    if($objShippingMethod->title == 'ShipRocket')
                    {
                        $this->shipService->handleShipping('printInvoiceManifest', [$objShipments->shipment_order_id]);
                    }
                    elseif($objShippingMethod->title == 'Ithinklogistics')
                    {
                        $this->ithinkService->handleShipping('printInvoiceManifest', [$objShipments->shipment_id]);
                    }
                    $objNewShipments = Shipments::where('shipment_order_number', $objShipping->shipment_order_number)->first();
                    $url = $objNewShipments->invoice_url;
                } 
            }

            return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.ACTION_GET_SUCCESSFULLY.code'),
                __('constants.messages.ACTION_GET_SUCCESSFULLY.msg'),
                $url
            );
        } catch (Exception $e) {
             return $this->errorResponse(
                __('constants.ERROR_STATUS'),
                __('constants.errors.SOMETHING_WRONG.code'),
                __('constants.errors.SOMETHING_WRONG.msg'),
                $e->getMessage()
            );
        }
        return view('admin.shippingproducts.index');
    }

    public function cancelShipping($shipping_id){
        try{
            abort_if(Gate::denies('btn_cancel_shipping_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
            $objShipping = Shipping::whereId($shipping_id)->first();
            if(!empty($objShipping))
            {
                $objShippingMethod = ShippingMethod::whereId($objShipping->shipping_method_id)->first();
                $objShipments = Shipments::where('shipment_order_number', $objShipping->shipment_order_number)->first();
                if($objShippingMethod->title == 'ShipRocket')
                {
                    $this->shipService->handleShipping('cancelShipping', [$objShipments->shipment_order_id]);
                }
                elseif($objShippingMethod->title == 'Ithinklogistics')
                {
                    $this->ithinkService->handleShipping('cancelShipping', [$objShipments->shipment_id]);
                }
            }
            $objShipping->delete();
            return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.SHIPPING_ORDER_CANCEL_SUCCESSFULLY.code'),
                __('constants.messages.SHIPPING_ORDER_CANCEL_SUCCESSFULLY.msg'),
                route('admin.shippingproducts.index')
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

    public function pickup($shipping_id){
        try{
            abort_if(Gate::denies('btn_pickup_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
            $objShipping = Shipping::whereId($shipping_id)->first();
            $objShippingMethod = ShippingMethod::whereId($objShipping->shipping_method_id)->first();
            $objShipments = Shipments::where('shipment_order_number', $objShipping->shipment_order_number)->first();
            if($objShippingMethod->title == 'ShipRocket')
            {
                $this->shipService->handleShipping('setRequestPickup', [$objShipments->shipment_id]);
            }

            return $this->successResponse(
                    __('constants.SUCCESS_STATUS'),
                    __('constants.messages.PICKUP_DONE.code'),
                    __('constants.messages.PICKUP_DONE.msg'),
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

    public function checkTrack($shipmentId){
        try{
                $objShipments = Shipments::where('id',$shipmentId)->first();
                $objShippingMethod = ShippingMethod::whereId($objShipments->shipping_method_id)->first();
                $objTrackResponse = [];
                if($objShippingMethod->title == 'ShipRocket') // shiprocket
                {
                    $objTrackResponse = $this->shipService->handleShipping('getShippingAWBTrack', $objShipments->awb_code);
                    foreach($objTrackResponse['tracking_data']['shipment_track_activities'] as $intKey => $objShippingTrackData)
                    {
                        $objTempData = [];
                        $objTempData['date'] = Carbon::createFromFormat('Y-m-d H:i:s', $objShippingTrackData['date'])->format(config('panel.date_format') . ' ' . config('panel.time_format'));
                        $objTempData['status'] = $objShippingTrackData['activity'];
                        $objTempData['location'] = $objShippingTrackData['location'];
                        $objResponse['track_data'][] = $objTempData;
                    }
                        $objResponse['track_url'] = $objTrackResponse['tracking_data']['track_url'];
                } 
                else if($objShippingMethod->title == 'Ithinklogistics') // ithinklogistcs
                {
                    $objShipmentDetail = ShippingDetail::where('name','Ithinklogistics')->latest()->first();
                    $dataKey = ($objShipmentDetail->test_mode == 1) ? '901234567109': $objShipments->shipment_id;
                    $objTrackResponse = $this->ithinkService->handleShipping('getOrderTracking', [$objShipments->shipment_id]);
                    foreach($objTrackResponse['data'][$dataKey]['scan_details'] as $intKey => $objShippingTrackData)
                        {
                            $objTempData = [];
                            $objTempData['date'] = Carbon::createFromFormat('Y-m-d H:i:s', $objShippingTrackData['scan_date_time'])->format(config('panel.date_format') . ' ' . config('panel.time_format'));
                            $objTempData['status'] = $objShippingTrackData['status'];
                            $objTempData['location'] = $objShippingTrackData['scan_location'];
                            $objResponse['track_data'][] = $objTempData;
                        }
                        $objResponse['track_url'] = null;
                }
                return $this->successResponse(
                        __('constants.SUCCESS_STATUS'),
                        __('constants.messages.SHIPPING_TRACKED_SUCCESSFULLY.code'),
                        __('constants.messages.SHIPPING_TRACKED_SUCCESSFULLY.msg'),
                        $objResponse
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

    public function destroy($id)
    {
        try {
              abort_if(Gate::denies('shipping_product_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
              Shipping::where('id',$id)->delete();   
              ShippingProduct::where('shipping_id',$id)->delete();   
             
        } 
        catch (\Exception $e) {
            return $this->errorResponse(
                __('constants.ERROR_STATUS'),
                __('constants.errors.SOMETHING_WRONG.code'),
                __('constants.errors.SOMETHING_WRONG.msg'),
                $e->getMessage()
            );
        }
        return $this->successResponse(
            __('constants.SUCCESS_STATUS'),
            __('constants.messages.SHIPPING_ORDER_DELETE_SUCCESSFULLY.code'),
            __('constants.messages.SHIPPING_ORDER_DELETE_SUCCESSFULLY.msg'),
        );
    }

    public function massDestroy(MassDestroyShippingProductRequest $request)
    {
        try {
              Shipping::whereIn('id', request('ids'))->delete(); 
              ShippingProduct::whereIn('shipping_id', request('ids'))->delete(); 
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
            __('constants.messages.SHIPPING_ORDERS_DELETE_SUCCESSFULLY.code'),
            __('constants.messages.SHIPPING_ORDERS_DELETE_SUCCESSFULLY.msg'),
        );
    }

    public function getPickupLocation($shippingMethodId){
        try{
            $list = [];
            $list['getpickup'] = Warehouse::where('shipping_method_id',$shippingMethodId)->pluck('pickup_code','pickup_id');
            return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.PICKUP_LOCATION_GET_SUCCESSFULLY.code'),
                __('constants.messages.PICKUP_LOCATION_GET_SUCCESSFULLY.msg'),
                $list['getpickup']
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

    public function availableCouriers(Request $request){
        try{

            $foreachData = $objResponse = [];
            $params = collect($request->all());
            $objShippingMethod = ShippingMethod::whereId($params['shipping_method_id'])->first();
            if($objShippingMethod->title == 'ShipRocket') // shiprocket
            {
                $objCourierResponse = $this->shipService->handleShipping('getRate', $params);
                foreach(collect($objCourierResponse['data']['available_courier_companies'])->sortBy('rate') as $intKey => $objShippingTrackData)
                {
                    $objTempData = [];
                    $objTempData['id'] = $objShippingTrackData['courier_company_id'];
                    $objTempData['logistics_name'] = $objShippingTrackData['courier_name'];
                    $objTempData['rate'] = $objShippingTrackData['rate'];
                    $objTempData['delivery_days'] = $objShippingTrackData['estimated_delivery_days'];
                    $objTempData['rating'] = $objShippingTrackData['rating'];
                    $objResponse[] = $objTempData;
                }
            } 
            else if($objShippingMethod->title == 'Ithinklogistics') // ithinklogistcs
            {
                $objCourierResponse = $this->ithinkService->handleShipping('getRate', $params);
                foreach(collect($objCourierResponse['data'])->sortBy('rate') as $intKey => $objShippingTrackData)
                {
                    $objTempData = [];
                    $objTempData['id'] = $objShippingTrackData['logistic_name'];
                    $objTempData['logistics_name'] = $objShippingTrackData['logistic_name'];
                    $objTempData['rate'] = $objShippingTrackData['rate'];
                    $objTempData['delivery_days'] = $objCourierResponse['expected_delivery_date'];
                    $objTempData['rating'] = "-";
                    $objResponse[] = $objTempData;
                }
            }
            return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.COURIER_GET_SUCCESSFULLY.code'),
                __('constants.messages.COURIER_GET_SUCCESSFULLY.msg'),
                $objResponse
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

    public function updateShippingDeliveredStatus(Request $request){
        try
        {
            abort_if(Gate::denies('btn_shipping_delivered_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
            $params = collect($request->all());
            if($this->updateDeliveredStatus($params['shipment_id']))
                {
                    $url = Route('admin.shippingproducts.index');
                    return $this->successResponse(
                    __('constants.SUCCESS_STATUS'),
                    __('constants.messages.DELIVERED_STATUS_SAVED_SUCCESSFULLY.code'),
                    __('constants.messages.DELIVERED_STATUS_SAVED_SUCCESSFULLY.msg'),
                    ['url' => $url]
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

     public function downloadInvoice($orderId)
    {
        try {
            $res = $this->orderInvoice($orderId);
            return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.INVOICE_DOWNLOAD_SUCCESSFULLY.code'),
                __('constants.messages.INVOICE_DOWNLOAD_SUCCESSFULLY.msg'),
                $res
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

    public function deleteInvoice($orderId){
        try{
            $client_id = Config::get('client_id');

            $objOrder = Order::select('order_nr')->where('id',$orderId)->first();
            $fileName = $objOrder->order_nr."-invoice.pdf";
            $url = Route('admin.orders.show', ['order' => $orderId]);
            if(File::exists(public_path('/storage/'.$client_id.'/invoice/'.$fileName)))
            {
                File::delete(public_path('/storage/'.$client_id.'/invoice/'.$fileName));
                return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.INVOICE_DELETED_SUCCESSFULLY.code'),
                __('constants.messages.INVOICE_DELETED_SUCCESSFULLY.msg'),
                $url
            );
            }
            else{
                return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.FILE_NOT_FOUND.code'),
                __('constants.messages.FILE_NOT_FOUND.msg'),
                $url
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

    public function saveCodPayment($shipment_id){
        try
        {
            $objShipment = Shipments::whereId($shipment_id)->first();
            if(!empty($objShipment)){
                $objShipment->final_payment_status = 'Paid';   
                $objShipment->save();   
            }   
            $url = Route('admin.shippingproducts.index');
            return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.COD_PAYMENT_SAVED_SUCCESSFULLY.code'),
                __('constants.messages.COD_PAYMENT_SAVED_SUCCESSFULLY.msg'),
                ['url' => $url],
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

    public function importShippingMethod(Request $request){
       try{
            abort_if(Gate::denies('shipping_product_import_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
            $params = collect($request->all());
            ini_set('safe_mode', 'Off');
            ini_set('memory_limit', '-1');
            set_time_limit(3000);

            $header = null;
            if ($request->input('media', false))
            {   
                if(($handle = fopen(storage_path('tmp/uploads/' . basename($request->input('media'))), "r")) !== false) 
                {
                    while (($row = fgetcsv($handle, 0, ',')) !== false){
                        if (!$header){
                            $header = $row;
                             if($params['importshippingtype'] == "Ithinklogistics")
                                {
                                    if($row[0] != 'AWB NO.'){
                                        return $this->successResponse(
                                            __('constants.SUCCESS_STATUS'),
                                            __('constants.messages.HEADER_MISSING.code'),
                                            __('constants.messages.HEADER_MISSING.msg'),
                                        );
                                    }
                                }
                        }
                        else 
                        {
                            //for chunk data
                            // $productData[] = array_combine($header, $row);
                            $data = array_combine($header, $row);
                            try 
                            {   
                                if($params['importshippingtype'] == "Ithinklogistics")
                                {
                                    $awb_no = trim($data['AWB NO.']);
                                    $objShipments = Shipments::where('awb_code',$awb_no)->update(['final_payment_status'=>'Paid']);
                                }

                            } catch(Exception $e) {
                                return $this->errorResponse(
                                    __('constants.ERROR_STATUS'),
                                    __('constants.errors.SOMETHING_WRONG.code'),
                                    __('constants.errors.SOMETHING_WRONG.msg'),
                                    $e->getMessage()
                                );
                            }
                        }
                    }
                    fclose($handle);
                    
                    return $this->successResponse(
                        __('constants.SUCCESS_STATUS'),
                        __('constants.messages.SHIPPING_IMPORT_SUCCESSFULLY.code'),
                        __('constants.messages.SHIPPING_IMPORT_SUCCESSFULLY.msg'),
                    );
                }
            } else {
                return $this->successResponse(
                    __('constants.SUCCESS_STATUS'),
                    __('constants.messages.PLEASE_IMPORT_FILE.code'),
                    __('constants.messages.PLEASE_IMPORT_FILE.msg'),
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

    public function shippingConfirmation(){
        $tempData = $mainData = $shippingAddress = [];
        $totalAmount = $total = $totalQuantity = 0; 
        $orderId = '13702380-a929-11ed-a189-bf4933ba9e84';
        $objOrder = Order::with('order_products','shipping_address')->select('id','order_nr','discount_code','discount_amount','sub_total','shipping_cost','taxes','created_at','shipping_address_id')->where('id',$orderId)->first();
        
        $objShipping = Shipping::with('shipping_products')->where('order_id',$orderId)->first();

        if(!empty($objShipping))
        {
            if($objShipping->shipping_products->isNotEmpty())
            {
                $shippingProductId = $objShipping->shipping_products->pluck('product_id')->toArray(); 
                $shippingProductVariantId = $objShipping->shipping_products->pluck('product_variant_options_id')->toArray();
                $objShippingProduct = ShippingProduct::where('order_id',$orderId)->whereIn('product_id',$shippingProductId)->get();
                $objSelectionProducts = Product::select('id','title','quantity','price','is_product_variant')
                        ->with([
                        'medias' => function($media){
                            $media->select('client_id','product_id','src'); 
                            }, 
                        ])->whereIn('id',$shippingProductId)
                          ->get();

                foreach($objShippingProduct as $shippingProduct)
                {
                    $orderProductId = $objOrder->order_products->filter(function($orderProductQuery) use($shippingProduct){
                            return $orderProductQuery->product_id == $shippingProduct->product_id && $orderProductQuery->product_variant_options_id == $shippingProduct->product_variant_options_id;
                                        })->first()->id;

                            $tempData['title'] = $shippingProduct->title;
                            $tempData['price'] = $shippingProduct->selling_price;
                            $tempData['quantity'] = $shippingProduct->quantity;
                            $tempData['trackOrderUrl'] = Route('orderdata',['order_id'=>$shippingProduct->order_id,'order_product_id'=>$orderProductId]);

                            $objSelectionProduct = $objSelectionProducts->filter(function($objSelectionProduct) use($shippingProduct){
                                        return $objSelectionProduct->id == $shippingProduct->product_id;
                                        })->first();
                            if(!empty($objSelectionProduct))
                            {
                                $tempData['img_src'] = (!empty($objSelectionProduct->medias[0])) ? config::get('app.url').$objSelectionProduct->medias[0]->image_src[2] : '';
                            }
                            $mainData[] = $tempData;
                }

                if(!empty($objOrder))
                {
                    $total = $objOrder->sub_total + $objOrder->shipping_cost + $objOrder->taxes -$objOrder->discount_amount;
                    $totalAmount = $total - $objOrder->discount_amount;
                    $totalQuantity = $objOrder->order_products->sum('quantity');

                    if(!empty($objOrder->shipping_address))
                    {
                        $shippingAddress = [
                            'address'     => $objOrder->shipping_address->address,
                            'address_2'   => $objOrder->shipping_address->address_2,
                            'city_name'   => $objOrder->shipping_address->city_name,
                            'state'       => $objOrder->shipping_address->state,
                            'country'     => $objOrder->shipping_address->short_code,
                            'postal_code' => $objOrder->shipping_address->postal_code,
                            'mobile'      => $objOrder->shipping_address->mobile
                        ];
                    }
                }

                $data = [
                         'user'            => $objShipping->user->name,
                         'email'           => $objShipping->user->email,
                         'mainData'        => $mainData,
                         'totalAmount'     => $totalAmount,
                         'shippingAddress' => $shippingAddress,
                         'orderDetails'    => $objOrder,
                         'adminContact'    => config('contactNo'),
                         'totalQuantity'    => $totalQuantity
                        ];
                        // dd($data);

                // $this->emailService->shippingConfirmation($data);
                // dd('Shipping Confirmation Mail Sent');
                // $this->emailService->outforDelivery($data);
                // dd('out for delivery mail sent');
                // $this->emailService->delivered($data);
                // dd('delivered mail sent');
                $this->emailService->shippingUpdate($data);
                dd('Shipping update mail sent');
            }
        } 
    }
}