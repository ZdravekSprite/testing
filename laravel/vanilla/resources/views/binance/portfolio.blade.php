<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Portfolio') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-gray-100 border-b border-gray-200">
          <div id="app">
            @if(count($balance) > 0)
            <form method="POST" action="{{ route('dustTransfer') }}">
              @csrf

              @foreach($balance as $coin => $asset)
              <div title="{{$asset->name}} {{round($asset->price,2)}}">
                <x-input class="float-left mr-1" :width="'4'" id="{{ $coin }}" type="checkbox" name="assets[]" value="{{ $coin }}" :checked="old('$coin') ? 'checked' : null" />
                <span class="block font-medium text-gray-700">
                {{ $asset->name }}: {{$asset->total}} {{$coin}} (<span id="price{{$coin}}">{{round($asset->price,2)}}</span> kn) [Free: {{$asset->free}}(<span id="{{$coin}}free"></span> kn)]
                </span>
              </div>
              @endforeach
              <hr>
              <p> Total: <span id="total">{{round($total,2)}}</span> kn</p>
              <div class="flex items-center justify-end mt-4">
                <x-button class="ml-4">
                  {{ __('Dust') }}
                </x-button>
              </div>
            </form>
            @else
            <p> No assets found</p>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
    <script>
    var binanceSocket = new WebSocket("{{$binanceSocket}}");
    @foreach($balance as $coin => $asset)
    var {{strtolower($coin)}} = {{$balance[$coin]->price}};
    @endforeach
    // Create our number formatter.
    var formatter = new Intl.NumberFormat('hr-HR', {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2,
    });
    var busd_kn = {{$busd_kn}};
    binanceSocket.onmessage = function(event) {
      var message = JSON.parse(event.data);
      @foreach($balance as $coin => $asset)
        @switch($coin)
          @case('EUR')
              eur = {{$asset->total}} * {{$eur_kn}};
              document.getElementById('priceEUR').innerHTML = formatter.format(eur,2);
              eurfree = {{$asset->free}} * {{$eur_kn}};
              document.getElementById('EURfree').innerHTML = formatter.format(eurfree,2);
            @break

          @case('BUSD')
            if (message.stream == 'eurbusd@kline_1m') {
              busd_kn = {{$eur_kn}} / message.data.k.c;
              busd = {{$asset->total}} * busd_kn;
              document.getElementById('priceBUSD').innerHTML = formatter.format(busd,2);
              busdfree = {{$asset->free}} * busd_kn;
              document.getElementById('BUSDfree').innerHTML = formatter.format(busdfree,2);
            }
            @break

          @case('DAI')
            if (message.stream == 'busddai@kline_1m') {
              dai = {{$asset->total}} * busd_kn / message.data.k.c;
              document.getElementById('priceDAI').innerHTML = formatter.format(dai,2);
              daifree = {{$asset->free}} * busd_kn / message.data.k.c;
              document.getElementById('DAIfree').innerHTML = formatter.format(daifree,2);
            }
            @break

          @default
            if (message.stream == '{{strtolower($coin)}}busd@kline_1m') {
              {{strtolower($coin)}} = message.data.k.c * {{$asset->total}} * busd_kn;
              document.getElementById('price{{$coin}}').innerHTML = formatter.format({{strtolower($coin)}},2);
              {{strtolower($coin)}}free = message.data.k.c * {{$asset->free}} * busd_kn;
              document.getElementById('{{$coin}}free').innerHTML = formatter.format({{strtolower($coin)}}free,2);
            }
        @endswitch
      @endforeach
      var total = 0;
      @foreach($balance as $coin => $asset)
      total = total + {{strtolower($coin)}};
      @endforeach
      document.getElementById('total').innerHTML = formatter.format(total,2);
      document.title = formatter.format(total,2);
    }

  </script>
</x-app-layout>
