<?php

namespace App\Services;

use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Http\Controllers\Traits\ApiResponser;
use Carbon\Carbon;
use Seshac\Shiprocket\Shiprocket;
use App\Models\Courier;
use App\Models\Warehouse;
use App\Models\Address;
use App\Models\Country;
use App\Models\State;

use App\Models\ShippingMethod;
use App\Models\ShippingDetail;
use App\Models\Order;
use App\Models\OrderLocation;
use App\Models\Shipments;
use App\Models\Shipping;
use App\Models\ReturnShipping;
use App\Models\ShippingProduct;
use App\Models\Weightmanage;
use App\Models\Dimension;

use Auth;
use Session;
use Config;
use Exception;
use Log;

class ShiprocketService
{
    use ApiResponser;

    protected $shiprocketToken;
    protected $shippingMethodId;

    public function __construct()
    {
         
    }

    public function getAccessToken()
    {
         $this->shippingMethodId = 0;
         if(\Schema::hasTable('shipping_details')){
            $objShippingDetails = ShippingDetail::where('name','ShipRocket')->first();
            if(!empty($objShippingDetails))
            {
                $objShippingMethods =  ShippingMethod::where('title','ShipRocket')->first();
                if(!empty($objShippingMethods))
                {
                    $this->shippingMethodId = $objShippingMethods->id;
                }

                if($objShippingDetails->email != null)
                {
                    Config::set("shiprocket.credentials.email", $objShippingDetails->email);
                    Config::set("shiprocket.credentials.password", $objShippingDetails->password);
                }
            }
        }

        $this->shiprocketToken = Shiprocket::getToken();
    }

    public function handleShipping($fnname,$data)
    {
        try {
           $this->getAccessToken();
           $data = $this->$fnname($data);
           return $data;
        } catch (Exception $e) {
            return false;
        }
    }
    
    public function createShipping($intShippingId)
    {
        $response = $objShipping = [];
        try {
            
            $objShipping = Shipping::whereId($intShippingId)->latest()->first();
            $shipmentOrderNumber = $objShipping->shipment_order_number;
            if(!empty($objShipping)) 
            {
                $pickupLocation = Warehouse::where('pickup_id',$objShipping->pickup_id)->first()->pickup_code;
                $arrObjShippingProducts = $this->getShippingProducts($objShipping->id);
                $objOrderLocationShipping = $this->getShippingAddressData($objShipping->order_id);
                $objOrderLocationBilling = $this->getBillingAddressData($objShipping->order_id); 
               
                $weight = $this->handleWeightManagement($objShipping->weight, $objShipping->weight_type->short_code, "kg");
                $length = $this->handleDimensionManagement($objShipping->length, $objShipping->length_type->short_code, "cm");
                $width =  $this->handleDimensionManagement($objShipping->width, $objShipping->width_type->short_code, "cm");
                $height = $this->handleDimensionManagement($objShipping->height, $objShipping->height_type->short_code, "cm");

                $orderShippingData = [
                    'selling_price' => $objShipping->selling_price,
                    'payment_method' => $objShipping->payment_mode,
                    'shipping_charges' => $objShipping->shipping_charges,
                    'giftwrap_charges' => $objShipping->giftwrap_charges,
                    'transaction_charges' => $objShipping->transaction_charges,
                    'total_discount' => $objShipping->total_discount,
                    'sub_total' => $objShipping->sub_total
                ];

                $orderDetails = [
                    'order_id' => $shipmentOrderNumber,
                    'order_date' =>  Carbon::createFromFormat('Y-m-d H:i:s', $objShipping->created_at)->format('Y-m-d H:i'),
                    'pickup_location' => $pickupLocation,
                    'billing_customer_name' => $objOrderLocationBilling['billing_customer_name'],
                    'billing_last_name' => $objOrderLocationBilling['billing_last_name'],
                    'billing_address' => $objOrderLocationBilling['billing_address'],
                    'billing_address_2' => $objOrderLocationBilling['billing_address_2'],
                    'billing_city' => $objOrderLocationBilling['billing_city'],
                    'billing_pincode' => $objOrderLocationBilling['billing_pincode'],
                    'billing_state' => $objOrderLocationBilling['billing_state'],
                    'billing_country' => $objOrderLocationBilling['billing_country'],
                    'billing_email' => $objOrderLocationBilling['billing_email'],
                    'billing_phone' => $objOrderLocationBilling['billing_phone'],
                    'shipping_is_billing' => false,
                    'shipping_customer_name' => $objOrderLocationShipping['shipping_customer_name'],
                    'shipping_last_name' => $objOrderLocationShipping['shipping_last_name'],
                    'shipping_address' => $objOrderLocationShipping['shipping_address'],
                    'shipping_address_2' => $objOrderLocationShipping['shipping_address_2'],
                    'shipping_city' => $objOrderLocationShipping['shipping_city'],
                    'shipping_pincode' => $objOrderLocationShipping['shipping_pincode'],
                    'shipping_state' => $objOrderLocationShipping['shipping_state'],
                    'shipping_country' => $objOrderLocationShipping['shipping_country'],
                    'shipping_email' => $objOrderLocationShipping['shipping_email'],
                    'shipping_phone' => $objOrderLocationShipping['shipping_phone'],
                    'order_items' => $arrObjShippingProducts,
                    'payment_method' => $orderShippingData['payment_method'],
                    'shipping_charges' =>  (int) $orderShippingData['shipping_charges'],
                    'giftwrap_charges' =>  (int) $orderShippingData['giftwrap_charges'],
                    'transaction_charges' => (int)  $orderShippingData['shipping_charges'],
                    'total_discount' =>  (int) $orderShippingData['total_discount'],
                    'sub_total' =>  (int) $orderShippingData['sub_total'],
                    'length' => (float) $length,
                    'breadth' => (float) $width,
                    'height' => (float) $height,
                    'weight' => $weight
                ];
                $objResponse =  Shiprocket::order($this->shiprocketToken)->create($orderDetails);
                if(isset($objResponse['status_code']) && $objResponse['status_code'] == 1)
                {
                    $objShipmenets = new Shipments;
                    $objShipmenets->shipment_order_number = $objShipping->shipment_order_number;
                    $objShipmenets->order_id = $objShipping->order_id;
                    $objShipmenets->shipment_order_id = $objResponse['order_id'];
                    $objShipmenets->shipment_id = $objResponse['shipment_id'];
                    $objShipmenets->shipping_method_id = $this->shippingMethodId;
                    $objShipmenets->cod = $orderDetails['payment_method'];
                    $objShipmenets->data = json_encode($objResponse);
                    $objShipmenets->save();
                    $intCourierId = $this->getCourierId($objShipping->courier_id);
                    $this->setAWB(['shipment_id' => $objResponse['shipment_id'], 'courier_id' => $intCourierId]);
                    $this->setRequestPickup($objResponse['shipment_id']);
                }
                else
                {
                    Log::info("shippingOrderRequest",$orderDetails);
                    Log::info("shippingOrder",$objResponse);
                    $message = $objResponse['message'];
                    if(isset($objResponse['errors']))
                    {
                        if(isset($objResponse['errors']['billing_phone']))
                        {
                            $message .= $objResponse['errors']['billing_phone'][0];
                        } 

                        if(isset($objResponse['errors']['shipping_phone']))
                        {
                            $message .= $objResponse['errors']['shipping_phone'][0];
                        } 
                    }
                    $this->displayOurCustomMessage($message);            
                }
            }
        } catch (Exception $e) {
            
        }
      
        return $objResponse;

    }


    public function setAWB($data)
    {   
        $objResponse = [];
        try {
            $data = [
                'shipment_id' => $data['shipment_id'],
                'courier_id' => $data['courier_id']
            ];
            $objResponse =  Shiprocket::courier($this->shiprocketToken)->generateAWB($data);
            if($objResponse['awb_assign_status'])
            {
                Shipments::where('shipment_id', $data['shipment_id'])->update(['shipment_staus_id' => 6,'awb_code' => $objResponse['response']['data']['awb_code'], 'courier_id' => $objResponse['response']['data']['courier_company_id'], 'awb_data' => json_encode($objResponse)]);
            }
            else
            {
                Log::info("shippingOrderAWBRequest",$data);
                Log::info("shippingOrderAWB",$objResponse);
                $this->displayOurCustomMessage($objResponse['message']);            
            }
            
        } catch (Exception $e) {
            
        }
        return $objResponse;
    }

