@extends('layouts/contentLayoutMaster')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.giftCardCollection.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.gift-card-collections.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.giftCardCollection.fields.id') }}
                        </th>
                        <td>
                            {{ $giftCardCollection->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.giftCardCollection.fields.gift_card') }}
                        </th>
                        <td>
                            {{ $giftCardCollection->gift_card->title ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.giftCardCollection.fields.collection') }}
                        </th>
                        <td>
                            {{ $giftCardCollection->collection->title ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-primary mt-2" href="{{ route('admin.gift-card-collections.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection