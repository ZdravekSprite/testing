@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
  <div class="col-md-8">
    <div class="card">
      <div class="card-header">{{ __('Reset Password') }}</div>
      <div class="card-body">
        @include('_inc.alert')
        <form method="POST" action="{{ route('password.email') }}">
          @csrf
          @include('_inc.form.email')
          <div class="form-group row mb-0">
            <div class="col-md-6 offset-md-4">
              <button type="submit" class="btn btn-primary">
                {{ __('Send Password Reset Link') }}
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
