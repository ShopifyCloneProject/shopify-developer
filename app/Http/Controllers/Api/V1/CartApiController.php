<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use App\Models\Product;

class CartApiController extends ApiController
{
    public function getSearchProducts(Request $request){
        try{
            $params = collect($request->all());
            $search = $params['search'];
            $response = [];
            if($search != ''){
                $objProducts = Product::with('product_variant_options','medias','product_variant_options.variant_media')
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
