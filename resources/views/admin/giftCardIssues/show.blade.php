@extends('layouts/contentLayoutMaster')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.giftCardIssue.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.gift-card-issues.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.giftCardIssue.fields.id') }}
                        </th>
                        <td>
                            {{ $giftCardIssue->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.giftCardIssue.fields.code') }}
                        </th>
                        <td>
                            {{ $giftCardIssue->code }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.giftCardIssue.fields.status') }}
                        </th>
                        <td>
                            {{ App\Models\GiftCardIssue::STATUS_RADIO[$giftCardIssue->status] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.giftCardIssue.fields.date_issued') }}
                        </th>
                        <td>
                            {{ $giftCardIssue->date_issued }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.giftCardIssue.fields.remaining_value') }}
                        </th>
                        <td>
                            {{ $giftCardIssue->remaining_value }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.giftCardIssue.fields.initial_value') }}
                        </th>
                        <td>
                            {{ $giftCardIssue->initial_value }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.giftCardIssue.fields.expiration_type') }}
                        </th>
                        <td>
                            {{ App\Models\GiftCardIssue::EXPIRATION_TYPE_RADIO[$giftCardIssue->expiration_type] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.giftCardIssue.fields.expiration_date') }}
                        </th>
                        <td>
                            {{ $giftCardIssue->expiration_date }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.giftCardIssue.fields.note') }}
                        </th>
                        <td>
                            {{ $giftCardIssue->note }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.giftCardIssue.fields.enabled') }}
                        </th>
                        <td>
                            {{ App\Models\GiftCardIssue::ENABLED_RADIO[$giftCardIssue->enabled] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.giftCardIssue.fields.disabled_at') }}
                        </th>
                        <td>
                            {{ $giftCardIssue->disabled_at }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.giftCardIssue.fields.user') }}
                        </th>
                        <td>
                            {{ $giftCardIssue->user->email ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.giftCardIssue.fields.gift_card') }}
                        </th>
                        <td>
                            {{ $giftCardIssue->gift_card->title ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.giftCardIssue.fields.currency') }}
                        </th>
                        <td>
                            {{ $giftCardIssue->currency->currency ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-primary mt-2" href="{{ route('admin.gift-card-issues.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection