<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\MassDestroyReturnOrderRequest;
use App\Models\ReturnOrder;
use App\Models\ReturnOrderProduct;
use App\Models\User;
use App\Models\Order;
use App\Models\Dimension;
use App\Models\Weightmanage;
use App\Models\Product;
use App\Models\Country;
use App\Models\ReturnShipping;
use App\Models\ReturnShippingProduct;
use Gate;
use Config;

class ReturnOrderController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('returnorders_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        if ($request->ajax()) {
            try{

                    $query = ReturnOrder::with(['order', 'user'])->whereHas('order', function($orderQuery){
                        $orderQuery->where('financial_status', "!=" , 'exchanged');
                    })->select(sprintf('%s.*', (new ReturnOrder())->table));
                    $table = Datatables::of($query);

                    $table->addColumn('actions', '&nbsp;');

                    $table->editColumn('id', function ($row) {
                        return $row->id ? $row->id : '';
                    });

                    $table->addColumn('order_nr', function ($row) {
                        return $row->order ? $row->order->order_nr : '';
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

                    $table->rawColumns(['actions', 'user', 'order']);
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
        // $return_orders = ReturnOrder::select('admin_approve')->get();
        // $users = User::select('email','mobile')->get();
        // $orders = Order::select('order_nr')->get();

        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['name' => trans('cruds.returnorders.title_singular')." ".trans('global.listing') ]];
        return view('admin.returnorders.index', compact('breadcrumbs'));
    }

     public function show($id){
        abort_if(Gate::denies('returnorders_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $list = $data = [];
        $searchProductLimit = Config::get('SEARCH_PRODUCT_LIMIT');
        $objReturnOrderDetails = ReturnOrderProduct::where('return_order_id',$id)->get();
        $objReturnOrder = ReturnOrder::where('id',$id)->first();
        $objReturnShippingProductId = $objReturnShippingProductVariantId = null;
        $objReturnShippings = ReturnShipping::where('order_id',$objReturnOrder->order_id)->get();
        if(!empty($objReturnShippings)){
            $objReturnShippingProduct = ReturnShippingProduct::where('order_id',$objReturnOrder->order_id)->get();
            $objReturnShippingProductId = $objReturnShippingProduct->pluck('product_id')->toArray();
            $objReturnShippingProductVariantId = $objReturnShippingProduct->pluck('product_variant_options_id')->toArray();
            $data['objReturnShippingProduct'] = $objReturnShippingProduct;
            $data['shipping_link'] = [];
            foreach($objReturnShippings as $intkey => $objReturnShipping){
                $data['shipping_link'][] = Route('admin.returnshippingproducts.show', ['returnshippingproduct' => $objReturnShipping->id]);
            }
        }
        $countries = Country::get();
        $order = Order::with('shipping_address', 'billing_address')->where('id', $objReturnOrder->order_id)->first();
        $shipping_address = $order->shipping_address;
        $billing_address = $order->billing_address;
        $shippingAddress = [];
        $billingAddress = [];
         $list = [
            'countries' => $countries, 
            'btn_return_shipping_order_access' => !Gate::denies('btn_return_shipping_order_access'), 
            'btn_return_delete_order_access' => !Gate::denies('btn_return_delete_order_access'), 
            'btn_return_delete_action_access' => !Gate::denies('btn_return_delete_action_access'), 
            'btn_return_shipping_action_access' => !Gate::denies('btn_return_shipping_action_access'), 
        ];

        if(isset($shipping_address) && $shipping_address != ''){
            $shippingAddress = $shipping_address->toArray();
            $shippingAddress['countryName'] = $shipping_address->country;
            $shippingAddress['stateName'] = $shipping_address->state;
            $shippingAddress['shortCode'] =  $shipping_address->short_code;
            $shippingAddress['phone_code'] =  $shipping_address->phone_code;
        }

        if(isset($billing_address) && $billing_address != ''){
            $billingAddress = $billing_address->toArray();
            $billingAddress['countryName'] = $billing_address->country;
            $billingAddress['stateName'] =$billing_address->state;
            $billingAddress['shortCode'] =  $billing_address->short_code;
            $billingAddress['phone_code'] =  $billing_address->phone_code;
        }

        $data = [
            'order' => $order,
            'shipping_address' => $shippingAddress,
            'billing_address' => $billing_address,
            'returnOrderId' => $objReturnOrder->id,
            'objReturnShipping' => $objReturnShippings,
            'objReturnShippingProduct' => $objReturnShippingProduct,
            'shipping_link' => $data['shipping_link'],
            'objReturnOrder' => $objReturnOrder
        ];
        $objReturnOrderDetailsProductId = $objReturnOrderDetails->pluck('product_id')->toArray();
        $objReturnOrderDetailsProductVariantId = $objReturnOrderDetails->pluck('product_variant_options_id')->toArray();
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
                    $img_src = (!empty($objSelectionProduct->medias[0])) ? $objSelectionProduct->medias[0]->image_src[2] : '';
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
                                    'shipping_link'=> $intReturnShippingId
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
                            'shipping_link'=> $intReturnShippingId
                        ]);
                    }
                }
        $data['objSelectionProducts'] = collect($data['objSelectionProducts'])->sortBy('id')->values()->toArray();
        // dd($data);
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => 'Home'], ['link'=>route('admin.returnorders.index'),'name' => trans('cruds.returnorders.return_product') .' '.trans('global.listing')], ['name' => trans('global.show') .' '.trans('cruds.returnorders.return_product') ]];
        return view('admin.returnorders.show', compact('breadcrumbs','list','data'));    
     }

     public function deleteReturnOrder($id)
    {
        try{
            ReturnOrder::where('id', $id)->delete();
            $url = route('admin.returnorders.index');
            return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.RETURN_ORDER_DELETED_SUCCESSFULLY.code'),
                __('constants.messages.RETURN_ORDER_DELETED_SUCCESSFULLY.msg'),
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

    public function deleteReturnOrderProduct($returnOrderProductId){
        try{
            ReturnOrderProduct::where('id',$returnOrderProductId)->delete();
            return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.RETURN_ORDER_PRODUCT_DELETE_SUCCESSFULLY.code'),
                __('constants.messages.RETURN_ORDER_PRODUCT_DELETE_SUCCESSFULLY.msg'),
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

    public function destroy($id)
    {
        try {
              abort_if(Gate::denies('returnorders_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
              ReturnOrder::where('id',$id)->delete();   
             
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
            __('constants.messages.RETURN_ORDERS_DELETE_SUCCESSFULLY.code'),
            __('constants.messages.RETURN_ORDERS_DELETE_SUCCESSFULLY.msg'),
        );
    }

    public function massDestroy(MassDestroyReturnOrderRequest $request)
    {
        try {
              ReturnOrder::whereIn('id', request('ids'))->delete(); 
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
            __('constants.messages.RETURN_ORDERS_DELETE_SUCCESSFULLY.code'),
            __('constants.messages.RETURN_ORDERS_DELETE_SUCCESSFULLY.msg'),
        );
    }

}
