<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Courier;

class CouriersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Courier::truncate();
        $couriers = [
            ['id' => 1, 'name' => 'Blue Dart', 'courier_code' => '1','shipping_method_id' => 1, 'status' => 1],
            ['id' => 2, 'name' => 'FedEx', 'courier_code' => '2','shipping_method_id' => 1, 'status' => 1],
            ['id' => 3, 'name' => 'FEDEX PACKAGING#', 'courier_code' => '7','shipping_method_id' => 1, 'status' => 1],
            ['id' => 4, 'name' => 'DHL Packet International#', 'courier_code' => '8','shipping_method_id' => 1, 'status' => 1],
            ['id' => 5, 'name' => 'Delhivery', 'courier_code' => '10','shipping_method_id' => 1, 'status' => 1],
            ['id' => 6, 'name' => 'FedEx Surface 10 Kg', 'courier_code' => '12','shipping_method_id' => 1, 'status' => 1],
            ['id' => 7, 'name' => 'Ecom Express', 'courier_code' => '14','shipping_method_id' => 1, 'status' => 1],
            ['id' => 8, 'name' => 'Dotzot', 'courier_code' => '16','shipping_method_id' => 1, 'status' => 1],
            ['id' => 9, 'name' => 'Xpressbees', 'courier_code' => '33','shipping_method_id' => 1, 'status' => 1],
            ['id' => 10, 'name' => 'Aramex International#', 'courier_code' => '35','shipping_method_id' => 1, 'status' => 1],
            ['id' => 11, 'name' => 'DHL PACKET PLUS INTERNATIONAL#', 'courier_code' => '37','shipping_method_id' => 1, 'status' => 1],
            ['id' => 12, 'name' => 'DHL PARCEL INTERNATIONAL DIRECT#', 'courier_code' => '38','shipping_method_id' => 1, 'status' => 1],
            ['id' => 13, 'name' => 'Delhivery Surface 5 Kgs', 'courier_code' => '39','shipping_method_id' => 1, 'status' => 1],
            ['id' => 14, 'name' => 'Gati Surface 5 Kg', 'courier_code' => '40','shipping_method_id' => 1, 'status' => 1],
            ['id' => 15, 'name' => 'FedEx Flat Rate', 'courier_code' => '41','shipping_method_id' => 1, 'status' => 1],
            ['id' => 16, 'name' => 'FedEx Surface 5 Kg', 'courier_code' => '42','shipping_method_id' => 1, 'status' => 1],
            ['id' => 17, 'name' => 'Delhivery Surface', 'courier_code' => '43','shipping_method_id' => 1, 'status' => 1],
            ['id' => 18, 'name' => 'Delhivery Surface 2 Kgs', 'courier_code' => '44','shipping_method_id' => 1, 'status' => 1],
            ['id' => 19, 'name' => 'Ecom Express Reverse## ', 'courier_code' => '45','shipping_method_id' => 1, 'status' => 1],
            ['id' => 20, 'name' => 'Shadowfax Reverse##', 'courier_code' => '46','shipping_method_id' => 1, 'status' => 1],
            ['id' => 21, 'name' => 'Ekart Logistics', 'courier_code' => '48','shipping_method_id' => 1, 'status' => 1],
            ['id' => 22, 'name' => 'Wow Express', 'courier_code' => '50','shipping_method_id' => 1, 'status' => 1],
            ['id' => 23, 'name' => 'Xpressbees Surface', 'courier_code' => '51','shipping_method_id' => 1, 'status' => 1],
            ['id' => 24, 'name' => 'RAPID DELIVERY', 'courier_code' => '52','shipping_method_id' => 1, 'status' => 1],
            ['id' => 25, 'name' => 'Gati Surface 1 Kg', 'courier_code' => '53','shipping_method_id' => 1, 'status' => 1],
            ['id' => 26, 'name' => 'Ekart Logistics Surface', 'courier_code' => '54','shipping_method_id' => 1, 'status' => 1],
            ['id' => 27, 'name' => 'Blue Dart Surface', 'courier_code' => '55','shipping_method_id' => 1, 'status' => 1],
            ['id' => 28, 'name' => 'DHL Express International', 'courier_code' => '56','shipping_method_id' => 1, 'status' => 1],
            ['id' => 29, 'name' => 'Professional', 'courier_code' => '57','shipping_method_id' => 1, 'status' => 1],
            ['id' => 30, 'name' => 'Shadowfax Surface', 'courier_code' => '58','shipping_method_id' => 1, 'status' => 1],
            ['id' => 31, 'name' => 'Ecom Express ROS', 'courier_code' => '60','shipping_method_id' => 1, 'status' => 1],
            ['id' => 32, 'name' => 'FedEx Surface 1 Kg', 'courier_code' => '62','shipping_method_id' => 1, 'status' => 1],
            ['id' => 33, 'name' => 'Delhivery Flash', 'courier_code' => '63','shipping_method_id' => 1, 'status' => 1],
            ['id' => 34, 'name' => 'Delhivery Essential Surface', 'courier_code' => '68','shipping_method_id' => 1, 'status' => 1],
            ['id' => 35, 'name' => 'Delhivery Reverse QC', 'courier_code' => '80','shipping_method_id' => 1, 'status' => 1],
            ['id' => 36, 'name' => 'Shadowfax Local', 'courier_code' => '95','shipping_method_id' => 1, 'status' => 1],
            ['id' => 37, 'name' => 'Shadowfax Essential Surface', 'courier_code' => '96','shipping_method_id' => 1, 'status' => 1],
            ['id' => 38, 'name' => 'Dunzo Local', 'courier_code' => '97','shipping_method_id' => 1, 'status' => 1],
            ['id' => 39, 'name' => 'Ecom Express ROS Reverse', 'courier_code' => '99','shipping_method_id' => 1, 'status' => 1],
            ['id' => 40, 'name' => 'Delhivery Surface 10 Kgs', 'courier_code' => '100','shipping_method_id' => 1, 'status' => 1],
            ['id' => 41, 'name' => 'Delhivery Surface 20 Kgs', 'courier_code' => '101','shipping_method_id' => 1, 'status' => 1],
            ['id' => 42, 'name' => 'Delhivery Essential Surface 5Kg', 'courier_code' => '102','shipping_method_id' => 1, 'status' => 1],
            ['id' => 43, 'name' => 'Xpressbees Essential Surface', 'courier_code' => '103','shipping_method_id' => 1, 'status' => 1],
            ['id' => 44, 'name' => 'Delhivery Essential Surface 2Kg', 'courier_code' => '104','shipping_method_id' => 1, 'status' => 1],
            ['id' => 45, 'name' => 'Wefast Local', 'courier_code' => '106','shipping_method_id' => 1, 'status' => 1],
            ['id' => 46, 'name' => 'Wefast Local 5 Kg', 'courier_code' => '107','shipping_method_id' => 1, 'status' => 1],
            ['id' => 47, 'name' => 'Ecom Express Essential', 'courier_code' => '108','shipping_method_id' => 1, 'status' => 1],
            ['id' => 48, 'name' => 'Ecom Express ROS Essential', 'courier_code' => '109','shipping_method_id' => 1, 'status' => 1],
            ['id' => 49, 'name' => 'Delhivery Essential', 'courier_code' => '110','shipping_method_id' => 1, 'status' => 1],
            ['id' => 50, 'name' => 'Delhivery Non Essential', 'courier_code' => '111','shipping_method_id' => 1, 'status' => 1],
        ];

        Courier::insert($couriers);
    }
}
