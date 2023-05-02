<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\MassDestroyExchangeOrderRequest;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\Country;
use App\Models\Dimension;
use App\Models\Weightmanage;
use App\Models\ReturnOrder;
use App\Models\ReturnOrderProduct;
use App\Models\ReturnShipping;
use App\Models\ReturnShippingProduct;
use App\Models\ExchangeMedium;
use Gate;
use Config;
use Auth;


class ExchangeOrderController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('exchangeorders_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        if ($request->ajax()) {
            try{

                    $query = Order::with(['user'])->select(sprintf('%s.*', (new Order())->table))->where('parent_order_id','!=',null);
                    $table = Datatables::of($query);

                    $table->addColumn('actions', ' ');

                    $table->editColumn('id', function ($row) {
                        return $row->id ? $row->id : '';
                    });

                    $table->editColumn('order_nr', function ($row) {
                        return $row->order_nr ? (int) $row->order_nr : '';
                    });

                    $table->addColumn('email', function ($row) {
                        return $row->user ? $row->user->email : '';
                    });

                    $table->addColumn('mobile', function ($row) {
                        return $row->user ? $row->user->mobile : '';
                    }); 

                    $table->editColumn('admin_approve', function ($row) {
                        return $row->admin_approve ? $row->admin_approve : '';
                    });

                    $table->rawColumns(['actions', 'user']);
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
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['name' => trans('cruds.exchangeorders.title_singular')." ".trans('global.listing') ]];
        return view('admin.exchangeorders.index', compact('breadcrumbs'));
    }

    public function show($id){
        abort_if(Gate::denies('exchangeorders_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $list = $data = [];
        $searchProductLimit = Config::get('SEARCH_PRODUCT_LIMIT');
        $objReturnOrder = ReturnOrder::where('order_id',$id)->first();
        if($objReturnOrder)
        {
            $objReturnOrderDetails = ReturnOrderProduct::where('return_order_id',$objReturnOrder->id)->get();
            $objReturnShippings = ReturnShipping::where('order_id',$objReturnOrder->order_id)->get();
            $objReturnOrderDetailsProductId = $objReturnOrderDetails->pluck('product_id')->toArray();
            $objReturnOrderDetailsProductVariantId = $objReturnOrderDetails->pluck('product_variant_options_id')->toArray();
            $data['returnOrderId'] = $objReturnOrder->id;
            $data['objReturnShipping'] = $objReturnShippings;

        $objReturnShippingProductId = $objReturnShippingProductVariantId = null;
        $data['shipping_link'] = '';
        if(!empty($objReturnShippings)){
            $objReturnShippingProduct = ReturnShippingProduct::where('order_id',$objReturnOrder->order_id)->get();
            $data['objReturnShippingProduct'] = $objReturnShippingProduct;
            $objReturnShippingProductId = $objReturnShippingProduct->pluck('product_id')->toArray();
            $objReturnShippingProductVariantId = $objReturnShippingProduct->pluck('product_variant_options_id')->toArray();
            $data['objReturnShippingProduct'] = $objReturnShippingProduct;
            $data['shipping_link'] = [];
            foreach($objReturnShippings as $intkey => $objReturnShipping){
                $data['shipping_link'][] = Route('admin.returnshippingproducts.show', ['returnshippingproduct' => $objReturnShipping->id]);
            }
        }
        $countries = Country::get();
        $order = Order::with('shipping_address', 'billing_address')->where('id', $id)->first();
        $shipping_address = $order->shipping_address;
        $billing_address = $order->billing_address;
        $shippingAddress = [];
        $billingAddress = [];
         $list = [
            'countries' => $countries, 
            'btn_exchange_shipping_order_access' => !Gate::denies('btn_exchange_shipping_order_access'), 
            'btn_exchange_delete_order_access' => !Gate::denies('btn_exchange_delete_order_access'), 
            'btn_exchange_delete_action_access' => !Gate::denies('btn_exchange_delete_action_access'), 
            'btn_exchange_shipping_action_access' => !Gate::denies('btn_exchange_shipping_action_access'), 
        ];

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
            'shipping_address' => $shippingAddress,
            'billing_address' => $billing_address,
            'shipping_link' => $data['shipping_link'],
        ];
        $list['dimensions'] = Dimension::get()->pluck('short_code','id')->prepend(trans('global.pleaseSelect'));
        $list['weightmanages'] = Weightmanage::get()->pluck('short_code','id')->prepend(trans('global.pleaseSelect'));
        $objWeightManageDetails = $list['weightmanages']->toArray();
        $objDimensionDetails = $list['dimensions']->toArray();
        $objSelectionProducts = Product::select('id','title','quantity','slug','price','compare_at_price','is_product_variant','description','sku','barcode','weight','weight_type_id','length','length_type_id','width','width_type_id','height','height_type_id','hs_code','is_product_charge','is_special_product','special_price','is_track','cost_per_item')
                ->with([
                'medias' => function($media){
                    $media->select('client_id','product_id','src');
                    }, 
                'product_variant_options' => function ($variant) use($objReturnOrderDetailsProductVariantId) {
                        $variant->select('id','product_id','variant_option_1_id','variant_option_2_id','variant_option_3_id','price','compare_at_price','sku','barcode','weight','weight_type_id','length','length_type_id','width','width_type_id','height','height_type_id','hs_code','is_product_charge','is_special_product','special_price','is_track','cost_per_item');
                        $variant->whereIn('id', $objReturnOrderDetailsProductVariantId);
                    }, 
                'product_variant_options.variant_media' => function ($variantmedia) {
                        $variantmedia->select('client_id','product_variant_id','src'); 
                },
                'product_variant_options.variant_option_1','product_variant_options.variant_option_2','product_variant_options.variant_option_3'
            ])->whereIn('id',$objReturnOrderDetailsProductId)
                ->limit($searchProductLimit)
                ->get();

            $data['objSelectionProducts'] = [];
                foreach($objSelectionProducts as $objSelectionProduct)
                {
                    $title = $objSelectionProduct->title;
                    $slug = $objSelectionProduct->slug;
                    $img_src = (!empty($objSelectionProduct->medias[0])) ? $objSelectionProduct->medias[0]->image_src['2'] : '';
                    if($objSelectionProduct->is_product_variant){

                        if($objSelectionProduct->product_variant_options->isNotEmpty())
                        {
                            foreach($objSelectionProduct->product_variant_options as $objProductVariantOptions)
                            {   
                                $variantOption = $this->getVariantOptions($objProductVariantOptions->variant_option_1,$objProductVariantOptions->variant_option_2,$objProductVariantOptions->variant_option_3);
                                $title = $title. " (" .$variantOption.")";
                                $img_src = (!empty($objProductVariantOptions->variant_media[0])) ? $objProductVariantOptions->variant_media[0]->image_src[2] : $img_src;

                                $orderIndex = $objReturnOrderDetails->search(function($objReturnOrderDetail) use($objProductVariantOptions) {
                                return $objReturnOrderDetail->product_id == $objProductVariantOptions->product_id && $objReturnOrderDetail->product_variant_options_id == $objProductVariantOptions->id;

                                });
                                $boolShipping = false;
                                if(!empty($objReturnShippingProductVariantId)){
                                    if(in_array($objProductVariantOptions->id,$objReturnShippingProductVariantId)){
                                        $boolShipping = true;
                                    }
                                }
                                if(!empty($objReturnShippingProduct))
                                {
                                    $objReturnShipping = $objReturnShippingProduct->filter(function($returnShippingProduct) use($objProductVariantOptions){
                                    return $returnShippingProduct->product_id == $objProductVariantOptions->product_id && $returnShippingProduct->product_variant_options_id == $objProductVariantOptions->id;
                                    })->first();
                                    $intReturnShippingId = null;
                                    if(!empty($objReturnShipping)){
                                        $intReturnShippingId =  Route('admin.returnshippingproducts.show', ['returnshippingproduct' => $objReturnShipping->return_shipping_id]);
                                    }
                                }
                                array_push($data['objSelectionProducts'],
                                [
                                    'id' => $objReturnOrderDetails[$orderIndex]['id'],
                                    'product_variant_option_id' => $objReturnOrderDetails[$orderIndex]['product_variant_options_id'],
                                    'product_id' => $objReturnOrderDetails[$orderIndex]['product_id'],
                                    'title' => $title,
                                    'quantity' => $objReturnOrderDetails[$orderIndex]['quantity'],
                                    'slug' => $slug,
                                    'price' => $objProductVariantOptions->price,
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
                                    'sku'=>$objProductVariantOptions->sku,
                                    'hs_code'=> $objProductVariantOptions->hs_code,
                                    'is_product_charge'=> $objProductVariantOptions->is_product_charge,
                                    'is_special_product'=> $objProductVariantOptions->is_special_product,
                                    'special_price'=> $objProductVariantOptions->special_price,
                                    'is_track'=> $objProductVariantOptions->is_track,
                                    'isChecked' => $boolShipping,
                                    'isShipping'=>$boolShipping,
                                    'shipping_link'=> $intReturnShippingId,
                                    'is_approve'=> $objReturnOrderDetails[$orderIndex]['admin_approve']
                                ]);
                            }        
                        }
                    }
                    else
                    {
                        $orderIndex = $objReturnOrderDetails->search(function($objReturnOrderDetail) use($objSelectionProduct) {
                            return $objReturnOrderDetail->product_id == $objSelectionProduct->id;
                        });
                        $boolShipping = false;
                        if(!empty($objReturnShippingProductId)){
                            if(in_array($objSelectionProduct['id'],$objReturnShippingProductId)){
                                $boolShipping = true;
                            }
                        }
                        if(!empty($objReturnShippingProduct));
                        {
                            $objReturnShipping = $objReturnShippingProduct->filter(function($returnShippingProduct) use($objSelectionProduct){
                            return $returnShippingProduct->product_id == $objSelectionProduct->id;
                            })->first();
                            $intReturnShippingId = null;
                            if(!empty($objReturnShipping)){
                                $intReturnShippingId = Route('admin.returnshippingproducts.show', ['returnshippingproduct' => $objReturnShipping->return_shipping_id]);

                            }
                        }
                        array_push($data['objSelectionProducts'],
                        [
                            'id' => $objReturnOrderDetails[$orderIndex]['id'],
                            'product_variant_option_id' => $objReturnOrderDetails[$orderIndex]['product_variant_options_id'],
                            'product_id' => $objReturnOrderDetails[$orderIndex]['product_id'],
                            'title' => $title,
                            'quantity' => $objReturnOrderDetails[$orderIndex]['quantity'],
                            'slug' => $slug,
                            'price' => $objSelectionProduct->price,
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
                            'sku'=>$objSelectionProduct->sku,
                            'hs_code'=> $objSelectionProduct->hs_code,
                            'is_product_charge'=> $objSelectionProduct->is_product_charge,
                            'is_special_product'=> $objSelectionProduct->is_special_product,
                            'special_price'=> $objSelectionProduct->special_price,
                            'is_track'=> $objSelectionProduct->is_track,
                            'isChecked' => $boolShipping,
                            'isShipping'=>$boolShipping,
                            'shipping_link'=> $intReturnShippingId,
                            'is_approve'=> $objReturnOrderDetails[$orderIndex]['admin_approve']
                        ]);
                    }
                }
        $data['objSelectionProducts'] = collect($data['objSelectionProducts'])->sortBy('id')->values()->toArray();
    }
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => 'Home'], ['link'=>route('admin.exchangeorders.index'),'name' => trans('cruds.exchangeorders.exchange_product') .' '.trans('global.listing')], ['name' => trans('global.show') .' '.trans('cruds.exchangeorders.exchange_product') ]];
        return view('admin.exchangeorders.show', compact('breadcrumbs','data','list'));    
     }

     public function ExchangeOrders($id)
     {
        abort_if(Gate::denies('return_exchangeorders'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $list = $data = [];
        $objExchangeOrderDetails = Order::where(['id'=>$id])->where('parent_order_id','!=','null')->first();
        if(!empty($objExchangeOrderDetails)){
            $objOrderDetails = $orderProduct = $descriptionData = [];
            $data['orderId'] = $id;
            $objExchangeOrderProductDetails = OrderProduct::where(['order_id'=>$objExchangeOrderDetails->id])->first();
            $objWeightManageDetails = Weightmanage::pluck('short_code','id')->toArray();
            $objDimensionDetails = Dimension::pluck('short_code','id')->toArray();
            $objReturnOrder = ReturnOrder::where('order_id',$id)->first();

            if(!empty($objReturnOrder)){
                $objReturnOrderProduct = ReturnOrderProduct::where('return_order_id',$objReturnOrder->id)->first();
                $objReturnShippingProduct = ReturnShippingProduct::where('order_id',$id)->first();
                $data['isShipping'] = false;
                if(!empty($objReturnShippingProduct)){
                    $data['isShipping'] = true;
                }
            }
            $searchProductLimit = Config::get('SEARCH_PRODUCT_LIMIT');
            $objExchangeOrderProductVariantId = $objExchangeOrderProductDetails->product_variant_options_id;
            $mainOrderProduct = OrderProduct::select('id','product_id','product_variant_options_id')->where(['order_id' => $objExchangeOrderDetails->parent_order_id , 'product_id' => $objExchangeOrderProductDetails->product_id , 'product_variant_options_id' => $objExchangeOrderProductDetails->product_variant_options_id])->first();
            $orderProduct['descriptionData'] = $this->getDescriptionData($objExchangeOrderDetails->parent_order_id,$mainOrderProduct->id,$mainOrderProduct->product_id,$mainOrderProduct->product_variant_options_id);
            $objExchangeMedia = ExchangeMedium::where('order_id',$objExchangeOrderDetails->id)->get();
                $objExchangeMediaSrc = [];
                $client_id = Config::get('client_id');
                foreach($objExchangeMedia as $exchangeMedia){
                    $tempExchangeMedia = [];
                    $tempExchangeMedia['img_src'] = (!empty($exchangeMedia->src)) ? $exchangeMedia->src : '';
                    $tempExchangeMedia['id'] = $exchangeMedia->id;
                    $objExchangeMediaSrc[] = $tempExchangeMedia;
                }

            $objSelectionProducts = Product::select('id','title','quantity','slug','price','compare_at_price','is_product_variant','description','sku','barcode','weight','weight_type_id','length','length_type_id','width','width_type_id','height','height_type_id','hs_code','is_product_charge','is_special_product','special_price','is_track','cost_per_item')
                    ->with([
                    'medias' => function($media){
                        $media->select('client_id','product_id','src');
                        }, 
                    'product_variant_options' => function ($variant) use($objExchangeOrderProductVariantId) {
                            $variant->select('id','product_id','variant_option_1_id','variant_option_2_id','variant_option_3_id','price','compare_at_price','sku','barcode','weight','weight_type_id','length','length_type_id','width','width_type_id','height','height_type_id','hs_code','is_product_charge','is_special_product','special_price','is_track','cost_per_item');
                            $variant->where('id', $objExchangeOrderProductVariantId);
                        }, 
                    'product_variant_options.variant_media' => function ($variantmedia) {
                            $variantmedia->select('client_id','product_variant_id','src'); 
                    },
                    'product_variant_options.variant_option_1','product_variant_options.variant_option_2','product_variant_options.variant_option_3'
                ])->where('id',$objExchangeOrderProductDetails->product_id)
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
                            //Variant Product
                            $descriptionData = [];
                            $exchangequantity = $exchangeApproveQuantity = $exchangeTotal = 0;
                            if(!empty($objReturnOrderProduct)){
                                $exchangeApproveQuantity = $objExchangeOrderProductDetails->admin_approve_quantity;
                                $exchangeTotal = $objReturnOrderProduct->quantity * $objExchangeOrderProductDetails->price;
                            }

                            array_push($data['objSelectionProducts'],
                            [
                                'id' => $objExchangeOrderProductDetails->id,
                                'product_variant_option_id' => $objExchangeOrderProductDetails->product_variant_options_id,
                                'product_id' => $objExchangeOrderProductDetails->product_id,
                                'title' => $objExchangeOrderProductDetails->title,
                                'quantity' => $objExchangeOrderProductDetails->quantity,
                                'exchangeApproveQuantity' => $exchangeApproveQuantity,
                                'exchangeTotal' => $exchangeTotal,
                                'slug' => $objExchangeOrderProductDetails->slug,
                                'price' => $objExchangeOrderProductDetails->price,
                                'img_src'=>$img_src,
                                'sku'=> $objExchangeOrderProductDetails->sku,
                                'weight'=> $objExchangeOrderProductDetails->weight,
                                'weight_type_id' => $objExchangeOrderProductDetails->weight_type_id,
                                'weight_type' => ($objExchangeOrderProductDetails->weight_type_id > 0) ? $objWeightManageDetails[$objExchangeOrderProductDetails->weight_type_id] : null,
                                'width'=> $objExchangeOrderProductDetails->width,
                                'width_type_id' => $objExchangeOrderProductDetails->width_type_id,
                                'dimension_width_type' => ($objExchangeOrderProductDetails->width_type_id > 0) ? $objDimensionDetails[$objExchangeOrderProductDetails->width_type_id] : null,
                                'height'=> $objExchangeOrderProductDetails->height,
                                'height_type_id' => $objExchangeOrderProductDetails->height_type_id,
                                'dimension_height_type' => ($objExchangeOrderProductDetails->height_type_id > 0) ? $objDimensionDetails[$objExchangeOrderProductDetails->height_type_id] : null,
                                'length'=> $objExchangeOrderProductDetails->length,
                                'length_type_id' => $objExchangeOrderProductDetails->length_type_id,
                                'dimension_length_type' => ($objExchangeOrderProductDetails->length_type_id > 0) ? $objDimensionDetails[$objExchangeOrderProductDetails->length_type_id] : null,
                                'compareprice' => $objProductVariantOptions->compare_at_price,
                                'stock_status'=>$objSelectionProduct['stock_status'],
                                'cost_per_item'=>$objExchangeOrderProductDetails->cost_per_item,
                                'description'=> $orderProduct['descriptionData'],
                                'is_approve'=> (!empty($objReturnOrderProduct)) ? $objReturnOrderProduct->admin_approve : 0,
                                'latest_description'=> (!empty($objReturnOrderProduct)) ? $objReturnOrderProduct->description : null,
                                'note'=> $objExchangeOrderDetails->note,
                            ]);
                        }
                    }
                }
                else
                {
                //NonVariant Product
                 $descriptionData = [];
                 $exchangequantity = $exchangeApproveQuantity = $exchangeTotal = 0;
                    if(!empty($objReturnOrderProduct)){
                        $exchangeApproveQuantity = $objExchangeOrderProductDetails->admin_approve_quantity;
                        $exchangeTotal = $objReturnOrderProduct->quantity * $objExchangeOrderProductDetails->price;
                    }

                    array_push($data['objSelectionProducts'],
                    [
                        'id' => $objExchangeOrderProductDetails->id,
                        'product_variant_option_id' => $objExchangeOrderProductDetails->product_variant_options_id,
                        'product_id' => $objExchangeOrderProductDetails->product_id,
                        'title' => $objExchangeOrderProductDetails->title,
                        'quantity' => $objExchangeOrderProductDetails->quantity,
                        'exchangeApproveQuantity' => $exchangeApproveQuantity,
                        'exchangeTotal' => $exchangeTotal,
                        'slug' => $objExchangeOrderProductDetails->slug,
                        'price' => $objExchangeOrderProductDetails->price,
                        'img_src'=>$img_src,
                        'sku'=> $objExchangeOrderProductDetails->sku,
                        'weight'=> $objExchangeOrderProductDetails->weight,
                        'weight_type_id' => $objExchangeOrderProductDetails->weight_type_id,
                        'weight_type' => ($objExchangeOrderProductDetails->weight_type_id > 0) ? $objWeightManageDetails[$objExchangeOrderProductDetails->weight_type_id] : null,
                        'width'=> $objExchangeOrderProductDetails->width,
                        'width_type_id' => $objExchangeOrderProductDetails->width_type_id,
                        'dimension_width_type' => ($objExchangeOrderProductDetails->width_type_id > 0) ? $objDimensionDetails[$objExchangeOrderProductDetails->width_type_id] : null,
                        'height'=> $objExchangeOrderProductDetails->height,
                        'height_type_id' => $objExchangeOrderProductDetails->height_type_id,
                        'dimension_height_type' => ($objExchangeOrderProductDetails->height_type_id > 0) ? $objDimensionDetails[$objExchangeOrderProductDetails->height_type_id] : null,
                        'length'=> $objExchangeOrderProductDetails->length,
                        'length_type_id' => $objExchangeOrderProductDetails->length_type_id,
                        'dimension_length_type' => ($objExchangeOrderProductDetails->length_type_id > 0) ? $objDimensionDetails[$objExchangeOrderProductDetails->length_type_id] : null,
                        'compareprice' => $objSelectionProduct->compare_at_price,
                        'stock_status'=>$objSelectionProduct['stock_status'],
                        'cost_per_item'=>$objExchangeOrderProductDetails->cost_per_item,
                        'description'=> $orderProduct['descriptionData'],
                        'is_approve'=> (!empty($objReturnOrderProduct)) ? $objReturnOrderProduct->admin_approve : 0,
                        'latest_description'=> (!empty($objReturnOrderProduct)) ? $objReturnOrderProduct->description : null,
                        'note'=> $objExchangeOrderDetails->note,
                    ]);
                }
            }
            $data['objSelectionProducts'] = collect($data['objSelectionProducts'])->sortBy('id')->values()->toArray();
        }
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => 'Home'], ['link'=>route('admin.exchangeorders.index'),'name' => trans('cruds.exchangeorders.orders') .' '.trans('global.listing')], ['name' => trans('global.exchange') .' '.trans('cruds.exchangeorders.exchange_orders') ]];

        return view('admin.exchangeorders.exchangeorder', compact('breadcrumbs','list','data'));
    }

    public function addExchangeProduct(Request $request){
        try{
            $params = collect($request->all());
            $url = route('admin.exchangeorders.index');
            $objOrder = Order::whereId($params['order_id'])->latest()->first();
            if(empty($objOrder)){
                     return $this->successResponse(
                    __('constants.SUCCESS_STATUS'),
                    __('constants.messages.SHIPPED_EXCHANGE_CANCELED.code'),
                    __('constants.messages.SHIPPED_EXCHANGE_CANCELED.msg'),
                      ['url'=>$url]
                    );
            }
            $authUser = Auth::user();
            $objReturnOrder = ReturnOrder::where(['order_id'=>$params['order_id'],'admin_approve' => 0])->latest()->first();
            $objOrder = Order::whereId($params['order_id'])->first();
            $objOrderProduct = OrderProduct::where('order_id',$params['order_id'])->first();
            foreach($params['exchangeProducts'] as $exchangeProduct)
            {
                if($exchangeProduct['isApprove'] == true)
                {
                        if(empty($objReturnOrder))
                        {
                            $objReturnOrder = new ReturnOrder;
                        }
                        $objReturnOrder->order_id = $params['order_id'];
                        $objReturnOrder->user_id = $authUser->id;
                        $objReturnOrder->save();

                        $objReturnOrderProduct = ReturnOrderProduct::where('return_order_id',$objReturnOrder->id)->first();
                        if(empty($objReturnOrderProduct))
                        {
                            $objReturnOrderProduct = new ReturnOrderProduct;
                        }
                            $objReturnOrderProduct->return_order_id = $objReturnOrder->id;
                            $objReturnOrderProduct->product_id = $exchangeProduct['product_id'];
                            $objReturnOrderProduct->product_variant_options_id = $exchangeProduct['product_variant_option_id'];
                            $objReturnOrderProduct->quantity =  $exchangeProduct['exchangeQuantity'];
                            $objReturnOrderProduct->description = $exchangeProduct['approveOrderDescription'];
                            $objReturnOrderProduct->admin_approve = $exchangeProduct['isApprove'];
                            $objReturnOrderProduct->save();  
                      
                        if(!empty($objOrderProduct))
                        {
                            $objOrderProduct->admin_approve_quantity = $exchangeProduct['exchangeQuantity'];
                            $objOrderProduct->admin_response = $exchangeProduct['approveOrderDescription'];
                            $objOrderProduct->save();
                        }
                    $url = route('admin.exchangeorders.index');
                    return $this->successResponse(
                        __('constants.SUCCESS_STATUS'),
                        __('constants.messages.RETURN_EXCHANGE_ORDER_SAVED_SUCCESSFULLY.code'),
                        __('constants.messages.RETURN_EXCHANGE_ORDER_SAVED_SUCCESSFULLY.msg'),
                        ['url'=>$url]
                    );
                }
                else {
                    return $this->errorResponse(
                    __('constants.ERROR_STATUS'),
                    __('constants.errors.SOMETHING_WRONG.code'),
                    __('constants.errors.SOMETHING_WRONG.msg'),
                    // $e->getMessage()
                    );       
                }
            }      
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

}