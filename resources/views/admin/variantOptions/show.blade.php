@extends('layouts/contentLayoutMaster')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.variantOption.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.variant-options.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.variantOption.fields.id') }}
                        </th>
                        <td>
                            {{ $variantOption->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.variantOption.fields.options') }}
                        </th>
                        <td>
                            {{ $variantOption->options }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.variantOption.fields.status') }}
                        </th>
                        <td>
                            {{ App\Models\VariantOption::STATUS_RADIO[$variantOption->status] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.variantOption.fields.variant') }}
                        </th>
                        <td>
                            {{ $variantOption->variant->title ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.variant-options.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection