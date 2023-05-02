<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyGiftCardTagRequest;
use App\Http\Requests\StoreGiftCardTagRequest;
use App\Http\Requests\UpdateGiftCardTagRequest;
use App\Models\GiftCardTag;
use App\Models\Product;
use App\Models\Tag;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class GiftCardTagsController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('gift_card_tag_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = GiftCardTag::with(['gift_card', 'tag'])->select(sprintf('%s.*', (new GiftCardTag())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'gift_card_tag_show';
                $editGate = 'gift_card_tag_edit';
                $deleteGate = 'gift_card_tag_delete';
                $crudRoutePart = 'gift-card-tags';

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

            $table->addColumn('tag_title', function ($row) {
                return $row->tag ? $row->tag->title : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'gift_card', 'tag']);

            return $table->make(true);
        }

        $products = Product::get();
        $tags     = Tag::get();
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['name' => trans('cruds.giftCardTag.title')." ".trans('global.listing') ]];
        return view('admin.giftCardTags.index', compact('products', 'tags','breadcrumbs'));
    }

    public function create()
    {
        abort_if(Gate::denies('gift_card_tag_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $gift_cards = Product::all()->pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');

        $tags = Tag::all()->pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['link'=>route('admin.gift-card-tags.index'),'name' => trans('cruds.giftCardTag.title') ],['name' => trans('locale.Add')." ".trans('cruds.giftCardTag.title_singular') ]];
        return view('admin.giftCardTags.create', compact('gift_cards', 'tags','breadcrumbs'));
    }

    public function store(StoreGiftCardTagRequest $request)
    {
        try {
            $giftCardTag = GiftCardTag::create($request->all());
            return redirect()->route('admin.gift-card-tags.index')->with('success','Giftcard tag added successfully!!');          
        } catch (Exception $e) {
            return redirect()->route('admin.gift-card-tags.index')->with('error','Something went wrong!!');              
        }
    }

    public function edit(GiftCardTag $giftCardTag)
    {
        abort_if(Gate::denies('gift_card_tag_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $gift_cards = Product::all()->pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');

        $tags = Tag::all()->pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');

        $giftCardTag->load('gift_card', 'tag');
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['link'=>route('admin.gift-card-tags.index'),'name' => trans('cruds.giftCardTag.title') ],['name' => trans('global.edit')." ".trans('cruds.giftCardTag.title_singular') ]];

        return view('admin.giftCardTags.edit', compact('gift_cards', 'tags', 'giftCardTag','breadcrumbs'));
    }

    public function update(UpdateGiftCardTagRequest $request, GiftCardTag $giftCardTag)
    {
        try {
            $giftCardTag->update($request->all());
            return redirect()->route('admin.gift-card-tags.index')->with('success','Giftcard tag updated successfully!!');
        } catch (Exception $e) {
            return redirect()->route('admin.gift-card-tags.index')->with('error','Something went wrong!!');
        }
    }

    public function show(GiftCardTag $giftCardTag)
    {
        abort_if(Gate::denies('gift_card_tag_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $giftCardTag->load('gift_card', 'tag');
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['link'=>route('admin.gift-card-tags.index'),'name' => trans('cruds.giftCardTag.title') ],['name' => trans('global.show')." ".trans('cruds.giftCardTag.title_singular') ]];

        return view('admin.giftCardTags.show', compact('giftCardTag','breadcrumbs'));
    }

    public function destroy(GiftCardTag $giftCardTag , $id)
    {
        try {
              abort_if(Gate::denies('gift_card_tag_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
              GiftCardTag::where('id',$id)->delete();   
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
            __('constants.messages.GIFTCARD_TAGS_DELETE_SUCCESSFULLY.code'),
            __('constants.messages.GIFTCARD_TAGS_DELETE_SUCCESSFULLY.msg'),
        );
    }

    public function massDestroy(MassDestroyGiftCardTagRequest $request)
    {
        try {
              GiftCardTag::whereIn('id', request('ids'))->delete(); 
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
            __('constants.messages.GIFTCARD_TAGS_DELETE_SUCCESSFULLY.code'),
            __('constants.messages.GIFTCARD_TAGS_DELETE_SUCCESSFULLY.msg'),
        );
    }
}
