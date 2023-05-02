@extends('layouts/contentLayoutMaster')
@section('title','Gift Card Denominations')

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

 @can('gift_card_denomination_create')
<input type="hidden" id="gift_card_denomination_create" value="1">
@endcan
@can('gift_card_denomination_edit')
<input type="hidden" id="gift_card_denomination_edit" value="1">
@endcan
@can('gift_card_denomination_show')
<input type="hidden" id="gift_card_denomination_show" value="1">
@endcan
@can('gift_card_denomination_delete')
<input type="hidden" id="gift_card_denomination_delete" value="1">
@endcan
@can('gift_card_denomination_export')
<input type="hidden" id="gift_card_denomination_export" value="1">
@endcan


<section id="column-search-datatable">
  @can('gift_card_denomination_access')
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
              <h4 class="card-title"> {{ trans('cruds.giftCardDenomination.title') }}</h4>
            </div>
            <div class="card-datatable">
              <table class="dt-column-search table table-responsive">
                <thead>
                 <tr>
                    <th></th>
                    <th width="10%">
                        {{ trans('cruds.giftCardDenomination.fields.id') }}
                    </th>
                    <th width="40%">
                        {{ trans('cruds.giftCardDenomination.fields.product') }}
                    </th>
                    <th>
                        {{ trans('cruds.giftCardDenomination.fields.value') }}
                    </th>
                    <th>
                       {{ trans('global.actions') }}
                    </th>
                </tr>
                <tr class="bg-gradient-secondary">
                    <td>
                    </td>
                    <td width="10%">
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td width="40%">
                        <select class="search">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach($products as $key => $item)
                                <option value="{{ $item->title }}">{{ $item->title }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
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
     let getListingUrl = "{{ route('admin.gift-card-denominations.index') }}";
     let createUrl = "{{ route('admin.gift-card-denominations.create') }}";
     let deleteUrl = "{{ url('api/admin/gift-card-denominations/delete') }}"
     let destroyUrl = "{{ url('api/admin/gift-card-denominations/massdestroy') }}"
     let displayUrl = "{{ url('admin/gift-card-denominations') }}";
    </script>

 <script src="{{ asset(mix('adminassets/js/giftCardDenominations/giftCardDenominations.min.js')) }}"></script>

@endsection
