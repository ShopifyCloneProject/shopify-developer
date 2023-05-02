@extends('layouts/contentLayoutMaster')
@section('title','Gift Card Tags')
@section('vendor-style')
   @include('admin/partials/datatableCss')
@endsection
@section('page-style')
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


@can('gift_card_tag_create')
<input type="hidden" id="gift_card_tag_create" value="1">
@endcan
@can('gift_card_tag_edit')
<input type="hidden" id="gift_card_tag_edit" value="1">
@endcan
@can('gift_card_tag_show')
<input type="hidden" id="gift_card_tag_show" value="1">
@endcan
@can('gift_card_tag_delete')
<input type="hidden" id="gift_card_tag_delete" value="1">
@endcan
@can('gift_card_tag_export')
<input type="hidden" id="gift_card_tag_export" value="1">
@endcan

<section id="column-search-datatable">
  @can('variant_medium_access')
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
              <h4 class="card-title"> {{ trans('cruds.giftCardTag.title') }}</h4>
            </div>
            <div class="card-datatable">
              <table class="dt-column-search table table-responsive">
                <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th>
                        {{ trans('cruds.giftCardTag.fields.id') }}
                    </th>
                    <th>
                        {{ trans('cruds.giftCardTag.fields.gift_card') }}
                    </th>
                    <th>
                        {{ trans('cruds.giftCardTag.fields.tag') }}
                    </th>
                    <th>
                         
                    </th>
                </tr>
                <tr class="bg-gradient-secondary">
                    <td>
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
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
                            @foreach($tags as $key => $item)
                                <option value="{{ $item->title }}">{{ $item->title }}</option>
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
     let getListingUrl = "{{ route('admin.gift-card-tags.index') }}";
     let createUrl = "{{ route('admin.gift-card-tags.create') }}";
     let deleteUrl = "{{ url('api/admin/gift-card-tags/delete') }}"
     let destroyUrl = "{{ url('api/admin/gift-card-tags/massdestroy') }}"
     let displayUrl = "{{ url('admin/gift-card-tags') }}";
    </script>

 <script src="{{ asset(mix('adminassets/js/giftcardtags/giftcardtags.min.js')) }}"></script>

@endsection