@extends('layouts/contentLayoutMaster')
@section('title','Gift Card vendor')
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

 @can('gift_card_vendor_create')
<input type="hidden" id="gift_card_vendor_create" value="1">
@endcan
@can('gift_card_vendor_edit')
<input type="hidden" id="gift_card_vendor_edit" value="1">
@endcan
@can('gift_card_vendor_show')
<input type="hidden" id="gift_card_vendor_show" value="1">
@endcan
@can('gift_card_vendor_delete')
<input type="hidden" id="gift_card_vendor_delete" value="1">
@endcan
@can('pgift_card_vendor_export')
<input type="hidden" id="pgift_card_vendor_export" value="1">
@endcan

<section id="column-search-datatable">
  @can('gift_card_vendor_access')
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
              <h4 class="card-title"> {{ trans('cruds.giftCardVendor.title') }}</h4>
            </div>
            <div class="card-datatable">
              <table class="dt-column-search table table-responsive">
                <thead>
                  <tr>
                    <th>
                    </th>
                    <th width="10%">
                        {{ trans('cruds.giftCardVendor.fields.id') }}
                    </th>
                    <th width="40%">
                        {{ trans('cruds.giftCardVendor.fields.gift_card') }}
                    </th>
                    <th>
                        {{ trans('cruds.giftCardVendor.fields.vendor') }}
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
                        <select class="search">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach($vendors as $key => $item)
                                <option value="{{ $item->name }}">{{ $item->name }}</option>
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
     let getListingUrl = "{{ route('admin.gift-card-vendors.index') }}";
     let createUrl = "{{ route('admin.gift-card-vendors.create') }}";
     let deleteUrl = "{{ url('api/admin/gift-card-vendors/delete') }}"
     let destroyUrl = "{{ url('api/admin/gift-card-vendors/massdestroy') }}"
     let displayUrl = "{{ url('admin/gift-card-vendors') }}";
    </script>

 <script src="{{ asset(mix('adminassets/js/giftcardvendor/giftcardvendor.min.js')) }}"></script>

@endsection

{{-- @endsection
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('gift_card_vendor_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.gift-card-vendors.massDestroy') }}",
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
    ajax: "{{ route('admin.gift-card-vendors.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'id', name: 'id' },
{ data: 'gift_card_title', name: 'gift_card.title' },
{ data: 'vendor_name', name: 'vendor.name' },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  };
  let table = $('.datatable-GiftCardVendor').DataTable(dtOverrideGlobals);
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
                if($('#gift_card_vendor_create').length != 1)
                {
                    $('#create_new').remove();
                }
            }
});

</script>
@endsection --}}