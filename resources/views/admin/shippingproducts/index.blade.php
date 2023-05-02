@extends('layouts/contentLayoutMaster')

@section('title', 'Shipping Products')

@section('vendor-style')
   @include('admin/partials/datatableCss')
   <link rel="stylesheet" href="{{ asset(mix('vendors/css/file-uploaders/dropzone.min.css')) }}">
@endsection
@section('page-style')
  <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-file-uploader.css')) }}">
   <style>
      .conditionx-list {
          font-size: 12px;
          line-height: 20px;
      }
      .custom-switch .custom-control-label .switch-icon-left svg{
        height: 23px;
      }
      .custom-switch .custom-control-label .switch-icon-right svg{
        height: 23px;
      }


      .dropzone {
        min-height: 250px;
    }
    .dropzone .dz-message {
      font-size: 1.5rem;
    }
    .dropzone .dz-message:before {
      top: 10rem;
      width: 40px;
      height: 40px;
    }
    /** Preview of collections of uploaded documents **/
    .preview-container{
      position: relative;
      bottom: 0px;
      width: 100%;
      margin: auto;
      top: 25px;
      visibility: hidden;
    }
    .secondary-content.actions {
        margin-left: 20px;
        margin-top: 20px;
    }
    .collection-item {
        padding: 10px;
    }
    .preview-container #previews{
      max-height: 400px;
      overflow: auto; 
    }
    .preview-container #previews .zdrop-info{
      width: 95%;
      font-weight: bold;
    }
    .preview-container #previews.collection{
      margin: 0;
      box-shadow: none;
    }
    .preview-container #previews.collection .collection-item {
        background-color: #e0e0e0;
    }
    .preview-container #previews.collection .actions a {
        color: red;
        font-weight: bold;
        font-size: 17px;
        position: relative;
        top: -7px;
        line-height: 1.6;
    }
    .preview-container #previews.collection .dz-error-message{
      font-size: 0.8em;
      margin-top: -12px;
      color: #F44336;
    }
    .progress .determinate {
        top: 0;
        left: 0;
        bottom: 0;
        background-color: #26a69a;
        transition: width .3s linear;
    }
   </style>
@endsection
@can('shipping_product_show')
<input type="hidden" id="shipping_product_show" value="1">
@endcan
@can('shipping_product_export')
<input type="hidden" id="shipping_product_export" value="1">
@endcan
@can('shipping_product_delete')
<input type="hidden" id="shipping_product_delete" value="1">
@endcan


@section('content')
@can('shipping_product_access')
    <section id="column-search-datatable">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-datatable">
              <table class="dt-column-search table">
                <thead>
                  <tr>
                    <th></th>
                    <th>{{ trans('cruds.shippingproducts.fields.id') }}</th>
                    <th>{{ trans('cruds.shippingproducts.fields.orders') }}</th>
                    <th>{{ trans('cruds.shippingproducts.fields.customer') }}</th>
                    <th>{{ trans('cruds.shippingproducts.fields.mobile') }}</th>
                    <th>{{ trans('cruds.shippingproducts.fields.shipping') }}</th>
                    <th>{{ trans('cruds.shippingproducts.fields.approved') }}</th>
                    <th>{{ trans('cruds.shippingproducts.fields.order_id') }}</th>
                    <th>{{ trans('global.actions') }}</th>
                  </tr>
                  <tr class="bg-gradient-secondary">
                    <td></td>
                    <td>
                      <input class="form-control form-control-sm search" type="text" placeholder="{{ trans('global.search') }}  {{ trans('cruds.shippingproducts.fields.id') }}">
                    </td>
                    <td>
                      <input class="form-control form-control-sm search" type="text" placeholder="{{ trans('global.search') }}  {{ trans('cruds.shippingproducts.fields.orders') }}">
                    </td>
                    <td>
                      <input class="form-control form-control-sm search" type="text" placeholder="{{ trans('global.search') }}  {{ trans('cruds.shippingproducts.fields.customer') }}">
                    </td>
                    <td>
                      <input class="form-control form-control-sm search" type="text" placeholder="{{ trans('global.search') }}  {{ trans('cruds.shippingproducts.fields.mobile') }}">
                    </td>
                    <td>
                      <select class="form-control-sm search">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach($shippings as $key => $item)
                                <option value="{{ $item->id }}">{{ $item->title }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                      <select class="form-control-sm search">
                            <option value>{{ trans('global.all') }}</option>
                            <option value="1">{{ trans('global.approved') }}</option>
                            <option value="0">{{ trans('global.not_approved') }}</option>
                      </select>
                    </td>
                    <td></td>
                    <td></td>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
  </section>
@endcan


@can('shipping_product_import_access')
<div class="vertical-modal-ex">
    <div class="modal fade" id="modalImport" tabindex="-1" role="dialog" aria-labelledby="modalImportTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalImportTitle">Import products by CSV</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <!-- single file upload starts -->
              <input type="hidden" name="filename" value="" id="importFileName">
              <input type="hidden"  value="" id="importModal">
              <div class="row">
                <div class="col-12">
                  <div class="card">
                    <div class="card-body">
                      <p class="card-text">
                        Upload a CSV template to import products.
                      </p>
                      <div class="alert alert-danger d-none" role="alert">
                        <div class="alert-body">
                          <p>There was an error importing your CSV file. Please try importing the CSV file again.</p>
                          <p>Invalid CSV Header: Missing headers.</p>
                        </div>
                      </div>
                      <form action="#" class="dropzone dropzone-area fileuploader" id="dpz-import-file">
                        <div class="dz-message">Drop files here or click to upload.</div>
                      </form>
                      <div class="preview-container">
                          <div class="collection card" id="previews">
                             <div class="collection-item clearhack valign-wrapper item-template d-flex" id="zdrop-template">
                                <div class="left pv zdrop-info" data-dz-thumbnail>
                                   <div>
                                      <span data-dz-name></span> <span data-dz-size></span>
                                   </div>
                                   <div class="progress">
                                      <div class="determinate" style="width:0" data-dz-uploadprogress></div>
                                   </div>
                                   <div class="dz-error-message"><span data-dz-errormessage></span></div>
                                </div>
                                <div class="secondary-content actions">
                                   <a href="#!" data-dz-remove class="btn-floating ph red white-text waves-effect waves-light">X</a>
                                </div>
                             </div>
                          </div>
                       </div>
                    </div>
                  </div>
                </div>
              </div>
            <!-- single file upload ends -->
          </div>
          <div class="modal-footer">
            <div class="form-group mr-auto d-flex">
                  @foreach ($objShippingMethods as $key => $item)
                    <div class="custom-control custom-radio mb-1 mr-1">
                      <input type="radio" name="importshippingtype" id="importradio_{{$key}}" class="custom-control-input" value="{{ $item }}"/>
                      <label class="custom-control-label" for="importradio_{{$key}}">{{ $item }}</label>
                    </div>
                  @endforeach
                </div> 
          {{--   <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button> --}}
            <button type="button" class="btn btn-primary" id="uploadAndContinue" disabled="disabled">Upload And Continue</button>
          </div>
        </div>
      </div>
    </div>
</div>
@endcan



@endsection

@section('vendor-script')
   @include('admin/partials/datatableJs')
   <script src="{{ asset(mix('vendors/js/extensions/dropzone.min.js')) }}"></script>
@endsection

@section('page-script')
  <script type="text/javascript">
      let getListingUrl = "{{ route('admin.shippingproducts.index') }}";
      let handleActionUrl = "{{ route('admin.handleShippingActions') }}";
      let deleteUrl = "{{ route('admin.shippingproducts.massDestroy') }}";
      let storeUrl = "{{ route('admin.shippingproducts.store') }}";
      let importUrl = "{{ route('admin.shippingproducts.import') }}";
  </script>

  @can('shipping_product_import_access')
  <script type="text/javascript">
      Dropzone.autoDiscover = false;

      $(document).ready(function() {
          initFileUploader("#dpz-import-file");

          function initFileUploader(target) {
              var CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute("content");
              var previewNode = document.querySelector("#zdrop-template");
              previewNode.id = "";
              var previewTemplate = previewNode.parentNode.innerHTML;
              previewNode.parentNode.removeChild(previewNode);
              var zdrop = new Dropzone(target, {
                  url: storeUrl  + '/file',
                  maxFiles: 1,
                  maxFilesize: 30,
                  previewTemplate: previewTemplate,
                  previewsContainer: "#previews",
                  // clickable: "#upload-label",
                  acceptedFiles: '.csv, text/csv, application/vnd.ms-excel, application/csv, text/x-csv, application/x-csv, text/comma-separated-values, text/x-comma-separated-values'
              });

              zdrop.on("success", function(file, response) {
                if(typeof response.name !== undefined && response.name != ''){
                  $('#importFileName').val(response.name);
                  $('#uploadAndContinue').prop('disabled', false);
                }
              });

              zdrop.on("sending", function(file, xhr, formData) {
                 formData.append("_token", CSRF_TOKEN);
              });

              zdrop.on("addedfile", function(file) {
                  $('.preview-container').css('visibility', 'visible');
                  $('.dropzone-area').hide();
              });

              zdrop.on("removedfile", function(file) {
                  $('.alert-danger').addClass('d-none');
                  $('.dropzone-area').show();
                  $('#uploadAndContinue').prop('disabled', true);
                  $('#importFileName').val('');
              });

              zdrop.on("totaluploadprogress", function(progress) {
                  var progr = document.querySelector(".progress .determinate");
                  if (progr === undefined || progr === null)
                      return;
                  progr.style.width = progress + "%";
              });

              zdrop.on('dragenter', function() {
                  $('.fileuploader').addClass("active");
              });

              zdrop.on('dragleave', function() {
                  $('.fileuploader').removeClass("active");
              });

              zdrop.on('drop', function() {
                  $('.fileuploader').removeClass("active");
              });
          }

          $('#modalImport').on('hide.bs.modal', function () {
             Dropzone.forElement('#dpz-import-file').removeAllFiles(true)
          });
      });
  </script>
  @endcan
  <script src="{{ asset(mix('adminassets/js/shippingproducts/shippingproducts.min.js')) }}"></script>
@endsection