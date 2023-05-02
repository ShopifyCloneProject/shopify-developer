<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Collection;
use App\Models\ProductCollection;
use App\Models\ProductMedium;
use App\Models\Product;
use App\Models\Pages;
use App\Models\PageMedia;
use App\Models\PageOption;

use Auth;
use Exception;
use Helper;

class HomeController extends Controller
{
    public function index()
    {   
        $selectedCollection = [];
        $optionValue = Helper::getOption('home_collections');
        if($optionValue != '') {
          $selectedCollection = explode(', ', $optionValue);
        }

        $objCollections = Collection::with(['products' => function($query){
              $query->select('id', 'title', 'description', 'price', 'compare_at_price', 'special_price', 'slug')->where('status',1);
        },'products.medias']);

        if(!empty($selectedCollection)){
            $objCollections = $objCollections->whereIn('id', $selectedCollection);
        }

        $objCollections = $objCollections->orderBy('updated_at', 'desc')->get()->map(function ($query) {
            $query->setRelation('products', $query->products->take(4));
            return $query;
        });

        $objSliderData = PageMedia::where('section_id',2)->orderBy('order')->get();
        $objAccessoriesData = PageMedia::where('section_id',3)->orderBy('order')->get();
        $objCompanyLogos = PageMedia::where('section_id',4)->orderBy('order')->get();
        $objTrends = PageMedia::where('section_id',6)->orderBy('order')->get();

        $objNewArriaval = Product::with('medias')->select('id','title','description','price','compare_at_price','special_price','slug')->where('status',1)->latest()->limit(50)->get()->shuffle();
        $newArriaval = $objNewArriaval->skip(42);

        $user = $this->checkAuthUser();
        $data = [
            'collections' => $objCollections,
            'page'        => 'home',
            'newArriaval' => $newArriaval,
            'user'        => $user,
            'sliders'      => $objSliderData,
            'accessories'   => $objAccessoriesData,
            'companylogos'   => $objCompanyLogos,
            'trends'         => $objTrends,
        ];

        if(false)
        {

        }
        else
        {
            return view('theme.default.pages.home', compact('data'));
        }
    }

     public function refund()
    {
        $user = $this->checkAuthUser();
        $objPages = Pages::where('pages','refund')->first();
        $data = [
            'page'        => 'static',
            'pageData'    => $objPages,
            'user'        => $user
        ];
        if(false){

        }
        else{
            return view('theme.default.pages.refund', compact('data'));
        }
    }

     public function privacy()
    {
        $user = $this->checkAuthUser();
        $objPages = Pages::where('pages','privacy')->first();
        $data = [
            'page'        => 'static',
            'pageData'    => $objPages,
            'user'        => $user
        ];
        if(false){

        }
        else{
           return view('theme.default.pages.privacy', compact('data'));
     }
    }

     public function termsandconditions()
    {
        $user = $this->checkAuthUser();
        $objPages = Pages::where('pages','terms')->first();
        $data = [
            'page'        => 'static',
            'pageData'    => $objPages,
            'user'        => $user
        ];
        if(false){

        }
        else{
            return view('theme.default.pages.terms', compact('data'));
        }
    }

    public function shippingpolicy()
    {
        $user = $this->checkAuthUser();
        $objPages = Pages::where('pages','shippingpolicy')->first();
        $data = [
            'page'        => 'static',
            'pageData'    => $objPages,
            'user'        => $user
        ];
        if(false){

        }
        else{
            return view('theme.default.pages.shippingpolicy', compact('data'));
        }
    }

    public function pagenotfound()
    {
        $user = $this->checkAuthUser();
        $data = [
            'page'        => 'pagenotfound',
            'user'        => $user
        ];
        if(false){

        }
        else{
            return view('theme.default.pages.home', compact('data'));
        }
    }

    public  function countLive()
    {
        try {
            $this->setLiveUserCount('live');
            return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.LIVE_USER_COUNT_SUCCESFULLY.code'),
                __('constants.messages.LIVE_USER_COUNT_SUCCESFULLY.msg'),
                []
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
