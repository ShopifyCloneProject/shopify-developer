@extends('layouts/contentLayoutMaster')

@section('title', 'Products')

@section('vendor-style')
  {{-- vendor css files --}}
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/dataTables.bootstrap4.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/responsive.bootstrap4.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/buttons.bootstrap4.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/rowGroup.bootstrap4.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/file-uploaders/dropzone.min.css')) }}">
@endsection
@section('page-style')
  <!-- Page css files -->
  <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-file-uploader.css')) }}">
  <style type="text/css">
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
@section('content')

{{-- @can('product_create') 
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.products.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.product.title_singular') }}
            </a>
        </div>
    </div>
@endcan --}}
@if($msg != '')
<div class="demo-spacing-0">
  <div class="alert alert-warning" role="alert">
    <div class="alert-body">
      <span>{{ $msg }}</span>
    </div>
  </div>
</div>
@endif

@can('product_create')
<input type="hidden" id="product_create" value="1">
@endcan
@can('product_edit')
<input type="hidden" id="product_edit" value="1">
@endcan
@can('product_delete')
<input type="hidden" id="product_delete" value="1">
@endcan

<section id="basic-datatable">
@can('product_access')
  <div class="row" id="column-search-datatable">
    <div class="col-12">
      <div class="card">
        <div class="card-datatable">
          <table class="dt-column-search  table table-responsive" >
            <thead>
              <tr>
                <th>{{ trans('global.id') }}</th>
                <th>{{ trans('global.name') }}</th>
                <th>{{ trans('global.status') }}</th>
                <th>{{ trans('global.inventory') }}</th>
                <th>{{ trans('cruds.product.fields.product_type') }}</th>
                <th>{{ trans('cruds.product.fields.vendor') }}</th>
                <th>{{ trans('global.actions') }}</th>
              </tr>
               <tr class="bg-gradient-secondary">
                    <td></td>
                    <td>
                        <input class="form-control form-control-sm search" type="text" placeholder="{{ trans('global.search') }}  {{ trans('global.name') }}">
                    </td>
                    <td>
                       <select class="form-control-sm search">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach(App\Models\Product::STATUS_SELECT as $key => $item)
                                <option value="{{ $key }}">{{ $item }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                    </td>
                    <td>
                        <select class="form-control-sm search">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach($product_types as $key => $item)
                                <option value="{{ $item->title }}">{{ $item->title }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select class="form-control-sm search">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach($vendors as $key => $item)
                                <option value="{{ $item->name }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td></td>
                  </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>
  @endcan

  <!-- import file modal -->
  <div class="vertical-modal-ex">
    <div class="modal fade" id="modalImport" tabindex="-1" role="dialog" aria-labelledby="modalImportTitle" aria-hidden="true">
    @can('product_import_access')
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
              <div class="custom-control custom-radio mb-1 mr-1">
                <input type="radio" id="shopify" name="importtype" class="custom-control-input" value="shopify" checked />
                <label class="custom-control-label" for="shopify">Shopify</label>
              </div>
              <div class="custom-control custom-radio mb-1">
                <input type="radio" id="wordpress" name="importtype" class="custom-control-input" value="wordpress" />
                <label class="custom-control-label" for="wordpress">Wordpress</label>
              </div>
            </div>
          {{--   <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button> --}}
            <button type="button" class="btn btn-primary" id="uploadAndContinue" disabled="disabled">Upload And Continue</button>
          </div>
        </div>
      @endcan
      </div>
    </div>

    <div class="modal fade" id="modalExport" tabindex="-1" role="dialog" aria-labelledby="modalExportTitle" aria-hidden="true">
      @can('product_export_access')
      <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalExportTitle">Export products</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <!-- single file upload starts -->
              <input type="hidden" name="filename" value="" id="importFileName">
              <input type="hidden"  value="" id="exportModal">
              <div class="row">
                <div class="col-12">
                    <p class="card-text">
                      This CSV file can update all product information except for inventory quantities.
                    </p>
                    <form  id="export-file">
                        <div class="export-option">
                          <h6 class="mb-1">Export</h6>
                          <div class="form-group">
                              <div class="custom-control custom-radio mb-1">
                                <input type="radio" id="option_1" name="export_option" class="custom-control-input" value="current_page" checked />
                                <label class="custom-control-label" for="option_1">Current page</label>
                              </div>
                          </div>
                          <div class="form-group">
                              <div class="custom-control custom-radio mb-1">
                                <input type="radio" id="option_2" name="export_option" class="custom-control-input" value="all_products" />
                                <label class="custom-control-label" for="option_2">All products</label>
                              </div>
                          </div>
                          <div class="form-group">
                              <div class="custom-control custom-radio mb-1">
                                <input type="radio" id="option_3" name="export_option" class="custom-control-input" value="selected" />
                                <label class="custom-control-label" for="option_3">Selected products</label>
                              </div>
                          </div>
                        </div>
                        <div class="export-as--option">
                          <h6 class="mb-1">Export as</h6>
                          <div class="form-group">
                              <div class="custom-control custom-radio mb-1">
                                <input type="radio" id="option_4" name="export_as" class="custom-control-input" value="csv" checked/>
                                <label class="custom-control-label" for="option_4">CSV for Excel, Numbers, or other spreadsheet programs</label>
                              </div>
                          </div>
                        </div>
                    </form>
                </div>
              </div>
            <!-- single file upload ends -->
          </div>
          <div class="modal-footer">
            <div class="form-group mr-auto d-flex">
              <div class="custom-control custom-radio mb-1 mr-1">
                <input type="radio" id="exportShopify" name="exporttype" class="custom-control-input" value="shopify" checked />
                <label class="custom-control-label" for="exportShopify">Shopify</label>
              </div>
              <div class="custom-control custom-radio mb-1">
                <input type="radio" id="exportWordpress" name="exporttype" class="custom-control-input" value="wordpress" />
                <label class="custom-control-label" for="exportWordpress">Wordpress</label>
              </div>
            </div>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary" id="exportProducts">Export products</button>
          </div>
        </div>
      </div>
      @endcan

    </div>
  </div>
  <!-- Vertical modal end-->
</section>
@endsection
@section('vendor-script')
  @include('admin/partials/datatableJs')
  <script src="{{ asset(mix('vendors/js/extensions/dropzone.min.js')) }}"></script>
@endsection

@section('page-script')
  <script type="text/javascript">
    var createUrl = '{{ route('admin.products.create') }}';
    let getListingUrl = "{{ route('admin.products.index') }}";
    let storeUrl = "{{ route('admin.products.store') }}";
    let deleteUrl = "{{ url('api/admin/products/delete') }}"
     let destroyUrl = "{{ url('api/admin/products/massdestroy') }}"
  </script>
@can('product_import_access')
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
  <script src="{{ asset(mix('adminassets/js/product/product.min.js')) }}"></script>
@endsection