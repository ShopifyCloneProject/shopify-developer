<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyRoleRequest;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Models\Permission;
use App\Models\Role;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class RolesController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('role_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Role::with(['permissions'])->select(sprintf('%s.*', (new Role())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'role_show';
                $editGate = 'role_edit';
                $deleteGate = 'role_delete';
                $crudRoutePart = 'roles';

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
            $table->editColumn('permissions', function ($row) {
                $labels = [];
                foreach ($row->permissions as $permission) {
                    $labels[] = sprintf('<span class="label label-info label-many">%s</span>', $permission->title);
                }

                return implode(' ', $labels);
            });
            $table->editColumn('status', function ($row) {
                return Role::STATUS_RADIO[$row->status];
            });

            $table->rawColumns(['actions', 'placeholder', 'permissions']);

            return $table->make(true);
        }

        $permissions = Permission::get();
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['name' => trans('cruds.role.title_singular')." ".trans('global.listing') ]];
        return view('admin.roles.index', compact('permissions','breadcrumbs'));
    }

    public function create()
    {
        abort_if(Gate::denies('role_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $permissions = Permission::all()->pluck('title', 'id');
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['link'=>route('admin.roles.index'), 'name' => trans('cruds.role.title')], ['name' => trans('locale.Add')." ".trans('cruds.role.title_singular') ]];
        return view('admin.roles.create', compact('permissions','breadcrumbs'));
    }

    public function store(StoreRoleRequest $request)
    {
        $role = Role::create($request->all());
        $role->permissions()->sync($request->input('permissions', []));
        $url = route('admin.roles.index');
        return $this->successResponse(
            __('constants.SUCCESS_STATUS'),
            __('constants.messages.ROLE_ADDED_SUCCESSFULLY.code'),
            __('constants.messages.ROLE_ADDED_SUCCESSFULLY.msg'),
            ['url' => $url]
        );
    }

    public function edit(Role $role)
    {
        abort_if(Gate::denies('role_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $permissions = Permission::all()->pluck('title', 'id');
        $role->load('permissions');
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['link'=>route('admin.roles.index'), 'name' => trans('cruds.role.title')], ['name' => trans('locale.Edit')." ".trans('cruds.role.title_singular') ]];
        return view('admin.roles.edit', compact('permissions', 'role','breadcrumbs'));
    }

    public function update(UpdateRoleRequest $request, Role $role)
    {
        $role->update($request->all());
        $role->permissions()->sync($request->input('permissions', []));

        $url = route('admin.roles.index');
        return $this->successResponse(
            __('constants.SUCCESS_STATUS'),
            __('constants.messages.ROLE_UPDATE_SUCCESSFULLY.code'),
            __('constants.messages.ROLE_UPDATE_SUCCESSFULLY.msg'),
            ['url' => $url]
        );
    }

    public function show(Role $role)
    {
        abort_if(Gate::denies('role_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $role->load('permissions');

        return view('admin.roles.show', compact('role'));
    }

    public function destroy(Role $role)
    {
        abort_if(Gate::denies('role_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $role->delete();

        return $this->successResponse(
            __('constants.SUCCESS_STATUS'),
            __('constants.messages.ROLE_DELETE_SUCCESSFULLY.code'),
            __('constants.messages.ROLE_DELETE_SUCCESSFULLY.msg'),
        );
    }

    public function massDestroy(MassDestroyRoleRequest $request)
    {
        Role::whereIn('id', request('ids'))->delete();

        return $this->successResponse(
            __('constants.SUCCESS_STATUS'),
            __('constants.messages.ROLE_DELETE_SUCCESSFULLY.code'),
            __('constants.messages.ROLE_DELETE_SUCCESSFULLY.msg'),
        );
    }
}
