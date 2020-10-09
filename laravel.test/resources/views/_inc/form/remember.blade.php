<div class="form-group row">
  <div class="col-md-6 offset-md-4">
    <div class="form-check">
      <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
      <label class="form-check-label" for="remember">
        {{ __('Remember Me') }}
      </label>
    </div>
  </div>
</div>
