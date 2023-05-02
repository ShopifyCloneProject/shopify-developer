@extends('layouts/contentLayoutMaster')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.userStoreIndustry.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.user-store-industries.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.userStoreIndustry.fields.id') }}
                        </th>
                        <td>
                            {{ $userStoreIndustry->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.userStoreIndustry.fields.user') }}
                        </th>
                        <td>
                            {{ $userStoreIndustry->user->email ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.userStoreIndustry.fields.title') }}
                        </th>
                        <td>
                            {{ $userStoreIndustry->title }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.userStoreIndustry.fields.status') }}
                        </th>
                        <td>
                            {{ App\Models\UserStoreIndustry::STATUS_RADIO[$userStoreIndustry->status] ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-primary mt-2" href="{{ route('admin.user-store-industries.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection