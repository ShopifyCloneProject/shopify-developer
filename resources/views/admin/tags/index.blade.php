@extends('layouts/contentLayoutMaster')

@section('vendor-style')
   @include('admin/partials/datatableCss')
@endsection
@section('page-style')
<link rel="stylesheet" type="text/css" href="{{asset('css/base/plugins/forms/pickers/form-flat-pickr.css')}}">
@endsection

@section('title')
  {{ trans('cruds.tag.title_singular') }}
@endsection

@section('content')

@can('tag_create')
    <input type="hidden" id="tag_create" value="1">
  @endcan
  @can('tag_edit')
  <input type="hidden" id="tag_edit" value="1">
  @endcan
  @can('tag_delete')
  <input type="hidden" id="tag_delete" value="1">
  @endcan
  @can('tag_export')
  <input type="hidden" id="tag_export" value="1">
  @endcan

<section id="column-search-datatable">
  @can('tag_access')
  <div class="row">
    <div class="col-12">
      <div class="card">
       {{--  <div class="card-header border-bottom">
          <h4 class="card-title">{{ trans('cruds.tag.title') }}</h4>
        </div> --}}
        <div class="card-datatable">
          <table class="dt-column-search table">
            <thead>
              <tr >
                  <th></th>
                  <th width="15%">
                      {{ trans('cruds.tag.fields.id') }}
                  </th>
                  <th>
                      {{ trans('cruds.tag.fields.title') }}
                  </th>
                  <th>
                      {{ trans('cruds.tag.fields.status') }}
                  </th>
                  <th></th>
              </tr>
              <tr class="bg-gradient-secondary">
                    <td>
                    </td>
                    <td width="15%">
                        <input class="form-control form-control-sm search" type="text" placeholder="{{ trans('global.search') }} {{ trans('cruds.tag.title_singular') }} {{ trans('cruds.tag.fields.id') }}">
                    </td>
                    <td>
                        <input class="form-control form-control-sm search" type="text" placeholder="{{ trans('global.search') }} {{ trans('cruds.tag.title_singular') }} {{ trans('cruds.tag.fields.title') }}">
                    </td>
                    <td>
                        <select class="form-control-sm search">
                            <option value>{{ trans('global.all') }}</option>
                             @foreach(App\Models\Tag::STATUS_RADIO as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
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

  <!-- Modal to add new record -->
  <div class="modal modal-slide-in fade" id="modals-slide-in">
    <div class="modal-dialog sidebar-sm">
      <form class="add-new-record modal-content pt-0">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
        <div class="modal-header mb-1">
          <h5 class="modal-title" id="exampleModalLabel">{{ trans('global.new') }} {{ trans('cruds.tag.title_singular') }}</h5>
        </div>
        <div class="modal-body flex-grow-1">
          <div class="form-group">
            <label class="form-label" for="basic-icon-default-fullname">{{ trans('cruds.tag.fields.title') }}</label>
            <input type="text" class="form-control" id="addTitle" />
          </div>
          <div class="form-group">
            <label class="form-label" for="basic-icon-default-post">{{ trans('cruds.tag.fields.status') }}</label>
            <select class="form-control" name="status" id="addStatus">
               @foreach(App\Models\Tag::STATUS_RADIO as $key => $label)
                  <option value="{{ $key }}">{{ $label }}</option>
                @endforeach
            </select>
          </div>
          <button type="button" class="btn btn-primary data-submit mr-1">{{ trans('global.submit') }}</button>
          <button type="reset" class="btn btn-outline-secondary" data-dismiss="modal">{{ trans('global.cancel') }}</button>
          <input type="reset" name="reset" value="Reset" id="reset" style="display: none;">
        </div>
      </form>
    </div>
  </div>
  <!-- Edit Modals start -->
  <div class="modal fade text-left" id="default" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <form class="add-new-record modal-content pt-0">
          <input type="hidden" id="tagId">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" id="myModalLabel1">{{ trans('global.edit') }} {{ trans('cruds.tag.title_singular') }}</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <label class="form-label" for="basic-icon-default-fullname">{{ trans('cruds.tag.fields.title') }}</label>
                <input type="text" class="form-control" id="editTitle"/>
              </div>
              <div class="form-group">
                <label class="form-label" for="basic-icon-default-post">{{ trans('cruds.tag.fields.status') }}</label>
                <select class="form-control" name="status" id="editStatus">
                   @foreach(App\Models\Tag::STATUS_RADIO as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary data-edit">{{ trans('global.submit') }}</button>
              <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">{{ trans('global.cancel') }}</button>
            </div>
          </div>
        </form>
    </div>
  </div>
  <!-- Edit Modals end -->
</section>
@endsection

@section('vendor-script')
  @include('admin/partials/datatableJs')
@endsection

@section('page-script')
   <script type="text/javascript">
       let getListingUrl = "{{ route('admin.tags.index') }}";
       let storeUrl = "{{ route('admin.tags.store') }}";
       let deleteUrl = "{{ route('admin.tags.massDestroy') }}";

   </script>
   <script src="{{ asset(mix('adminassets/js/tag/tag.min.js')) }}"></script>
@endsection