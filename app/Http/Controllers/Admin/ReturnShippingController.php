<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use App\Services\ShiprocketService;
use App\Services\IthinklogisticsService;
use App\Http\Requests\MassDestroyReturnShippingProductRequest;
use App\Models\ReturnShipping;
use App\Models\ReturnShippingProduct;
use App\Models\Order;
use App\Models\Address;
use App\Models\Dimension;
use App\Models\Weightmanage;
use App\Models\ShippingMethod;
use App\Models\Courier;
use App\Models\Shipments;
use App\Models\User;
use App\Models\Product;
use App\Models\Shipping;
use App\Models\Country;
use App\Models\ReturnOrderLocation;
use App\Models\Payment;
use App\Models\Warehouse;
use App\Models\ReturnOrder;
use Gate;
use Config;

class ReturnShippingController extends Controller
{
    protected $shipService;
    protected $ithinkService;

    public function __construct()
    {
        $this->shipService = new ShiprocketService;
        $this->ithinkService = new IthinklogisticsService;
    }
    public function index(Request $request){
        abort_if(Gate::denies('return_shipping_product_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            try{
                    $query = ReturnShipping::with(['user','shipping_method'])->select(sprintf('%s.*', (new ReturnShipping())->table));
                    $table = Datatables::of($query);

                    $table->addColumn('actions', ' ');

                    $table->editColumn('id', function ($row) {
                        return $row->id ? $row->id : '';
                    });

                    $table->editColumn('return_shipment_order_number', function ($row) {
                        return $row->return_shipment_order_number ? $row->return_shipment_order_number : '';
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

                    $table->rawColumns(['actions','user','shipping_method']);
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
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['name' => trans('cruds.returnshippingproducts.title_singular')." ".trans('global.listing') ]];
        return view('admin.returnshippingproducts.index', compact('shippings','breadcrumbs'));
    }

    public function show($id){
       abort_if(Gate::denies('return_shipping_product_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
       $list = $data =  $breadcrumbs = [];
       $objReturnShipping = ReturnShipping::with('return_shipping_products')->where('id',$id)->first();
       $countries = Country::get();
       if(!empty($objReturnShipping ))
       {
           $list = [
            'btn_return_save_shipping_access' => !Gate::denies('btn_return_save_shipping_access'),
            'btn_return_save_shipping_order_access' => !Gate::denies('btn_return_save_shipping_order_access'),
            'btn_return_delete_shipping_access' => !Gate::denies('btn_return_delete_shipping_access'),
            'btn_return_cancel_shipping_access' => !Gate::denies('btn_return_cancel_shipping_access'),
            'btn_return_delete_product_action_access' => !Gate::denies('btn_return_delete_product_action_access'),
            'btn_return_generate_manifest_access' => !Gate::denies('btn_return_generate_manifest_access'),
            'btn_return_print_manifest_access' => !Gate::denies('btn_return_print_manifest_access'),
            'btn_return_generate_label_access' => !Gate::denies('btn_return_generate_label_access'),
            'btn_return_generate_invoice_access' => !Gate::denies('btn_return_generate_invoice_access'),
            'btn_return_pickup_access' => !Gate::denies('btn_return_pickup_access'),
            'btn_return_track_url_access' => !Gate::denies('btn_return_track_url_access'),
            'btn_return_shipping_delivered_access' => !Gate::denies('btn_return_shipping_delivered_access'),
            'countries' => $countries
           ];
           $objAddress = Address::where('store_status',1)->get();
           $list['addresses'] = $objAddress->pluck('location_name','id')->prepend(trans('global.pleaseSelect'),0);
           $list['defaultAddress'] = $objAddress->where('is_default',1)->first()->id;
           $list['dimensions'] = Dimension::get()->pluck('short_code','id')->prepend(trans('global.pleaseSelect'),0);
           $list['weightmanages'] = Weightmanage::get()->pluck('short_code','id')->prepend(trans('global.pleaseSelect'),0);
           $list['shippingmethods'] = ShippingMethod::get()->pluck('title','id');
           $list['couriers'] = Courier::get()->pluck('name','id');
           $list['pickup_location'] = Warehouse::where('shipping_method_id', array_key_first($list['shippingmethods']->toArray()))->pluck('pickup_code','pickup_id');
           $searchProductLimit = Config::get('SEARCH_PRODUCT_LIMIT');

            if($objReturnShipping->pickup_id > 0)
           {
            $list['pickup_location'] = Warehouse::where('shipping_method_id', $objReturnShipping->shipping_method_id)->pluck('pickup_code','pickup_id');
           }
           $objReturnShippingOrderDetailsProductId = $objReturnShipping->return_shipping_products->pluck('product_id')->toArray();
           $objReturnShippingOrderDetailsProductVariantId = $objReturnShipping->return_shipping_products->pluck('product_variant_options_id')->toArray();
           $objWeightManageDetails = $list['weightmanages']->toArray();
           $objDimensionDetails = $list['dimensions']->toArray();
           $objShipments = Shipments::where(['order_id'=>$objReturnShipping->order_id,'shipment_order_number'=>$objReturnShipping->return_shipment_order_number])->first();
           $foreachData = $objResponse = [];
           $objCourierResponse = $objReturnShipping->rate_data;
           $objCourierResponse = json_decode($objCourierResponse,true);
           if(!empty($objShipments && !empty($objCourierResponse)))
           {
                $objShippingMethod = ShippingMethod::whereId($objShipments->shipping_method_id)->first();
                if($objShippingMethod->title == 'ShipRocket') // shiprocket
                {
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
           $data['objCourierResponse'] = collect($objResponse)->sortBy('rate')->toArray();
            }
           else if(empty($objShipments))
           {
            $objShipments['pickup_status'] = 0;
            $objShipments['shipment_id'] = null;
           }
           $order = Order::with('shipping_address')->where('id', $objReturnShipping->order_id)->first();
           $objReturnOrder = ReturnOrder::where('order_id',$objReturnShipping->order_id)->first();
           $order_link = null;
           if($objReturnOrder){
            $order_link = Route('admin.returnorders.show', ['returnorder' => $objReturnOrder->id]); 
           }
           if($order){
            $shipping_address = $order->shipping_address;
           }
           $objReturnOrderLocation = ReturnOrderLocation::where('order_id', $objReturnShipping->order_id)->first();
           if(!empty($objReturnOrderLocation)){
            $shipping_address = $objReturnOrderLocation;
           }
           $shippingAddress = [];
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
                $shippingAddress['email'] =  $shipping_address->email;
                $shippingAddress['mobile'] =  $shipping_address->mobile;
                $shippingAddress['shortCode'] =  $shipping_address->short_code;
                $shippingAddress['postal_code'] =  $shipping_address->postal_code;
                $shippingAddress['phone_code'] =  $shipping_address->phone_code;
                $shippingAddress['status'] =  $shipping_address->status;
            }

           $objPayment = Payment::where('order_id',$objReturnShipping->order_id)->first();
            $data['payment_mode'] = 'COD';
           if(!empty($objPayment)){
                $data['payment_mode'] = 'Prepaid';
           }
            $data=[
                'shipping_address'=>$shippingAddress,
                'order'=>$order,
                'objReturnShipping'=>$objReturnShipping,
                'order_id'=>$objReturnShipping->order_id,
                'objShipments'=>$objShipments,
                'objReturnShippingOrderDetails'=>$objReturnShipping->return_shipping_products,
                'objCourierResponse'=>$objResponse,
                'order_link'=>$order_link
            ]; 
            $objSelectionProducts = Product::select('id','title','quantity','slug','price','compare_at_price','is_product_variant','description','sku','barcode','weight','weight_type_id','length','length_type_id','width','width_type_id','height','height_type_id','hs_code','is_product_charge','is_special_product','special_price','is_track','cost_per_item')
                ->with([
                'medias' => function($media){
                    $media->select('client_id','product_id','src');
                    }, 
                'product_variant_options' => function ($variant) use($objReturnShippingOrderDetailsProductVariantId) {
                        $variant->select('id','product_id','variant_option_1_id','variant_option_2_id','variant_option_3_id','price','compare_at_price','sku','barcode','weight','weight_type_id','length','length_type_id','width','width_type_id','height','height_type_id','hs_code','is_product_charge','is_special_product','special_price','is_track','cost_per_item');
                        $variant->whereIn('id', $objReturnShippingOrderDetailsProductVariantId);
                    }, 
                'product_variant_options.variant_media' => function ($variantmedia) {
                        $variantmedia->select('client_id','product_variant_id','src'); 
                },
                'product_variant_options.variant_option_1','product_variant_options.variant_option_2','product_variant_options.variant_option_3'
            ])->whereIn('id',$objReturnShippingOrderDetailsProductId)
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

                                $orderIndex = $objReturnShipping->return_shipping_products->search(function($objReturnShippingOrderDetail) use($objProductVariantOptions) {
                                return $objReturnShippingOrderDetail->product_id == $objProductVariantOptions->product_id && $objReturnShippingOrderDetail->product_variant_options_id == $objProductVariantOptions->id;

                                });
                                array_push($data['objSelectionProducts'],
                                [
                                    'id' => $objReturnShipping->return_shipping_products[$orderIndex]['id'],
                                    'product_variant_option_id' => $objReturnShipping->return_shipping_products[$orderIndex]['product_variant_options_id'],
                                    'product_id' => $objReturnShipping->return_shipping_products[$orderIndex]['product_id'],
                                    'title' => $title,
                                    'quantity' => $objReturnShipping->return_shipping_products[$orderIndex]['quantity'],
                                    'slug' => $slug,
                                    'price' => $objReturnShipping->return_shipping_products[$orderIndex]['selling_price'],
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
                                    'sku'=>$objReturnShipping->return_shipping_products[$orderIndex]['sku'],
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
                        $orderIndex = $objReturnShipping->return_shipping_products->search(function($objReturnShippingOrderDetail) use($objSelectionProduct) {
                            return $objReturnShippingOrderDetail->product_id == $objSelectionProduct->id;
                        });
                        array_push($data['objSelectionProducts'],
                        [
                            'id' => $objReturnShipping->return_shipping_products[$orderIndex]['id'],
                            'product_variant_option_id' => $objReturnShipping->return_shipping_products[$orderIndex]['product_variant_options_id'],
                            'product_id' => $objReturnShipping->return_shipping_products[$orderIndex]['product_id'],
                            'title' => $title,
                            'quantity' => $objReturnShipping->return_shipping_products[$orderIndex]['quantity'],
                            'slug' => $slug,
                            'price' => $objReturnShipping->return_shipping_products[$orderIndex]['selling_price'],
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
                            'sku'=>$objReturnShipping->return_shipping_products[$orderIndex]['sku'],
                            'hs_code'=> $objSelectionProduct->hs_code,
                            'is_product_charge'=> $objSelectionProduct->is_product_charge,
                            'is_special_product'=> $objSelectionProduct->is_special_product,
                            'special_price'=> $objSelectionProduct->special_price,
                            'is_track'=> $objSelectionProduct->is_track,
                        ]);
                    }
                }
                $data['objSelectionProducts'] = collect($data['objSelectionProducts'])->sortBy('id')->values()->toArray();
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => 'Home'], ['link'=>route('admin.returnshippingproducts.index'),'name' => trans('cruds.returnshippingproducts.title') .' '.trans('global.listing')], ['name' => trans('global.show') .' '.trans('cruds.returnshippingproducts.title_singular') ]];
    }
        return view('admin.returnshippingproducts.show', compact('breadcrumbs','list','data'));    
 }

    public function saveReturnShippingOrder(Request $request){
        try{
           abort_if(Gate::denies('btn_return_shipping_order_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
            $params = collect($request->all());
            $url = route('admin.returnshippingproducts.index');
            $objOrder = Order::whereId($params['order_id'])->latest()->first();
            if(empty($objOrder)){
                     return $this->successResponse(
                    __('constants.SUCCESS_STATUS'),
                    __('constants.messages.RETURN_SHIPPED_EXCHANGE_CANCELED.code'),
                    __('constants.messages.RETURN_SHIPPED_EXCHANGE_CANCELED.msg'),
                      ['url'=>$url]
                    );
            }
            $required = ['order_id', 'user_id','objFinalSelectionProducts'];
            $this->validateRequiredParams($required,$params->keys()->toArray());

            $data['shipping_product'] = false;
                $objShipments = Shipments::select('id')->where('order_id',$params['order_id'])->first();
                $objOrder = Order::where('id',$params['order_id'])->first();
                if(!empty($objOrder)){
                    $objOrder->admin_approve = 1;
                    $objOrder->save();
                }
                $objReturnShipping = new ReturnShipping;
                $objReturnShipping->order_id = $params['order_id'];
                $objReturnShipping->user_id = $params['user_id'];
                $objReturnShipping->save();    
            
            foreach($params['objFinalSelectionProducts'] as $selectionProducts){
                $objReturnShippingProduct = new ReturnShippingProduct;
                $objReturnShippingProduct->return_shipping_id = $objReturnShipping->id;
                $objReturnShippingProduct->order_id = $params['order_id'];
                $objReturnShippingProduct->product_id = $selectionProducts['product_id'];
                $objReturnShippingProduct->product_variant_options_id = $selectionProducts['product_variant_option_id'];
                $objReturnShippingProduct->title = trim($selectionProducts['title']);
                $objReturnShippingProduct->quantity = $selectionProducts['quantity'];
                $objReturnShippingProduct->selling_price = $selectionProducts['price'];
                $objReturnShippingProduct->sku = trim($selectionProducts['sku']);
                $objReturnShippingProduct->save();
            }
            if($request->ajax()){
                $url = route('admin.returnshippingproducts.show', ['returnshippingproduct' => $objReturnShipping->id]);
                return $this->successResponse(
                        __('constants.SUCCESS_STATUS'),
                        __('constants.messages.SHIPPING_PRODUCT_RETURN_SUCCESSFULLY.code'),
                        __('constants.messages.SHIPPING_PRODUCT_RETURN_SUCCESSFULLY.msg'),
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

    public function saveReturnShippingProduct(Request $request){
       try{
            abort_if(Gate::denies('btn_return_save_shipping_order_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
            $params = collect($request->all());
            $url = route('admin.returnshippingproducts.index');
            $objOrder = Order::whereId($params['order_id'])->latest()->first();
            if(empty($objOrder)){
                     return $this->successResponse(
                    __('constants.SUCCESS_STATUS'),
                    __('constants.messages.RETURN_SHIPPED_EXCHANGE_CANCELED.code'),
                    __('constants.messages.RETURN_SHIPPED_EXCHANGE_CANCELED.msg'),
                      ['url'=>$url]
                    );
            }
            $objReturnShipping = ReturnShipping::where('id',$params['id'])->first();
                $orderNumber = $this->getReturnShippingOrderNumber();
                if(empty($objReturnShipping)){
                    $objReturnShipping = new ReturnShipping;
                }
                $objReturnShipping->shipment_id = $params['shipment_id'];
                $objReturnShipping->return_shipment_order_number = $orderNumber;
                $objReturnShipping->pickup_id = $params['pickup_id'];
                $objReturnShipping->title = trim($params['title']);
                $objReturnShipping->quantity = $params['quantity'];
                $objReturnShipping->selling_price = $params['selling_price'];
                $objReturnShipping->tax = $params['tax'];
                $objReturnShipping->shipping_charges = $params['shipping_charges'];
                $objReturnShipping->transaction_charges = $params['transaction_charges'];
                $objReturnShipping->total_discount = $params['total_discount'];
                $objReturnShipping->sub_total = $params['sub_total'];        
                $objReturnShipping->weight_type_id = $params['weight_type_id'];
                $objReturnShipping->weight = $params['weight'];
                $objReturnShipping->length_type_id = $params['length_type_id'];
                $objReturnShipping->length = $params['length'];
                $objReturnShipping->width_type_id = $params['width_type_id'];
                $objReturnShipping->width = $params['width'];
                $objReturnShipping->height_type_id = $params['height_type_id'];
                $objReturnShipping->height = $params['height'];
                $objReturnShipping->shipping_method_id = $params['shipping_method_id'];
                $objReturnShipping->courier_id = $params['courier_id'];
                $objReturnShipping->save();

            if ($request->ajax()) {
                if($params['shippingStatus'] == 1){
                    $objShippingMethod = ShippingMethod::whereId($objReturnShipping->shipping_method_id)->first();
                    if($objShippingMethod->title == 'ShipRocket')
                    {
                        $this->shipService->handleShipping('setReturnOrder', $objReturnShipping->id);
                    }
                    elseif($objShippingMethod->title == 'Ithinklogistics')
                    {
                        $this->ithinkService->handleShipping('setReturnOrder', $objReturnShipping->id);
                    }   
                    $objReturnShipping->admin_approve = 1;
                    $objReturnShipping->save();
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

    public function deleteReturnShippingOrder($id){
        try{
            abort_if(Gate::denies('btn_return_delete_shipping_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
            ReturnShippingProduct::where('return_shipping_id',$id)->delete();
            ReturnShipping::where('id',$id)->delete();
            if ($request->ajax()) {
                $url = route('admin.returnshippingproducts.index');
                return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.RETURN_SHIPPING_ORDER_DELETED_SUCCESSFULLY.code'),
                __('constants.messages.RETURN_SHIPPING_ORDER_DELETED_SUCCESSFULLY.msg'),
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

    public function handleReturnShippingActions(Request $request){
        try{
            $params = collect($request->all());
            $url = null;

            $objReturnShipping = ReturnShipping::whereId($params['shipping_id'])->first();
            if(!empty($objReturnShipping))
            {
                $objShippingMethod = ShippingMethod::whereId($objReturnShipping->shipping_method_id)->first();
                $objShipments = Shipments::where('shipment_order_number', $objReturnShipping->return_shipment_order_number)->first();
                if($params['status'] == 'generate_manifest_url'){
                    if($objShippingMethod->title == 'ShipRocket')
                    {
                        $this->shipService->handleShipping('generateManifest', [$objShipments->shipment_id]);
                    }
                    $objShipments = Shipments::where('shipment_order_number', $objReturnShipping->return_shipment_order_number)->first();
                    $url = $objShipments->generate_manifest_url;
                }elseif($params['status'] == 'print_manifest_url'){
                   if($objShippingMethod->title == 'ShipRocket')
                    {
                        $this->shipService->handleShipping('printManifest', [$objShipments->shipment_order_id]);
                    }
                    $objShipments = Shipments::where('shipment_order_number', $objReturnShipping->return_shipment_order_number)->first();
                    $url = $objShipments->print_manifest_url;
                }elseif($params['status'] == 'label_url'){
                    $objShipments = Shipments::where('shipment_order_number', $objReturnShipping->return_shipment_order_number)->first();
                    if($objShippingMethod->title == 'ShipRocket')
                    {
                        $this->shipService->handleShipping('generateLabel', [$objShipments->shipment_id]);
                    }
                    elseif($objShippingMethod->title == 'Ithinklogistics')
                    {
                        $this->ithinkService->handleShipping('generateLabel', [$objShipments->shipment_id]);
                    }
                    $objNewShipments = Shipments::where('shipment_order_number', $objReturnShipping->return_shipment_order_number)->first();
                    $url = $objNewShipments->label_url;
                } elseif($params['status'] == 'invoice_url'){
                   $objShipments = Shipments::where('shipment_order_number', $objReturnShipping->return_shipment_order_number)->first();
                    if($objShippingMethod->title == 'ShipRocket')
                    {
                        $this->shipService->handleShipping('printInvoiceManifest', [$objShipments->shipment_order_id]);
                    }
                    elseif($objShippingMethod->title == 'Ithinklogistics')
                    {
                        $this->ithinkService->handleShipping('printInvoiceManifest', [$objShipments->shipment_id]);
                    }
                    $objNewShipments = Shipments::where('shipment_order_number', $objReturnShipping->return_shipment_order_number)->first();
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
        return view('admin.returnshippingproducts.index');
    }

    public function deleteReturnShippingOrderProduct(Request $request,$id){
        try{
             abort_if(Gate::denies('btn_return_delete_product_action_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
            $objReturnShippingProduct = ReturnShippingProduct::where('id',$id)->first();
            $objReturnShippingId = $objReturnShippingProduct->return_shipping_id;
            $objReturnShippingProduct->delete();
            $returnshipProductCount = ReturnShippingProduct::where('return_shipping_id',$objReturnShippingId)->count();
            if($returnshipProductCount == 0){
               ReturnShipping::where('id',$objReturnShippingId)->delete();
            }
            if ($request->ajax()) {
                $url = route('admin.returnshippingproducts.index'); 
                return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.RETURN_SHIPPING_ORDERPRODUCT_CANCELLED_SUCCESSFULLY.code'),
                __('constants.messages.RETURN_SHIPPING_ORDERPRODUCT_CANCELLED_SUCCESSFULLY.msg'),
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

     public function getReturnShippingOrderNumber(){
        $latestOrder = ReturnShipping::where('return_shipment_order_number','!=',null)->orderBy('return_shipment_order_number','DESC')->withTrashed()->first();
            if(!empty($latestOrder)){
                $orderNumber = $latestOrder->return_shipment_order_number;
                $orderNumber = $orderNumber + 1;
            } else {
                $orderNumber = Config::get('return_shipment_order_number');
            }

        return $orderNumber;
    }

     public function updateShippingAddress(Request $request)
    {
        try{
            $params = collect($request->all());
            $required = ['first_name', 'last_name','address','address_2','mobile','phone_code','country_id','state_id','city_name','postal_code'];
            $this->validateRequiredParams($required,$params->keys()->toArray());

            if(isset($params['id'])){ // check for address id
                $address = ReturnOrderLocation::where('order_id',$params['order_id'])->first();
                if(empty($address)){
                    $address = new ReturnOrderLocation;
                }
            }
            $address->order_id = $params['order_id'];
            $address->user_id = $params['user_id'];
            $address->first_name = $params['first_name'];
            $address->last_name = $params['last_name'];
            $address->email = $params['email'];
            $address->address = $params['address'];
            $address->address_2 = $params['address_2'];
            $address->phone_code = $params['phone_code'];
            $address->mobile =$params['mobile'];
            $address->status =$params['status'];
            $address->postal_code = $params['postal_code'];
            $address->country_id =$params['country_id'];
            $address->state_id = $params['state_id'];
            $address->city_name = $params['city_name'];
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

    public function cancelReturnShipping($return_shipping_id){
        try{
             abort_if(Gate::denies('btn_return_cancel_shipping_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
            $objReturnShipping = ReturnShipping::with('return_shipping_products')->whereId($return_shipping_id)->first();
            if(!empty($objReturnShipping))
            {
                $objReturnShippingMethod = ShippingMethod::whereId($objReturnShipping->shipping_method_id)->first();
                $objShipments = Shipments::where('shipment_order_number', $objReturnShipping->return_shipment_order_number)->first();
                if($objReturnShippingMethod->title == 'ShipRocket')
                {
                $this->shipService->handleShipping('cancelShipping', [$objShipments->shipment_order_id]);
                }
                elseif($objReturnShippingMethod->title == 'Ithinklogistics')
                {
                    $this->ithinkService->handleShipping('cancelShipping', [$objShipments->shipment_id]);
                }
            }
            $objReturnShipping->return_shipping_products()->delete();
            $objReturnShipping->delete();
            return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.SHIPPING_CANCEL_SUCCESSFULLY.code'),
                __('constants.messages.SHIPPING_CANCEL_SUCCESSFULLY.msg'),
                route('admin.returnshippingproducts.index')
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

    public function returnPickup($return_shipping_id){
        try{
             abort_if(Gate::denies('btn_return_pickup_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
            $objReturnShipping = ReturnShipping::whereId($return_shipping_id)->first();
            $objReturnShippingMethod = ShippingMethod::whereId($objReturnShipping->shipping_method_id)->first();
            $objShipments = Shipments::where('shipment_order_number', $objReturnShipping->return_shipment_order_number)->first();
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

    public function destroy($id)
    {
        try {
              abort_if(Gate::denies('return_shipping_product_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
              ReturnShippingProduct::where('return_shipping_id',$id)->delete();
              ReturnShipping::where('id',$id)->delete();   
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
            __('constants.messages.RETURN_SHIPPING_ORDER_DELETED_SUCCESSFULLY.code'),
            __('constants.messages.RETURN_SHIPPING_ORDER_DELETED_SUCCESSFULLY.msg'),
        );
    }

    public function massDestroy(MassDestroyReturnShippingProductRequest $request)
    {
        try {
              ReturnShippingProduct::whereIn('return_shipping_id',request('ids'))->delete();
              ReturnShipping::whereIn('id', request('ids'))->delete(); 
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
            __('constants.messages.RETURN_SHIPPING_ORDERS_DELETE_SUCCESSFULLY.code'),
            __('constants.messages.RETURN_SHIPPING_ORDERS_DELETE_SUCCESSFULLY.msg'),
        );
    }


    public function updateReturnShippingDeliveredStatus(Request $request){
        try
        {
            abort_if(Gate::denies('btn_return_shipping_delivered_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
            $params = collect($request->all());
            if($this->updateDeliveredStatus($params['return_shipment_id']))
                {
                    $url = Route('admin.returnshippingproducts.index');
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
}