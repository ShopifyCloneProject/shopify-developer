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

@can('product_type_create')
<input type="hidden" id="product_type_create" value="1">
@endcan
@can('product_type_edit')
<input type="hidden" id="product_type_edit" value="1">
@endcan
@can('product_type_show')
<input type="hidden" id="product_type_show" value="1">
@endcan
@can('product_type_delete')
<input type="hidden" id="product_type_delete" value="1">
@endcan
@can('product_type_export')
<input type="hidden" id="product_type_export" value="1">
@endcan


<section id="column-search-datatable">
  @can('product_type_access')
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
              <h4 class="card-title"> {{ trans('cruds.productType.title') }}</h4>
            </div>
            <div class="card-datatable">
              <table class="dt-column-search table table-responsive">
                <thead>
                  <tr>
                      <th></th>
                      <th width="15%">{{ trans('cruds.productType.fields.id') }}</th>
                      <th>{{ trans('cruds.productType.fields.title') }}</th>
                      <th>{{ trans('cruds.productType.fields.status') }}</th>
                      <th>{{ trans('global.actions') }}</th>
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
     let getListingUrl = "{{ route('admin.product-types.index') }}";
     let createUrl = "{{ route('admin.product-types.create') }}";
     let deleteUrl = "{{ url('api/admin/product-types/delete') }}"
     let destroyUrl = "{{ url('api/admin/product-types/massdestroy') }}"
     let displayUrl = "{{ url('admin/product-types') }}";
    </script>

 <script src="{{ asset(mix('adminassets/js/productTypes/productTypes.min.js')) }}"></script>

@endsection