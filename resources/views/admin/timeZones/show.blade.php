@extends('layouts/contentLayoutMaster')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.timeZone.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.time-zones.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.timeZone.fields.id') }}
                        </th>
                        <td>
                            {{ $timeZone->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.timeZone.fields.title') }}
                        </th>
                        <td>
                            {{ $timeZone->title }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.timeZone.fields.timezone_value') }}
                        </th>
                        <td>
                            {{ $timeZone->timezone_value }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.time-zones.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection