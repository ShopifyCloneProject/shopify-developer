<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyCollectionConditionRequest;
use App\Http\Requests\StoreCollectionConditionRequest;
use App\Http\Requests\UpdateCollectionConditionRequest;
use App\Models\Collection;
use App\Models\CollectionCondition;
use App\Models\ConditionOption;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class CollectionConditionsController extends Controller
{
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $query = CollectionCondition::with(['collection_title', 'condition'])->select(sprintf('%s.*', (new CollectionCondition())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'collection_condition_show';
                $editGate = 'collection_condition_edit';
                $deleteGate = 'collection_condition_delete';
                $crudRoutePart = 'collection-conditions';

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
            $table->editColumn('model_name', function ($row) {
                return $row->model_name ? $row->model_name : '';
            });
            $table->editColumn('model_ref', function ($row) {
                return $row->model_ref ? $row->model_ref : '';
            });
            $table->editColumn('status', function ($row) {
                return $row->status ? CollectionCondition::STATUS_RADIO[$row->status] : '';
            });
            $table->editColumn('value', function ($row) {
                return $row->value ? $row->value : '';
            });
            $table->addColumn('collection_title_title', function ($row) {
                return $row->collection_title ? $row->collection_title->title : '';
            });

            $table->addColumn('condition_title', function ($row) {
                return $row->condition ? $row->condition->title : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'collection_title', 'condition']);

            return $table->make(true);
        }

        $collections       = Collection::get();
        $condition_options = ConditionOption::get();

        return view('admin.collectionConditions.index', compact('collections', 'condition_options'));
    }

    public function create()
    {

        $collection_titles = Collection::all()->pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');

        $conditions = ConditionOption::all()->pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.collectionConditions.create', compact('collection_titles', 'conditions'));
    }

    public function store(StoreCollectionConditionRequest $request)
    {
        $collectionCondition = CollectionCondition::create($request->all());

        return redirect()->route('admin.collection-conditions.index');
    }

    public function edit(CollectionCondition $collectionCondition)
    {

        $collection_titles = Collection::all()->pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');

        $conditions = ConditionOption::all()->pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');

        $collectionCondition->load('collection_title', 'condition');

        return view('admin.collectionConditions.edit', compact('collection_titles', 'conditions', 'collectionCondition'));
    }

    public function update(UpdateCollectionConditionRequest $request, CollectionCondition $collectionCondition)
    {
        $collectionCondition->update($request->all());

        return redirect()->route('admin.collection-conditions.index');
    }

    public function show(CollectionCondition $collectionCondition)
    {

        $collectionCondition->load('collection_title', 'condition');

        return view('admin.collectionConditions.show', compact('collectionCondition'));
    }

    public function destroy(CollectionCondition $collectionCondition)
    {

        $collectionCondition->delete();

        return back();
    }

    public function massDestroy(MassDestroyCollectionConditionRequest $request)
    {
        CollectionCondition::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
