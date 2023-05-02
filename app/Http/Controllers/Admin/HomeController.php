<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\FullCreateMedia;
use App\Models\Product;
use App\Models\XMLFeed;

use View;
use Storage;
use Config;

class HomeController extends Controller
{
    public function index()
    {
        return view('home');
    }

   
    public function makeImage()
    {
       $this->importImage();
    }


    public function makeImageConvert()
    {
       $this->callImageConvert();
        $responseData = [
                'status'      => 'success',
                'status_code' => 1,
                'message' => 'success',
            ];
        return response()->json($responseData);
    }

    public function makeXMLFile($time = 0)
    {
        $client_id = env('CLIENT_ID');
        $selectProductXML = [];
        $products = Product::with('collections','product_variant_options','medias')->limit(500)->get();
        if(!empty($products))
        {   
            foreach ($products as $key => $objProduct) {
                $mainimage = Config::get('app.url')."assets/images/no-image.jpg";
                if($objProduct->medias->IsNotEmpty()){
                     $mainimage =Config::get('app.url')."/storage/$client_id/images/$objProduct->id/".$objProduct->medias[0]['src'];
                }
                $collectionname = '';
                if($objProduct->collections->IsNotEmpty()){
                    $collectionname = $objProduct->collections[0]->title;
                }
                $selectProductXML[$key] =[
                        74 => $objProduct->id,
                        78 => $objProduct->title,
                        79 => $objProduct->slug,
                        80 => $objProduct->description,
                        81 => ($objProduct->description)?'Active':'Draft',
                        82 => $objProduct->schedule_time,
                        83 => $objProduct->price,
                        84 => $objProduct->compare_at_price,
                        85 => $objProduct->cost_per_item,
                        86 => ($objProduct->is_product_charge)?'Yes':'No',
                        87 => $objProduct->sku,
                        88 => $objProduct->barcode,
                        89 => $objProduct->quantity,
                        90 => ($objProduct->is_track)?'Yes':'No',
                        91 => ($objProduct->is_continue_selling)?'Yes':'No',
                        92 => ($objProduct->is_pysical_product)?'Yes':'No',
                        93 => $objProduct->weight,
                        94 => $objProduct->hs_code,
                        95 => $objProduct->min_order_limit,
                        96 => $objProduct->max_order_limit,
                        97 => ($objProduct->is_cod_enabled)?'Yes':'No',
                        98 => ($objProduct->is_size_chart_enabled)?'Yes':'No',
                        99 => 1,
                        100 => ($objProduct->is_special_product)?'Yes':'No',
                        101 => $objProduct->special_price,
                        102 => $objProduct->expiry_date,
                        103 => ($objProduct->special_product_status)?'Yes':'No',
                        104 => $objProduct->seo_title,
                        105 => $objProduct->seo_description,
                        106 => 'in_stock',
                        107 => ($objProduct->is_gift_card)?'Yes':'No',
                        108 => $collectionname,
                        109 => $objProduct->medias,
                        110 => Config::get('app.name')."product/detail/$objProduct->slug",
                        111 => $mainimage,
                        112 => '1604 - Apparel & Accessories > Clothing',
                        113 => 'new',
                        114 => 'yes',
                ];      
            }
        }
        $XMLFeeds = XMLFeed::where('createtime',1)->get();
        $output = View::make('home', compact('products','selectProductXML','XMLFeeds'))->render();
        $this->checkFolder("public/$client_id/xml/");
        Storage::disk('public')->put("$client_id/xml/0.xml", $output);
    
        dd("done");
    }


    
}
