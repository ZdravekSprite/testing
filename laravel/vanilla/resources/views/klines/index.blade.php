<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>test</title>
  <script src="https://unpkg.com/lightweight-charts/dist/lightweight-charts.standalone.production.js"></script>
</head>

<body>
  <div id="chart_BTCUSDT"></div>
  <div id="chart_ETHUSDT"></div>
  <div id="chart_BNBUSDT"></div>
  <script>
  @foreach(['BTCUSDT' , 'ETHUSDT', 'BNBUSDT'] as $symbol)
    var chart_{{$symbol}} = LightweightCharts.createChart(document.getElementById('chart_{{$symbol}}'), {
      width: 800
      , height: 400
      , layout: {
        backgroundColor: '#000000'
        , textColor: 'rgba(255, 255, 255, 0.9)'
      , }
      , grid: {
        vertLines: {
          color: 'rgba(197, 203, 206, 0.5)'
        , }
        , horzLines: {
          color: 'rgba(197, 203, 206, 0.5)'
        , }
      , }
      , crosshair: {
        mode: LightweightCharts.CrosshairMode.Normal
      , }
      , priceScale: {
        borderColor: 'rgba(197, 203, 206, 0.8)'
      , }
      , timeScale: {
        borderColor: 'rgba(197, 203, 206, 0.8)'
        , timeVisible: true
        , secondsVisible: false
      , }
    , });

    var candleSeries_{{$symbol}} = chart_{{$symbol}}.addCandlestickSeries({
      upColor: '#00ff00'
      , downColor: '#ff0000'
      , borderDownColor: 'rgba(255, 144, 0, 1)'
      , borderUpColor: 'rgba(255, 144, 0, 1)'
      , wickDownColor: 'rgba(255, 144, 0, 1)'
      , wickUpColor: 'rgba(255, 144, 0, 1)'
    , });

    fetch('https://api.binance.com/api/v3/klines?symbol={{$symbol}}&interval=1m&limit=100')
      .then((r) => r.json())
      .then((response) => {
        //console.log('response_binance', response)
        var objs = response.map(function(x) {
          return {
            time: x[0] / 1000
            , open: x[1]
            , high: x[2]
            , low: x[3]
            , close: x[4]
          };
        });
        console.log(objs);
        //console.log('response_data', data)
        candleSeries_{{$symbol}}.setData(objs);
      })

  @endforeach

    // /stream?streams=<streamName1>/<streamName2>/<streamName3>
    //var binanceSocket = new WebSocket("wss://stream.binance.com:9443/ws/btcusdt@kline_1m");
    var binanceSocket = new WebSocket("wss://stream.binance.com:9443/stream?streams=btcusdt@kline_1m/ethusdt@kline_1m/bnbusdt@kline_1m");

    binanceSocket.onmessage = function(event) {
      var message = JSON.parse(event.data);
      console.log('message', message)
      if (message.stream == 'bnbusdt@kline_1m') {
        var candlestick = message.data.k;
        candleSeries_BNBUSDT.update({
          time: candlestick.t / 1000
          , open: candlestick.o
          , high: candlestick.h
          , low: candlestick.l
          , close: candlestick.c
        })
      }
      if (message.stream == 'ethusdt@kline_1m') {
        var candlestick = message.data.k;
        candleSeries_ETHUSDT.update({
          time: candlestick.t / 1000
          , open: candlestick.o
          , high: candlestick.h
          , low: candlestick.l
          , close: candlestick.c
        })
      }
      if (message.stream == 'btcusdt@kline_1m') {
        var candlestick = message.data.k;
        candleSeries_BTCUSDT.update({
          time: candlestick.t / 1000
          , open: candlestick.o
          , high: candlestick.h
          , low: candlestick.l
          , close: candlestick.c
        })
      }

      //var candlestick = message.k;

      console.log('candlestick', candlestick)

    }

  </script>
</body>

</html>
