<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyProductRequest;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Country;
use App\Models\Product;
use App\Models\ProductMedium;
use App\Models\ProductType;
use App\Models\ProductTag;
use App\Models\Vendor;
use App\Models\Weightmanage;
use App\Models\Dimension;
use App\Models\Variant;
use App\Models\ProductVariant;
use App\Models\VariantMedium;
use App\Models\VariantOption;
use App\Models\ProductVariantOption;
use App\Models\Collection;
use App\Models\ProductCollection;
use App\Models\Tag;
use App\Models\Address;
use App\Models\InventoryStock;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use App\Jobs\DownloadProductImages;
use App\Jobs\MediaUploadSuccessMail;
use App\Jobs\FullCreateMedia;
use App\Jobs\CreateMedia;
use App\Services\EmailService;
use App\Exports\ProductsExport;
use Carbon\Carbon;
use Storage;
use Auth;
use File;
use Excel;
use Exception;
use Config;
use Str;

class ProductsController extends Controller
{
    use MediaUploadingTrait;
    protected $emailService;

    public function __construct()
    {
        $this->emailService = new EmailService;
    }

    public function index(Request $request)
    {
        
        abort_if(Gate::denies('product_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        if ($request->ajax()) {
            try{
                    $query = Product::with(['product_type', 'vendor'])->select(sprintf('%s.*', (new Product())->table));
                    $table = Datatables::of($query);

                    $table->addColumn('actions', '&nbsp;');

                    $table->editColumn('id', function ($row) {
                        return $row->id ? $row->id : '';
                    });

                    $table->editColumn('title', function ($row) {
                        return $row->title ? Str::limit($row->title,35,"...") : '';
                    });

                    $table->addColumn('displaytitle', function ($row) {
                        return $row->title ? $row->title : '';
                    });

                    $table->editColumn('avatar', function ($row) {
                        if(!empty($row->medias) && sizeof($row->medias) > 0 && file_exists(public_path().$row->medias[0]->image_src[1]))
                        {
                            return $row->medias[0]->image_src[1];
                        }
                        return ''; 
                    });

                    $table->addColumn('product_type_title', function ($row) {
                        return $row->product_type ? $row->product_type->title : '';
                    });

                    $table->addColumn('vendor_name', function ($row) {
                        return $row->vendor ? $row->vendor->name : '';
                    });

                    $table->editColumn('status', function ($row) {
                        return $row->status;
                    });

                    $table->editColumn('is_track', function ($row) {
                        return $row->is_track;
                    });
                    
                    $table->rawColumns(['actions','displaytitle', 'product_type', 'vendor', 'is_track', 'avatar']);

                    return $table->make(true);
                }catch (Exception $e) {
                 return $this->errorResponse(
                    __('constants.ERROR_STATUS'),
                    __('constants.errors.SOMETHING_WRONG.code'),
                    __('constants.errors.SOMETHING_WRONG.msg'),
                    $e->getMessage()
                    );
                } 
        }

        $product_types = ProductType::get();
        $vendors       = Vendor::get();
       /* $weightmanages = Weightmanage::get();
        $countries     = Country::get();*/

        $msg = '';
        $objProductMedia = ProductMedium::where('source', 1)->count();
        $objProductVariantMedia = VariantMedium::where('source', 1)->count();
        if($objProductMedia > 0 || $objProductVariantMedia > 0)
        {
            $msg = __('constants.messages.PRODUCT_IMPORT_MEDIA_PROCESSING.msg');
        }

        if($msg == '')
        {
            $objProductMediaConvert = ProductMedium::where('convert', 0)->count();
            $objProductVariantMediaConvert = VariantMedium::where('convert', 0)->count();
            if($objProductMediaConvert > 0 || $objProductVariantMediaConvert > 0)
            {
                $msg = __('constants.messages.PRODUCT_IMPORT_MEDIA_CONVERTING.msg');
            }
        }
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['name' => trans('cruds.product.title')." ".trans('global.listing') ]];
        return view('admin.products.index', compact('product_types', 'vendors', 'msg','breadcrumbs'));
    }

