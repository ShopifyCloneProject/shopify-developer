@extends('layouts/contentLayoutMaster')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.couriers.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.couriers.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.couriers.fields.id') }}
                        </th>
                        <td>
                            {{ $couriers->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.couriers.fields.name') }}
                        </th>
                        <td>
                            {{ $couriers->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.couriers.fields.courier_code') }}
                        </th>
                        <td>
                            {{ $couriers->courier_code }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.couriers.fields.status') }}
                        </th>
                        <td>
                            {{ $couriers->status }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.couriers.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection