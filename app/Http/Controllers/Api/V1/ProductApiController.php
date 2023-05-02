<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductApiController extends ApiController
{
    public function getSearchProducts(Request $request){
        try{
            $params = collect($request->all());
            $search = $params['search'];
            $response = [];
            if($search != ''){
                $objProducts = Product::select('id','title','quantity','slug','price','compare_at_price','is_product_variant')->with(['medias' => function($media){
                    $media->select('client_id','product_id','src');
                    }, 'product_variant_options' => function ($variant) {
                    $variant->select('id','product_id','variant_option_1_id','variant_option_2_id','variant_option_3_id','price','compare_at_price'); }, 'product_variant_options.variant_media' => function ($variantmedia) {
                    $variantmedia->select('client_id','product_variant_id','src'); }])
                        ->where('title', 'LIKE', $search.'%')
                        ->orWhere('sku', 'LIKE', $search.'%')
                        ->orWhere('barcode', 'LIKE', $search.'%')
                        ->orWhere('hs_code', 'LIKE', $search.'%')
                        ->orderBy('title')
                        ->get();
                $response = $this->handleProductSelect($objProducts);
            }
            return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.PRODUCT_GET_SUCCESSFULLY.code'),
                __('constants.messages.PRODUCT_GET_SUCCESSFULLY.msg'),
                $response
            );
        } catch (Exception $e) {
             return $this->errorResponse(
                __('constants.ERROR_STATUS'),
                __('constants.errors.SOMETHING_WRONG.code'),
                __('constants.errors.SOMETHING_WRONG.msg'),
                $e->getMessage()
            );
        }
    }
}
