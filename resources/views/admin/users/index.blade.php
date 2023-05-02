@extends('layouts/contentLayoutMaster')
@section('content')
@can('user_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.users.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.user.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.user.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-User">
            <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th>
                        {{ trans('cruds.user.fields.id') }}
                    </th>
                    <th>
                        {{ trans('cruds.user.fields.name') }}
                    </th>
                    <th>
                        {{ trans('cruds.user.fields.last_name') }}
                    </th>
                    <th>
                        {{ trans('cruds.user.fields.mobile') }}
                    </th>
                    <th>
                        {{ trans('cruds.user.fields.email') }}
                    </th>
                    <th>
                        {{ trans('cruds.user.fields.email_verified_at') }}
                    </th>
                    <th>
                        {{ trans('cruds.user.fields.gender') }}
                    </th>
                    <th>
                        {{ trans('cruds.user.fields.google') }}
                    </th>
                    <th>
                        {{ trans('cruds.user.fields.facebook') }}
                    </th>
                    <th>
                        {{ trans('cruds.user.fields.is_verified') }}
                    </th>
                    <th>
                        {{ trans('cruds.user.fields.company') }}
                    </th>
                    <th>
                        {{ trans('cruds.user.fields.email_notification_status') }}
                    </th>
                    <th>
                        {{ trans('cruds.user.fields.sms_notification_status') }}
                    </th>
                    <th>
                        {{ trans('cruds.user.fields.blocked') }}
                    </th>
                    <th>
                        {{ trans('cruds.user.fields.accept_marketing') }}
                    </th>
                    <th>
                        {{ trans('cruds.user.fields.total_spent') }}
                    </th>
                    <th>
                        {{ trans('cruds.user.fields.total_orders') }}
                    </th>
                    <th>
                        {{ trans('cruds.user.fields.tags') }}
                    </th>
                    <th>
                        {{ trans('cruds.user.fields.note') }}
                    </th>
                    <th>
                        {{ trans('cruds.user.fields.tax_exempt') }}
                    </th>
                    <th>
                        {{ trans('cruds.user.fields.pics') }}
                    </th>
                    <th>
                        {{ trans('cruds.user.fields.roles') }}
                    </th>
                    <th>
                        &nbsp;
                    </th>
                </tr>
            </thead>
        </table>
    </div>
</div>



@endsection
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('user_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.users.massDestroy') }}",
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
    ajax: "{{ route('admin.users.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'id', name: 'id' },
{ data: 'name', name: 'name' },
{ data: 'last_name', name: 'last_name' },
{ data: 'mobile', name: 'mobile' },
{ data: 'email', name: 'email' },
{ data: 'email_verified_at', name: 'email_verified_at' },
{ data: 'gender', name: 'gender' },
{ data: 'google', name: 'google' },
{ data: 'facebook', name: 'facebook' },
{ data: 'is_verified', name: 'is_verified' },
{ data: 'company', name: 'company' },
{ data: 'email_notification_status', name: 'email_notification_status' },
{ data: 'sms_notification_status', name: 'sms_notification_status' },
{ data: 'blocked', name: 'blocked' },
{ data: 'accept_marketing', name: 'accept_marketing' },
{ data: 'total_spent', name: 'total_spent' },
{ data: 'total_orders', name: 'total_orders' },
{ data: 'tags', name: 'tags' },
{ data: 'note', name: 'note' },
{ data: 'tax_exempt', name: 'tax_exempt' },
{ data: 'pics', name: 'pics', sortable: false, searchable: false },
{ data: 'roles', name: 'roles.title' },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  };
  let table = $('.datatable-User').DataTable(dtOverrideGlobals);
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
});

</script>
@endsection