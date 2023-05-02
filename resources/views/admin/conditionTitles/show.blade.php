@extends('layouts/contentLayoutMaster')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.conditionTitle.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.condition-titles.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.conditionTitle.fields.id') }}
                        </th>
                        <td>
                            {{ $conditionTitle->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.conditionTitle.fields.title') }}
                        </th>
                        <td>
                            {{ $conditionTitle->title }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.conditionTitle.fields.status') }}
                        </th>
                        <td>
                            {{ App\Models\ConditionTitle::STATUS_RADIO[$conditionTitle->status] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.conditionTitle.fields.collection_condition') }}
                        </th>
                        <td>
                            {{ $conditionTitle->collection_condition }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.condition-titles.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection