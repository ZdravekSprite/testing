@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
  <div class="col-md-8">
    <div class="card">
      <div class="card-header">{{ __('Register') }}</div>
      <div class="card-body">
        <form method="POST" action="{{ route('register') }}">
          @csrf
          @include('_inc.form.name')
          @include('_inc.form.email')
          @include('_inc.form.password')
          <div class="form-group row mb-0">
            <div class="col-md-6 offset-md-4">
              <button type="submit" class="btn btn-primary">
                {{ __('Register') }}
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