    public function setNewPickupLocation($data)
    {
        $orderDetails = [
           'order_id' => $data['arrOrderIds'],
           'pickup_location' => $data['location_name']
        ];

        $response =  Shiprocket::order($this->shiprocketToken)->updatePickupLocation($orderDetails);
        Shipments::whereIn('order_id', $arrOrderIds)->update(['location_name' => $location_name, 'pickup_data' => json_encode($response) ]);
        return $response;
    }

    public function setRequestPickup($arrShipmentIds)
    {
        $objResponse = [];
        try {
            $pickupDetails = [
                'shipment_id' => $arrShipmentIds
            ];
            $objResponse =  Shiprocket::courier($this->shiprocketToken)->requestPickup($pickupDetails);
            if($objResponse['pickup_status'])
            {
                Shipments::whereIn('shipment_id', [$arrShipmentIds])->update(['pickup_request_data' => json_encode($objResponse)]);
            }
            else
            {
                Log::info("shippingOrderRequestPickupRequest",$pickupDetails);
                Log::info("shippingOrderRequestPickup",$objResponse);
            }
        } catch (Exception $e) {
            
        }
        return $objResponse;
    }

    public function getRate($params)
    {
        $objResponse = [];
        try {
            
            $weight_type = Weightmanage::whereId($params['weight_type_id'])->first()->short_code;
            $length_type = Dimension::whereId($params['length_type_id'])->first()->short_code;
            $width_type = Dimension::whereId($params['width_type_id'])->first()->short_code;
            $height_type = Dimension::whereId($params['height_type_id'])->first()->short_code;

            $weight = $this->handleWeightManagement($params['weight'], $weight_type, "kg");
            $length = $this->handleDimensionManagement($params['length'], $length_type, "cm");
            $width =  $this->handleDimensionManagement($params['width'], $width_type, "cm");
            $height = $this->handleDimensionManagement($params['height'], $height_type, "cm");


            $intWarehouseAddressId = Warehouse::where('pickup_id',$params['pickup_id'])->first()->address_id;
            $fromPincode = Address::whereId($intWarehouseAddressId)->first()->postal_code;
            $toPincode = $params['to_pincode'];

            if($params['order_type'] == "Reverse")
            {
                $fromPincode = $params['to_pincode'];
                $toPincode = Address::whereId($intWarehouseAddressId)->first()->postal_code;
            }
            $checkPincode = [
                'pickup_postcode' => $fromPincode,
                'delivery_postcode' => $toPincode,
                'cod' => ($params['payment_mode'] == "Prepaid")?0:1,
                'weight' => $weight,
                'length' => $length,
                'breadth' => $width,
                'height' => $height,
            ];
            
            $objResponse =  Shiprocket::courier($this->shiprocketToken)->checkServiceability($checkPincode);
            if($objResponse['status'] == 200)
            {
                foreach ($objResponse['data']['available_courier_companies'] as $key => $available_courier) {
                    
                    $objCourier = Courier::where('name',$available_courier['courier_name'])->first();
                    if(empty($objCourier)){
                        $objCourier = new Courier;
                        $objCourier->name = $available_courier['courier_name'];
                        $objCourier->courier_code = $available_courier['courier_company_id'];
                        $objCourier->shipping_method_id = $this->shippingMethodId;
                        $objCourier->save();
                    }
                }
                if($params['order_type'] == "Reverse")
                {
                    ReturnShipping::whereId($params['return_shipping_id'])->update(['rate_data' => $objResponse]);
                }
                else{
                    
                    Shipping::whereId($params['shipping_id'])->update(['rate_data' => $objResponse]);
                }
                return $objResponse;
            }
            else
            {
                Log::info("shippingOrderGetRateRequest",$checkPincode);
                Log::info("shippingOrderGetRate",$objResponse);
                $this->displayOurCustomMessage($objResponse['message']);            
            }
        } catch (Exception  $e) {
           
        }
        return $objResponse;
    }

