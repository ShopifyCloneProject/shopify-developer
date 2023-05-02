@extends('layouts/contentLayoutMaster')
@section('title','Sales Channel')

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

 @can('sales_channel_create')
<input type="hidden" id="sales_channel_create" value="1">
@endcan
@can('sales_channel_edit')
<input type="hidden" id="sales_channel_edit" value="1">
@endcan
@can('sales_channel_show')
<input type="hidden" id="sales_channel_show" value="1">
@endcan
@can('sales_channel_delete')
<input type="hidden" id="sales_channel_delete" value="1">
@endcan
@can('sales_channel_export')
<input type="hidden" id="sales_channel_export" value="1">
@endcan


<section id="column-search-datatable">
  @can('sales_channel_access')
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
              <h4 class="card-title"> {{ trans('cruds.salesChannel.title') }}</h4>
            </div>
            <div class="card-datatable">
              <table class="dt-column-search table table-responsive">
                <thead>
                  <tr>
                    <th>
                    </th>
                    <th width="5%">
                        {{ trans('cruds.salesChannel.fields.id') }}
                    </th>
                    <th width="35%">
                        {{ trans('cruds.salesChannel.fields.product') }}
                    </th>
                    <th>
                        {{ trans('cruds.salesChannel.fields.start_date') }}
                    </th>
                    <th>
                        {{ trans('cruds.salesChannel.fields.end_date') }}
                    </th>
                    <th>
                        {{ trans('cruds.salesChannel.fields.status') }}
                    </th>
                    <th>
                      {{ trans('global.actions') }}
                    </th>
                  </tr>
                  <tr class="bg-gradient-secondary">
                        <td>
                    </td>
                    <td width="5%">
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td width="35%">
                        <select class="search">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach($products as $key => $item)
                                <option value="{{ $item->title }}">{{ $item->title }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                    </td>
                    <td>
                    </td>
                    <td>
                        <select class="search" strict="true">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach(App\Models\SalesChannel::STATUS_RADIO as $key => $item)
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
     let getListingUrl = "{{ route('admin.sales-channels.index') }}";
     let createUrl = "{{ route('admin.sales-channels.create') }}";
     let deleteUrl = "{{ url('api/admin/sales-channels/delete') }}"
     let destroyUrl = "{{ url('api/admin/sales-channels/massdestroy') }}"
     let displayUrl = "{{ url('admin/sales-channels') }}";
    </script>

 <script src="{{ asset(mix('adminassets/js/saleschannels/saleschannels.min.js')) }}"></script>

@endsection

{{-- @section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('sales_channel_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.sales-channels.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).data(), function (entry) {
          return entry.id
      });

      if (ids.length === 0) {
        alert('{{ trans('global.datatables.zero_selected') }}')

        return
      }

      if (confirm('{{ trans('global.areYouSure') }}')) {
        $.ajax({
          headers: {'x-csrf-token': _token},
          method: 'POST',
          url: config.url,
          data: { ids: ids, _method: 'DELETE' }})
          .done(function () { location.reload() })
      }
    }
  }
  dtButtons.push(deleteButton)
@endcan

  let dtOverrideGlobals = {
    buttons: dtButtons,
    processing: true,
    serverSide: true,
    retrieve: true,
    aaSorting: [],
    ajax: "{{ route('admin.sales-channels.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'id', name: 'id' },
{ data: 'product_title', name: 'product.title' },
{ data: 'start_date', name: 'start_date' },
{ data: 'end_date', name: 'end_date' },
{ data: 'status', name: 'status' },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  };
  let table = $('.datatable-SalesChannel').DataTable(dtOverrideGlobals);
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
let visibleColumnsIndexes = null;
$('.datatable thead').on('input', '.search', function () {
      let strict = $(this).attr('strict') || false
      let value = strict && this.value ? "^" + this.value + "$" : this.value

      let index = $(this).parent().index()
      if (visibleColumnsIndexes !== null) {
        index = visibleColumnsIndexes[index]
      }

      table
        .column(index)
        .search(value, strict)
        .draw()
  });
table.on('column-visibility.dt', function(e, settings, column, state) {
      visibleColumnsIndexes = []
      table.columns(":visible").every(function(colIdx) {
          visibleColumnsIndexes.push(colIdx);
      });
  });
initComplete: function(){
                if($('#sales_channel_create').length != 1)
                {
                    $('#create_new').remove();
                }
            }
});

</script>
@endsection --}}