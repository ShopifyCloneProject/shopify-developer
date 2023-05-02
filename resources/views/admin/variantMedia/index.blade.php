@extends('layouts/contentLayoutMaster')
@section('title','Variant Media')
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

 @can('variant_medium_create')
<input type="hidden" id="variant_medium_create" value="1">
@endcan
@can('variant_medium_edit')
<input type="hidden" id="variant_medium_edit" value="1">
@endcan
@can('variant_medium_show')
<input type="hidden" id="variant_medium_show" value="1">
@endcan
@can('variant_medium_delete')
<input type="hidden" id="variant_medium_delete" value="1">
@endcan
@can('variant_medium_export')
<input type="hidden" id="variant_medium_export" value="1">
@endcan

<section id="column-search-datatable">
  @can('variant_medium_access')
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
              <h4 class="card-title"> {{ trans('cruds.variantMedium.title') }}</h4>
            </div>
            <div class="card-datatable">
              <table class="dt-column-search table table-responsive">
                <thead>
                   <tr>
                    <th>

                    </th>
                    <th>
                        {{ trans('cruds.variantMedium.fields.id') }}
                    </th>
                    <th>
                        {{ trans('cruds.variantMedium.fields.product_variant') }}
                    </th>
                    <th width="15%">
                        {{ trans('cruds.variantMedium.fields.product') }}
                    </th>
                    <th>
                        {{ trans('cruds.variantMedium.fields.src') }}
                    </th>
                    <th>
                        {{ trans('cruds.variantMedium.fields.src_alt_text') }}
                    </th>
                    <th>
                        {{ trans('cruds.variantMedium.fields.is_default') }}
                    </th>
                    <th>
                        {{ trans('cruds.variantMedium.fields.reorder') }}
                    </th>
                      <th>{{ trans('global.actions') }}</th>
                </tr>
                  <tr class="bg-gradient-secondary">
                    <td>
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        <select class="search">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach($product_variant_options as $key => $item)
                                <option value="{{ $item->sku }}">{{ $item->sku }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td width="15%">
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
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
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
     let getListingUrl = "{{ route('admin.variant-media.index') }}";
     let createUrl = "{{ route('admin.variant-media.create') }}";
     let deleteUrl = "{{ url('api/admin/variant-media/delete') }}"
     let destroyUrl = "{{ url('api/admin/variant-media/massdestroy') }}"
     let displayUrl = "{{ url('admin/variant-media') }}";
    </script>

 <script src="{{ asset(mix('adminassets/js/variantmedia/variantmedia.min.js')) }}"></script>

@endsection
