@extends('layouts/contentLayoutMaster')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.conditionOption.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.condition-options.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.conditionOption.fields.id') }}
                        </th>
                        <td>
                            {{ $conditionOption->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.conditionOption.fields.title') }}
                        </th>
                        <td>
                            {{ $conditionOption->title }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.conditionOption.fields.description') }}
                        </th>
                        <td>
                            {{ $conditionOption->description }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.condition-options.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection