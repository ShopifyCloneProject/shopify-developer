@extends('layouts/contentLayoutMaster')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.orderFinancialStatus.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.order-financial-statuses.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.orderFinancialStatus.fields.id') }}
                        </th>
                        <td>
                            {{ $orderstatus->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.orderFinancialStatus.fields.title') }}
                        </th>
                        <td>
                            {{ $orderstatus->title }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.orderFinancialStatus.fields.status') }}
                        </th>
                        <td>
                            {{ App\Models\OrderFinancialStatus::STATUS_RADIO[$orderstatus->status] ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-primary mt-2" href="{{ route('admin.order-financial-statuses.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection