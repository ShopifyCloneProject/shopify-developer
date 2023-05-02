@extends('layouts.contentLayoutMaster')
{{-- page title --}}
@section('title','Settings')
@section('vendor-style')
  <link rel="stylesheet" type="text/css" href="{{asset('/fonts/font-awesome/css/font-awesome.min.css')}}">
@endsection
{{-- page styles --}}
@section('page-style')
  <link rel="stylesheet" type="text/css" href="{{ asset(mix('css/base/pages/app-file-manager.css')) }}">
  <style>
  	.content-right{
  		width:100% !important;
  	}
  	.file-manager-content-body.ps{
  		height: 100% !important;
  	}
  	.fa
  	{
	    font-size: 20px;
	    color: #7367f0;
  	}

    .settings-card{
      height: 123px;
      overflow-y: auto;
      scrollbar-color: #6969dd #e0e0e0;
      scrollbar-width: thin;
    }
    .settings-card:hover{
      cursor: pointer;
      color: inherit;
      background: #ddd;
    }
    .settings-card::-webkit-scrollbar {
      width: 8px;
     
    }
    .settings-card::-webkit-scrollbar-track {
      background: #f1f1f1;
    }
    .settings-card::-webkit-scrollbar-thumb {
      background: #aaa;
      border-radius: 10px;

    }
    .settings-card a{
      padding: 1.5rem;
      color: inherit;
      display: block;
    }
    
  </style>
@endsection
@section('content')

@can('settings_access')
<!-- overlay container -->
<div class="body-content-overlay"></div>

