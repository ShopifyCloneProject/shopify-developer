<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroySectionRequest;
use App\Http\Requests\StoreSectionRequest;
use App\Http\Requests\UpdateSectionRequest;
use App\Models\Section;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

use Exception;
class SectionsController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('section_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Section::query()->select(sprintf('%s.*', (new Section())->table));
            $table = Datatables::of($query);


            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');
            $table->editColumn('actions', function ($row) {
                $viewGate = 'section_show';
                $editGate = 'section_edit';
                $deleteGate = 'section_delete';
                $crudRoutePart = 'sections';

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
            $table->editColumn('columnname', function ($row) {
                return $row->columnname ? $row->columnname : '';
            });
            $table->editColumn('displaycolumnname', function ($row) {
                return $row->displaycolumnname ? $row->displaycolumnname : '';
            });
            $table->editColumn('status', function ($row) {
                return $row->status ? Section::STATUS_RADIO[$row->status] : Section::STATUS_RADIO[$row->status];
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.sections.index');
    }

    public function create()
    {
        abort_if(Gate::denies('section_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.sections.create');
    }

    public function store(StoreSectionRequest $request)
    {
        $section = Section::create($request->all());
          return $this->successResponse(
            __('constants.SUCCESS_STATUS'),
            __('constants.messages.SECTION_ADDED_SUCCESSFULLY.code'),
            __('constants.messages.SECTION_ADDED_SUCCESSFULLY.msg'),
            $section
        );
        // return redirect()->route('admin.sections.index');
    }

    public function edit(Section $section)
    {
        abort_if(Gate::denies('section_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.sections.edit', compact('section'));
    }

    public function update(UpdateSectionRequest $request, Section $section)
    {
        $section->update($request->all());

        return $this->successResponse(
            __('constants.SUCCESS_STATUS'),
            __('constants.messages.SECTION_UPDATE_SUCCESSFULLY.code'),
            __('constants.messages.SECTION_UPDATE_SUCCESSFULLY.msg'),
        );

        //return redirect()->route('admin.sections.index');
    }

    public function show(Section $section)
    {
        abort_if(Gate::denies('section_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.sections.show', compact('section'));
    }

    public function destroy(Section $section)
    {
        abort_if(Gate::denies('section_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $section->delete();

         return $this->successResponse(
            __('constants.SUCCESS_STATUS'),
            __('constants.messages.SECTION_DELETE_SUCCESSFULLY.code'),
            __('constants.messages.SECTION_DELETE_SUCCESSFULLY.msg'),
        );

       // return back();
    }

    public function massDestroy(MassDestroySectionRequest $request)
    {
        Section::whereIn('id', request('ids'))->delete();

         return $this->successResponse(
            __('constants.SUCCESS_STATUS'),
            __('constants.messages.SECTION_DELETE_SUCCESSFULLY.code'),
            __('constants.messages.SECTION_DELETE_SUCCESSFULLY.msg'),
        );
         
       // return response(null, Response::HTTP_NO_CONTENT);
    }
}