    public function setPickupLocation($params)
    {
        $objResponse = [];
        $newLocation = [
            'pickup_location' => $params['location_name'],
            'name' => Auth::user()->name,
            'email' => Auth::user()->email,
            'phone' => $params['mobile'],
            'address' => $params['address'],
            'address_2' => $params['address_2'],
            'city' => $params['city_name'],
            'state' => $params['state']['name'],
            'country' => $params['country']['name'],
            'pin_code' => $params['postal_code']
        ];
        $objResponse =  Shiprocket::pickup($this->shiprocketToken)->addLocation($newLocation);
        if(isset($objResponse['success']))
        {
            $objWarehouse = new Warehouse;
            $objWarehouse->shipping_method_id = ShippingMethod::where('title','ShipRocket')->first()->id;
            $objWarehouse->pickup_id = $objResponse['pickup_id'];
            $objWarehouse->pickup_code = $objResponse['address']['pickup_code']; 
            $objWarehouse->status = 1; 
            $objWarehouse->data = json_encode($objResponse);
            $objWarehouse->address_id = $params['id'];
            $objWarehouse->save();
        }
        else
        {
            Log::info("shippingPickUpLocationRequest",$newLocation);
            Log::info("shippingPickUpLocation",$objResponse);
            $this->displayOurCustomMessage($objResponse['message']);            
        }
        return $objResponse;
    }

 
    public function generateManifest($arrShipmentIds)
    {
        $objResponse = [];
        try {
            $shipmentIds = [ 'shipment_id' => $arrShipmentIds ];
            $objResponse = Shiprocket::generate($this->shiprocketToken)->manifest($shipmentIds);
            if(isset($objResponse['status']))
            {
                Shipments::whereIn('shipment_id',$arrShipmentIds)->update(['generate_manifest_url' => $objResponse['manifest_url']]);
            }
            else
            {
                 Log::info("shippingGenerateManifestRequest",$shipmentIds);
                 Log::info("shippingGenerateManifest",$objResponse);
                $this->displayOurCustomMessage($objResponse['message']);
            }
        } catch (Exception $e) {
            
        }
        return $objResponse;
    }

    public function printManifest($arrShipmentOrderIds)
    {
        $response = [];
        try {
            $shipmentOrderIds = [ 'order_ids' => $arrShipmentOrderIds ];
            $objResponse = Shiprocket::generate($this->shiprocketToken)->printManifest($shipmentOrderIds);
            if(isset($objResponse['manifest_url']))
            {   
                Shipments::whereIn('shipment_order_id',$arrShipmentOrderIds)->update(['print_manifest_url' => $objResponse['manifest_url']]);
            }
            else
            {
                 Log::info("shippingPrintManifestRequest",$shipmentOrderIds);
                 Log::info("shippingPrintManifest",$objResponse);
                $this->displayOurCustomMessage($objResponse['message']);
            }
        } catch (Exception $e) {
            
        }
        return $response;
    }

    public function generateLabel($arrShipmentIds)
    {
        $response = [];
        try {
            $shipmentIds = [ 'shipment_id' => $arrShipmentIds ];
            $objResponse = Shiprocket::generate($this->shiprocketToken)->label($shipmentIds);
            if(isset($objResponse['label_created']))
            {
                Shipments::whereIn('shipment_id',$arrShipmentIds)->update(['label_url' => $objResponse['label_url']]);
            }
            else
            {
                Log::info("shippingGenerateLabelRequest",$shipmentIds);
                Log::info("shippingGenerateLabel",$objResponse);
                $this->displayOurCustomMessage($objResponse['message']);
            }
        } catch (Exception $e) {
            
        }
        return $response;
    }

    public function printInvoiceManifest($arrShipmentOrderIds)
    {
        $response = [];
        try {
            $shipmentOrderIds = [ 'ids' => $arrShipmentOrderIds ];
            $objResponse = Shiprocket::generate($this->shiprocketToken)->invoice($shipmentOrderIds);
            if(isset($objResponse['is_invoice_created']))
            {
                Shipments::whereIn('shipment_order_id',$arrShipmentOrderIds)->update(['invoice_url' => $objResponse['invoice_url']]);
            }
            else
            {
                Log::info("shippingPrintInvoiceRequest",$shipmentOrderIds);
                Log::info("shippingPrintInvoice",$objResponse);
                $this->displayOurCustomMessage($objResponse['message']);
            }
            
        } catch (Exception $e) {
            
        }
        return $response;
    }

