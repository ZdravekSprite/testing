<div class="form-group row">
  <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>
  <div class="col-md-6">
    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" @if(in_array(Route::current()->getName(),['login','password.reset','password.request'])) autofocus @endif>
    @error('email')
    <span class="invalid-feedback" role="alert">
      <strong>{{ $message }}</strong>
    </span>
    @enderror
  </div>
</div>
