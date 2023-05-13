<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    public function run()
    {
        Permission::truncate();
        $permissions = [
            [
                'id'    => 1,
                'title' => 'user_management_access',
            ],
            [
                'id'    => 2,
                'title' => 'permission_create',
            ],
            [
                'id'    => 3,
                'title' => 'permission_edit',
            ],
            [
                'id'    => 4,
                'title' => 'permission_show',
            ],
            [
                'id'    => 5,
                'title' => 'permission_delete',
            ],
            [
                'id'    => 6,
                'title' => 'permission_access',
            ],
            [
                'id'    => 7,
                'title' => 'role_create',
            ],
            [
                'id'    => 8,
                'title' => 'role_edit',
            ],
            [
                'id'    => 9,
                'title' => 'role_show',
            ],
            [
                'id'    => 10,
                'title' => 'role_delete',
            ],
            [
                'id'    => 11,
                'title' => 'role_access',
            ],
            [
                'id'    => 12,
                'title' => 'user_create',
            ],
            [
                'id'    => 13,
                'title' => 'user_edit',
            ],
            [
                'id'    => 14,
                'title' => 'user_show',
            ],
            [
                'id'    => 15,
                'title' => 'user_delete',
            ],
            [
                'id'    => 16,
                'title' => 'user_access',
            ],
            [
                'id'    => 17,
                'title' => 'country_create',
            ],
            [
                'id'    => 18,
                'title' => 'country_edit',
            ],
            [
                'id'    => 19,
                'title' => 'country_show',
            ],
            [
                'id'    => 20,
                'title' => 'country_delete',
            ],
            [
                'id'    => 21,
                'title' => 'country_access',
            ],
            [
                'id'    => 22,
                'title' => 'state_create',
            ],
            [
                'id'    => 23,
                'title' => 'state_edit',
            ],
            [
                'id'    => 24,
                'title' => 'state_show',
            ],
            [
                'id'    => 25,
                'title' => 'state_delete',
            ],
            [
                'id'    => 26,
                'title' => 'state_access',
            ],
            [
                'id'    => 27,
                'title' => 'address_create',
            ],
            [
                'id'    => 28,
                'title' => 'address_edit',
            ],
            [
                'id'    => 29,
                'title' => 'address_show',
            ],
            [
                'id'    => 30,
                'title' => 'address_delete',
            ],
            [
                'id'    => 31,
                'title' => 'address_access',
            ],
            [
                'id'    => 32,
                'title' => 'time_zone_create',
            ],
            [
                'id'    => 33,
                'title' => 'time_zone_edit',
            ],
            [
                'id'    => 34,
                'title' => 'time_zone_show',
            ],
            [
                'id'    => 35,
                'title' => 'time_zone_delete',
            ],
            [
                'id'    => 36,
                'title' => 'time_zone_access',
            ],
            [
                'id'    => 37,
                'title' => 'store_management_access',
            ],
            [
                'id'    => 38,
                'title' => 'user_store_industry_create',
            ],
            [
                'id'    => 39,
                'title' => 'user_store_industry_edit',
            ],
            [
                'id'    => 40,
                'title' => 'user_store_industry_show',
            ],
            [
                'id'    => 41,
                'title' => 'user_store_industry_delete',
            ],
            [
                'id'    => 42,
                'title' => 'user_store_industry_access',
            ],
            [
                'id'    => 43,
                'title' => 'user_store_create',
            ],
            [
                'id'    => 44,
                'title' => 'user_store_edit',
            ],
            [
                'id'    => 45,
                'title' => 'user_store_show',
            ],
            [
                'id'    => 46,
                'title' => 'user_store_delete',
            ],
            [
                'id'    => 47,
                'title' => 'user_store_access',
            ],
            [
                'id'    => 48,
                'title' => 'product_management_access',
            ],
            [
                'id'    => 49,
                'title' => 'variant_create',
            ],
            [
                'id'    => 50,
                'title' => 'variant_edit',
            ],
            [
                'id'    => 51,
                'title' => 'variant_show',
            ],
            [
                'id'    => 52,
                'title' => 'variant_delete',
            ],
            [
                'id'    => 53,
                'title' => 'variant_access',
            ],
            [
                'id'    => 54,
                'title' => 'variant_option_create',
            ],
            [
                'id'    => 55,
                'title' => 'variant_option_edit',
            ],
            [
                'id'    => 56,
                'title' => 'variant_option_show',
            ],
            [
                'id'    => 57,
                'title' => 'variant_option_delete',
            ],
            [
                'id'    => 58,
                'title' => 'variant_option_access',
            ],
            [
                'id'    => 59,
                'title' => 'product_create',
            ],
            [
                'id'    => 60,
                'title' => 'product_edit',
            ],
            [
                'id'    => 61,
                'title' => 'product_show',
            ],
            [
                'id'    => 62,
                'title' => 'product_delete',
            ],
            [
                'id'    => 63,
                'title' => 'product_access',
            ],
            [
                'id'    => 64,
                'title' => 'tag_create',
            ],
            [
                'id'    => 65,
                'title' => 'tag_edit',
            ],
            [
                'id'    => 66,
                'title' => 'tag_show',
            ],
            [
                'id'    => 67,
                'title' => 'tag_delete',
            ],
            [
                'id'    => 68,
                'title' => 'tag_access',
            ],
            [
                'id'    => 69,
                'title' => 'product_type_create',
            ],
            [
                'id'    => 70,
                'title' => 'product_type_edit',
            ],
            [
                'id'    => 71,
                'title' => 'product_type_show',
            ],
            [
                'id'    => 72,
                'title' => 'product_type_delete',
            ],
            [
                'id'    => 73,
                'title' => 'product_type_access',
            ],
            [
                'id'    => 74,
                'title' => 'collection_management_access',
            ],
            [
                'id'    => 75,
                'title' => 'collection_create',
            ],
            [
                'id'    => 76,
                'title' => 'collection_edit',
            ],
            [
                'id'    => 77,
                'title' => 'collection_show',
            ],
            [
                'id'    => 78,
                'title' => 'collection_delete',
            ],
            [
                'id'    => 79,
                'title' => 'collection_access',
            ],
            [
                'id'    => 80,
                'title' => 'condition_option_create',
            ],
            [
                'id'    => 81,
                'title' => 'condition_option_edit',
            ],
            [
                'id'    => 82,
                'title' => 'condition_option_show',
            ],
            [
                'id'    => 83,
                'title' => 'condition_option_delete',
            ],
            [
                'id'    => 84,
                'title' => 'condition_option_access',
            ],
            [
                'id'    => 85,
                'title' => 'collection_condition_create',
            ],
            [
                'id'    => 86,
                'title' => 'collection_condition_edit',
            ],
            [
                'id'    => 87,
                'title' => 'collection_condition_show',
            ],
            [
                'id'    => 88,
                'title' => 'collection_condition_delete',
            ],
            [
                'id'    => 89,
                'title' => 'collection_condition_access',
            ],
            [
                'id'    => 90,
                'title' => 'vendor_management_access',
            ],
            [
                'id'    => 91,
                'title' => 'vendor_create',
            ],
            [
                'id'    => 92,
                'title' => 'vendor_edit',
            ],
            [
                'id'    => 93,
                'title' => 'vendor_show',
            ],
            [
                'id'    => 94,
                'title' => 'vendor_delete',
            ],
            [
                'id'    => 95,
                'title' => 'vendor_access',
            ],
            [
                'id'    => 96,
                'title' => 'variant_management_access',
            ],
            [
                'id'    => 97,
                'title' => 'tags_manegment_access',
            ],
            [
                'id'    => 98,
                'title' => 'weightmanage_create',
            ],
            [
                'id'    => 99,
                'title' => 'weightmanage_edit',
            ],
            [
                'id'    => 100,
                'title' => 'weightmanage_show',
            ],
            [
                'id'    => 101,
                'title' => 'weightmanage_delete',
            ],
            [
                'id'    => 102,
                'title' => 'weightmanage_access',
            ],
            [
                'id'    => 103,
                'title' => 'product_variant_option_create',
            ],
            [
                'id'    => 104,
                'title' => 'product_variant_option_edit',
            ],
            [
                'id'    => 105,
                'title' => 'product_variant_option_show',
            ],
            [
                'id'    => 106,
                'title' => 'product_variant_option_delete',
            ],
            [
                'id'    => 107,
                'title' => 'product_variant_option_access',
            ],
            [
                'id'    => 108,
                'title' => 'variant_medium_create',
            ],
            [
                'id'    => 109,
                'title' => 'variant_medium_edit',
            ],
            [
                'id'    => 110,
                'title' => 'variant_medium_show',
            ],
            [
                'id'    => 111,
                'title' => 'variant_medium_delete',
            ],
            [
                'id'    => 112,
                'title' => 'variant_medium_access',
            ],
            [
                'id'    => 113,
                'title' => 'sales_channel_create',
            ],
            [
                'id'    => 114,
                'title' => 'sales_channel_edit',
            ],
            [
                'id'    => 115,
                'title' => 'sales_channel_show',
            ],
            [
                'id'    => 116,
                'title' => 'sales_channel_delete',
            ],
            [
                'id'    => 117,
                'title' => 'sales_channel_access',
            ],
            [
                'id'    => 118,
                'title' => 'condition_title_create',
            ],
            [
                'id'    => 119,
                'title' => 'condition_title_edit',
            ],
            [
                'id'    => 120,
                'title' => 'condition_title_show',
            ],
            [
                'id'    => 121,
                'title' => 'condition_title_delete',
            ],
            [
                'id'    => 122,
                'title' => 'condition_title_access',
            ],
            [
                'id'    => 123,
                'title' => 'inventory_management_access',
            ],
            [
                'id'    => 124,
                'title' => 'inventory_stock_create',
            ],
            [
                'id'    => 125,
                'title' => 'inventory_stock_edit',
            ],
            [
                'id'    => 126,
                'title' => 'inventory_stock_show',
            ],
            [
                'id'    => 127,
                'title' => 'inventory_stock_delete',
            ],
            [
                'id'    => 128,
                'title' => 'inventory_stock_access',
            ],
            [
                'id'    => 129,
                'title' => 'stock_create',
            ],
            [
                'id'    => 130,
                'title' => 'stock_edit',
            ],
            [
                'id'    => 131,
                'title' => 'stock_show',
            ],
            [
                'id'    => 132,
                'title' => 'stock_delete',
            ],
            [
                'id'    => 133,
                'title' => 'stock_access',
            ],
            [
                'id'    => 134,
                'title' => 'product_medium_create',
            ],
            [
                'id'    => 135,
                'title' => 'product_medium_edit',
            ],
            [
                'id'    => 136,
                'title' => 'product_medium_show',
            ],
            [
                'id'    => 137,
                'title' => 'product_medium_delete',
            ],
            [
                'id'    => 138,
                'title' => 'product_medium_access',
            ],
            [
                'id'    => 139,
                'title' => 'order_management_access',
            ],
            [
                'id'    => 140,
                'title' => 'order_financial_status_create',
            ],
            [
                'id'    => 141,
                'title' => 'order_financial_status_edit',
            ],
            [
                'id'    => 142,
                'title' => 'order_financial_status_show',
            ],
            [
                'id'    => 143,
                'title' => 'order_financial_status_delete',
            ],
            [
                'id'    => 144,
                'title' => 'order_financial_status_access',
            ],
            [
                'id'    => 145,
                'title' => 'order_create',
            ],
            [
                'id'    => 146,
                'title' => 'order_edit',
            ],
            [
                'id'    => 147,
                'title' => 'order_show',
            ],
            [
                'id'    => 148,
                'title' => 'order_delete',
            ],
            [
                'id'    => 149,
                'title' => 'order_access',
            ],
            [
                'id'    => 150,
                'title' => 'general_management_access',
            ],
            [
                'id'    => 151,
                'title' => 'currency_create',
            ],
            [
                'id'    => 152,
                'title' => 'currency_edit',
            ],
            [
                'id'    => 153,
                'title' => 'currency_show',
            ],
            [
                'id'    => 154,
                'title' => 'currency_delete',
            ],
            [
                'id'    => 155,
                'title' => 'currency_access',
            ],
            [
                'id'    => 156,
                'title' => 'shipping_method_create',
            ],
            [
                'id'    => 157,
                'title' => 'shipping_method_edit',
            ],
            [
                'id'    => 158,
                'title' => 'shipping_method_show',
            ],
            [
                'id'    => 159,
                'title' => 'shipping_method_delete',
            ],
            [
                'id'    => 160,
                'title' => 'shipping_method_access',
            ],
            [
                'id'    => 161,
                'title' => 'payment_method_create',
            ],
            [
                'id'    => 162,
                'title' => 'payment_method_edit',
            ],
            [
                'id'    => 163,
                'title' => 'payment_method_show',
            ],
            [
                'id'    => 164,
                'title' => 'payment_method_delete',
            ],
            [
                'id'    => 165,
                'title' => 'payment_method_access',
            ],
            [
                'id'    => 166,
                'title' => 'order_product_create',
            ],
            [
                'id'    => 167,
                'title' => 'order_product_edit',
            ],
            [
                'id'    => 168,
                'title' => 'order_product_show',
            ],
            [
                'id'    => 169,
                'title' => 'order_product_delete',
            ],
            [
                'id'    => 170,
                'title' => 'order_product_access',
            ],
            [
                'id'    => 171,
                'title' => 'order_product_variant_create',
            ],
            [
                'id'    => 172,
                'title' => 'order_product_variant_edit',
            ],
            [
                'id'    => 173,
                'title' => 'order_product_variant_show',
            ],
            [
                'id'    => 174,
                'title' => 'order_product_variant_delete',
            ],
            [
                'id'    => 175,
                'title' => 'order_product_variant_access',
            ],
            [
                'id'    => 176,
                'title' => 'product_variant_id_create',
            ],
            [
                'id'    => 177,
                'title' => 'product_variant_id_edit',
            ],
            [
                'id'    => 178,
                'title' => 'product_variant_id_show',
            ],
            [
                'id'    => 179,
                'title' => 'product_variant_id_delete',
            ],
            [
                'id'    => 180,
                'title' => 'product_variant_id_access',
            ],
            [
                'id'    => 181,
                'title' => 'gift_card_denomination_create',
            ],
            [
                'id'    => 182,
                'title' => 'gift_card_denomination_edit',
            ],
            [
                'id'    => 183,
                'title' => 'gift_card_denomination_show',
            ],
            [
                'id'    => 184,
                'title' => 'gift_card_denomination_delete',
            ],
            [
                'id'    => 185,
                'title' => 'gift_card_denomination_access',
            ],
            [
                'id'    => 186,
                'title' => 'gift_card_tag_create',
            ],
            [
                'id'    => 187,
                'title' => 'gift_card_tag_edit',
            ],
            [
                'id'    => 188,
                'title' => 'gift_card_tag_show',
            ],
            [
                'id'    => 189,
                'title' => 'gift_card_tag_delete',
            ],
            [
                'id'    => 190,
                'title' => 'gift_card_tag_access',
            ],
            [
                'id'    => 191,
                'title' => 'gift_card_vendor_create',
            ],
            [
                'id'    => 192,
                'title' => 'gift_card_vendor_edit',
            ],
            [
                'id'    => 193,
                'title' => 'gift_card_vendor_show',
            ],
            [
                'id'    => 194,
                'title' => 'gift_card_vendor_delete',
            ],
            [
                'id'    => 195,
                'title' => 'gift_card_vendor_access',
            ],
            [
                'id'    => 196,
                'title' => 'gift_card_issue_create',
            ],
            [
                'id'    => 197,
                'title' => 'gift_card_issue_edit',
            ],
            [
                'id'    => 198,
                'title' => 'gift_card_issue_show',
            ],
            [
                'id'    => 199,
                'title' => 'gift_card_issue_delete',
            ],
            [
                'id'    => 200,
                'title' => 'gift_card_issue_access',
            ],
            [
                'id'    => 201,
                'title' => 'gift_card_collection_create',
            ],
            [
                'id'    => 202,
                'title' => 'gift_card_collection_edit',
            ],
            [
                'id'    => 203,
                'title' => 'gift_card_collection_show',
            ],
            [
                'id'    => 204,
                'title' => 'gift_card_collection_delete',
            ],
            [
                'id'    => 205,
                'title' => 'gift_card_collection_access',
            ],
            [
                'id'    => 206,
                'title' => 'fbpixel_create',
            ],
            [
                'id'    => 207,
                'title' => 'fbpixel_edit',
            ],
            [
                'id'    => 208,
                'title' => 'fbpixel_show',
            ],
            [
                'id'    => 209,
                'title' => 'fbpixel_delete',
            ],
            [
                'id'    => 210,
                'title' => 'fbpixel_access',
            ],
            [
                'id'    => 211,
                'title' => 'checkout_create',
            ],
            [
                'id'    => 212,
                'title' => 'checkout_edit',
            ],
            [
                'id'    => 213,
                'title' => 'checkout_show',
            ],
            [
                'id'    => 214,
                'title' => 'checkout_delete',
            ],
            [
                'id'    => 215,
                'title' => 'checkout_access',
            ],
            [
                'id'    => 216,
                'title' => 'section_create',
            ],
            [
                'id'    => 217,
                'title' => 'section_edit',
            ],
            [
                'id'    => 218,
                'title' => 'section_show',
            ],
            [
                'id'    => 219,
                'title' => 'section_delete',
            ],
            [
                'id'    => 220,
                'title' => 'section_access',
            ],
            [
                'id'    => 221,
                'title' => 'product_import_access'
            ],
            [
                'id'    => 222,
                'title' => 'product_export_access'
            ],
            [
                'id'    => 223,
                'title' => 'collection_export',
            ],
            [
                'id'    => 224,
                'title' => 'order_export_access'
            ],
            [
                'id'    => 225,
                'title' => 'permission_export_access'
            ],
            [
                'id'    => 226,
                'title' => 'inventory_stock_export',
            ],
            [
                'id'    => 227,
                'title' => 'role_export_access'
            ],
            [
                'id'    => 228,
                'title' => 'vendor_export',
            ],
            [
                'id'    => 229,
                'title' => 'variant_option_export',
            ],
            [
                'id'    => 230,
                'title' => 'timezone_export_access'
            ],
            [
                'id'    => 231,
                'title' => 'tag_export'
            ],
            [
                'id'    => 232,
                'title' => 'country_export_access'
            ],
            [
                'id'    => 233,
                'title' => 'weightmanage_export_access'
            ],
            [
                'id'    => 234,
                'title' => 'state_export_access'
            ],
            [
                'id'    => 235,
                'title' => 'payment_method_export_access'
            ],
            [
                'id'    => 236,
                'title' => 'payment_type_create'
            ],
            [
                'id'    => 237,
                'title' => 'payment_type_edit'
            ],
            [
                'id'    => 238,
                'title' => 'payment_type_delete'
            ],
            [
                'id'    => 239,
                'title' => 'payment_type_access'
            ],
            [
                'id'    => 240,
                'title' => 'payment_type_export_access'
            ],
            [
                'id'    => 241,
                'title' => 'currency_export_access'
            ],
            [
                'id'    => 242,
                'title' => 'shipping_method_export_access'
            ],
            [
                'id'    => 243,
                'title' => 'settings_access'
            ],
            [
                'id'    => 244,
                'title' => 'theme_create'
            ],
            [
                'id'    => 245,
                'title' => 'theme_edit'
            ],
            [
                'id'    => 246,
                'title' => 'theme_show'
            ],
            [
                'id'    => 247,
                'title' => 'theme_delete'
            ],
            [
                'id'    => 248,
                'title' => 'theme_access'
            ],
            [
                'id'    => 249,
                'title' => 'theme_export_access'
            ],
            [
                'id'    => 250,
                'title' => 'plan_access'
            ],
            [
                'id'    => 251,
                'title' => 'custom_settings_access'
            ],
            [
                'id'    => 252,
                'title' => 'payment_settings_access'
            ],
            [
                'id'    => 253,
                'title' => 'xmlfeed_access'
            ],
            [
                'id'    => 254,
                'title' => 'xmlfeed_create'
            ],
            [
                'id'    => 255,
                'title' => 'xmlfeed_export_access'
            ],
            [
                'id'    => 256,
                'title' => 'xmlfeed_edit'
            ],
            [
                'id'    => 257,
                'title' => 'xmlfeed_delete'
            ],
            [
                'id'    => 258,
                'title' => 'default_xmlfeed_access'
            ],
            [
                'id'    => 259,
                'title' => 'payment_meethods_create'
            ],
            [
                'id'    => 260,
                'title' => 'payment_meethods_access'
            ],
            [
                'id'    => 261,
                'title' => 'payment_details_access'
            ],
            [
                'id'    => 262,
                'title' => 'notification_access'
            ],
            [
                'id'    => 263,
                'title' => 'fbpixel_export_access'
            ],
            [
                'id'    => 264,
                'title' => 'legal_policy_access'
            ],
            [
                'id'    => 265,
                'title' => 'account_create'
            ],
            [
                'id'    => 266,
                'title' => 'tax_access'
            ],
            [
                'id'    => 267,
                'title' => 'billing_access'
            ],
            [
                'id'    => 268,
                'title' => 'gift_card_accesss'
            ],
            [
                'id'    => 269,
                'title' => 'languages_access'
            ],
            [
                'id'    => 270,
                'title' => 'select_theme_access'
            ],
            [
                'id'    => 271,
                'title' => 'user_export_access'
            ],
            [
                'id'    => 272,
                'title' => 'general_access'
            ],
            [
                'id'    => 273,
                'title' => 'custom_settings_create'
            ],
            [
                'id'    => 274,
                'title' => 'location_access'
            ],
            [
                'id'    => 275,
                'title' => 'plan_create'
            ],
            [
                'id'    => 276,
                'title' => 'xmlfeed_access_create'
            ],
            [
                'id'    => 277,
                'title' => 'payment_settings_create'
            ],
            [
                'id'    => 278,
                'title' => 'default_xmlfeed_access_create'
            ],
            [
                'id'    => 279,
                'title' => 'notification_settings_access'
            ],
            [
                'id'    => 280,
                'title' => 'fbpixel_access_create'
            ],
            [
                'id'    => 281,
                'title' => 'user_account_access' 
            ],
            [
                'id'    => 282,
                'title' => 'legal_policy_access_create'
            ],
            [
                'id'    => 283,
                'title' => 'checkout_create'
            ],
            [
                'id'    => 284,
                'title' => 'gift_card_create'
            ],
            [
                'id'    => 285,
                'title' => 'sales_channel_access_create'
            ],
            [
                'id'    => 286,
                'title' => 'select_theme_create'
            ],
            [
                'id'    => 287,
                'title' => 'tax_access_create'
            ],
            [
                'id'    => 288,
                'title' => 'billing_access_create'
            ],
            [
                'id'    => 289,
                'title' => 'manage_account_acccess'
            ],
            [
                'id'    => 290,
                'title' => 'files_access'
            ],
            [
                'id'    => 291,
                'title' => 'files_access_create'
            ],
            [
                'id'    => 292,
                'title' => 'shipping_access'
            ],
            [
                'id'    => 293,
                'title' => 'shipping_access_create'
            ],
            [
                'id'    => 294,
                'title' => 'account_settings_general'
            ],
            [
                'id'    => 295,
                'title' => 'account_change_password'
            ],
            [
                'id'    => 296,
                'title' => 'account_information'
            ],
            [
                'id'    => 297,
                'title' => 'account_social_link'
            ],
            [
                'id'    => 298,
                'title' => 'account_settings_notification'
            ],
            [
                'id'    => 299,
                'title' => 'languages_settings_create'
            ],
            [
                'id'    => 300,
                'title' => 'domain_access'
            ],
            [
                'id'    => 301,
                'title' => 'domain_access_create'
            ],
            [
                'id'    => 302,
                'title' => 'languages_settings_access'
            ],
            [
                'id'    => 303,
                'title' => 'languages_settings_edit'
            ],
            [
                'id'    => 304,
                'title' => 'languages_settings_delete'
            ],
            [
                'id'    => 305,
                'title' => 'languages_settings_export'
            ],
            [
                'id'    => 306,
                'title' => 'languages_selection_access'
            ],
            [
                'id'    => 307,
                'title' => 'languages_selection_create'
            ],
            [
                'id'    => 308,
                'title' => 'notification_create'
            ],
            [
                'id'    => 309,
                'title' => 'notification_edit'
            ],
            [
                'id'    => 310,
                'title' => 'notification_delete'
            ],
            [
                'id'    => 311,
                'title' => 'notification_export_access'
            ],
            [
                'id'    => 312,
                'title' => 'notification_settings_create'
            ],
            [
                'id'    => 313,
                'title' => 'product_type_export'
            ],
            [
                'id'    => 314,
                'title' => 'variant_medium_export'
            ],
            [
                'id'    => 315,
                'title' => 'product_variant_option_export'

            ],
            [
                'id'    => 316,
                'title' => 'sales_channel_export'
            ],
            [
                'id'    => 318,
                'title' => 'gift_card_denomination_export'
            ],
            [
                'id'    => 319,
                'title' => 'gift_card_tag_export'
            ],
            [
                'id'    => 320,
                'title' => 'gift_card_vendor_export'
            ],
            [
                'id'    => 321,
                'title' => 'gift_card_issue_export'
            ],
            [
                'id'    => 322,
                'title' => 'gift_card_collection_export'
            ],
            [
                'id'    => 323,
                'title' => 'order_financial_status_export'
            ],
            [
                'id'    => 324,
                'title' => 'order_product_export'
            ],
            [
                'id'    => 325,
                'title' => 'order_product_variant_export'
            ],
            [
                'id'    => 326,
                'title' => 'stock_export'
            ],
            [
                'id'    => 327,
                'title' => 'user_store_industry_export'
            ],
            [
                'id'    => 328,
                'title' => 'add_cart_access'
            ],
            [
                'id'    => 329,
                'title' => 'add_cart_create'
            ],
            [
                'id'    => 330,
                'title' => 'add_cart_edit'
            ],
            [
                'id'    => 331,
                'title' => 'add_cart_delete'
            ],
            [
                'id'    => 332,
                'title' => 'add_cart_show'
            ],
            [
                'id'    => 333,
                'title' => 'add_cart_export'
            ],
            [
                'id'    => 334,
                'title' => 'notification_template_create'
            ],
            [
                'id'    => 335,
                'title' => 'abandone_checkout_create'
            ],
            [
                'id'    => 336,
                'title' => 'abandone_checkout_edit'
            ],
            [
                'id'    => 337,
                'title' => 'abandone_checkout_delete'
            ],
            [
                'id'    => 338,
                'title' => 'abandone_checkout_show'
            ],
            [
                'id'    => 339,
                'title' => 'abandone_checkout_access'
            ],
            [
                'id'    => 340,
                'title' => 'abandone_checkout_export'
            ],
            [
                'id'    => 341,
                'title' => 'theme_page_accesss'
            ],
            [
                'id'    => 342,
                'title' => 'theme_home_page_accesss'
            ],
            [
                'id'    => 343,
                'title' => 'theme_product_detail_page_accesss'
            ],
            [
                'id'    => 344,
                'title' => 'theme_menu_accesss'
            ],
            [
                'id'    => 345,
                'title' => 'admin_settings_access'
            ],
            [
                'id'    => 346,
                'title' => 'admin_settings_create'
            ],
            [
                'id'    => 347,
                'title' => 'theme_settings_create'
            ],
            [
                'id'    => 348,
                'title' => 'theme_settings_access'
            ],
            [
                'id'    => 349,
                'title' => 'order_refund'
            ],
            [
                'id'    => 350,
                'title' => 'dimensions_access'
            ],
            [
                'id'    => 351,
                'title' => 'dimensions_create'
            ],
            [
                'id'    => 352,
                'title' => 'dimensions_edit'
            ],
            [
                'id'    => 353,
                'title' => 'dimensions_delete'
            ],
            [
                'id'    => 354,
                'title' => 'dimensions_show'
            ],
            [
                'id'    => 355,
                'title' => 'dimensions_export'
            ],
            [
                'id'    => 356,
                'title' => 'returnorders_access'
            ],
            [
                'id'    => 357,
                'title' => 'returnorders_refund'
            ],
            [
                'id'    => 358,
                'title' => 'returnorders_show'
            ],
            [
                'id'    => 359,
                'title' => 'returnorders_export'
            ],
            [
                'id'    => 360,
                'title' => 'couriers_access'
            ],
            [
                'id'    => 361,
                'title' => 'couriers_create'
            ],
            [
                'id'    => 362,
                'title' => 'couriers_edit'
            ],
            [
                'id'    => 363,
                'title' => 'couriers_delete'
            ],
            [
                'id'    => 364,
                'title' => 'couriers_show'
            ],
            [
                'id'    => 365,
                'title' => 'couriers_export'
            ],
                       [
                'id'    => 366,
                'title' => 'tracking_access'
            ],
            [
                'id'    => 367,
                'title' => 'tracking_create'
            ],
            [
                'id'    => 368,
                'title' => 'tracking_edit'
            ],
            [
                'id'    => 369,
                'title' => 'tracking_delete'
            ],
            [
                'id'    => 370,
                'title' => 'tracking_show'
            ],
            [
                'id'    => 371,
                'title' => 'tracking_export'
            ],
            [
                'id'    => 372,
                'title' => 'shipment_status_access'
            ],
            [
                'id'    => 373,
                'title' => 'shipment_status_create'
            ],
            [
                'id'    => 374,
                'title' => 'shipment_status_edit'
            ],
            [
                'id'    => 375,
                'title' => 'shipment_status_delete'
            ],
            [
                'id'    => 376,
                'title' => 'shipment_status_show'
            ],
            [
                'id'    => 377,
                'title' => 'shipment_status_export'
            ],
            [
                'id'    => 378,
                'title' => 'ship_orders_access'
            ],
            [
                'id'    => 379,
                'title' => 'ship_orders_create'
            ],
            [
                'id'    => 380,
                'title' => 'ship_orders_edit'
            ],
            [
                'id'    => 381,
                'title' => 'ship_orders_delete'
            ],
            [
                'id'    => 382,
                'title' => 'ship_orders_show'
            ],
            [
                'id'    => 383,
                'title' => 'ship_orders_export'
            ],
            [
                'id'    => 384,
                'title' => 'shipping_product_access'
            ],
            [
                'id'    => 385,
                'title' => 'shipping_product_show'
            ],
            [
                'id'    => 386,
                'title' => 'shipping_product_export'
            ],
            [
                'id'    => 387,
                'title' => 'btn_shipping_order_access'
            ],
            [
                'id'    => 388,
                'title' => 'btn_delete_order_access'
            ],
            [
                'id'    => 389,
                'title' => 'btn_delete_shipping_access'
            ],
            [
                'id'    => 390,
                'title' => 'btn_cancel_shipping_access'
            ],
            [
                'id'    => 391,
                'title' => 'btn_delete_product_action_access'
            ],
            [
                'id'    => 392,
                'title' => 'btn_generate_manifest_access'
            ],
            [
                'id'    => 393,
                'title' => 'btn_print_manifest_access'
            ],
            [
                'id'    => 394,
                'title' => 'btn_generate_label_access'
            ],
            [
                'id'    => 395,
                'title' => 'btn_generate_invoice_access'
            ],
            [
                'id'    => 396,
                'title' => 'shipping_details_access'
            ],
            [
                'id'    => 397,
                'title' => 'shipping_details_create'
            ],
            [
                'id'    => 398,
                'title' => 'return_shipping_product_access'
            ],
            [
                'id'    => 399,
                'title' => 'return_shipping_product_show'
            ],
            [
                'id'    => 400,
                'title' => 'return_shipping_product_export'
            ],
            [
                'id'    => 401,
                'title' => 'return_shipping_product_delete'
            ],
            [
                'id'    => 402,
                'title' => 'shipping_product_delete'
            ],
            [
                'id'    => 403,
                'title' => 'returnorders_delete'
            ],
            [
                'id'    => 404,
                'title' => 'btn_pickup_access'
            ],
            [
                'id'    => 405,
                'title' => 'btn_track_url_access'
            ], 
            [
                'id'    => 406,
                'title' => 'btn_save_shipping_access'
            ],
            [
                'id'    => 407,
                'title' => 'exchangeorders_access'
            ],
            [
                'id'    => 408,
                'title' => 'exchangeorders_show'
            ],
            [
                'id'    => 409,
                'title' => 'exchangeorders_delete'
            ],
            [
                'id'    => 410,
                'title' => 'exchangeorders_export'
            ],
            [
                'id'    => 411,
                'title' => 'btn_return_shipping_order_access'
            ],
            [
                'id'    => 412,
                'title' => 'btn_return_delete_order_access'
            ],
            [
                'id'    => 413,
                'title' => 'btn_return_delete_shipping_access'
            ],
            [
                'id'    => 414,
                'title' => 'btn_return_cancel_shipping_access'
            ],
            [
                'id'    => 415,
                'title' => 'btn_return_delete_product_action_access'
            ],
            [
                'id'    => 416,
                'title' => 'btn_return_generate_manifest_access'
            ],
            [
                'id'    => 417,
                'title' => 'btn_return_print_manifest_access'
            ],
            [
                'id'    => 418,
                'title' => 'btn_return_generate_label_access'
            ],
            [
                'id'    => 419,
                'title' => 'btn_return_generate_invoice_access'
            ],
            [
                'id'    => 420,
                'title' => 'btn_return_pickup_access'
            ],
            [
                'id'    => 421,
                'title' => 'btn_return_track_url_access'
            ], 
            [
                'id'    => 422,
                'title' => 'btn_return_save_shipping_access'
            ],
            [
                'id'    => 423,
                'title' => 'btn_shipping_action_access'
            ],
            [
                'id'    => 424,
                'title' => 'btn_return_shipping_action_access'
            ],
            [
                'id'    => 425,
                'title' => 'btn_delete_action_access'
            ],
            [
                'id'    => 426,
                'title' => 'btn_return_delete_action_access'
            ],
            [
                'id'    => 427,
                'title' => 'btn_save_shipping_order_access'
            ],
            [
                'id'    => 428,
                'title' => 'btn_return_save_shipping_order_access'
            ],
            [
                'id'    => 429,
                'title' => 'return_exchangeorders'
            ],
            [
                'id'    => 430,
                'title' => 'btn_exchange_shipping_order_access'
            ],
            [
                'id'    => 431,
                'title' => 'btn_exchange_delete_order_access'
            ],
            [
                'id'    => 432,
                'title' => 'btn_exchange_shipping_action_access'
            ],
            [
                'id'    => 433,
                'title' => 'btn_exchange_delete_action_access'
            ],
            [
                'id'    => 434,
                'title' => 'btn_shipping_delivered_access'
            ],
            [
                'id'    => 435,
                'title' => 'btn_return_shipping_delivered_access'
            ],
             [
                'id'    => 436,
                'title' => 'discounts_access'
            ],
            [
                'id'    => 437,
                'title' => 'discounts_create'
            ],
            [
                'id'    => 438,
                'title' => 'discounts_edit'
            ],
            [
                'id'    => 439,
                'title' => 'discounts_delete'
            ],
            [
                'id'    => 440,
                'title' => 'discounts_show'
            ],
            [
                'id'    => 441,
                'title' => 'discounts_export'
            ],
            [
                'id'    => 442,
                'title' => 'btn_download_invoice_access'
            ],
            [
                'id'    => 443,
                'title' => 'btn_select_taxes'
            ],
            [
                'id'    => 444,
                'title' => 'state_tax_access'
            ],
            [
                'id'    => 445,
                'title' => 'btn_delete_invoice_access'
            ],
            [
                'id'    => 446,
                'title' => 'add_rates_access'
            ],
            [
                'id'    => 447,
                'title' => 'btn_paid_order_access'
            ],
             [
                'id'    => 448,
                'title' => 'shipping_product_import_access'
            ],
            
        ];
        Permission::insert($permissions);

    }
}