    public function getShippingAWBTrack($awb)
    {
        $objResponse = [];
        try {
            $objShipment = Shipments::where('awb_code',$awb)->latest()->first();
            if(!$objShipment->is_delivered)
            {
                $objResponse = Shiprocket::track($this->shiprocketToken)->throughAwb($awb);
                if($objResponse['tracking_data']['track_status'])
                {
                    $isDeliverd = 0;
                    if($objResponse['tracking_data']['shipment_status'] == 7)
                    {
                        $isDeliverd = 1;
                    }
                     $shipment_status_id =  $objShipment->shipment_staus_id;
                        if(in_array($objResponse['tracking_data']['shipment_status'], ["6","17","8"]))
                        {
                            $shipment_status_id = $objResponse['tracking_data']['shipment_status'];
                        }
                        

                    Shipments::where('awb_code',$awb)->update(['shipment_staus_id' => $shipment_status_id,'track_data' => $objResponse, 'track_url' => $objResponse['tracking_data']['track_url'], 'is_delivered' => $isDeliverd ]);
                }
                else
                {
                    Log::info("shippingOrderTrackRequest",$awb);
                    Log::info("shippingOrderTrack",$objResponse);
                     $this->displayOurCustomMessage($objResponse['tracking_data']['error']);
                }
            }
            else
            {
                    $objResponse = collect(json_decode($objShipment->track_data));
            }
        } catch (Exception $e) {
            
             $objShipment = Shipments::where('awb_code',$awb)->first();
             if(!empty($objShipment))
             {
                $objResponse = collect(json_decode($objShipment->track_data));
             }
        }

        return $objResponse;
    }

    public function getMultipleShippingAWBTrack($arrayAwb)
    {
        $response = Shiprocket::track($this->shiprocketToken)->throwMultipleAwb($arrayAwb);
        foreach ($response as $awb => $objShipment) {
            Shipments::where('awb_code',$awb)->update(['track_data' => $objShipment[$awb], 'track_url' => $objShipment['tracking_data']['track_url'] ]);
        }
        return $response;
    }
    
    public function getTrackWithshippingId($intShipmentId)
    {
        $response = [];
        try {
            
            $objResponse = Shiprocket::track($this->shiprocketToken)->throwShipmentId($intShipmentId);
            if($objResponse['tracking_data']['track_status'])
            {
                Shipments::where('shipment_id',$intShipmentId)->update(['track_data' => $objResponse, 'track_url' => $objResponse['tracking_data']['track_url'] ]);
            }
            else
            {
                Log::info("shippingOrderTrackWithishippingIdRequest",$intShipmentId);
                Log::info("shippingOrderTrackWithishippingId",$objResponse);
            }
        } catch (Exception $e) {
            $objShipment = Shipments::where('shipment_id',$intShipmentId)->first();
             if(!empty($objShipment))
             {
                $objResponse = json_decode($objShipment->track_data, true);
             }
        }
        return $objResponse;
    }

    public function getTrackWithOrderId($intShipmentOrderNumber)
    {
        $objResponse = [];
        try {
            $objResponse = Shiprocket::track($this->shiprocketToken)->throwOrderId($intShipmentOrderNumber);
            if($objResponse['tracking_data']['track_status'])
            {
                Shipments::where('shipment_order_number',$intShipmentOrderNumber)->update(['track_data' => $objResponse, 'track_url' => $objResponse['tracking_data']['track_url'] ]);
            }
            else
            {
                Log::info("shippingOrderTrackWithOrderIdRequest",$intShipmentOrderNumber);
                Log::info("shippingOrderTrackWithOrderId",$objResponse);
            }
            
        } catch (Exception $e) {

            $objShipment = Shipments::where('shipment_order_number',$intShipmentOrderNumber)->first();
             if(!empty($objShipment))
             {
                $objResponse = collect(json_decode($objShipment->track_data));
             }
        }
        return $objResponse;
    }

    public function getChannelId(){
        $objResponse = Shiprocket::channel($this->shiprocketToken)->get();
        if(!isset($objResponse['status_code']))
        {
            return $objResponse['data'][0]['id'];
        }
        return 0;
    }

    public function getWarehouse($data){
        return Shiprocket::pickup($this->shiprocketToken)->getLocations();
    }

