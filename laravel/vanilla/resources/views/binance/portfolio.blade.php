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
          @if(count($balance) > 0)
            <form method="POST" action="{{ route('dustTransfer') }}" onsubmit="return confirm('Are you sure?')">
              @csrf
              <table class="table-auto w-full">
                <thead>
                  <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($balance as $coin => $asset)
                  <tr>
                    <td title="{{$asset->name}} {{round($asset->price,2)}}">
                    <x-input :width="'4'" id="{{ $coin }}" type="checkbox" name="assets[]" value="{{ $coin }}" :checked="old('$coin') ? 'checked' : null" />
                    </td>
                    <td class="block font-medium text-gray-700 mt-1">
                    {{ $asset->name }}: {{$asset->total}} {{$coin}} (<span id="price{{$coin}}">{{round($asset->price,2)}}</span> kn)
                    </td>
                    <td>
                    @if($asset->free)
                    [Free: {{$asset->free}}(<span id="{{$coin}}free"></span> kn)]
                    @endif
                    </td>
                    <td>
                    @if($asset->ATH)
                    [ATH: {{$asset->ATH*1}} (<span id="{{$coin}}percent"></span>%)]
                    @endif
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
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
    <script>
    var binanceSocket = new WebSocket("{{$binanceSocket}}");
    @foreach($balance as $coin => $asset)
    var {{strtolower($coin)}} = {{$balance[$coin]->price}};
    var {{strtolower($coin)}}free = 0;
    var {{strtolower($coin)}}percent = 0;
    @endforeach
    // Create our number formatter.
    var formatter = new Intl.NumberFormat('hr-HR', {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2,
    });
    var busd_kn = {{$busd_kn}};
    var usdt_kn = {{$usdt_kn}};
    binanceSocket.onmessage = function(event) {
      var message = JSON.parse(event.data);
      @foreach($balance as $coin => $asset)
        @switch($coin)
          @case('EUR')
              {{strtolower($coin)}} = {{$asset->total}} * {{$eur_kn}};
              {{strtolower($coin)}}free = {{$asset->free}} * {{$eur_kn}};
            @break

          @case('BUSD')
            if (message.stream == 'eurbusd@kline_1m') {
              busd_kn = {{$eur_kn}} / message.data.k.c;
              {{strtolower($coin)}} = {{$asset->total}} * busd_kn;
              {{strtolower($coin)}}free = {{$asset->free}} * busd_kn;
            }
            @break

          @case('DAI')
            if (message.stream == 'busddai@kline_1m') {
              {{strtolower($coin)}} = {{$asset->total}} * busd_kn / message.data.k.c;
              {{strtolower($coin)}}free = {{$asset->free}} * busd_kn / message.data.k.c;
            }
            @break

          @case('SANTOS')
            if (message.stream == 'santosusdt@kline_1m') {
              {{strtolower($coin)}} = message.data.k.c * {{$asset->total}} * usdt_kn;
              {{strtolower($coin)}}free = message.data.k.c * {{$asset->free}} * usdt_kn;
              {{strtolower($coin)}}percent = message.data.k.c / {{$asset->ATH}} * 100;
            }
            @break

          @default
            if (message.stream == '{{strtolower($coin)}}busd@kline_1m') {
              {{strtolower($coin)}} = message.data.k.c * {{$asset->total}} * busd_kn;
              {{strtolower($coin)}}free = message.data.k.c * {{$asset->free}} * busd_kn;
              {{strtolower($coin)}}percent = message.data.k.c / {{$asset->ATH}} * 100;
            }
        @endswitch
        document.getElementById('price{{$coin}}').innerHTML = formatter.format({{strtolower($coin)}},2);
        @if ($asset->free)
        document.getElementById('{{$coin}}free').innerHTML = formatter.format({{strtolower($coin)}}free,2);
        @endif
        @if ($asset->ATH)
        document.getElementById('{{$coin}}percent').innerHTML = formatter.format({{strtolower($coin)}}percent,2);
        @endif
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
