<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyPermissionRequest;
use App\Http\Requests\StorePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;
use App\Models\Permission;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class PermissionsController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('permission_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Permission::query()->select(sprintf('%s.*', (new Permission())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'permission_show';
                $editGate = 'permission_edit';
                $deleteGate = 'permission_delete';
                $crudRoutePart = 'permissions';

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

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['name' => trans('cruds.permission.title')]];
        return view('admin.permissions.index',compact('breadcrumbs'));
    }

    public function create()
    {
        abort_if(Gate::denies('permission_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['link'=>route('admin.permissions.index'),'name' => trans('cruds.permission.title') ],['name' => trans('locale.Add')." ".trans('cruds.permission.title_singular') ]];
        return view('admin.permissions.create',compact('breadcrumbs'));
    }

    public function store(StorePermissionRequest $request)
    {
        $permission = Permission::create($request->all());

        return $this->successResponse(
            __('constants.SUCCESS_STATUS'),
            __('constants.messages.PERMISSION_ADDED_SUCCESSFULLY.code'),
            __('constants.messages.PERMISSION_ADDED_SUCCESSFULLY.msg'),
            $permission
        );
    }

    public function edit(Permission $permission)
    {
        abort_if(Gate::denies('permission_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.permissions.edit', compact('permission'));
    }

    public function update(UpdatePermissionRequest $request, Permission $permission)
    {
        $permission->update($request->all());

        return $this->successResponse(
            __('constants.SUCCESS_STATUS'),
            __('constants.messages.PERMISSION_UPDATE_SUCCESSFULLY.code'),
            __('constants.messages.PERMISSION_UPDATE_SUCCESSFULLY.msg'),
        );
    }

    public function show(Permission $permission)
    {
        // abort_if(Gate::denies('permission_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.permissions.show', compact('permission'));
    }

    public function destroy(Permission $permission)
    {
        abort_if(Gate::denies('permission_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $permission->delete();

        return $this->successResponse(
            __('constants.SUCCESS_STATUS'),
            __('constants.messages.PERMISSION_DELETE_SUCCESSFULLY.code'),
            __('constants.messages.PERMISSION_DELETE_SUCCESSFULLY.msg'),
        );
    }

    public function massDestroy(MassDestroyPermissionRequest $request)
    {
        Permission::whereIn('id', request('ids'))->delete();

        return $this->successResponse(
            __('constants.SUCCESS_STATUS'),
            __('constants.messages.PERMISSION_DELETE_SUCCESSFULLY.code'),
            __('constants.messages.PERMISSION_DELETE_SUCCESSFULLY.msg'),
        );
    }
}
