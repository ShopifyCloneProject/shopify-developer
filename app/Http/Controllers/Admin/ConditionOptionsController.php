<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyConditionOptionRequest;
use App\Http\Requests\StoreConditionOptionRequest;
use App\Http\Requests\UpdateConditionOptionRequest;
use App\Models\ConditionOption;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class ConditionOptionsController extends Controller
{
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $query = ConditionOption::query()->select(sprintf('%s.*', (new ConditionOption())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'condition_option_show';
                $editGate = 'condition_option_edit';
                $deleteGate = 'condition_option_delete';
                $crudRoutePart = 'condition-options';

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
            $table->editColumn('description', function ($row) {
                return $row->description ? $row->description : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.conditionOptions.index');
    }

    public function create()
    {

        return view('admin.conditionOptions.create');
    }

    public function store(StoreConditionOptionRequest $request)
    {
        $conditionOption = ConditionOption::create($request->all());

        return redirect()->route('admin.condition-options.index');
    }

    public function edit(ConditionOption $conditionOption)
    {

        return view('admin.conditionOptions.edit', compact('conditionOption'));
    }

    public function update(UpdateConditionOptionRequest $request, ConditionOption $conditionOption)
    {
        $conditionOption->update($request->all());

        return redirect()->route('admin.condition-options.index');
    }

    public function show(ConditionOption $conditionOption)
    {

        return view('admin.conditionOptions.show', compact('conditionOption'));
    }

    public function destroy(ConditionOption $conditionOption)
    {

        $conditionOption->delete();

        return back();
    }

    public function massDestroy(MassDestroyConditionOptionRequest $request)
    {
        ConditionOption::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
