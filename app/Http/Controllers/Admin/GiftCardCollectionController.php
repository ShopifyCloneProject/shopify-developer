<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyGiftCardCollectionRequest;
use App\Http\Requests\StoreGiftCardCollectionRequest;
use App\Http\Requests\UpdateGiftCardCollectionRequest;
use App\Models\Collection;
use App\Models\GiftCardCollection;
use App\Models\Product;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class GiftCardCollectionController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('gift_card_collection_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = GiftCardCollection::with(['gift_card', 'collection'])->select(sprintf('%s.*', (new GiftCardCollection())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'gift_card_collection_show';
                $editGate = 'gift_card_collection_edit';
                $deleteGate = 'gift_card_collection_delete';
                $crudRoutePart = 'gift-card-collections';

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

            $table->addColumn('collection_title', function ($row) {
                return $row->collection ? $row->collection->title : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'gift_card', 'collection']);

            return $table->make(true);
        }

        $products    = Product::get();
        $collections = Collection::get();
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['name' => trans('cruds.giftCardCollection.title')." ".trans('global.listing') ]];
        return view('admin.giftCardCollections.index', compact('products', 'collections','breadcrumbs'));
    }

    public function create()
    {
        abort_if(Gate::denies('gift_card_collection_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $gift_cards = Product::all()->pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');

        $collections = Collection::all()->pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['link'=>route('admin.gift-card-collections.index'),'name' => trans('cruds.giftCardCollection.title') ],['name' => trans('locale.Add')." ".trans('cruds.giftCardCollection.title_singular') ]]; 

        return view('admin.giftCardCollections.create', compact('gift_cards', 'collections','breadcrumbs'));
    }

    public function store(StoreGiftCardCollectionRequest $request)
    {
        try {
            $giftCardCollection = GiftCardCollection::create($request->all());
            return redirect()->route('admin.gift-card-collections.index')->with('success','Giftcard collection added successfully!!');
        } catch (Exception $e) {
            return redirect()->route('admin.gift-card-collections.index')->with('error','Something went wrong!!');    
        }
    }

    public function edit(GiftCardCollection $giftCardCollection)
    {
        abort_if(Gate::denies('gift_card_collection_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $gift_cards = Product::all()->pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');

        $collections = Collection::all()->pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');

        $giftCardCollection->load('gift_card', 'collection');
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['link'=>route('admin.gift-card-collections.index'),'name' => trans('cruds.giftCardCollection.title') ],['name' => trans('global.edit')." ".trans('cruds.giftCardCollection.title_singular') ]]; 

        return view('admin.giftCardCollections.edit', compact('gift_cards', 'collections', 'giftCardCollection','breadcrumbs'));
    }

    public function update(UpdateGiftCardCollectionRequest $request, GiftCardCollection $giftCardCollection)
    {
        try {
            $giftCardCollection->update($request->all());
            return redirect()->route('admin.gift-card-collections.index')->with('success','Giftcard collection updated successfully!!');
        } catch (Exception $e) {
            return redirect()->route('admin.gift-card-collections.index')->with('error','Something went wrong!!');
        }
    }

    public function show(GiftCardCollection $giftCardCollection)
    {
        abort_if(Gate::denies('gift_card_collection_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $giftCardCollection->load('gift_card', 'collection');
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['link'=>route('admin.gift-card-collections.index'),'name' => trans('cruds.giftCardCollection.title') ],['name' => trans('global.show')." ".trans('cruds.giftCardCollection.title_singular') ]]; 

        return view('admin.giftCardCollections.show', compact('giftCardCollection','breadcrumbs'));
    }

    public function destroy(GiftCardCollection $giftCardCollection,$id)
    {
        try {
              abort_if(Gate::denies('gift_card_collection_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
              GiftCardCollection::where('id',$id)->delete();   
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
            __('constants.messages.GIFTCARD_COLLECTION_DELETE_SUCCESSFULLY.code'),
            __('constants.messages.GIFTCARD_COLLECTION_DELETE_SUCCESSFULLY.msg'),
        );
    }

    public function massDestroy(MassDestroyGiftCardCollectionRequest $request)
    {
        try {
              GiftCardCollection::whereIn('id', request('ids'))->delete(); 
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
            __('constants.messages.GIFTCARD_COLLECTION_DELETE_SUCCESSFULLY.code'),
            __('constants.messages.GIFTCARD_COLLECTION_DELETE_SUCCESSFULLY.msg'),
        );
    }
}
