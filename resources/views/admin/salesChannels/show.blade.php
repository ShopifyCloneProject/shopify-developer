@extends('layouts/contentLayoutMaster')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.salesChannel.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.sales-channels.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.salesChannel.fields.id') }}
                        </th>
                        <td>
                            {{ $salesChannel->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.salesChannel.fields.product') }}
                        </th>
                        <td>
                            {{ $salesChannel->product->title ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.salesChannel.fields.start_date') }}
                        </th>
                        <td>
                            {{ $salesChannel->start_date }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.salesChannel.fields.end_date') }}
                        </th>
                        <td>
                            {{ $salesChannel->end_date }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.salesChannel.fields.status') }}
                        </th>
                        <td>
                            {{ App\Models\SalesChannel::STATUS_RADIO[$salesChannel->status] ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-primary mt-2" href="{{ route('admin.sales-channels.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection