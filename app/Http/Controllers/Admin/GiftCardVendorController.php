<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyGiftCardVendorRequest;
use App\Http\Requests\StoreGiftCardVendorRequest;
use App\Http\Requests\UpdateGiftCardVendorRequest;
use App\Models\GiftCardVendor;
use App\Models\Product;
use App\Models\Vendor;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class GiftCardVendorController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('gift_card_vendor_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = GiftCardVendor::with(['gift_card', 'vendor'])->select(sprintf('%s.*', (new GiftCardVendor())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'gift_card_vendor_show';
                $editGate = 'gift_card_vendor_edit';
                $deleteGate = 'gift_card_vendor_delete';
                $crudRoutePart = 'gift-card-vendors';

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
            $table->addColumn('gift_card_title', function ($row) {
                return $row->gift_card ? $row->gift_card->title : '';
            });

            $table->addColumn('vendor_name', function ($row) {
                return $row->vendor ? $row->vendor->name : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'gift_card', 'vendor']);

            return $table->make(true);
        }

        $products = Product::get();
        $vendors  = Vendor::get();
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['name' => trans('cruds.giftCardVendor.title')." ".trans('global.listing') ]];
        return view('admin.giftCardVendors.index', compact('products', 'vendors','breadcrumbs'));
    }

    public function create()
    {
        abort_if(Gate::denies('gift_card_vendor_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $gift_cards = Product::all()->pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');

        $vendors = Vendor::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['link'=>route('admin.gift-card-vendors.index'),'name' => trans('cruds.giftCardVendor.title') ],['name' => trans('locale.Add')." ".trans('cruds.giftCardVendor.title_singular') ]];
        return view('admin.giftCardVendors.create', compact('gift_cards', 'vendors','breadcrumbs'));
    }

    public function store(StoreGiftCardVendorRequest $request)
    {
        try {
            $giftCardVendor = GiftCardVendor::create($request->all());
            return redirect()->route('admin.gift-card-vendors.index')->with('success','Giftcard vendor added successfully!!');
        } catch (Exception $e) {
            return redirect()->route('admin.gift-card-vendors.index')->with('error','Something went wrong!!');
        }
    }

    public function edit(GiftCardVendor $giftCardVendor)
    {
        abort_if(Gate::denies('gift_card_vendor_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $gift_cards = Product::all()->pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');

        $vendors = Vendor::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $giftCardVendor->load('gift_card', 'vendor');
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['link'=>route('admin.gift-card-vendors.index'),'name' => trans('cruds.giftCardVendor.title') ],['name' => trans('global.edit')." ".trans('cruds.giftCardVendor.title_singular') ]];
        return view('admin.giftCardVendors.edit', compact('gift_cards', 'vendors', 'giftCardVendor','breadcrumbs'));
    }

    public function update(UpdateGiftCardVendorRequest $request, GiftCardVendor $giftCardVendor)
    {
        try {
            $giftCardVendor->update($request->all());
            return redirect()->route('admin.gift-card-vendors.index')->with('success','Giftcard vendor updated successfully!!');
        } catch (Exception $e) {
            return redirect()->route('admin.gift-card-vendors.index')->with('error','Something went wrong!!');    
        }
    }

    public function show(GiftCardVendor $giftCardVendor)
    {
        abort_if(Gate::denies('gift_card_vendor_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $giftCardVendor->load('gift_card', 'vendor');
         $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['link'=>route('admin.gift-card-vendors.index'),'name' => trans('cruds.giftCardVendor.title') ],['name' => trans('global.show')." ".trans('cruds.giftCardVendor.title_singular') ]];

        return view('admin.giftCardVendors.show', compact('giftCardVendor','breadcrumbs'));
    }

    public function destroy(GiftCardVendor $giftCardVendor,$id)
    {
        try {
              abort_if(Gate::denies('gift_card_vendor_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
              GiftCardVendor::where('id',$id)->delete();   
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
            __('constants.messages.GIFTCARD_VENDOR_DELETE_SUCCESSFULLY.code'),
            __('constants.messages.GIFTCARD_VENDOR_DELETE_SUCCESSFULLY.msg'),
        );
    }

    public function massDestroy(MassDestroyGiftCardVendorRequest $request)
    {
        try {
              GiftCardVendor::whereIn('id', request('ids'))->delete(); 
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
            __('constants.messages.GIFTCARD_VENDOR_DELETE_SUCCESSFULLY.code'),
            __('constants.messages.GIFTCARD_VENDOR_DELETE_SUCCESSFULLY.msg'),
        );
    }
}
