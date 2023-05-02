<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ShipOrder;

class ShipOrdersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ShipOrder::truncate();

        $shiporders = [
            ['id' => 1, 'filter' => 'cod','description' => 'cash on delivery orders','shipping_method_id' => 1, 'status' => 1],
            ['id' => 2, 'filter' => 'Prepaid','description' => 'for prepaid orders','shipping_method_id' => 1, 'status' => 1],
            ['id' => 3, 'filter' => '123','description' => 'Your order id','shipping_method_id' => 1, 'status' => 1],
            ['id' => 4, 'filter' => '1','description' => 'New Order','shipping_method_id' => 1, 'status' => 1],
            ['id' => 5, 'filter' => '2','description' => 'Invoiced','shipping_method_id' => 1, 'status' => 1],
            ['id' => 6, 'filter' => '3','description' => 'Ready To Ship','shipping_method_id' => 1, 'status' => 1],
            ['id' => 7, 'filter' => '4','description' => 'Pickup Scheduled','shipping_method_id' => 1, 'status' => 1],
            ['id' => 8, 'filter' => '5','description' => 'Canceled','shipping_method_id' => 1, 'status' => 1],
            ['id' => 9, 'filter' => '6','description' => 'Shipped','shipping_method_id' => 1, 'status' => 1],
            ['id' => 10, 'filter' => '7','description' => 'Delivered','shipping_method_id' => 1, 'status' => 1],
            ['id' => 11, 'filter' => '8','description' => 'ePayment Failed','shipping_method_id' => 1, 'status' => 1],
            ['id' => 12, 'filter' => '9','description' => 'Returned','shipping_method_id' => 1, 'status' => 1],
            ['id' => 13, 'filter' => '10','description' => 'Unmapped','shipping_method_id' => 1, 'status' => 1],
            ['id' => 14, 'filter' => '11','description' => 'Unfulfillable','shipping_method_id' => 1, 'status' => 1],
            ['id' => 15, 'filter' => '12','description' => 'Pickup Queue','shipping_method_id' => 1, 'status' => 1],
            ['id' => 16, 'filter' => '13','description' => 'Pickup Rescheduled','shipping_method_id' => 1, 'status' => 1],
            ['id' => 17, 'filter' => '14','description' => 'Pickup Error// Created when there is an error on pickup schedule','shipping_method_id' => 1, 'status' => 1],
            ['id' => 18, 'filter' => '15','description' => 'RTO Initiated','shipping_method_id' => 1, 'status' => 1],
            ['id' => 19, 'filter' => '16','description' => 'RTO Delivered','shipping_method_id' => 1, 'status' => 1],
            ['id' => 20, 'filter' => '17','description' => 'RTO Acknowledged','shipping_method_id' => 1, 'status' => 1],
            ['id' => 21, 'filter' => '18','description' => 'Cancellation Requested','shipping_method_id' => 1, 'status' => 1],
            ['id' => 22, 'filter' => '19','description' => 'Out for Delivery','shipping_method_id' => 1, 'status' => 1],
            ['id' => 23, 'filter' => '20','description' => 'In Transit','shipping_method_id' => 1, 'status' => 1],
            ['id' => 24, 'filter' => '21','description' => 'Return Pending','shipping_method_id' => 1, 'status' => 1],
            ['id' => 25, 'filter' => '22','description' => 'Return Initiated','shipping_method_id' => 1, 'status' => 1],
            ['id' => 26, 'filter' => '23','description' => 'Return Pickup Queued','shipping_method_id' => 1, 'status' => 1],
            ['id' => 27, 'filter' => '24','description' => 'Return Pickup Error','shipping_method_id' => 1, 'status' => 1],
            ['id' => 28, 'filter' => '25','description' => 'Return In Transit','shipping_method_id' => 1, 'status' => 1],
            ['id' => 29, 'filter' => '26','description' => 'Return Delivered','shipping_method_id' => 1, 'status' => 1],
            ['id' => 30, 'filter' => '27','description' => 'Return Cancelled','shipping_method_id' => 1, 'status' => 1],
            ['id' => 31, 'filter' => '28','description' => 'Return Pickup Generated','shipping_method_id' => 1, 'status' => 1],
            ['id' => 32, 'filter' => '29','description' => 'Return Cancellation Requested','shipping_method_id' => 1, 'status' => 1],
            ['id' => 33, 'filter' => '30','description' => 'Return Pickup Cancelled','shipping_method_id' => 1, 'status' => 1],
            ['id' => 34, 'filter' => '31','description' => 'Return Pickup Rescheduled','shipping_method_id' => 1, 'status' => 1],
            ['id' => 35, 'filter' => '32','description' => 'Return Pickedup','shipping_method_id' => 1, 'status' => 1],
            ['id' => 36, 'filter' => '33','description' => 'Lost','shipping_method_id' => 1, 'status' => 1],
            ['id' => 37, 'filter' => '34','description' => 'Out For Pickup','shipping_method_id' => 1, 'status' => 1],
            ['id' => 38, 'filter' => '35','description' => 'Pickup Exception','shipping_method_id' => 1, 'status' => 1],
            ['id' => 39, 'filter' => '36','description' => 'Undelivered','shipping_method_id' => 1, 'status' => 1],
            ['id' => 40, 'filter' => '37','description' => 'Delayed','shipping_method_id' => 1, 'status' => 1],
            ['id' => 41, 'filter' => '38','description' => 'Partial Delivered','shipping_method_id' => 1, 'status' => 1],
            ['id' => 42, 'filter' => '39','description' => 'Destroyed','shipping_method_id' => 1, 'status' => 1],
            ['id' => 43, 'filter' => '40','description' => 'Damaged','shipping_method_id' => 1, 'status' => 1],
            ['id' => 44, 'filter' => '41','description' => 'Fulfilled','shipping_method_id' => 1, 'status' => 1],
            ['id' => 45, 'filter' => '42','description' => 'Archived','shipping_method_id' => 1, 'status' => 1],
            ['id' => 46, 'filter' => '43','description' => 'Reached Destination Hub','shipping_method_id' => 1, 'status' => 1],
            ['id' => 47, 'filter' => '44','description' => 'Misrouted','shipping_method_id' => 1, 'status' => 1],
            ['id' => 48, 'filter' => '45','description' => 'RTO OFD','shipping_method_id' => 1, 'status' => 1],
            ['id' => 49, 'filter' => '46','description' => 'RTO NDR','shipping_method_id' => 1, 'status' => 1],
            ['id' => 50, 'filter' => '47','description' => 'Return Out For Pickup','shipping_method_id' => 1, 'status' => 1],
            ['id' => 51, 'filter' => '48','description' => 'Return Out For Delivery','shipping_method_id' => 1, 'status' => 1],
            ['id' => 52, 'filter' => '49','description' => 'Return Pickup Exception','shipping_method_id' => 1, 'status' => 1],
            ['id' => 53, 'filter' => '50','description' => 'Return Undelivered','shipping_method_id' => 1, 'status' => 1],
            ['id' => 54, 'filter' => '51','description' => 'Picked Up','shipping_method_id' => 1, 'status' => 1],
            ['id' => 55, 'filter' => '52','description' => 'Self Fulfilled','shipping_method_id' => 1, 'status' => 1],
            ['id' => 56, 'filter' => '53','description' => 'Disposed Of','shipping_method_id' => 1, 'status' => 1],
            ['id' => 57, 'filter' => '54','description' => 'Canceled before Dispatched','shipping_method_id' => 1, 'status' => 1],
            ['id' => 58, 'filter' => '55','description' => 'RTO In-Transit','shipping_method_id' => 1, 'status' => 1],
            ['id' => 59, 'filter' => '57','description' => 'QC Failed','shipping_method_id' => 1, 'status' => 1],
            ['id' => 60, 'filter' => '58','description' => 'Reached Warehouse','shipping_method_id' => 1, 'status' => 1],
            ['id' => 61, 'filter' => '59','description' => 'Custom Cleared','shipping_method_id' => 1, 'status' => 1],
            ['id' => 62, 'filter' => '60','description' => 'In Flight','shipping_method_id' => 1, 'status' => 1],
            ['id' => 63, 'filter' => '61','description' => 'Handover to Courier','shipping_method_id' => 1, 'status' => 1],
            ['id' => 64, 'filter' => '62','description' => 'Order booked','shipping_method_id' => 1, 'status' => 1],
            ['id' => 65, 'filter' => '64','description' => 'In Transit Overseas','shipping_method_id' => 1, 'status' => 1],
            ['id' => 66, 'filter' => '65','description' => 'Connection Aligned','shipping_method_id' => 1, 'status' => 1],
            ['id' => 67, 'filter' => '66','description' => 'Reached Overseas Warehouse','shipping_method_id' => 1, 'status' => 1],
            ['id' => 68, 'filter' => '67','description' => 'Custom Cleared Overseas','shipping_method_id' => 1, 'status' => 1],
            ['id' => 69, 'filter' => '69','description' => 'Box Packing','shipping_method_id' => 1, 'status' => 1],
        ];

        ShipOrder::insert($shiporders);
    }
}