<!-- file manager app content starts -->
<div class="file-manager-main-content">
  <div class="file-manager-content-body">
    <!-- drives area starts-->
    <div class="settings">
      <div class="row">
        <div class="col-12 mb-2">
          <h4 class="files-section-title">{{ trans('cruds.settings.title') }}</h4>
        </div>
        @can('general_access')
        <div class="col-lg-4 col-md-6 col-12">
          <div class="card shadow-none border cursor-pointer">
            <div class="settings-card">
        	  <a href="{{ url('admin/settings/general') }}">
                <div class="d-flex">
    	              <div class="d-flex justify-content-between">
    	                <i class="fa fa-cog" aria-hidden="true"></i>
    	              </div>
    	              <div class="ml-1">
    	                <h5>{{ trans('cruds.settings.general') }}</h5>
                      <p><small>{{ trans('cruds.settings.general_helper') }}</small></p>
    	              </div>
                </div>
               </a>
            </div>
          </div>
        </div>
        @endcan

        @can('location_access')
        <div class="col-lg-4 col-md-6 col-12">
          <div class="card shadow-none border cursor-pointer">
            <div class="settings-card">
            <a href="{{ url('admin/settings/locations') }}">
                <div class="d-flex">
                    <div class="d-flex justify-content-between">
                      <i class="fa fa-map-marker" aria-hidden="true"></i>
                    </div>
                    <div class="ml-1">
                      <h5>{{ trans('cruds.settings.locations') }}</h5>
                      <p><small>{{ trans('cruds.settings.locations_helper') }}</small></p>
                    </div>
                </div>
               </a>
            </div>
          </div>
        </div>
        @endcan

        @can('plan_access')
        <div class="col-lg-4 col-md-6 col-12">
          <div class="card shadow-none border cursor-pointer">
            <div class="settings-card">
            <a href="{{ url('admin/settings/plan') }}">
                <div class="d-flex">
                    <div class="d-flex justify-content-between">
                      <i class="fa fa-lightbulb" aria-hidden="true"></i>
                    </div>
                    <div class="ml-1">
                      <h5>{{ trans('cruds.settings.plan') }}</h5>
                      <p><small>{{ trans('cruds.settings.plan_helper1') }} {{ trans('cruds.settings.plan_helper2') }}</small></p>
                    </div>
                </div>
               </a>
            </div>
          </div>
        </div>
        @endcan
        
        @can('payment_settings_access')
        <div class="col-lg-4 col-md-6 col-12">
          <div class="card shadow-none border cursor-pointer">
            <div class="settings-card">
            <a href="{{ url('admin/settings/payments') }}">
                <div class="d-flex">
                    <div class="d-flex justify-content-between">
                      <i class="fa fa-credit-card" aria-hidden="true"></i>
                    </div>
                    <div class="ml-1">
                      <h5>{{ trans('cruds.settings.payments') }}</h5>
                      <p><small>{{ trans('cruds.settings.payments_helper') }}</small></p>
                    </div>
                </div>
               </a>
            </div>
          </div>
        </div>
        @endcan

        @can('notification_settings_access')
        <div class="col-lg-4 col-md-6 col-12">
          <div class="card shadow-none border cursor-pointer">
            <div class="settings-card">
            <a href="{{ url('admin/settings/notificationdetails') }}">
                <div class="d-flex">
                    <div class="d-flex justify-content-between">
                      <i class="fa fa-bell" aria-hidden="true"></i>
                    </div>
                    <div class="ml-1">
                      <h5>{{ trans('cruds.settings.notifications') }}</h5>
                      <p><small>{{ trans('cruds.settings.notifications_helper') }}</small></p>
                    </div>
                </div>
               </a>
            </div>
          </div>
        </div>
        @endcan

        @can('gift_card_accesss')
        <div class="col-lg-4 col-md-6 col-12">
          <div class="card shadow-none border cursor-pointer">
            <div class="settings-card">
            <a href="{{ url('admin/settings/gift-cards') }}">
                <div class="d-flex">
                    <div class="d-flex justify-content-between">
                      <i class="fa fa-gift" aria-hidden="true"></i>
                    </div>
                    <div class="ml-1">
                      <h5>{{ trans('cruds.settings.gift_card') }}</h5>
                      <p><small>{{ trans('cruds.settings.gift_card_helper') }}</small></p>
                    </div>
                </div>
               </a>
            </div>
          </div>
        </div>
        @endcan

        @can('languages_selection_access')
        <div class="col-lg-4 col-md-6 col-12">
          <div class="card shadow-none border cursor-pointer">
            <div class="settings-card">
            <a href="{{ url('admin/settings/selectlanguage') }}">
                <div class="d-flex">
                    <div class="d-flex justify-content-between">
                      <i class="fa fa-language" aria-hidden="true"></i>
                    </div>
                    <div class="ml-1">
                      <h5>{{ trans('cruds.settings.store_language') }}</h5>
                      <p><small>{{ trans('cruds.settings.store_language_helper') }}</small></p>
                    </div>
                </div>
               </a>
            </div>
          </div>
        </div>
        @endcan

        @can('shipping_access')
        <div class="col-lg-4 col-md-6 col-12">
          <div class="card shadow-none border cursor-pointer">
            <div class="settings-card">
            <a href="{{ url('admin/settings/shipping') }}">
                <div class="d-flex">
                    <div class="d-flex justify-content-between">
                      <i class="fa fa-truck" aria-hidden="true"></i>
                    </div>
                    <div class="ml-1">
                      <h5>{{ trans('cruds.settings.shipping_and_delivery') }}</h5>
                      <p><small>{{ trans('cruds.settings.shipping_and_delivery_helper') }}</small></p>
                    </div>
                </div>
               </a>
            </div>
          </div>
        </div>
        @endcan

        @can('tax_access')
        <div class="col-lg-4 col-md-6 col-12">
          <div class="card shadow-none border cursor-pointer">
            <div class="settings-card">
            <a href="{{ url('admin/settings/taxes') }}">
                <div class="d-flex">
                    <div class="d-flex justify-content-between">
                      <i class="fa fa-certificate" aria-hidden="true"></i>
                    </div>
                    <div class="ml-1">
                      <h5>{{ trans('cruds.settings.taxes') }}</h5>
                      <p><small>{{ trans('cruds.settings.taxes_helper') }}</small></p>
                    </div>
                </div>
               </a>
            </div>
          </div>
        </div>
        @endcan

        @can('sales_channel_access')
        <div class="col-lg-4 col-md-6 col-12">
          <div class="card shadow-none border cursor-pointer">
            <div class="settings-card">
            <a href="{{ url('admin/settings/channel') }}">
                <div class="d-flex">
                    <div class="d-flex justify-content-between">
                      <i class="fa fa-link" aria-hidden="true"></i>
                    </div>
                    <div class="ml-1">
                      <h5>{{ trans('cruds.settings.sales_channel') }}</h5>
                      <p><small>{{ trans('cruds.settings.sales_channel_helper') }}</small></p>
                    </div>
                </div>
               </a>
            </div>
          </div>
        </div>
        @endcan

        @can('legal_policy_access')
        <div class="col-lg-4 col-md-6 col-12">
          <div class="card shadow-none border cursor-pointer">
            <div class="settings-card">
              <a href="{{ url('admin/settings/legal') }}">
                <div class="d-flex">
                    <div class="d-flex justify-content-between">
                      <i class="fa fa-link" aria-hidden="true"></i>
                    </div>
                    <div class="ml-1">
                      <h5>{{ trans('cruds.settings.legal') }}</h5>
                      <p><small>{{ trans('cruds.settings.legal_helper') }}</small></p>
                    </div>
                </div>
               </a>
            </div>
          </div>
        </div>
        @endcan

        @can('fbpixel_access')
        <div class="col-lg-4 col-md-6 col-12">
          <div class="card shadow-none border cursor-pointer">
            <div class="settings-card">
              <a href="{{ url('admin/settings/fbpixel') }}">
                <div class="d-flex">
                    <div class="d-flex justify-content-between">
                      <i class="fa fa-code" aria-hidden="true"></i>
                    </div>
                    <div class="ml-1">
                      <h5>{{ trans('cruds.settings.fbpixel') }}</h5>
                      <p><small>{{ trans('cruds.settings.fbpixel_helper') }}</small></p>
                    </div>
                </div>
               </a>
            </div>
          </div>
        </div>
        @endcan

        @can('default_xmlfeed_access')
        <div class="col-lg-4 col-md-6 col-12">
          <div class="card shadow-none border cursor-pointer">
            <div class="settings-card">
              <a href="{{ url('admin/settings/default-xml-section') }}">
                <div class="d-flex">
                    <div class="d-flex justify-content-between">
                      <i class="fa fa-rss-square" aria-hidden="true"></i>
                    </div>
                    <div class="ml-1">
                      <h5>Default XML Feed</h5>
                      <p><small>Manage default XML feed</small></p>
                    </div>
                </div>
               </a>
            </div>
          </div>
        </div>
        @endcan

        @can('xmlfeed_access')
        <div class="col-lg-4 col-md-6 col-12">
          <div class="card shadow-none border cursor-pointer">
            <div class="settings-card">
              <a href="{{ url('admin/settings/xmlfeed') }}">
                <div class="d-flex">
                    <div class="d-flex justify-content-between">
                      <i class="fa fa-link" aria-hidden="true"></i>
                    </div>
                    <div class="ml-1">
                      <h5>{{ trans('cruds.settings.xmlfeed') }}</h5>
                      <p><small>{{ trans('cruds.settings.xmlfeed_helper') }}</small></p>
                    </div>
                </div>
               </a>
            </div>
          </div>
        </div>
        @endcan

        @can('custom_settings_access')
        <div class="col-lg-4 col-md-6 col-12">
          <div class="card shadow-none border cursor-pointer">
            <div class="settings-card">
              <a href="{{ url('admin/settings/custom') }}">
                <div class="d-flex">
                    <div class="d-flex justify-content-between">
                      <i class="fa fa-cogs" aria-hidden="true"></i>
                    </div>
                    <div class="ml-1">
                      <h5>{{ trans('cruds.settings.custom_settings') }}</h5>
                      <p><small>{{ trans('cruds.settings.custom_settings_helper') }}</small></p>
                    </div>
                </div>
               </a>
            </div>
          </div>
        </div>
        @endcan

        @can('domain_access')
        <div class="col-lg-4 col-md-6 col-12">
          <div class="card shadow-none border cursor-pointer">
            <div class="settings-card">
              <a href="{{ url('admin/settings/domainurl') }}">
                <div class="d-flex">
                    <div class="d-flex justify-content-between">
                      <i class="fa fa-venus-double" aria-hidden="true"></i>
                    </div>
                    <div class="ml-1">
                      <h5>{{ trans('cruds.settings.domain_settings') }}</h5>
                      <p><small>{{ trans('cruds.settings.domain_settings_helper') }}</small></p>
                    </div>
                </div>
               </a>
            </div>
          </div>
        </div>
        @endcan

        @can('admin_settings_access')
        <div class="col-lg-4 col-md-6 col-12">
          <div class="card shadow-none border cursor-pointer">
            <div class="settings-card">
              <a href="{{ url('admin/settings/adminsettings') }}">
                <div class="d-flex">
                    <div class="d-flex justify-content-between">
                      <i class="fa fa-venus-double" aria-hidden="true"></i>
                    </div>
                    <div class="ml-1">
                      <h5>{{ trans('cruds.settings.admin_settings') }}</h5>
                      <p><small>{{ trans('cruds.settings.admin_settings_helper') }}</small></p>
                    </div>
                </div>
               </a>
            </div>
          </div>
        </div>
        @endcan

        {{-- @can('shipping_details_access')
            <div class="col-lg-4 col-md-6 col-12">
              <div class="card shadow-none border cursor-pointer">
                <div class="settings-card">
                  <a href="{{ url('admin/settings/shippingdetails') }}">
                    <div class="d-flex">
                        <div class="d-flex justify-content-between">
                          <i class="fa fa-venus-double" aria-hidden="true"></i>
                        </div>
                        <div class="ml-1">
                          <h5>{{ trans('cruds.settings.shipment_settings') }}</h5>
                          <p><small>{{ trans('cruds.settings.shipment_settings_helper') }}</small></p>
                        </div>
                    </div>
                   </a>
                </div>
              </div>
            </div>
        @endcan --}}

      </div>
    </div>
    <!-- drives area ends-->
  </div>
</div>
<!-- file manager app content ends -->
@endcan
@endsection
<!-- @section('vendor-script')
<script src="{{asset('vendors/js/extensions/jstree.min.js')}}"></script>
@endsection
{{-- page styles --}}
@section('page-script')
<script src="{{asset('js/scripts/pages/app-file-manager.js')}}"></script>
@endsection -->
