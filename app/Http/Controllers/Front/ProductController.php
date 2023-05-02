<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Collection;
use App\Models\VariantOption;
use App\Models\Vendor;
use App\Models\ProductType;
use App\Models\Tag;
use App\Models\Product;

use Redirect;
use Helper;
use Exception;
use DB;
class ProductController extends Controller
{
    public function index()
    {   
        $user = $this->checkAuthUser();
        $collections = Collection::select('id', 'title')->where('status', 1)->get();
        $size = VariantOption::select('id', 'variant_id', 'options')->where('variant_id', 1)->get();
        $color = VariantOption::select('id', 'variant_id', 'options')->where('variant_id', 2)->get();
        $material = VariantOption::select('id', 'variant_id', 'options')->where('variant_id', 3)->get();
        $style = VariantOption::select('id', 'variant_id', 'options')->where('variant_id', 4)->get();
        $title = VariantOption::select('id', 'variant_id', 'options')->where('variant_id', 5)->get();
        $vendors = Vendor::select('id', 'name')->where('status', 1)->get();
        $productTypes = ProductType::select('id', 'title')->where('status', 1)->get();
        $tags = Tag::select('id', 'title')->where('status', 1)->get();

        $data = [
            'page' => 'shop',
            'user' => $user,
            'collections' => $collections,
            'size' => $size,
            'color' => $color,
            'material' => $material,
            'style' => $style,
            'title' => $title,
            'vendors' => $vendors,
            'tags' => $tags,
        ];
        if(false){

        }
        else{
            return view('theme.default.pages.shop', compact('data'));
        }
    }

    public function getProducts(Request $request)
    {
        try {
            $params = collect($request->all());
            $perPage = $params['perPage'];
            $sortType = $params['sortType'];
            $currentPage = $params['pid'];
            $price = isset($params['price']) ? $params['price'] : [];
            $size = isset($params['size']) ? $params['size'] : [];
            $color = isset($params['color']) ? $params['color'] : [];
            $material = isset($params['material']) ? $params['material'] : [];
            $style = isset($params['style']) ? $params['style'] : [];
            $titles = isset($params['titles']) ? $params['titles'] : [];
            $brand = isset($params['brand']) ? $params['brand'] : [];
            $tags = isset($params['tags']) ? $params['tags'] : [];
            $collections = isset($params['collections']) ? $params['collections'] : [];

            // DB::connection()->enableQueryLog();
            $products = Product::select('id', 'title', 'status', 'description', 'price', 'compare_at_price', 'special_price', 'slug', 'is_continue_selling')->where('status', 1)->withCount('order_products');

            //filter by collections
            if(!empty($collections)){
                $products = $products->whereHas('collections', function($query) use($collections){
                   $query->whereIn('id', $collections);
                });
            }
            
            //filter by price
            if(!empty($price)){
                $products = $products->whereBetween('price', $price);
            }

            //filter by brand/vendor
            if(!empty($brand)){
                $products = $products->whereIn('vendor_id', $brand);
            }

            //filter products by tags
            if(!empty($tags)){
                $products = $products->whereHas('product_tags', function($query1) use($tags){
                   $query1->whereIn('tag_id', $tags);
                });
            }

            //filter products by size
            if(!empty($size)){
                $products = $products->whereHas('product_variant_options', function($query1) use($size){
                    $query1 = $query1->where(function($query2) use ($size){
                        $query2->where('variant_option_1_id', $size);
                        $query2->orWhereIn('variant_option_2_id', $size);
                        $query2->orWhereIn('variant_option_3_id', $size);
                    });
                });
            }

            //filter products by color
            if(!empty($color)){
                $products = $products->whereHas('product_variant_options', function($query1) use($color){
                    $query1 = $query1->where(function($query2) use ($color){
                        $query2->where('variant_option_1_id', $color);
                        $query2->orWhereIn('variant_option_2_id', $color);
                        $query2->orWhereIn('variant_option_3_id', $color);
                    });
                });
            }

            //filter products by material
            if(!empty($material)){
                $products = $products->whereHas('product_variant_options', function($query1) use($material){
                    $query1 = $query1->where(function($query2) use ($material){
                        $query2->where('variant_option_1_id', $material);
                        $query2->orWhereIn('variant_option_2_id', $material);
                        $query2->orWhereIn('variant_option_3_id', $material);
                    });
                });
            }

            //filter products by style
            if(!empty($style)){
                $products = $products->whereHas('product_variant_options', function($query1) use($style){
                    $query1 = $query1->where(function($query2) use ($style){
                        $query2->where('variant_option_1_id', $style);
                        $query2->orWhereIn('variant_option_2_id', $style);
                        $query2->orWhereIn('variant_option_3_id', $style);
                    });
                });
            }

            //filter products by title
            if(!empty($titles)){
                $products = $products->whereHas('product_variant_options', function($query1) use($titles){
                    $query1 = $query1->where(function($query2) use ($titles){
                        $query2->where('variant_option_1_id', $titles);
                        $query2->orWhereIn('variant_option_2_id', $titles);
                        $query2->orWhereIn('variant_option_3_id', $titles);
                    });
                });
            }

            $products = $products->with(['medias' => function($query){
                $query->select('id', 'product_id', 'src','client_id');
            }]);

            //filter by sort order
            if($sortType == 'new'){
                $products = $products->orderBy('products.updated_at', 'desc');
            } elseif($sortType == 'lowest'){
                $products = $products->orderBy('price');
            } elseif($sortType == 'highest'){
                $products = $products->orderBy('price', 'DESC');
            } 
            elseif($sortType == 'highest_sale'){
                $products = $products->orderBy('order_products_count', 'desc');
            }

            $products = $products->get();

            // dd( DB::getQueryLog());
            $totalRecords = $products->count();

            $products = collect($products);
            $products = $products->slice($perPage * ( $currentPage - 1 ))->take($perPage);
            $products = $products->values()->all();

            $totalPages = ceil($totalRecords / $perPage);
            $totalPages = $totalPages == 0 ? 1 : $totalPages; 

            $response = ['currentPage' => (int)$currentPage, 'totalPages' => (int)$totalPages, 'totalRecords' => $totalRecords , 'products' => $products];
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
