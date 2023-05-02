@extends('layouts/contentLayoutMaster')

@section('title', 'domain Settings')

@section('page-style')
<style>
    .alert{
      padding: 20px !important;
    }
    
  </style>
@endsection
@section('content')
@can('domain_access_create')

<section id="domain-url-settings">
  <div class="row">
   
    <!-- left content section -->
    <div class="col-md-12">
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
      <div class="card">
        <div class="card-body">
          <form method="post" action="{{ route('admin.settings.domainurl.addEdit')}}" enctype="multipart/form-data">
            @csrf
            <div class="row">
              <div class="col-4">
                  <div class="form-group">
                       <label for="app_name">{{ trans('cruds.domain.fields.appname') }}</label>
                      <input
                        type="text"
                        class="form-control"
                        id="app_name"
                        name="appname"
                        value="{{config('app.name')}}"
                      />
                    </div>
              </div>
              <div class="col-4">
                  <div class="form-group">
                      <label for="app_url">{{ trans('cruds.domain.fields.appurl') }}</label>
                      <input
                        type="text"
                        class="form-control"
                        id="app_url"
                        name="appurl"
                        value="{{config('app.url')}}"
                      />
                    </div>
              </div>
              <div class="col-4">
                  <div class="form-group">
                      <label for="domainTextarea">{{ trans('cruds.domain.fields.authurl') }}</label>
                      <input
                        type="text"
                        class="form-control"
                        id="domainTextarea"
                        name="authurl"
                        value="{{implode(',',config('sanctum.stateful'))}}"
                      />
                    </div>
              </div>
              <div class="col-4">
                  <div class="form-group">
                       <label for="callAppUrl">{{ trans('cruds.domain.fields.call_app_url') }}</label>
                      <input
                        type="text"
                        class="form-control"
                        id="callAppUrl"
                        name="call_app_url"
                        value="{{config('CALL_APP_URL')}}"
                      />
                    </div>
              </div>
              <div class="col-4">
                  <div class="form-group">
                       <label for="FrontAppUrl">{{ trans('cruds.domain.fields.front_app_url') }}</label>
                      <input
                        type="text"
                        class="form-control"
                        id="FrontAppUrl"
                        name="front_app_url"
                        value="{{config('CALL_FRONT_APP_URL')}}"
                      />
                    </div>
              </div>
              <div class="col-4">
                  <div class="form-group">
                       <label for="dbConnection">{{ trans('cruds.domain.fields.db_connection') }}</label>
                      <input
                        type="text"
                        class="form-control"
                        id="dbConnection"
                        name="db_connection"
                        value="{{config('database.default')}}"
                      />
                    </div>
              </div>
              <div class="col-4">
                  <div class="form-group">
                       <label for="dbHost">{{ trans('cruds.domain.fields.db_host') }}</label>
                      <input
                        type="text"
                        class="form-control"
                        id="dbHost"
                        name="db_host"
                        value="{{config('database.connections.mysql.host')}}"
                      />
                    </div>
              </div>
              <div class="col-4">
                  <div class="form-group">
                       <label for="dbPort">{{ trans('cruds.domain.fields.db_port') }}</label>
                      <input
                        type="text"
                        class="form-control"
                        id="dbPort"
                        name="db_port"
                        value="{{config('database.connections.mysql.port')}}"
                      />
                    </div>
              </div>
              <div class="col-4">
                  <div class="form_group">
                       <label for="dbDatabase">{{ trans('cruds.domain.fields.db_database') }}</label>
                      <input
                        type="text"
                        class="form-control"
                        id="dbDatabase"
                        name="db_database"
                        value="{{config('database.connections.mysql.database')}}"
                      />
                    </div>
              </div>
              <div class="col-4">
                  <div class="form-group">
                       <label for="dbUsername">{{ trans('cruds.domain.fields.db_username') }}</label>
                      <input
                        type="text"
                        class="form-control"
                        id="dbUsername"
                        name="db_username"
                        value="{{config('database.connections.mysql.username')}}"
                      />
                    </div>
              </div>
              <div class="col-4">
                  <div class="form-group">
                       <label for="dbPassword">{{ trans('cruds.domain.fields.db_password') }}</label>
                      <input
                        type="text"
                        class="form-control"
                        id="dbPassword"
                        name="db_password"
                        value="{{config('database.connections.mysql.password')}}"
                      />
                    </div>
              </div>
              <div class="col-4">
                  <div class="form-group">
                       <label for="mailMailer">{{ trans('cruds.domain.fields.mail_mailer') }}</label>
                      <input
                        type="text"
                        class="form-control"
                        id="mailMailer"
                        name="mail_mailer"
                        value="{{config('mail.default')}}"
                      />
                    </div>
              </div>
              <div class="col-4">
                  <div class="form-group">
                       <label for="mailHost">{{ trans('cruds.domain.fields.mail_host') }}</label>
                      <input
                        type="text"
                        class="form-control"
                        id="mailHost"
                        name="mail_host"
                        value="{{config('mail.mailers.smtp.host')}}"
                      />
                    </div>
              </div>
              <div class="col-4">
                  <div class="form-group">
                       <label for="mailPort">{{ trans('cruds.domain.fields.mail_port') }}</label>
                      <input
                        type="text"
                        class="form-control"
                        id="mailPort"
                        name="mail_port"
                        value="{{config('mail.mailers.smtp.port')}}"
                      />
                    </div>
              </div>
              <div class="col-4">
                  <div class="form-group">
                    <label for="mailUsername">{{ trans('cruds.domain.fields.mail_username') }}</label>
                    <input
                        type="text"
                        class="form-control"
                        id="mailUsername"
                        name="mail_username"
                        value="{{config('mail.mailers.smtp.username')}}"
                    />
                  </div>
              </div>
              <div class="col-4">
                  <div class="form-group">
                    <label for="mailPassword">{{ trans('cruds.domain.fields.mail_password') }}</label>
                    <input
                        type="text"
                        class="form-control"
                        id="mailPassword"
                        name="mail_password"
                        value="{{config('mail.mailers.smtp.password')}}"
                    />
                  </div>
              </div>
              <div class="col-4">
                  <div class="form-group">
                    <label for="mailEncryption">{{ trans('cruds.domain.fields.mail_encryption') }}</label>
                    <input
                        type="text"
                        class="form-control"
                        id="mailEncryption"
                        name="mail_encryption"
                        value="{{config('mail.mailers.smtp.encryption')}}"
                    />
                  </div>
              </div>
              <div class="col-4">
                  <div class="form-group">
                    <label for="mailFromAddress">{{ trans('cruds.domain.fields.mail_from_address') }}</label>
                    <input
                        type="text"
                        class="form-control"
                        id="mailFromAddress"
                        name="mail_from_address"
                        value="{{config('mail.from.address')}}"
                    />
                  </div>
              </div>
              <div class="col-4">
                  <div class="form-group">
                    <label for="mailFromName">{{ trans('cruds.domain.fields.mail_from_name') }}</label>
                    <input
                        type="text"
                        class="form-control"
                        id="mailFromName"
                        name="mail_from_name"
                        value="{{config('mail.from.name')}}"
                    />
                  </div>
              </div>
              <div class="col-4">
                  <div class="form-group">
                       <label for="cashfreeReturnUrl">{{ trans('cruds.domain.fields.cashfree_return_url') }}</label>
                      <input
                        type="text"
                        class="form-control"
                        id="cashfreeReturnUrl"
                        name="cashfree_return_url"
                        value="{{config('CASHFREE_RETURN_URL')}}"
                      />
                    </div>
              </div>
              <div class="col-4">
                  <div class="form-group">
                       <label for="cashfreeNotifyUrl">{{ trans('cruds.domain.fields.cashfree_notify_url') }}</label>
                      <input
                        type="text"
                        class="form-control"
                        id="cashfreeNotifyUrl"
                        name="cashfree_notify_url"
                        value="{{config('CASHFREE_NOTIFY_URL')}}"
                      />
                    </div>
              </div>
              <div class="col-4">
                  <div class="form-group">
                       <label for="razorpayCallbackUrl">{{ trans('cruds.domain.fields.razorpay_callback_url') }}</label>
                      <input
                        type="text"
                        class="form-control"
                        id="razorpayCallbackUrl"
                        name="razorpay_callback_url"
                        value="{{config('RAZORPAY_CALLBACK_URL')}}"
                      />
                    </div>
              </div>
              <div class="col-4">
                  <div class="form-group">
                       <label for="instamojoCallbackUrl">{{ trans('cruds.domain.fields.instamojo_callback_url') }}</label>
                      <input
                        type="text"
                        class="form-control"
                        id="instamojoCallbackUrl"
                        name="instamojo_callback_url"
                        value="{{config('INSTAMOJO_CALLBACK_URL')}}"
                      />
                    </div>
              </div>
              <div class="col-4">
                  <div class="form-group">
                       <label for="paytmCallbackUrl">{{ trans('cruds.domain.fields.paytm_callback_url') }}</label>
                      <input
                        type="text"
                        class="form-control"
                        id="paytmCallbackUrl"
                        name="paytm_callback_url"
                        value="{{config('PAYTM_CALLBACK_URL')}}"
                      />
                    </div>
              </div>
              <div class="col-4">
                  <div class="form-group">
                       <label for="defaultCountry">{{ trans('cruds.domain.fields.default_country') }}</label>
                      <input
                        type="text"
                        class="form-control"
                        id="defaultCountry"
                        name="default_country"
                        value="{{config('DEFAULT_COUNTRY')}}"
                      />
                    </div>
              </div>
              <div class="col-4">
                  <div class="form-group">
                       <label for="defaultCurrency">{{ trans('cruds.domain.fields.default_currency') }}</label>
                      <input
                        type="text"
                        class="form-control"
                        id="defaultCurrency"
                        name="default_currency"
                        value="{{config('DEFAULT_CURRECNY')}}"
                      />
                    </div>
              </div>
              <div class="col-4">
                  <div class="form-group">
                       <label for="ImageSize">{{ trans('cruds.domain.fields.image_size') }}</label>
                      <input
                        type="text"
                        class="form-control"
                        id="ImageSize"
                        name="image_size"
                        value="{{config('ORDER_START_NUMBER')}}"
                      />
                    </div>
              </div>
              <div class="col-4">
                  <div class="form-group">
                       <label for="orderStartNumber">{{ trans('cruds.domain.fields.order_start_number') }}</label>
                      <input
                        type="text"
                        class="form-control"
                        id="orderStartNumber"
                        name="order_start_number"
                        value="{{config('ORDER_START_NUMBER')}}"
                      />
                    </div>
              </div>
              <div class="col-4">
                  <div class="form-group">
                       <label for="displayOrderLimit">{{ trans('cruds.domain.fields.display_order_limit') }}</label>
                      <input
                        type="text"
                        class="form-control"
                        id="displayOrderLimit"
                        name="display_order_limit"
                        value="{{config('DISPLAY_ORDER_LIMIT')}}"
                      />
                    </div>
              </div>
              <div class="col-4">
                  <div class="form-group">
                       <label for="perPage">{{ trans('cruds.domain.fields.per_page') }}</label>
                      <input
                        type="text"
                        class="form-control"
                        id="perPage"
                        name="per_page"
                        value="{{config('PER_PAGE')}}"
                      />
                    </div>
              </div>
              <div class="col-4">
                  <div class="form-group">
                       <label for="searchUserLimit">{{ trans('cruds.domain.fields.search_user_limit') }}</label>
                      <input
                        type="text"
                        class="form-control"
                        id="searchUserLimit"
                        name="search_user_limit"
                        value="{{config('SEARCH_USER_LIMIT')}}"
                      />
                    </div>
              </div>
              <div class="col-4">
                  <div class="form-group">
                       <label for="searchProductLimit">{{ trans('cruds.domain.fields.search_product_limit') }}</label>
                      <input
                        type="text"
                        class="form-control"
                        id="searchProductLimit"
                        name="search_product_limit"
                        value="{{config('SEARCH_PRODUCT_LIMIT')}}"
                      />
                    </div>
              </div>
               <div class="col-4">
                  <div class="form-group">
                       <label for="shipmentOrderNumber">{{ trans('cruds.domain.fields.shipment_order_number') }}</label>
                      <input
                        type="text"
                        class="form-control"
                        id="shipmentOrderNumber"
                        name="shipment_order_number"
                        value="{{config('shipment_order_number')}}"
                      />
                    </div>
              </div>
              <div class="col-4">
                  <div class="form-group">
                       <label for="returnShipmentOrderNumber">{{ trans('cruds.domain.fields.return_shipment_order_number') }}</label>
                      <input
                        type="text"
                        class="form-control"
                        id="returnShipmentOrderNumber"
                        name="shipment_order_number"
                        value="{{config('return_shipment_order_number')}}"
                      />
                    </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                  <button type="submit" class="btn btn-primary mr-1 mt-1">{{ trans('global.submit') }}</button>
                  <button type="reset" class="btn btn-outline-secondary mt-1">{{ trans('global.cancel') }}</button>
              </div>
            </div>
            
          </form>
        </div>
      </div>
    </div>
    <!--/ right content section -->
  </div>
</section>

@endcan
@endsection

@section('page-script')
   
@endsection