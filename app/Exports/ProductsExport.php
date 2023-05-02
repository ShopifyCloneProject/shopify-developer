<?php

namespace App\Exports;

use App\Models\Product;
use App\Models\ProductMedium;
use App\Models\ProductTag;
use App\Models\Tag;
use App\Models\Vendor;
use App\Models\ProductType;
use App\Models\Weightmanage;
use App\Models\ProductVariantOption;
use App\Models\Variant;
use App\Models\VariantOption;
use App\Models\ProductVariant;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use Config;

class ProductsExport implements FromCollection, WithHeadings
{
    protected $params;

    function __construct($params) {
        $this->params = $params;
    }

    public function headings(): array
    {
        $data = $this->params;
        if($data['exporttype'] == 'wordpress')
        {
            return [
                "ID", "Type", "SKU", "Name", "Published", "Is featured?", "Visibility in catalog", "Short description", "Description", "Date sale price starts" , "Date sale price ends", "Tax status" , "Tax class", "In stock?", "Stock", "Low stock amount", "Backorders allowed?", "Sold individually?", "Weight (g)", "Length (cm)", "Width (cm)", "Height (cm)", "Allow customer reviews?", "Purchase note", "Sale price", "Regular price", "Categories", "Tags", "Shipping class", "Images",
                    "Download limit", "Download expiry days", "Parent", "Grouped products", "Upsells", "Cross-sells", "External URL", "Button text", "Position", "Minimum Quantity", "Maximum Quantity", "Attribute 1 name", "Attribute 1 value(s)",
                    "Attribute 1 visible", "Attribute 1 global", "Attribute 1 default", "Attribute 2 name", "Attribute 2 value(s)",
                    "Attribute 2 visible", "Attribute 2 global", "Attribute 2 default", "Attribute 3 name", "Attribute 3 value(s)",
                    "Attribute 3 visible", "Attribute 3 global", "Attribute 3 default"
                ]; 
                
        }
        return [
            "Handle", "Title", "Body (HTML)", "Vendor", "Type", "Tags", "Published", "Option1 Name", "Option1 Value", "Option2 Name", "Option2 Value", "Option3 Name", "Option3 Value", "Variant SKU", "Variant Grams", "Variant Inventory Tracker", "Variant Inventory Qty", "Variant Inventory Policy", "Variant Fulfillment Service", "Variant Price", "Variant Compare At Price", "Variant Requires Shipping", "Variant Taxable", "Variant Barcode", "Image Src", "Image Position", "Image Alt Text", "Gift Card", "SEO Title", "SEO Description", "Google Shopping / Google Product Category", "Google Shopping / Gender", "Google Shopping / Age Group", "Google Shopping / MPN", "Google Shopping / AdWords Grouping", "Google Shopping / AdWords Labels", "Google Shopping / Condition", "Google Shopping / Custom Product", "Google Shopping / Custom Label 0", "Google Shopping / Custom Label 1", "Google Shopping / Custom Label 2", "Google Shopping / Custom Label 3", "Google Shopping / Custom Label 4", "Variant Image", "Variant Weight Unit", "Variant Tax Code", "Cost per item", "Status"
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $client_id = Config::get('client_id');
        $data = $this->params;

        $export_option = $data["export_option"];
        $export_as = $data["export_as"];
        $ids = json_decode($data["ids"]);

        $products = Product::with('product_tags', 'medias');

        if($export_option != 'all_products'){
            $products = $products->whereIn('id', $ids);
        } 
        $products = $products->where('status',1)->get();

        $productDetail = [];
        foreach ($products as $key => $product) {
            $id = $product->id;
            $slug = $product->slug;
            $title = $product->title;
            $description = $product->description;
            $seo_title = $product->seo_title;
            $seo_description = $product->seo_description;
            $medias = ProductMedium::where(['product_id' => $id])->orderBy('reorder')->get();

            $vendorName = '';
            if($product->vendor_id != ''){
                $vendor = Vendor::where('id', $product->vendor_id)->first();
                if($vendor){
                    $vendorName = $vendor->name;
                }
            }

            $type = '';
            if($product->product_type_id != ''){
                $productType = ProductType::where('id', $product->product_type_id)->first();
                if($productType){
                    $type = $productType->title;
                }
            }

            $tags = $this->getTag($product);
            if($data['exporttype'] == 'wordpress')
            {
                $id = $type = $sku =  $saleStart = $saleEnd = $taxclass = $lowStock = $stock = $weight = $length = $width = $height = $collection =  $images = $parentSKU = $option1Name = $option1Value = $option1Visible = $option1Global = $option1Default = $option2Name = $option2Value = $option2Visible = $option2Global = $option2Default = $option3Name = $option3Value = $option3Visible = $option3Global = $option3Default = $variantAttributeData = null;
                $published = 1; 
                $isFeature = $salePrice = $regularPrice = $minimumQty = $maximumQty = 0;

                    $id = $product->id;
                    $type = ($product->is_product_variant)?"variable":"simple";
                    $sku = $product->sku;
                    $saleStart = $product->db_start_schedule_date;
                    $saleEnd = $product->db_schedule_time;
                    $stock = $product->quantity;
                    $weight = $product->weight;
                    $length = $product->length;
                    $width = $product->width;
                    $height = $product->height;
                    $salePrice = $product->original_price;
                    $regularPrice = $product->compare_at_price;
                    $collection = implode(", ",$product->collections->pluck('title')->toArray());
                    $objMediaData = [];
                    foreach($medias as $key => $objMedia){
                        $objMediaData[] = Config::get('app.url').$objMedia['image_src'][0];
                    }
                    $images = implode(", ",$objMediaData);
                    $parentSKU = ($product->is_product_variant)?$product->sku:$parentSKU;
                    $minimumQty = $product->min_order_limit;
                    $maximumQty = $product->max_order_limit;
                    if($product->is_product_variant){
                        $variantAttributeData = $this->getAllVariantAttributes($product);
                        $salePrice = $regularPrice = null;
                        if(isset($variantAttributeData[0]))
                        {   
                            $option1Visible = 1;
                            $option1Global = "0";
                            $option1Value = implode(", ",$variantAttributeData[0]);
                            $option1Default = $variantAttributeData[0][0];
                            $option1 = $this->getVariantionIdName($product->product_variant_options[0],1);
                            $option1Name = $option1['name'];
                        }

                        if(isset($variantAttributeData[1]))
                        {   
                            $option2Visible = 1;
                            $option2Global = "0";
                            $option2Value = implode(", ",$variantAttributeData[1]);
                            $option2Default = $variantAttributeData[1][0];
                            $option2 = $this->getVariantionIdName($product->product_variant_options[0],2);
                            $option2Name = $option2['name'];
                        }

                        if(isset($variantAttributeData[2]))
                        {   
                            $option3Visible = 1;
                            $option3Global =  "0";
                            $option3Value = implode(", ",$variantAttributeData[2]);
                            $option3Default = $variantAttributeData[2][0];
                            $option3 = $this->getVariantionIdName($product->product_variant_options[0],3);
                            $option3Name = $option3['name'];
                        }
                    }
               
                $productDetail[] = [
                     "ID" => $id,
                     "Type" => $type,
                     "SKU" => $sku,
                     "Name" => $title,
                     "Published" => $published,
                     "Is featured?" => 0,
                     "Visibility in catalog" => "visible",
                     "Short description" => "",
                     "Description" => $description,
                     "Date sale price starts"  => $saleStart,
                     "Date sale price ends" => $saleEnd,
                     "Tax status"  => "taxable",
                     "Tax class" => $taxclass,
                     "In stock?" => 1,
                     "Stock" => $stock,
                     "Low stock amount" => "",
                     "Backorders allowed?" => 0,
                     "Sold individually?" => 0,
                     "Weight (g)" => ($weight > 0)?$weight:null,
                     "Length (cm)" => ($length > 0)?$length:null,
                     "Width (cm)" => ($width > 0)?$width:null,
                     "Height (cm)" => ($height > 0)?$height:null,
                     "Allow customer reviews?" => 1,
                     "Purchase note" => "",
                     "Sale price" => $salePrice,
                     "Regular price" => $regularPrice,
                     "Categories" => $collection,
                     "Tags" => $tags,
                     "Shipping class" => "",
                     "Images" => $images,
                     "Download limit" => "",
                     "Download expiry days" => "",
                     "Parent" => $parentSKU,
                     "Grouped products" => "",
                     "Upsells" => "",
                     "Cross-sells" => "",
                     "External URL" => "",
                     "Button text" => "",
                     "Position" => "",
                     "Minimum Quantity" => $minimumQty,
                     "Maximum Quantity" => $maximumQty,
                     "Attribute 1 name" => $option1Name,
                     "Attribute 1 value(s)" => $option1Value,
                     "Attribute 1 visible" => $option1Visible,
                     "Attribute 1 global" => $option1Global,
                     "Attribute 1 default" => $option1Default,
                     "Attribute 2 name" => $option2Name,
                     "Attribute 2 value(s)" =>  $option2Value,
                     "Attribute 2 visible" => $option2Visible,
                     "Attribute 2 global" => $option2Global,
                     "Attribute 2 default" => $option2Default,
                     "Attribute 3 name" => $option3Name,
                     "Attribute 3 value(s)" => $option3Value,
                     "Attribute 3 visible" => $option3Visible,
                     "Attribute 3 global" => $option3Global,
                     "Attribute 3 default" => $option3Default
                ];

                if($product->is_product_variant == 1)
                {
                    $variantProducts = ProductVariantOption::where('product_id',  $id)->with('variant_media')->get();
                    $count = 1;
                    foreach($variantProducts as $key1 => $vproduct){

                        $vid = $vproduct->id;
                        $vtype = "variation";
                        $vsku = $vproduct->sku;
                        $vstock = $vproduct->quantity;
                        $vweight = $vproduct->weight;
                        $vlength = $vproduct->length;
                        $vwidth = $vproduct->width;
                        $vheight = $vproduct->height;
                        $vreview = 1;
                        $vsalePrice = $vproduct->price;
                        $vregularPrice = $vproduct->compare_at_price;
                        $objMediaVariantData = [];
                        $variantMedia = $vproduct->variant_media->toArray();
                        foreach($variantMedia as $key => $objMediaVariant){
                        $objMediaVariantData[] = Config::get('app.url').$objMediaVariant['image_src'][0];
                        }
                        $vimages = implode(", ",$objMediaVariantData);
                        $vminimumQty = $vproduct->min_order_limit;
                        $vmaximumQty = $vproduct->max_order_limit;
                        $option1 = $this->getVariantionIdName($vproduct,1);
                        $option2 = $this->getVariantionIdName($vproduct,2);
                        $option3 = $this->getVariantionIdName($vproduct,3);
                        $option1Name = $option1['name'];
                        $option1Value = $option1['value'];
                        $option2Name = $option2['name'];
                        $option2Value = $option2['value'];
                        $option3Name = $option3['name'];
                        $option3Value = $option3['value'];
                       
                        $productDetail[] = [
                                 "ID" => $vid,
                                 "Type" => $vtype,
                                 "SKU" => $vsku,
                                 "Name" => $title,
                                 "Published" => $published,
                                 "Is featured?" => 0,
                                 "Visibility in catalog" => "visible",
                                 "Short description" => "",
                                 "Description" => "",
                                 "Date sale price starts"  => "",
                                 "Date sale price ends" => "",
                                 "Tax status"  => "taxable",
                                 "Tax class" => "parent",
                                 "In stock?" => 1,
                                 "Stock" => $vstock,
                                 "Low stock amount" => "",
                                 "Backorders allowed?" => 0,
                                 "Sold individually?" => 0,
                                 "Weight (g)" => ($vweight > 0)?$vweight:null,
                                 "Length (cm)" => ($vlength > 0)?$vlength:null,
                                 "Width (cm)" => ($vwidth > 0)?$vwidth:null,
                                 "Height (cm)" => ($vheight > 0)?$vheight:null,
                                 "Allow customer reviews?" => 0,
                                 "Purchase note" => "",
                                 "Sale price" => $vsalePrice,
                                 "Regular price" => $vregularPrice,
                                 "Categories" => "",
                                 "Tags" => "",
                                 "Shipping class" => "",
                                 "Images" => $vimages,
                                 "Download limit" => "",
                                 "Download expiry days" => "",
                                 "Parent" => $parentSKU,
                                 "Grouped products" => "",
                                 "Upsells" => "",
                                 "Cross-sells" => "",
                                 "External URL" => "",
                                 "Button text" => "",
                                 "Position" => "",
                                 "Minimum Quantity" => $vminimumQty,
                                 "Maximum Quantity" => $vmaximumQty,
                                 "Attribute 1 name" => $option1Name,
                                 "Attribute 1 value(s)" => $option1Value,
                                 "Attribute 1 visible" => "",
                                 "Attribute 1 global" => $option1Global,
                                 "Attribute 1 default" => "",
                                 "Attribute 2 name" => $option2Name,
                                 "Attribute 2 value(s)" => $option2Value,
                                 "Attribute 2 visible" => "",
                                 "Attribute 2 global" => $option2Global,
                                 "Attribute 2 default" => "",
                                 "Attribute 3 name" => $option3Name,
                                 "Attribute 3 value(s)" => $option3Value,
                                 "Attribute 3 visible" => "",
                                 "Attribute 3 global" => $option3Global,
                                 "Attribute 3 default" => ""
                            ];
                    }
                }
            } 
            else
            { // Shopify export
               if($product->is_product_variant == 1){
                    $variantProducts = ProductVariantOption::where('product_id',  $id)->with('variant_media')->get();
                    $count = 1;
                    foreach($variantProducts as $key1 => $vproduct){

                        $price = $vproduct->price;
                        $cprice = $vproduct->compare_at_price;
                        $variant_sku = $vproduct->sku;
                        $published = 'TRUE';
                        $variant_grams = $vproduct->weight;
                        $variant_inventory_tracker = '';
                        $variant_inventory_policy = 'deny';
                        $variant_fulfillment_service = 'manual';
                        $variant_requires_shipping = 'TRUE';
                        $variant_taxable = 'FALSE';
                        $variant_barcode = '';
                        $image_src = sizeof($medias) > $key1 ? config::get('app.url').$medias[$key1]['image_src'][0]  : '';
                        $image_position = $image_src != '' ? $count : '';
                        $gift_card = 'FALSE';
                        $cost_per_item = $vproduct->cost_per_item;
                        $status = 'active';
                        $google_shopping_condition = 'New';
                        $variant_image = '';

                        $variantMedia = $vproduct->variant_media->toArray();
                        if(!empty($variantMedia)){
                            $variant_image = config::get('app.url').$variantMedia[0]['image_src'][0];
                        }

                        $count++;
                        $google_shopping_google_product_category = 'Apparel & Accessories > Clothing';
                        $google_shopping_gender = 'unisex';
                        $google_shopping_age_group = 'adult';

                        $variant_weight_unit = '';
                        if($vproduct->weight_type_id != ''){
                            $weightType = Weightmanage::where('id', $vproduct->weight_type_id)->first();
                            if($weightType){
                                $variant_weight_unit = $weightType->short_code;
                            }
                        }

                        $option1 = $this->getVariantionIdName($vproduct,1);
                        $option2 = $this->getVariantionIdName($vproduct,2);
                        $option3 = $this->getVariantionIdName($vproduct,3);
                        $option1_name = $option1['name'];
                        $option1_value = $option1['value'];
                        $option2_name = $option2['name'];
                        $option2_value = $option2['value'];
                        $option3_name = $option3['name'];
                        $option3_value = $option3['value'];
                       
                        if($key1 > 0){
                            $title = '';
                            $description = '';
                            $vendorName = '';
                            $type = '';
                            $tags = '';
                            $google_shopping_google_product_category = '';
                            $google_shopping_gender = '';
                            $google_shopping_age_group = '';
                            $status = '';
                            $google_shopping_condition = '';
                        }

                        $productDetail[] = [
                            'handle' => $slug,
                            'title' => $title,
                            'body' => $description,
                            'vendor' => $vendorName,
                            'type' => $type,
                            'tags' => $tags,
                            'published' => $published,
                            'option1_name' => $option1_name,
                            'option1_value' => $option1_value,
                            'option2_name' => $option2_name,
                            'option2_value' => $option2_value,
                            'option3_name' => $option3_name,
                            'option3_value' => $option3_value,
                            'variant_sku' => $variant_sku,
                            'variant_grams' => $variant_grams,
                            'variant_inventory_tracker' => $variant_inventory_tracker,
                            'variant_inventory_qt' => 1000,
                            'variant_inventory_policy' => $variant_inventory_policy,
                            'variant_fulfillment_service' => $variant_fulfillment_service,
                            'variant_price' => $price,
                            'variant_compare_at_price' => $cprice,
                            'variant_requires_shipping' => $variant_requires_shipping,
                            'variant_taxable' => $variant_taxable,
                            'variant_barcode' => $variant_barcode,
                            'image_src' => $image_src,
                            'image_position' => $image_position,
                            'image_alt_text' => '',
                            'gift_card' => $gift_card,
                            'seo_title' => $seo_title,
                            'seo_description' => $seo_description,
                            'google_shopping_google_product_category' => $google_shopping_google_product_category,
                            'google_shopping_gender' => $google_shopping_gender,
                            'google_shopping_age_group' => $google_shopping_age_group,
                            'google_shopping_mpn' => '',
                            'google_shopping_adwords_grouping' => '',
                            'google_shopping_adwords_labels' => '',
                            'google_shopping_condition' => $google_shopping_condition,
                            'google_shopping_custom_product' => '',
                            'google_shopping_custom_label_0' => '',
                            'google_shopping_custom_label_1' => '',
                            'google_shopping_custom_label_2' => '',
                            'google_shopping_custom_label_3' => '',
                            'google_shopping_custom_label_4' => '',
                            'variant_image' => $variant_image,
                            'variant_weight_unit' => $variant_weight_unit,
                            'variant_tax_code' => '',
                            'cost_per_item' => $cost_per_item,
                            'status' => $status,
                        ];
                    }

                    //for media
                    $mediaLength = sizeof($medias);
                    $variationLength = sizeof($variantProducts->toArray());

                    while($mediaLength > $variationLength){
                        $index = $variationLength;
                        $variationLength++;
                        $image_src = Config::get('app.url').$medias[$index]['image_src'][0];
                        $image_position = $variationLength;

                        $productDetail[] = [
                            'handle' => $slug,
                            'title' => '',
                            'body' => '',
                            'vendor' => '',
                            'type' => '',
                            'tags' => '',
                            'published' => '',
                            'option1_name' => '',
                            'option1_value' => '',
                            'option2_name' => '',
                            'option2_value' => '',
                            'option3_name' => '',
                            'option3_value' => '',
                            'variant_sku' => '',
                            'variant_grams' => '',
                            'variant_inventory_tracker' => '',
                            'variant_inventory_qt' => '',
                            'variant_inventory_policy' => '',
                            'variant_fulfillment_service' => '',
                            'variant_price' => '',
                            'variant_compare_at_price' => '',
                            'variant_requires_shipping' => '',
                            'variant_taxable' => '',
                            'variant_barcode' => '',
                            'image_src' => $image_src,
                            'image_position' => $image_position,
                            'image_alt_text' => '',
                            'gift_card' => '',
                            'seo_title' => '',
                            'seo_description' => '',
                            'google_shopping_google_product_category' => '',
                            'google_shopping_gender' => '',
                            'google_shopping_age_group' => '',
                            'google_shopping_mpn' => '',
                            'google_shopping_adwords_grouping' => '',
                            'google_shopping_adwords_labels' => '',
                            'google_shopping_condition' => '',
                            'google_shopping_custom_product' => '',
                            'google_shopping_custom_label_0' => '',
                            'google_shopping_custom_label_1' => '',
                            'google_shopping_custom_label_2' => '',
                            'google_shopping_custom_label_3' => '',
                            'google_shopping_custom_label_4' => '',
                            'variant_image' => '',
                            'variant_weight_unit' => '',
                            'variant_tax_code' => '',
                            'cost_per_item' => '',
                            'status' => '',
                        ];
                    }

                } else {
                    // code...
                    $price = $product->price;
                    $cprice = $product->compare_at_price;
                    $variant_sku = $product->sku;
                    $published = 'TRUE';
                    $variant_grams = $product->weight;
                    $variant_inventory_tracker = '';
                    $variant_inventory_policy = 'deny';
                    $variant_fulfillment_service = 'manual';
                    $variant_requires_shipping = 'TRUE';
                    $variant_taxable = 'FALSE';
                    $variant_barcode = '';
                    $image_src = "";
                    if(!empty($medias))
                    {
                        $image_src = Config::get('app.url').$medias[0]['image_src'][0];
                    }
                    $image_position = 1;
                    $gift_card = 'FALSE';
                    $cost_per_item = $product->cost_per_item;
                    $option1_name = 'Title';
                    $option1_value = 'Default Title';

                    $variant_weight_unit = '';
                    if($product->weight_type_id != ''){
                        $weightType = Weightmanage::where('id', $product->weight_type_id)->first();
                        if($weightType){
                            $variant_weight_unit = $weightType->short_code;
                        }
                    }

                    $productDetail[] = [
                        'handle' => $slug,
                        'title' => $title,
                        'body' => $vendorName,
                        'vendor' => $vendorName,
                        'type' => $type,
                        'tags' => $tags,
                        'published' => $published,
                        'option1_name' => $option1_name,
                        'option1_value' => $option1_value,
                        'option2_name' => '',
                        'option2_value' => '',
                        'option3_name' => '',
                        'option3_value' => '',
                        'variant_sku' => $variant_sku,
                        'variant_grams' => $variant_grams,
                        'variant_inventory_tracker' => $variant_inventory_tracker,
                        'variant_inventory_qt' => 1000,
                        'variant_inventory_policy' => $variant_inventory_policy,
                        'variant_fulfillment_service' => $variant_fulfillment_service,
                        'variant_price' => $price,
                        'variant_compare_at_price' => $cprice,
                        'variant_requires_shipping' => $variant_requires_shipping,
                        'variant_taxable' => $variant_taxable,
                        'variant_barcode' => $variant_barcode,
                        'image_src' => $image_src,
                        'image_position' => $image_position,
                        'image_alt_text' => '',
                        'gift_card' => $gift_card,
                        'seo_title' => $seo_title,
                        'seo_description' => $seo_description,
                        'google_shopping_google_product_category' => 'Apparel & Accessories > Clothing',
                        'google_shopping_gender' => 'unisex',
                        'google_shopping_age_group' => 'adult',
                        'google_shopping_mpn' => '',
                        'google_shopping_adwords_grouping' => '',
                        'google_shopping_adwords_labels' => '',
                        'google_shopping_condition' => 'New',
                        'google_shopping_custom_product' => '',
                        'google_shopping_custom_label_0' => '',
                        'google_shopping_custom_label_1' => '',
                        'google_shopping_custom_label_2' => '',
                        'google_shopping_custom_label_3' => '',
                        'google_shopping_custom_label_4' => '',
                        'variant_image' => '',
                        'variant_weight_unit' => $variant_weight_unit,
                        'variant_tax_code' => '',
                        'cost_per_item' => $cost_per_item,
                        'status' => 'active',
                    ];

                    //for media
                    $mediaLength = sizeof($medias);
                    $variationLength = 1;

                    while($mediaLength > $variationLength){
                        $index = $variationLength;
                        $image_src = Config::get('app.url').$medias[$index]['image_src'][0];
                        $image_position = $variationLength + 1;

                        $productDetail[] = [
                            'handle' => $slug,
                            'title' => '',
                            'body' => '',
                            'vendor' => '',
                            'type' => '',
                            'tags' => '',
                            'published' => '',
                            'option1_name' => '',
                            'option1_value' => '',
                            'option2_name' => '',
                            'option2_value' => '',
                            'option3_name' => '',
                            'option3_value' => '',
                            'variant_sku' => '',
                            'variant_grams' => '',
                            'variant_inventory_tracker' => '',
                            'variant_inventory_qt' => '',
                            'variant_inventory_policy' => '',
                            'variant_fulfillment_service' => '',
                            'variant_price' => '',
                            'variant_compare_at_price' => '',
                            'variant_requires_shipping' => '',
                            'variant_taxable' => '',
                            'variant_barcode' => '',
                            'image_src' => $image_src,
                            'image_position' => $image_position,
                            'image_alt_text' => '',
                            'gift_card' => '',
                            'seo_title' => '',
                            'seo_description' => '',
                            'google_shopping_google_product_category' => '',
                            'google_shopping_gender' => '',
                            'google_shopping_age_group' => '',
                            'google_shopping_mpn' => '',
                            'google_shopping_adwords_grouping' => '',
                            'google_shopping_adwords_labels' => '',
                            'google_shopping_condition' => '',
                            'google_shopping_custom_product' => '',
                            'google_shopping_custom_label_0' => '',
                            'google_shopping_custom_label_1' => '',
                            'google_shopping_custom_label_2' => '',
                            'google_shopping_custom_label_3' => '',
                            'google_shopping_custom_label_4' => '',
                            'variant_image' => '',
                            'variant_weight_unit' => '',
                            'variant_tax_code' => '',
                            'cost_per_item' => '',
                            'status' => '',
                        ];

                       $variationLength++;
                    }
                }
            }
         }     
        return collect($productDetail); 
    }

    public function getTag($product)
    {
         $tags = '';
            if(!empty($product->product_tags)){
                $taglist = ProductTag::where('product_id',$product->id)->get()->pluck('tag_id')->toArray();
                if(!empty($taglist)){
                    $tags = Tag::whereIn('id', $taglist)->pluck('title')->toArray();
                    $tags = implode(', ', $tags);
                }
            }
        return $tags;
    }

    public function getVariantionIdName($vproduct, $variantIndex) // index like as variation_option1_id , variation_option2_id
    {   
        $name = $value = "";

        if($variantIndex == 1)
        {
            if($vproduct->variant_option_1_id != ''){
            $variantOption = VariantOption::where('id', $vproduct->variant_option_1_id)->first();
                if($variantOption){
                    $name = Variant::where('id', $variantOption->variant_id)->value('title');
                    $value = $variantOption->options;
                }
            }
        }
        else if($variantIndex == 2)
        {
            if($vproduct->variant_option_2_id != ''){
            $variantOption = VariantOption::where('id', $vproduct->variant_option_2_id)->first();
                if($variantOption){
                    $name = Variant::where('id', $variantOption->variant_id)->value('title');
                    $value = $variantOption->options;
                }
            }
        }
        else if($variantIndex == 3)
        {
            if($vproduct->variant_option_3_id != ''){
            $variantOption = VariantOption::where('id', $vproduct->variant_option_3_id)->first();
                if($variantOption){
                    $name = Variant::where('id', $variantOption->variant_id)->value('title');
                    $value = $variantOption->options;
                }
            }
        }

        return ["name" => $name, "value" => $value];
    }

    public function getAllVariantAttributes($product)
    {
        $objVariantSelectDataIndex = 1;
        $strVariantData = [];
        $objProductVariant = ProductVariant::where('product_id', $product->id)->get();
        for($variantindex = 0; $variantindex < 3; $variantindex++)
        {
            $fieldName = 'variant_option_'.$objVariantSelectDataIndex++.'_id';
            $objVariantSelectData[$variantindex] = ProductVariantOption::where('product_id', $product->id)->DISTINCT($fieldName)->pluck($fieldName)->toArray();
        }
            
        foreach ($objProductVariant as $key =>  $objProductVariant) {
            if(isset($objVariantSelectData[$key])){
                $objVariantOption = VariantOption::whereIn('id', $objVariantSelectData[$key])->get();
                $objTempVariantOption = $objVariantOption->pluck('options');
                if($objTempVariantOption->IsNotEmpty())
                {
                    $objVariantData[$key] = $objTempVariantOption;
                    $strVariantData[] = $objVariantData[$key]->toArray();
                }
            }
        }
        return $strVariantData;
    }
}