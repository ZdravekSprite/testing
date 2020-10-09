<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('_inc.head')
<body>
  <div id="app">
    @include('_inc.navbar')
    <main class="py-4 container">
      @yield('content')
    </main>
  </div>
  <!-- Scripts -->
  <script src="{{ asset('js/app.js') }}" defer></script>
</body>
</html>
