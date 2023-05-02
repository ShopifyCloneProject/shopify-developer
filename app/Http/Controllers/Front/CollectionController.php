<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Collection;
use App\Models\VariantOption;
use App\Models\Vendor;
use App\Models\ProductType;
use App\Models\Tag;

use Redirect;
use Helper;
use Exception;
use DB;

class CollectionController extends Controller
{
    public function __construct(){
    
    }

    public function index()
    {   
        $user = $this->checkAuthUser();

        $collections = Collection::select('id', 'title', 'url', 'src_alt_text', 'slug')->with(['products' => function($query){
                $query = $query->select('id', 'title', 'status', 'slug')->where('status',1);
        }])->get();

        foreach($collections as $key=>$collection){
            $collection->products_count = $collection->products->count();
            $collection->unsetRelation('products');
        }

        $data = [
            'page' => 'collections',
            'user' => $user,
            'collections' => $collections
        ];
        if(false){

        }
        else{
            return view('theme.default.pages.collections', compact('data'));
        }
    }

    public function detail($slug)
    {   
        $user = $this->checkAuthUser();
        $collection = Collection::select('id', 'title', 'slug')->where('slug', $slug)->first();
        //if collection not found
        if(!$collection){
            $data = [
                'user'        => $user,
                'page' => 'pagenotfound',
            ];
        if(false){

        }
        else{
            return view('theme.default.pages.productdetail', compact('data'));
        }
    }

        $size = VariantOption::select('id', 'variant_id', 'options')->where('variant_id', 1)->get();
        $color = VariantOption::select('id', 'variant_id', 'options')->where('variant_id', 2)->get();
        $material = VariantOption::select('id', 'variant_id', 'options')->where('variant_id', 3)->get();
        $style = VariantOption::select('id', 'variant_id', 'options')->where('variant_id', 4)->get();
        $title = VariantOption::select('id', 'variant_id', 'options')->where('variant_id', 5)->get();
        $vendors = Vendor::select('id', 'name')->where('status', 1)->get();
        $productTypes = ProductType::select('id', 'title')->where('status', 1)->get();
        $tags = Tag::select('id', 'title')->where('status', 1)->get();

        $data = [
            'page' => 'collectiondetail',
            'user' => $user,
            'collection' => $collection,
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
            return view('theme.default.pages.collectiondetail', compact('data'));
        }
    }

    public function getFilterProducts(Request $request)
    {
        try {
            $params = collect($request->all());
            $perPage = $params['perPage'];
            $sortType = $params['sortType'];
            $slug = $params['slug'];
            $currentPage = $params['pid'];
            $price = isset($params['price']) ? $params['price'] : [];
            $size = isset($params['size']) ? $params['size'] : [];
            $color = isset($params['color']) ? $params['color'] : [];
            $material = isset($params['material']) ? $params['material'] : [];
            $style = isset($params['style']) ? $params['style'] : [];
            $titles = isset($params['titles']) ? $params['titles'] : [];
            $brand = isset($params['brand']) ? $params['brand'] : [];
            $tags = isset($params['tags']) ? $params['tags'] : [];

            // DB::connection()->enableQueryLog();
            $collection = Collection::select('id', 'title')->with(['products' => function($query) use($price, $size, $color, $material, $style, $titles, $brand, $tags, $sortType){
                $query = $query->select('id', 'title', 'status', 'description', 'price', 'compare_at_price', 'special_price', 'slug')->where('status',1)->withCount('order_products');

                //filter by price
                if(!empty($price)){
                    $query = $query->whereBetween('price', $price);
                } 

                //filter by brand/vendor
                if(!empty($brand)){
                    $query = $query->whereIn('vendor_id', $brand);
                }

                //filter products by tags
                if(!empty($tags)){
                    $query = $query->whereHas('product_tags', function($query1) use($tags){
                       $query1->whereIn('tag_id', $tags);
                    });
                }

                //filter products by size
                if(!empty($size)){
                    $query = $query->whereHas('product_variant_options', function($query1) use($size){
                        $query1 = $query1->where(function($query2) use ($size){
                            $query2->where('variant_option_1_id', $size);
                            $query2->orWhereIn('variant_option_2_id', $size);
                            $query2->orWhereIn('variant_option_3_id', $size);
                        });
                    });
                }

                //filter products by color
                if(!empty($color)){
                    $query = $query->whereHas('product_variant_options', function($query1) use($color){
                        $query1 = $query1->where(function($query2) use ($color){
                            $query2->where('variant_option_1_id', $color);
                            $query2->orWhereIn('variant_option_2_id', $color);
                            $query2->orWhereIn('variant_option_3_id', $color);
                        });
                    });
                }

                //filter products by material
                if(!empty($material)){
                    $query = $query->whereHas('product_variant_options', function($query1) use($material){
                        $query1 = $query1->where(function($query2) use ($material){
                            $query2->where('variant_option_1_id', $material);
                            $query2->orWhereIn('variant_option_2_id', $material);
                            $query2->orWhereIn('variant_option_3_id', $material);
                        });
                    });
                }

                //filter products by style
                if(!empty($style)){
                    $query = $query->whereHas('product_variant_options', function($query1) use($style){
                        $query1 = $query1->where(function($query2) use ($style){
                            $query2->where('variant_option_1_id', $style);
                            $query2->orWhereIn('variant_option_2_id', $style);
                            $query2->orWhereIn('variant_option_3_id', $style);
                        });
                    });
                }

                //filter products by title
                if(!empty($titles)){
                    $query = $query->whereHas('product_variant_options', function($query1) use($titles){
                        $query1 = $query1->where(function($query2) use ($titles){
                            $query2->where('variant_option_1_id', $titles);
                            $query2->orWhereIn('variant_option_2_id', $titles);
                            $query2->orWhereIn('variant_option_3_id', $titles);
                        });
                    });
                }

                $query = $query->with(['medias' => function($query){
                    $query->select('id', 'product_id', 'src', 'client_id');
                }]);

                //filter by sort order
                if($sortType == 'new'){
                    $query = $query->orderBy('products.updated_at', 'desc');
                } elseif($sortType == 'lowest'){
                    $query = $query->orderBy('price');
                } elseif($sortType == 'highest'){
                    $query = $query->orderBy('price', 'DESC');
                } elseif($sortType == 'highest_sale'){
                    $query = $query->orderBy('order_products_count', 'desc');
                }

            }])->where('slug', $slug)->first();

            // dd( DB::getQueryLog());
            $totalRecords = $collection->products->count();
            $products = $collection->products;
            // $products = $products->where('id', '82e95c70-fa66-11eb-aa09-3de719eabfaf')->get();

            $products = collect($products);
            $products = $products->slice($perPage * ( $currentPage - 1 ))->take($perPage);
            $products = $products->values()->all();

            // $collection->setRelation('products', $collection->products->skip($perPage * ( $currentPage - 1 ))->take(10));
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
