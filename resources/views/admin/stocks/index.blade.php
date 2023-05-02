@extends('layouts/contentLayoutMaster')
@section('title','Product Types')
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

@can('stock_create')
<input type="hidden" id="stock_create" value="1">
@endcan
@can('stock_edit')
<input type="hidden" id="stock_edit" value="1">
@endcan
@can('stock_show')
<input type="hidden" id="stock_show" value="1">
@endcan
@can('stock_delete')
<input type="hidden" id="stock_delete" value="1">
@endcan
@can('stock_export')
<input type="hidden" id="stock_export" value="1">
@endcan

<section id="column-search-datatable">
  @can('stock_access')
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
              <h4 class="card-title"> {{ trans('cruds.stock.title') }}</h4>
            </div>
            <div class="card-datatable">
              <table class="dt-column-search table table-responsive">
                <thead>
                  <tr>
                      <th>
                    </th>
                    <th>
                        {{ trans('cruds.stock.fields.id') }}
                    </th>
                    <th>
                        {{ trans('cruds.stock.fields.quantity') }}
                    </th>
                    <th>
                        {{ trans('cruds.stock.fields.available_quantity') }}
                    </th>
                    <th>
                        {{ trans('cruds.stock.fields.defect_quantity') }}
                    </th>
                    <th>
                        {{ trans('cruds.stock.fields.product') }}
                    </th>
                    <th>
                        {{ trans('cruds.stock.fields.address') }}
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
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
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
                            @foreach($addresses as $key => $item)
                                <option value="{{ $item->address }}">{{ $item->address }}</option>
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
     let getListingUrl = "{{ route('admin.stocks.index') }}";
     let createUrl = "{{ route('admin.stocks.create') }}";
     let deleteUrl = "{{ url('api/admin/stocks/delete') }}"
     let destroyUrl = "{{ url('api/admin/stocks/massdestroy') }}"
     let displayUrl = "{{ url('admin/stocks') }}";
    </script>

 <script src="{{ asset(mix('adminassets/js/stocks/stocks.min.js')) }}"></script>

@endsection
