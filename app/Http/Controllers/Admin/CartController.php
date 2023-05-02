<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyCartRequest;
use App\Http\Requests\StoreCartRequest;
use App\Http\Requests\UpdateCartRequest;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Cart;
use App\Models\CartDetail;
use App\Models\User;
use App\Models\Product;
use App\Models\Address;
use App\Models\Country;
use App\Models\State;
use Auth;
use Helper;
use Gate;
use Config;

class CartController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('add_cart_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Cart::with(['user'])->select(sprintf('%s.*', (new Cart())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', ' ');
            $table->addColumn('actions', ' ');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'cart_show';
                $editGate = 'cart_edit';
                $deleteGate = 'cart_delete';
                $crudRoutePart = 'carts';

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

        $carts = Cart::get();
        $users = User::get();
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['name' => trans('cruds.cart.title_singular')." ".trans('global.listing') ]];
        return view('admin.cart.index', compact('users','breadcrumbs'));
    }

    public function create(Request $request)
    {
        abort_if(Gate::denies('add_cart_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $list = $data = [];
        $searchUserLimit = Config::get('SEARCH_USER_LIMIT');
        $searchProductLimit = Config::get('SEARCH_PRODUCT_LIMIT');
        $data['defaultCountry'] = Config::get('DEFAULT_COUNTRY');
        $type = 'Add';        
        $list['objUsers'] = User::select('id','name','last_name','email','mobile')->limit($searchUserLimit)->get();
        $objProducts = Product::select('id','title','quantity','slug','price','compare_at_price','is_product_variant',
            'is_continue_selling','max_order_limit','min_order_limit')
                ->with([
                'medias' => function($media){
                    $media->select('client_id','product_id','src');
                }, 
                'product_variant_options' => function ($variant) {
                    $variant->select('id','product_id','variant_option_1_id','variant_option_2_id','variant_option_3_id','price','compare_at_price','is_continue_selling','max_order_limit','min_order_limit');
                }, 
                'product_variant_options.variant_media' => function ($variantmedia) {
                    $variantmedia->select('client_id','product_variant_id','src'); 
                }])
                ->limit($searchProductLimit)
                ->get();

        $list['objProducts'] = $this->handleProductSelect($objProducts);

        $list['countries'] = Country::all()->pluck('name', 'id');
        $list['states'] = State::where('country_id', $data['defaultCountry'])->pluck('name', 'id');

        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['link'=>route('admin.cart.index'), 'name' => trans('cruds.cart.title')], ['name' => trans('locale.Add')." ".trans('cruds.cart.title_singular') ]];
        return view('admin.cart.createupdate', compact('list','breadcrumbs','type','data'));
    }

    public function store(StoreCartRequest $request)
    {
        try
        {
            $params = collect($request->all());
            $objUser = User::where('id',$params['user_id'])->first();
            $objShippingAddress = $this->handleAddress($params,0,'store');
            $objBillingAddress = $this->handleAddress($params,1,'store');


            $objCart = new Cart;
            $objCart->user_id = $objUser->id;
            $objCart->mac_id = Helper::getMacAddress();
            $objCart->addresses_id = $objBillingAddress->id;
            $objCart->shipping_address_id = $objShippingAddress->id;
            $objCart->save();

            $objAddress = Address::where(['store_status'=>1,'user_id'=>Config::get('client_id'),'is_default'=>1])->first();
            foreach($params['products'] as $objProduct)
            {
                $objCartDetails = new CartDetail;
                $objCartDetails->cart_id = $objCart->id;
                $objCartDetails->product_id = $objProduct['id'];
                $objCartDetails->store_id = $objAddress->id;
                if($objProduct['product_variant_option_id'] > 0)
                {
                    $objCartDetails->variant_option_id = $objProduct['product_variant_option_id'];
                }
                $objCartDetails->quantity = $objProduct['quantity'];
                $objCartDetails->save();
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
        if ($request->ajax()) {
                $url = route('admin.carts.edit',['cart' => $objCartDetails->cart_id]);
                return $this->successResponse(
                __('constants.SUCCESS_STATUS'),
                __('constants.messages.CART_ADDED_SUCCESSFULLY.code'),
                __('constants.messages.CART_ADDED_SUCCESSFULLY.msg'),
                  ['url'=>$url]
                );
        }   
        else
        {
            return redirect('/admin/carts')->with('message', __('constants.messages.CART_ADDED_SUCCESSFULLY.msg'));
        }
    }

    public function edit($cart_id)
    {
        abort_if(Gate::denies('add_cart_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $list = $data = [];
        $searchUserLimit = Config::get('SEARCH_USER_LIMIT');
        $searchProductLimit = Config::get('SEARCH_PRODUCT_LIMIT');
        $data['defaultCountry'] = Config::get('DEFAULT_COUNTRY');
        $type = 'Edit';
        $objCartDetails = CartDetail::where('cart_id',$cart_id)->get();
        $data['cart_id'] = $cart_id;
        $objCartDetailsProductId = $objCartDetails->pluck('product_id')->toArray();
        $objCartDetailsProductVariantId = $objCartDetails->pluck('variant_option_id')->toArray();

        $objProducts = Product::select('id','title','quantity','slug','price','compare_at_price','is_product_variant','is_continue_selling','max_order_limit','min_order_limit')
                ->with([
                'medias' => function($media){
                    $media->select('client_id','product_id','src');
                }, 
                'product_variant_options' => function ($variant) {
                    $variant->select('id','product_id','variant_option_1_id','variant_option_2_id','variant_option_3_id','price','compare_at_price','is_continue_selling','max_order_limit','min_order_limit'); 
                }, 
               'product_variant_options.variant_media' => function ($variantmedia) {
                        $variantmedia->select('client_id','product_variant_id','src'); 
                }])
                ->limit($searchProductLimit)
                ->get();

        $objSelectionProducts = Product::select('id','title','quantity','slug','price','compare_at_price','is_product_variant','is_continue_selling','max_order_limit','min_order_limit')
            ->with([
            'medias' => function($media){
                $media->select('client_id','product_id','src');
                }, 
            'product_variant_options' => function ($variant) {
                    $variant->select('id','product_id','variant_option_1_id','variant_option_2_id','variant_option_3_id','price','compare_at_price','is_continue_selling','max_order_limit','min_order_limit'); 
                }, 
            'product_variant_options.variant_media' => function ($variantmedia) {
                    $variantmedia->select('client_id','product_variant_id','src'); 
            }])
            ->whereIn('id',$objCartDetailsProductId)
            ->limit($searchProductLimit)
            ->get();

        $objSelectedProductId = $objSelectionProducts->pluck('id');
        $objCart = Cart::with('address','shippingaddress')->where('id',$cart_id)->first();
        $objCartUserId = $objCart->user_id;
        $data['objCartUser'] = User::select('id','name','last_name','email','mobile')->where('id',$objCartUserId)->first();
        $list['objUsers'] = User::select('id','name','last_name','email','mobile')->limit($searchUserLimit)->get();
        $list['objProducts'] = $this->handleProductSelect($objProducts);
        $objSelectionProducts = $this->handleProductSelect($objSelectionProducts);
        $data['objSelectionProducts'] = [];
        foreach($objSelectionProducts as $objSelectionProduct){
            if(in_array($objSelectionProduct['id'],$objCartDetailsProductId)){
                if($objSelectionProduct['product_variant_option_id'] > 0){
                    if(in_array($objSelectionProduct['product_variant_option_id'],$objCartDetailsProductVariantId)){
                    //Variant Product
                        $cartIndex = $objCartDetails->search(function($objCartDetail) use($objSelectionProduct) {
                            return $objCartDetail->product_id == $objSelectionProduct['id'] && $objCartDetail->variant_option_id == $objSelectionProduct['product_variant_option_id'];
                        });
                        array_push($data['objSelectionProducts'],
                            [
                                'id' => $objSelectionProduct['id'],
                                'product_variant_option_id' => $objSelectionProduct['product_variant_option_id'],
                                'title' => $objSelectionProduct['title'],
                                'quantity' => $objCartDetails[$cartIndex]['quantity'],
                                'slug' => $objSelectionProduct['slug'],
                                'price' => $objSelectionProduct['price'],
                                'img_src'=> $objSelectionProduct['img_src'],
                                'compareprice' => $objSelectionProduct['compareprice'],
                                'stock_status'=>$objSelectionProduct['stock_status'],
                                'cart_details_id'=>$objCartDetails[$cartIndex]['id'],
                                'isContinueSelling' => $objSelectionProduct['isContinueSelling'],
                                'maxOrderLimit' => $objSelectionProduct['maxOrderLimit'],
                                'minOrderLimit' => $objSelectionProduct['minOrderLimit']
                            ]);
                    }

                }
                else{
                    //NonVariant Product
                    $cartIndex = $objCartDetails->search(function($objCartDetail) use($objSelectionProduct) {
                        return $objCartDetail->product_id == $objSelectionProduct['id'];
                    });
                    array_push($data['objSelectionProducts'],
                            [
                                'id' => $objSelectionProduct['id'],
                                'title' => $objSelectionProduct['title'],
                                'quantity' => $objCartDetails[$cartIndex]['quantity'],
                                'slug' => $objSelectionProduct['slug'],
                                'price' => $objSelectionProduct['price'],
                                'img_src'=>$objSelectionProduct['img_src'] ,
                                'compareprice' => $objSelectionProduct['compareprice'],
                                'stock_status'=>$objSelectionProduct['stock_status'],
                                'cart_details_id'=>$objCartDetails[$cartIndex]['id'],
                                'isContinueSelling' => $objSelectionProduct['isContinueSelling'],
                                'maxOrderLimit' => $objSelectionProduct['maxOrderLimit'],
                                'minOrderLimit' => $objSelectionProduct['minOrderLimit']
                            ]);
                }
            }
        }
        $data['objSelectionProducts'] = collect($data['objSelectionProducts'])->sortBy('cart_details_id')->values()->toArray();
        $list['countries'] = Country::all()->pluck('name', 'id');
        $list['billingStates'] = $list['shippingStates'] = State::where('country_id', $data['defaultCountry'])->pluck('name', 'id');
        $data['shippingAddress'] = $data['billingAddress']  = [];
        if($objCart->addresses_id > 0)
        {
             $list['billingStates'] = State::where('country_id', $objCart->address->country_id)->pluck('name', 'id');
             $data['billingAddress'] = Address::whereId($objCart->addresses_id)->first();
        }
        if($objCart->shipping_address_id > 0)
        {
             $list['shippingStates'] = State::where('country_id', $objCart->shippingaddress->country_id)->pluck('name', 'id');
             $data['shippingAddress'] = Address::whereId($objCart->shipping_address_id)->first();
        }
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['link'=>route('admin.cart.index'), 'name' => trans('cruds.cart.title')], ['name' => trans('locale.Edit')." ".trans('cruds.cart.title_singular') ]];
        return view('admin.cart.createupdate', compact('list', 'data','type','breadcrumbs'));
    }

    public function update(UpdateCartRequest $request,$cart_id)
    {
        try{
                $authUser = Auth::user();
                $params = collect($request->all());
                $objUser = User::where('id',$params['user_id'])->first();
                $objShippingAddress = $this->handleAddress($params,0,'update');
                $objBillingAddress = $this->handleAddress($params,1,'update');

                $objCart = Cart::where('id',$cart_id)->first(); 
                if(empty($objCart)){
                    $objCart = new Cart;                    
                }
                $objCart->user_id = $objUser->id;
                $objCart->mac_id = Helper::getMacAddress();
                $objCart->addresses_id = $objBillingAddress->id;
                $objCart->shipping_address_id = $objShippingAddress->id;
                $objCart->save();

            if(empty($params['products'])){
                CartDetail::where('cart_id', $cart_id)->delete();
                Cart::where('id', $cart_id)->delete();
            }
            else{
            $objCartDetails = CartDetail::where('cart_id',$cart_id)->get();
            $objCartDetailsId = $objCartDetails->pluck('id')->toArray();
            $newObjCartDetailsId = collect($params['products'])->pluck('cart_details_id')->toArray();
            $objRemoveCartDetailsId = array_diff($objCartDetailsId,$newObjCartDetailsId);
            CartDetail::whereIn('id', $objRemoveCartDetailsId)->delete();
            $cart_new_id = $cart_id;
            $objAddress = Address::where(['store_status'=>1,'user_id'=>Config::get('client_id'),'is_default'=>1])->first();
                    Cart::where('id',(int) $cart_id)->update(['user_id' => $params['user_id']]);
                    foreach($params['products'] as $key => $objProduct)
                    {
                        $objCartDetail = CartDetail::where('id',$objProduct['cart_details_id'])->first();
                        if(empty($objCartDetail)){
                            $objCartDetail = new CartDetail;
                        }
                        $objCartDetail->cart_id = (int) $cart_new_id;
                        $objCartDetail->product_id = $objProduct['id'];
                        $objCartDetail->store_id = $objAddress->id;
                        if($objProduct['product_variant_option_id'] > 0)
                        {
                            $objCartDetail->variant_option_id = $objProduct['product_variant_option_id'];
                        }
                        $objCartDetail->quantity = $objProduct['quantity'];
                        $objCartDetail->save();
                    }
                // }
            }
        }
        catch (\Exception $e) {
            return $this->errorResponse(
                __('constants.ERROR_STATUS'),
                __('constants.errors.SOMETHING_WRONG.code'),
                __('constants.errors.SOMETHING_WRONG.msg'),
                $e->getMessage()
            );
        }
        if ($request->ajax()) {
            $url = route('admin.carts.index');
            return $this->successResponse(
            __('constants.SUCCESS_STATUS'),
            __('constants.messages.CART_UPDATE_SUCCESSFULLY.code'),
            __('constants.messages.CART_UPDATE_SUCCESSFULLY.msg'),
            ['url'=>$url]
        );
    }
        else {
                return redirect('/admin/cart')->with('message', __('constants.messages.CART_UPDATE_SUCCESSFULLY.msg'));
            }
    }

    public function destroy(Cart $carts,$id)
    {
        try {
              abort_if(Gate::denies('add_cart_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
              Cart::where('id',$id)->forcedelete();   
              CartDetail::where('cart_id',$id)->forcedelete();
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
            __('constants.messages.CART_DELETE_SUCCESSFULLY.code'),
            __('constants.messages.CART_DELETE_SUCCESSFULLY.msg'),
        );
    }

    public function massDestroy(MassDestroyCartRequest $request)
    {
        try {
              Cart::whereIn('id', request('ids'))->forcedelete(); 
              CartDetail::whereIn('cart_id', request('ids'))->forcedelete(); 
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
            __('constants.messages.CART_DELETE_SUCCESSFULLY.code'),
            __('constants.messages.CART_DELETE_SUCCESSFULLY.msg'),
        );
    }

    public function handleAddress($params,$shippingStatus, $actiontype){
        $authUser = Auth::user();
        $objAddressSave = Address::where(['user_id'=>$params['user_id'],'address'=>$params['shipping_address']['address'],'address_2'=>$params['shipping_address']['address_2'],'country_id'=>$params['shipping_address']['country_id'],'state_id'=>$params['shipping_address']['state_id'],'city_name'=>$params['shipping_address']['city_name'],'store_status'=>0,'status'=>0])->first();
        if($actiontype=='update')
        {
            $objAddressSave = Address::where(['id'=>$params['shipping_address']['id']])->first();
        }
        $first_name = $params['shipping_address']['first_name'];
        $last_name = $params['shipping_address']['last_name'];
        $address = $params['shipping_address']['address'];
        $address2 = $params['shipping_address']['address_2'];
        $email = $params['shipping_address']['email'];
        $mobile = $params['shipping_address']['mobile'];
        $status = $shippingStatus;
        $postal_code = $params['shipping_address']['postal_code'];
        $country_id = $params['shipping_address']['country_id'];
        $state_id = $params['shipping_address']['state_id'];
        $city_name = $params['shipping_address']['city_name'];
        if($shippingStatus){
            //billing address
            $objAddressSave = Address::where(['user_id'=>$params['user_id'],'address'=>$params['billing_address']['address'],'address_2'=>$params['billing_address']['address_2'],'country_id'=>$params['billing_address']['country_id'],'state_id'=>$params['billing_address']['state_id'],'city_name'=>$params['billing_address']['city_name'],'store_status'=>0,'status'=>1])->first();
            if($actiontype=='update')
            {
             $objAddressSave = Address::where(['id'=>$params['billing_address']['id']])->first();
            }
                $first_name = $params['billing_address']['first_name'];
                $last_name = $params['billing_address']['last_name'];
                $address = $params['billing_address']['address'];
                $address2 = $params['billing_address']['address_2'];
                $email = $params['billing_address']['email'];
                $mobile = $params['billing_address']['mobile'];
                $status = $shippingStatus;
                $postal_code = $params['billing_address']['postal_code'];
                $country_id = $params['billing_address']['country_id'];
                $state_id = $params['billing_address']['state_id'];
                $city_name = $params['billing_address']['city_name'];
        }
                if(empty($objAddressSave)) {
                    $objAddressSave = new Address;
                    }
                    $objAddressSave->user_id = $params['user_id']; 
                    $objAddressSave->mac_id = Helper::getMacAddress(); 
                    $objAddressSave->first_name = $first_name; 
                    $objAddressSave->last_name = $last_name; 
                    $objAddressSave->address = $address; 
                    $objAddressSave->address_2 = $address2; 
                    $objAddressSave->email = $email; 
                    $objAddressSave->mobile = $mobile; 
                    $objAddressSave->status = $status; 
                    $objAddressSave->postal_code = $postal_code; 
                    $objAddressSave->country_id = $country_id; 
                    $objAddressSave->state_id = $state_id; 
                    $objAddressSave->city_name = $city_name; 
                    $objAddressSave->save();

                    return  $objAddressSave;

    }
}