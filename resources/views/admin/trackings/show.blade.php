@extends('layouts/contentLayoutMaster')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.trackings.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.trackings.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.trackings.fields.id') }}
                        </th>
                        <td>
                            {{ $trackings->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.trackings.fields.description') }}
                        </th>
                        <td>
                            {{ $trackings->description }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.trackings.fields.status_code') }}
                        </th>
                        <td>
                            {{ $trackings->status_code }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.trackings.fields.status') }}
                        </th>
                        <td>
                            {{ $trackings->status }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.trackings.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection