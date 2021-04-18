<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Curl\Curl;

class SpotAccountTrade extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'binance:spot-account-trade';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Spot Account/Trade';

  /**
   * Create a new command instance.
   *
   * @return void
   */
  public function __construct()
  {
    parent::__construct();
  }

  /**
   * Execute the console command.
   *
   * @return int
   */
  public function handle()
  {
    $test = true;

    if ($test) {
      $server = 'https://testnet.binance.vision/api';
      $ws = 'wss://testnet.binance.vision/ws';
      $stream = 'wss://testnet.binance.vision/stream';
      $apiKey = env('BINANCE_TEST_API_KEY');
      $apiSecret = env('BINANCE_TEST_API_SECRET');
    } else {
      $server = 'https://api.binance.com/api';
      $ws = 'wss://stream.binance.com:9443/ws';
      $stream = 'wss://stream.binance.com:9443/stream';
      $apiKey = env('BINANCE_API_KEY');
      $apiSecret = env('BINANCE_API_SECRET');
    }

    $this->line('Spot Account/Trade:');
    $this->line('Test New Order (TRADE):');
    /*Test New Order (TRADE)
     * Response:
     *  {}
     * POST /api/v3/order/test (HMAC SHA256)
     *Test new order creation and signature/recvWindow long. Creates and validates a new order but does not send it into the matching engine.
     * Weight: 1
     * Parameters:
     *  Same as POST /api/v3/order
     */
    $time = json_decode(Http::get($server . '/v3/time'));
    $serverTime = $time->serverTime;
    // Variables
    // url, key and secret is on separate file, called using require once
    $symbol = "BNBUSDT";
    $side = "SELL";
    $type = "LIMIT";
    $timeInForce = "GTC";
    $quantity = 10;
    $price = 500;
    // Constructing query arrays
    $queryArray = array(
      "symbol" => $symbol,
      "side" => $side,
      "type" => $type,
      "timeInForce" => $timeInForce,
      "quantity" => $quantity,
      "price" => $price,
      "timestamp" => $serverTime
    );
    $signature = hash_hmac("sha256", http_build_query($queryArray), $apiSecret);
    $signatureArray = array("signature" => $signature);
    $curlArray = $queryArray + $signatureArray;
    /*
    // Curl : setting header and POST
    $curl = new Curl();
    $curl->setHeader("Content-Type", "application/x-www-form-urlencoded");
    $curl->setHeader("X-MBX-APIKEY", $apiKey);

    $curl->post($server . '/v3/order', $curlArray);

    if ($curl->error) {
      echo 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n";
    }
    $order = $curl->response;
    //dd($order->{'orderId'});
    $this->line('Order ID: '.$order->{'orderId'});
*/
    /*
    // New Order (TRADE)
    // Response ACK:
    
    // {
    //   "symbol": "BTCUSDT",
    //   "orderId": 28,
    //   "orderListId": -1, //Unless OCO, value will be -1
    //   "clientOrderId": "6gCrw2kRUAF9CvJDGP16IP",
    //   "transactTime": 1507725176595
    // }
    // Response RESULT:
    
    // {
    //   "symbol": "BTCUSDT",
    //   "orderId": 28,
    //   "orderListId": -1, //Unless OCO, value will be -1
    //   "clientOrderId": "6gCrw2kRUAF9CvJDGP16IP",
    //   "transactTime": 1507725176595,
    //   "price": "0.00000000",
    //   "origQty": "10.00000000",
    //   "executedQty": "10.00000000",
    //   "cummulativeQuoteQty": "10.00000000",
    //   "status": "FILLED",
    //   "timeInForce": "GTC",
    //   "type": "MARKET",
    //   "side": "SELL"
    // }
    // Response FULL:
    
    // {
    //   "symbol": "BTCUSDT",
    //   "orderId": 28,
    //   "orderListId": -1, //Unless OCO, value will be -1
    //   "clientOrderId": "6gCrw2kRUAF9CvJDGP16IP",
    //   "transactTime": 1507725176595,
    //   "price": "0.00000000",
    //   "origQty": "10.00000000",
    //   "executedQty": "10.00000000",
    //   "cummulativeQuoteQty": "10.00000000",
    //   "status": "FILLED",
    //   "timeInForce": "GTC",
    //   "type": "MARKET",
    //   "side": "SELL",
    //   "fills": [
    //     {
    //       "price": "4000.00000000",
    //       "qty": "1.00000000",
    //       "commission": "4.00000000",
    //       "commissionAsset": "USDT"
    //     },
    //     {
    //       "price": "3999.00000000",
    //       "qty": "5.00000000",
    //       "commission": "19.99500000",
    //       "commissionAsset": "USDT"
    //     },
    //     {
    //       "price": "3998.00000000",
    //       "qty": "2.00000000",
    //       "commission": "7.99600000",
    //       "commissionAsset": "USDT"
    //     },
    //     {
    //       "price": "3997.00000000",
    //       "qty": "1.00000000",
    //       "commission": "3.99700000",
    //       "commissionAsset": "USDT"
    //     },
    //     {
    //       "price": "3995.00000000",
    //       "qty": "1.00000000",
    //       "commission": "3.99500000",
    //       "commissionAsset": "USDT"
    //     }
    //   ]
    // }
    // POST /api/v3/order (HMAC SHA256)
    
    // Send in a new order.
    
    // Weight: 1
    
    // Parameters:
    
    // Name	Type	Mandatory	Description
    // symbol	STRING	YES	
    // side	ENUM	YES	
    // type	ENUM	YES	
    // timeInForce	ENUM	NO	
    // quantity	DECIMAL	NO	
    // quoteOrderQty	DECIMAL	NO	
    // price	DECIMAL	NO	
    // newClientOrderId	STRING	NO	A unique id among open orders. Automatically generated if not sent.
    // stopPrice	DECIMAL	NO	Used with STOP_LOSS, STOP_LOSS_LIMIT, TAKE_PROFIT, and TAKE_PROFIT_LIMIT orders.
    // icebergQty	DECIMAL	NO	Used with LIMIT, STOP_LOSS_LIMIT, and TAKE_PROFIT_LIMIT to create an iceberg order.
    // newOrderRespType	ENUM	NO	Set the response JSON. ACK, RESULT, or FULL; MARKET and LIMIT order types default to FULL, all other orders default to ACK.
    // recvWindow	LONG	NO	The value cannot be greater than 60000
    // timestamp	LONG	YES	
    // Additional mandatory parameters based on type:
    
    // Type	Additional mandatory parameters
    // LIMIT	timeInForce, quantity, price
    // MARKET	quantity or quoteOrderQty
    // STOP_LOSS	quantity, stopPrice
    // STOP_LOSS_LIMIT	timeInForce, quantity, price, stopPrice
    // TAKE_PROFIT	quantity, stopPrice
    // TAKE_PROFIT_LIMIT	timeInForce, quantity, price, stopPrice
    // LIMIT_MAKER	quantity, price
    // Other info:
    
    // LIMIT_MAKER are LIMIT orders that will be rejected if they would immediately match and trade as a taker.
    // STOP_LOSS and TAKE_PROFIT will execute a MARKET order when the stopPrice is reached.
    // Any LIMIT or LIMIT_MAKER type order can be made an iceberg order by sending an icebergQty.
    // Any order with an icebergQty MUST have timeInForce set to GTC.
    // MARKET orders using the quantity field specifies the amount of the base asset the user wants to buy or sell at the market price.
    // For example, sending a MARKET order on BTCUSDT will specify how much BTC the user is buying or selling.
    // MARKET orders using quoteOrderQty specifies the amount the user wants to spend (when buying) or receive (when selling) the quote asset; the correct quantity will be determined based on the market liquidity and quoteOrderQty.
    // Using BTCUSDT as an example:
    // On the BUY side, the order will buy as many BTC as quoteOrderQty USDT can.
    // On the SELL side, the order will sell as much BTC needed to receive quoteOrderQty USDT.
    // MARKET orders using quoteOrderQty will not break LOT_SIZE filter rules; the order will execute a quantity that will have the notional value as close as possible to quoteOrderQty.
    // same newClientOrderId can be accepted only when the previous one is filled, otherwise the order will be rejected.
    // Trigger order price rules against market price for both MARKET and LIMIT versions:
    
    // Price above market price: STOP_LOSS BUY, TAKE_PROFIT SELL
    // Price below market price: STOP_LOSS SELL, TAKE_PROFIT BUY
     */
    $this->line('Cancel Order (TRADE):');
    /* Cancel Order (TRADE) */
/*
    $time = json_decode(Http::get($server . '/v3/time'));
    $serverTime = $time->serverTime;
    $queryArray = array(
      "symbol" => $symbol,
      "orderId" => 4337849,
      //"origClientOrderId" => 4338053,
      "timestamp" => $serverTime
    );
    $signature = hash_hmac("sha256", http_build_query($queryArray), $apiSecret);
    $signatureArray = array("signature" => $signature);
    $curlArray = $queryArray + $signatureArray;
    // Curl : setting header and POST
    $curl = new Curl();
    $curl->setHeader("Content-Type", "application/x-www-form-urlencoded");
    $curl->setHeader("X-MBX-APIKEY", $apiKey);

    $curl->delete($server . '/v3/order', $curlArray);

    if ($curl->error) {
      echo 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n";
    }
    $order = $curl->response;
    //dd($order);
    if(isset($order->{'orderId'})) $this->line('Order ID: ' . $order->{'orderId'});
*/
    $this->line('Current Open Orders (USER_DATA):');
    /* Current Open Orders (USER_DATA) */
/*
    $time = json_decode(Http::get($server . '/v3/time'));
    $serverTime = $time->serverTime;
    $signature = hash_hmac('SHA256', 'timestamp=' . $serverTime, $apiSecret);
    $openOrders = json_decode(Http::withHeaders([
      'X-MBX-APIKEY' => $apiKey
    ])->get($server . '/v3/openOrders', [
      'timestamp' => $serverTime,
      'signature' => $signature
    ]));
    foreach ($openOrders as $key => $order) {
      $this->line(
        'date: ' . gmdate("Y-m-d H:i:s", $order->time / 1000)
          . ' pair: ' . $order->symbol
          . ' orderId: ' . $order->orderId
      );
    }
*/
    $this->line('Account Trade List (USER_DATA):');
    /* Account Trade List (USER_DATA) */

    $time = json_decode(Http::get($server . '/v3/time'));
    $serverTime = $time->serverTime;
    $queryArray = array(
      "symbol" => $symbol,
      "timestamp" => $serverTime
    );
    $signature = hash_hmac("sha256", http_build_query($queryArray), $apiSecret);
    $signatureArray = array("signature" => $signature);
    $curlArray = $queryArray + $signatureArray;
    $curl = new Curl();
    $curl->setHeader("Content-Type", "application/x-www-form-urlencoded");
    $curl->setHeader("X-MBX-APIKEY", $apiKey);
    $curl->get($server . '/v3/myTrades', $curlArray);
    if ($curl->error) {
      echo 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n";
    }
    $orders = $curl->response;
    //dd($orders);
    foreach ($orders as $key => $order) {
      //dd($order);
      $this->line(
        'date: ' . gmdate("Y-m-d H:i:s", $order->time / 1000)
          . ' pair: ' . $order->symbol
          . ' orderId: ' . $order->orderId
          . ' id: ' . $order->id
          . ' orderListId: ' . $order->orderListId
          . ' price: ' . $order->price
          . ' qty: ' . $order->qty
          . ' quoteQty: ' . $order->quoteQty
          . ' commission: ' . $order->commission
          . ' commissionAsset: ' . $order->commissionAsset
          . ' time: ' . $order->time
          . ' isBuyer: ' . $order->isBuyer
          . ' isMaker: ' . $order->isMaker
          . ' isBestMatch: ' . $order->isBestMatch
      );
    }

  }
}
