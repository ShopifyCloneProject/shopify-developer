<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyCollectionRequest;
use App\Http\Requests\StoreCollectionRequest;
use App\Http\Requests\UpdateCollectionRequest;
use App\Models\Product;
use App\Models\Collection;
use App\Models\ConditionTitle;
use App\Models\CollectionCondition;
use App\Models\ProductCollection;
use App\Models\Condition;
use App\Models\ProductType;
use App\Models\Vendor;
use App\Models\Tag;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use Exception;
use DB;
use File;
use Image;
use Storage;
use Config;



class CollectionsController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('collection_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {

            $query = Collection::query()->select(sprintf('%s.*', (new Collection())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'collection_show';
                $editGate = 'collection_edit';
                $deleteGate = 'collection_delete';
                $crudRoutePart = 'collections';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->editColumn('title', function ($row) {
                return $row->title ? $row->title : '';
            });
            $table->editColumn('url', function ($row) {
                if ($photo = $row->src) {
                    return sprintf(
                        '<a href="%s" target="_blank"><img src="%s" width="50px" height="50px"></a>',
                        $photo->url,
                        $photo->thumbnail
                    );
                } else {
                    return sprintf(
                        '<a href="%s" target="_blank"><img src="%s" width="50px" height="50px"></a>',
                        '/adminassets/images/default-collection.jpg',
                        '/adminassets/images/default-collection.jpg'
                    );
                }
            });

            $table->editColumn('conditions', function ($row) {
                $string = '';
                if($row->collection_type == 1){
                    $collectionConditions = $row->collection_conditions;
                    foreach ($collectionConditions as $key => $value) {
                        $conditionValue = $value->value;
                        if($value->collection_title_id == 2){
                            $conditionValue = ProductType::where('id', $value->value)->value('title');
                        } elseif($value->collection_title_id == 3){
                            $conditionValue = Vendor::where('id', $value)->value('name');
                        } elseif($value->collection_title_id == 5){
                            $conditionValue = Tag::where('id', $value)->value('title');
                        }
                        
                        $string .= $value->collection_title->title .' '. $value->condition->title. ' '. $conditionValue.'<br />';
                    }

                    if($string != ''){
                        return sprintf(
                            '<div class="conditionx-list">'.$string.'</div>',
                        );
                    }
                }    
                return  $string;
            });

            $table->rawColumns(['actions', 'placeholder', 'url', 'conditions']);

            return $table->make(true);
        }

        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['name' => trans('cruds.collection.title_singular')." ".trans('global.listing') ]];
        return view('admin.collections.index',compact('breadcrumbs'));
    }

    public function create()
    {

        abort_if(Gate::denies('collection_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $conditionTitles = ConditionTitle::get();
        $conditions = Condition::get();
        $product_types = ProductType::all()->pluck('title', 'id');
        $vendors = Vendor::all()->pluck('name', 'id');
        $tags = Tag::all()->pluck('title', 'id');

        $list = [
            'condition_titles' => $conditionTitles,
            'conditions' => $conditions,
            'product_types' => $product_types,
            'vendors' => $vendors,
            'tags' => $tags,
            'description_position' => Collection::DESCRIPTION_POSITION_RADIO,
            'collection_type' => Collection::COLLECTION_TYPE_RADIO,
            'conditions_type' => Collection::CONDITIONS_TYPE_RADIO,
            'status' => Collection::STATUS_RADIO,
            'online_store' => Collection::ONLINE_STORE_RADIO
        ];

        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['link'=>route('admin.collections.index'),'name' => trans('cruds.collection.title') ],['name' => trans('locale.Add')." ".trans('cruds.collection.title_singular') ]];
        return view('admin.collections.create', compact('list','breadcrumbs'));
    }

    public function store(Request $request)
    {
        try{
            abort_if(Gate::denies('collection_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
             $client_id = Config::get('client_id');
            $params = collect($request->all());
            $required = ['title'];
            $this->validateRequiredParams($required,$params->keys()->toArray());

             $schedule_date = null;
            if($params['is_schudule'] == 1){
                if( ($params['scheduleDate'] != '' && $params['scheduleTime'] != '') && $params['onlineStore'] == 1 ){
                    $params['scheduleTime'] = substr($params['scheduleTime'], 0, -3);
                    $schedule_date = date("Y-m-d H:i:s",strtotime($params['scheduleDate'].' '.$params['scheduleTime']));
                }
            }

            $collection = new Collection;  
            $collection->title = $params['title'];
            $collection->description = $params['description'];
            $collection->collection_type = $params['collectionType'];
            $collection->conditions_type = $params['conditionsType'];
            $collection->description_position = $params['descriptionPosition'];
            $collection->seo_keywords = $params['seoKeywords'];
            $collection->seo_description = $params['seoDescription'];
            $collection->status = $params['status'];
            $collection->src_alt_text = $params['srcAltText'];
            $collection->online_store = $params['onlineStore'];
            $collection->schedule_time = $schedule_date;
            $collection->url = $params['url'];
            $collection->save();
            $collectionId = $collection->id;

            //if conditions options is on
            if($params['collectionType'] == 1){
                if(!empty($params['conditions'])){
                    $conditions = $params['conditions'];
                    foreach ($conditions as $key => $condition) {
                       $collectionCondition = new CollectionCondition;
                       $collectionCondition->collection_id = $collectionId;
                       $collectionCondition->condition_id = $condition['conditionId'];
                       $collectionCondition->collection_title_id = $condition['typeId'];
                       $collectionCondition->value = $condition['value'];
                       $collectionCondition->save();
                    }
                }
            }
            if ($request->input('url', false)) {
                $collection->addMedia(storage_path('tmp/uploads/' . basename($request->input('url'))))->toMediaCollection('url');
                $media = DB::table('media')->where('file_name',$collection->url)->first();
                $this->collectionImageConvert($media->id, $media->file_name);
            }

            $url = route("admin.collections.edit" , ['collection' => $collectionId]);
            
            
            return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.COLLECTION_ADDED_SUCCESSFULLY.code'),
                __('constants.messages.COLLECTION_ADDED_SUCCESSFULLY.msg'),
                $url
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

    public function edit(Collection $collection)
    {   
        abort_if(Gate::denies('collection_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        try{

                $conditionTitles = ConditionTitle::get();
                $conditions = Condition::get();
                $product_types = ProductType::all()->pluck('title', 'id');
                $vendors = Vendor::all()->pluck('name', 'id');
                $tags = Tag::all()->pluck('title', 'id');
                $collection->load('collection_conditions');
               
                $list = [
                    'condition_titles' => $conditionTitles,
                    'conditions' => $conditions,
                    'product_types' => $product_types,
                    'vendors' => $vendors,
                    'tags' => $tags,
                    'description_position' => Collection::DESCRIPTION_POSITION_RADIO,
                    'collection_type' => Collection::COLLECTION_TYPE_RADIO,
                    'conditions_type' => Collection::CONDITIONS_TYPE_RADIO,
                    'status' => Collection::STATUS_RADIO,
                    'online_store' => Collection::ONLINE_STORE_RADIO,
                    'product_sort_types' => Collection::PRODUCT_SHORT_TYPE
                ];
                $collection->products = $collection->products->pluck('id');
                $collection->unsetRelation('products');

                $data['collectionDetails'] = $collection;
                $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => 'Home'], ['link'=>route('admin.collections.index'),'name' => 'Collections' ],['name' => 'Edit Collection' ]];
                return view('admin.collections.edit', compact('list', 'data','breadcrumbs'));
        } catch (Exception $e) {
            dd( $e->getMessage());
        }
    }

    public function update(Request $request)
    {
         try{
            $params = collect($request->all());
            $client_id = Config::get('client_id');
            $required = ['id' ,'title'];
            $this->validateRequiredParams($required,$params->keys()->toArray());
            //update collections table data
            $collectionId = $params['id'];

            $schedule_date = null;
            if($params['is_schudule'] == 1){
                if( ($params['scheduleDate'] != '' && $params['scheduleTime'] != '') && $params['onlineStore'] == 1 ){
                     $params['scheduleTime'] = substr($params['scheduleTime'], 0, -3);
                     $schedule_date = date("Y-m-d H:i:s",strtotime($params['scheduleDate'].' '.$params['scheduleTime']));
                }
            }
           
            $collection = Collection::where('id', $params['id'])->first();
            $collection->title = $params['title'];
            $collection->description = $params['description'];
            $collection->collection_type = $params['collectionType'];
            $collection->conditions_type = $params['conditionsType'];
            $collection->description_position = $params['descriptionPosition'];
            $collection->seo_keywords = $params['seoKeywords'];
            $collection->seo_description = $params['seoDescription'];
            $collection->status = $params['status'];
            $collection->src_alt_text = $params['srcAltText'];
            $collection->online_store = $params['onlineStore'];
            $collection->schedule_time = $schedule_date;
            $collection->save();
            CollectionCondition::where(['collection_id' => $collection->id])->forceDelete();
          //if conditions options is on
            if($params['collectionType'] == 1){
                if(!empty($params['conditions'])){
                    $conditions = $params['conditions'];
                    foreach ($conditions as $key => $condition) {
                       $collectionCondition = new CollectionCondition;
                       $collectionCondition->collection_id = $collectionId;
                       $collectionCondition->condition_id = $condition['conditionId'];
                       $collectionCondition->collection_title_id = $condition['typeId'];
                       $collectionCondition->value = $condition['value'];
                       $collectionCondition->save();
                    }
                }
            }
            $url = $params['url'];
            $objMedia = [];
            if ($request->input('url', false)) {
                if (!$collection->url || $request->input('url') !== $collection->url) {
                    if ($collection->src) {
                        $collection->src->delete();
                    }
                     $objMedia = $collection->addMedia(storage_path('tmp/uploads/' . basename($request->input('url'))))->toMediaCollection('url');
                $media = DB::table('media')->where('file_name',$url)->first(); 
                $this->checkFolder("public/$client_id/collection/$media->id");
                $this->collectionImageConvert($media->id, $media->file_name);
                }
            } elseif ($collection->url) {
                $collection->src->delete();
            }

            $collection->url = $url;
            $collection->save();
        } catch (Exception $e) {
             return $this->errorResponse(
                __('constants.ERROR_STATUS'),
                __('constants.errors.SOMETHING_WRONG.code'),
                __('constants.errors.SOMETHING_WRONG.msg'),
                $e->getMessage()
            );
        }

            if ($request->ajax()) {
            $url = route('admin.collections.index');
            return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.COLLECTION_UPDATE_SUCCESSFULLY.code'),
                __('constants.messages.COLLECTION_UPDATE_SUCCESSFULLY.msg'),
                ['url'=>$url]
            );
            }
            else {
                return redirect('/admin/collections')->with('message', __('constants.messages.COLLECTION_UPDATE_SUCCESSFULLY.msg'));
            }
        return redirect()->route('admin.collections.index');
    }

    public function show(Collection $collection)
    {
        abort_if(Gate::denies('collection_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.collections.show', compact('collection'));
    }

    public function destroy(Collection $collection)
    {
        abort_if(Gate::denies('collection_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $collection->delete();
        return $this->successResponse(
            __('constants.SUCCESS_STATUS'),
            __('constants.messages.COLLECTION_DELETE_SUCCESSFULLY.code'),
            __('constants.messages.COLLECTION_DELETE_SUCCESSFULLY.msg'),
        );
    }

    public function massDestroy(MassDestroyCollectionRequest $request)
    {
        Collection::whereIn('id', request('ids'))->delete();

        return $this->successResponse(
            __('constants.SUCCESS_STATUS'),
            __('constants.messages.COLLECTION_DELETE_SUCCESSFULLY.code'),
            __('constants.messages.COLLECTION_DELETE_SUCCESSFULLY.msg'),
        );
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('collection_create') && Gate::denies('collection_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Collection();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }

    public function getSortProducts($id, $stype){
        try{
            $collection = Collection::where('id', $id)->first();
            $collection->sorted_type = $stype;
            $collection->save();

            $collection = $collection->with(['products' => function($query){
                $query->select('id', 'title', 'status', 'price', 'products.created_at')->with(['medias' => function($query){
                    $query->select('id', 'product_id', 'src','client_id')->where('is_default', 1);
                }]);
            }])->first();

            if($stype == 1){
                $sortedProducts = collect($collection->products)->sortBy('id');
            } elseif($stype == 2){
                $sortedProducts = collect($collection->products)->sortBy('title');
            } elseif($stype == 3){
                $sortedProducts = collect($collection->products)->sortByDesc('title');
            } elseif($stype == 4){
                $sortedProducts = collect($collection->products)->sortBy('price');
            } elseif($stype == 5){
                $sortedProducts = collect($collection->products)->sortByDesc('price');
            } elseif($stype == 6){
                $sortedProducts = collect($collection->products)->sortBy('created_at');
            } elseif($stype == 7){
                $sortedProducts = collect($collection->products)->sortByDesc('created_at');
            }
            $products = $sortedProducts->values()->all();

            foreach ($products as $key => $product) {
                $data[$product->id] = [ 'order' => $key ];
            }

            if(!empty($products)){
                $collection->products()->sync($data);
            }
           
            return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.SORT_PRODUCT_GET_SUCCESSFULLY.code'),
                __('constants.messages.SORT_PRODUCT_GET_SUCCESSFULLY.msg'),
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

    public function changeSortOrder(Request $request){
        try{
            $params = collect($request->all());
            $collectionId = $params['id'];
            $stype = $params['sid'];
            $productIds = $params['order'];

            $collection = Collection::where('id', $collectionId)->first();
            $collection->sorted_type = $stype;
            $collection->save();

            if(!empty($productIds)){
                foreach ($productIds as $key => $id) {
                    $data[$id] = [ 'order' => $key ];
                }
                $collection->products()->sync($data);
            }
            
            return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.ORDER_CHANGE_SUCCESSFULLY.code'),
                __('constants.messages.ORDER_CHANGE_SUCCESSFULLY.msg')
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

    public function getConditionProducts(Request $request){
        try{
            $params = collect($request->all());
            $id =  $params['id'];
            $conditionId = $params['conditionId'];
            $collectionConditions = $params['collectionConditions'];
            $sortType = $params['sortType'];

            // if conditions options is on
            CollectionCondition::where(['collection_id' => $id])->forceDelete();
            if(!empty($collectionConditions)){
                foreach ($collectionConditions as $key => $condition) {
                   $collectionCondition = new CollectionCondition;
                   $collectionCondition->collection_id = $id;
                   $collectionCondition->condition_id = $condition['conditionId'];
                   $collectionCondition->collection_title_id = $condition['typeId'];
                   $collectionCondition->value = $condition['value'];
                   $collectionCondition->save();
                }
            }

            $collection = Collection::where('id', $id)->first();
            $collection->conditions_type = $conditionId;
            $collection->sorted_type = $sortType;
            $collection->save();
            $collectionTypeId = $collection->collection_type;
            $collectionConditions = $collection->collection_conditions;

            //pivot table entry for product and collections 
            if($collectionTypeId == 1){ // 1 = automated, 0=manual
                //Automated Product
                $products = new Product;
                if($collectionConditions->count() > 0){
                    if($conditionId == 1){
                        //Any conditions
                        $conditionType = 'orWhere';
                        $conditionTypeIn = 'orWhereIn';
                        $conditionTypeNotIn = 'orWhereNotIn';
                        $conditionTypeWhereHas = 'orWhereHas';
                        $conditionTypeWhereNull = 'orWhereNull';
                        $conditionTypeWhereNotNull = 'orWhereNotNull';
                    } else {
                        //All conditions
                        $conditionType = 'where';
                        $conditionTypeIn = 'whereIn';
                        $conditionTypeNotIn = 'whereNotIn';
                        $conditionTypeNotIn = 'whereNotIn';
                        $conditionTypeWhereHas = 'whereHas';
                        $conditionTypeWhereNull = 'whereNull';
                        $conditionTypeWhereNotNull = 'whereNotNull';
                    }
                   
                    foreach ($collectionConditions as $key => $condition) {
                        $titleId = $condition->collection_title_id;
                        $collectionId = $condition->condition_id;
                        $value = $condition->value;

                        if($titleId == 1){
                            if($collectionId == 1){
                                $products = $products->$conditionType('title',  $value);
                            }elseif($collectionId == 2){
                                $products = $products->$conditionType('title', '!=' , $value);
                            }elseif($collectionId == 5){
                                $products = $products->$conditionType('title', 'like', $value.'%');
                            }elseif($collectionId == 6){
                                $products = $products->$conditionType('title', 'like', '%'.$value);
                            }elseif($collectionId == 7){
                                $products = $products->$conditionType('title', 'LIKE', '%'.$value.'%');
                            }elseif($collectionId == 8){
                                $products = $products->$conditionType('title', 'NOT LIKE', '%'.$value.'%');
                            }
                        } 
                        elseif($titleId == 2)
                        {
                            //get title from table for get match ids
                            $typeTitle = ProductType::where('id', $value)->value('title');
                            if($collectionId == 1){
                                $products = $products->$conditionType('product_type_id',  $value);
                            }elseif($collectionId == 2){
                                $products = $products->$conditionType('product_type_id', '!=' , $value);
                            }elseif($collectionId == 5){
                                $typeIds = ProductType::where('title', 'LIKE', $typeTitle.'%')->pluck('id');
                                $products = $products->$conditionTypeIn('product_type_id', $typeIds);
                            }elseif($collectionId == 6){
                                $typeIds = ProductType::where('title', 'LIKE', '%'.$typeTitle)->pluck('id');
                                $products = $products->$conditionTypeIn('product_type_id', $typeIds);
                            }elseif($collectionId == 7){
                                $typeIds = ProductType::where('title', 'LIKE', '%'.$typeTitle.'%')->pluck('id');
                                $products = $products->$conditionTypeIn('product_type_id', $typeIds);
                            }elseif($collectionId == 8){
                                $typeIds = ProductType::where('title', 'NOT LIKE', '%'.$typeTitle.'%')->pluck('id');
                                $products = $products->$conditionTypeNotIn('product_type_id',  $typeIds);
                            }
                        }
                        elseif($titleId == 3)
                        {   
                            //get title from table for get match ids
                            $vendorName = Vendor::where('id', $value)->value('name');
                            if($collectionId == 1){
                                $products = $products->$conditionType('vendor_id',  $value);
                            }elseif($collectionId == 2){
                                $products = $products->$conditionType('vendor_id', '!=' , $value);
                            }elseif($collectionId == 5){
                                $vendorIds = Vendor::where('name', 'LIKE', $vendorName.'%')->pluck('id');
                                $products = $products->$conditionTypeIn('vendor_id', $vendorIds);
                            }elseif($collectionId == 6){
                                $vendorIds = Vendor::where('name', 'LIKE', '%'.$vendorName)->pluck('id');
                                $products = $products->$conditionTypeIn('vendor_id', $vendorIds);
                            }elseif($collectionId == 7){
                                $vendorIds = Vendor::where('name', 'LIKE', '%'.$vendorName.'%')->pluck('id');
                                $products = $products->$conditionTypeIn('vendor_id', $vendorIds);
                            }elseif($collectionId == 8){
                                $vendorIds = Vendor::where('name', 'NOT LIKE', '%'.$vendorName.'%')->pluck('id');
                                $products = $products->$conditionTypeNotIn('vendor_id',  $vendorIds);
                            }
                        }
                        elseif($titleId == 4)
                        {
                            if($collectionId == 1){
                                $products = $products->$conditionType('price',  $value);
                            }elseif($collectionId == 2){
                                $products = $products->$conditionType('price', '!=' , $value);
                            }elseif($collectionId == 3){
                                $products = $products->$conditionType('price', '>',  $value);
                            }elseif($collectionId == 4){
                                $products = $products->$conditionType('price', '<',  $value);
                            }
                        }
                        elseif($titleId == 5)
                        {
                            if($collectionId == 1){
                                $products = $products->$conditionTypeWhereHas('product_tags');

                                $products = $products->$conditionTypeWhereHas('product_tags', function ($query) use ($value)
                                {
                                      $query->where('tag_id', $value);
                                });
                            }
                        }
                        elseif($titleId == 6)
                        {
                            if($collectionId == 1){
                                $products = $products->$conditionType('compare_at_price',  $value);
                            }elseif($collectionId == 2){
                                $products = $products->$conditionType('compare_at_price', '!=' , $value);
                            }elseif($collectionId == 3){
                                $products = $products->$conditionType('compare_at_price', '>',  $value);
                            }elseif($collectionId == 4){
                                $products = $products->$conditionType('compare_at_price', '<',  $value);
                            }elseif($collectionId == 9){
                                $products = $products->$conditionType('compare_at_price', '!=', 0);
                            }elseif($collectionId == 10){
                                $products = $products->$conditionType('compare_at_price', 0);
                            }
                        }
                        elseif($titleId == 7)
                        {
                            if($collectionId == 1){
                                $products = $products->$conditionType('weight',  $value);
                            }elseif($collectionId == 2){
                                $products = $products->$conditionType('weight', '!=' , $value);
                            }elseif($collectionId == 3){
                                $products = $products->$conditionType('weight', '>',  $value);
                            }elseif($collectionId == 4){
                                $products = $products->$conditionType('weight', '<',  $value);
                            }
                        }
                        elseif($titleId == 8)
                        {
                            if($collectionId == 1){
                                $products = $products->$conditionType('quantity',  $value);
                            }elseif($collectionId == 3){
                                $products = $products->$conditionType('quantity', '>',  $value);
                            }elseif($collectionId == 4){
                                $products = $products->$conditionType('quantity', '<',  $value);
                            }
                        }
                    }
                }
                $productsIds = $products->pluck('id');
                if($productsIds->count() > 0){
                    if($sortType != 8){
                        //if sort type is not manual then sort data by collections methods
                        $products = Product::whereIn('id', $productsIds)->get();
                        if($sortType == 1){
                            $sortedProducts = collect($products)->sortBy('id');
                        } elseif($sortType == 2){
                            $sortedProducts = collect($products)->sortBy('title');
                        } elseif($sortType == 3){
                            $sortedProducts = collect($products)->sortByDesc('title');
                        } elseif($sortType == 4){
                            $sortedProducts = collect($products)->sortBy('price');
                        } elseif($sortType == 5){
                            $sortedProducts = collect($products)->sortByDesc('price');
                        } elseif($sortType == 6){
                            $sortedProducts = collect($products)->sortBy('created_at');
                        } elseif($sortType == 7){
                            $sortedProducts = collect($products)->sortByDesc('created_at');
                        }
                        $products = $sortedProducts->values()->all();

                        foreach ($products as $key => $product) {
                            $data[$product->id] = [ 'order' => $key ];
                        }
                        $collection->products()->sync($data);
                    } else {
                        //if sort type is manual do not remove existing record from pivot table and add new product with last order index
                        $pivotProducts = $collection->products;
                        $pivotProductIds = [];
                        if($pivotProducts->count() > 0){
                            $pivotProductIds = $collection->products->map->only(['id'])->pluck('id');
                        }
                        //check if any new product is added by conditions
                        $productsIds = collect($productsIds);
                        $diff = $productsIds->diff($pivotProductIds);
                        $diffIds = $diff->all();
                        if(!empty($diffIds)){
                            //attach new product with last order in pivot table
                            $lastOrderId = ProductCollection::where( 'collection_id', $id )->max('order');
                            $lastOrderId = $lastOrderId == '' ? 0 : $lastOrderId;
                            $products = Product::whereIn('id', $diffIds)->get();
                            foreach ($products as $key => $product) {
                                $lastOrderId++;
                                $data[$product->id] = [ 'order' => $lastOrderId ];
                            }
                            $collection->products()->attach($data);
                        } else {
                            //check if any product is removed from conditions
                            $pivotProductIds = collect($pivotProductIds);
                            $diff = $pivotProductIds->diff($productsIds);
                            $diffIds = $diff->all();

                            if(!empty($diffIds)){
                                //remove data from pivot table
                                $collection->products()->detach($diffIds);
                            } 
                        } 
                    }
                } else {
                    //if product not found then remoe entry from pivot table
                    $collection->products()->detach();
                }
            } 
          
            $collection = $collection->with(['products' => function($query){
                $query->select('id', 'title', 'status')->with(['medias' => function($query){
                    $query->select('id', 'product_id', 'src','client_id')->where('is_default', 1);
                }]);
            }])->where('id', $id)->first();
            // dd(DB::getQueryLog());
            $products = $collection->products->take(10)->all();

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

    public function loadProducts($number, $search = null){
        try{
            $limit= 20;

            $products = Product::select('id', 'title')->where('status', 1);

            if($search != ''){
                $products = $products->where('title', 'LIKE', '%'.$search.'%');
            }

            $products = $products->with([ 'medias' => function($query){
                $query->select('id', 'product_id', 'src', 'client_id')->where('is_default', 1);
            }]);

            $products = $products->skip($number)->take($limit)->get();

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

    public function addProducts(Request $request){
        try{
            $params = collect($request->all());
            $required = ['cid', 'products'];
            $this->validateRequiredParams($required,$params->keys()->toArray());

            //store response data
            $id = $params['cid'];
            $productsIds = $params['products'];
            $collection = Collection::where( 'id', $id )->first();
            $pivotProducts = $collection->products;
            $pivotProductIds = [];
            if($pivotProducts->count() > 0){
                $pivotProductIds = $collection->products->map->only(['id'])->pluck('id');
            }
            //check if any new product is added by conditions
            $productsIds = collect($productsIds);
            $diff = $productsIds->diff($pivotProductIds); //new checked product ids
            $removediffid = [];
            if(!empty($pivotProductIds)){
                $removediffid = $pivotProductIds->diff($productsIds); //unchecked product ids
                $removediffid = $removediffid->all();
            }
            $diffIds = $diff->all();

            if(!empty($diffIds)){
                //attach new product with last order in pivot table
                $lastOrderId = ProductCollection::where( 'collection_id', $id )->max('order');
                $lastOrderId = $lastOrderId == '' ? 0 : $lastOrderId;
                $products = Product::whereIn('id', $diffIds)->get();
                foreach ($products as $key => $product) {
                    $lastOrderId++;
                    $data[$product->id] = [ 'order' => $lastOrderId ];
                }

                //unchecked product ids
                if(!empty( $removediffid )){
                    $collection->products()->detach($removediffid);
                }

                //new checked product ids
                $collection->products()->attach($data);
            } else {
                if(!empty($removediffid)){
                    //remove data from pivot table
                    $collection->products()->detach($removediffid);
                } 
            } 

            $collection = $collection->with(['products' => function($query){
                $query->select('id', 'title', 'status', 'price', 'products.created_at')->with(['medias' => function($query){
                    $query->select('id', 'product_id', 'src','client_id')->where('is_default', 1);
                }]);
            }])->where('id', $id)->first();
            $products = $collection->products;

            return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.PRODUCT_ADDED_SUCCESSFULLY.code'),
                __('constants.messages.PRODUCT_ADDED_SUCCESSFULLY.msg'),
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

    public function showAllProducts($id){
        try{

            $collection = Collection::where('id', $id)->first();
            $totaoProduct = $collection->products->count();
            $take = $totaoProduct - 10;

            $collection = $collection->with(['products' => function($query) use($take){
                $query->select('id', 'title', 'status')->with(['medias' => function($query){
                    $query->select('id', 'product_id', 'src', 'client_id')->where('is_default', 1);
                }]);
            }])->where('id', $id)->first();
            $products = $collection->products->skip(10)->take($take)->all();

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

    public function AllProducts(){
        try{

            $products = Product::select('id', 'title', 'status')->with(['medias' => function($query){
                    $query->select('id', 'product_id', 'src','client_id')->where('is_default', 1);
                }])->where('status', 1)->get();


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

    public function collectionImageConvert($collection_media_id, $collection_img_url)
    {
        $client_id = Config::get('client_id');
        if(!file_exists(storage_path("app/public/$client_id/collection/$collection_media_id/conversions/$collection_img_url")))
        $this->checkFolder("public/$client_id/collection/$collection_media_id/conversions");
        
            $image = Image::make(storage_path("app/public/$client_id/collection/$collection_media_id/$collection_img_url"))->resize(null, 50, function($constraint) {
                $constraint->aspectRatio();
            });
            $image->save(storage_path("app/public/$client_id/collection/$collection_media_id/conversions/$collection_img_url"));
           
        }
    }

     

