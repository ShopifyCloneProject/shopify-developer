<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyGiftCardIssueRequest;
use App\Http\Requests\StoreGiftCardIssueRequest;
use App\Http\Requests\UpdateGiftCardIssueRequest;
use App\Models\Currency;
use App\Models\GiftCardIssue;
use App\Models\Product;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class GiftCardIssueController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('gift_card_issue_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        if ($request->ajax()) {
            $query = GiftCardIssue::with(['user', 'gift_card', 'currency'])->select(sprintf('%s.*', (new GiftCardIssue())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'gift_card_issue_show';
                $editGate = 'gift_card_issue_edit';
                $deleteGate = 'gift_card_issue_delete';
                $crudRoutePart = 'gift-card-issues';

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
            $table->editColumn('code', function ($row) {
                return $row->code ? $row->code : '';
            });
            $table->editColumn('status', function ($row) {
                return $row->status ? GiftCardIssue::STATUS_RADIO[$row->status] : '';
            });

            $table->editColumn('remaining_value', function ($row) {
                return $row->remaining_value ? $row->remaining_value : '';
            });
            $table->editColumn('initial_value', function ($row) {
                return $row->initial_value ? $row->initial_value : '';
            });
            $table->editColumn('expiration_type', function ($row) {
                return $row->expiration_type ? GiftCardIssue::EXPIRATION_TYPE_RADIO[$row->expiration_type] : '';
            });

            $table->editColumn('note', function ($row) {
                return $row->note ? $row->note : '';
            });
            $table->editColumn('enabled', function ($row) {
                return GiftCardIssue::ENABLED_RADIO[$row->enabled];
            });

            $table->addColumn('user_email', function ($row) {
                return $row->user ? $row->user->email : '';
            });

            $table->addColumn('gift_card_title', function ($row) {
                return $row->gift_card ? $row->gift_card->title : '';
            });

            $table->addColumn('currency_currency', function ($row) {
                return $row->currency ? $row->currency->currency : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'user', 'gift_card', 'currency']);

            return $table->make(true);
        }

        $users      = User::get();
        $products   = Product::get();
        $currencies = Currency::get();

        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['name' => trans('cruds.giftCardIssue.title_singular')." ".trans('global.listing') ]];
        return view('admin.giftCardIssues.index', compact('users', 'products', 'currencies','breadcrumbs'));
    }

    public function create()
    {
        abort_if(Gate::denies('gift_card_issue_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $users = User::all()->pluck('email', 'id')->prepend(trans('global.pleaseSelect'), '');

        $gift_cards = Product::all()->pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');

        $currencies = Currency::all()->pluck('currency', 'id')->prepend(trans('global.pleaseSelect'), '');

        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['link'=>route('admin.gift-card-issues.index'),'name' => trans('cruds.giftCardIssue.title') ],['name' => trans('locale.Add')." ".trans('cruds.giftCardIssue.title_singular') ]];
        return view('admin.giftCardIssues.create', compact('users', 'gift_cards', 'currencies','breadcrumbs'));
    }

    public function store(StoreGiftCardIssueRequest $request)
    {
        try {
            $giftCardIssue = GiftCardIssue::create($request->all());
            return redirect()->route('admin.gift-card-issues.index')->with('success','Giftcard issue added successfully!!');
        } catch (Exception $e) {
            return redirect()->route('admin.gift-card-issues.index')->with('error','Something went wrong!!');     
        }
    }

    public function edit(GiftCardIssue $giftCardIssue)
    {
        abort_if(Gate::denies('gift_card_issue_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::all()->pluck('email', 'id')->prepend(trans('global.pleaseSelect'), '');

        $gift_cards = Product::all()->pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');

        $currencies = Currency::all()->pluck('currency', 'id')->prepend(trans('global.pleaseSelect'), '');

        $giftCardIssue->load('user', 'gift_card', 'currency');

        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['link'=>route('admin.gift-card-issues.index'),'name' => trans('cruds.giftCardIssue.title') ],['name' => trans('global.edit')." ".trans('cruds.giftCardIssue.title_singular') ]];
        return view('admin.giftCardIssues.edit', compact('users', 'gift_cards', 'currencies', 'giftCardIssue','breadcrumbs'));
    }

    public function update(UpdateGiftCardIssueRequest $request, GiftCardIssue $giftCardIssue)
    {
        try {
            $giftCardIssue->update($request->all());
            return redirect()->route('admin.gift-card-issues.index')->with('success','Giftcard issue updated successfully!!');   
        } catch (Exception $e) {
            return redirect()->route('admin.gift-card-issues.index')->with('error','Something went wrong!!');   
        }
    }

    public function show(GiftCardIssue $giftCardIssue)
    {
        abort_if(Gate::denies('gift_card_issue_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $giftCardIssue->load('user', 'gift_card', 'currency');
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['link'=>route('admin.gift-card-issues.index'),'name' => trans('cruds.giftCardIssue.title') ],['name' => trans('global.show')." ".trans('cruds.giftCardIssue.title_singular') ]];

        return view('admin.giftCardIssues.show', compact('giftCardIssue','breadcrumbs'));
    }

    public function destroy(GiftCardIssue $giftCardIssue,$id)
    {
        try {
              abort_if(Gate::denies('gift_card_issue_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
              GiftCardIssue::where('id',$id)->delete();   
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
            __('constants.messages.GIFTCARD_ISSUE_DELETE_SUCCESSFULLY.code'),
            __('constants.messages.GIFTCARD_ISSUE_DELETE_SUCCESSFULLY.msg'),
        );
    }

    public function massDestroy(MassDestroyGiftCardIssueRequest $request)
    {
        try {
              GiftCardIssue::whereIn('id', request('ids'))->delete(); 
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
            __('constants.messages.GIFTCARD_ISSUE_DELETE_SUCCESSFULLY.code'),
            __('constants.messages.GIFTCARD_ISSUE_DELETE_SUCCESSFULLY.msg'),
        );
    }
}
