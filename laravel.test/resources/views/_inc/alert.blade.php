@if (session('status'))
<div class="alert alert-success" role="alert">
  {{ session('status') }}
</div>
@endif
@if (session('resent'))
<div class="alert alert-success" role="alert">
  {{ __('A fresh verification link has been sent to your email address.') }}
</div>
@endif