    public function setReturnOrder($intReturnShippingId)
    {

        $response = [];
        try {
            $objReturnShipping = ReturnShipping::whereId($intReturnShippingId)->latest()->first();
            if(!empty($objReturnShipping)) 
            {
                $pickupLocationAddressId = Warehouse::where('pickup_id',$objReturnShipping->pickup_id)->first()->address_id;
                $arrObjReturnShippingProducts = $this->getReturnShippingProducts($objReturnShipping->id);
                $objWarehouseAddress = $this->getWarehouseAddress($pickupLocationAddressId);
                $objOrderLocation = $this->getShippingAddressData($objReturnShipping->order_id);
               
                $weight = $this->handleWeightManagement($objReturnShipping->weight, $objReturnShipping->weight_type->short_code, "kg");
                $length = $this->handleDimensionManagement($objReturnShipping->length, $objReturnShipping->length_type->short_code, "cm");
                $width =  $this->handleDimensionManagement($objReturnShipping->width, $objReturnShipping->width_type->short_code, "cm");
                $height = $this->handleDimensionManagement($objReturnShipping->height, $objReturnShipping->height_type->short_code, "cm");

                $intChannelId = $this->getChannelId();

                $orderReturnShippingData = [
                    'selling_price' => $objReturnShipping->selling_price,
                    'payment_method' => $objReturnShipping->payment_mode,
                    'shipping_charges' => $objReturnShipping->shipping_charges,
                    'transaction_charges' => $objReturnShipping->transaction_charges,
                    'total_discount' => $objReturnShipping->total_discount,
                    'sub_total' => $objReturnShipping->sub_total
                ];

            $returnData =[
                'order_id' => $objReturnShipping->return_shipment_order_number,
                'order_date' =>  Carbon::createFromFormat('Y-m-d H:i:s', $objShipping->created_at)->format('Y-m-d H:i'),
                'channel_id' => $intChannelId,
                'pickup_customer_name' => $objWarehouseAddress['warehouse_customer_name'],
                'pickup_last_name' => $objWarehouseAddress['warehouse_last_name'],
                'pickup_address' => $objWarehouseAddress['warehouse_address'],
                'pickup_address_2' => $objWarehouseAddress['warehouse_address_2'],
                'pickup_city' => $objWarehouseAddress['warehouse_city'],
                'pickup_state' => $objWarehouseAddress['warehouse_state'],
                'pickup_country' => $objWarehouseAddress['warehouse_country'],
                'pickup_pincode' => $objWarehouseAddress['warehouse_pincode'],
                'pickup_email' => $objWarehouseAddress['warehouse_email'],
                'pickup_phone' => $objWarehouseAddress['warehouse_phone'],
                'shipping_customer_name' => $objOrderLocation['shipping_customer_name'],
                'shipping_last_name' => $objOrderLocation['shipping_last_name'],
                'shipping_address' => $objOrderLocation['shipping_address'],
                'shipping_address_2' =>  $objOrderLocation['shipping_address_2'],
                'shipping_city' => $objOrderLocation['shipping_city'],
                'shipping_state' => $objOrderLocation['shipping_state'],
                'shipping_country' => $objOrderLocation['shipping_country'],
                'shipping_pincode' => $objOrderLocation['shipping_pincode'],
                'shipping_email' => $objOrderLocation['shipping_email'],
                'shipping_phone' => $objOrderLocation['shipping_phone'],
                'order_items' => $arrObjReturnShippingProducts,
                'payment_method' => 'PREPAID',
                'total_discount' => '0',
                'sub_total' => $orderReturnShippingData['sub_total'],
                'length' => $length,
                'breadth' => $width,
                'height' => $height,
                'weight' => $weight
            ];
            $client = new \GuzzleHttp\Client();

            $headers = [
                'Authorization' => 'Bearer '.$this->shiprocketToken,
                'Content-Type' => 'application/json',
            ];

            $response  = $client->post('https://apiv2.shiprocket.in/v1/external/orders/create/return', $headers, $returnData);
            $objResponse =  json_decode($response->getBody(), true);

            if(!isset($objResponse['message']))
            {
                $objShipment = new Shipments;
                $objShipment->order_id = $objReturnShipping->order_id;
                $objShipment->shipment_order_id = $objResponse['order_id'];
                $objShipment->shipment_order_number = $objReturnShipping['return_shipment_order_number'];
                $objShipment->shipment_id = $objResponse['shipment_id'];
                $objShipment->channel_id = $intChannelId;
                $objShipment->cod = $returnData['payment_method'];
                $objShipment->data = json_encode($objResponse);
                $objShipment->save();
                $intCourierId = $this->getCourierId($objReturnShipping->courier_id);
                $this->setAWB(['shipment_id' => $objResponse['shipment_id'], 'courier_id' => $intCourierId]);
                $this->setRequestPickup($objResponse['shipment_id']);
            }
            else
            {
                Log::info("ReturnShippingOrderRequest",$returnData);
                Log::info("ReturnShippingOrder",$objResponse);
                $this->displayOurCustomMessage($objResponse['message']);            
            }

        }
           
        } catch (Exception $e) {
            
        }
        return $response;
    }


