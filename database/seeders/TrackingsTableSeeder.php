<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tracking;

class TrackingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Tracking::truncate();
        $trackings = [
            ['id' => 1, 'description' => 'Shipped', 'status_code' => '6','shipping_method_id' => 1, 'status' => 1],
            ['id' => 2, 'description' => 'Delivered', 'status_code' => '7','shipping_method_id' => 1, 'status' => 1],
            ['id' => 3, 'description' => 'Cancelled', 'status_code' => '8','shipping_method_id' => 1, 'status' => 1],
            ['id' => 4, 'description' => 'RTO Initiated', 'status_code' => '9','shipping_method_id' => 1, 'status' => 1],
            ['id' => 5, 'description' => 'RTO Delivered', 'status_code' => '10','shipping_method_id' => 1, 'status' => 1],
            ['id' => 6, 'description' => 'Lost', 'status_code' => '12','shipping_method_id' => 1, 'status' => 1],
            ['id' => 7, 'description' => 'Pickup Error', 'status_code' => '13','shipping_method_id' => 1, 'status' => 1],
            ['id' => 8, 'description' => 'RTO Acknowledged', 'status_code' => '14','shipping_method_id' => 1, 'status' => 1],
            ['id' => 9, 'description' => 'Pickup Rescheduled', 'status_code' => '15','shipping_method_id' => 1, 'status' => 1],
            ['id' => 10, 'description' => 'Cancellation Requested', 'status_code' => '16','shipping_method_id' => 1, 'status' => 1],
            ['id' => 11, 'description' => 'Out For Delivery', 'status_code' => '17','shipping_method_id' => 1, 'status' => 1],
            ['id' => 12, 'description' => 'In Transit', 'status_code' => '18','shipping_method_id' => 1, 'status' => 1],
            ['id' => 13, 'description' => 'Out For Pickup', 'status_code' => '19','shipping_method_id' => 1, 'status' => 1],
            ['id' => 14, 'description' => 'Pickup Exception', 'status_code' => '20','shipping_method_id' => 1, 'status' => 1],
            ['id' => 15, 'description' => 'Undelivered', 'status_code' => '21','shipping_method_id' => 1, 'status' => 1],
            ['id' => 16, 'description' => 'Delayed', 'status_code' => '22','shipping_method_id' => 1, 'status' => 1],
            ['id' => 17, 'description' => 'Partial_Delivered', 'status_code' => '23','shipping_method_id' => 1, 'status' => 1],
            ['id' => 18, 'description' => 'Destroyed', 'status_code' => '24','shipping_method_id' => 1, 'status' => 1],
            ['id' => 19, 'description' => 'Damaged', 'status_code' => '25','shipping_method_id' => 1, 'status' => 1],
            ['id' => 20, 'description' => 'Fulfilled', 'status_code' => '26','shipping_method_id' => 1, 'status' => 1],
            ['id' => 21, 'description' => 'Reached at Destination', 'status_code' => '38','shipping_method_id' => 1, 'status' => 1],
            ['id' => 22, 'description' => 'Misrouted', 'status_code' => '39','shipping_method_id' => 1, 'status' => 1],
            ['id' => 23, 'description' => 'RTO NDR', 'status_code' => '40','shipping_method_id' => 1, 'status' => 1],
            ['id' => 24, 'description' => 'RTO OFD', 'status_code' => '41','shipping_method_id' => 1, 'status' => 1],
            ['id' => 25, 'description' => 'Picked Up', 'status_code' => '42','shipping_method_id' => 1, 'status' => 1],
            ['id' => 26, 'description' => 'Self Fulfilled', 'status_code' => '43','shipping_method_id' => 1, 'status' => 1],
            ['id' => 27, 'description' => 'DISPOSED_OFF', 'status_code' => '44','shipping_method_id' => 1, 'status' => 1],
            ['id' => 28, 'description' => 'CANCELLED_BEFORE_DISPATCHED', 'status_code' => '45','shipping_method_id' => 1, 'status' => 1],
            ['id' => 29, 'description' => 'RTO_IN_TRANSIT', 'status_code' => '46','shipping_method_id' => 1, 'status' => 1],
            ['id' => 30, 'description' => 'QC Failed', 'status_code' => '47','shipping_method_id' => 1, 'status' => 1],
            ['id' => 31, 'description' => 'Reached Warehouse', 'status_code' => '48','shipping_method_id' => 1, 'status' => 1],
            ['id' => 32, 'description' => 'Custom Cleared', 'status_code' => '49','shipping_method_id' => 1, 'status' => 1],
            ['id' => 33, 'description' => 'In Flight', 'status_code' => '50','shipping_method_id' => 1, 'status' => 1],
            ['id' => 34, 'description' => 'Handover to Courier', 'status_code' => '51','shipping_method_id' => 1, 'status' => 1],
            ['id' => 35, 'description' => 'Shipment Booked', 'status_code' => '52','shipping_method_id' => 1, 'status' => 1],
            ['id' => 36, 'description' => 'In Transit Overseas', 'status_code' => '54','shipping_method_id' => 1, 'status' => 1],
            ['id' => 37, 'description' => 'Connection Aligned', 'status_code' => '55','shipping_method_id' => 1, 'status' => 1],
            ['id' => 38, 'description' => 'Reached Overseas Warehouse', 'status_code' => '56','shipping_method_id' => 1, 'status' => 1],
            ['id' => 39, 'description' => 'Custom Cleared Overseas', 'status_code' => '57','shipping_method_id' => 1, 'status' => 1],
            ['id' => 40, 'description' => 'Box Packing', 'status_code' => '59','shipping_method_id' => 1, 'status' => 1],
            ['id' => 41, 'description' => 'Manifested', 'status_code' => 'UD','shipping_method_id' => 2, 'status' => 1],
            ['id' => 42, 'description' => 'Not Picked', 'status_code' => 'UD','shipping_method_id' => 2, 'status' => 1],
            ['id' => 43, 'description' => 'Picked Up', 'status_code' => 'UD','shipping_method_id' => 2, 'status' => 1],
            ['id' => 44, 'description' => 'In Transit', 'status_code' => 'UD','shipping_method_id' => 2, 'status' => 1],
            ['id' => 45, 'description' => 'Reached At Destination', 'status_code' => 'UD','shipping_method_id' => 2, 'status' => 1],
            ['id' => 46, 'description' => 'Out For Delivery', 'status_code' => 'UD','shipping_method_id' => 2, 'status' => 1],
            ['id' => 47, 'description' => 'Undelivered', 'status_code' => 'UD','shipping_method_id' => 2, 'status' => 1],
            ['id' => 48, 'description' => 'Out of Delivery Area', 'status_code' => 'UD','shipping_method_id' => 2, 'status' => 1],
            ['id' => 49, 'description' => 'Delayed', 'status_code' => 'UD','shipping_method_id' => 2, 'status' => 1],
            ['id' => 50, 'description' => 'Damaged', 'status_code' => 'UD','shipping_method_id' => 2, 'status' => 1],
            ['id' => 51, 'description' => 'Misrouted', 'status_code' => 'UD','shipping_method_id' => 2, 'status' => 1],
            ['id' => 52, 'description' => 'Delivered', 'status_code' => 'DL','shipping_method_id' => 2, 'status' => 1],
            ['id' => 53, 'description' => 'Cancelled', 'status_code' => 'CN','shipping_method_id' => 2, 'status' => 1],
            ['id' => 54, 'description' => 'RTO Pending', 'status_code' => 'RT','shipping_method_id' => 2, 'status' => 1],
            ['id' => 55, 'description' => 'RTO Processing', 'status_code' => 'RT','shipping_method_id' => 2, 'status' => 1],
            ['id' => 56, 'description' => 'RTO In Transit', 'status_code' => 'RT','shipping_method_id' => 2, 'status' => 1],
            ['id' => 57, 'description' => 'Reached At Origin', 'status_code' => 'RT','shipping_method_id' => 2, 'status' => 1],
            ['id' => 58, 'description' => 'RTO Out For Delivery', 'status_code' => 'RT','shipping_method_id' => 2, 'status' => 1],
            ['id' => 59, 'description' => 'RTO Undelivered', 'status_code' => 'RT','shipping_method_id' => 2, 'status' => 1],
            ['id' => 60, 'description' => 'RTO Delivered', 'status_code' => 'DL','shipping_method_id' => 2, 'status' => 1],
            ['id' => 61, 'description' => 'Lost', 'status_code' => 'Lost','shipping_method_id' => 2, 'status' => 1],
            ['id' => 62, 'description' => 'Shortage', 'status_code' => 'Shortage','shipping_method_id' => 2, 'status' => 1],
            ['id' => 63, 'description' => 'RTO Shortage', 'status_code' => 'RTO Shortage','shipping_method_id' => 2, 'status' => 1],
            ['id' => 64, 'description' => 'REV Manifest', 'status_code' => 'UD','shipping_method_id' => 2, 'status' => 1],
            ['id' => 65, 'description' => 'REV Out for Pick Up', 'status_code' => 'UD','shipping_method_id' => 2, 'status' => 1],
            ['id' => 66, 'description' => 'REV Picked Up', 'status_code' => 'UD','shipping_method_id' => 2, 'status' => 1],
            ['id' => 67, 'description' => 'REV In Transit', 'status_code' => 'UD','shipping_method_id' => 2, 'status' => 1],
            ['id' => 68, 'description' => 'REV Cancelled', 'status_code' => 'UD','shipping_method_id' => 2, 'status' => 1],
            ['id' => 69, 'description' => 'REV Out For Delivery', 'status_code' => 'UD','shipping_method_id' => 2, 'status' => 1],
            ['id' => 70, 'description' => 'REV Delivered', 'status_code' => 'DL','shipping_method_id' => 2, 'status' => 1],
            ['id' => 71, 'description' => 'REV Closed', 'status_code' => 'UD','shipping_method_id' => 2, 'status' => 1],
        ];

        Tracking::insert($trackings);
    }
}
