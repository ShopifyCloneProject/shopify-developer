@extends('layouts/contentLayoutMaster')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.collectionCondition.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.collection-conditions.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.collectionCondition.fields.id') }}
                        </th>
                        <td>
                            {{ $collectionCondition->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.collectionCondition.fields.model_name') }}
                        </th>
                        <td>
                            {{ $collectionCondition->model_name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.collectionCondition.fields.model_ref') }}
                        </th>
                        <td>
                            {{ $collectionCondition->model_ref }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.collectionCondition.fields.status') }}
                        </th>
                        <td>
                            {{ App\Models\CollectionCondition::STATUS_RADIO[$collectionCondition->status] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.collectionCondition.fields.value') }}
                        </th>
                        <td>
                            {{ $collectionCondition->value }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.collectionCondition.fields.collection_title') }}
                        </th>
                        <td>
                            {{ $collectionCondition->collection_title->title ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.collectionCondition.fields.condition') }}
                        </th>
                        <td>
                            {{ $collectionCondition->condition->title ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.collection-conditions.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection