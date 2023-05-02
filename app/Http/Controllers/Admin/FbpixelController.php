<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\MassDestroyFbpixelRequest;
use App\Http\Requests\StoreFbpixelRequest;
use App\Http\Requests\UpdateFbpixelRequest;
use App\Models\Fbpixel;
use Gate;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class FbpixelController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('fbpixel_access_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Fbpixel::query()->select(sprintf('%s.*', (new Fbpixel())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'fbpixel_show';
                $editGate = 'fbpixel_edit';
                $deleteGate = 'fbpixel_delete';
                $crudRoutePart = 'fbpixels';

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
            $table->editColumn('pixel', function ($row) {
                return $row->pixel ? $row->pixel : '';
            });
            $table->editColumn('status', function ($row) {
                return $row->status ? Fbpixel::STATUS_SELECT[$row->status] : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }
        $breadcrumbs = [['link'=>route('admin.dashboard'),'name' => trans('cruds.pages.home')], ['link'=>route('admin.settings.index'),'name' => trans('cruds.settings.title')], ['name' => trans('cruds.fbpixel.title') ]];
        return view('admin.fbpixels.index',compact('breadcrumbs'));
    }

    public function create()
    {
        abort_if(Gate::denies('fbpixel_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.fbpixels.create');
    }

    public function store(StoreFbpixelRequest $request)
    {
        $fbpixel = Fbpixel::create($request->all());
        \Artisan::call('config:clear');
         return $this->successResponse(
            __('constants.SUCCESS_STATUS'),
            __('constants.messages.PIXEL_ADDED_SUCCESSFULLY.code'),
            __('constants.messages.PIXEL_ADDED_SUCCESSFULLY.msg'),
            $fbpixel
        );
    }

    public function edit(Fbpixel $fbpixel)
    {
        abort_if(Gate::denies('fbpixel_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.fbpixels.edit', compact('fbpixel'));
    }

    public function update(UpdateFbpixelRequest $request, Fbpixel $fbpixel)
    {
        $fbpixel->update($request->all());
         \Artisan::call('config:clear');
        return $this->successResponse(
            __('constants.SUCCESS_STATUS'),
            __('constants.messages.PIXEL_UPDATE_SUCCESSFULLY.code'),
            __('constants.messages.PIXEL_UPDATE_SUCCESSFULLY.msg'),
        );
    }

    public function show(Fbpixel $fbpixel)
    {
        abort_if(Gate::denies('fbpixel_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.fbpixels.show', compact('fbpixel'));
    }

    public function destroy(Fbpixel $fbpixel)
    {
        abort_if(Gate::denies('fbpixel_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
         \Artisan::call('config:clear');
        $fbpixel->delete();

       return $this->successResponse(
            __('constants.SUCCESS_STATUS'),
            __('constants.messages.PIXEL_DELETE_SUCCESSFULLY.code'),
            __('constants.messages.PIXEL_DELETE_SUCCESSFULLY.msg'),
        );
    }

    public function massDestroy(MassDestroyFbpixelRequest $request)
    {
        Fbpixel::whereIn('id', request('ids'))->delete();
         \Artisan::call('config:clear');
       return $this->successResponse(
            __('constants.SUCCESS_STATUS'),
            __('constants.messages.PIXEL_DELETE_SUCCESSFULLY.code'),
            __('constants.messages.PIXEL_DELETE_SUCCESSFULLY.msg'),
        );
    }
}
