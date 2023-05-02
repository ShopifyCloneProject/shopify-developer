@extends('layouts/contentLayoutMaster')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.shipmentstatus.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.shipments.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.shipmentstatus.fields.id') }}
                        </th>
                        <td>
                            {{ $shipments_status->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.shipmentstatus.fields.description') }}
                        </th>
                        <td>
                            {{ $shipments_status->description }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.shipmentstatus.fields.status_code') }}
                        </th>
                        <td>
                            {{ $shipments_status->status_code }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.shipmentstatus.fields.status') }}
                        </th>
                        <td>
                            {{ $shipments_status->status }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.shipments.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection