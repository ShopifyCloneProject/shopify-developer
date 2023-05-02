<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wishlist;
use Config;
use Auth;
use Redirect;
use Helper;
use Exception;

class WishlistController extends Controller
{
    public function index()
    {   

        $user = $this->checkAuthUser();
        $perPage = Config::get('PER_PAGE');
        $wishlist = Wishlist::select('id', 'user_id', 'product_id')->with(['product' => function($query){
                return $query->select('id','title','description','price','compare_at_price','special_price','slug')->where('status',1);
            }, 'product.medias'])
        ->whereHas('product')
        ->where('user_id', $user->id);

        $totalRecords = $wishlist->count();

        $totalPages = ceil($totalRecords / $perPage);
        $totalPages = $totalPages == 0 ? 1 : $totalPages; 

        $wishlist = $wishlist->skip(0)->take($perPage);
        $wishlist = $wishlist->get();

        $data = [
            'page' => 'wishlist',
            'user' => $user,
            'wishlist' => $wishlist,
            'totalPages' => $totalPages,
            'totalRecords' => $totalRecords
        ];
        if(false){

        }
        else{
            return view('theme.default.pages.wishlist', compact('data'));
        }
    }

    public function addToWishlist($pid){
        try{
            $userId = Auth::user()->id;
            $wishlist = Wishlist::where('product_id', $pid)->where('user_id', $userId)->first();
            if(!$wishlist){
                $wishlist = new Wishlist();
                $wishlist->user_id = $userId;
                $wishlist->product_id = $pid;
                $wishlist->save();
            }

            return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.PRODUCT_ADDED_TO_WISHLIST_SUCCESSFULLY.code'),
                __('constants.messages.PRODUCT_ADDED_TO_WISHLIST_SUCCESSFULLY.msg')
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

    public function deleteItemFromWishlist($pid){
        try{
            $userId = Auth::user()->id;
            $wishlist = Wishlist::where('product_id', $pid)->where('user_id', $userId)->first();
            if($wishlist){
               $wishlist->delete();
            }

            return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.PRODUCT_REMOVE_TO_WISHLIST_SUCCESSFULLY.code'),
                __('constants.messages.PRODUCT_REMOVE_TO_WISHLIST_SUCCESSFULLY.msg'),
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

    public function getWishlist($page){
        try{
            $userId = Auth::user()->id;
            $perPage = Config::get('PER_PAGE');
            $wishlist = Wishlist::select('id', 'user_id', 'product_id')->with(['product' => function($query){
                return $query->select('id','title','description','price','compare_at_price','special_price','slug')->where('status',1);
            }, 'product.medias'])
            ->whereHas('product')
            ->where('user_id', $userId)
            ->skip($perPage * ( $page - 1 ))->take($perPage)
            ->get();

            return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.DATA_GET_SET_SUCCESFULLY.code'),
                __('constants.messages.DATA_GET_SET_SUCCESFULLY.msg'),
                $wishlist
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
