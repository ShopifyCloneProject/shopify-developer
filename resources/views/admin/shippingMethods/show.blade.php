@extends('layouts/contentLayoutMaster')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.shippingMethod.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.shipping-methods.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.shippingMethod.fields.id') }}
                        </th>
                        <td>
                            {{ $shippingMethod->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.shippingMethod.fields.title') }}
                        </th>
                        <td>
                            {{ $shippingMethod->title }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.shippingMethod.fields.status') }}
                        </th>
                        <td>
                            {{ App\Models\ShippingMethod::STATUS_RADIO[$shippingMethod->status] ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.shipping-methods.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection