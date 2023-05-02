<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\ExchangeMedium;
use App\Models\ShippingProduct;
use App\Models\ReturnShippingProduct;
use Config;
use Auth;
use Storage;

class ExchangeOrderController extends Controller
{
    
    public function cancelExchangeOrder($orderid){
        try{

            $objOrder = Order::with('order_products')->where('id',$orderid)->latest()->first();
            $objOrderProduct = $objOrder->order_products->first();
            $mainOrderProduct = OrderProduct::where(['order_id'=>$objOrder->parent_order_id, 'product_id' => $objOrderProduct->product_id, 'product_variant_options_id' => $objOrderProduct->product_variant_options_id])->latest()->first();

            $objShippingProduct = ShippingProduct::where(['order_id'=>$objOrder->id, 'product_id' => $objOrderProduct->product_id, 'product_variant_options_id' => $objOrderProduct->product_variant_options_id])->latest()->first();

            $objReturnShippingProduct = ReturnShippingProduct::where(['order_id'=>$objOrder->id, 'product_id' => $objOrderProduct->product_id, 'product_variant_options_id' => $objOrderProduct->product_variant_options_id])->latest()->first();
            $url = route("orderdata", ['order_id' => $objOrder->parent_order_id, 'order_product_id' => $mainOrderProduct->id]);
                if(!empty($objShippingProduct)){
                        return $this->successResponse(
                        __('constants.SUCCESS_STATUS'),
                        __('constants.messages.DELIVERED_EXCHANGE_CANCEL_SUCCESSFULLY.code'),
                        __('constants.messages.DELIVERED_EXCHANGE_CANCEL_SUCCESSFULLY.msg'),
                        ['url' => $url]
                    );
                }
                elseif(!empty($objReturnShippingProduct)){
                        return $this->successResponse(
                        __('constants.SUCCESS_STATUS'),
                        __('constants.messages.DELIVERED_RETURN_EXCHANGE_CANCEL_SUCCESSFULLY.code'),
                        __('constants.messages.DELIVERED_RETURN_EXCHANGE_CANCEL_SUCCESSFULLY.msg'),
                        ['url' => $url]
                    );
                }
            $objExchangeMedia = ExchangeMedium::where('order_id',$orderid)->delete();
            $objOrder->delete();
            $objOrderProduct->delete();
            return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.EXCHANGE_ORDER_CANCEL_SUCCESSFULLY.code'),
                __('constants.messages.EXCHANGE_ORDER_CANCEL_SUCCESSFULLY.msg'),
                ['url' => $url]
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

    public function saveexchangeorder(Request $request){
        try{
            $params = collect($request->all());
            if($params['quantity'] > 0)
            {
                $latestOrder = $this->getOrderNumber();
                $objOrder = Order::with(['order_products' =>function($query) use($params){
                $query->where(['order_id'=>$params['order_id'],'product_id'=>$params['product_id'],'product_variant_options_id'=>$params['product_variant_option_id']]);
                    }])->where(['id' => $params['order_id'],'parent_order_id' => NULL])->latest()->first();
                $objExistingOrder = Order::where(['parent_order_id' => $params['order_id'],'admin_approve'=>0])->latest()->first();
                if($objExistingOrder){
                    $objExistingOrderProduct = OrderProduct::where(['order_id'=>$objExistingOrder->id,'product_id'=>$params['product_id'],'product_variant_options_id'=>$params['product_variant_option_id']])->latest()->first();
                }

                $objExistingApproveOrder = Order::where(['parent_order_id' => $params['order_id'],'admin_approve'=>1])->latest()->first();
                if(!empty($objExistingOrderProduct))
                {
                    $objExistingOrderProduct->quantity = $params['quantity'];
                    $objExistingOrderProduct->order_id = $objExistingOrder->id;
                    $objExistingOrderProduct->client_request = $params['client_request'];
                    $objExistingOrderProduct->save();
                    $objAmountOrder = $objExistingOrder;
                }
                elseif(!empty($objOrder) || !empty($objExistingApproveOrder)) // first exchange order
                {
                    $newObjOrder = $objOrder->replicate(); 
                    $newObjOrder->order_nr = $latestOrder;
                    $newObjOrder->parent_order_id = $params['order_id'];
                    $newObjOrder->discount_amount = 0;
                    $newObjOrder->discount_code = null;
                    $newObjOrder->financial_status = 'exchanged';
                    $newObjOrder->admin_approve = 0;
                    $newObjOrder->save();
                    $objAmountOrder = $newObjOrder;

                    $objOrderProduct = OrderProduct::whereId($params['id'])->first();
                    $newOrderProduct = $objOrderProduct->replicate();
                    $newOrderProduct->quantity = $params['quantity'];
                    $newOrderProduct->order_id = $newObjOrder->id;
                    $newOrderProduct->client_request = $params['client_request'];
                    $newOrderProduct->save();
                }
                $sumAmount = 0;
                $objSumOrderProduct = OrderProduct::where(['order_id' => $objAmountOrder->id])->get();
                if($objSumOrderProduct->isNotEmpty())
                {
                    foreach($objSumOrderProduct as $intKey => $objSingleOrderProduct)
                    {
                        $sumAmount += $objSingleOrderProduct->quantity * $objSingleOrderProduct->price;
                    }
                }
                $objAmountOrder->sub_total = $sumAmount;
                $objAmountOrder->total = $sumAmount;
                $objAmountOrder->save();

                $client_id = Config::get('client_id');

                    if(!empty($params['media']))
                    {
                        $path = "public/$client_id";
                        $product_id = $params['product_id'];
                        $this->checkFolder($path);
                        $this->checkFolder($path.'/exchangeorder');
                        $this->checkFolder($path .'/exchangeorder/'.$product_id);
                        foreach($params['media'] as $key => $imageData)
                        {
                            if($imageData['id'] < 0){
                                $refrence_id = mt_rand( 1000, 9999);
                                $imagename = time().$refrence_id.'.png';
                                $image = file_get_contents($imageData['imageurl']);
                                Storage::disk('public')->put($client_id.'/exchangeorder/'.$product_id.'/'.$imagename, $image, 'public');

                                $objExchangeProductMedia = new ExchangeMedium;
                                $objExchangeProductMedia->client_id = $client_id;
                                $objExchangeProductMedia->order_id = $objAmountOrder->id;
                                $objExchangeProductMedia->product_id = $product_id;
                                $objExchangeProductMedia->product_variant_options_id = $params['product_variant_option_id'];
                                $objExchangeProductMedia->src = $imagename;
                                $objExchangeProductMedia->save();
                            }
                        }
                    }   
                $url = route("orderdata", ['order_id' => $params['order_id'], 'order_product_id' => $params['id']]);
                return $this->successResponse(
                    __('constants.SUCCESS_STATUS'),
                    __('constants.messages.EXCHANGE_ORDER_SAVED_SUCCESSFULLY.code'),
                    __('constants.messages.EXCHANGE_ORDER_SAVED_SUCCESSFULLY.msg'),
                    ['url' => $url]
                );
            }
            else
            {
                return $this->errorResponse(
                __('constants.ERROR_STATUS'),
                __('constants.errors.QTY_GRETER_THAN.code'),
                __('constants.errors.QTY_GRETER_THAN.msg'),
               
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
