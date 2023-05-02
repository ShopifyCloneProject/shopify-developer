@extends('layouts/contentLayoutMaster')
@section('title','Product Variant Options')

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

 @can('product_variant_option_create')
<input type="hidden" id="product_variant_option_create" value="1">
@endcan
@can('product_variant_option_edit')
<input type="hidden" id="product_variant_option_edit" value="1">
@endcan
@can('product_variant_option_show')
<input type="hidden" id="product_variant_option_show" value="1">
@endcan
@can('product_variant_option_delete')
<input type="hidden" id="product_variant_option_delete" value="1">
@endcan
@can('product_variant_option_export')
<input type="hidden" id="product_variant_option_export" value="1">
@endcan


<section id="column-search-datatable">
  @can('product_variant_option_access')
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
              <h4 class="card-title"> {{ trans('cruds.productVariantOption.title') }}</h4>
            </div>
            <div class="card-datatable">
              <table class="dt-column-search table table-responsive">
                <thead>
                  <tr>
                    <th width="5%">

                    </th>
                    <th width="5%">{{ trans('cruds.productVariantOption.fields.id') }}</th>
                    <th width="5%">{{ trans('cruds.productVariantOption.fields.product') }}</th>
                    <th width="5%">{{ trans('cruds.productVariantOption.fields.variant_option_1') }}</th>
                    <th width="5%">{{ trans('cruds.productVariantOption.fields.variant_option_2') }}</th>
                    <th width="5%">{{ trans('cruds.productVariantOption.fields.variant_option_3') }}</th>
                    <th width="5%">{{ trans('cruds.productVariantOption.fields.src') }}</th>
                    <th width="5%">{{ trans('cruds.productVariantOption.fields.src_alt_text') }}</th>
                    <th width="5%">{{ trans('cruds.productVariantOption.fields.price') }}</th>
                    <th width="5%">{{ trans('cruds.productVariantOption.fields.compare_at_price') }}</th>
                    <th width="5%">{{ trans('cruds.productVariantOption.fields.cost_per_item') }}</th>
                    <th width="5%">{{ trans('cruds.productVariantOption.fields.is_product_charge') }}</th>
                    <th width="5%">{{ trans('cruds.productVariantOption.fields.sku') }}</th>
                    <th width="5%">{{ trans('cruds.productVariantOption.fields.barcode') }}</th>
                    <th width="5%">{{ trans('cruds.productVariantOption.fields.is_track') }}</th>
                    <th width="5%">{{ trans('cruds.productVariantOption.fields.is_continue_selling') }}</th>
                    <th width="5%">{{ trans('cruds.productVariantOption.fields.is_physical_product') }}</th>
                    <th width="5%">{{ trans('cruds.productVariantOption.fields.weight') }}</th>
                    <th width="5%">{{ trans('cruds.productVariantOption.fields.weight_type') }}</th>
                    <th width="5%">{{ trans('cruds.productVariantOption.fields.country') }}</th>
                    <th width="5%">{{ trans('cruds.productVariantOption.fields.hs_code') }}</th>
                    <th width="5%">{{ trans('cruds.productVariantOption.fields.is_special_product') }}</th>
                    <th width="5%">{{ trans('cruds.productVariantOption.fields.special_price') }}</th>
                    <th width="5%">{{ trans('cruds.productVariantOption.fields.expiry_date') }}</th>
                    <th width="5%">{{ trans('cruds.productVariantOption.fields.special_product_status') }}</th>
                    <th width="5%">{{ trans('cruds.productVariantOption.fields.is_shipping') }}</th>
                    <th width="5%">{{ trans('cruds.productVariantOption.fields.is_taxable') }}</th>
                    <th width="5%">{{ trans('cruds.productVariantOption.fields.reorder') }}</th>
                    <th width="5%">{{ trans('global.actions') }}</th>
                </tr>
                  <tr class="bg-gradient-secondary">
                    <td width="5%">
                    </td>
                    <td width="5%">
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td width="5%">
                        <select class="search" width="100%">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach($products as $key => $item)
                                <option value="{{ $item->title }}">{{ $item->title }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td width="5%">
                        <select class="search">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach($variant_options as $key => $item)
                                <option value="{{ $item->options }}">{{ $item->options }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td width="5%">
                        <select class="search">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach($variant_options as $key => $item)
                                <option value="{{ $item->options }}">{{ $item->options }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td width="5%">
                        <select class="search">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach($variant_options as $key => $item)
                                <option value="{{ $item->options }}">{{ $item->options }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td width="5%">
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td width="5%">
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td width="5%">
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td width="5%">
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td width="5%">
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td width="5%">
                        <select class="search" strict="true">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach(App\Models\ProductVariantOption::IS_PRODUCT_CHARGE_RADIO as $key => $item)
                                <option value="{{ $key }}">{{ $item }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td width="5%">
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td width="5%">
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td width="5%">
                    </td>
                    <td width="5%">
                    </td>
                    <td width="5%">
                    </td>
                    <td width="5%">
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td width="5%">
                        <select class="search">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach($weightmanages as $key => $item)
                                <option value="{{ $item->title }}">{{ $item->title }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td width="5%">
                        <select class="search">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach($countries as $key => $item)
                                <option value="{{ $item->name }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td width="5%">
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td width="5%">
                    </td>
                    <td width="5%">
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td width="5%">
                    </td>
                    <td width="5%">
                    </td>
                    <td width="5%">
                    </td>
                    <td width="5%">
                    </td>
                    <td width="5%">
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td width="5%">
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
     let getListingUrl = "{{ route('admin.product-variant-options.index') }}";
     let createUrl = "{{ route('admin.product-variant-options.create') }}";
     let deleteUrl = "{{ url('api/admin/product-variant-options/delete') }}"
     let destroyUrl = "{{ url('api/admin/product-variant-options/massdestroy') }}"
     let displayUrl = "{{ url('admin/product-variant-options') }}";
    </script>

 <script src="{{ asset(mix('adminassets/js/productvariantoptions/productvariantoptions.min.js')) }}"></script>

@endsection
