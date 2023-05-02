<?php

namespace App\Services;

use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Http\Controllers\Traits\ApiResponser;
use Carbon\Carbon;
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
use App\Models\ShippingProduct;
use App\Models\Weightmanage;
use App\Models\Dimension;
use App\Models\ReturnShipping;
use App\Models\ReturnShippingProduct;

use Auth;
use Session;
use Config;
use Illuminate\Support\Facades\Log;


class IthinklogisticsService
{
    use ApiResponser;

    protected $accessToken;
    protected $secretKey;
    protected $testMode;
    protected $shippingMethodId;
    protected $shippingtype;

    public function getAccessToken()
    {
        $this->accessToken = env('ITHINKLOGISTICSTOKEN');
        $this->secretKey =  env('ITHINKLOGISTICSKEY');   
        $this->testMode =  env('ITHINKLOGISTICTESTMODE');   
        $this->shippingMethodId = 0;
         if(\Schema::hasTable('shipping_details')){
            $objShippingDetails = ShippingDetail::where('name','Ithinklogistics')->first();
            $objShippingMethods =  ShippingMethod::where('title','Ithinklogistics')->first();
            if(!empty($objShippingMethods))
            {
                $this->shippingMethodId = $objShippingMethods->id;
            }
            if(!empty($objShippingDetails))
            {
                $this->accessToken = $objShippingDetails->access_token;
                $this->secretKey = $objShippingDetails->secret_key;
                $this->testMode = $objShippingDetails->test_mode;
            }
        }
    }

    public function handleShipping($fnname,$data)
    {
        try {
           $this->getAccessToken();
           $objResponse = $this->$fnname($data);
           return $objResponse;
        } catch (Exception $e) {
            return false;
        }
    }

    public function createShipping($intShippingId, $orderType='forward')
    {
        $objResponse = $objShipping = [];
        try {
            $this->shippingtype = $orderType;
            if($orderType == "forward")
            {
                $objShipping = Shipping::whereId($intShippingId)->latest()->first();
                $shipmentOrderNumber = $objShipping->shipment_order_number;
            }
            else
            {
                $objShipping = ReturnShipping::whereId($intShippingId)->latest()->first();
                $shipmentOrderNumber = $objShipping->return_shipment_order_number;
            }
            if(!empty($objShipping)) 
            {
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
                $cod_amount = '0';
                if($orderType == 'forward')
                {
                    if($orderShippingData['payment_method'] == "cod")
                    {
                        $cod_amount = $orderShippingData['sub_total'];
                    }
                }

                $payment_mode = 'Prepaid';
                if($orderType == 'forward')
                {
                    if($orderShippingData['payment_method'] == "cod")
                    {
                        $payment_mode = 'COD';
                    }
                }
                $orderDetails = [
                    'waybill' =>  $shipmentOrderNumber,
                    'order' => $shipmentOrderNumber,
                    'sub_order' => "",
                    'order_date' =>  Carbon::createFromFormat('Y-m-d H:i:s', $objShipping->created_at)->format('Y-m-d H:i'),
                    'total_amount' => (float) $orderShippingData['sub_total'],
                    'name' => $objOrderLocationShipping['shipping_customer_name']." ".$objOrderLocationShipping['shipping_last_name'],
                    'company_name' => config('app.name'),
                    'add' => $objOrderLocationShipping['shipping_address'],
                    'add2' => $objOrderLocationShipping['shipping_address_2'],
                    'pin' => (int) $objOrderLocationShipping['shipping_pincode'],
                    'city' => $objOrderLocationShipping['shipping_city'],
                    'state' => $objOrderLocationShipping['shipping_state'],
                    'country' => $objOrderLocationShipping['shipping_country'],
                    'phone' => (int) $objOrderLocationShipping['shipping_phone'],
                    'alt_phone' => "",
                    'email' => $objOrderLocationShipping['shipping_email'],
                    'is_billing_same_as_shipping' => "No",
                    'billing_name' => $objOrderLocationBilling['billing_customer_name']." ".$objOrderLocationBilling['billing_last_name'],
                    'billing_add' => $objOrderLocationBilling['billing_address'],
                    'billing_add2' => $objOrderLocationBilling['billing_address_2'],
                    'billing_pin' => (int) $objOrderLocationBilling['billing_pincode'],
                    'billing_city' => $objOrderLocationBilling['billing_city'],
                    'billing_state' => $objOrderLocationBilling['billing_state'],
                    'billing_country' => $objOrderLocationBilling['billing_country'],
                    'billing_phone' => $objOrderLocationBilling['billing_phone'],
                    'billing_email' => $objOrderLocationBilling['billing_email'],
                    'products' => $arrObjShippingProducts,
                    'shipment_length' => (float) $length,
                    'shipment_width' => (float) $width,
                    'shipment_height' => (float) $height,
                    'weight' => $weight,
                    'shipping_charges' => (float) $orderShippingData['shipping_charges'],
                    'giftwrap_charges' => (float) $orderShippingData['giftwrap_charges'],
                    'transaction_charges' => (float) $orderShippingData['shipping_charges'],
                    'total_discount' => (float) $orderShippingData['total_discount'],
                    'cod_charges' => "0",
                    'advance_amount' => "0",
                    'cod_amount' => $cod_amount,
                    'payment_mode' => $payment_mode,
                    'return_address_id' => (int) $objShipping->pickup_id,
                    'gst_number' => "",
                    'eway_bill_number' => "",
                    'reseller_name' => "",
                    'first_attemp_discount' => "0",
                    'api_source' => "1",
                ];
                $finaldata = ['data' => 
                                ['shipments' => [$orderDetails], 
                                'pickup_address_id' => (int) $objShipping->pickup_id,
                                'access_token' => $this->accessToken,
                                'secret_key' => $this->secretKey,
                                'logistics' => $objShipping->courier_id,
                                'order_type' => $orderType,
                                's_type' => 'standard'
                                ]
                             ];
                $orderUrl = "pre-alpha";
                if(!$this->testMode)
                {
                    $orderUrl = "my";
                }

                $finalOrderUrl = "https://".$orderUrl.".ithinklogistics.com/api_v3/order/add.json";
                $response = $this->callCurl($finalOrderUrl, $finaldata);
                $objResponse = json_decode($response, true);
                if($objResponse['status'] != 'error')
                {
                    $objShipmenets = new Shipments;
                    $objShipmenets->shipment_order_number = $shipmentOrderNumber;
                    $objShipmenets->order_id = $objShipping->order_id;
                    $objShipmenets->shipping_method_id = $this->shippingMethodId;
                    $objShipmenets->courier_id = $objShipping->courier_id;
                    $objShipmenets->shipment_id = $objResponse['data']['1']['waybill'];
                    $objShipmenets->cod = $orderDetails['payment_mode'];
                    $objShipmenets->pickup_status = 1;
                    $objShipmenets->data = json_encode($objResponse);
                    $objShipmenets->save();
                }
                else
                {

                    Log::info("shippingOrderRequest",$finaldata);
                    Log::info("shippingOrder",$objResponse);
                    if($objResponse['html_message'] != "")
                    {
                        $this->displayOurCustomMessage($objResponse['html_message']);
                    }
                    else
                    {
                        $this->displayOurCustomMessage($objResponse['data']['1']['remark']); 
                    }

                }
            }
        } catch (Exception $e) {
            Log::info("shippingOrderCatch",$e->getMessage());   
            }
        return $objResponse;
    } 

    public function setReturnOrder($intShippingId)
    {
        return $this->createShipping($intShippingId, 'reverse');
    }

    public function getOrderDetails($arrAws, $startDate, $endDate)
    {
        $objResponse = [];
        try {
            $orderDetailsData = [
                'awb_number_list' => implode(",", $arrAws),
                'start_date' => Carbon::createFromFormat('Y-m-d', $startDate)->format('Y-m-d'),
                'end_date' => Carbon::createFromFormat('Y-m-d', $endDate)->format('Y-m-d'),
                'access_token' => $this->accessToken,
                'secret_key' => $this->secretKey,
            ];

            $finaldata = ['data' => $orderDetailsData ];
            $orderDetailsUrl = "pre-alpha";
            if(!$this->testMode)
            {
                $orderDetailsUrl = "api";
            }
            $finalOrderDetailsUrl = "https://".$orderDetailsUrl.".ithinklogistics.com/api_v3/get_details.json";
            $response = $this->callCurl($finalOrderDetailsUrl, $finaldata);
            $objResponse = json_decode($response, true);
            if($objResponse['status_code'] == 200)
            {   foreach($objResponse['data'] as $key => $aws)
                {
                    Shipments::where('shipment_id',$key)->update(['awb_data' => $aws[$key]]);
                }
            }
        } catch (Exception $e) {
            
        }
        return $objResponse;
    }

    public function getOrderTracking($arrAws)
    {
        $objResponse = [];

        try {
            $objShipment = Shipments::whereIn('shipment_id',$arrAws)->latest()->first();
            if(!$objShipment->is_delivered)
            {
                $orderTrackingData = [
                    'awb_number_list' => implode(",", $arrAws),
                    'access_token' => $this->accessToken,
                    'secret_key' => $this->secretKey,
                ];

                $finaldata = ['data' => $orderTrackingData ];
                $orderTrackingUrl = "pre-alpha";
                $dataKey = '901234567109';
                if(!$this->testMode)
                {
                    $orderTrackingUrl = "api";
                    $dataKey = $objShipment->shipment_id;
                }
                $finalOrderTrackingUrl = "https://".$orderTrackingUrl.".ithinklogistics.com/api_v3/order/track.json";
                $response = $this->callCurl($finalOrderTrackingUrl, $finaldata);
                $objResponse = json_decode($response, true);
                if($objResponse['status_code'] == 200)
                {   foreach($objResponse['data'] as $key => $awsdata)
                    {
                        $isDeliverd = 0;
                        if($awsdata['current_status_code'] == 'DL')
                        {
                            $isDeliverd = 1;
                        }
                        $shipment_status_id =  $objShipment->shipment_staus_id;
                        if(in_array($awsdata['current_status'],["Picked Up","In Transit"]))
                        {
                            $shipment_status_id = "Shipped";
                        }
                        elseif($awsdata['current_status'] == "Out For Delivery")
                        {
                            $shipment_status_id = "Out For Delivery";
                        }
                        elseif($awsdata['current_status'] == "Cancelled")
                        {
                            $shipment_status_id = "Cancelled";
                        }
                        elseif($awsdata['current_status'] == "Delivered")
                        {
                            $shipment_status_id = "Delivered";
                        }

                        Shipments::whereIn('shipment_id',$arrAws)->update(['shipment_staus_id'=> $shipment_status_id, 'awb_code' => $key, 'track_data' => json_encode($objResponse), 'is_delivered' => $isDeliverd ]);
                    }
                }
                else
                {
                    Log::info("shippingOrderTrackingRequest",$finaldata);
                    Log::info("shippingOrderTracking",$objResponse);
                }
            }
            else
            {
                  $objResponse = json_decode($objShipment->track_data, true);
            }
            
        } catch (Exception $e) {
            
        }
        return $objResponse;
    }

    public function generateLabel($arrAws)
    {   

        $objResponse = [];
        try {
            $shipmentLabel = [
                'awb_numbers' => implode(",", $arrAws),
                'page_size' => "A4",
                'display_cod_prepaid' => "1",
                'display_shipper_mobile' => "1",
                'display_shipper_address' => "1",
                'access_token' => $this->accessToken,
                'secret_key' => $this->secretKey,
            ];


            $finaldata = ['data' => $shipmentLabel ];
            $labelUrl = "pre-alpha";
            if(!$this->testMode)
            {
                $labelUrl = "my";
            }
            $finalLabelUrl = "https://".$labelUrl.".ithinklogistics.com/api_v3/shipping/label.json";
            $response = $this->callCurl($finalLabelUrl, $finaldata);
            $objResponse = json_decode($response, true);
            if($objResponse['status_code'] == 200)
            {   
                Shipments::whereIn('shipment_id',$arrAws)->update(['label_url' => $objResponse['file_name']]);
            }
            else
            {
                Log::info("shippingOrderLabelRequest",$finaldata);
                Log::info("shippingOrderLabel",$objResponse);
            }
        } catch (Exception $e) {
            
        }
        return $objResponse;
    }

    public function printInvoiceManifest($arrAws)
    {   
        $objResponse = [];

        try { 
             $printManifest = [
                'awb_numbers' => implode(",", $arrAws),
                'access_token' => $this->accessToken,
                'secret_key' => $this->secretKey,
            ];
            $finaldata = ['data' => $printManifest ];
            $printManifestUrl = "pre-alpha";
            if(!$this->testMode)
            {
                $printManifestUrl = "my";
            }
            $finalPrintManifestUrl = "https://".$printManifestUrl.".ithinklogistics.com/api_v3/shipping/manifest.json";
            $response = $this->callCurl($finalPrintManifestUrl, $finaldata);
            $objResponse = json_decode($response, true);
            if($objResponse['status_code'] == 200)
            {   
                Shipments::whereIn('shipment_id',$arrAws)->update(['invoice_url' => $objResponse['file_name']]);
            }
            else
            {
                 Log::info("shippingprintInvoiceManifestRequest",$finaldata);
                 Log::info("shippingprintInvoiceManifest",$objResponse);
            }
        } catch (Exception $e) {
            
        }


        return $objResponse;
    }

    public function cancelShipping($arrAws)
    {
        $objResponse = [];
        try {
             $cancelShipping = [
                'awb_numbers' => implode(",", $arrAws),
                'access_token' => $this->accessToken,
                'secret_key' => $this->secretKey,
            ];

            $finaldata = ['data' => $cancelShipping ];
            $printManifestUrl = "pre-alpha";
            if(!$this->testMode)
            {
                $printManifestUrl = "my";
            }
            $finalCancelShippingUrl = "https://".$printManifestUrl.".ithinklogistics.com/api_v3/order/cancel.json";
            $response = $this->callCurl($finalCancelShippingUrl, $finaldata);
            $objResponse = json_decode($response, true);
            if($objResponse['status'] == 'success')
            {   
                Shipments::whereIn('shipment_id',$arrAws)->update(['cancel_data' => json_encode($objResponse)]);
            }
            else
            {
                Log::info("shippingCancelRequest".$finaldata);
                Log::info("shippingCancel",$objResponse);
                if(!$this->testMode)
                {
                    $this->displayOurCustomMessage($objResponse['html_message']);            
                }
            }
        } catch (Exception $e) {
            
        }
        return $objResponse;
    }

