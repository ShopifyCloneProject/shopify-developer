@extends('layouts/contentLayoutMaster')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.userStore.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.settings.user-stores.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.userStore.fields.id') }}
                        </th>
                        <td>
                            {{ $userStore->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.userStore.fields.store_contact_email') }}
                        </th>
                        <td>
                            {{ $userStore->store_contact_email }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.userStore.fields.sender_email') }}
                        </th>
                        <td>
                            {{ $userStore->sender_email }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.userStore.fields.company') }}
                        </th>
                        <td>
                            {{ $userStore->company }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.userStore.fields.unit_system') }}
                        </th>
                        <td>
                            {{ App\Models\UserStore::UNIT_SYSTEM_SELECT[$userStore->unit_system] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.userStore.fields.unit_weight') }}
                        </th>
                        <td>
                            {{ App\Models\UserStore::UNIT_WEIGHT_SELECT[$userStore->unit_weight] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.userStore.fields.prefix') }}
                        </th>
                        <td>
                            {{ $userStore->prefix }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.userStore.fields.suffix') }}
                        </th>
                        <td>
                            {{ $userStore->suffix }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.userStore.fields.address') }}
                        </th>
                        <td>
                            {{ $userStore->address->address ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.user-stores.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection