@extends('layouts/contentLayoutMaster')
@section('content')
<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.productType.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.product-types.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.productType.fields.id') }}
                        </th>
                        <td>
                            {{ $productType->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.productType.fields.title') }}
                        </th>
                        <td>
                            {{ $productType->title }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.productType.fields.status') }}
                        </th>
                        <td>
                            {{ App\Models\ProductType::STATUS_RADIO[$productType->status] ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group mt-2">
                <a class="btn btn-primary" href="{{ route('admin.product-types.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection