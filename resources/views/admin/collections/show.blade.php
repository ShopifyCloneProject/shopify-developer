@extends('layouts/contentLayoutMaster')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.collection.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.collections.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.collection.fields.id') }}
                        </th>
                        <td>
                            {{ $collection->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.collection.fields.title') }}
                        </th>
                        <td>
                            {{ $collection->title }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.collection.fields.description') }}
                        </th>
                        <td>
                            {!! $collection->description !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.collection.fields.collection_type') }}
                        </th>
                        <td>
                            {{ App\Models\Collection::COLLECTION_TYPE_RADIO[$collection->collection_type] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.collection.fields.conditions_type') }}
                        </th>
                        <td>
                            {{ App\Models\Collection::CONDITIONS_TYPE_RADIO[$collection->conditions_type] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.collection.fields.description_position') }}
                        </th>
                        <td>
                            {{ App\Models\Collection::DESCRIPTION_POSITION_RADIO[$collection->description_position] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.collection.fields.seo_keywords') }}
                        </th>
                        <td>
                            {{ $collection->seo_keywords }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.collection.fields.seo_description') }}
                        </th>
                        <td>
                            {{ $collection->seo_description }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.collection.fields.status') }}
                        </th>
                        <td>
                            {{ App\Models\Collection::STATUS_RADIO[$collection->status] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.collection.fields.src') }}
                        </th>
                        <td>
                            @if($collection->src)
                                <a href="{{ $collection->src->getUrl() }}" target="_blank" style="display: inline-block">
                                    <img src="{{ $collection->src->getUrl('thumb') }}">
                                </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.collection.fields.src_alt_text') }}
                        </th>
                        <td>
                            {{ $collection->src_alt_text }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.collection.fields.url') }}
                        </th>
                        <td>
                            {{ $collection->url }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.collection.fields.online_store') }}
                        </th>
                        <td>
                            {{ App\Models\Collection::ONLINE_STORE_RADIO[$collection->online_store] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.collection.fields.schedule_time') }}
                        </th>
                        <td>
                            {{ $collection->schedule_time }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.collections.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection