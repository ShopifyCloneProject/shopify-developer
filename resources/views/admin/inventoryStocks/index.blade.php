@extends('layouts/contentLayoutMaster')

@section('title', 'Inventory')

@section('vendor-style')
   @include('admin/partials/datatableCss')
@endsection
@section('page-style')
 <style type="text/css">
   .dataTables_filter label, .dataTables_filter label input{
     width: 100% !important;
   }
   .location-toggle{
      width: 200px;
   }
   .location-name{
      width: 80%;
      overflow: hidden;
      display: inline-block;
      text-overflow: ellipsis;
      text-align: left;
    }
    @media (max-width: 768px) { 
      .location-toggle, .dt-action-buttons .buttons-collection,.dt-action-buttons .btn-group{
          width: 100%;
      }
      .dt-action-buttons .dt-buttons{
        display: block !important;
      }
   }
 </style>
@endsection


@section('content')

@can('inventory_stock_export')
  <input type="hidden" id="inventoryStock_export" value="1">
  @endcan

  <section id="column-search-datatable">
    @can('inventory_stock_access')
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div id="locations" class="d-none">
                <div class="btn-group">
                  <button
                    type="button"
                    class="btn btn-outline-primary dropdown-toggle location-toggle"
                    data-toggle="dropdown"
                    aria-haspopup="true"
                    aria-expanded="false"
                   >
                   <i data-feather='map-pin'></i>
                   <span class="location-name">All Location</span>
                  </button>
                  <div class="dropdown-menu keep-open p-1 location-list">
                      @foreach($addresses as $key => $location)
                        <div class="custom-control custom-radio m-25">
                          <input type="radio" id="customRadio_{{$key}}" name="status" class="custom-control-input location-radio" value="{{$location->id}}" data-value="{{$location->location_name}}" data-column="5" {{ $location->is_default == 1 ? "checked" : ''}}>
                          <label class="custom-control-label mt-0" for="customRadio_{{$key}}">{{$location->location_name}}</label>
                        </div>
                      @endforeach
                  </div>
                </div>
            </div>
            <div class="card-datatable">
              <table class="dt-column-search table">
                <thead>
                  <tr>
                    <th>Title</th>
                    <th>SKU</th>
                    <th>When Sold Out</th>
                    <th>Incoming</th>
                    <th>Available</th>
                    <th></th>
                    <th></th>
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
     let getListingUrl = "{{ route('admin.inventory-stocks.index') }}";
     let productUrl = "{{ route('admin.products.index') }}";
  </script>
  <script src="{{ asset(mix('adminassets/js/inventory/index.min.js')) }}"></script>
@endsection