<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Cart;
use App\Models\CartDetail;
use App\Models\User;
use App\Models\Product;
use App\Models\ProductMedium;
use App\Models\ProductVariantOption;
use App\Models\VariantMedium;
use App\Models\Address;
use Helper;
use Gate;
use Config;

class AbandoneCheckoutsController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('abandone_checkout_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Cart::with(['user'])->where('payment_status','>','0')->select(sprintf('%s.*', (new Cart())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', ' ');
            $table->addColumn('actions', ' ');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'cart_show';
                $editGate = 'cart_edit';
                $deleteGate = 'cart_delete';
                $crudRoutePart = 'abandonecheckouts';

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
            $table->addColumn('user', function ($row) {
                return $row->user ? $row->user->name : '';
            });

            $table->addColumn('email', function ($row) {
                return $row->user ? $row->user->email : '';
            });

            $table->addColumn('mobile', function ($row) {
                return $row->user ? $row->user->mobile : '';
            });

            $table->rawColumns(['actions', 'placeholder','user']);

            return $table->make(true);
        }

        $users = User::get();
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['name' => trans('cruds.abandonecheckouts.title_singular')." ".trans('global.listing') ]];
        return view('admin.abandonecheckouts.index', compact('users','breadcrumbs'));
    }

    public function show($cart_id)
    {
        abort_if(Gate::denies('abandone_checkout_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $searchProductLimit = Config::get('SEARCH_PRODUCT_LIMIT');
        $list = $data = [];
        $objAddress = Address::where(['store_status'=>1,'user_id'=>Config::get('client_id'),'is_default'=>1])->first();
        $objCartDetails = CartDetail::where('cart_id',$cart_id)->get();
        $data['cart_id'] = $cart_id;
        $objCartDetailsProductId = $objCartDetails->pluck('product_id')->toArray();
        $objCartDetailsProductVariantId = $objCartDetails->pluck('variant_option_id')->toArray();
        
        $addressId = Cart::where('id',$cart_id)->where('payment_status','>','0')->first()->addresses_id;
        $shippingAddressId = Cart::where('id',$cart_id)->first()->shipping_address_id;
        
        if(!($cart_id)){
            $data['address'] = $data['shippingAddress'] = '';
        }
        $data['address'] = Address::where('id',$addressId)->first();
        $data['shippingAddress'] = Address::where('id',$shippingAddressId)->first();

        $objSelectionProducts = Product::select('id','title','quantity','slug','price','compare_at_price','is_product_variant')
                ->with([
                'medias' => function($media){
                    $media->select('client_id','product_id','src');
                    }, 
                'product_variant_options' => function ($variant) use($objCartDetailsProductVariantId) {
                        $variant->select('id','product_id','variant_option_1_id','variant_option_2_id','variant_option_3_id','price','compare_at_price');
                        $variant->whereIn('id', $objCartDetailsProductVariantId);
                    }, 
                'product_variant_options.variant_media' => function ($variantmedia) {
                        $variantmedia->select('client_id','src'); 
                },
                'product_variant_options.variant_option_1','product_variant_options.variant_option_2','product_variant_options.variant_option_3'
            ])->whereIn('id',$objCartDetailsProductId)
                ->limit($searchProductLimit)
                ->get();

        $data['objSelectionProducts'] = [];
            foreach($objSelectionProducts as $objSelectionProduct)
            {
                $title = $objSelectionProduct->title;
                $slug = $objSelectionProduct->slug;

                $img_src = (!empty($objSelectionProduct->medias[0])) ? $objSelectionProduct->medias[0]->image_src[2] : '';
                if($objSelectionProduct->is_product_variant){

                    if($objSelectionProduct->product_variant_options->isNotEmpty())
                    {
                        foreach($objSelectionProduct->product_variant_options as $objProductVariantOptions)
                        {   
                            $variantOption = $this->getVariantOptions($objProductVariantOptions->variant_option_1,$objProductVariantOptions->variant_option_2,$objProductVariantOptions->variant_option_3);
                            $title = $title. " (" .$variantOption.")";

                            $img_src = (!empty($objSelectionProduct->medias[0])) ? $objSelectionProduct->medias[0]->image_src[2] : '';

                            $cartIndex = $objCartDetails->search(function($objCartDetail) use($objProductVariantOptions) {
                            return $objCartDetail->product_id == $objProductVariantOptions->product_id && $objCartDetail->product_variant_options_id == $objProductVariantOptions->id;

                            });
                            array_push($data['objSelectionProducts'],
                            [
                                'id' => $objCartDetails[$cartIndex]['product_id'],
                                'product_variant_option_id' => $objCartDetails[$cartIndex]['variant_option_id'],
                                'title' => $title,
                                'quantity' => $objCartDetails[$cartIndex]['quantity'],
                                'slug' => $slug,
                                'price' => $objProductVariantOptions->price,
                                'img_src'=>$img_src,
                                'compareprice' => $objProductVariantOptions->compare_at_price,
                                'stock_status'=>$objSelectionProduct['stock_status'],
                                'cart_details_id'=>$objCartDetails[$cartIndex]['id']
                            ]);
                        }
                    }
                }
                else{
                    //NonVariant Product
                    $cartIndex = $objCartDetails->search(function($objCartDetail) use($objSelectionProduct) {
                        return $objCartDetail->product_id == $objSelectionProduct->id;
                    });
                    array_push($data['objSelectionProducts'],
                    [
                        'id' => $objCartDetails[$cartIndex]['product_id'],
                        'title' => $title,
                        'quantity' => $objCartDetails[$cartIndex]['quantity'],
                        'slug' => $slug,
                        'price' => $objSelectionProduct['price'],
                        'img_src'=>$img_src,
                        'compareprice' => $objSelectionProduct['compare_at_price'],
                        'stock_status'=>$objSelectionProduct['stock_status'],
                        'cart_details_id'=>$objCartDetails[$cartIndex]['id']
                    ]);
                }
            }
        $data['objSelectionProducts'] = collect($data['objSelectionProducts'])->sortBy('cart_details_id')->values()->toArray();
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['link'=>route('admin.abandonecheckouts.index'),'name' => trans('cruds.abandonecheckouts.title') ],['name' => trans('global.show')." ".trans('cruds.abandonecheckouts.title_singular') ]];

        return view('admin.abandonecheckouts.show', compact('breadcrumbs','list','data'));
    }
}