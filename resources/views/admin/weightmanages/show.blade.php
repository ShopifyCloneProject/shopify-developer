@extends('layouts/contentLayoutMaster')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.weightmanage.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.weightmanages.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.weightmanage.fields.id') }}
                        </th>
                        <td>
                            {{ $weightmanage->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.weightmanage.fields.title') }}
                        </th>
                        <td>
                            {{ $weightmanage->title }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.weightmanage.fields.status') }}
                        </th>
                        <td>
                            {{ App\Models\Weightmanage::STATUS_RADIO[$weightmanage->status] ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.weightmanages.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection