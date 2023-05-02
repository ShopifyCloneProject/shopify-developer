<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Carbon;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\ReturnOrder;
use App\Models\Refund;
use App\Models\RefundProduct;
use App\Models\ReturnOrderProduct;
use App\Models\Weightmanage;
use App\Models\Dimension;
use Gate;
use Config;

class RefundOrderController extends Controller
{
    public function refundOrder($id)
    {
        abort_if(Gate::denies('order_refund'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $order = Order::where('id', $id)->first();
        $data['order'] = $order;

        $searchProductLimit = Config::get('SEARCH_PRODUCT_LIMIT');
        $objOrderDetails = OrderProduct::where('order_id',$id)->get();
        $data['order_id'] = $id;
        $objOrderDetailsProductId = $objOrderDetails->pluck('product_id')->toArray();
        $objOrderDetailsProductVariantId = $objOrderDetails->pluck('product_variant_options_id')->toArray();
        $objOrderDetailsUserId = $objOrderDetails->pluck('user_id')->toArray();
        $objWeightManageDetails = Weightmanage::pluck('short_code','id')->toArray();
        $objDimensionDetails = Dimension::pluck('short_code','id')->toArray();
        $objRefunds = Refund::where('order_id',$data['order_id'])->get();
        $data['objRefunds'] = null;
        if(!empty($objRefunds)){
            $data['objRefunds'] = $objRefunds;
        }
        $list['payment_status'] = Order::PAYMENT_STATUS;

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
        $data['cost_per_item'] = $data['totalPrice'] = $data['totalCosting'] = 0;

        foreach($objOrderDetails as $objOrderDetail){
            $data['costing'] = $objOrderDetail['quantity']*$objOrderDetail['cost_per_item'];
            $data['totalCosting'] += $data['costing'];
            $price = $objOrderDetail['price']*$objOrderDetail['quantity'];
            $data['totalPrice'] += $price;
            $data['cost_per_item'] += $objOrderDetail['cost_per_item'];
        }
        $totalOrderProduct = count($objOrderDetailsProductId);
        $data['subtotal'] = $data['order']['sub_total'];
        $data['shipping_cost'] = $data['order']['shipping_cost'];
        $data['discount_amount'] = $data['order']['discount_amount'];
        $data['taxes'] = $data['order']['taxes'];
        $data['paidByCustomer'] = ($data['subtotal'] + $data['taxes'] + $data['shipping_cost']) - $data['discount_amount'];
        $data['profit'] = $data['paidByCustomer']-$data['totalCosting'];

        $intReturnOrderId = [];
        $objReturnOrder = ReturnOrder::where('order_id',$id)->get();
        if($objReturnOrder->isNotEmpty())
        {
            $intReturnOrderId  = $objReturnOrder->pluck('id');
        }

        $data['objSelectionProducts'] = $this->handleRefundProductSelect($objSelectionProducts, $objOrderDetailsProductId, $objOrderDetailsProductVariantId, $objOrderDetails,$data['order_id'],0,$objWeightManageDetails,$objDimensionDetails);
        $data['objSelectionProducts'] = collect($data['objSelectionProducts'])->sortBy('order_details_id')->values()->toArray();
        
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => 'Home'], ['link'=>route('admin.orders.index'),'name' => trans('cruds.order.title') .' '.trans('global.listing')], ['name' => trans('global.refund') .' '.trans('cruds.order.title_singular') ]];

        return view('admin.orders.refundorders', compact('breadcrumbs','data','list'));
    }

    public function refundOrderproduct($id){
        $objOrderDetails = [];
        $returnOrderId = ReturnOrder::where(['id'=>$id])->first();
        if($returnOrderId)
        {
            $objOrder = Order::select('id','order_nr','currency_id','user_id','email','mobile','billing_address_id','shipping_address_id','payment_method_id','sub_total','shipping_cost','taxes','total','financial_status','discount_code','discount_amount','note','admin_approve','fulfillment_status','paid_at','created_at')->whereId($returnOrderId->order_id)->first();
            $data['symbol'] = $objOrder->currency->symbol;
            $data['order'] = $objOrder;
        }
        $data['orderId'] = $returnOrderId->order_id;
        $objReturnOrderDetails = ReturnOrderProduct::where(['return_order_id'=>$id])->get();
        $objReturnOrderProductId = $objReturnOrderDetails->pluck('product_id')->toArray();
        $objReturnOrderProductVariantId = $objReturnOrderDetails->pluck('product_variant_options_id')->toArray();
        $objReturnOrderUserId = $returnOrderId->user_id;
        $objWeightManageDetails = Weightmanage::pluck('short_code','id')->toArray();
        $objDimensionDetails = Dimension::pluck('short_code','id')->toArray();
        $searchProductLimit = Config::get('SEARCH_PRODUCT_LIMIT'); 
        $objRefunds = Refund::where('order_id',$data['orderId'])->get();
        $data['objRefunds'] = null;
        if(!empty($objRefunds)){
            $data['objRefunds'] = $objRefunds;
        }
        $list['payment_status'] = Order::PAYMENT_STATUS;

        $objSelectionProducts = Product::select('id','title','quantity','slug','price','compare_at_price','is_product_variant','description','sku','barcode','weight','weight_type_id','length','length_type_id','width','width_type_id','height','height_type_id','hs_code','is_product_charge','is_special_product','special_price','is_track','cost_per_item')
                ->with([
                'medias' => function($media){
                    $media->select('client_id','product_id','src');
                    }, 
                'product_variant_options' => function ($variant) use($objReturnOrderProductVariantId) {
                        $variant->select('id','product_id','variant_option_1_id','variant_option_2_id','variant_option_3_id','price','compare_at_price','sku','barcode','weight','weight_type_id','length','length_type_id','width','width_type_id','height','height_type_id','hs_code','is_product_charge','is_special_product','special_price','is_track','cost_per_item');
                        $variant->whereIn('id', $objReturnOrderProductVariantId);
                    }, 
                'product_variant_options.variant_media' => function ($variantmedia) {
                        $variantmedia->select('client_id','product_variant_id','src'); 
                },
                'product_variant_options.variant_option_1','product_variant_options.variant_option_2','product_variant_options.variant_option_3'
            ])->whereIn('id',$objReturnOrderProductId)
                ->limit($searchProductLimit)
                ->get();

        if(!empty($returnOrderId))
        {
            $intReturnOrderId  = $returnOrderId->id;
        }

        $data['objSelectionProducts'] = $this->handleRefundProductSelect($objSelectionProducts, $objReturnOrderProductId, $objReturnOrderProductVariantId,  $objReturnOrderDetails,$data['orderId'],$intReturnOrderId,$objWeightManageDetails,$objDimensionDetails);
       
        $data['objSelectionProducts'] = collect($data['objSelectionProducts'])->sortBy('order_details_id')->values()->toArray();

        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => 'Home'], ['link'=>route('admin.returnorders.index'),'name' => trans('cruds.product.title') .' '.trans('global.listing')], ['name' => trans('global.refund') .' '.trans('cruds.order.title_singular') ]];
        return view('admin.orders.refundparticularorder', compact('data','breadcrumbs','list'));
    }

    public function handleRefundProductSelect($objSelectionProducts,$objOrderProductId,$objProductVariantId,  $objReturnOrderDetails,$objOrderId,$intReturnOrderId,$objWeightManageDetails,$objDimensionDetails){
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
                        $descriptionData = $objRefundProductDescription = $objReturnOrderProductDescription = [];
                        $refundQuantity = $requestReturnQuantity = $finalQuantity = $adminApproveReturnQuantity = 0;
                        $orderIndex = $objReturnOrderDetails->search(function($objReturnOrderDetail) use($objProductVariantOptions) {
                            return $objReturnOrderDetail->product_id == $objProductVariantOptions->product_id && $objReturnOrderDetail->product_variant_options_id == $objProductVariantOptions->id;
                        }); // get order index from return order product

                        $objOrderDetails = OrderProduct::select('quantity')->where(['order_id'=>$objOrderId, 'product_id' => $objProductVariantOptions->product_id, 'product_variant_options_id' => $objProductVariantOptions->id])->first();
                        $objRefundProduct = RefundProduct::select('quantity','total', 'description','updated_at')->where(['product_id' => $objReturnOrderDetails[$orderIndex]['product_id'], 'product_variant_options_id' =>  $objReturnOrderDetails[$orderIndex]['product_variant_options_id'], 'order_id' => $objOrderId])->get();
                        if($objRefundProduct->isNotEmpty())
                        {
                            $refundQuantity = $objRefundProduct->sum('quantity');
                            $objRefundProductDescription =  $objRefundProduct->toArray();
                        }

                        $objReturnOrderProduct = ReturnOrderProduct::with('return_orders')->whereHas('return_orders', function($returnorderquery) use ($objOrderId){
                                $returnorderquery->where('order_id', $objOrderId);
                        })->select('admin_approve','quantity', 'description','created_at','cancel_request')->where(['product_id' => $objReturnOrderDetails[$orderIndex]['product_id'], 'product_variant_options_id' =>  $objReturnOrderDetails[$orderIndex]['product_variant_options_id'],'admin_approve' => 0])->get();

                        // if($intReturnOrderId != 0)
                        // {
                        //     $objReturnOrderProduct = $objReturnOrderProduct->where('return_order_id',$intReturnOrderId);
                        // }
                        // $objReturnOrderProduct = $objReturnOrderProduct->get();

                        if($objReturnOrderProduct->isNotEmpty())
                        {
                            $requestReturnQuantity = $objReturnOrderProduct->sum('quantity');
                            $adminApproveReturnQuantity = $objReturnOrderProduct->filter(function ($item) {
                                return $item->admin_approve == 1;
                            })->sum('quantity');

                            $objReturnOrderProductDescription =  $objReturnOrderProduct->toArray();
                        }

                        $descriptionData = array_merge($objRefundProductDescription,$objReturnOrderProductDescription);
                        $descriptionData = collect($descriptionData)->sortBy('created_at')->values()->toArray();
                        array_push($data['objSelectionProducts'],
                            [
                                'id' => $objReturnOrderDetails[$orderIndex]['id'],
                                'product_variant_option_id' => $objReturnOrderDetails[$orderIndex]['product_variant_options_id'],
                                'product_id' => $objReturnOrderDetails[$orderIndex]['product_id'],
                                'title' => $title,
                                'quantity' => ($objOrderDetails) ?$objOrderDetails['quantity'] : null,
                                'refundquantity' => $objRefundProduct->sum('quantity'),
                                'refundtotal' => $objRefundProduct->sum('total'),
                                'slug' => $slug,
                                'price' => $objProductVariantOptions->price,
                                'img_src'=>$img_src,
                                'sku'=> $objProductVariantOptions->sku,
                                'barcode'=> $objProductVariantOptions->barcode,
                                'weight'=> $objProductVariantOptions->weight,
                                'weight_type_id' => $objProductVariantOptions->weight_type_id,
                                'weight_type' => ($objProductVariantOptions->weight_type_id > 0) ? $objWeightManageDetails[$objProductVariantOptions->weight_type_id] : null,
                                'width'=> $objProductVariantOptions->width,
                                'width_type_id' => $objProductVariantOptions->width_type_id,
                                'dimension_width_type' => ($objProductVariantOptions->width_type_id > 0) ? $objDimensionDetails[$objProductVariantOptions->width_type_id] : null,
                                'height'=> $objProductVariantOptions->height,
                                'height_type_id' => $objProductVariantOptions->height_type_id,
                                'dimension_height_type' => ($objProductVariantOptions->height_type_id > 0) ? $objDimensionDetails[$objProductVariantOptions->height_type_id] : null,
                                'length'=> $objProductVariantOptions->length,
                                'length_type_id' => $objProductVariantOptions->length_type_id,
                                'dimension_length_type' => ($objProductVariantOptions->length_type_id > 0) ? $objDimensionDetails[$objProductVariantOptions->length_type_id] : null,
                                'hs_code'=> $objProductVariantOptions->hs_code,
                                'is_product_charge'=> $objProductVariantOptions->is_product_charge,
                                'is_special_product'=> $objProductVariantOptions->is_special_product,
                                'special_price'=> $objProductVariantOptions->special_price,
                                'is_track'=> $objProductVariantOptions->is_track,
                                'compareprice' => $objProductVariantOptions->compare_at_price,
                                'stock_status'=>$objSelectionProduct['stock_status'],
                                'cost_per_item'=>$objProductVariantOptions->cost_per_item,
                                'description'=> $descriptionData,
                                'requestReturnQuantity' => ($requestReturnQuantity) ? $requestReturnQuantity : 0,
                            ]);
                    }
                }
            }
                else{
                    //NonVariant Product
                     $descriptionData = $objRefundProductDescription = $objReturnOrderProductDescription = [];
                     $refundQuantity = $requestReturnQuantity = $finalQuantity = 0;
                        $orderIndex = $objReturnOrderDetails->search(function($objReturnOrderDetail) use($objSelectionProduct) {
                            return $objReturnOrderDetail->product_id == $objSelectionProduct['id'];
                        }); // get order index from order product
                         $objOrderDetails = OrderProduct::select('quantity')->where(['order_id'=>$objOrderId, 'product_id' => $objSelectionProduct['id'], 'product_variant_options_id' => $objSelectionProduct['product_variant_option_id']])->first();

                        $objRefundProduct = RefundProduct::select('quantity','total', 'description','updated_at')->where(['product_id' => $objReturnOrderDetails[$orderIndex]['product_id'],'order_id' => $objOrderId])->get();

                        if($objRefundProduct->isNotEmpty())
                        {
                            $refundQuantity = $objRefundProduct->sum('quantity');
                            $objRefundProductDescription =  $objRefundProduct->toArray();
                        }
                        $objReturnOrderProduct = ReturnOrderProduct::with('return_orders')->whereHas('return_orders', function($returnorderquery) use ($objOrderId){
                                $returnorderquery->where('order_id', $objOrderId);
                        })->select('admin_approve','quantity', 'description','created_at','cancel_request')->where(['product_id' => $objReturnOrderDetails[$orderIndex]['product_id']])->get();

                        if($objReturnOrderProduct->isNotEmpty())
                        {
                            $requestReturnQuantity = $objReturnOrderProduct->sum('quantity');
                            $adminApproveReturnQuantity = $objReturnOrderProduct->filter(function ($item) {
                                return $item->admin_approve == 1;
                            })->sum('quantity');
                            $objReturnOrderProductDescription =  $objReturnOrderProduct->toArray();
                        }

                        $descriptionData = array_merge($objRefundProductDescription,$objReturnOrderProductDescription);
                        $descriptionData = collect($descriptionData)->sortBy('created_at')->values()->toArray();
                    array_push($data['objSelectionProducts'],
                            [
                                'id' => $objReturnOrderDetails[$orderIndex]['id'],
                                'product_variant_option_id' => $objReturnOrderDetails[$orderIndex]['product_variant_options_id'],
                                'product_id' => $objReturnOrderDetails[$orderIndex]['product_id'],
                                'title' => $title,
                                'quantity' => ($objOrderDetails) ?$objOrderDetails['quantity'] : null,
                                'refundquantity' => $objRefundProduct->sum('quantity'),
                                'refundtotal' => $objRefundProduct->sum('total'),
                                'slug' => $slug,
                                'price' => $objSelectionProduct['price'],
                                'img_src'=>$img_src,
                                'sku'=> $objSelectionProduct['sku'],
                                'barcode'=> $objSelectionProduct['barcode'],
                                'weight'=> $objSelectionProduct['weight'],
                                'weight_type_id' => $objSelectionProduct['weight_type_id'],
                                'weight_type' => ($objSelectionProduct['weight_type_id'] > 0) ? $objWeightManageDetails[$objSelectionProduct['weight_type_id']] : null,
                                'width'=> $objSelectionProduct['width'],
                                'width_type_id' => $objSelectionProduct['width_type_id'],
                                'dimension_width_type' => ($objSelectionProduct['width_type_id'] > 0) ? $objDimensionDetails[$objSelectionProduct['width_type_id']] : null,
                                'height'=> $objSelectionProduct['height'],
                                'height_type_id' => $objSelectionProduct['height_type_id'],
                                'dimension_height_type' => ($objSelectionProduct['height_type_id'] > 0) ? $objDimensionDetails[$objSelectionProduct['height_type_id']] : null,
                                'length'=> $objSelectionProduct['length'],
                                'length_type_id' => $objSelectionProduct['length_type_id'],
                                'dimension_length_type' => ($objSelectionProduct['length_type_id'] > 0) ? $objDimensionDetails[$objSelectionProduct['length_type_id']] : null,
                                'hs_code'=> $objSelectionProduct['hs_code'],
                                'is_product_charge'=> $objSelectionProduct['is_product_charge'],
                                'is_special_product'=> $objSelectionProduct['is_special_product'],
                                'special_price'=> $objSelectionProduct['special_price'],
                                'is_track'=> $objSelectionProduct['is_track'],
                                'compareprice' => $objSelectionProduct['compare_at_price'],
                                'stock_status'=>$objSelectionProduct['stock_status'],
                                'cost_per_item'=>$objSelectionProduct['cost_per_item'],
                                'description'=> $descriptionData,
                                'requestReturnQuantity' => ($requestReturnQuantity) ? $requestReturnQuantity : 0,
                            ]);
                }
        }
        return $data['objSelectionProducts'];
    }
}
