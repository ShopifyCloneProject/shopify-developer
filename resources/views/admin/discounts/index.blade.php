@extends('layouts/contentLayoutMaster')

@section('title','Discounts')
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

@can('discounts_create')
<input type="hidden" id="discounts_create" value="1">
@endcan
@can('discounts_edit')
<input type="hidden" id="discounts_edit" value="1">
@endcan
@can('discounts_delete')
<input type="hidden" id="discounts_delete" value="1">
@endcan
@can('discounts_export')
<input type="hidden" id="discounts_export" value="1">
@endcan


<section id="column-search-datatable">
  @can('discounts_access')
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header border-bottom">
              <h4 class="card-title"> {{ trans('cruds.discounts.title') }}</h4>
            </div>
            <div class="card-datatable">
              <table class="dt-column-search table table-responsive">
                <thead>
                   <tr>
                    <th>
                    </th>
                    <th>
                        {{ trans('cruds.discounts.fields.id') }}
                    </th>
                    <th>
                        {{ trans('cruds.discounts.fields.code') }}
                    </th>
                    <th>
                        {{ trans('cruds.discounts.fields.initial_value') }}
                    </th>
                    <th>
                        {{ trans('cruds.discounts.fields.product_or_collection') }}
                    </th>
                    <th>
                        {{ trans('cruds.discounts.fields.status') }}
                    </th>
                    <th>
                        {{ trans('cruds.discounts.fields.starting_date') }}
                    </th>
                    <th>
                        {{ trans('cruds.discounts.fields.expiry_date') }}
                    </th>
                    <th>
                       {{ trans('global.actions') }}
                    </th>
                </tr>
                <tr class="bg-gradient-secondary">
                    <td>
                    </td>
                    <td>
                        <input class="form-control form-control-sm search" type="text" placeholder="{{ trans('global.search') }} {{ trans('cruds.discounts.fields.id') }}">
                    </td>
                    <td>
                        <input class="form-control form-control-sm search" type="text" placeholder="{{ trans('global.search') }} {{ trans('cruds.discounts.fields.code') }}">
                    </td>
                    <td>
                        <input class="form-control form-control-sm search" type="text" placeholder="{{ trans('global.search') }} {{ trans('cruds.discounts.fields.initial_value') }}">
                    </td>
                    <td>
                        <select class="form-control form-control-sm search" strict="true">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach(App\Models\Discount::PRODUCT_OR_COLLECTION_RADIO as $key => $item)
                                <option value="{{ $key }}">{{ $item }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select class="form-control form-control-sm search" strict="true">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach(App\Models\Discount::STATUS_RADIO as $key => $item)
                                <option value="{{ $key }}">{{ $item }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input class="form-control form-control-sm search" type="text" placeholder="{{ trans('global.search') }} {{ trans('cruds.discounts.fields.starting_date') }}">
                    </td>
                    <td>
                        <input class="form-control form-control-sm search" type="text" placeholder="{{ trans('global.search') }} {{ trans('cruds.discounts.fields.expiry_date') }}">
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
     let getListingUrl = "{{ route('admin.discounts.index') }}";
     let createUrl = "{{ route('admin.discounts.create') }}";
    let deleteUrl = "{{ route('admin.discounts.massDestroy') }}";
     let displayUrl = "{{ url('admin/discounts') }}";
    </script>

 <script src="{{ asset(mix('adminassets/js/discounts/discounts.min.js')) }}"></script>

@endsection
