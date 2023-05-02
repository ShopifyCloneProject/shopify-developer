@extends('layouts/contentLayoutMaster')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.variantMedium.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.variant-media.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.variantMedium.fields.id') }}
                        </th>
                        <td>
                            {{ $variantMedium->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.variantMedium.fields.product_variant') }}
                        </th>
                        <td>
                            {{ $variantMedium->product_variant->sku ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.variantMedium.fields.product') }}
                        </th>
                        <td>
                            {{ $variantMedium->product->title ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.variantMedium.fields.src') }}
                        </th>
                        <td>
                            {{ $variantMedium->src }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.variantMedium.fields.src_alt_text') }}
                        </th>
                        <td>
                            {{ $variantMedium->src_alt_text }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.variantMedium.fields.is_default') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $variantMedium->is_default ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.variantMedium.fields.reorder') }}
                        </th>
                        <td>
                            {{ $variantMedium->reorder }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-primary mt-2" href="{{ route('admin.variant-media.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection