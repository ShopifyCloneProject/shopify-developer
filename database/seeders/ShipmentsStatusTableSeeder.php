<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ShipmentStatus;

class ShipmentsStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ShipmentStatus::truncate();

        $shipmentstatuses = [
            ['id' => 1, 'description' => 'AWB Assigned', 'status_code' => '1','shipping_method_id' => 1,'status' => 1],
            ['id' => 2, 'description' => 'Label Generated', 'status_code' => '2','shipping_method_id' => 1,'status' => 1],
            ['id' => 3, 'description' => 'Pickup Scheduled/Generated', 'status_code' => '3','shipping_method_id' => 1,'status' => 1],
            ['id' => 4, 'description' => 'Pickup Queued', 'status_code' => '4','shipping_method_id' => 1,'status' => 1],
            ['id' => 5, 'description' => 'Manifest Generated', 'status_code' => '5','shipping_method_id' => 1,'status' => 1],
            ['id' => 6, 'description' => 'Shipped', 'status_code' => '6','shipping_method_id' => 1,'status' => 1],
            ['id' => 7, 'description' => 'Delivered', 'status_code' => '7','shipping_method_id' => 1,'status' => 1],
            ['id' => 8, 'description' => 'Cancelled', 'status_code' => '8','shipping_method_id' => 1,'status' => 1],
            ['id' => 9, 'description' => 'RTO Initiated', 'status_code' => '9','shipping_method_id' => 1,'status' => 1],
            ['id' => 10, 'description' => 'RTO Delivered', 'status_code' => '10','shipping_method_id' => 1,'status' => 1],
            ['id' => 11, 'description' => 'Pending', 'status_code' => '11','shipping_method_id' => 1,'status' => 1],
            ['id' => 12, 'description' => 'Lost', 'status_code' => '12','shipping_method_id' => 1,'status' => 1],
            ['id' => 13, 'description' => 'Pickup Error', 'status_code' => '13','shipping_method_id' => 1,'status' => 1],
            ['id' => 14, 'description' => 'RTO Acknowledged', 'status_code' => '14','shipping_method_id' => 1,'status' => 1],
            ['id' => 15, 'description' => 'Pickup Rescheduled', 'status_code' => '15','shipping_method_id' => 1,'status' => 1],
            ['id' => 16, 'description' => 'Cancellation Requested', 'status_code' => '16','shipping_method_id' => 1,'status' => 1],
            ['id' => 17, 'description' => 'Out For Delivery', 'status_code' => '17','shipping_method_id' => 1,'status' => 1],
            ['id' => 18, 'description' => 'In Transit', 'status_code' => '18','shipping_method_id' => 1,'status' => 1],
            ['id' => 19, 'description' => 'Out For Pickup', 'status_code' => '19','shipping_method_id' => 1,'status' => 1],
            ['id' => 20, 'description' => 'Pickup Exception', 'status_code' => '20','shipping_method_id' => 1,'status' => 1],
            ['id' => 21, 'description' => 'Undelivered', 'status_code' => '21','shipping_method_id' => 1,'status' => 1],
            ['id' => 22, 'description' => 'Delayed', 'status_code' => '22','shipping_method_id' => 1,'status' => 1],
            ['id' => 23, 'description' => 'Partial_Delivered', 'status_code' => '23','shipping_method_id' => 1,'status' => 1],
            ['id' => 24, 'description' => 'Destroyed', 'status_code' => '24','shipping_method_id' => 1,'status' => 1],
            ['id' => 25, 'description' => 'Damaged', 'status_code' => '25','shipping_method_id' => 1,'status' => 1],
            ['id' => 26, 'description' => 'Fulfilled', 'status_code' => '26','shipping_method_id' => 1,'status' => 1],
            ['id' => 27, 'description' => 'Reached at Destination', 'status_code' => '38','shipping_method_id' => 1,'status' => 1],
            ['id' => 28, 'description' => 'Misrouted', 'status_code' => '39','shipping_method_id' => 1,'status' => 1],
            ['id' => 29, 'description' => 'RTO NDR', 'status_code' => '40','shipping_method_id' => 1,'status' => 1],
            ['id' => 30, 'description' => 'RTO OFD', 'status_code' => '41','shipping_method_id' => 1,'status' => 1],
            ['id' => 31, 'description' => 'Picked Up', 'status_code' => '42','shipping_method_id' => 1,'status' => 1],
            ['id' => 32, 'description' => 'Self Fulfilled', 'status_code' => '43','shipping_method_id' => 1,'status' => 1],
            ['id' => 33, 'description' => 'DISPOSED_OFF', 'status_code' => '44','shipping_method_id' => 1,'status' => 1],
            ['id' => 34, 'description' => 'CANCELLED_BEFORE_DISPATCHED', 'status_code' => '45','shipping_method_id' => 1,'status' => 1],
            ['id' => 35, 'description' => 'RTO_IN_TRANSIT', 'status_code' => '46','shipping_method_id' => 1,'status' => 1],
            ['id' => 36, 'description' => 'QC Failed', 'status_code' => '47','shipping_method_id' => 1,'status' => 1],
            ['id' => 37, 'description' => 'Reached Warehouse', 'status_code' => '48','shipping_method_id' => 1,'status' => 1],
            ['id' => 38, 'description' => 'Custom Cleared', 'status_code' => '49','shipping_method_id' => 1,'status' => 1],
            ['id' => 39, 'description' => 'In Flight', 'status_code' => '50','shipping_method_id' => 1,'status' => 1],
            ['id' => 40, 'description' => 'Handover to Courier', 'status_code' => '51','shipping_method_id' => 1,'status' => 1],
            ['id' => 41, 'description' => 'Shipment Booked', 'status_code' => '52','shipping_method_id' => 1,'status' => 1],
            ['id' => 42, 'description' => 'In Transit Overseas', 'status_code' => '54','shipping_method_id' => 1,'status' => 1],
            ['id' => 43, 'description' => 'Connection Aligned', 'status_code' => '55','shipping_method_id' => 1,'status' => 1],
            ['id' => 44, 'description' => 'Reached Overseas Warehouse', 'status_code' => '56','shipping_method_id' => 1,'status' => 1],
            ['id' => 45, 'description' => 'Custom Cleared Overseas', 'status_code' => '57','shipping_method_id' => 1,'status' => 1],
            ['id' => 46, 'description' => 'Box Packing', 'status_code' => '59','shipping_method_id' => 1,'status' => 1],
        ];

        ShipmentStatus::insert($shipmentstatuses);  
    }
}