    public function cancelShipping($arrShipmentOrderIds)
    {
        $objResponse =  Shiprocket::order($this->shiprocketToken)->cancel(['ids' => $arrShipmentOrderIds]);
        Shipments::whereIn('shipment_order_id',$arrShipmentOrderIds)->update(['cancel_data' => json_encode($objResponse)]);
        if(isset($objResponse['status']) && $objResponse['status'] != 200 )
        {
            Log::info("CancelShippingOrderRequest",$arrShipmentOrderIds);
            Log::info("CancelShippingOrder",$objResponse);
            $this->displayOurCustomMessage($objResponse['message']);
        }
        
        return $objResponse;
    }

    public function getAllShipOrder()
    {
        $orderDetails = [
        'per_page' => 20,
        ];
        $response = Shiprocket::order($this->shiprocketToken)->getOrders($orderDetails);
        return $response;
    }

    public function getInternationalService()
    {
        $pincodeDetails = [
            'weight' => '',
            'cod' => 0,
            'delivery_country' => '',
            'order_id' => '',
            'pickup_postcode' => ''
        ];
        $response = Shiprocket::courier($this->shiprocketToken)->checkInterNationalServiceability($pincodeDetails);

    }

    public function getBillingAddressData($intShippingOrderId)
    {
        $billingData = [
                'billing_customer_name' => '',
                'billing_last_name' => '',
                'billing_address' => '',
                'billing_address_2' => '',
                'billing_city' => '',
                'billing_pincode' => '',
                'billing_state' => '',
                'billing_country' => '',
                'billing_email' => '',
                'billing_phone' => ''
            ];
        $objOrder = Order::whereId($intShippingOrderId)->latest()->first();
        if(!empty($objOrder))
        {

            if($objOrder->parent_order_id == null)
            {
                $objOrderLocationBilling = OrderLocation::where(['order_id'=>$intShippingOrderId, 'status' => 1])->latest()->first();
            }
            else
            {
                $objOrderLocationBilling = OrderLocation::where(['order_id'=>$objOrder->parent_order_id, 'status' => 1])->latest()->first();
            }
            if(!empty($objOrderLocationBilling))
            {
                $billingData = [
                    'billing_customer_name' => $objOrderLocationBilling->first_name,
                    'billing_last_name' => $objOrderLocationBilling->last_name,
                    'billing_address' => $objOrderLocationBilling->address,
                    'billing_address_2' => $objOrderLocationBilling->address_2,
                    'billing_city' => $objOrderLocationBilling->city_name,
                    'billing_pincode' => $objOrderLocationBilling->postal_code,
                    'billing_state' => $objOrderLocationBilling->state,
                    'billing_country' => $objOrderLocationBilling->country,
                    'billing_email' => $objOrderLocationBilling->email,
                    'billing_phone' => $objOrderLocationBilling->phone_code.$objOrderLocationBilling->mobile
                ];
                return $billingData;
            }
        }
            return $billingData;
    }

