<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\MassDestroyXmlFeedRequest;
use App\Http\Requests\UpdateXMLFeedRequest;
use App\Models\Collection;
use App\Models\Product;
use App\Models\XMLFeed;
use App\Models\Section;
use App\Models\XMLS;
use App\Models\XMLItem;
use Gate;
use Auth;
use DB;
use Exception;
use Storage;
use View;
use Config;

class XmlFeedController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('xmlfeed_access_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $client_id = config('client_id');
        if ($request->ajax()) {
            $query = XMLS::select(sprintf('%s.*', (new XMLS())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            // $table->editColumn('actions', function ($row) {
            //     $viewGate = 'state_show';
            //     $editGate = 'state_edit';
            //     $deleteGate = 'state_delete';
            //     $crudRoutePart = 'states';

            //     return view('partials.datatablesActions', compact(
            //     'viewGate',
            //     'editGate',
            //     'deleteGate',
            //     'crudRoutePart',
            //     'row'
            //     ));
            // });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->editColumn('title', function ($row) {
                return $row->title ? $row->title : '';
            });
            $table->addColumn('url', function ($row) use ($client_id) {
                return '<a href="'.Config::get("app.url").'/storage/'.$client_id.'/xml/'.$row->id.'.xml" download>'.$row->title.'</a>';
            });

            $table->rawColumns(['actions', 'placeholder', 'url']);

            return $table->make(true);
        }

        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['link'=>route('admin.settings.index'),'name' => trans('cruds.settings.title')], ['name' => trans('cruds.settings.xmlfeed')." ".trans('global.listing')]];
        return view('admin.settings.xmlfeed.index', compact('breadcrumbs'));
    }

    public function create()
    {
        abort_if(Gate::denies('xmlfeed_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $collections = Collection::where('status', 1)->get();
        $objSection = Section::where('status', 1)->get();
        $objXmlFeed = XMLFeed::where('createtime', 1)->whereNull('xml_id')->get();
        $objSection1 = Section::with('relations')->where(['section'=> 0, 'relation' => null])->get();
        $objSection2 = Section::where(['section'=>1, 'relation' => null])->get();

        $data = [
            'collections' => $collections,
            'objSection'  => $objSection,
            'objXmlFeed' => $objXmlFeed,
            'objSection1' => $objSection1,
            'objSection2' => $objSection2,
        ];
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['link'=>route('admin.settings.xmlfeed.index'),'name' => trans('cruds.settings.xmlfeed') ],['name' => trans('locale.Add')." ".trans('cruds.settings.xmlfeed') ]];
        return view('admin.settings.xmlfeed.create', compact('data','breadcrumbs'));
    }

    public function edit($id){
        abort_if(Gate::denies('xmlfeed_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $collections = Collection::where('status', 1)->get();
        $objSection = Section::where('status', 1)->get();
        $objXmlFeed = XMLFeed::where('createtime', 1)->where('xml_id', $id)->get();
        $objSection1 = Section::with('relations')->where(['section'=> 0, 'relation' => null])->get();
        $objSection2 = Section::where(['section'=> 1, 'relation' => null])->get();
        $xmlsData = XMLS::select('id', 'title', 'product_type', 'option_type', 'createtime')
        ->with('items')
        ->where('id', $id)->first();

        $selectedProductList = $xmlsData->items->pluck('product_id')->filter()->toArray();
        $selectedCollectionList = $xmlsData->items->pluck('collection_id')->filter()->toArray();

        // $selectedCollectionList = [];
        // if(!empty($collectionList)){

        // }
        // $selectedCollectionList = 

        unset($xmlsData->items);
        $data = [
            'collections' => $collections,
            'objSection'  => $objSection,
            'objXmlFeed' => $objXmlFeed,
            'objSection1' => $objSection1,
            'objSection2' => $objSection2,
            'xmlsData' => $xmlsData,
            'selectedProductList' => $selectedProductList,
            'selectedCollectionList' => $selectedCollectionList,
        ];

        return view('admin.settings.xmlfeed.edit', compact('data'));
    }

    public function loadProducts($number, $search = null){
        try{
            $limit= 20;

            $products = Product::select('id', 'title');

            if($search != ''){
                $products = $products->where('title', 'LIKE', '%'.$search.'%');
            }

            $products = $products->with([ 'medias' => function($query){
                $query->select('id', 'product_id', 'src', 'client_id')->where('is_default', 1);
            }])->where('status', 1);

            $totalProducts = $products->count();

            if($number == 'all'){
                $products = $products->get();
            } else {
                $products = $products->skip($number)->take($limit)->get();
            }

            $data = ['total' => $totalProducts, 'products' =>  $products];
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

    public function getCollectionProducts($id = null){
        try{

            // DB::table('users')->orderBy('id')->chunk(100, function ($users) {
            //     foreach ($users as $user) {
            //     }
            // });
            $selectedProductList = [];
            if(isset($id)){
                $xmlsId = $id;
                $xmlsData = XMLS::select('id', 'title', 'product_type', 'option_type', 'createtime')
                ->with('items')
                ->where('id', $xmlsId)->first();

                $selectedProductList = $xmlsData->items->pluck('product_id')->filter()->toArray();
            }
            $collections = Collection::with('products')->get();
            $objCollections = [];
            $count = 1;
            foreach($collections as $key => $collection){
                // $objCollections[$key]['id'] = $count;
                $objCollections[$key]['id'] = $collection->id;
                $objCollections[$key]['text'] = $collection->title;
                $objCollections[$key]['cid'] =  $collection->id;
                $objCollections[$key]['expanded'] = false;
                // $count++;
                foreach($collection->products as $key1 => $products){
                    // $objCollections[$key]['items'][$key1]['id'] = $count;
                    $objCollections[$key]['items'][$key1]['id'] = $collection->id.'_'.$products->id;
                    $objCollections[$key]['items'][$key1]['text'] =  $products->title;
                    $objCollections[$key]['items'][$key1]['expanded'] =  false;
                    $objCollections[$key]['items'][$key1]['cid'] =  $collection->id;
                    $objCollections[$key]['items'][$key1]['pid'] =  $products->id;
                    if(!empty($selectedProductList)){
                        $objCollections[$key]['items'][$key1]['selected'] = in_array($products->id, $selectedProductList) ? true : false;
                    }
                    // $count++;
                }
            }
            $data['collections'] = $objCollections;

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

    public function getDefaultXMLSettings()
    {
        abort_if(Gate::denies('default_xmlfeed_access_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $objXmlFeed = XMLFeed::where('default', 1)->get();
        $objSection1 = Section::with('relations')->where(['section'=>0, 'relation' => null])->get();
        $objSection2 = Section::where(['section'=>1, 'relation' => null])->get();
         $data = [
            'objXmlFeed' => $objXmlFeed,
            'objSection1' => $objSection1,
            'objSection2' => $objSection2,
        ];
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['link'=>route('admin.settings.index'),'name' => trans('cruds.settings.title')], ['name' => trans('cruds.settings.setfield') ]];
       return view('admin.settings.xmlfeed.defaultxml', compact('data','breadcrumbs'));
    }

    public function saveDefaultXML(Request $request)
    {
        try {
            $params = collect($request->all());
            XMLFeed::truncate();
            foreach($params['defaultxml'] as $xmlKey => $objXmlFeed)
            {
                $objXMLFeed = new XMLFeed;
                $objXMLFeed->choose1 = $objXmlFeed['choose1'];
                $objXMLFeed->choose2 = $objXmlFeed['choose2'];
                $objXMLFeed->default = 1;
                $objXMLFeed->createtime =1;
                $objXMLFeed->save();
            }
        return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.DEFAULT_XML_SET_SUCCESSFULLY.code'),
                __('constants.messages.DEFAULT_XML_SET_SUCCESSFULLY.msg'),
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

    public function generateXML(Request $request)
    {
        try {
            $params = collect($request->all());

            $title = $params['title'];
            $defaultxml = $params['defaultxml'];
            $productType = $params['XMLOption'];
            $optionType = $params['productOption'];
            $selectedProductList = $params['selectedProductList'];
            $selectedCollectionList = $params['selectedCollectionList'];

            $xmlObj = new XMLS();
            if(isset($params['id'])){
                XMLItem::where('xml_id', $params['id'])->forceDelete();
                $xmlObj = XMLS::where('id', $params['id'])->first();
            }
           
            $xmlObj->title = $title;
            $xmlObj->product_type = $productType;
            $xmlObj->option_type = $optionType;
            $xmlObj->createtime = 1;
            $xmlObj->save();
            $xmlId = $xmlObj->id;
            $intTime = time();
            if(isset($defaultxml)){
                foreach($defaultxml as $key => $xmlFeed){
                    $objXMLFeed = new XMLFeed;
                    $objXMLFeed->xml_id = $xmlId;
                    $objXMLFeed->choose1 = $xmlFeed['choose1'];
                    $objXMLFeed->choose2 = $xmlFeed['choose2'];
                    $objXMLFeed->default =  isset($xmlFeed['default']) ? $xmlFeed['default'] : 1;
                    $objXMLFeed->createtime = $intTime;
                    $objXMLFeed->save();
                }
            }

            if($productType == 0 && $optionType == 1){
                if(!empty( $selectedProductList )){
                    foreach($selectedProductList as $key => $id){
                        $objXMLItem = new XMLItem;
                        $objXMLItem->xml_id = $xmlId;
                        $objXMLItem->product_id = $id;
                        $objXMLItem->save();
                    }
                }
            }

            if($productType == 1 && $optionType == 1){
                if(!empty( $selectedCollectionList )){
                    foreach($selectedCollectionList as $key => $value){
                        $objXMLItem = new XMLItem;
                        $objXMLItem->xml_id = $xmlId;
                        $objXMLItem->collection_id = $value['cid'];
                        $objXMLItem->product_id = $value['pid'];
                        $objXMLItem->save();
                    }
                }
            }
            $this->generateXMLFile($xmlId, false);
            return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.XML_GENERATE_SUCCESSFULLY.code'),
                __('constants.messages.XML_GENERATE_SUCCESSFULLY.msg'),
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

    public function destroy(MassDestroyXmlFeedRequest $request)
    {
          try {
            abort_if(Gate::denies('xmlfeed_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
            $Xmls = XMLS::whereIn('id', request('ids'))->delete();
            return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.RECORD_DELETE_SUCCESSFULLY.code'),
                __('constants.messages.RECORD_DELETE_SUCCESSFULLY.msg'),
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

    public function delete($id){
        try {
            $Xmls = XMLS::where('id', $id)->first();
            if($Xmls){
                $Xmls->delete();
                return $this->successResponse(
                    __('constants.SUCCESS_STATUS'),
                    __('constants.messages.RECORD_DELETE_SUCCESSFULLY.code'),
                    __('constants.messages.RECORD_DELETE_SUCCESSFULLY.msg'),
                );
            } else {
                return $this->successResponse(
                    __('constants.SUCCESS_STATUS'),
                    __('constants.messages.RECORD_NOT_FOUND.code'),
                    __('constants.messages.RECORD_NOT_FOUND.msg'),
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

   public function generateXMLFile($xmlId = 0)
    {
        $client_id = config('client_id');
        $selectProductXML = [];
        $products = [];
        $xmls = XMLS::where('id', $xmlId)->first();
        if($xmls->product_type == 0)
        {
            if($xmls->option_type == 0)
            {
                $products = Product::with('collections','product_variant_options','medias')->get();
            }
            else 
            {
                 $xmlSelectProductId = XMLItem::where('xml_id',$xmlId)->pluck('product_id');
                 $products = Product::whereIn('id', $xmlSelectProductId)->with('collections','product_variant_options','medias')->get();
            }
        }
        else  // collections
        {
            if($xmls->option_type == 0)
            {
                $products = Product::with('collections','product_variant_options','medias')->get();
            }
            else 
            {
                 $xmlSelectProductId = XMLItem::where('xml_id',$xmlId)->pluck('product_id');
                 $products = Product::whereIn('id', $xmlSelectProductId)->with('collections','product_variant_options','medias')->get();
            }
        }

        if(!empty($products))
        {   
            foreach ($products as $key => $objProduct) {
                $mainimage = config('app.url')."/assets/images/no-image.jpg";
                if($objProduct->medias->IsNotEmpty()){
                     $mainimage = config('app.url').$objProduct->medias[0]['image_src'][0];
                }
                $collectionname = '';
                if($objProduct->collections->IsNotEmpty()){
                    $collectionname = $objProduct->collections[0]->title;
                }
                $selectProductXML[$key] =[
                        74 => $objProduct->id,
                        78 => $objProduct->title,
                        79 => $objProduct->slug,
                        80 => $objProduct->description,
                        81 => ($objProduct->description)?'Active':'Draft',
                        82 => $objProduct->schedule_time,
                        83 => $objProduct->price,
                        84 => $objProduct->compare_at_price,
                        85 => $objProduct->cost_per_item,
                        86 => ($objProduct->is_product_charge)?'Yes':'No',
                        87 => $objProduct->sku,
                        88 => $objProduct->barcode,
                        89 => $objProduct->quantity,
                        90 => ($objProduct->is_track)?'Yes':'No',
                        91 => ($objProduct->is_continue_selling)?'Yes':'No',
                        92 => ($objProduct->is_pysical_product)?'Yes':'No',
                        93 => $objProduct->weight,
                        94 => $objProduct->hs_code,
                        95 => $objProduct->min_order_limit,
                        96 => $objProduct->max_order_limit,
                        97 => ($objProduct->is_cod_enabled)?'Yes':'No',
                        98 => ($objProduct->is_size_chart_enabled)?'Yes':'No',
                        99 => 1,
                        100 => ($objProduct->is_special_product)?'Yes':'No',
                        101 => $objProduct->special_price,
                        102 => $objProduct->expiry_date,
                        103 => ($objProduct->special_product_status)?'Yes':'No',
                        104 => $objProduct->seo_title,
                        105 => $objProduct->seo_description,
                        106 => 'in_stock',
                        107 => ($objProduct->is_gift_card)?'Yes':'No',
                        108 => $collectionname,
                        109 => $objProduct->medias,
                        110 => Config::get('app.url')."/product/detail/$objProduct->slug",
                        111 => $mainimage,
                        112 => '1604 - Apparel & Accessories > Clothing',
                        113 => 'new',
                        114 => 'yes',
                ];      
            }
        }
        $XMLFeeds = XMLFeed::where('createtime',1)->get();
        $output = View::make('admin.settings.xmlfeed.createxml', compact('products','selectProductXML','XMLFeeds'))->render();
        $this->checkFolder('public/xml/');
        Storage::disk('public')->put("$client_id/xml/$xmlId.xml", $output);
    }

    public function regenerateXMLFile($id)
    {
        
        try{
            $this->generateXMLFile($id);
        return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.XML_REGENERATE_SUCCESSFULLY.code'),
                __('constants.messages.XML_REGENERATE_SUCCESSFULLY.msg'),
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
