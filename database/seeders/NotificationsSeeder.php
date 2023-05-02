<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Notification;
use Str;

class NotificationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Notification::truncate();
        $email_template ="Hello World";

            $notifications = [
                ['id' => 1 , 'title' => 'Order confirmation' , 'description' => 'Sent automatically to the customer after they place their order.' , 'category' => '0', 'options' => '0', 'status' => 1, 'email' => 1, 'email_subject' => 'Order confirmation', 'email_template' => $email_template, 'sms' => 1, 'sms_template' => 'Order confirmation', 'variable_description' => 'Order confirmation'],
                ['id' => 2 , 'title' => 'Order edited' , 'description' => 'Sent to the customer after their order is edited (if you select this option).' , 'category' => '0', 'options' => '0', 'status' => 1, 'email' => 1, 'email_subject' => 'Order edited', 'email_template' => $email_template, 'sms' => 1, 'sms_template' => 'Order edited', 'variable_description' => 'Order edited'],
                ['id' => 3 , 'title' => 'Order invoice' , 'description' => 'Sent to the customer when the order has an outstanding balance.' , 'category' => '0', 'options' => '0', 'status' => 1, 'email' => 1, 'email_subject' => 'Order invoice', 'email_template' => $email_template, 'sms' => 1, 'sms_template' => 'Order invoice', 'variable_description' => 'Order invoice'],
                ['id' => 4 , 'title' => 'Order cancelled' , 'description' => 'Sent automatically to the customer if their order is cancelled (if you select this option).' , 'category' => '0', 'options' => '0', 'status' => 1, 'email' => 1, 'email_subject' => 'Order cancelled', 'email_template' => $email_template, 'sms' => 1, 'sms_template' => 'Order cancelled', 'variable_description' => 'Order cancelled'],
                ['id' => 5 , 'title' => 'Order refund' , 'description' => 'Sent automatically to the customer if their order is refunded (if you select this option).' , 'category' => '0', 'options' => '0', 'status' => 1, 'email' => 1, 'email_subject' => 'Order refund', 'email_template' => $email_template, 'sms' => 1, 'sms_template' => 'Order refund', 'variable_description' => 'Order refund'],
                ['id' => 6 , 'title' => 'Draft order invoice' , 'description' => 'Sent to the customer when a draft order invoice is created. You can edit this email invoice before you send it.' , 'category' => '0', 'options' => '0', 'status' => 1, 'email' => 1, 'email_subject' => 'Draft order invoice', 'email_template' => $email_template, 'sms' => 1, 'sms_template' => 'Draft order invoice', 'variable_description' => 'Draft order invoice'],
                ['id' => 7 , 'title' => 'Abandoned POS checkout' , 'description' => 'Sent to the customer when you email their cart from POS. Includes a link to buy online.' , 'category' => '0', 'options' => '0', 'status' => 1, 'email' => 1, 'email_subject' => 'Abandoned POS checkout', 'email_template' => $email_template, 'sms' => 1, 'sms_template' => 'Abandoned POS checkout', 'variable_description' => 'Abandoned POS checkout'],
                ['id' => 8 , 'title' => 'Abandoned checkout' , 'description' => 'Sent to the customer if they leave checkout before they buy the items in their cart. Configure options in "checkout settings".' , 'category' => '0', 'options' => '0', 'status' => 1, 'email' => 1, 'email_subject' => 'Abandoned checkout', 'email_template' => $email_template, 'sms' => 1, 'sms_template' => 'Abandoned checkout', 'variable_description' => 'Abandoned checkout'],
                ['id' => 9 , 'title' => 'POS and mobile receipt' , 'description' => 'Sent to the customer after they complete an in person order and want to be emailed a receipt.' , 'category' => '0', 'options' => '0', 'status' => 1, 'email' => 1, 'email_subject' => 'POS and mobile receipt', 'email_template' => $email_template, 'sms' => 1, 'sms_template' => 'POS and mobile receipt', 'variable_description' => 'POS and mobile receipt'],
                ['id' => 10 , 'title' => 'POS exchange receipt' , 'description' => 'Sent to the customer after they complete an exchange in person and want to be emailed a receipt.' , 'category' => '0', 'options' => '0', 'status' => 1, 'email' => 1, 'email_subject' => 'POS exchange receipt', 'email_template' => $email_template, 'sms' => 1, 'sms_template' => 'POS exchange receipt', 'variable_description' => 'POS exchange receipt'],
                ['id' => 11 , 'title' => 'POS exchange V2 receipt' , 'description' => 'Sent to the customer after they complete an exchange V2 in person and want to be emailed a receipt.' , 'category' => '0', 'options' => '0', 'status' => 1, 'email' => 1, 'email_subject' => 'POS exchange V2 receipt', 'email_template' => $email_template, 'sms' => 1, 'sms_template' => 'POS exchange V2 receipt', 'variable_description' => 'POS exchange V2 receipt'],
                ['id' => 12 , 'title' => 'Gift card created' , 'description' => 'Sent automatically to the customer when you issue or fulfill a gift card.' , 'category' => '0', 'options' => '0', 'status' => 1, 'email' => 1, 'email_subject' => 'Gift card created', 'email_template' => $email_template, 'sms' => 1, 'sms_template' => 'Gift card created', 'variable_description' => 'Gift card created'],
                ['id' => 13 , 'title' => 'Payment error' , 'description' => 'Sent automatically to the customer if their payment can’t be processed during checkout.' , 'category' => '0', 'options' => '0', 'status' => 1, 'email' => 1, 'email_subject' => 'Payment error', 'email_template' => $email_template, 'sms' => 1, 'sms_template' => 'Payment error', 'variable_description' => 'Payment error'],
                ['id' => 14 , 'title' => 'Pending payment error' , 'description' => "Sent automatically to the customer if their pending payment can't be processed after they have checked out. Learn more about pending payments." , 'category' => '0', 'options' => '0', 'status' => 1, 'email' => 1, 'email_subject' => 'Pending payment error', 'email_template' => $email_template, 'sms' => 1, 'sms_template' => 'Pending payment error', 'variable_description' => 'Pending payment error'],
                ['id' => 15 , 'title' => 'Pending payment success' , 'description' => "Sent automatically to the customer when their pending payment is successfully processed after they have checked out. Learn more about pending payments." , 'category' => '0', 'options' => '0', 'status' => 1, 'email' => 1, 'email_subject' => 'Pending payment success', 'email_template' => $email_template, 'sms' => 1, 'sms_template' => 'Pending payment success', 'variable_description' => 'Pending payment success'],
                ['id' => 16 , 'title' => 'Fulfillment request' , 'description' => "Sent automatically to a third-party fulfillment service provider when order items are fulfilled." , 'category' => '1', 'options' => '0', 'status' => 1, 'email' => 1, 'email_subject' => 'Fulfillment request', 'email_template' => $email_template, 'sms' => 1, 'sms_template' => 'Fulfillment request', 'variable_description' => 'Fulfillment request'],
                ['id' => 17 , 'title' => 'Shipping confirmation' , 'description' => "Sent automatically to the customer when their order is fulfilled (if you select this option)." , 'category' => '1', 'options' => '0', 'status' => 1, 'email' => 1, 'email_subject' => 'Shipping confirmation', 'email_template' => $email_template, 'sms' => 1, 'sms_template' => 'Shipping confirmation', 'variable_description' => 'Shipping confirmation'],
                ['id' => 18 , 'title' => 'Shipping update' , 'description' => "Sent automatically to the customer if their fulfilled order’s tracking number is updated (if you select this option)." , 'category' => '1', 'options' => '0', 'status' => 1, 'email' => 1, 'email_subject' => 'Shipping update', 'email_template' => $email_template, 'sms' => 1, 'sms_template' => 'Shipping update', 'variable_description' => 'Shipping update'],
                ['id' => 19 , 'title' => 'Out for delivery' , 'description' => "Sent to the customer automatically after orders with tracking information are out for delivery." , 'category' => '1', 'options' => '2', 'status' => 1, 'email' => 1, 'email_subject' => 'Out for delivery', 'email_template' => $email_template, 'sms' => 1, 'sms_template' => 'Out for delivery', 'variable_description' => 'Out for delivery'],
                ['id' => 20 , 'title' => 'Delivered' , 'description' => "Sent to the customer automatically after orders with tracking information are delivered." , 'category' => '1', 'options' => '2', 'status' => 1, 'email' => 1, 'email_subject' => 'Delivered', 'email_template' => $email_template, 'sms' => 1, 'sms_template' => 'Delivered', 'variable_description' => 'Delivered'],
                ['id' => 21 , 'title' => 'Local order out for delivery' , 'description' => "Sent to the customer when their local order is out for delivery." , 'category' => '2', 'options' => '2', 'status' => 1, 'email' => 1, 'email_subject' => 'Local order out for delivery', 'email_template' => $email_template, 'sms' => 1, 'sms_template' => 'Local order out for delivery', 'variable_description' => 'Local order out for delivery'],
                ['id' => 22 , 'title' => 'Local order delivered' , 'description' => "Sent to the customer when their local order is delivered." , 'category' => '2', 'options' => '2', 'status' => 1, 'email' => 1, 'email_subject' => 'Local order delivered', 'email_template' => $email_template, 'sms' => 1, 'sms_template' => 'Local order delivered', 'variable_description' => 'Local order delivered'],
                ['id' => 23 , 'title' => 'Local order missed delivery' , 'description' => "Sent to the customer when they miss a local delivery." , 'category' => '2', 'options' => '2', 'status' => 1, 'email' => 1, 'email_subject' => 'Local order missed delivery', 'email_template' => $email_template, 'sms' => 1, 'sms_template' => 'Local order missed delivery', 'variable_description' => 'Local order missed delivery'],
                ['id' => 24 , 'title' => 'Ready for pickup' , 'description' => "Sent to the customer manually through Point of Sale or admin. Lets the customer know their order is ready to be picked up." , 'category' => '3', 'options' => '0', 'status' => 1, 'email' => 1, 'email_subject' => 'Ready for pickup', 'email_template' => $email_template, 'sms' => 1, 'sms_template' => 'Ready for pickup', 'variable_description' => 'Ready for pickup'],
                ['id' => 25 , 'title' => 'Picked up' , 'description' => "Sent to the customer when the order is marked as picked up." , 'category' => '3', 'options' => '0', 'status' => 1, 'email' => 1, 'email_subject' => 'Picked up', 'email_template' => $email_template, 'sms' => 1, 'sms_template' => 'Picked up', 'variable_description' => 'Picked up'],
                ['id' => 26 , 'title' => 'Customer account invite' , 'description' => "Sent to the customer with account activation instructions. You can edit this email before you send it." , 'category' => '4', 'options' => '0', 'status' => 1, 'email' => 1, 'email_subject' => 'Customer account invite', 'email_template' => $email_template, 'sms' => 1, 'sms_template' => 'Customer account invite', 'variable_description' => 'Customer account invite'],
                ['id' => 27 , 'title' => 'Customer account welcome' , 'description' => "Sent automatically to the customer when they complete their account activation." , 'category' => '4', 'options' => '0', 'status' => 1, 'email' => 1, 'email_subject' => 'Customer account welcome', 'email_template' => $email_template, 'sms' => 1, 'sms_template' => 'Customer account welcome', 'variable_description' => 'Customer account welcome'],
                ['id' => 28 , 'title' => 'Customer account password reset' , 'description' => "Sent automatically to the customer when they ask to reset their accounts password." , 'category' => '4', 'options' => '0', 'status' => 1, 'email' => 1, 'email_subject' => 'Customer account password reset', 'email_template' => $email_template, 'sms' => 1, 'sms_template' => 'Customer account password reset', 'variable_description' => 'Customer account password reset'],
                ['id' => 29 , 'title' => 'Contact customer' , 'description' => "Sent to the customer when you contact them from the orders or customers page. You can edit this email before you send it." , 'category' => '4', 'options' => '0', 'status' => 1, 'email' => 1, 'email_subject' => 'Contact customer', 'email_template' => $email_template, 'sms' => 1, 'sms_template' => 'Contact customer', 'variable_description' => 'Contact customer'],
                ['id' => 30 , 'title' => 'Customer marketing confirmation' , 'description' => "Sent to the customer automatically when they sign up for email marketing (if email double opt-in is enabled)." , 'category' => '5', 'options' => '0', 'status' => 1, 'email' => 1, 'email_subject' => 'Customer marketing confirmation', 'email_template' => $email_template, 'sms' => 1, 'sms_template' => 'Customer marketing confirmation', 'variable_description' => 'Customer marketing confirmation'],
                ['id' => 31 , 'title' => 'Return instructions with label/tracking' , 'description' => "Sent to the customer when you first create a return that contains a return label or tracking information." , 'category' => '6', 'options' => '0', 'status' => 1, 'email' => 1, 'email_subject' => 'Return instructions with label/tracking', 'email_template' => $email_template, 'sms' => 1, 'sms_template' => 'Return instructions with label/tracking', 'variable_description' => 'Return instructions with label/tracking'],
                ['id' => 32 , 'title' => 'Return label only' , 'description' => "Sent to the customer when you add a return label after creating the initial return." , 'category' => '6', 'options' => '0', 'status' => 1, 'email' => 1, 'email_subject' => 'Return label only', 'email_template' => $email_template, 'sms' => 1, 'sms_template' => 'Return label only', 'variable_description' => 'Return label only'],
            ];
            Notification::insert($notifications);
            $objNotifications = Notification::get();
            foreach($objNotifications as $objNotification){
                $templatename = Str::slug($objNotification->title , "_");
                $dynamicTemplateName  = $templatename;
              
                $notificationsfolderPath = Notification::folderPath;
               $dir = resource_path($notificationsfolderPath);
                if ( !is_dir( $dir ) ) {
                    mkdir( $dir, 0777 );       
                }

                $folderPath = "$notificationsfolderPath/$templatename";
                $dir = resource_path($folderPath);
                if ( !is_dir( $dir ) ) {
                    mkdir( $dir, 0777 );       
                }
                $myfile = fopen(resource_path($folderPath."/".$dynamicTemplateName.".blade.php"), "w");
                $txtnew = "@extends('email.template') \n @section('email.main') \n";
                $txtnew .= $email_template;
                $txtnew .= "\n @endsection";
                fwrite($myfile, $txtnew);
                fclose($myfile);
            }

    }
}