    public function checkPincode($params)
    {
        $checkPincode = [
            'access_token' => $this->accessToken,
            'secret_key' => $this->secretKey,
            'pincode' => $params['postal_code'],
        ];
        $finaldata = ['data' => $checkPincode];
        $checkPincodeUrl = "pre-alpha";
        if(!$this->testMode)
        {
            $checkPincodeUrl = "my";
        }
        $finalCheckPincodeUrl = "https://".$checkPincodeUrl.".ithinklogistics.com/api_v3/pincode/check.json";
        $response = $this->callCurl($finalCheckPincodeUrl, $finaldata);
        $objResponse = json_decode($response, true);
        if($objResponse['status_code'] == 200)
        {
            return $objResponse['data'][$params['postal_code']];
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
                'from_pincode' => $fromPincode,
                'to_pincode' => $toPincode,
                'shipping_length_cms' => $length,
                'shipping_width_cms' => $width,
                'shipping_height_cms' => $height,
                'shipping_weight_kg' => $weight,
                'order_type' => $params['order_type'],
                'payment_method' => $params['payment_mode'],
                'product_mrp' => $params['product_mrp'],
                'access_token' => $this->accessToken,
                'secret_key' => $this->secretKey,
            ];
            $finaldata = ['data' => $checkPincode];

            $checkPincodeUrl = "pre-alpha";
            if(!$this->testMode)
            {
                $checkPincodeUrl = "manage";
            }
            $finalOrderUrl = "https://".$checkPincodeUrl.".ithinklogistics.com/api_v3/rate/check.json";
            $response = $this->callCurl($finalOrderUrl, $finaldata);
            $objResponse = json_decode($response, true);
            if ($objResponse == null) {
                $this->displayOurCustomMessage("Please check shipping address first.");
            }
            elseif(isset($objResponse['status']) && $objResponse['status'] == "success")
            {   
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
                 Log::info("shippinggetRateRequest",$finaldata);
                 Log::info("shippinggetRate",$objResponse);
                $this->displayOurCustomMessage($objResponse['message']);            
            }
        
        } catch (Exception $e) {
            
        }
        return $objResponse;
    }

    public function getState($stateId, $countryId)
    {
        $state_id = 0;

        $objShippingDetails = ShippingDetail::where('name','Ithinklogistics')->first();
        if(!empty($objShippingDetails))
        {
            $countryData = [
                'access_token' => $this->accessToken,
                'secret_key' => $this->secretKey,
                'country_id' => $countryId,
            ];
            $finaldata = ['data' => $countryData];

            $getCountryUrl = "pre-alpha";
            if(!$this->testMode)
            {
                $getCountryUrl = "manage";
            }
            $finalGetCountryUrl = "https://".$getCountryUrl.".ithinklogistics.com/api_v3/state/get.json";
            $response = $this->callCurl($finalGetCountryUrl, $finaldata);
            $objResponse = json_decode($response, true);

            if($objResponse['status_code'] == 200)
            {
                $objShippingDetails = ShippingDetail::where('name','Ithinklogistics')->first();
                $objShippingDetails->state_data = $objResponse['data'];
                $objShippingDetails->save();
            }
            $objStateData = collect($objShippingDetails->state_data);
            $strStateName = State::whereId($stateId)->first()->name;
            $objState = $objStateData->where('state_name',$strStateName)->where('country_id',$countryId)->first();
            if(!empty($objState))
            {
                $state_id = $objState['id'];
            }
        }
        return $state_id;
    }

    public function getCity($city_name, $state_id)
    {
        $city_id = 0;
        $objShippingDetails = ShippingDetail::where('name','Ithinklogistics')->first();
        if(!empty($objShippingDetails))
        {
            $getCityData = [
                'access_token' => $this->accessToken,
                'secret_key' => $this->secretKey,
                'state_id' => $state_id,
            ];
            $finaldata = ['data' => $getCityData];

            $getCityUrl = "pre-alpha";
            if(!$this->testMode)
            {
                $getCityUrl = "manage";
            }
            $finalOrderUrl = "https://".$getCityUrl.".ithinklogistics.com/api_v3/city/get.json";
            $response = $this->callCurl($finalOrderUrl, $finaldata);
            $objResponse = json_decode($response, true);
            if($objResponse['status_code'] == 200)
            {
                $objShippingDetails = ShippingDetail::where('name','Ithinklogistics')->first();
                $objShippingDetails->city_data = $objResponse['data'];
                $objShippingDetails->save();
            }
            $objCityData = collect($objShippingDetails->city_data);
            $objCity = $objCityData->where('city_name',ucfirst($city_name))->where('state_id',$state_id)->first();
            if(!empty($objCity))
            {
                $city_id = $objCity['id'];
            }
        }
        return $city_id;
    }

   

    public function setPickupLocation($params)
    {
        $objResponse = [];
        $allowedCountry = explode(",", config('ithinkcountry'));
        if(in_array($params['country_id'], $allowedCountry))
        {
            $state_id = $this->getState($params['state_id'], $params['country_id']);
            $city_id = $this->getCity($params['city_name'], $state_id);
            if($state_id == 0 || $city_id == 0)
            {
                return;
            }

            $newLocation = [
                'company_name' => config('app.name'),
                'address1' => $params['address'],
                'address2' => $params['address_2'],
                'mobile' => $params['mobile'],
                'pincode' => $params['postal_code'],
                'city_id' => $city_id,
                'state_id' => $state_id,
                'country_id' => $params['country_id'],
                'access_token' => $this->accessToken,
                'secret_key' => $this->secretKey
            ];

            $finaldata = ['data' => $newLocation ];
            $pickupStoreUrl = "pre-alpha";
            if(!$this->testMode)
            {
                $pickupStoreUrl = "manage";
            }
            $finalPickupStoreUrl = "https://".$pickupStoreUrl.".ithinklogistics.com/api_v3/warehouse/add.json";
            $response = $this->callCurl($finalPickupStoreUrl, $finaldata);
            $objResponse = json_decode($response, true);
            if($objResponse['status'] == 'success')
            {
                $objWarehouse = new Warehouse;
                $objWarehouse->shipping_method_id = $this->shippingMethodId;
                $objWarehouse->pickup_id = ($this->testMode)?'1293': $objResponse['warehouse_id'];
                $objWarehouse->pickup_code = $params['location_name'];
                $objWarehouse->status = 1; 
                $objWarehouse->data = json_encode($objResponse);
                $objWarehouse->address_id = $params['id'];
                $objWarehouse->save();
            }
            else
            {
                Log::info("shippinggetRateRequest",$finaldata);
                Log::info("shippinggetRate",$objResponse);
                $this->displayOurCustomMessage($objResponse['message']);            
            }
        }
        return $objResponse;
    }

    public function getWarehouse($data)
    {
            $Location = [
                /*'warehouse_id' => $data['warehouse_id'],*/
                'access_token' => $this->accessToken,
                'secret_key' => $this->secretKey
            ];

            $finaldata = ['data' => $Location ];

        $getWarehouseUrl = "pre-alpha";
            if(!$this->testMode)
            {
                $getWarehouseUrl = "my";
            }
            $finalGetWarehouseUrl = "https://".$getWarehouseUrl.".ithinklogistics.com/api_v3/warehouse/get.json";
            $response = $this->callCurl($finalGetWarehouseUrl, $finaldata);
            $objResponse = json_decode($response, true);
            return $objResponse;
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
            $objOrderLocationBilling = [];

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
            $objOrderLocationShipping = [];
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
        $arrObjShippingProducts = $objShippingProducts = [];
        if($this->shippingtype == "forward")
        {
         $objShippingProducts = ShippingProduct::where('shipping_id', $intShippingId)->skip(0)->take(2)->get();
        }
        else
        {  
         $objShippingProducts = ReturnShippingProduct::where('return_shipping_id', $intShippingId)->skip(0)->take(2)->get();
        }
            if($objShippingProducts->isNotEmpty())
            {
                foreach ($objShippingProducts as $key => $objShippingProduct) {
                    // code...
                    $tempOrderData =  [
                            'product_name' => $objShippingProduct['title'],
                            'product_sku' => $objShippingProduct['sku'],
                            'product_quantity' => (int) $objShippingProduct['quantity'],
                            'product_price' => (float) $objShippingProduct['selling_price']
                        ];
                    $arrObjShippingProducts[] = $tempOrderData;
                }
            }
        return $arrObjShippingProducts;
    }

    public function callCurl($callUrl, $data)
    {
          $curl = curl_init();
          curl_setopt_array($curl, array(
              CURLOPT_URL             => $callUrl,
              CURLOPT_RETURNTRANSFER  => true,
              CURLOPT_ENCODING        => "",
              CURLOPT_MAXREDIRS       => 10,
              CURLOPT_TIMEOUT         => 30,
              CURLOPT_HTTP_VERSION    => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST   => "POST",
              CURLOPT_POSTFIELDS      => json_encode($data),
              CURLOPT_HTTPHEADER      =>  [
                                            'cache-control' => 'no-cache',
                                            'content-type' => 'application/json',
                                          ]
          ));

          $response = curl_exec($curl);
          $err      = curl_error($curl);
          curl_close($curl);
          if ($err) 
          {
              echo "cURL Error #:" . $err;
          }
          else
          {
              return $response;
          }
        
    }

}
