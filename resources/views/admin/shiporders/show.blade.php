@extends('layouts/contentLayoutMaster')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.shiporders.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.shiporders.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.shiporders.fields.id') }}
                        </th>
                        <td>
                            {{ $ship_orders->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.shiporders.fields.filter') }}
                        </th>
                        <td>
                            {{ $ship_orders->filter }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.shiporders.fields.description') }}
                        </th>
                        <td>
                            {{ $ship_orders->description }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.shiporders.fields.status') }}
                        </th>
                        <td>
                            {{ $ship_orders->status }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.shiporders.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection