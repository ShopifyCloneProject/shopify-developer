@extends('layouts/contentLayoutMaster')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.giftCardDenomination.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.gift-card-denominations.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.giftCardDenomination.fields.id') }}
                        </th>
                        <td>
                            {{ $giftCardDenomination->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.giftCardDenomination.fields.product') }}
                        </th>
                        <td>
                            {{ $giftCardDenomination->product->title ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.giftCardDenomination.fields.value') }}
                        </th>
                        <td>
                            {{ $giftCardDenomination->value }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-primary mt-2" href="{{ route('admin.gift-card-denominations.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection