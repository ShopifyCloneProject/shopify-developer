@extends('layouts/contentLayoutMaster')

@section('title','Gift Card Issue')
@section('vendor-style')
   @include('admin/partials/datatableCss')
@endsection
@section('page-style')
<link rel="stylesheet" type="text/css" href="{{asset('css/base/plugins/forms/pickers/form-flat-pickr.css')}}">
<style>
    .alert-msg{
        margin-left: auto;
    }
    .alert{
        padding: 20px;
    }
    .alert strong{
        font-size: 16px;
        letter-spacing: 0.5px;
    }
</style>

@endsection
@section('content')

@can('gift_card_issue_create')
<input type="hidden" id="gift_card_issue_create" value="1">
@endcan
@can('gift_card_issue_edit')
<input type="hidden" id="gift_card_issue_edit" value="1">
@endcan
@can('gift_card_issue_show')
<input type="hidden" id="gift_card_issue_show" value="1">
@endcan
@can('gift_card_issue_delete')
<input type="hidden" id="gift_card_issue_delete" value="1">
@endcan
@can('gift_card_issue_export')
<input type="hidden" id="gift_card_issue_export" value="1">
@endcan


<section id="column-search-datatable">
  @can('gift_card_issue_access')
      <div class="row">

        <div class="col-12 alert-msg">
              @if ($message = Session::get('success'))
              <div class="alert alert-success alert-block">
              <button type="button" class="close" data-dismiss="alert">×</button>
              <strong>{{ $message }}</strong>
              </div>
              @endif
              @if ($message = Session::get('error'))
              <div class="alert alert-danger alert-block">
              <button type="button" class="close" data-dismiss="alert">×</button>
              <strong>{{ $message }}</strong>
              </div>
              @endif
        </div>

        <div class="col-12">
          <div class="card">
            <div class="card-header border-bottom">
              <h4 class="card-title"> {{ trans('cruds.giftCardIssue.title') }}</h4>
            </div>
            <div class="card-datatable">
              <table class="dt-column-search table table-responsive">
                <thead>
                   <tr>
                    <th>
                    </th>
                    <th>
                        {{ trans('cruds.giftCardIssue.fields.id') }}
                    </th>
                    <th>
                        {{ trans('cruds.giftCardIssue.fields.code') }}
                    </th>
                    <th>
                        {{ trans('cruds.giftCardIssue.fields.status') }}
                    </th>
                    <th>
                        {{ trans('cruds.giftCardIssue.fields.date_issued') }}
                    </th>
                    <th>
                        {{ trans('cruds.giftCardIssue.fields.remaining_value') }}
                    </th>
                    <th>
                        {{ trans('cruds.giftCardIssue.fields.initial_value') }}
                    </th>
                    <th>
                        {{ trans('cruds.giftCardIssue.fields.expiration_type') }}
                    </th>
                    <th>
                        {{ trans('cruds.giftCardIssue.fields.expiration_date') }}
                    </th>
                    <th>
                        {{ trans('cruds.giftCardIssue.fields.note') }}
                    </th>
                    <th>
                        {{ trans('cruds.giftCardIssue.fields.enabled') }}
                    </th>
                    <th>
                        {{ trans('cruds.giftCardIssue.fields.disabled_at') }}
                    </th>
                    <th>
                        {{ trans('cruds.giftCardIssue.fields.user') }}
                    </th>
                    <th>
                        {{ trans('cruds.giftCardIssue.fields.gift_card') }}
                    </th>
                    <th>
                        {{ trans('cruds.giftCardIssue.fields.currency') }}
                    </th>
                    <th>
                       {{ trans('global.actions') }}
                    </th>
                </tr>
                <tr class="bg-gradient-secondary">
                    <td>
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        <select class="search" strict="true">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach(App\Models\GiftCardIssue::STATUS_RADIO as $key => $item)
                                <option value="{{ $key }}">{{ $item }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        <select class="search" strict="true">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach(App\Models\GiftCardIssue::EXPIRATION_TYPE_RADIO as $key => $item)
                                <option value="{{ $key }}">{{ $item }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        <select class="search" strict="true">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach(App\Models\GiftCardIssue::ENABLED_RADIO as $key => $item)
                                <option value="{{ $key }}">{{ $item }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        <select class="search">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach($users as $key => $item)
                                <option value="{{ $item->email }}">{{ $item->email }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select class="search">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach($products as $key => $item)
                                <option value="{{ $item->title }}">{{ $item->title }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select class="search">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach($currencies as $key => $item)
                                <option value="{{ $item->currency }}">{{ $item->currency }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                    </td>
                </tr>
                </thead>
                
              </table>
            </div>
          </div>
        </div>
      </div>
  @endcan
</section>

@endsection

@section('vendor-script')
  @include('admin/partials/datatableJs')
@endsection

@section('page-script')

    <script type="text/javascript">
     let getListingUrl = "{{ route('admin.gift-card-issues.index') }}";
     let createUrl = "{{ route('admin.gift-card-issues.create') }}";
     let deleteUrl = "{{ url('api/admin/gift-card-issues/delete') }}"
     let destroyUrl = "{{ url('api/admin/gift-card-issues/massdestroy') }}"
     let displayUrl = "{{ url('admin/gift-card-issues') }}";
    </script>

 <script src="{{ asset(mix('adminassets/js/giftcardissue/giftcardissue.min.js')) }}"></script>

@endsection
