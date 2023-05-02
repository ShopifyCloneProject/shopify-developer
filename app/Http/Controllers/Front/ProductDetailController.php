<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Product;
use App\Models\ProductCollection;
use App\Models\ProductVariantOption;
use App\Models\VariantOption;
use App\Models\Variant;
use App\Models\InventoryStock;
use App\Models\ThemeSetting;
use App\Models\Review;
use App\Models\ReviewImage;
use App\Models\OrderProduct;
use App\Models\Order;
use App\Models\Shipments;

use Auth;
use Exception;
use Helper;
use Config;
use Storage;

class ProductDetailController extends Controller
{
    public function index( $slug )
    {   
        $user = $this->checkAuthUser();
        $perPage = config::get('display_review_length');
        $totalPages = 0;
        $totalRecords = 0;
        $data = $reviews = [];

        $productDetail = Product::with('collections','medias')
        ->with(['vendor' => function($query){
            $query->select('id', 'name');
        }])
        ->where('slug', $slug)
        ->first();

        $status = $this->reviewStatus($productDetail->id);
        $objReviews = $this->getReview($productDetail->id);
        $reviews = $objReviews;

        if(!empty($reviews)){
            foreach($reviews as $review){
                $review['review_edit_access'] = false;
                if(!empty($user)){
                    if($review->user_id == $user->id){
                        $review['review_edit_access'] = true;
                    }
                }
            }
        }
        $totalRecords = $reviews->count();
        $totalPages = ceil($totalRecords / $perPage);
        $totalPages = $totalPages == 0 ? 1 : $totalPages; 
        $reviews = $reviews->skip(0)->take($perPage);

        if(!$productDetail){
            $data = [
                'user'        => $user,
                'page' => 'pagenotfound',
            ];

            if(false)
            {

            }
            else
            {
                return view('theme.default.pages.productdetail', compact('data'));
            }
        }

        //check if a variable is set and not null:
        $id = $productDetail->id;
        $relatedProducts = [];
        if($productDetail->collections->isNotEmpty()){
            $collectionIds = $productDetail->collections->pluck('id');
            $productIds = ProductCollection::whereIn('collection_id',$collectionIds)->limit(50)->get()->pluck('product_id');
            $relatedProducts = Product::with('medias')->whereIn('id',$productIds)->where('id', '!=', $id)->inRandomOrder()->limit(4)->get();
        } else {
          $relatedProducts = Product::with('medias')->where('id', '!=', $id)->inRandomOrder()->limit(4)->get();
        }

        $productDetail->makeHidden(['deleted_at', 'updated_at', 'created_at']);
        $productDetail->load('invetory_stock');

        $product_id = $productDetail->id;
        $variants = Variant::where('status',1)->select('id','title')->get();
        
        //get product variant options like sizes, color, materials
        $objVariantSelectData = [];
        $objVariantSelectDataIndex = 1;
        for( $variantindex = 0; $variantindex < 3; $variantindex++ ) {
            $fieldName = 'variant_option_'.$objVariantSelectDataIndex++.'_id';
            $objVariantSelectData[$variantindex] = ProductVariantOption::where('product_id', $product_id)->DISTINCT($fieldName)->pluck($fieldName)->toArray();
        }

        $objVariantData = [];
        foreach ($productDetail->product_variants as $key =>  $objProductVariant) {
            if(isset($objVariantSelectData[$key])){
                $objTempVariantOption = VariantOption::whereIn('id', $objVariantSelectData[$key])->get();
                if(!empty($objTempVariantOption))
                {   
                    $variantId = $objProductVariant->variant_id;
                    $objVariantData[$key]['type'] = Variant::where('id', $variantId)->value('title');
                    $objVariantData[$key]['options'] = $objTempVariantOption;
                }
            }
        }
        //get product variant options end

        //get product variant details
        $productVariants = ProductVariantOption::with('invetory_stock')->where('product_id', $productDetail->id)->get();
        $productVariants->load('variant_media');
        $productTags = $productDetail->tags->pluck('title', 'id');

        //get settings details for product detail
        $objSettings = ThemeSetting::where('page', 2)->select('id','page','sectionname','logo','status')->orderBy('order')->get();

        $layoutPosition = Helper::getOption('detail_layout_position');
        $charlimit = Helper::getOption('short_description_limit');
        $advanceSettings = [
            'detail_layout_position' => $layoutPosition == "" ? 1 : $layoutPosition,
            'short_description_limit' => $charlimit == "" ? 500 : $charlimit
        ];

        $dataSettings = ['settings' => $objSettings, 'advanceSettings' => $advanceSettings];

        $data = [
            'productdetail' => $productDetail,
            'page' => 'productdetail',
            'productvariantsdetail' => $productVariants,
            'variants' => $objVariantData,
            'tags' => $productTags,
            'relatedProducts' => $relatedProducts,
            'user'        => $user,
            'themeSettings' => $dataSettings,
            'reviews' => $reviews,
            'totalPages' => $totalPages,
            'totalRecords' => $totalRecords,
            'review_edit_access' => $status,
        ];

        if(false)
        {

        }
        else
        {
            return view('theme.default.pages.productdetail', compact('data'));
        }
    }

    public function addReview(Request $request){
        try
        {
            $params = collect($request->all());
            $user = $this->checkAuthUser();
            $client_id = Config::get('client_id');

            if(isset($params['id'])){
                $objReview = Review::where('id',$params['id'])->first();
            }
            if(!empty($user) && empty($objReview)){
                $objReview = new Review();
            }
                $objReview->user_id = $user->id;
                $objReview->product_id = $params['product_id'];
                $objReview->title = $params['title'];
                $objReview->description = $params['description'];
                $objReview->star_rating = $params['star_rating'];
                $objReview->save();

                $path = "public/$client_id";
                $this->checkFolder($path);
                $this->checkFolder($path.'/review');

                $saveMedia = ReviewImage::where(['review_id' => $objReview->id])->pluck('id')->toArray();
                $newMedia = collect($params['media'])->pluck('id')->toArray();

                $removeMedia = array_diff($saveMedia, $newMedia);
                if(!empty($removeMedia))
                {
                    $objRemoveMedia = ReviewImage::whereIn('id', $removeMedia)->get();
                    foreach($objRemoveMedia as $key => $objSingleMedia)
                    {
                        Storage::disk('public')->delete("$client_id/review/$objSingleMedia->src");
                    }
                    ReviewImage::whereIn('id', $removeMedia)->delete();
                }

                if(!empty($params['media']))
                    {
                        foreach($params['media'] as $key => $imageData)
                        {
                            $refrence_id = mt_rand( 1000, 9999);
                            $imagename = time().$refrence_id.'.png';
                            $image = file_get_contents($imageData['imageurl']);
                            Storage::disk('public')->put("$client_id/review/$imagename", $image, 'public');

                            $objReviewImage = new ReviewImage();
                            $objReviewImage->review_id = $objReview->id;
                            $objReviewImage->src = $imagename;
                            // $objReviewImage->status = $imageData['status'];
                            $objReviewImage->save();
                        }
                    }

                if(isset($params['id'])){
                    return $this->successResponse(
                        __('constants.SUCCESS_STATUS'),
                        __('constants.messages.REVIEW_UPDATED_SUCCESSFULLY.code'),
                        __('constants.messages.REVIEW_UPDATED_SUCCESSFULLY.msg'),
                        );
                }   
                else{
                    return $this->successResponse(
                        __('constants.SUCCESS_STATUS'),
                        __('constants.messages.REVIEW_SAVED_SUCCESSFULLY.code'),
                        __('constants.messages.REVIEW_SAVED_SUCCESSFULLY.msg'),
                        );
                } 

            }
        catch (Exception $e) {
            return $this->errorResponse(
                __('constants.ERROR_STATUS'),
                __('constants.errors.SOMETHING_WRONG.code'),
                __('constants.errors.SOMETHING_WRONG.msg'),
                $e->getMessage()
            );
        }
    }

    public function reviewPage($page,$product_id){
        try{
            $user = $this->checkAuthUser();
            $data = $reviews = [];
            $perPage = config::get('display_review_length');
;
            $totalPages = 0;
            $totalRecords = 0;

            $status = $this->reviewStatus($product_id);
            $reviews = $this->getReview($product_id);

            if($reviews->isNotEmpty()){
                $totalRecords = $reviews->count();
                $totalPages = ceil($totalRecords / $perPage);
                $totalPages = $totalPages == 0 ? 1 : $totalPages; 
                $objReviews = $reviews->skip($perPage * ( $page - 1 ))->take($perPage);
                $reviews = $objReviews;
                foreach($reviews as $review){
                    $review['review_edit_access'] = false;
                    if($review->user_id == $user->id){
                        $review['review_edit_access'] = true;
                    }
                }
            }

            $data = [
                'reviews' => $reviews,
                'totalPages' => $totalPages,
                'totalRecords' => $totalRecords,
                'review_edit_access' => $status,
            ];

            return $this->successResponse(
                    __('constants.SUCCESS_STATUS'),
                    __('constants.messages.REVIEW_GET_SUCCESSFULLY.code'),
                    __('constants.messages.REVIEW_GET_SUCCESSFULLY.msg'),
                    $data
                    );
        }
        catch (Exception $e) {
             return $this->errorResponse(
                __('constants.ERROR_STATUS'),
                __('constants.errors.SOMETHING_WRONG.code'),
                __('constants.errors.SOMETHING_WRONG.msg'),
                $e->getMessage()
            );
        }
    }

    public function getReview($product_id){
        $reviews = Review::with(['media','user'])
            ->with([
            'user'=> function($userquery){
                $userquery->select('id','name','last_name');
            },
            'media' => function($reviewimagequery){
                $reviewimagequery->select('id','src','review_id');
            }
            ])->select('id','title','description','star_rating','created_at','user_id')->where('product_id',$product_id)->orderBy('id', 'DESC')->get();

        return $reviews;
    }

    public function reviewStatus($product_id){
        $user = $this->checkAuthUser();
        $status = 0;
        if(!empty($user)){
            $objOrderProduct = OrderProduct::where(['product_id'=>$product_id,'user_id'=>$user->id])->pluck('order_id');
                if(!empty($objOrderProduct)){
                    $objOrder = Order::whereIn('id',$objOrderProduct)->pluck('id');
                    if(!empty($objOrder)){
                        $objShipments = Shipments::whereIn('order_id',$objOrder)->select('is_delivered')->get();
                        if(!empty($objShipments)){
                            foreach($objShipments as $shipment){
                                if($shipment->is_delivered == 1){
                                    $status = 1;
                                    $objReview = Review::where(['user_id'=>$user->id,'product_id'=>$product_id])->first();
                                    if(!empty($objReview)){
                                        $status = 2;  
                                    }
                                }
                            }
                        }
                    }
                }
            }
        return $status;
        }
    }