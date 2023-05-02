<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ShiprocketService;
use App\Services\IthinklogisticsService;
use Illuminate\Support\Carbon;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\RefundProduct;
use App\Models\Product;
use App\Models\Address;
use App\Models\OrderLocation;
use App\Models\ReturnOrder;
use App\Models\ReturnOrderProduct;
use App\Models\UserStore;
use App\Models\Shipping;
use App\Models\ShippingProduct;
use App\Models\ReturnShipping;
use App\Models\ReturnShippingProduct;
use App\Models\Shipments;
use App\Models\ShippingDetail;
use App\Models\ShippingMethod;
use App\Models\ExchangeMedium;
use App\Models\Tracking;
use Auth;
use Exception;
use PDF;
use Storage;
use Config;


class OrderController extends Controller
{
    protected $shipService;
    protected $ithinkService;

    public function __construct()
    {
        $this->shipService = new ShiprocketService;
        $this->ithinkService = new IthinklogisticsService;
    }

    public function orders()
    {
        $user = $this->checkAuthUser();
        $data = [
            'page'        => 'account',
            'section'     => 'orders',
            'user'        => $user,
        ];
        if(false){

        }
        else{
            return view('theme.default.pages.account', compact('data'));
        }
    }

    public function getAllOrder(Request $request)
    {
        try {
            $list = $data = [];
            $objOrderProductDetails = collect(); 
            $params = collect($request->all());
            $totalPages = 0;
            $user = $this->checkAuthUser();
            $objOrder = Order::select('id', 'order_nr','paid_at', 'financial_status', 'total', 'currency_id','created_at')->where(['user_id'=>$user->id,'parent_order_id'=>null])->orderBy('order_nr');
            $objOrderId = $objOrder->pluck('id');
            $objRefundProduct = RefundProduct::whereIn('order_id',$objOrderId)->get();
            $perPage = Config::get('DISPLAY_ORDER_LIMIT');
            $currentPage = (isset($params['currentPage']))?$params['currentPage']:1;
            $totalRecords = $objOrder->count();
            if($totalRecords > 0)
            {
                $objOrder = $objOrder->skip( $perPage * ( $currentPage - 1 ) )->take($perPage)->get();
                $objOrder->load('currency');

                foreach($objOrder as $intKey => $objSingleOrder)
                {
                    foreach($objSingleOrder->order_products as $intProductKey => $objSingleOrderProduct)
                    {
                        $objSingleOrderProduct['symbol'] = $objSingleOrder->currency->symbol;
                        $objSingleOrderProduct['order_id'] = $objSingleOrder->id;
                        $objOrderProductDetails->push($objSingleOrderProduct);
                    }
                }

                $totalPages = ceil($totalRecords / $perPage);
                $totalPages = $totalPages == 0 ? 1 : $totalPages; 
                $searchProductLimit = Config::get('SEARCH_PRODUCT_LIMIT');
                $objfinalOrderProducts = [];
                foreach($objOrderProductDetails as $intkey => $objSingleProduct)
                {
                    $objTempOrderProduct = [];
                    $objTempOrderProduct['remainQuantity'] = 0;
                    if($objRefundProduct){
                        $objTempOrderProduct['remainQuantity'] = $objRefundProduct->filter(function($refundProduct) use($objSingleProduct){
                                        return $refundProduct->product_id == $objSingleProduct->product_id && $refundProduct->product_variant_options_id == $objSingleProduct->product_variant_options_id;
                                        })->sum('quantity');
                    }

                    $objTempOrderProduct['id'] = $objSingleProduct['id'];
                    $objTempOrderProduct['title'] = $objSingleProduct['title'];
                    $objTempOrderProduct['slug'] = $objSingleProduct['slug'];
                    $objTempOrderProduct['quantity'] = $objSingleProduct['quantity'];
                    $objTempOrderProduct['price'] = $objSingleProduct['price'];
                    $objTempOrderProduct['symbol'] = $objSingleProduct['symbol'];
                    $objTempOrderProduct['image_src'] = $objSingleProduct['image_src'];
                    $objTempOrderProduct['created_at'] = $objSingleProduct['created_at'];
                    $objTempOrderProduct['order_id'] = $objSingleProduct['order_id'];
                    $objfinalOrderProducts[] = $objTempOrderProduct;
                }
            }
            $response = ['currentPage' => (int)$currentPage, 'totalPages' => (int)$totalPages, 'totalRecords' => $totalRecords,  'orderProducts' => $objfinalOrderProducts];
            return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.USER_ORDER_GET_SUCCESSFULLY.code'),
                __('constants.messages.USER_ORDER_GET_SUCCESSFULLY.msg'),
                $response
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

    public function orderindex($orderid,$orderproductid)
    {
        $shipping_address = $orderProduct = $user = $objReturnOrderProduct = [];
        $latestExchangeOrderId = $latestExchangeOrderProductId = $exchangeOrderId = $exchangeOrderproductid = 0;
        $objShipmentsData['main_order_delivered'] = 0;
        $refund_status = "";

        $objOrder = Order::with(['order_products' =>function($query) use($orderproductid){
            $query->whereId($orderproductid);
        }])->whereId($orderid)->first();
        $orderNumber = $objOrder->order_nr;
        $orderDate = $objOrder->paid_at;

        $orderProduct = $objOrder->order_products->first();

        if($objOrder)
        {
            $exchangeOrderId = $orderid;
            if($objOrder->order_products)
            {
               $exchangeOrderproductid = $orderproductid; 
            }
        }
        $totalOrderProductQuantity = $orderProduct->quantity;
        $totalRefundQuantity = RefundProduct::where(['order_id' => $orderid,'product_id' => $orderProduct->product_id,'product_variant_options_id'=>$orderProduct->product_variant_options_id])->get()->sum('quantity');
        $orderProduct['quantity'] = $totalOrderProductQuantity - $totalRefundQuantity; 
        if($orderProduct['refundQuantity'] == 0)
        {
            $refund_status = 'partially_refunded';
            if($totalOrderProductQuantity == $orderProduct['refundQuantity']){
                $refund_status = 'refunded';
            }
        }

        $objReturnOrderProduct = ReturnOrderProduct::with(['return_orders'])->whereHas('return_orders',function($query) use($orderid){
        $query->where('order_id',$orderid);
        })->where(['product_id' => $orderProduct->product_id,'product_variant_options_id'=>$orderProduct->product_variant_options_id,'admin_approve'=>0])
        ->get();

        $objExistingOrder = OrderProduct::with(['order'])->whereHas('order',function($query) use($orderid){
            $query->where('parent_order_id',$orderid);
            $query->latest();
        })->where(['product_id' => $orderProduct->product_id, 'product_variant_options_id' => $orderProduct->product_variant_options_id])
        ->latest()->first();
        $exchangeRequest = 0;
        if($objExistingOrder){
                $latestExchangeOrderId = $objExistingOrder->order_id;
                $latestExchangeOrderProductId = $objExistingOrder->id;
                if($objExistingOrder->order != null)
                {
                    if($objExistingOrder->order->admin_approve == 0){
                        $exchangeRequest = 1;
                    }
                    elseif($objExistingOrder->order->admin_approve == 1){
                        $exchangeRequest = 0;
                    }
                $objOrder = $objExistingOrder->order->first();
                $orderid  = $objOrder->id;
                }
            $objOrder['order_nr'] = $orderNumber;
            $objOrder['paid_at'] = $orderDate;

            $objShipmentsData['main_order_delivered'] = 1;
        }
        $page = 'pagenotfound';
        if(!empty($objOrder))
        {
            $user = $this->checkAuthUser();
            $shipping_address = $objOrder->shipping_address;
            if($shipping_address){
                $shipping_address['Countryname'] = $shipping_address->country;
                $shipping_address['Statename'] = $shipping_address->state;
            }
            $intReturnOrderId = [];

            $orderProduct['symbol'] = $objOrder->currency->symbol;            
            $page = 'orderdata';
            $objShippingProduct = ShippingProduct::where(['order_id'=>$orderid,'product_id'=>$orderProduct->product_id,'product_variant_options_id'=>$orderProduct->product_variant_options_id])->latest()->first();
            if(!empty($objShippingProduct->shipping)){
                $shipment_order_number = $objShippingProduct->shipping->shipment_order_number;
                $objShipments = Shipments::select('id','shipping_method_id','shipment_id','is_delivered','shipment_staus_id')->where('shipment_order_number',$shipment_order_number)->latest()->first();
            }
            $trackingStatus = [];
            if(!empty($objShipments))
            {

                if($objShipments->shipping_method_id == 1){
                    $objTrackResponse = $this->shipService->handleShipping('getShippingAWBTrack', $objShipments->awb_code);
                }
                elseif($objShipments->shipping_method_id == 2){
                    $objTrackResponse = $this->ithinkService->handleShipping('getOrderTracking', [$objShipments->shipment_id]);
                }
                if($objShipments->shipping_method_id == 1){
                    $objTracking = Tracking::where('id',$objShipments->shipment_staus_id)->first();
                    if(!empty($objTracking))
                    {
                        $trackingStatus['description'] = $objTracking->description;
                    }
                }
                elseif($objShipments->shipping_method_id == 2){
                    $trackingStatus['description'] = $objShipments->shipment_staus_id;
                    }

                $objShipmentsData['trackStatus'] = $trackingStatus;
                $objShipmentsData['shipmentId'] = $objShipments->id;
                if(empty($objExistingOrder)){
                    $objShipmentsData['main_order_delivered'] = $objShipments->is_delivered;
                }
            }

        $returnorderurl = route('returnorders',['order_product_id' => $orderProduct->id ]);
        $exchangeorderurl = route('exchangeorder',['exchangeOrderId' => $exchangeOrderId, 'exchangeOrderproductid' => $exchangeOrderproductid]);
        $cancelexchangeorderurl = route('cancelexchangeorder',['latestExchangeOrderId' => $latestExchangeOrderId, 'latestExchangeOrderProductId' => $latestExchangeOrderProductId]);
        
        $data = [
            'page'                         => $page,
            'section'                      => 'orders',
            'user'                         => $user,
            'order'                        => $objOrder,
            'orderproduct'                 => $orderProduct,
            'objreturnorderproduct'        => (!empty($objReturnOrderProduct)) ? $objReturnOrderProduct : null,
            'shippingaddress'              => $shipping_address,
            'shipments'                    => $objShipmentsData,
            'exchangeRequest'              => $exchangeRequest,
            'refund_status'                => $refund_status,
            'latestExchangeOrderId'        => $latestExchangeOrderId,
            'latestExchangeOrderProductId' => $latestExchangeOrderProductId,
            'exchangeOrderId'              => $exchangeOrderId,
            'exchangeOrderproductid'       => $exchangeOrderproductid,
            'returnorderurl'               => $returnorderurl,
            'exchangeorderurl'             => $exchangeorderurl,
            'cancelexchangeorderurl'       => $cancelexchangeorderurl,
        ];
        if(false){

        }
        else{
            return view('theme.default.pages.orders', compact('data'));
        } 
      }
    }

    public function downloadInvoice($id)
    {
        try {
            $res = $this->orderInvoice($id);
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

    public function returnOrder($orderproductid)
    {
        $orderProduct = $descriptionData = $objRefundProductDescription = $objReturnOrderProductDescription = [];
        $orderProduct = OrderProduct::select('id','order_id','title','sku','slug','quantity','price','src','product_id','product_variant_options_id')->where(['id' => $orderproductid])->first();
        $objOrder = Order::whereId($orderProduct->order_id)->first();
        $page = 'pagenotfound';

        if(!empty($orderProduct)){
            $user = $this->checkAuthUser();
            $orderProduct['symbol'] = $objOrder->currency->symbol;
            $page = 'returnorder';

            $objRefundProduct = RefundProduct::select('quantity','description','updated_at','created_at')->where(['order_id'=>$orderProduct->order_id , 'product_id'=>$orderProduct->product_id, 'product_variant_options_id'=>$orderProduct->product_variant_options_id])->get();
            if($objRefundProduct->isNotEmpty())
            {
                $objRefundProductDescription =  $objRefundProduct->toArray();
            }
            $intReturnOrderId = [];
            $objReturnOrder = ReturnOrder::where('order_id',$orderProduct->order_id)->get();
            if($objReturnOrder->isNotEmpty())
            {
                $intReturnOrderId  = $objReturnOrder->pluck('id');
            }

            $objReturnOrderProduct = ReturnOrderProduct::select('admin_approve','quantity', 'description','created_at')->where(['product_id'=>$orderProduct->product_id, 'product_variant_options_id'=>$orderProduct->product_variant_options_id])->whereIn('return_order_id', $intReturnOrderId)->get();
            $orderProduct['returnQuantity'] = 0;

            if($objReturnOrderProduct->isNotEmpty())
            {
                $objReturnOrderProductDescription =  $objReturnOrderProduct->toArray();
                $orderProduct['returnQuantity'] = $objReturnOrderProduct->sum('quantity');
            }
            $orderProduct = $orderProduct->toArray();
            $orderProduct['descriptionData'] = array_merge($objRefundProductDescription,$objReturnOrderProductDescription);
            $orderProduct['descriptionData'] = collect($orderProduct['descriptionData'])->sortBy('created_at')->values()->toArray();
        }   
        
        $data = [
            'orderproduct' => $orderProduct,
            'page' => $page,
            'user' => $user,
        ];

        if(false){

        }
        else{
            return view('theme.default.pages.returnorder', compact('data'));
        }                                                                                       
    }

    public function cancelRequest(Request $request)
    {
        $params = collect($request->all());
        $objOrderProduct = OrderProduct::where('id',$params['orderproductId'])->first();
        $objReturnOrder = ReturnOrder::where('order_id',$objOrderProduct->order_id)->first();
        $objReturnOrderProduct = ReturnOrderProduct::where(['id'=>$objReturnOrder->id, 'product_id' => $objOrderProduct->product_id, 'product_variant_options_id' => $objOrderProduct->product_variant_options_id])->get();
        foreach($objReturnOrderProduct as $returnOrderProduct){
            if($returnOrderProduct->admin_approve == 0){
                $objReturnOrderProduct->cancel_request = 0;
                $objReturnOrderProduct->cancel_request_description = $params['description'];
                $objReturnOrderProduct->cancel_request_date = Carbon::now();
                $objReturnOrderProduct->save();
            }
        }
    }
    public function exchangeOrder($exchangeOrderId,$exchangeOrderproductid)
    {
        $orderProduct = $descriptionData = $objExchangeMediaSrc = $objAllExchangeMediaSrc = [];
        $user = $this->checkAuthUser();
        $page = 'pagenotfound';
        $orderProduct['client_request'] = "";

        $objOrderProduct = OrderProduct::select('id','order_id','title','sku','slug','quantity','price','src','product_id','product_variant_options_id')->where(['id' => $exchangeOrderproductid])->first();
        $objMainOrderProduct = OrderProduct::with(['order'])->whereHas('order',function($query) use($exchangeOrderId){
        $query->where('id',$exchangeOrderId);
        })->where(['product_id'=>$objOrderProduct->product_id,'product_variant_options_id'=>$objOrderProduct->product_variant_options_id])
        ->first();
        $orderProduct = $objOrderProduct->toArray();
        $totalRefundQuantity = RefundProduct::where(['order_id' => $objOrderProduct->order_id,'product_id' => $objOrderProduct->product_id,'product_variant_options_id'=>$objOrderProduct->product_variant_options_id])->get()->sum('quantity');
            if($totalRefundQuantity > 0)
            {
                $orderProduct['quantity'] = $objOrderProduct->quantity - $totalRefundQuantity; 
            }

        if($objOrderProduct)
        {
            $page = 'exchangeorder';
            $objLatestOrderProduct = OrderProduct::with(['order'])->whereHas('order',function($query) use($exchangeOrderId){
            $query->where('parent_order_id',$exchangeOrderId);
            })->where(['product_id'=>$objMainOrderProduct->product_id,'product_variant_options_id'=>$objMainOrderProduct->product_variant_options_id])
            ->latest()
            ->first();
        }
        $orderProduct['exchangeQuantity'] = 0;
        if($objLatestOrderProduct)
        {
            $orderProduct['exchangeQuantity'] = $objLatestOrderProduct->quantity;
            $orderProduct['client_request'] = $objLatestOrderProduct->client_request;
            $objLatestExchangeMedia = ExchangeMedium::where(['order_id'=>$objLatestOrderProduct['order_id'],'product_id'=>$objLatestOrderProduct['product_id'],'product_variant_options_id'=>$objLatestOrderProduct['product_variant_options_id']])->get();
            if($objMainOrderProduct->order != null){
                $orderProduct['symbol'] = $objMainOrderProduct->order->currency->symbol;
            }
            if($objLatestExchangeMedia)
                {
                    foreach($objLatestExchangeMedia as $exchangeMedia)
                    {
                        $tempExchangeMedia = [];
                        $tempExchangeMedia['id'] = $exchangeMedia->id;
                        $tempExchangeMedia['img_src'] = (!empty($exchangeMedia->src)) ? $exchangeMedia->src : '';
                        $objExchangeMediaSrc[] = $tempExchangeMedia;
                    }
                    $orderProduct['exchange_media'] = $objExchangeMediaSrc;
                }
            $orderProduct['descriptionData'] = $this->getDescriptionData($exchangeOrderId,$exchangeOrderproductid,$objMainOrderProduct->product_id,$objMainOrderProduct->product_variant_options_id);
        }

        $data = [
            'orderproduct' => $orderProduct,
            'page' => $page,
            'user' => $user,
        ];

        if(false){

        }
        else{
            return view('theme.default.pages.exchangeorder', compact('data'));
        } 
    }


    public function cancelExchangeOrder($latestExchangeOrderId,$latestExchangeOrderProductId)
    {
        $orderProduct = $descriptionData = $objExchangeMediaSrc = $objAllExchangeMediaSrc = [];
        $page = 'pagenotfound';
        $orderProduct['exchangeQuantity'] = 0;
        $orderProduct['client_request'] = "";
        $objLatestOrder = Order::whereId($latestExchangeOrderId)->latest()->first();
        $objLatestOrderProduct = OrderProduct::select('id','order_id','title','sku','slug','quantity','price','src','product_id','product_variant_options_id','client_request')->where(['id' => $latestExchangeOrderProductId])->latest()->withTrashed()->first();
        if($objLatestOrder){
            $mainOrderProduct = OrderProduct::where(['order_id' => $objLatestOrder->parent_order_id, 'product_id' => $objLatestOrderProduct->product_id, 'product_variant_options_id' => $objLatestOrderProduct->product_variant_options_id])->first();
        }
        if($objLatestOrderProduct)
        {
            $page = 'cancelexchangeorder';
            $orderProduct = $objLatestOrderProduct->toArray();
            $totalRefundQuantity = RefundProduct::where(['order_id' => $objLatestOrder->parent_order_id,'product_id' => $objLatestOrderProduct->product_id,'product_variant_options_id'=>$objLatestOrderProduct->product_variant_options_id])->get()->sum('quantity');
            $orderProduct['mainQuantity'] = $mainOrderProduct->quantity;
            if($totalRefundQuantity > 0)
            {
                $orderProduct['mainQuantity'] = $mainOrderProduct->quantity - $totalRefundQuantity; 
            }
            $objLatestExchangeMedia = ExchangeMedium::where(['order_id'=>$latestExchangeOrderId,'product_id'=>$objLatestOrderProduct['product_id'],'product_variant_options_id'=>$objLatestOrderProduct['product_variant_options_id']])->get();

            if($objLatestOrderProduct)
            {
                $user = $this->checkAuthUser();
                $orderProduct['symbol'] = $objLatestOrder->currency->symbol;
                $orderProduct['exchangeQuantity'] = $objLatestOrderProduct->quantity;
                $orderProduct['client_request'] = $objLatestOrderProduct->client_request;
            }

            if($objLatestExchangeMedia)
            {
                foreach($objLatestExchangeMedia as $exchangeMedia)
                {
                    $tempExchangeMedia = [];
                    $tempExchangeMedia['id'] = $exchangeMedia->id;
                    $tempExchangeMedia['img_src'] = (!empty($exchangeMedia->src)) ? $exchangeMedia->src : '';
                    $objExchangeMediaSrc[] = $tempExchangeMedia;
                }
                $orderProduct['exchange_media'] = $objExchangeMediaSrc;
            }

            $orderProduct['descriptionData'] = $this->getDescriptionData($objLatestOrder->parent_order_id,$mainOrderProduct->id,$objLatestOrderProduct->product_id,$objLatestOrderProduct->product_variant_options_id);
            $data = [
                'orderproduct' => $orderProduct,
                'page' => $page,
                'user' => $user,
            ];

            if(false){

            }
            else{
                return view('theme.default.pages.cancelexchangeorder', compact('data'));
            } 
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
                    __('constants.messages.ORDER_TRACKED_SUCCESSFULLY.code'),
                    __('constants.messages.ORDER_TRACKED_SUCCESSFULLY.msg'),
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
}
