<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

use Redirect;
use Exception;

class SearchController extends Controller
{
    public function index()
    {   
        $user = $this->checkAuthUser();
        $perPage = env('PER_PAGE');
        $totalPages = 0;
        $totalRecords = 0;
        $products = [];
        $title = '';

        if(isset($_REQUEST['q'])){
            $title = $_REQUEST['q'];
            if($title != ''){
                $products = Product::select('id','title','description','price','compare_at_price','special_price','slug')->where('status', 1);
                $products = $products->where('title', 'LIKE', '%'.$title.'%');

                $products = $products->with([ 'medias' => function($query){
                    $query->select('id', 'product_id', 'src', 'client_id');
                }]);

                $totalRecords = $products->count();

                $totalPages = ceil($totalRecords / $perPage);
                $totalPages = $totalPages == 0 ? 1 : $totalPages; 

                $products = $products->skip(0)->take($perPage)->get();
            } 
        }

        $data = [
            'page' => 'search',
            'user' => $user,
            'title' => $title,
            'products' => $products,
            'totalPages' => $totalPages,
            'totalRecords' => $totalRecords
        ];
        if(false){

        }
        else{
            return view('theme.default.pages.search', compact('data'));
        }
    }

    public function search($page, $title)
    {   
        try{ 
            if(isset($title) && $title != ''){
                $perPage = env('PER_PAGE');

                $products = Product::select('id','title', 'slug','price','compare_at_price','special_price')->where('status', 1);
                $products = $products->where('title', 'LIKE', '%'.$title.'%');

                $products = $products->with([ 'medias' => function($query){
                    $query->select('id', 'product_id', 'src','client_id');
                }]);
                if(empty($products->medias))
                {
                    $products = $products->with([ 'medias' => function($query){
                        $query->select('id', 'product_id', 'src','client_id');
                    }]);
                }
                $totalRecords = $products->count();

                $products = $products->skip($perPage * ( $page - 1 ))->take($perPage)->get();
            } else {
                $products = [];
            }

            $data = ['total' => $totalRecords, 'data' => $products];

            return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.PRODUCT_GET_SUCCESSFULLY.code'),
                __('constants.messages.PRODUCT_GET_SUCCESSFULLY.msg'),
                $data
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
