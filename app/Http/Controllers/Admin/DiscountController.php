<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\MassDestroyDiscountRequest;
use App\Models\Product;
use App\Models\User;
use App\Models\Currency;
use App\Models\Collection;
use App\Models\Discount;
use App\Models\DiscountProduct;
use App\Models\DiscountCollection;
use App\Models\DiscountUser;
use App\Models\DiscountMedium;
use Gate;
use Config;
use Storage;
use DB;

class DiscountController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('discounts_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        if ($request->ajax()) {
            $query = Discount::select(sprintf('%s.*', (new Discount)->table));
            $table = Datatables::of($query);

            $table->addColumn('actions', ' ');

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->editColumn('code', function ($row) {
                return $row->code ? $row->code : '';
            });
            $table->editColumn('initial_value', function ($row) {
                return $row->initial_value ? $row->initial_value : '';
            });
            $table->editColumn('product_or_collection', function ($row) {
                return Discount::PRODUCT_OR_COLLECTION_RADIO[$row->product_or_collection];
            });
            $table->editColumn('status', function ($row) {
                return Discount::STATUS_RADIO[$row->status];
            });

            $table->editColumn('starting_date', function ($row) {
                return $row->starting_date ? $row->starting_date : '';
            });

            $table->editColumn('expiry_date', function ($row) {
                return $row->expiry_date ? $row->expiry_date : '';
            });

            $table->rawColumns(['actions']);

            return $table->make(true);
        }

        $product_or_collection_status = Discount::PRODUCT_OR_COLLECTION_RADIO;
        $discountStatus = Discount::STATUS_RADIO;
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['name' => trans('cruds.discounts.title_singular')." ".trans('global.listing') ]];
        return view('admin.discounts.index', compact('breadcrumbs','product_or_collection_status','discountStatus'));
    }

    public function create()
    {
        abort_if(Gate::denies('discounts_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $list = $data = [];
        $data['collections'] = [];
        $type = 'Add';
        $searchProductLimit = Config::get('SEARCH_PRODUCT_LIMIT');

        $list['currencies'] = Currency::select(DB::raw("CONCAT(name,' (',symbol,')') AS name"),'id')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $collections = Collection::select('title', 'id')->get();
        $collections->map(function($d) { $d['checked'] = false; return $d; });
        $list['collections'] = $collections->toArray();
        $list['objProducts'] = Product::select('id','title')->with([
                'medias' => function($media) {
                    $media->select('client_id','product_id','src');
                    }])->where('status',1)->limit(50)->get();
        $list['objUsers'] = User::select('id','name','last_name','email','mobile')->limit(50)->get();

        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => 'Home'], ['link'=>route('admin.discounts.index'),'name' => trans('cruds.discounts.title') .' '.trans('global.listing') ], ['name' => trans('global.add') .' '.trans('cruds.discounts.title_singular') ]];
        return view('admin.discounts.createupdate',compact('list','breadcrumbs','type','data'));
    }


    public function edit($discount_id)
    {
        abort_if(Gate::denies('discounts_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $list = $data = $discountProducts = $collections = $objProducts = [];
        $type = 'Edit';
        $searchProductLimit = Config::get('SEARCH_PRODUCT_LIMIT');
        $currencies = Currency::all()->pluck('currency', 'id')->prepend(trans('global.pleaseSelect'), '');
        $objUsers = User::select('id','name','last_name','email','mobile')->limit(50)->get();
        $objDiscount = Discount::where('id',$discount_id)->first();

        if(!empty($objDiscount))
        {
            $objDiscountProducts = $arrCollections = [];
            if($objDiscount->product_or_collection == 1){
                $objProducts  = Product::select('id','title')->with([
                        'medias' => function($media) {
                        $media->select('client_id','product_id','src');
                            }])->where('status',1)->limit(50)->get();

                $objDiscountProductId = DiscountProduct::where('discount_id',$discount_id)->pluck('product_id')->toArray();
                $objDiscountProducts 
                =  Product::select('id','title')->with(['medias','discount_products' => function($query_discount_products) use($discount_id){
                  $query_discount_products->where('discount_id',$discount_id);
                }])
                ->whereHas('discount_products', function($query_discount_products) use($discount_id){
                  $query_discount_products->where('discount_id',$discount_id);
                })->get();

                foreach($objDiscountProducts as $objDiscountProduct){
                    foreach($objDiscountProduct->discount_products as $discount_product){
                        $productstatus = ($discount_product->status == 1) ? true : false;
                        $objDiscountProduct = $objDiscountProduct->setAttribute('productstatus',$productstatus);
                    }
                }
            }
            else{
                $objDiscountCollectionsId = DiscountCollection::where('discount_id',$discount_id)->pluck('collection_id')->toArray();
                $objCollections = DiscountCollection::with([
                        'collection' => function($collection) use($objDiscountCollectionsId){
                        $collection->select('id','title')->whereIn('id',$objDiscountCollectionsId)->get();
                            }])->where('discount_id',$discount_id)->get();
                foreach($objCollections as $objCollection){
                        $tempCollectionData = [];
                        $tempCollectionData['id'] = $objCollection->collection()->first()->id;
                        $tempCollectionData['title'] = $objCollection->collection()->first()->title;
                        $tempCollectionData['checked'] = ($objCollection->status == 1) ? true : false;
                        $arrCollections[] = $tempCollectionData;
                    }
                $arrcollectionId = collect($arrCollections)->pluck('id')->toArray();
                $collections = Collection::select('title', 'id')->get();
                $collections->map(function($d) use($arrcollectionId) { 
                    $d['checked'] = in_array($d['id'], $arrcollectionId)?true : false; 
                    return $d; 
                });
                $collections = $collections->toArray();
            }

            $discountUserId = DiscountUser::where('discount_id',$discount_id)->pluck('user_id')->toArray();
            $objDiscountUsers = User::with([
               'discount_user' => function($q) use($discount_id) {
                    $q->where('discount_id',$discount_id)->get();
                },
            ])->select('id', 'email','last_name','name')->whereIn('id',$discountUserId)->get();

            foreach($objDiscountUsers as $objDiscountUser){
                foreach($objDiscountUser->discount_user as $discount_user){
                        $userstatus = ($discount_user->status == 1) ? true : false;
                        $objDiscountUser = $objDiscountUser->setAttribute('userstatus',$userstatus);
                }
            }

            $objDiscountMedia = DiscountMedium::where('discount_id',$discount_id)->get();
            if($objDiscountMedia)
            {
                $orderProduct = $objDiscountMediaSrc = [];
                foreach($objDiscountMedia as $discountMedia)
                {
                    $tempDiscountMedia = [];
                    $tempDiscountMedia['id'] = $discountMedia->id;
                    $tempDiscountMedia['src'] = (!empty($discountMedia->src)) ? $discountMedia->src : '';
                    $objDiscountMediaSrc[] = $tempDiscountMedia;
                }
                $objDiscountMediaSrc = $objDiscountMediaSrc;
            }
        }
        $list = [
            'currencies'  => $currencies,
            'collections' => $collections,
            'objProducts' => $objProducts,
            'objUsers'    => $objUsers,
        ];
        $data = [
            'objDiscount' => $objDiscount,
            'objDiscountProducts' => $objDiscountProducts,
            'collections' => $arrCollections,
            'objDiscountUsers' => $objDiscountUsers,
            'objDiscountMediaSrc' => $objDiscountMediaSrc,
        ];

        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => 'Home'], ['link'=>route('admin.discounts.index'),'name' => trans('cruds.discounts.title') .' '.trans('global.listing') ], ['name' => trans('global.edit') .' '.trans('cruds.discounts.title_singular') ]];     
        return view('admin.discounts.createupdate',compact('list','breadcrumbs','type','data'));
    }

    public function addEditDiscount(Request $request)
    {
        try {
            $params = collect($request->all());
            $client_id = Config::get('client_id');
           

            if(isset($params['id'])){
                $objDiscount = Discount::where('id',$params['id'])->first();
            }
            if(empty($objDiscount))
            {
                $objCheckDiscount = Discount::where('code',$params['code'])->first();
                if(empty($objCheckDiscount))
                {
                    $objDiscount = new Discount;
                }
                else
                {
                    return $this->successResponse(
                        __('constants.SUCCESS_STATUS'),
                        __('constants.messages.DISCOUNT_ALREADY_EXIST.code'),
                        __('constants.messages.DISCOUNT_ALREADY_EXIST.msg'),
                    );

                }
            }
            $objDiscount->code = $params['code'];
            $objDiscount->starting_date = $params['starting_date'];
            $objDiscount->expiry_date = $params['expiry_date'];
            $objDiscount->expiry_type = $params['expiry_type'];
            $objDiscount->currency_id = $params['currency_id'];
            $objDiscount->percentage_or_amount = $params['percentage_or_amount'];
            $objDiscount->amount = $params['amount'];
            $objDiscount->initial_value = $params['initial_value'];
            $objDiscount->product_or_collection = $params['product_or_collection'];
            $objDiscount->product_status = $params['product_status'];
            $objDiscount->status = $params['status'];
            $objDiscount->user_availability = $params['user_availability'];
            $objDiscount->note = $params['note'];
            $objDiscount->save();

            if($params['product_or_collection'] == 1){

                    if($params['product_status'] == 0){

                        $savedProduct = DiscountProduct::where(['discount_id' => $objDiscount->id])->pluck('product_id')->toArray();
                        $newProduct = collect($params['finalSelectionProduct'])->pluck('id')->toArray();

                        $removeProduct = array_diff($savedProduct, $newProduct);
                        if(!empty($removeProduct))
                        {
                            DiscountProduct::whereIn('product_id', $removeProduct)->forcedelete();
                        }
                        foreach($params['finalSelectionProduct'] as $selectionProduct)
                        {
                            $objDiscountProduct = DiscountProduct::where(['discount_id' => $objDiscount->id, 'product_id' => $selectionProduct['id']])->first();
                            if($params['product_status'] == 0){
                                if(empty($objDiscountProduct))
                                {
                                    $objDiscountProduct = new DiscountProduct;
                                }
                                $objDiscountProduct->discount_id = $objDiscount->id;
                                $objDiscountProduct->product_id = $selectionProduct['id'];
                                $objDiscountProduct->product_variant_options_id = 0;
                                $objDiscountProduct->initial_value = $params['initial_value'];
                                $objDiscountProduct->status = $selectionProduct['productstatus'];
                                $objDiscountProduct->currency_id = $params['currency_id'];
                                $objDiscountProduct->save();
                            }
                        }
                    }
                    else
                    {
                        DiscountProduct::where(['discount_id' => $objDiscount->id])->forcedelete();
                    }

            }
            else{
                 DiscountCollection::where(['discount_id' => $objDiscount->id])->forcedelete();
                foreach($params['finalSelectCollection'] as $collectionSelect){
                    $objDiscountCollection = new DiscountCollection;
                    $objDiscountCollection->discount_id = $objDiscount->id;
                    $objDiscountCollection->collection_id = $collectionSelect['id'];
                    $objDiscountCollection->initial_value = $params['initial_value'];
                    $objDiscountCollection->status = 1;
                    $objDiscountCollection->currency_id = $params['currency_id'];
                    $objDiscountCollection->save();
                }
            }

            if($params['user_status'] == 1){
                DiscountUser::where(['discount_id' => $objDiscount->id])->forcedelete();
            }
            else
            {
                $savedUser = DiscountUser::where(['discount_id' => $objDiscount->id])->pluck('user_id')->toArray();
                $newUser = collect($params['finalSelectUserId'])->pluck('id')->toArray();

                $removeUser = array_diff($savedUser, $newUser);
                if(!empty($removeUser))
                {
                    DiscountUser::whereIn('user_id', $removeUser)->forcedelete();
                }
                foreach($params['finalSelectUserId'] as $userSelect)
                {
                    $objDiscountUser = DiscountUser::where(['discount_id' => $objDiscount->id, 'user_id' => $userSelect['id']])->first();
                    if($params['user_status'] == 0){
                        if(empty($objDiscountUser))
                        {
                            $objDiscountUser = new DiscountUser;
                        }
                        $objDiscountUser->discount_id = $objDiscount->id;
                        $objDiscountUser->user_id = $userSelect['id'];
                        $objDiscountUser->status = $userSelect['userstatus'];
                        $objDiscountUser->save();
                    } 
                }
            }

            $saveMedia = DiscountMedium::where(['discount_id' => $objDiscount->id])->pluck('id')->toArray();
            $newMedia = collect($params['media'])->pluck('id')->toArray();

            $removeMedia = array_diff($saveMedia, $newMedia);
            if(!empty($removeMedia))
            {
                $objRemoveMedia = DiscountMedium::whereIn('id', $removeMedia)->get();
                foreach($objRemoveMedia as $key => $objSingleMedia)
                {
                    Storage::disk('public')->delete("$client_id/discount/$objSingleMedia->src");
                }
                DiscountMedium::whereIn('id', $removeMedia)->delete();
            }

            if(!empty($params['media']))
            {
                foreach($params['media'] as $key => $imageData)
                {
                    $refrence_id = mt_rand( 1000, 9999);
                    $imagename = time().$refrence_id.'.png';
                    $objDiscountMedia = DiscountMedium::where('id', $imageData['id'])->first();
                    if(empty($objDiscountMedia))
                    {
                        $objDiscountMedia = new DiscountMedium;
                        $image = file_get_contents($imageData['imageurl']);
                        Storage::disk('public')->put("$client_id/discount/$imagename", $image, 'public');
                        $objDiscountMedia->src = $imagename;
                    }
                    $objDiscountMedia->client_id = $client_id;
                    $objDiscountMedia->discount_id = $objDiscount->id;
                    $objDiscountMedia->save();
                }
            }
            if(isset($params['id'])){
                 $url = route("admin.discounts.index");
                return $this->successResponse(
                    __('constants.SUCCESS_STATUS'),
                    __('constants.messages.DISCOUNT_UPDATED_SUCCESSFULLY.code'),
                    __('constants.messages.DISCOUNT_UPDATED_SUCCESSFULLY.msg'),
                    ['url'=>$url]
                );
            } else {
                $url = route("admin.discounts.edit",['discount'=>$objDiscount->id]);
                 return $this->successResponse(
                    __('constants.SUCCESS_STATUS'),
                    __('constants.messages.DISCOUNT_SAVED_SUCCESSFULLY.code'),
                    __('constants.messages.DISCOUNT_SAVED_SUCCESSFULLY.msg'),
                    ['url'=>$url]
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

    public function destroy($id)
    {
        try
        {    
            abort_if(Gate::denies('discounts_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
            Discount::where('id',$id)->delete();
            DiscountProduct::where('discount_id',$id)->delete(); 
            DiscountCollection::where('discount_id',$id)->delete();
            $url = route('admin.discounts.index');
            return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.DISCOUNT_DELETED_SUCCESSFULLY.code'),
                __('constants.messages.DISCOUNT_DELETED_SUCCESSFULLY.msg'),
                ['url' => $url]
            );
        }
        catch (\Exception $e) 
        {
            return $this->errorResponse(
            __('constants.ERROR_STATUS'),
            __('constants.errors.SOMETHING_WRONG.code'),
            __('constants.errors.SOMETHING_WRONG.msg'),
            $e->getMessage()
            );
        }
    }

    public function massDestroy(MassDestroyDiscountRequest $request)
    {
        try 
        {
            abort_if(Gate::denies('discounts_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
            Discount::whereIn('id', request('ids'))->delete(); 
            DiscountProduct::whereIn('discount_id', request('ids'))->delete(); 
            DiscountCollection::whereIn('discount_id', request('ids'))->delete(); 
            return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.DISCOUNT_DELETED_SUCCESSFULLY.code'),
                __('constants.messages.DISCOUNT_DELETED_SUCCESSFULLY.msg'),
            );
        } 
        catch (\Exception $e) 
        {
            return $this->errorResponse(
                __('constants.ERROR_STATUS'),
                __('constants.errors.SOMETHING_WRONG.code'),
                __('constants.errors.SOMETHING_WRONG.msg'),
                $e->getMessage()
            );
        }
    }
}