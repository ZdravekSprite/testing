@if (session('status'))
<div class="alert alert-success" role="alert">
    {{ session('status') }}
</div>
@endif

@if (session('success'))
<div class="alert alert-success" role="alert">
    {{ session('success') }}
</div>
@endif

@if (session('warning'))
<div class="alert alert-warninig" role="alert">
    {{ session('warning') }}
</div>
@endif
