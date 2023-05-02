@extends('layouts/contentLayoutMaster')
@section('title','Gift Card Collections')
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

@can('gift_card_collection_create')
<input type="hidden" id="gift_card_collection_create" value="1">
@endcan
@can('gift_card_collection_edit')
<input type="hidden" id="gift_card_collection_edit" value="1">
@endcan
@can('gift_card_collection_show')
<input type="hidden" id="gift_card_collection_show" value="1">
@endcan
@can('gift_card_collection_delete')
<input type="hidden" id="gift_card_collection_delete" value="1">
@endcan
@can('gift_card_collection_export')
<input type="hidden" id="gift_card_collection_export" value="1">
@endcan


<section id="column-search-datatable">
  @can('gift_card_collection_access')
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
              <h4 class="card-title"> {{ trans('cruds.giftCardCollection.title') }}</h4>
            </div>
            <div class="card-datatable">
              <table class="dt-column-search table table-responsive">
                <thead>
                  <tr>
                    <th>
                    </th>
                    <th width="15%">
                        {{ trans('cruds.giftCardCollection.fields.id') }}
                    </th>
                    <th>
                        {{ trans('cruds.giftCardCollection.fields.gift_card') }}
                    </th>
                    <th>
                        {{ trans('cruds.giftCardCollection.fields.collection') }}
                    </th>
                    <th>
                        &nbsp;
                    </th>
                </tr>
                  <tr class="bg-gradient-secondary">
                        <td>
                        </td>
                        <td width="15%">
                            <input class="form-control form-control-sm search" type="text" placeholder="{{ trans('global.search') }}  {{ trans('cruds.productType.fields.id') }}">
                        </td>
                        <td>
                            <input class="form-control form-control-sm search" type="text" placeholder="{{ trans('global.search') }} {{ trans('cruds.productType.fields.title') }}">
                        </td>
                        <td>
                           <select class="search" strict="true">
                                <option value>{{ trans('global.all') }}</option>
                                @foreach(App\Models\ProductType::STATUS_RADIO as $key => $item)
                                    <option value="{{ $key }}">{{ $item }}</option>
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
     let getListingUrl = "{{ route('admin.gift-card-collections.index') }}";
     let createUrl = "{{ route('admin.gift-card-collections.create') }}";
     let deleteUrl = "{{ url('api/admin/gift-card-collections/delete') }}"
     let destroyUrl = "{{ url('api/admin/gift-card-collections/massdestroy') }}"
     let displayUrl = "{{ url('admin/gift-card-collections') }}";
    </script>

 <script src="{{ asset(mix('adminassets/js/giftcardcollections/giftcardcollections.min.js')) }}"></script>

@endsection