    public function create()
    {
        abort_if(Gate::denies('product_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $collections = Collection::select('title', 'id')->get();
        $collections->map(function($d) { $d['checked'] = false; return $d; });


        $product_types = ProductType::get();
        $vendors       = Vendor::get();
        $weightmanages = Weightmanage::where('status',1)->get();
        $weight_types = $weightmanages->pluck('title', 'id');
        $dimension = Dimension::where('status',1)->get();
        $dimension_types = $dimension->pluck('title', 'id');
        $countries     = Country::get();
        $tags = Tag::where('status',1)->select('id','title')->get();
        $variants = Variant::where('status',1)->select('id','title')->get();
        $address = Address::where('store_status',1)->get();

        $locations = [];
        foreach($address as $key => $objLocations)
        {
            $objTempLocations = [];
            $objTempLocations['id'] = $objLocations->id;
            $objTempLocations['name'] = $objLocations->location_name;
            $objTempLocations['available'] = 0;
            $objTempLocations['incoming'] = 0;
            $locations[] = $objTempLocations;
        }

        $list = [
            'product_types' => $product_types,
            'variants' => $variants,
            'vendors' => $vendors,
            'weight_type' => $weight_types,
            'dimension_types' => $dimension_types,
            'countries' => $countries,
            'collections' => $collections->toArray(),
            'tags' => $tags->toArray(),
            'status' => Product::STATUS_SELECT,
            'is_product_charge' => Product::IS_PRODUCT_CHARGE_RADIO,
            'action'=> 'add',
            'locations' => $locations,
        ];

        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['link'=>route('admin.products.index'),'name' => trans('cruds.product.title')." ".trans('global.listing')],['name' => trans('global.add')." ".trans('cruds.product.title_singular') ]];
        return view('admin.products.create', compact('list','breadcrumbs'));
    }

    public function store(StoreProductRequest $request)
    {
        try{
            $client_id = Config::get('client_id');
            $params = collect($request->all());
            $user_id = Auth::user()->id;
            
            $objVendorId = null;
            if(isset($params['vendor']['name']))
            {
                $objVendor = Vendor::where('name', $params['vendor']['name'])->first();
                if(empty($objVendor))
                {
                    $objVendor = new Vendor;
                    $objVendor->name = $params['vendor']['name'];
                    $objVendor->status = 1;
                    $objVendor->save();
                }
                $objVendorId = $objVendor->id;
            }

            $objProductTypeId = null;
            if(isset($params['product_type']['title']))
            {
                $objProductType = ProductType::where('title', $params['product_type']['title'])->first();
                if(empty($objProductType))
                {
                    $objProductType = new ProductType;
                    $objProductType->title = $params['product_type']['title'];
                    $objProductType->status = 1;
                    $objProductType->save();
                }
                $objProductTypeId = $objProductType->id;
            }

            $objProduct = new Product;
            $objProduct->product_type_id = $objProductTypeId;
            $objProduct->vendor_id = $objVendorId;
            $objProduct->country_id = $params['country_id'];
            $objProduct->is_product_variant = ($params['is_product_variant'])?1:0;
            $objProduct->title = $params['title'];
            $objProduct->description = $params['description'];
            $objProduct->status = $params['status'];
            $objProduct->is_online = $params['is_online'];

            $schedule_date = null;
            if($params['is_online']){
                if($params['schedule_time'] != ''){
                     $schedule_date = date("Y-m-d H:i:s",strtotime($params['schedule_time']));
                }
            }
            $objProduct->schedule_time = $schedule_date;
            $objProduct->price = $params['price'];
            $objProduct->compare_at_price = $params['compare_at_price'];
            $objProduct->cost_per_item = $params['cost_per_item'];
            $objProduct->is_product_charge = ($params['is_product_charge'])?1:0;
            $objProduct->sku = $params['sku'];
            $objProduct->barcode = $params['barcode'];
            $objProduct->quantity = $params['quantity'];
            $objProduct->is_track = ($params['is_track'])?1:0;
            $objProduct->is_continue_selling = ($params['is_continue_selling'])?1:0;
            $objProduct->is_physical_product = ($params['is_physical_product'])?1:0;
            $objProduct->weight_type_id = isset($params['weight_type_id'])?$params['weight_type_id']:null;
            $objProduct->weight = $params['weight'];
            $objProduct->length_type_id = isset($params['length_type_id'])?$params['length_type_id']:null;
            $objProduct->length = $params['length'];
            $objProduct->width_type_id = isset($params['width_type_id'])?$params['width_type_id']:null;
            $objProduct->width = $params['width'];
            $objProduct->height_type_id = isset($params['height_type_id'])?$params['height_type_id']:null;
            $objProduct->height = $params['height'];
            $objProduct->hs_code = $params['hs_code'];
            $objProduct->min_order_limit = $params['min_order_limit'];
            $objProduct->max_order_limit = $params['max_order_limit'];
            $objProduct->is_cod_enabled = ($params['is_cod_enabled'])?1:0;
            $objProduct->is_size_chart_enabled = ($params['is_size_chart_enabled'])?1:0;
            $objProduct->is_special_product = ($params['is_special_product'])?1:0;
            $objProduct->special_product_status = ($params['special_product_status'])?1:0;
            $objProduct->special_price = $params['special_price'];
            $objProduct->expiry_date = $params['expiry_date'];
            $objProduct->seo_title = $params['seo_title'];
            $objProduct->seo_description = $params['seo_description'];
            $objProduct->is_gift_card = ($params['is_gift_card'])?1:0;
            $objProduct->user_id = $user_id;
            $objProduct->save();
            $product_id =  $objProduct->id;

            foreach($params['locations'] as $key => $objLocationsData)
            {
                $objInventoryStocks = new InventoryStock;
                $objInventoryStocks->product_id = $product_id;
                $objInventoryStocks->product_variant_option_id = null;
                $objInventoryStocks->address_id = $objLocationsData['id'];
                $objInventoryStocks->quantity = $objLocationsData['available'];
                $objInventoryStocks->available_quantity = $objLocationsData['available'];
                $objInventoryStocks->save();
            }
            $path = "public/$client_id";
            $this->checkFolder($path);
            $this->checkFolder($path.'/images');
            $this->checkFolder($path .'/images/'.$product_id);

            if(!empty($params['media']))
            {
                foreach($params['media'] as $key => $imageData)
                {
                    $refrence_id = mt_rand( 1000, 9999);
                    $imagename = time().$refrence_id.'.png';
                    $image = file_get_contents($imageData['imageurl']);
                    Storage::disk('public')->put("$client_id/images/$product_id/$imagename", $image, 'public');

                    $objProductMedia = new ProductMedium;
                    $objProductMedia->client_id = $client_id;
                    $objProductMedia->product_id = $product_id;
                    $objProductMedia->src = $imagename;
                    $objProductMedia->is_default = 0;
                    if($key == 0)
                    {
                        $objProductMedia->is_default = 1;
                    }
                    $objProductMedia->reorder = $key;
                    $objProductMedia->save();
                    
                }
            }

            foreach($params['collections'] as $key => $objCollection)
            {   
                $intFindOrder = 0;
                $objFindOrder = ProductCollection::where('collection_id', $objCollection['id'])->latest()->first();
                if(!empty($objFindOrder))
                {
                    $intFindOrder = $objFindOrder->order + 1;
                }
                $objProductCollection = new ProductCollection;
                $objProductCollection->product_id = $product_id;
                $objProductCollection->collection_id = $objCollection['id'];
                $objProductCollection->order = $intFindOrder;
                $objProductCollection->save();
            }

            $selected_tag = [];
            if(!empty($params['tags']))
            {
                foreach($params['tags'] as $key => $objTag)
                {
                    $objTagModel = Tag::find($objTag['id']);
                    if(empty($objTagModel)){
                        $objTagModel = new Tag;
                        $objTagModel->title = $objTag['title'];
                        $objTagModel->save();
                    }
                    array_push($selected_tag, $objTagModel->id);
                }
            }            

            if(!empty($selected_tag))
            {
                foreach($selected_tag as $key => $objTagId)
                {
                    $objProductTag = new ProductTag;
                    $objProductTag->product_id = $product_id;
                    $objProductTag->tag_id = $objTagId;
                    $objProductTag->save();
                }

            }
            if($params['is_product_variant'])
            {
                foreach($params['variantData'] as $variantKey => $objVariant){
                    $objVariantSelect = Variant::find($objVariant['selectOptions']);
                    foreach($objVariant['selectvalue'] as $key => $valVariantOption){
                        $objVariantOption = VariantOption::where('options', $valVariantOption)->first();
                        if(empty($objVariantOption))
                        {
                            $objVariantOption = new VariantOption;
                            $objVariantOption->variant_id = $objVariantSelect->id;
                            $objVariantOption->options = $valVariantOption;
                            $objVariantOption->save();
                        }
                    }
                        $objProductVariant = new ProductVariant;
                        $objProductVariant->product_id = $product_id;
                        $objProductVariant->variant_id = $objVariant['selectOptions'];
                        $objProductVariant->reorder = $variantKey;
                        $objProductVariant->save();
                }
                foreach($params['variants'] as $key => $objVariantCombinationVal){
                        
                    $objVariantCombination = new ProductVariantOption;
                    $objVariantCombination->product_id = $product_id;

                    foreach(explode(" / ",$objVariantCombinationVal['variant_name']) as $variantKey => $objVariantName){
                        $intVariantId = VariantOption::where('options', $objVariantName)->first()->id;
                        if($variantKey == 0)
                        {
                            $objVariantCombination->variant_option_1_id = $intVariantId;
                        }
                        else if($variantKey == 1)
                        {
                            $objVariantCombination->variant_option_2_id = $intVariantId;
                        }
                        else if($variantKey == 2)
                        {
                            $objVariantCombination->variant_option_3_id = $intVariantId;
                        }
                    }
                    $objVariantCombination->min_order_limit = in_array($objVariantCombinationVal['min_order_limit'],['',null])?0:$objVariantCombinationVal['min_order_limit'];
                    $objVariantCombination->max_order_limit = in_array($objVariantCombinationVal['max_order_limit'],['',null])?0:$objVariantCombinationVal['max_order_limit'];
                    $objVariantCombination->price = $objVariantCombinationVal['price'];
                    $objVariantCombination->cost_per_item = $objVariantCombinationVal['cost_per_item'];
                    $objVariantCombination->compare_at_price = $objVariantCombinationVal['compare_at_price'];
                    $objVariantCombination->is_product_charge = ($objVariantCombinationVal['is_product_charge'])?1:0;
                    $objVariantCombination->is_track = ($objVariantCombinationVal['is_track'])?1:0;
                    $objVariantCombination->is_continue_selling = ($objVariantCombinationVal['is_continue_selling'])?1:0;
                    $objVariantCombination->is_physical_product = ($objVariantCombinationVal['is_physical_product'])?1:0;
                    $objVariantCombination->sku = $objVariantCombinationVal['sku'];
                    $objVariantCombination->barcode = $objVariantCombinationVal['barcode'];
                    $objVariantCombination->hs_code = $objVariantCombinationVal['hs_code'];
                    $objVariantCombination->weight_type_id =  isset($objVariantCombinationVal['weight_type_id'])?$objVariantCombinationVal['weight_type_id']:null;
                    $objVariantCombination->weight = in_array($objVariantCombinationVal['weight'],['',null])?0:$objVariantCombinationVal['weight'];
                    $objVariantCombination->length_type_id =  isset($objVariantCombinationVal['length_type_id'])?$objVariantCombinationVal['length_type_id']:null;
                    $objVariantCombination->length = in_array($objVariantCombinationVal['length'],['',null])?0:$objVariantCombinationVal['length'];
                    $objVariantCombination->width_type_id =  isset($objVariantCombinationVal['width_type_id'])?$objVariantCombinationVal['width_type_id']:null;
                    $objVariantCombination->width = in_array($objVariantCombinationVal['width'],['',null])?0:$objVariantCombinationVal['width'];
                    $objVariantCombination->height_type_id =  isset($objVariantCombinationVal['height_type_id'])?$objVariantCombinationVal['height_type_id']:null;
                    $objVariantCombination->height = in_array($objVariantCombinationVal['height'],['',null])?0:$objVariantCombinationVal['height'];
                    $objVariantCombination->country_id =  isset($objVariantCombinationVal['country_id'])?$objVariantCombinationVal['country_id']:null;
                    $objVariantCombination->save();

                    $intVariant_id = $objVariantCombination->id;

                    if(!empty($objVariantCombinationVal['media']))
                    {
                        foreach($objVariantCombinationVal['media'] as $key => $imageData)
                        {
                            $refrence_id = mt_rand( 1000, 9999);
                            $imagename = time().'variant_'.$refrence_id.'.png';
                            $image = file_get_contents($imageData['imageurl']);
                            Storage::disk('public')->put("$client_id/images/$product_id/$imagename", $image, 'public');

                            $objProductVariantMedia = new VariantMedium;
                            $objProductVariantMedia->client_id = $client_id;
                            $objProductVariantMedia->product_id = $product_id;
                            $objProductVariantMedia->product_variant_id = $intVariant_id;
                            $objProductVariantMedia->src = $imagename;
                            $objProductVariantMedia->is_default = 0;
                            if($key == 0)
                            {
                                $objProductVariantMedia->is_default = 1;
                            }
                            $objProductVariantMedia->reorder = $key;
                            $objProductVariantMedia->save();
                            
                        }
                    }

                    foreach($objVariantCombinationVal['locations'] as $key => $objLocationsData)
                    {
                        $objInventoryStocks = new InventoryStock;
                        $objInventoryStocks->product_id = $product_id;
                        $objInventoryStocks->product_variant_option_id = $intVariant_id;
                        $objInventoryStocks->address_id = $objLocationsData['id'];
                        $objInventoryStocks->quantity = $objLocationsData['available'];
                        $objInventoryStocks->available_quantity = $objLocationsData['available'];
                        $objInventoryStocks->save();
                    }
                }
            }

            $url = route('admin.products.edit' , ['product' => $product_id]);
        return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.PRODUCT_ADDED_SUCCESSFULLY.code'),
                __('constants.messages.PRODUCT_ADDED_SUCCESSFULLY.msg'),
                 ['url'=>$url]
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

    public function edit(Product $product)
    {
        abort_if(Gate::denies('product_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $client_id = Config::get('client_id');
        $product_types = ProductType::all()->pluck('title', 'id');
        $vendors = Vendor::all()->pluck('name', 'id');
        $weight_types = Weightmanage::all()->pluck('title', 'id');
        $dimension_types = Dimension::all()->pluck('title', 'id');
        $countries = Country::all()->pluck('name', 'id');
        $product_types = ProductType::get();
        $vendors       = Vendor::get();
        $weightmanages = Weightmanage::get();
        $dimension = Dimension::get();
        $countries     = Country::get();
        $tags = Tag::where('status',1)->select('id','title')->get();
        $variants = Variant::where('status',1)->select('id','title')->get();
        $collections = Collection::select('title', 'id')->get();
        $selectedCollections = ProductCollection::where('product_id',$product->id)->pluck('collection_id')->toArray();
        $collections->map(function($d) use($selectedCollections) { 
             unset($d['media']);
             unset($d['src']);
             $d['checked'] = in_array($d['id'],$selectedCollections)? true :false; 
             return $d; 
         });

        $product_id = $product->id;

        $objAllLocations  =  Address::where('store_status',1)->get();
        $objTempLocationsProduct = [];
        foreach($objAllLocations as $locationkey => $objCurrentLocations){
             $objInventoryStocks = InventoryStock::where(['product_id' => $product_id, 'product_variant_option_id' => null, 'address_id' => $objCurrentLocations->id])->first();
            if(!empty($objInventoryStocks))    {
                    $objTempLocationsProduct[$locationkey]['id'] = $objInventoryStocks->address_id;
                    $objTempLocationsProduct[$locationkey]['name'] = $objInventoryStocks->address->location_name;
                    $objTempLocationsProduct[$locationkey]['available'] = $objInventoryStocks->available_quantity;
                    $objTempLocationsProduct[$locationkey]['incoming'] = $objInventoryStocks->incoming;
            }
            else {
                $objTempLocationsProduct[$locationkey]['id'] = $objCurrentLocations->id;
                $objTempLocationsProduct[$locationkey]['name'] = $objCurrentLocations->location_name;
                $objTempLocationsProduct[$locationkey]['available'] = 0;
                $objTempLocationsProduct[$locationkey]['incoming'] = 0;
            }
        }
        $product['locations'] = $objTempLocationsProduct;

        $selectedCollections = [];
        foreach($collections as $key => $objCollection)
        {
            if($objCollection->checked)
            {
                $selectedCollections[] = $objCollection->toArray();
            }
        }

        $objProductVariant = [];
        $objVariantSelectData = [];
        $locations = [];
        $objProductVariantCombination = [];
        $objVariantSelectDataIndex = 1;
        $objVariantData = [];

        if($product->is_product_variant)
        {
            $objProductVariant = ProductVariant::where('product_id',$product->id)->get();
            for($variantindex = 0; $variantindex < 3; $variantindex++)
            {
                $fieldName = 'variant_option_'.$objVariantSelectDataIndex++.'_id';
                $objVariantSelectData[$variantindex] = ProductVariantOption::where('product_id', $product_id)->DISTINCT($fieldName)->pluck($fieldName)->toArray();
            }
                
            foreach ($objProductVariant as $key =>  $objProductVariant) {
                if(isset($objVariantSelectData[$key])){
                    $objVariantOption = VariantOption::whereIn('id', $objVariantSelectData[$key])->get();
                    $objTempVariantOption = $objVariantOption->pluck('options');

                    if($objTempVariantOption->IsNotEmpty())
                    {
                        $objVariantData[$key]['selectOptions'] = $objVariantOption[0]->variant_id;
                        $objVariantData[$key]['selectvalue'] = $objTempVariantOption;
                        $objVariantData[$key]['setOptions'] = $variants->toArray();
                    }
                }
            }

            $objProductVariantCombination = ProductVariantOption::where('product_id', $product_id)->get();
            foreach ($objProductVariantCombination as $key =>  $objProductVariantVal) {

                $objTempLocations = [];
                foreach($objAllLocations as $locationkey => $objCurrentLocations){
                     $objInventoryStocks = InventoryStock::where(['product_id' => $product_id, 'product_variant_option_id' => $objProductVariantVal->id, 'address_id' => $objCurrentLocations->id])->first();
                    if(!empty($objInventoryStocks))    {
                            $objTempLocations[$locationkey]['id'] = $objInventoryStocks->address_id;
                            $objTempLocations[$locationkey]['name'] = $objInventoryStocks->address->location_name;
                            $objTempLocations[$locationkey]['available'] = $objInventoryStocks->available_quantity;
                            $objTempLocations[$locationkey]['incoming'] = $objInventoryStocks->incoming;
                    }
                    else {
                        $objTempLocations[$locationkey]['id'] = $objCurrentLocations->id;
                        $objTempLocations[$locationkey]['name'] = $objCurrentLocations->location_name;
                        $objTempLocations[$locationkey]['available'] = 0;
                        $objTempLocations[$locationkey]['incoming'] = 0;
                    }
                }
                $objProductVariantCombination[$key]['locations'] = $objTempLocations;


                $objProductVariantMedia = VariantMedium::where(['product_id'=>$product_id, 'product_variant_id' => $objProductVariantVal->id])->orderBy('reorder')->get();
                $objProductVariantCombination[$key]['media'] = [];
                if($objProductVariantMedia->IsNotEmpty())
                {
                    foreach($objProductVariantMedia as $mediaKey => $objProductVariantMediaData)
                    {
                        $objProductVariantMediaData['checked'] = false;
                        $objProductVariantMediaData['displaycheckbox'] = false;
                        $objProductVariantMediaData['imageurl'] = $objProductVariantMediaData->image_src[3];
                    }
                    $objProductVariantCombination[$key]['media'] = $objProductVariantMedia->toarray();
                }
            }
        }

        $productTags = ProductTag::where('product_id', $product->id)->get()->pluck('tag_id')->toArray();
        $select_tag = [];
        $selecttagkey =0;
        foreach($tags as $key => $valTag) {
            $tags[$key]['checked'] = false;
            if(in_array($valTag['id'], $productTags))
            {
                $select_tag[$selecttagkey]['id'] = $valTag['id'];
                $select_tag[$selecttagkey]['title'] = $valTag['title'];
                $select_tag[$selecttagkey++]['checked'] = true;
                $tags[$key]['checked'] = true;
            }
        }

        $objProductMedia = [];
        $objProductMedia = ProductMedium::where(['product_id' => $product_id])->orderBy('reorder')->get();
        if(!empty($objProductMedia))
        {
            foreach($objProductMedia as $key =>  $objSingleProductMedia)
            {
                $objProductMedia[$key]['checked'] = false;
                $objProductMedia[$key]['imageurl'] = $objSingleProductMedia->image_src[3];
                $objProductMedia[$key]['displaycheckbox'] = false;
            }
        }

        $list = [
            'variant_data' => $objVariantData,
            'product' => $product->toArray(),
            'product_media' => $objProductMedia->toArray(),
            'tags' => $tags->toArray(),
            'product_variant_option' => $objProductVariantCombination,
            'taggingSelected' => $select_tag,
            'taggingOptions' => $tags,
            'product_types' => $product_types,
            'variants' => $variants,
            'vendors' => $vendors,
            'weight_type' => $weight_types,
            'dimension_types' => $dimension_types,
            'countries' => $countries,
            'collections' => $collections->toArray(),
            'selectCollections' => $selectedCollections,
            'status' => Product::STATUS_SELECT,
            'is_product_charge' => Product::IS_PRODUCT_CHARGE_RADIO,
            'action'=> 'edit',
        ];

        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['link'=>route('admin.products.index'),'name' => trans('cruds.product.title')." ".trans('global.listing')],['name' => trans('global.edit')." ".trans('cruds.product.title_singular') ]];

        return view('admin.products.edit', compact('list','breadcrumbs'));
    }

    public function update(UpdateProductRequest $request)
    {  
        try{
            $client_id = Config::get('client_id');
            $params = collect($request->all());
            $user_id = Auth::user()->id;

             $objVendorId = null;
            if(isset($params['vendor']['name']))
            {
                $objVendor = Vendor::where('name', $params['vendor']['name'])->first();
                if(empty($objVendor))
                {
                    $objVendor = new Vendor;
                    $objVendor->name = $params['vendor']['name'];
                    $objVendor->status = 1;
                    $objVendor->save();
                }
                $objVendorId = $objVendor->id;
            }

            $objProductTypeId = null;
            if(isset($params['product_type']['title']))
            {
                $objProductType = ProductType::where('title', $params['product_type']['title'])->first();
                if(empty($objProductType))
                {
                    $objProductType = new ProductType;
                    $objProductType->title = $params['product_type']['title'];
                    $objProductType->status = 1;
                    $objProductType->save();
                }
                $objProductTypeId = $objProductType->id;
            }

            $objProduct = Product::find($params['id']);
            $objProduct->product_type_id = $objProductTypeId;
            $objProduct->vendor_id = $objVendorId;
            $objProduct->country_id = $params['country_id'];
            $objProduct->is_product_variant = ($params['is_product_variant'])?1:0;
            $objProduct->title = $params['title'];
            $objProduct->description = $params['description'];
            $objProduct->status = $params['status'];
            $objProduct->is_online = $params['is_online'];
            $schedule_date = null;
            if($params['is_online']){
                if($params['schedule_time'] != ''){
                     $schedule_date = date("Y-m-d H:i:s",strtotime($params['schedule_time']));
                }
            }
            $objProduct->schedule_time = $schedule_date;
            $objProduct->price = $params['price'];
            $objProduct->compare_at_price = $params['compare_at_price'];
            $objProduct->cost_per_item = $params['cost_per_item'];
            $objProduct->is_product_charge = ($params['is_product_charge'])?1:0;
            $objProduct->sku = $params['sku'];
            $objProduct->barcode = $params['barcode'];
            $objProduct->quantity = $params['quantity'];
            $objProduct->is_track = ($params['is_track'])?1:0;
            $objProduct->is_continue_selling = ($params['is_continue_selling'])?1:0;
            $objProduct->is_physical_product = ($params['is_physical_product'])?1:0;
            $objProduct->weight_type_id = isset($params['weight_type_id'])?$params['weight_type_id']:null;
            $objProduct->weight = $params['weight'];
            $objProduct->length_type_id = isset($params['length_type_id'])?$params['length_type_id']:null;
            $objProduct->length = $params['length'];
            $objProduct->width_type_id = isset($params['width_type_id'])?$params['width_type_id']:null;
            $objProduct->width = $params['width'];
            $objProduct->height_type_id = isset($params['height_type_id'])?$params['height_type_id']:null;
            $objProduct->height = $params['height'];
            $objProduct->hs_code = $params['hs_code'];
            $objProduct->min_order_limit = $params['min_order_limit'];
            $objProduct->max_order_limit = $params['max_order_limit'];
            $objProduct->is_cod_enabled = ($params['is_cod_enabled'])?1:0;
            $objProduct->is_size_chart_enabled = ($params['is_size_chart_enabled'])?1:0;
            $objProduct->is_special_product = ($params['is_special_product'])?1:0;
            $objProduct->special_product_status = ($params['special_product_status'])?1:0;
            $objProduct->special_price = $params['special_price'];
            $objProduct->expiry_date = $params['expiry_date'];
            $objProduct->seo_title = $params['seo_title'];
            $objProduct->seo_description = $params['seo_description'];
            $objProduct->is_gift_card = ($params['is_gift_card'])?1:0;
            $objProduct->user_id = $user_id;
            $objProduct->save();
            $product_id =  $objProduct->id;

             foreach($params['locations'] as $key => $objLocationsData)
            {
                $objInventoryStocks = InventoryStock::where(['product_id' => $product_id, 'product_variant_option_id' => null, 'address_id' => $objLocationsData['id']])->first();
                if(empty($objInventoryStocks))
                {
                    $objInventoryStocks = new InventoryStock;
                }
                $objInventoryStocks->product_id = $product_id;
                $objInventoryStocks->product_variant_option_id = null;
                $objInventoryStocks->address_id = $objLocationsData['id'];
                $objInventoryStocks->quantity = $objLocationsData['available'];
                $objInventoryStocks->available_quantity = $objLocationsData['available'];
                $objInventoryStocks->save();
            }


            $saveMedia = ProductMedium::where(['product_id' => $product_id])->pluck('id')->toArray();
            $newMedia = collect($params['media'])->pluck('id')->toArray();

            $removeMedia = array_diff($saveMedia, $newMedia);
            if(!empty($removeMedia))
            {
                $objRemoveMedia = ProductMedium::whereIn('id', $removeMedia)->get();
                foreach($objRemoveMedia as $key => $objSingleMedia)
                {
                    Storage::disk('public')->delete("$client_id/images/$product_id/$objSingleMedia->src");
                }
                ProductMedium::whereIn('id', $removeMedia)->delete();
            }

            if(!empty($params['media']))
            {
                foreach($params['media'] as $key => $imageData)
                {
                    $refrence_id = mt_rand( 1000, 9999);
                    $imagename = time().$refrence_id.'.png';
                    $objProductMedia = ProductMedium::where('id', $imageData['id'])->first();
                    if(empty($objProductMedia))
                    {
                        $objProductMedia = new ProductMedium;
                        $image = file_get_contents($imageData['imageurl']);
                        Storage::disk('public')->put("$client_id/images/$product_id/$imagename", $image, 'public');
                        $objProductMedia->src = $imagename;
                    }
                    $objProductMedia->client_id = $client_id;
                    $objProductMedia->product_id = $product_id;
                    $objProductMedia->is_default = 0;
                    if($key == 0)
                    {
                        $objProductMedia->is_default = 1;
                    }
                    $objProductMedia->reorder = $key;
                    $objProductMedia->save();
                    
                }
            }

            $newSelectedCollection = [];
            $saveSelectedCollection = [];
            $saveSelectedCollection = ProductCollection::where('product_id', $product_id)->pluck('collection_id')->toArray();

            if(!empty($params['collections']))
            {
                foreach($params['collections'] as $key => $objCollection)
                {
                   $objProductCollection =  ProductCollection::where(['product_id' =>  $product_id, 'collection_id' => $objCollection['id']])->first();
                   if(empty($objProductCollection))
                   {
                        $intFindOrder = 0;
                        $objFindOrder = ProductCollection::where('collection_id', $objCollection['id'])->latest()->first();
                        if(!empty($objFindOrder))
                        {
                            $intFindOrder = $objFindOrder->order + 1;
                        }
                        $objProductCollection = new ProductCollection;
                        $objProductCollection->product_id = $product_id;
                        $objProductCollection->collection_id = $objCollection['id'];
                        $objProductCollection->order = $intFindOrder;
                        $objProductCollection->save();

                   }
                    array_push($newSelectedCollection, $objCollection['id']);
                }

            }
            $removeCollection = array_diff($saveSelectedCollection, $newSelectedCollection);

            if(!empty($removeCollection))
            {
                ProductCollection::whereIn('collection_id', $removeCollection)->where('product_id', $product_id)->forceDelete();
            }

            $selected_tag = [];
            if(!empty($params['tags']))
            {
                foreach($params['tags'] as $key => $objTag)
                {
                    $objTagModel = Tag::find($objTag['id']);
                    if(empty($objTagModel)){
                        $objTagModel = new Tag;
                        $objTagModel->title = $objTag['title'];
                        $objTagModel->save();
                    }
                    array_push($selected_tag, $objTagModel->id);
                }
            }

            $saveSelectedTag = $objProduct->product_tags->pluck('tag_id')->toArray();
            $arrDiffSelectedTag = array_diff($selected_tag, $saveSelectedTag);
            $removeTags = array_diff($saveSelectedTag, $selected_tag);

            if(!empty($removeTags))
            {
                ProductTag::whereIn('tag_id', $removeTags)->where('product_id', $product_id)->forceDelete();
            }

           if(!empty($arrDiffSelectedTag))
            {
                foreach($arrDiffSelectedTag as $key => $objTagId)
                {
                    $objProductTag = new ProductTag;
                    $objProductTag->product_id = $product_id;
                    $objProductTag->tag_id = $objTagId;
                    $objProductTag->save();
                }
            }

            $saveProductSelectedVariant = $objProduct->product_variant_options->pluck('id')->toArray();
            $newProductSelectedVariant  = collect($params['variants'])->pluck('id')->toArray();

            $removeProductVariant = array_diff($saveProductSelectedVariant, $newProductSelectedVariant);

            if(!empty($removeProductVariant)){
               ProductVariantOption::whereIn('id', $removeProductVariant)->forceDelete();
            }

            if($params['is_product_variant'])
            {
                foreach($params['variantData'] as $variantKey => $objVariant){
                    $objVariantSelect = Variant::find($objVariant['selectOptions']);
                    foreach($objVariant['selectvalue'] as $key => $valVariantOption){
                        $objVariantOption = VariantOption::where('options', $valVariantOption)->first();
                        if(empty($objVariantOption))
                        {
                            $objVariantOption = new VariantOption;
                            $objVariantOption->variant_id = $objVariantSelect->id;
                            $objVariantOption->options = $valVariantOption;
                            $objVariantOption->save();
                        }
                    }
                $objProductVariant = ProductVariant::where(['product_id' => $product_id, 'variant_id' => $objVariant['selectOptions'] ])->first();
                if(empty($objProductVariant))
                {
                    $objProductVariant = new ProductVariant;
                }
                    $objProductVariant->product_id = $product_id;
                    $objProductVariant->variant_id = $objVariant['selectOptions'];
                    $objProductVariant->reorder = $variantKey;
                    $objProductVariant->save();
                }

                foreach($params['variants'] as $key => $objVariantCombinationVal){
                        
                    /*if($key==0)
                    {
                        dd('here');
                    }*/
                    $objVariantCombination = ProductVariantOption::find($objVariantCombinationVal['id']);
                    if(empty($objVariantCombination))
                    {
                        $objVariantCombination = new ProductVariantOption;
                    }

                    $objVariantCombination->product_id = $product_id;

                    foreach(explode(" / ",$objVariantCombinationVal['variant_name']) as $variantKey => $objVariantName){
                        $intVariantId = VariantOption::where('options', $objVariantName)->first()->id;
                        if($variantKey == 0)
                        {
                            $objVariantCombination->variant_option_1_id = $intVariantId;
                        }
                        else if($variantKey == 1)
                        {
                            $objVariantCombination->variant_option_2_id = $intVariantId;
                        }
                        else if($variantKey == 2)
                        {
                            $objVariantCombination->variant_option_3_id = $intVariantId;
                        }
                    }
                    $objVariantCombination->min_order_limit = in_array($objVariantCombinationVal['min_order_limit'],['',null])?0:$objVariantCombinationVal['min_order_limit'];
                    $objVariantCombination->max_order_limit = in_array($objVariantCombinationVal['max_order_limit'],['',null])?0:$objVariantCombinationVal['max_order_limit'];
                    $objVariantCombination->price = $objVariantCombinationVal['price'];
                    $objVariantCombination->cost_per_item = $objVariantCombinationVal['cost_per_item'];
                    $objVariantCombination->compare_at_price = $objVariantCombinationVal['compare_at_price'];
                    $objVariantCombination->is_product_charge = ($objVariantCombinationVal['is_product_charge'])?1:0;
                    $objVariantCombination->is_track = ($objVariantCombinationVal['is_track'])?1:0;
                    $objVariantCombination->is_continue_selling = ($objVariantCombinationVal['is_continue_selling'])?1:0;
                    $objVariantCombination->is_physical_product = ($objVariantCombinationVal['is_physical_product'])?1:0;
                    $objVariantCombination->sku = $objVariantCombinationVal['sku'];
                    $objVariantCombination->barcode = $objVariantCombinationVal['barcode'];
                    $objVariantCombination->hs_code = $objVariantCombinationVal['hs_code'];
                    $objVariantCombination->weight_type_id =  isset($objVariantCombinationVal['weight_type_id'])?$objVariantCombinationVal['weight_type_id']:null;
                    $objVariantCombination->weight = in_array($objVariantCombinationVal['weight'],['',null])?0:$objVariantCombinationVal['weight'];
                    $objVariantCombination->length_type_id =  isset($objVariantCombinationVal['length_type_id'])?$objVariantCombinationVal['length_type_id']:null;
                    $objVariantCombination->length = in_array($objVariantCombinationVal['length'],['',null])?0:$objVariantCombinationVal['length'];
                    $objVariantCombination->width_type_id =  isset($objVariantCombinationVal['width_type_id'])?$objVariantCombinationVal['width_type_id']:null;
                    $objVariantCombination->width = in_array($objVariantCombinationVal['width'],['',null])?0:$objVariantCombinationVal['width'];
                    $objVariantCombination->height_type_id =  isset($objVariantCombinationVal['height_type_id'])?$objVariantCombinationVal['height_type_id']:null;
                    $objVariantCombination->height = in_array($objVariantCombinationVal['height'],['',null])?0:$objVariantCombinationVal['height'];

                    $objVariantCombination->country_id =  isset($objVariantCombinationVal['country_id'])?$objVariantCombinationVal['country_id']:null;
                    $objVariantCombination->save();

                     $intVariant_id = $objVariantCombination->id;

                    $saveMedia = VariantMedium::where(['product_id' => $product_id, 'product_variant_id' => $objVariantCombination->id])->pluck('id')->toArray();
                    $newMedia = collect($objVariantCombinationVal['media'])->pluck('id')->toArray();

                    $removeMedia = array_diff($saveMedia, $newMedia);
                    if(!empty($removeMedia))
                    {
                        $objRemoveMedia = VariantMedium::whereIn('id', $removeMedia)->get();
                        foreach($objRemoveMedia as $key => $objSingleMedia)
                        {
                            Storage::disk('public')->delete("$client_id/images/$product_id/$objSingleMedia->src");
                        }
                        VariantMedium::whereIn('id', $removeMedia)->delete();
                    }


                    if(!empty($objVariantCombinationVal['media']))
                    {
                        foreach($objVariantCombinationVal['media'] as $key => $imageData)
                        {
                            $refrence_id = mt_rand( 1000, 9999);
                            $imagename = time().'variant_'.$refrence_id.'.png';
                            $objProductVariantMedia = VariantMedium::where('id', $imageData['id'])->first();
                            if(empty($objProductVariantMedia))
                            {
                                $objProductVariantMedia = new VariantMedium;
                                $image = file_get_contents($imageData['imageurl']);
                                Storage::disk('public')->put("$client_id/images/$product_id/$imagename", $image, 'public');
                                $objProductVariantMedia->src = $imagename;
                            }
                            $objProductVariantMedia->client_id = $client_id;
                            $objProductVariantMedia->product_id = $product_id;
                            $objProductVariantMedia->product_variant_id = $intVariant_id;
                            $objProductVariantMedia->is_default = 0;
                            if($key == 0)
                            {
                                $objProductVariantMedia->is_default = 1;
                            }
                            $objProductVariantMedia->reorder = $key;
                            $objProductVariantMedia->save();

                             
                        }
                    }

                    foreach($objVariantCombinationVal['locations'] as $key => $objLocationsData)
                    {
                        $objInventoryStocks = InventoryStock::where(['product_id'=> $product_id, 'product_variant_option_id' => $intVariant_id, 'address_id' => $objLocationsData['id']])->first();
                        if(empty($objInventoryStocks))
                        {
                            $objInventoryStocks = new InventoryStock;
                        }
                        $objInventoryStocks->product_id = $product_id;
                        $objInventoryStocks->product_variant_option_id = $intVariant_id;
                        $objInventoryStocks->address_id = $objLocationsData['id'];
                        $objInventoryStocks->quantity = $objLocationsData['available'];
                        $objInventoryStocks->available_quantity = $objLocationsData['available'];
                        $objInventoryStocks->save();
                    }

                } 
            } 
            $url = route('admin.products.index');
        return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.PRODUCT_UPDATE_SUCCESSFULLY.code'),
                __('constants.messages.PRODUCT_UPDATE_SUCCESSFULLY.msg'),
                 ['url' => $url]
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

    public function show(Product $product)
    {
        abort_if(Gate::denies('product_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $product->load('product_type', 'vendor', 'weight_type', 'country');

        return view('admin.products.show', compact('product'));
    }

    public function destroy(Request $request,$id=0)
    {
        try {
              abort_if(Gate::denies('product_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
              Product::where('id',$id)->delete();   
             
        } catch (\Exception $e) {
            return $this->errorResponse(
                __('constants.ERROR_STATUS'),
                __('constants.errors.SOMETHING_WRONG.code'),
                __('constants.errors.SOMETHING_WRONG.msg'),
                $e->getMessage()
            );
        }
        return $this->successResponse(
            __('constants.SUCCESS_STATUS'),
            __('constants.messages.PRODUCT_DELETE_SUCCESSFULLY.code'),
            __('constants.messages.PRODUCT_DELETE_SUCCESSFULLY.msg'),
        );
    }

    public function massDestroy(MassDestroyProductRequest $request)
    {
        try {
              Product::whereIn('id', request('ids'))->delete(); 
        } catch (\Exception $e) {
            return $this->errorResponse(
                __('constants.ERROR_STATUS'),
                __('constants.errors.SOMETHING_WRONG.code'),
                __('constants.errors.SOMETHING_WRONG.msg'),
                $e->getMessage()
            );
        }
        return $this->successResponse(
            __('constants.SUCCESS_STATUS'),
            __('constants.messages.PRODUCT_DELETE_SUCCESSFULLY.code'),
            __('constants.messages.PRODUCT_DELETE_SUCCESSFULLY.msg'),
        );
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('product_create') && Gate::denies('product_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Product();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }

    public function importProducts(Request $request){

        try{
             abort_if(Gate::denies('product_import_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
            $client_id = Config::get('client_id');
            $params = collect($request->all());
            ini_set('safe_mode', 'Off');
            ini_set('memory_limit', '-1');
            set_time_limit(3000);
            // if(!Storage::exists('public/csv/')){
            //     Storage::makeDirectory('public/csv/',0777,true);
            // }
            $userId = Auth::user()->id;

            $header = null;
            if ($request->input('media', false))
            {   
                if(($handle = fopen(storage_path('tmp/uploads/' . basename($request->input('media'))), "r")) !== false) 
                {
                    $flag = true;
                    $productType = '';
                    $productId = null;
                    $tempOptionId1 = null;
                    $tempOptionId2 = null;
                    $tempOptionId3 = null;
                    while (($row = fgetcsv($handle, 0, ',')) !== false){
                        if (!$header){
                            $header = $row;
                             if($params['importtype'] == "wordpress")
                                {
                                    if($row[1] != 'Type' && $row[0] != 'ID'){
                                        return $this->successResponse(
                                            __('constants.SUCCESS_STATUS'),
                                            __('constants.messages.HEADER_MISSING.code'),
                                            __('constants.messages.HEADER_MISSING.msg'),
                                        );
                                    }
                                }
                                else
                                {
                                    if($row[0] != 'Handle'){
                                        return $this->successResponse(
                                            __('constants.SUCCESS_STATUS'),
                                            __('constants.messages.HEADER_MISSING.code'),
                                            __('constants.messages.HEADER_MISSING.msg'),
                                        );
                                    }
                                }
                        }
                        else 
                        {
                            //for chunk data
                            // $productData[] = array_combine($header, $row);
                            $data = array_combine($header, $row);
                            try 
                            {   
                                if($params['importtype'] == "wordpress")
                                {
                                    $title = explode(" - ", trim($data["Name"]))[0];
                                    $slug =  Str::slug($title);
                                    $description = trim($data["Description"]);
                                    $saleStartPrice = ($data["Date sale price starts"] != '')?trim($data["Date sale price starts"]):null;
                                    $saleEndPrice = ($data["Date sale price ends"] != '')?trim($data["Date sale price ends"]):null;
                                    $taxClass = trim($data["Tax class"]);
                                    $inventoryStock = trim($data["Stock"]);
                                    $vendorname = isset($data["Vendor"])?trim($data["Vendor"]):null;
                                    $type = trim($data["Type"]);
                                    $sku = trim($data['SKU']);
                                    $isContinueSelling = isset($data['In stock?'])?trim($data['In stock?']):0;
                                    $published = trim($data["Published"]);
                                    $weight = $length = $width = $height =  0;
                                    $weighttype = $lengthtype = $widthtype = $heighttype = null;
                                    if(isset($data["Weight (kg)"]))
                                    {
                                        $weight = trim($data["Weight (kg)"]) != '' ? trim($data["Weight (kg)"]) : 0;
                                        $weighttype = "kg";
                                    }
                                    elseif(isset($data["Weight (g)"]))
                                    {
                                        $weight = trim($data["Weight (g)"]) != '' ? trim($data["Weight (g)"]) : 0;
                                        $weighttype = "gm";
                                    }
                                    elseif(isset($data["Weight (lbs)"]))
                                    {
                                        $weight = trim($data["Weight (lbs)"]) != '' ? trim($data["Weight (lbs)"]) : 0;
                                        $weighttype = "lbs";
                                    }
                                    elseif(isset($data["Weight (oz)"]))
                                    {
                                        $weight = trim($data["Weight (oz)"]) != '' ? trim($data["Weight (oz)"]) : 0;
                                        $weighttype = "oz";
                                    }

                                    if(isset($data["Length (m)"]))
                                    {
                                        $length = trim($data["Length (m)"]) != '' ? trim($data["Length (m)"]) : 0;
                                        $lengthtype = "m";
                                    }
                                    elseif(isset($data["Width (cm)"]))
                                    {
                                        $length = trim($data["Length (cm)"]) != '' ? trim($data["Length (cm)"]) : 0;
                                        $lengthtype = "cm";
                                    }
                                    elseif(isset($data["Length (mm)"]))
                                    {
                                        $length = trim($data["Length (mm)"]) != '' ? trim($data["Length (mm)"]) : 0;
                                        $lengthtype = "mm";
                                    }
                                    elseif(isset($data["Length (in)"]))
                                    {
                                        $length = trim($data["Length (in)"]) != '' ? trim($data["Length (in)"]) : 0;
                                        $lengthtype = "in";
                                    }
                                    elseif(isset($data["Length (yd)"]))
                                    {
                                        $length = trim($data["Length (yd)"]) != '' ? trim($data["Length (yd)"]) : 0;
                                        $lengthtype = "yd";
                                    }

                                    if(isset($data["Width (m)"]))
                                    {
                                        $width = trim($data["Width (m)"]) != '' ? trim($data["Width (m)"]) : 0;
                                        $widthtype = "m";
                                    }
                                    elseif(isset($data["Width (cm)"]))
                                    {
                                        $width = trim($data["Width (cm)"]) != '' ? trim($data["Width (cm)"]) : 0;
                                        $widthtype = "cm";
                                    }
                                    elseif(isset($data["Width (mm)"]))
                                    {
                                        $width = trim($data["Width (mm)"]) != '' ? trim($data["Width (mm)"]) : 0;
                                        $widthtype = "mm";
                                    }
                                    elseif(isset($data["Width (in)"]))
                                    {
                                        $width = trim($data["Width (in)"]) != '' ? trim($data["Width (in)"]) : 0;
                                        $widthtype = "in";
                                    }
                                    elseif(isset($data["Width (yd)"]))
                                    {
                                        $width = trim($data["Width (yd)"]) != '' ? trim($data["Width (yd)"]) : 0;
                                        $widthtype = "yd";
                                    }

                                    if(isset($data["Height (m)"]))
                                    {
                                        $height = trim($data["Height (m)"]) != '' ? trim($data["Height (m)"]) : 0;
                                        $heighttype = "m";
                                    }
                                    elseif(isset($data["Width (cm)"]))
                                    {
                                        $height = trim($data["Height (cm)"]) != '' ? trim($data["Height (cm)"]) : 0;
                                        $heighttype = "cm";
                                    }
                                    elseif(isset($data["Height (mm)"]))
                                    {
                                        $height = trim($data["Height (mm)"]) != '' ? trim($data["Height (mm)"]) : 0;
                                        $heighttype = "mm";
                                    }
                                    elseif(isset($data["Height (in)"]))
                                    {
                                        $height = trim($data["Height (in)"]) != '' ? trim($data["Height (in)"]) : 0;
                                        $heighttype = "in";
                                    }
                                    elseif(isset($data["Height (yd)"]))
                                    {
                                        $height = trim($data["Height (yd)"]) != '' ? trim($data["Height (yd)"]) : 0;
                                        $heighttype = "yd";
                                    }

                                    $customerReview = trim($data["Allow customer reviews?"]) != '' ? trim($data["Allow customer reviews?"]) : 0;
                                    $salePrice = (trim($data["Sale price"]) != '')?($data["Sale price"] > 0)?$data["Sale price"]:0:0;
                                    $regularPrice = (trim($data["Regular price"]) != '')?($data["Regular price"] > 0)?$data["Regular price"]:0:0;
                                    $collection = trim($data["Categories"]);
                                    $tags = trim($data["Tags"]);
                                    $images = trim($data["Images"]);
                                    $minOrderLimit = isset($data["Minimum Quantity"])?($data["Minimum Quantity"] > 0)?trim($data["Minimum Quantity"]):0:0;
                                    $maxOrderLimit = isset($data["Maximum Quantity"])?($data["Maximum Quantity"] > 0)?trim($data["Maximum Quantity"]):0:0;

                                    $option1 =  isset($data["Attribute 1 name"])?trim($data["Attribute 1 name"]):null;
                                    $option1value = isset($data["Attribute 1 value(s)"])?trim($data["Attribute 1 value(s)"]):null;
                                    $option2 = isset($data["Attribute 2 name"])?trim($data["Attribute 2 name"]):null;
                                    $option2value = isset($data["Attribute 2 value(s)"])?trim($data["Attribute 2 value(s)"]):null;
                                    $option3 = isset($data["Attribute 3 name"])?trim($data["Attribute 3 name"]):null;
                                    $option3value = isset($data["Attribute 3 value(s)"])?trim($data["Attribute 3 value(s)"]):null;

                                    $giftCard = isset($data["Gift Card"])?trim($data["Gift Card"]):0;
                                    $seoTitle = isset($data["SEO Title"])?trim($data["SEO Title"]):null;
                                    $seoDescription = isset($data["SEO Description"])?trim($data["SEO Description"]):null;
                                    $inventoryTrack = 1;

                                    $costPerItem = isset($data["Cost of Good"])?trim($data["Cost of Good"]):0.00;
                                    $barcode = isset($data["Barcode"])?trim($data["Barcode"]):null;
                                    $weightTypeId = $lengthTypeId = $widthTypeId = $heightTypeId = null;
                                    if($weight != null)
                                    {
                                        $weightTypeId = $this->getWeightTypeId($weighttype);
                                    }
                                    if($length != null)
                                    {
                                        $lengthTypeId = $this->getDimensionTypeId($lengthtype);
                                    }
                                    if($width != null)
                                    {
                                        $widthTypeId = $this->getDimensionTypeId($widthtype);
                                    }
                                    if($height != null)
                                    {
                                        $heightTypeId = $this->getDimensionTypeId($heighttype);
                                    }
                                    $price = $comparePrice = $regularPrice;
                                    if($salePrice != null)
                                    {
                                        $price = $salePrice;
                                    }
                                    if($saleStartPrice != null)
                                    {   
                                        $tempStartDate = explode(" ",$saleStartPrice);
                                        if(isset($tempStartDate[1]))
                                        {
                                            if($tempStartDate[1] == "0:00")
                                            {
                                                $saleStartPrice = $tempStartDate[0]." 00:00";
                                            }
                                        }
                                        if (Carbon::parse($saleStartPrice)->format('m/d/Y H:i') == $saleStartPrice) 
                                        {
                                            $saleStartPrice = Carbon::createFromFormat('m/d/Y H:i', $saleStartPrice)->format('Y-m-d H:i:s');
                                        }
                                    }
                                    if($saleEndPrice != null)
                                    {
                                        $tempEndDate = explode(" ",$saleEndPrice);
                                        if(isset($tempEndDate[1]))
                                        {
                                            if($tempEndDate[1] == "0:00")
                                            {
                                                $saleEndPrice = $tempEndDate[0]." 00:00";
                                            }
                                        }
                                        if (Carbon::parse($saleEndPrice)->format('m/d/Y H:i') == $saleEndPrice) {

                                            $saleEndPrice = Carbon::createFromFormat('m/d/Y H:i', $saleEndPrice)->format('Y-m-d H:i:s');
                                        }
                                        else if(!isset($tempEndDate[1]))
                                        {
                                            $saleEndPrice .= " 23:59:59";
                                        }

                                    }
                                    if($type == 'simple')
                                    {
                                        $objProduct = Product::where('slug', $slug)->first();
                                        if(empty($objProduct)){
                                            $objProduct = new Product;
                                        }

                                        $objProduct->product_type_id = null;
                                        $objProduct->vendor_id = null;
                                        $objProduct->country_id = Config::get('DEFAULT_COUNTRY'); //make it dynamic later
                                        $objProduct->is_product_variant = 0;
                                        $objProduct->title = $title;
                                        $objProduct->description = $description;
                                        $objProduct->status = ($published) ? 1 : 0;
                                        $objProduct->is_online = 1; // check it later
                                        $objProduct->price = $price;
                                        $objProduct->compare_at_price = $comparePrice;
                                        $objProduct->cost_per_item = $costPerItem;
                                        $objProduct->is_product_charge = 0; //default
                                        $objProduct->sku = $sku;
                                        $objProduct->barcode = $barcode;
                                        $objProduct->is_continue_selling = $isContinueSelling;
                                        $objProduct->min_order_limit = $minOrderLimit;
                                        $objProduct->max_order_limit = $maxOrderLimit;
                                        $objProduct->is_size_chart_enabled = 0;
                                        $objProduct->is_cod_enabled = 0;
                                        $objProduct->special_product_status = 0;
                                        $objProduct->is_special_product = 0;
                                        $objProduct->special_price = null;
                                        $objProduct->start_schedule_date = $saleStartPrice;
                                        $objProduct->schedule_time = $saleEndPrice;
                                        $objProduct->is_track = ($inventoryTrack != '') ? 1 : 0;
                                        $objProduct->weight_type_id = $weightTypeId;
                                        $objProduct->weight = $weight;
                                        $objProduct->length_type_id = $lengthTypeId;
                                        $objProduct->length = $length;
                                        $objProduct->width_type_id = $widthTypeId;
                                        $objProduct->width = $width;
                                        $objProduct->height_type_id = $heightTypeId;
                                        $objProduct->height = $height;
                                        $objProduct->seo_title = $seoTitle;
                                        $objProduct->seo_description = $seoDescription;
                                        $objProduct->is_gift_card = $giftCard;
                                        $objProduct->user_id = $userId;
                                        $objProduct->save();
                                        $productId = $objProduct->id;
                                        Product::where('id', $productId)->update(['slug' => $slug]);
                                        $this->manageCollection($collection, $productId);
                                        $this->manageQunatity($userId, $productId, $inventoryStock);
                                        $this->insertTags($tags, $productId);
                                        $objImageDataLink = explode(", ", $images);
                                        foreach ($objImageDataLink as $key => $strSingleLink) {
                                            $firstImage = 0;
                                            if($key == 0)
                                            {
                                                $firstImage == 1;
                                            }
                                            $this->uploadImage($strSingleLink, $productId, $firstImage);
                                        }

                                    }
                                    else if($type == 'variable')
                                    {

                                        $objProduct = Product::where('slug', $slug)->first();
                                        if(empty($objProduct)){
                                            $objProduct = new Product;
                                        }
                                        
                                        $objProduct->product_type_id = null;
                                        $objProduct->vendor_id = null;
                                        $objProduct->country_id = Config::get('DEFAULT_COUNTRY');
                                        $objProduct->is_product_variant = 1;
                                        $objProduct->title = $title;
                                        $objProduct->description = $description;
                                        $objProduct->status = ($published) ? 1 : 0;
                                        $objProduct->is_online = 1; // check it later
                                        $objProduct->price = $price;
                                        $objProduct->compare_at_price = $comparePrice;
                                        $objProduct->cost_per_item = $costPerItem;
                                        $objProduct->is_product_charge = 0; //default
                                        $objProduct->sku = $sku;
                                        $objProduct->barcode = $barcode;
                                        $objProduct->is_continue_selling = $isContinueSelling;
                                        $objProduct->min_order_limit = $minOrderLimit;
                                        $objProduct->max_order_limit = $maxOrderLimit;
                                        $objProduct->is_size_chart_enabled = 0;
                                        $objProduct->is_cod_enabled = 0;
                                        $objProduct->special_product_status = 0;
                                        $objProduct->is_special_product = 0;
                                        $objProduct->special_price = null;
                                        $objProduct->start_schedule_date = $saleStartPrice;
                                        $objProduct->schedule_time = $saleEndPrice;
                                        $objProduct->is_track = ($inventoryTrack != '') ? 1 : 0;
                                        $objProduct->weight_type_id = $weightTypeId;
                                        $objProduct->weight = $weight;
                                        $objProduct->length_type_id = $lengthTypeId;
                                        $objProduct->length = $length;
                                        $objProduct->width_type_id = $widthTypeId;
                                        $objProduct->width = $width;
                                        $objProduct->height_type_id = $heightTypeId;
                                        $objProduct->height = $height;
                                        $objProduct->seo_title = $seoTitle;
                                        $objProduct->seo_description = $seoDescription;
                                        $objProduct->is_gift_card = $giftCard;
                                        $objProduct->user_id = $userId;
                                        $objProduct->save();
                                        $productId = $objProduct->id;
                                        Product::where('id', $productId)->update(['slug' => $slug]);
                                        $this->manageCollection($collection, $productId);
                                        $this->manageQunatity($userId, $productId, $inventoryStock);
                                        $this->insertTags($tags, $productId);
                                        $objImageDataLink = explode(", ", $images);
                                        foreach ($objImageDataLink as $key => $strSingleLink) {
                                            $firstImage = 0;
                                            if($key == 0)
                                            {
                                                $firstImage == 1;
                                            }
                                            $this->uploadImage($strSingleLink, $productId, $firstImage);
                                        }

                                    }
                                    else if($type == 'variation' && $taxClass == 'parent')
                                    {
                                         
                                         $objProduct = Product::where('slug', $slug)->first();

                                         if(!empty($objProduct))
                                         {
                                            $parentProductId = $objProduct->id;
                                            $optionValue1Id =  $this->getVariantOptionID($option1, $option1value, $parentProductId);
                                            $optionValue2Id =  $this->getVariantOptionID($option2, $option2value, $parentProductId);
                                            $optionValue3Id =  $this->getVariantOptionID($option3, $option3value, $parentProductId);
                                            $tempOptionId1 = $option1;
                                            $tempOptionId2 = $option2;
                                            $tempOptionId3 = $option3;

                                            $objVariantProduct = ProductVariantOption::where('product_id', $parentProductId)
                                            ->where('variant_option_1_id', $optionValue1Id)
                                            ->where('variant_option_2_id', $optionValue2Id)
                                            ->where('variant_option_3_id', $optionValue3Id)
                                            ->first();
                                            if(empty($objVariantProduct)){
                                                $objVariantProduct = new ProductVariantOption;
                                            }
                                            $objVariantProduct->product_id = $parentProductId;
                                            $objVariantProduct->variant_option_1_id = $optionValue1Id;
                                            $objVariantProduct->variant_option_2_id = $optionValue2Id;
                                            $objVariantProduct->variant_option_3_id = $optionValue3Id;
                                            $objVariantProduct->country_id = Config::get('DEFAULT_COUNTRY'); //make it dynamic later
                                            $objVariantProduct->price = $price;
                                            $objVariantProduct->cost_per_item = $costPerItem;
                                            $objVariantProduct->is_continue_selling = $isContinueSelling;
                                            $objVariantProduct->is_product_charge = 0; //default
                                            $objVariantProduct->sku = $sku;
                                            $objVariantProduct->barcode = $barcode;
                                            $objVariantProduct->is_track = ($inventoryTrack != '') ? 1 : 0;
                                            $objVariantProduct->weight_type_id = $weightTypeId;
                                            $objVariantProduct->weight = $weight;
                                            $objVariantProduct->length_type_id = $lengthTypeId;
                                            $objVariantProduct->length = $length;
                                            $objVariantProduct->width_type_id = $widthTypeId;
                                            $objVariantProduct->width = $width;
                                            $objVariantProduct->height_type_id = $heightTypeId;
                                            $objVariantProduct->height = $height;
                                            $objVariantProduct->save();
                                            $variantId = $objVariantProduct->id;
                                            $this->manageQunatity($userId, $parentProductId, $inventoryStock, $variantId);
                                            $this->insertTags($tags, $parentProductId);
                                            $objImageDataLink = explode(", ", $images);
                                            foreach ($objImageDataLink as $key => $strSingleVariantLink) {
                                                $isDefault = 0;
                                                if($key == 0)
                                                {
                                                    $isDefault = 1;
                                                }
                                                $this->uploadVariantImage($strSingleVariantLink, $parentProductId, $variantId, $isDefault);
                                            }
                                        }
                                    }

                                }
                                else
                                {    
                                    $slug = trim($data["Handle"]);
                                    $title = trim($data["Title"]);
                                    $description = trim($data["Body (HTML)"]);
                                    $vendorname = trim($data["Vendor"]);
                                    $type = trim($data["Type"]);
                                    $tags = trim($data["Tags"]);
                                    $published = trim($data["Published"]);
                                    $option1 =  trim($data["Option1 Name"]);
                                    $option1value = trim($data["Option1 Value"]);
                                    $option2 = trim($data["Option2 Name"]);
                                    $option2value = trim($data["Option2 Value"]);
                                    $option3 = trim($data["Option3 Name"]);
                                    $option3value = trim($data["Option3 Value"]);
                                    $variantsku = trim($data["Variant SKU"]);
                                    $weight = trim($data["Variant Grams"]) != '' ? trim($data["Variant Grams"]) : 0.00;
                                    $inventoryTrack = trim($data["Variant Inventory Tracker"]);
                                    $inventoryPolicy = trim($data["Variant Inventory Policy"]);
                                    $fulfillmentService = trim($data["Variant Fulfillment Service"]);
                                    $variantPrice = trim($data["Variant Price"]) != '' ? trim($data["Variant Price"]) : 0.00;
                                    $variantComparePrice = trim($data["Variant Compare At Price"]) != '' ? trim($data["Variant Compare At Price"]) : 0.00;
                                    $requiredShipping = trim($data["Variant Requires Shipping"]);
                                    $variantTaxable = trim($data["Variant Taxable"]);
                                    $variantBarcode = trim($data["Variant Barcode"]);
                                    $src = trim($data["Image Src"]);
                                    $srcPosition = isset($data["Image Position"])?trim($data["Image Position"]):'';
                                    $srcAtlText = trim($data["Image Alt Text"]);
                                    $giftCard = trim($data["Gift Card"]);
                                    $seoTitle = trim($data["SEO Title"]);
                                    $seoDescription = trim($data["SEO Description"]);
                                    $variantImage = trim($data["Variant Image"]);
                                    $variantWeightUnit = trim($data["Variant Weight Unit"]);
                                    $variantTaxCode = isset($data["Variant Tax Code"])?trim($data["Variant Tax Code"]):'';
                                    $costPerItem = isset($data["Cost per item"])?trim($data["Cost per item"]) != '' ? trim($data["Cost per item"]) : 0.00:0.00;
                                    $status = isset($data["Variant Tax Code"])?trim($data["Status"]):'';
                                    $quantity = isset($data["Variant Inventory Qty"]) ? $data["Variant Inventory Qty"] : 0;

                                    $vendorId = null;
                                    $productTypeId = null;
                                    $weightTypeId = null;
                                    $tagsId = [];

                                    //product is variant product main
                                    if($title != '' && ($option1 != 'Title' && $option1 != '')){
                                        $objProduct = Product::where('slug', $slug)->first();
                                        if(empty($objProduct)){
                                            $objProduct = new Product;
                                        }
                                        $vendorId = $this->getVendorId($vendorname);
                                        $productTypeId = $this->getProductTypeId($type);
                                        $weightTypeId = $this->getWeightTypeId($variantWeightUnit);

                                        $objProduct->product_type_id = $productTypeId;
                                        $objProduct->vendor_id = $vendorId;
                                        $objProduct->weight_type_id =   $weightTypeId;
                                        $objProduct->country_id = Config::get('DEFAULT_COUNTRY');
                                        $objProduct->is_product_variant = 1;
                                        $objProduct->title = $title;
                                        $objProduct->description = $description;
                                        $objProduct->status = $status == "active" ? 1 : 0;
                                        $objProduct->is_online = 1; // check it later
                                        $objProduct->price = $variantPrice;
                                        $objProduct->compare_at_price = $variantComparePrice;
                                        $objProduct->cost_per_item = $costPerItem;
                                        $objProduct->is_product_charge = 0; //default
                                        $objProduct->sku = $variantsku;
                                        $objProduct->barcode = $variantBarcode;
                                        $objProduct->is_continue_selling = 1;
                                        // $objProduct->quantity = $params['quantity'];
                                        // $objProduct->is_physical_product = 0;
                                        // $objProduct->hs_code = $params['hs_code'];
                                        // $objProduct->min_order_limit = 1;
                                        // $objProduct->max_order_limit = 1;
                                        // $objProduct->is_size_chart_enabled = 0;
                                        // $objProduct->is_cod_enabled = 0;
                                        // $objProduct->special_product_status = 0;
                                        // $objProduct->is_special_product = 0;
                                        // $objProduct->special_price = null;
                                        // $objProduct->expiry_date = null;
                                        $objProduct->is_track = ($inventoryTrack != '') ? 1 : 0;
                                        $objProduct->weight = $weight;
                                        $objProduct->seo_title = $seoTitle;
                                        $objProduct->seo_description = $seoDescription;
                                        $objProduct->is_gift_card = ($giftCard == 'FALSE') ? 0 : 1;
                                        $objProduct->user_id = $userId;
                                        $objProduct->save();
                                        $productId = $objProduct->id;
                                        Product::where('id', $productId)->update(['slug' => $slug]);

                                        //sub variant product data
                                        $optionValue1Id =  $this->getVariantOptionID($option1, $option1value, $productId);
                                        $optionValue2Id =  $this->getVariantOptionID($option2, $option2value, $productId);
                                        $optionValue3Id =  $this->getVariantOptionID($option3, $option3value, $productId);
                                        $tempOptionId1 = $option1;
                                        $tempOptionId2 = $option2;
                                        $tempOptionId3 = $option3;

                                        $objVariantProduct = ProductVariantOption::where('product_id', $productId)
                                        ->where('variant_option_1_id', $optionValue1Id)
                                        ->where('variant_option_2_id', $optionValue2Id)
                                        ->where('variant_option_3_id', $optionValue3Id)
                                        ->first();
                                        if(empty($objVariantProduct)){
                                            $objVariantProduct = new ProductVariantOption;
                                        }
                                        $objVariantProduct->product_id = $productId;
                                        $objVariantProduct->variant_option_1_id = $optionValue1Id;
                                        $objVariantProduct->variant_option_2_id = $optionValue2Id;
                                        $objVariantProduct->variant_option_3_id = $optionValue3Id;
                                        $objVariantProduct->weight_type_id =   $weightTypeId;
                                        $objVariantProduct->country_id = Config::get('DEFAULT_COUNTRY'); //make it dynamic later
                                        $objVariantProduct->price = $variantPrice;
                                        $objVariantProduct->compare_at_price = $variantComparePrice;
                                        $objVariantProduct->cost_per_item = $costPerItem;
                                        $objVariantProduct->is_continue_selling = 1;
                                        $objVariantProduct->is_product_charge = 0; //default
                                        $objVariantProduct->sku = $variantsku;
                                        $objVariantProduct->barcode = $variantBarcode;
                                        $objVariantProduct->is_track = ($inventoryTrack != '') ? 1 : 0;
                                        $objVariantProduct->weight = $weight;
                                        $objVariantProduct->save();
                                        $variantId = $objVariantProduct->id;
                                        $this->manageQunatity($userId, $productId, $quantity, $variantId);
                                        $this->insertTags($tags, $productId);
                                        $objImageDataLink = explode(", ", $src);
                                        foreach ($objImageDataLink as $key => $strSingleLink) {
                                            $this->uploadImage($strSingleLink, $productId, 1);
                                        }
                                        $objImageDataLink = explode(", ", $variantImage);
                                        foreach ($objImageDataLink as $key => $strSingleVariantLink) {
                                            $isDefault = 0;
                                            if($key == 0)
                                            {
                                                $isDefault = 1;
                                            }
                                            $this->uploadVariantImage($strSingleVariantLink, $productId, $variantId, $isDefault);
                                        }
                                    } else if($title == '' && ($option1 != 'Title' && $option1value != '')){
                                        //sub variant product data
                                        $optionValue1Id = $this->getVariantOptionID($tempOptionId1, $option1value, $productId);
                                        $optionValue2Id = $this->getVariantOptionID($tempOptionId2, $option2value, $productId);
                                        $optionValue3Id = $this->getVariantOptionID($tempOptionId3, $option3value, $productId);

                                        $objVariantProduct = ProductVariantOption::where('product_id', $productId)
                                        ->where('variant_option_1_id', $optionValue1Id)
                                        ->where('variant_option_2_id', $optionValue2Id)
                                        ->where('variant_option_3_id', $optionValue3Id)
                                        ->first();

                                        if(empty($objVariantProduct)){
                                            $objVariantProduct = new ProductVariantOption;
                                        }
                                        $objVariantProduct->product_id = $productId;
                                        $objVariantProduct->variant_option_1_id = $optionValue1Id;
                                        $objVariantProduct->variant_option_2_id = $optionValue2Id;
                                        $objVariantProduct->variant_option_3_id = $optionValue3Id;
                                        $objVariantProduct->weight_type_id =   $weightTypeId;
                                        $objVariantProduct->country_id = Config::get('DEFAULT_COUNTRY');
                                        $objVariantProduct->price = $variantPrice;
                                        $objVariantProduct->compare_at_price = $variantComparePrice;
                                        $objVariantProduct->cost_per_item = $costPerItem;
                                        $objVariantProduct->is_continue_selling = 1;
                                        $objVariantProduct->is_product_charge = 0; //default
                                        $objVariantProduct->sku = $variantsku;
                                        $objVariantProduct->barcode = $variantBarcode;
                                        $objVariantProduct->is_track = ($inventoryTrack != '') ? 1 : 0;
                                        $objVariantProduct->weight = $weight;
                                        $objVariantProduct->save();
                                        $variantId = $objVariantProduct->id;
                                        $this->manageQunatity($userId, $productId, $quantity, $variantId);
                                        $objImageDataLink = explode(", ", $src);
                                        foreach ($objImageDataLink as $intkey => $strSingleLink) {
                                            $this->uploadImage($strSingleLink, $productId, $intkey);
                                        }
                                        $objImageDataLink = explode(", ", $variantImage);
                                        foreach ($objImageDataLink as $key => $strSingleVariantLink) {
                                            $isDefault = 0;
                                            if($key == 0)
                                            {
                                                $isDefault = 1;
                                            }
                                            $this->uploadVariantImage($strSingleVariantLink, $productId, $variantId, $isDefault);
                                        }
                                       /* $this->uploadImage($src, $productId, 0);
                                        $this->uploadVariantImage($variantImage, $productId, $variantId);*/
                                    } else if($option1 == 'Title') {
                                        $objProduct = Product::where('slug', $slug)->first();
                                        if(empty($objProduct))
                                        {
                                            $objProduct = new Product;
                                        }
                                        
                                        $vendorId = $this->getVendorId($vendorname);
                                        $productTypeId = $this->getProductTypeId($type);
                                        $weightTypeId = $this->getWeightTypeId($variantWeightUnit);
                                       
                                        $objProduct->product_type_id = $productTypeId;
                                        $objProduct->vendor_id = $vendorId;
                                        $objProduct->weight_type_id =   $weightTypeId;
                                        $objProduct->country_id = Config::get('DEFAULT_COUNTRY');
                                        $objProduct->is_product_variant = 0;
                                        $objProduct->title = $title;
                                        $objProduct->description = $description;
                                        $objProduct->status = $status == "active" ? 1 : 0;
                                        $objProduct->is_online = 1; // check it later
                                        $objProduct->price = $variantPrice;
                                        $objProduct->compare_at_price = $variantComparePrice;
                                        $objProduct->cost_per_item = $costPerItem;
                                        $objProduct->is_product_charge = 0; //default
                                        $objProduct->sku = $variantsku;
                                        $objProduct->barcode = $variantBarcode;
                                        $objProduct->is_continue_selling = 1;
                                        $objProduct->is_track = ($inventoryTrack != '') ? 1 : 0;
                                        $objProduct->weight = $weight;
                                        $objProduct->seo_title = $seoTitle;
                                        $objProduct->seo_description = $seoDescription;
                                        $objProduct->is_gift_card = ($giftCard == 'FALSE') ? 0 : 1;
                                        $objProduct->user_id = $userId;
                                        $objProduct->save();
                                        $productId = $objProduct->id;
                                        Product::where('id', $productId)->update(['slug' => $slug]);

                                        $this->manageQunatity($userId, $productId, $quantity);
                                        $this->insertTags($tags, $productId);
                                         $objImageDataLink = explode(", ", $src);
                                        foreach ($objImageDataLink as $intkey => $strSingleLink) {
                                            $this->uploadImage($strSingleLink, $productId, 1);
                                        }

                                        // $this->uploadImage($src, $productId, 1);
                                    } else {
                                        //only upload images
                                        $this->uploadImage($src, $productId, 0);
                                    }
                                }

                            } catch(Exception $e) {
                                return $this->errorResponse(
                                    __('constants.ERROR_STATUS'),
                                    __('constants.errors.SOMETHING_WRONG.code'),
                                    __('constants.errors.SOMETHING_WRONG.msg'),
                                    $e->getMessage()
                                );
                            }
                        }
                    }
                    fclose($handle);
                    
                    return $this->successResponse(
                        __('constants.SUCCESS_STATUS'),
                        __('constants.messages.PRODUCT_IMPORTED_SUCCESSFULLY.code'),
                        __('constants.messages.PRODUCT_IMPORTED_SUCCESSFULLY.msg'),
                    );
                }
            } else {
                return $this->successResponse(
                    __('constants.SUCCESS_STATUS'),
                    __('constants.messages.PLEASE_IMPORT_FILE.code'),
                    __('constants.messages.PLEASE_IMPORT_FILE.msg'),
                );
            }

        } catch (Exception $e) {
            return $this->errorResponse(
                __('constants.ERROR_STATUS'),
                __('constants.errors.SOMETHING_WRONG.code'),
                __('constants.errors.SOMETHING_WRONG.msg'),
                $e->getMessage()
            );
        }
    }

    public function manageCollection($collectionname, $productId){
        if($collectionname != ''){
            $collectionname = explode(", ",$collectionname)[0];
            $objCollection = Collection::where('title', $collectionname)->first();
            if(empty($objCollection)){
                $objCollection = new Collection;
                $objCollection->title = $collectionname;
                $objCollection->save();
            }
            $collectionId = $objCollection->id;

            $objProductCollection = ProductCollection::where('product_id', $productId)->where('collection_id', $collectionId)->first();
            if(empty($objProductCollection)){
                $objProductCollection = new ProductCollection;
                $objProductCollection->product_id = $productId;
                $objProductCollection->collection_id = $collectionId;
                $objProductCollection->save();
            }
        }
    }
   

    public function manageQunatity($userId, $productId, $quantity, $variantId = null){
        if($quantity > 0)
        {
            $address = Address::where('user_id', $userId)->where('is_default', 1)->first();
            if($address){
                $objInventoryStocks = InventoryStock::where('product_id', $productId)->where('product_variant_option_id', $variantId)->first();
                if(empty($objInventoryStocks)){
                    $objInventoryStocks = new InventoryStock;
                }
                $objInventoryStocks->product_id = $productId;
                $objInventoryStocks->product_variant_option_id = $variantId;
                $objInventoryStocks->address_id = $address->id;
                $objInventoryStocks->quantity = $quantity;
                $objInventoryStocks->available_quantity = $quantity;
                $objInventoryStocks->save();
            }
        }
    }   

    public function getVariantOptionID($option, $value, $productId){
        //check variant exist or not. If not then insert new variant
        $optionValueId = null;
        if($option != ''){
            $Variant = Variant::where('title', $option)->first();
            if(empty($Variant)) {
                $Variant = new Variant;
                $Variant->title = $option;
                $Variant->save();
            }
            $optionId = $Variant->id;

            if($value != ''){
                $VariantOption = VariantOption::where('variant_id', $optionId)->where('options', $value)->first();
                if(empty($VariantOption)) {
                    $VariantOption = new VariantOption;
                    $VariantOption->variant_id = $optionId;
                    $VariantOption->options = $value;
                    $VariantOption->save();
                }
                $optionValueId = $VariantOption->id;

                $objProductVariant = ProductVariant::where('product_id', $productId)->where('variant_id', $optionValueId)->first();
                if(empty($objProductVariant)){
                    $objProductVariant = new ProductVariant;
                    $objProductVariant->product_id = $productId;
                    $objProductVariant->variant_id = $optionValueId;
                    $objProductVariant->save();
                }
            }
        }

        return $optionValueId;
    }

    public function getVendorId($vendorname){
        $vendorId = null;
        if($vendorname != ''){
            $Vendor = Vendor::where('name', $vendorname)->first();
            if(empty($Vendor)) {
                $Vendor = new Vendor;
                $Vendor->name = $vendorname;
                $Vendor->save();
            }
            $vendorId = $Vendor->id;
        }

        return $vendorId;
    }

    public function getProductTypeId($type){
        $productTypeId = null;
        if($type != '')
        {
            $ProductType = ProductType::where('title', $type)->first();
            if(empty($ProductType)) {
                $ProductType = new ProductType;
                $ProductType->title = $type;
                $ProductType->save();
            }
            $productTypeId = $ProductType->id;
        }
        return $productTypeId;
    }

    public function getWeightTypeId($variantWeightUnit){
        $weightTypeId = null;
        if($variantWeightUnit != '')
        {
            $Weightmanage = Weightmanage::where('short_code', $variantWeightUnit)->first();
            if(!empty($Weightmanage)) {
                $weightTypeId = $Weightmanage->id;
            }
        }
        return $weightTypeId;
    }

    public function getDimensionTypeId($variantDimesionUnit){
        $dimensionTypeId = null;
        if($variantDimesionUnit != '')
        {
            $dimensionManage = Dimension::where('short_code', $variantDimesionUnit)->first();
            if(!empty($dimensionManage)) {
                $dimensionTypeId = $dimensionManage->id;
            }
        }
        return $dimensionTypeId;
    }

    public function insertTags($tags, $productId){
        if($tags != ''){
            $tags = explode(',', $tags);
            foreach($tags as $key=> $tagname){
                $Tag = Tag::where('title', trim($tagname))->first();
                if(empty($Tag)){
                    $Tag = new Tag;
                    $Tag->title = trim($tagname);
                    $Tag->save();
                }
                $tagId = $Tag->id;

                $ProductTag = ProductTag::where('product_id', $productId)->where('tag_id', $tagId)->first();
                if(empty($ProductTag)){
                    $ProductTag = new ProductTag;
                    $ProductTag->product_id = $productId;
                    $ProductTag->tag_id = $tagId;
                    $ProductTag->save();
                }
            }
        }
    }

    public function uploadImage($src, $productId, $isDefault){
        if($src != ''){
            $objProductMedia = ProductMedium::where('product_id', $productId)->where('cdn_url', $src)->first();
            if(empty($objProductMedia)){
                $objProductMedia = new ProductMedium;
                $objProductMedia->client_id = Config::get('client_id');
                $objProductMedia->product_id = $productId;
                $objProductMedia->is_default = $isDefault;
                $objProductMedia->reorder = 0;
                $objProductMedia->source = 1;
                $objProductMedia->cdn_url = $src;
                $objProductMedia->save();
            }
        }

        // if($src != ''){
        //     $image = file_get_contents($src);
        //     $name = substr($src, strrpos($src, '/') + 1);
        //     $name = substr($name, 0, strpos($name, '?'));

        //     $exists = Storage::disk('public')->exists("images/$productId/$name");
        //     if(!$exists){
        //         Storage::disk('public')->put("images/$productId/$name", $image, 'public');
        //         $objProductMedia = new ProductMedium;
        //         $objProductMedia->product_id = $productId;
        //         $objProductMedia->src = $name;
        //         $objProductMedia->is_default = $isDefault;
        //         $objProductMedia->reorder = 0;
        //         $objProductMedia->save();
        //     }
        // }
    }

    public function uploadVariantImage($src, $productId, $variantId, $isDefault){
        if($src != ''){
            $objProductVariantMedia = VariantMedium::where('product_id', $productId)->where('product_variant_id', $variantId)->where('cdn_url', $src)->first();
            if(empty($objProductVariantMedia)){
                $objProductVariantMedia = new VariantMedium;
                $objProductVariantMedia->client_id = Config::get('client_id');
                $objProductVariantMedia->product_id = $productId;
                $objProductVariantMedia->product_variant_id = $variantId;
                $objProductVariantMedia->is_default = $isDefault;
                $objProductVariantMedia->reorder = 0;
                $objProductVariantMedia->source = 1;
                $objProductVariantMedia->cdn_url = $src;
                $objProductVariantMedia->save();
            }
        }

        // if($src != ''){
        //     $refrence_id = mt_rand( 1000, 9999);
        //     $imagename = time().'variant_'.$refrence_id.'.png';
        //     $image = file_get_contents($src);
        //     $exists = Storage::disk('public')->exists("images/$productId/$imagename");

        //     if(!$exists){
        //         Storage::disk('public')->put("images/$productId/$imagename", $image, 'public');
        //         $objProductVariantMedia = new VariantMedium;
        //         $objProductVariantMedia->product_id = $productId;
        //         $objProductVariantMedia->product_variant_id = $variantId;
        //         $objProductVariantMedia->src = $imagename;
        //         $objProductVariantMedia->is_default = 1;
        //         $objProductVariantMedia->reorder = 0;
        //         $objProductVariantMedia->save();
        //     }
        // }
    }

    public function startQueue(){
        MediaUploadSuccessMail::dispatch(Auth::user())->delay(now()->addSeconds(5));

        \Artisan::call('queue:work', ['--tries' => 3]);
        // \Artisan::call('queue:forget');
    
        return $this->successResponse(
            __('constants.SUCCESS_STATUS'),
            __('constants.messages.PRODUCT_MEDIA_UPLOAD_SUCCESFULLY.code'),
            __('constants.messages.PRODUCT_MEDIA_UPLOAD_SUCCESFULLY.msg'),
        );
    } 

    public function getSearchProducts(Request $request){
        try{
            $params = collect($request->all());
            $search = $params['search'];
            $products = Product::select('id', 'title');
            if($search != ''){
                $products = $products->with([
                'medias' => function($media) {
                    $media->select('client_id','product_id','src');
                    }])->where('title', 'LIKE', '%'.$search.'%');
            }
            $products = $products->get();
            return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.PRODUCT_GET_SUCCESSFULLY.code'),
                __('constants.messages.PRODUCT_GET_SUCCESSFULLY.msg'),
                $products
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

    public function exportProducts(Request $request){
        try{
            abort_if(Gate::denies('product_export_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
            
            $params = collect( $request->all() );
            $fileName = time().'-products.csv';
            Excel::store(new ProductsExport($params), 'public/export/'.$fileName);
            
            $data = ['file_name' => $fileName, 'url' => Config::get('app.url').'/storage/export/'];

            return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.PRODUCT_EXPORT_SUCCESSFULLY.code'),
                __('constants.messages.PRODUCT_EXPORT_SUCCESSFULLY.msg'),
                $data
            );

        } 
        catch(\Throwable $e) {
            return $this->errorResponse(
                __('constants.ERROR_STATUS'),
                __('constants.errors.SOMETHING_WRONG.code'),
                __('constants.errors.SOMETHING_WRONG.msg'),
                $e->getMessage()
            );
        }
    }
}
