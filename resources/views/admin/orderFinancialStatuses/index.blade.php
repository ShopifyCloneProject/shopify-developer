@extends('layouts/contentLayoutMaster')
@section('title','Order Financial Status')
@section('vendor-style')
   @include('admin/partials/datatableCss')
@endsection
@section('page-style')
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

@can('order_financial_status_create')
<input type="hidden" id="order_financial_status_create" value="1">
@endcan
@can('order_financial_status_edit')
<input type="hidden" id="order_financial_status_edit" value="1">
@endcan
@can('order_financial_status_show')
<input type="hidden" id="order_financial_status_show" value="1">
@endcan
@can('order_financial_status_delete')
<input type="hidden" id="order_financial_status_delete" value="1">
@endcan
@can('order_financial_status_export')
<input type="hidden" id="order_financial_status_export" value="1">
@endcan

<section id="column-search-datatable">
  @can('order_financial_status_access')
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
              <h4 class="card-title"> {{ trans('cruds.orderFinancialStatus.title') }}</h4>
            </div>
            <div class="card-datatable">
              <table class="dt-column-search table table-responsive">
                <thead>
                  <tr>
                    <th>
                    </th>
                    <th width="10%">
                        {{ trans('cruds.orderFinancialStatus.fields.id') }}
                    </th>
                    <th>
                        {{ trans('cruds.orderFinancialStatus.fields.title') }}
                    </th>
                    <th>
                        {{ trans('cruds.orderFinancialStatus.fields.status') }}
                    </th>
                    <th>
                        {{ trans('global.actions') }}
                    </th>
                  </tr>
                  <tr class="bg-gradient-secondary">
                        <td>
                        </td>
                        <td width="10%">
                            <input class="form-control form-control-sm search" type="text" placeholder="{{ trans('global.search') }}  {{ trans('cruds.orderFinancialStatus.fields.id') }}">
                        </td>
                        <td>
                            <input class="form-control form-control-sm search" type="text" placeholder="{{ trans('global.search') }} {{ trans('cruds.orderFinancialStatus.fields.title') }}">
                        </td>
                        <td>
                           <select class="search" strict="true">
                                <option value>{{ trans('global.all') }}</option>
                                @foreach(App\Models\OrderFinancialStatus::STATUS_RADIO as $key => $item)
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
     let getListingUrl = "{{ route('admin.order-financial-statuses.index') }}";
     let createUrl = "{{ route('admin.order-financial-statuses.create') }}";
     let deleteUrl = "{{ url('api/admin/order-financial-statuses/delete') }}"
     let destroyUrl = "{{ url('api/admin/order-financial-statuses/massdestroy') }}"
     let displayUrl = "{{ url('admin/order-financial-statuses') }}";
    </script>

 <script src="{{ asset(mix('adminassets/js/orderfinancialstatuses/orderfinancialstatuses.min.js')) }}"></script>

@endsection

