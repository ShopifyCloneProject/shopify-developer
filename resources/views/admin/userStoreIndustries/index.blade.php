@extends('layouts/contentLayoutMaster')
@section('title','User Store Industry')
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

@can('user_store_industry_create')
<input type="hidden" id="user_store_industry_create" value="1">
@endcan
@can('user_store_industry_edit')
<input type="hidden" id="user_store_industry_edit" value="1">
@endcan
@can('user_store_industry_show')
<input type="hidden" id="user_store_industry_show" value="1">
@endcan
@can('user_store_industry_delete')
<input type="hidden" id="user_store_industry_delete" value="1">
@endcan
@can('user_store_industry_export')
<input type="hidden" id="user_store_industry_export" value="1">
@endcan

<section id="column-search-datatable">
  @can('user_store_industry_access')
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
              <h4 class="card-title"> {{ trans('cruds.userStoreIndustry.title') }}</h4>
            </div>
            <div class="card-datatable">
              <table class="dt-column-search table table-responsive">
                <thead>
                  <tr>
                      <th>
                    </th>
                    <th width="10%">
                        {{ trans('cruds.userStoreIndustry.fields.id') }}
                    </th>
                    <th>
                        {{ trans('cruds.userStoreIndustry.fields.title') }}
                    </th>
                    <th>
                        {{ trans('cruds.userStoreIndustry.fields.status') }}
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
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        <select class="search" strict="true">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach(App\Models\UserStoreIndustry::STATUS_RADIO as $key => $item)
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
     let getListingUrl = "{{ route('admin.user-store-industries.index') }}";
     let createUrl = "{{ route('admin.user-store-industries.create') }}";
     let deleteUrl = "{{ url('api/admin/user-store-industries/delete') }}"
     let destroyUrl = "{{ url('api/admin/user-store-industries/massdestroy') }}"
     let displayUrl = "{{ url('admin/user-store-industries') }}";
    </script>

 <script src="{{ asset(mix('adminassets/js/userstoreindustries/userstoreindustries.min.js')) }}"></script>

@endsection