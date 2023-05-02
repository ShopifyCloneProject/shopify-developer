@extends('layouts/contentLayoutMaster')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.user.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.users.update", [$user->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.user.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required>
                @if($errors->has('name'))
                    <span class="text-danger">{{ $errors->first('name') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="last_name">{{ trans('cruds.user.fields.last_name') }}</label>
                <input class="form-control {{ $errors->has('last_name') ? 'is-invalid' : '' }}" type="text" name="last_name" id="last_name" value="{{ old('last_name', $user->last_name) }}">
                @if($errors->has('last_name'))
                    <span class="text-danger">{{ $errors->first('last_name') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.last_name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="mobile">{{ trans('cruds.user.fields.mobile') }}</label>
                <input class="form-control {{ $errors->has('mobile') ? 'is-invalid' : '' }}" type="text" name="mobile" id="mobile" value="{{ old('mobile', $user->mobile) }}">
                @if($errors->has('mobile'))
                    <span class="text-danger">{{ $errors->first('mobile') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.mobile_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="email">{{ trans('cruds.user.fields.email') }}</label>
                <input class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required>
                @if($errors->has('email'))
                    <span class="text-danger">{{ $errors->first('email') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.email_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="password">{{ trans('cruds.user.fields.password') }}</label>
                <input class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" type="password" name="password" id="password">
                @if($errors->has('password'))
                    <span class="text-danger">{{ $errors->first('password') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.password_helper') }}</span>
            </div>
            <div class="form-group">
                <label>{{ trans('cruds.user.fields.gender') }}</label>
                @foreach(App\Models\User::GENDER_RADIO as $key => $label)
                    <div class="form-check {{ $errors->has('gender') ? 'is-invalid' : '' }}">
                        <input class="form-check-input" type="radio" id="gender_{{ $key }}" name="gender" value="{{ $key }}" {{ old('gender', $user->gender) === (string) $key ? 'checked' : '' }}>
                        <label class="form-check-label" for="gender_{{ $key }}">{{ $label }}</label>
                    </div>
                @endforeach
                @if($errors->has('gender'))
                    <span class="text-danger">{{ $errors->first('gender') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.gender_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="google">{{ trans('cruds.user.fields.google') }}</label>
                <input class="form-control {{ $errors->has('google') ? 'is-invalid' : '' }}" type="text" name="google" id="google" value="{{ old('google', $user->google) }}">
                @if($errors->has('google'))
                    <span class="text-danger">{{ $errors->first('google') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.google_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="facebook">{{ trans('cruds.user.fields.facebook') }}</label>
                <input class="form-control {{ $errors->has('facebook') ? 'is-invalid' : '' }}" type="text" name="facebook" id="facebook" value="{{ old('facebook', $user->facebook) }}">
                @if($errors->has('facebook'))
                    <span class="text-danger">{{ $errors->first('facebook') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.facebook_helper') }}</span>
            </div>
            <div class="form-group">
                <label>{{ trans('cruds.user.fields.is_verified') }}</label>
                @foreach(App\Models\User::IS_VERIFIED_RADIO as $key => $label)
                    <div class="form-check {{ $errors->has('is_verified') ? 'is-invalid' : '' }}">
                        <input class="form-check-input" type="radio" id="is_verified_{{ $key }}" name="is_verified" value="{{ $key }}" {{ old('is_verified', $user->is_verified) === (string) $key ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_verified_{{ $key }}">{{ $label }}</label>
                    </div>
                @endforeach
                @if($errors->has('is_verified'))
                    <span class="text-danger">{{ $errors->first('is_verified') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.is_verified_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="company">{{ trans('cruds.user.fields.company') }}</label>
                <input class="form-control {{ $errors->has('company') ? 'is-invalid' : '' }}" type="text" name="company" id="company" value="{{ old('company', $user->company) }}">
                @if($errors->has('company'))
                    <span class="text-danger">{{ $errors->first('company') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.company_helper') }}</span>
            </div>
            <div class="form-group">
                <label>{{ trans('cruds.user.fields.email_notification_status') }}</label>
                @foreach(App\Models\User::EMAIL_NOTIFICATION_STATUS_RADIO as $key => $label)
                    <div class="form-check {{ $errors->has('email_notification_status') ? 'is-invalid' : '' }}">
                        <input class="form-check-input" type="radio" id="email_notification_status_{{ $key }}" name="email_notification_status" value="{{ $key }}" {{ old('email_notification_status', $user->email_notification_status) === (string) $key ? 'checked' : '' }}>
                        <label class="form-check-label" for="email_notification_status_{{ $key }}">{{ $label }}</label>
                    </div>
                @endforeach
                @if($errors->has('email_notification_status'))
                    <span class="text-danger">{{ $errors->first('email_notification_status') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.email_notification_status_helper') }}</span>
            </div>
            <div class="form-group">
                <label>{{ trans('cruds.user.fields.sms_notification_status') }}</label>
                @foreach(App\Models\User::SMS_NOTIFICATION_STATUS_RADIO as $key => $label)
                    <div class="form-check {{ $errors->has('sms_notification_status') ? 'is-invalid' : '' }}">
                        <input class="form-check-input" type="radio" id="sms_notification_status_{{ $key }}" name="sms_notification_status" value="{{ $key }}" {{ old('sms_notification_status', $user->sms_notification_status) === (string) $key ? 'checked' : '' }}>
                        <label class="form-check-label" for="sms_notification_status_{{ $key }}">{{ $label }}</label>
                    </div>
                @endforeach
                @if($errors->has('sms_notification_status'))
                    <span class="text-danger">{{ $errors->first('sms_notification_status') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.sms_notification_status_helper') }}</span>
            </div>
            <div class="form-group">
                <label>{{ trans('cruds.user.fields.blocked') }}</label>
                @foreach(App\Models\User::BLOCKED_RADIO as $key => $label)
                    <div class="form-check {{ $errors->has('blocked') ? 'is-invalid' : '' }}">
                        <input class="form-check-input" type="radio" id="blocked_{{ $key }}" name="blocked" value="{{ $key }}" {{ old('blocked', $user->blocked) === (string) $key ? 'checked' : '' }}>
                        <label class="form-check-label" for="blocked_{{ $key }}">{{ $label }}</label>
                    </div>
                @endforeach
                @if($errors->has('blocked'))
                    <span class="text-danger">{{ $errors->first('blocked') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.blocked_helper') }}</span>
            </div>
            <div class="form-group">
                <label>{{ trans('cruds.user.fields.accept_marketing') }}</label>
                @foreach(App\Models\User::ACCEPT_MARKETING_RADIO as $key => $label)
                    <div class="form-check {{ $errors->has('accept_marketing') ? 'is-invalid' : '' }}">
                        <input class="form-check-input" type="radio" id="accept_marketing_{{ $key }}" name="accept_marketing" value="{{ $key }}" {{ old('accept_marketing', $user->accept_marketing) === (string) $key ? 'checked' : '' }}>
                        <label class="form-check-label" for="accept_marketing_{{ $key }}">{{ $label }}</label>
                    </div>
                @endforeach
                @if($errors->has('accept_marketing'))
                    <span class="text-danger">{{ $errors->first('accept_marketing') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.accept_marketing_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="total_spent">{{ trans('cruds.user.fields.total_spent') }}</label>
                <input class="form-control {{ $errors->has('total_spent') ? 'is-invalid' : '' }}" type="number" name="total_spent" id="total_spent" value="{{ old('total_spent', $user->total_spent) }}" step="0.01">
                @if($errors->has('total_spent'))
                    <span class="text-danger">{{ $errors->first('total_spent') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.total_spent_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="total_orders">{{ trans('cruds.user.fields.total_orders') }}</label>
                <input class="form-control {{ $errors->has('total_orders') ? 'is-invalid' : '' }}" type="number" name="total_orders" id="total_orders" value="{{ old('total_orders', $user->total_orders) }}" step="1">
                @if($errors->has('total_orders'))
                    <span class="text-danger">{{ $errors->first('total_orders') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.total_orders_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="tags">{{ trans('cruds.user.fields.tags') }}</label>
                <input class="form-control {{ $errors->has('tags') ? 'is-invalid' : '' }}" type="text" name="tags" id="tags" value="{{ old('tags', $user->tags) }}">
                @if($errors->has('tags'))
                    <span class="text-danger">{{ $errors->first('tags') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.tags_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="note">{{ trans('cruds.user.fields.note') }}</label>
                <textarea class="form-control {{ $errors->has('note') ? 'is-invalid' : '' }}" name="note" id="note">{{ old('note', $user->note) }}</textarea>
                @if($errors->has('note'))
                    <span class="text-danger">{{ $errors->first('note') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.note_helper') }}</span>
            </div>
            <div class="form-group">
                <label>{{ trans('cruds.user.fields.tax_exempt') }}</label>
                @foreach(App\Models\User::TAX_EXEMPT_RADIO as $key => $label)
                    <div class="form-check {{ $errors->has('tax_exempt') ? 'is-invalid' : '' }}">
                        <input class="form-check-input" type="radio" id="tax_exempt_{{ $key }}" name="tax_exempt" value="{{ $key }}" {{ old('tax_exempt', $user->tax_exempt) === (string) $key ? 'checked' : '' }}>
                        <label class="form-check-label" for="tax_exempt_{{ $key }}">{{ $label }}</label>
                    </div>
                @endforeach
                @if($errors->has('tax_exempt'))
                    <span class="text-danger">{{ $errors->first('tax_exempt') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.tax_exempt_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="pics">{{ trans('cruds.user.fields.pics') }}</label>
                <div class="needsclick dropzone {{ $errors->has('pics') ? 'is-invalid' : '' }}" id="pics-dropzone">
                </div>
                @if($errors->has('pics'))
                    <span class="text-danger">{{ $errors->first('pics') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.pics_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="roles">{{ trans('cruds.user.fields.roles') }}</label>
                <div style="padding-bottom: 4px">
                    <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                    <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                </div>
                <select class="form-control select2 {{ $errors->has('roles') ? 'is-invalid' : '' }}" name="roles[]" id="roles" multiple required>
                    @foreach($roles as $id => $roles)
                        <option value="{{ $id }}" {{ (in_array($id, old('roles', [])) || $user->roles->contains($id)) ? 'selected' : '' }}>{{ $roles }}</option>
                    @endforeach
                </select>
                @if($errors->has('roles'))
                    <span class="text-danger">{{ $errors->first('roles') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.roles_helper') }}</span>
            </div>
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection

@section('scripts')
<script>
    Dropzone.options.picsDropzone = {
    url: '{{ route('admin.users.storeMedia') }}',
    maxFilesize: 2, // MB
    acceptedFiles: '.jpeg,.jpg,.png,.gif',
    maxFiles: 1,
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 2,
      width: 4096,
      height: 4096
    },
    success: function (file, response) {
      $('form').find('input[name="pics"]').remove()
      $('form').append('<input type="hidden" name="pics" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="pics"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($user) && $user->pics)
      var file = {!! json_encode($user->pics) !!}
          this.options.addedfile.call(this, file)
      this.options.thumbnail.call(this, file, file.preview)
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="pics" value="' + file.file_name + '">')
      this.options.maxFiles = this.options.maxFiles - 1
@endif
    },
    error: function (file, response) {
        if ($.type(response) === 'string') {
            var message = response //dropzone sends it's own error messages in string
        } else {
            var message = response.errors.file
        }
        file.previewElement.classList.add('dz-error')
        _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
        _results = []
        for (_i = 0, _len = _ref.length; _i < _len; _i++) {
            node = _ref[_i]
            _results.push(node.textContent = message)
        }

        return _results
    }
}
</script>
@endsection