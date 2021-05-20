<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Orders') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-gray-100 border-b border-gray-200">
          <div id="app">
            <table class="table-auto w-full">
              <thead>
                <tr>
                  @foreach($simbols as $coin => $data)
                  <th>{{$coin}}</th>
                  @endforeach
                </tr>
              </thead>
              <tbody class="text-sm">
                @foreach([9,8,7,6,5,4,3,2,1,0] as $n)
                <tr>
                  @foreach($simbols as $coin => $data)
                  <td id="{{$coin}}+{{$n}}">{{$data['up'][$n]}} {{$data['busd10up'][$n]}} {{$data['up'][$n]*$data['busd10up'][$n]}}</td>
                  @endforeach
                </tr>
                @endforeach
                <tr>
                  @foreach($simbols as $coin => $data)
                  <td id="{{$coin}}">{{$data['price']*1}}</td>
                  @endforeach
                </tr>
                @foreach([0,1,2,3,4,5,6,7,8,9] as $n)
                <tr>
                  @foreach($simbols as $coin => $data)
                  <td id="{{$coin}}-{{$n}}">{{$data['down'][$n]}} {{$data['busd10down'][$n]}} {{$data['down'][$n]*$data['busd10down'][$n]}}</td>
                  @endforeach
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script>
    var binanceSocket = new WebSocket("wss://stream.binance.com:9443/stream?streams=adabusd@kline_1m/bnbbusd@kline_1m/ethbusd@kline_1m/maticbusd@kline_1m/btcbusd@kline_1m");
    binanceSocket.onmessage = function(event) {
      var message = JSON.parse(event.data);
      @foreach($coins as $coin)
      if (message.stream == '{{strtolower($coin[0])}}busd@kline_1m') {
        {{strtolower($coin[0])}} = message.data.k.c;
        document.getElementById('{{$coin[0]}}').innerHTML = {{strtolower($coin[0])}}*1;
        var busd10 = 10/{{strtolower($coin[0])}};
        var pow1 = Math.pow(10,{{$coin[1]}});
        var pow2 = Math.pow(10,{{$coin[2]}});

        var up = [];
        var busd10up = [];
        var down = [];
        var busd10down = [];

        up[0] = Math.floor(busd10*pow1)/pow1;
        busd10up[0] = Math.ceil(1/up[0]*10*pow2)/pow2;
        down[0] = Math.ceil(busd10*pow1)/pow1;
        busd10down[0] = Math.ceil(1/down[0]*10*pow2)/pow2;
        document.getElementById('{{$coin[0]}}-0').innerHTML = down[0]*1 + ' ' + busd10down[0];
        document.getElementById('{{$coin[0]}}+0').innerHTML = up[0]*1 + ' ' + busd10up[0];
        /*
      for ($i=0; $i < 10; $i++) { 
        $up[$i+1] = $up[$i]-1/$pow1;
        $busd10up[$i+1] = ceil(1/$up[$i+1]*10*$pow2)/$pow2;
        $down[$i+1] = $down[$i]+1/$pow1;
        $busd10down[$i+1] = ceil(1/$down[$i+1]*10*$pow2)/$pow2;
        # code...
      }
        */
      }
      @endforeach
    }

  </script>

</x-app-layout>
