@extends('layouts/contentLayoutMaster')

@section('vendor-style')
   @include('admin/partials/datatableCss')
@endsection
@section('page-style')
<link rel="stylesheet" type="text/css" href="{{asset('css/base/plugins/forms/pickers/form-flat-pickr.css')}}">
@endsection

@section('title')
  {{ trans('cruds.variantOption.title_singular') }}
@endsection

@section('content')

@can('variant_option_create')
    <input type="hidden" id="variant_option_create" value="1">
  @endcan
  @can('variant_option_edit')
  <input type="hidden" id="variant_option_edit" value="1">
  @endcan
  @can('variant_option_delete')
  <input type="hidden" id="variant_option_delete" value="1">
  @endcan
  @can('variant_option_export')
  <input type="hidden" id="variant_option_export" value="1">
  @endcan

<section id="column-search-datatable">
  @can('product_variant_option_access')
  <div class="row">
    <div class="col-12">
      <div class="card">
       {{--  <div class="card-header border-bottom">
          <h4 class="card-title">{{ trans('cruds.variantOption.title') }}</h4>
        </div> --}}
        <div class="card-datatable">
          <table class="dt-column-search table">
            <thead>
              <tr >
                <th></th>
                <th width="15%">
                    {{ trans('cruds.variantOption.fields.id') }}
                </th>
                <th>
                    {{ trans('cruds.variantOption.fields.options') }}
                </th>
                <th>
                    {{ trans('cruds.variantOption.fields.variant') }}
                </th>
                <th>
                    {{ trans('cruds.variantOption.fields.status') }}
                </th>
                <th></th>
              </tr>
              <tr class="bg-gradient-secondary">
                    <td>
                    </td>
                    <td width="15%">
                        <input class="form-control form-control-sm search" type="text" placeholder="{{ trans('global.search') }}  {{ trans('cruds.variantOption.fields.id') }}">
                    </td>
                    <td>
                        <input class="form-control form-control-sm search" type="text" placeholder="{{ trans('global.search') }}  {{ trans('cruds.variantOption.fields.options') }}">
                    </td>
                    <td>
                        <select class="form-control search" name="variant">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach($variants as $id => $entry)
                                  <option value="{{ $id }}">{{ $entry }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select class="form-control-sm search">
                            <option value>{{ trans('global.all') }}</option>
                             @foreach(App\Models\variantOption::STATUS_RADIO as $key => $label)
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
          <h5 class="modal-title" id="exampleModalLabel">{{ trans('global.new') }} {{ trans('cruds.variantOption.title_singular') }}</h5>
        </div>
        <div class="modal-body flex-grow-1">
          <div class="form-group">
            <label class="form-label" for="basic-icon-default-fullname">{{ trans('cruds.variantOption.fields.options') }}</label>
            <input type="text" class="form-control" id="addOption"/>
          </div>
          <div class="form-group">
            <label class="form-label" for="basic-icon-default-variant">{{ trans('cruds.variantOption.fields.variant') }}</label>
            <select class="form-control" name="variant" id="addVariant">
                @foreach($variants as $id => $entry)
                      <option value="{{ $id }}">{{ $entry }}</option>
                @endforeach
            </select>
          </div>
          <div class="form-group">
            <label class="form-label" for="basic-icon-default-status">{{ trans('cruds.variantOption.fields.status') }}</label>
            <select class="form-control" name="status" id="addStatus">
               @foreach(App\Models\VariantOption::STATUS_RADIO as $key => $label)
                  <option value="{{ $key }}">{{ $label }}</option>
                @endforeach
            </select>
          </div>
          <button type="button" class="btn btn-primary data-submit mr-1">{{ trans('global.submit') }}</button>
          <button type="reset" class="btn btn-outline-secondary" data-dismiss="modal">{{ trans('global.cancel') }}</button>
          <input type="reset" value="Reset"  id="reset" style="display: none;">
        </div>
      </form>
    </div>
  </div>
  <!-- Edit Modals start -->
  <div class="modal fade text-left" id="default" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <form class="add-new-record modal-content pt-0" id="frmAddOptions">
          <input type="hidden" id="optionsId">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" id="myModalLabel1">{{ trans('global.edit') }} {{ trans('cruds.variantOption.title_singular') }}</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
           <div class="modal-body flex-grow-1">
              <div class="form-group">
                <label class="form-label" for="basic-icon-default-fullname">{{ trans('cruds.variantOption.fields.options') }}</label>
                <input type="text" class="form-control" id="editOption"/>
              </div>
              <div class="form-group">
                <label class="form-label" for="basic-icon-default-post">{{ trans('cruds.variantOption.fields.variant') }}</label>
                <select class="form-control" name="variant" id="editVariant">
                  @foreach($variants as $id => $entry)
                      <option value="{{ $id }}">{{ $entry }}</option>
                  @endforeach
                </select>
              </div>
              <div class="form-group">
                <label class="form-label" for="basic-icon-default-post">{{ trans('cruds.variantOption.fields.status') }}</label>
                <select class="form-control" name="status" id="editStatus">
                   @foreach(App\Models\VariantOption::STATUS_RADIO as $key => $label)
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
       let getListingUrl = "{{ route('admin.variant-options.index') }}";
       let storeUrl = "{{ route('admin.variant-options.store') }}";
       let deleteUrl = "{{ route('admin.variant-options.massDestroy') }}";

   </script>
   <script src="{{ asset(mix('adminassets/js/variantoption/variantoptions.min.js')) }}"></script>
@endsection