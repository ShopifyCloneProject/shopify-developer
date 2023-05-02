<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyConditionTitleRequest;
use App\Http\Requests\StoreConditionTitleRequest;
use App\Http\Requests\UpdateConditionTitleRequest;
use App\Models\ConditionTitle;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class ConditionTitlesController extends Controller
{
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $query = ConditionTitle::query()->select(sprintf('%s.*', (new ConditionTitle())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'condition_title_show';
                $editGate = 'condition_title_edit';
                $deleteGate = 'condition_title_delete';
                $crudRoutePart = 'condition-titles';

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
            $table->editColumn('status', function ($row) {
                return ( $row->status || $row->status == 0 ) ? ConditionTitle::STATUS_RADIO[$row->status] : '';
            });
            $table->editColumn('collection_condition', function ($row) {
                return $row->collection_condition ? $row->collection_condition : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        $breadcrumbs = [['name' => 'Collection'], ['name' => 'Conditions Title' ]];
        return view('admin.conditionTitles.index');
    }

    public function create()
    {

        return view('admin.conditionTitles.create');
    }

    public function store(StoreConditionTitleRequest $request)
    {
        $conditionTitle = ConditionTitle::create($request->all());

        return $this->successResponse(
            __('constants.SUCCESS_STATUS'),
            __('constants.messages.CONDITION_TITLE_ADDED_SUCCESSFULLY.code'),
            __('constants.messages.CONDITION_TITLE_ADDED_SUCCESSFULLY.msg'),
            $conditionTitle
        );
    }

    public function edit(ConditionTitle $conditionTitle)
    {

        return view('admin.conditionTitles.edit', compact('conditionTitle'));
    }

    public function update(UpdateConditionTitleRequest $request, ConditionTitle $conditionTitle)
    {
        $conditionTitle->update($request->all());

        return $this->successResponse(
            __('constants.SUCCESS_STATUS'),
            __('constants.messages.CONDITION_TITLE_UPDATE_SUCCESSFULLY.code'),
            __('constants.messages.CONDITION_TITLE_UPDATE_SUCCESSFULLY.msg'),
        );
    }

    public function show(ConditionTitle $conditionTitle)
    {

        return view('admin.conditionTitles.show', compact('conditionTitle'));
    }

    public function destroy(ConditionTitle $conditionTitle)
    {

        $conditionTitle->delete();

        return $this->successResponse(
            __('constants.SUCCESS_STATUS'),
            __('constants.messages.CONDITION_TITLE_DELETE_SUCCESSFULLY.code'),
            __('constants.messages.CONDITION_TITLE_DELETE_SUCCESSFULLY.msg'),
        );
    }

    public function massDestroy(MassDestroyConditionTitleRequest $request)
    {
        ConditionTitle::whereIn('id', request('ids'))->delete();

        return $this->successResponse(
            __('constants.SUCCESS_STATUS'),
            __('constants.messages.CONDITION_TITLE_DELETE_SUCCESSFULLY.code'),
            __('constants.messages.CONDITION_TITLE_DELETE_SUCCESSFULLY.msg'),
        );
    }
}