    public function getShippingAddressData($intShippingOrderId)
    {
        $shippingData = [
                'shipping_customer_name' => '',
                'shipping_last_name' => '',
                'shipping_address' => '',
                'shipping_address_2' => '',
                'shipping_city' => '',
                'shipping_pincode' => '',
                'shipping_state' => '',
                'shipping_country' => '',
                'shipping_email' => '',
                'shipping_phone' => ''
            ];
        $objOrder = Order::whereId($intShippingOrderId)->latest()->first();
        if(!empty($objOrder))
        {
            if($objOrder->parent_order_id == null)
            {
                $objOrderLocationShipping = OrderLocation::where(['order_id'=>$intShippingOrderId, 'status' => 0])->latest()->first();
            }
            else
            {
                $objOrderLocationShipping = OrderLocation::where(['order_id'=>$objOrder->parent_order_id, 'status' => 0])->latest()->first();
            }
            if(!empty($objOrderLocationShipping))
            {
                $shippingData = [
                    'shipping_customer_name' => $objOrderLocationShipping->first_name,
                    'shipping_last_name' => $objOrderLocationShipping->last_name,
                    'shipping_address' => $objOrderLocationShipping->address,
                    'shipping_address_2' => $objOrderLocationShipping->address_2,
                    'shipping_city' => $objOrderLocationShipping->city_name,
                    'shipping_pincode' => $objOrderLocationShipping->postal_code,
                    'shipping_state' => $objOrderLocationShipping->state,
                    'shipping_country' => $objOrderLocationShipping->country,
                    'shipping_email' => $objOrderLocationShipping->email,
                    'shipping_phone' => $objOrderLocationShipping->phone_code.$objOrderLocationShipping->mobile
                ];
                return $shippingData;
            }
        }
        return [];
    }

    public function getShippingProducts($intShippingId)
    {
        $arrObjShippingProducts = [];
         $objShippingProducts = ShippingProduct::where('shipping_id', $intShippingId)->get();
            if($objShippingProducts->isNotEmpty())
            {
                foreach ($objShippingProducts as $key => $objShippingProduct) {
                    $tempOrderData =  [
                            'name' => $objShippingProduct['title'],
                            'sku' => $objShippingProduct['sku'],
                            'units' => (int) $objShippingProduct['quantity'],
                            'selling_price' => (float) $objShippingProduct['selling_price']
                        ];
                        $arrObjShippingProducts[] = $tempOrderData;
                }
            }
        return $arrObjShippingProducts;
    }

    public function getReturnShippingProducts($intReturnShippingId)
    {
        $arrObjReturnShippingProducts = [];
         $objReturnShippingProducts = ReturnShippingProduct::where('return_shipping_id', $intReturnShippingId)->get();
            if($objReturnShippingProducts->isNotEmpty())
            {
                foreach ($objReturnShippingProducts as $key => $objReturnShippingProduct) {
                    $tempOrderData =  [
                            'name' => $objReturnShippingProduct['title'],
                            'sku' => $objReturnShippingProduct['sku'],
                            'units' => (int) $objReturnShippingProduct['quantity'],
                            'selling_price' => (float) $objReturnShippingProduct['selling_price']
                        ];
                        $arrObjReturnShippingProducts[] = $tempOrderData;
                }
            }
        return $arrObjReturnShippingProducts;
    }

    public function getWarehouseAddress($address_id)
    {
        $warehouseData = [
                'warehouse_customer_name' => '',
                'warehouse_last_name' => '',
                'warehouse_address' => '',
                'warehouse_address_2' => '',
                'warehouse_city' => '',
                'warehouse_pincode' => '',
                'warehouse_state' => '',
                'warehouse_country' => '',
                'warehouse_email' => '',
                'warehouse_phone' => ''
            ];
        $objAddress = Address::whereId($address_id)->latest()->first();
        if(!empty($objAddress))
        {
            $warehouseData = [
                'warehouse_customer_name' => $objAddress->location_name,
                'warehouse_last_name' => '',
                'warehouse_address' => $objAddress->address,
                'warehouse_address_2' => $objAddress->address_2,
                'warehouse_city' => $objAddress->city_name,
                'warehouse_pincode' => $objAddress->postal_code,
                'warehouse_state' => $objAddress->state,
                'warehouse_country' => $objAddress->country,
                'warehouse_email' => $objAddress->email,
                'warehouse_phone' => $objAddress->phone_code.$objAddress->mobile
            ];
            return $warehouseData;
        }
        return [];
    }

    public function getCourierId($courier_name)
    {
        $intCourierId= 0;
        $objCourier = Courier::where(['name' =>  $courier_name, 'shipping_method_id' => $this->shippingMethodId ])->first();
        if(!empty($objCourier))
        {
            $intCourierId = $objCourier->courier_code;
        }
        return $intCourierId;
    }

}
