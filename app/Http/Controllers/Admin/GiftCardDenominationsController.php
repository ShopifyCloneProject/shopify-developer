<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyGiftCardDenominationRequest;
use App\Http\Requests\StoreGiftCardDenominationRequest;
use App\Http\Requests\UpdateGiftCardDenominationRequest;
use App\Models\GiftCardDenomination;
use App\Models\Product;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class GiftCardDenominationsController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('gift_card_denomination_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = GiftCardDenomination::with(['product'])->select(sprintf('%s.*', (new GiftCardDenomination())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'gift_card_denomination_show';
                $editGate = 'gift_card_denomination_edit';
                $deleteGate = 'gift_card_denomination_delete';
                $crudRoutePart = 'gift-card-denominations';

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
            $table->addColumn('product_title', function ($row) {
                return $row->product ? $row->product->title : '';
            });

            $table->editColumn('value', function ($row) {
                return $row->value ? $row->value : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'product']);

            return $table->make(true);
        }

        $products = Product::get();
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['name' => trans('cruds.giftCardDenomination.title')." ".trans('global.listing') ]];

        return view('admin.giftCardDenominations.index', compact('products','breadcrumbs'));
    }

    public function create()
    {
        abort_if(Gate::denies('gift_card_denomination_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $products = Product::all()->pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['link'=>route('admin.gift-card-denominations.index'),'name' => trans('cruds.giftCardDenomination.title_singular') ],['name' => trans('locale.Add')." ".trans('cruds.giftCardDenomination.title_singular') ]];

        return view('admin.giftCardDenominations.create', compact('products','breadcrumbs'));
    }

    public function store(StoreGiftCardDenominationRequest $request)
    {
        try {
            $giftCardDenomination = GiftCardDenomination::create($request->all());
            return redirect()->route('admin.gift-card-denominations.index')->with('success','Giftcard denominations added successfully!!'); 
        } catch (Exception $e) {
            return redirect()->route('admin.gift-card-denominations.index')->with('error','Something went wrong!!'); 
            
        }
    }

    public function edit(GiftCardDenomination $giftCardDenomination)
    {
        abort_if(Gate::denies('gift_card_denomination_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $products = Product::all()->pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');

        $giftCardDenomination->load('product');
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['link'=>route('admin.gift-card-denominations.index'),'name' => trans('cruds.giftCardDenomination.title_singular') ],['name' => trans('global.edit')." ".trans('cruds.giftCardDenomination.title_singular') ]];
        return view('admin.giftCardDenominations.edit', compact('products', 'giftCardDenomination','breadcrumbs'));
    }

    public function update(UpdateGiftCardDenominationRequest $request, GiftCardDenomination $giftCardDenomination)
    {
        try {
            $giftCardDenomination->update($request->all());
            return redirect()->route('admin.gift-card-denominations.index')->with('success','Giftcard denominations updated successfully!!');
        } catch (Exception $e) {
            return redirect()->route('admin.gift-card-denominations.index')->with('error','Something went wrong!!');
            
        }
    }

    public function show(GiftCardDenomination $giftCardDenomination)
    {
        abort_if(Gate::denies('gift_card_denomination_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $giftCardDenomination->load('product');
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['link'=>route('admin.gift-card-denominations.index'),'name' => trans('cruds.giftCardDenomination.title_singular') ],['name' => trans('global.show')." ".trans('cruds.giftCardDenomination.title_singular') ]];

        return view('admin.giftCardDenominations.show', compact('giftCardDenomination','breadcrumbs'));
    }

    public function destroy(GiftCardDenomination $giftCardDenomination , $id)
    {
        try {
              abort_if(Gate::denies('gift_card_denomination_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
              GiftCardDenomination::where('id',$id)->delete();   
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
            __('constants.messages.GIFTCARD_DENOMINATIONS_DELETE_SUCCESSFULLY.code'),
            __('constants.messages.GIFTCARD_DENOMINATIONS_DELETE_SUCCESSFULLY.msg'),
        );
    }

    public function massDestroy(MassDestroyGiftCardDenominationRequest $request)
    {
        try {
              GiftCardDenomination::whereIn('id', request('ids'))->delete(); 
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
            __('constants.messages.GIFTCARD_DENOMINATIONS_DELETE_SUCCESSFULLY.code'),
            __('constants.messages.GIFTCARD_DENOMINATIONS_DELETE_SUCCESSFULLY.msg'),
        );
    }
}
