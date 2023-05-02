@extends('layouts/contentLayoutMaster')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.variant.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.variant.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.variant.fields.id') }}
                        </th>
                        <td>
                            {{ $variant->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.variant.fields.title') }}
                        </th>
                        <td>
                            {{ $variant->title }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.variant.fields.status') }}
                        </th>
                        <td>
                            {{ App\Models\Variant::STATUS_RADIO[$variant->status] ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.variant-types.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection