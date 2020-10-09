@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
  <div class="col-md-8">
    <div class="card">
      <div class="card-header">{{ __('Login') }}</div>
      <div class="card-body">
        <form method="POST" action="{{ route('login') }}">
          @csrf
          @include('_inc.form.email')
          @include('_inc.form.password')
          @include('_inc.form.remember')
          <div class="form-group row mb-0">
            <div class="col-md-8 offset-md-4">
              <button type="submit" class="btn btn-primary">
                {{ __('Login') }}
              </button>
              @if (Route::has('password.request'))
              <a class="btn btn-link" href="{{ route('password.request') }}">
                {{ __('Forgot Your Password?') }}
              </a>
              @endif
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
