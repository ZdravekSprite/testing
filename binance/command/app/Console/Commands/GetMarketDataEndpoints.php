<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use API;

class GetMarketDataEndpoints extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'binance:get-market-data-endpoints';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Get Market Data Endpoints';

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
    $test = false;

    if ($test) {
      $Server = 'https://testnet.binance.vision/api';
      $ws = 'wss://testnet.binance.vision/ws';
      $stream = 'wss://testnet.binance.vision/stream';
      $ApiKey = env('BINANCE_TEST_API_KEY');
      $ApiSecret = env('BINANCE_TEST_API_SECRET');
    } else {
      $Server = 'https://api.binance.com/api';
      $ws = 'wss://stream.binance.com:9443/ws';
      $stream = 'wss://stream.binance.com:9443/stream';
      $ApiKey = env('BINANCE_API_KEY');
      $ApiSecret = env('BINANCE_API_SECRET');
    }

    $this->line('Get Market Data Endpoints:');
    $this->line('Test Connectivity:');
    /*
     *Test Connectivity
     * Response:
     *  {}
     * GET /api/v3/ping
     *
     *Test connectivity to the Rest API.
     * Weight: 1
     * Parameters:
     *  NONE
    */
    $this->line('Check Server Time:');
    /*
     *Check Server Time
     * Response:
     *  {
     *   "serverTime": 1499827319559
     *  }
     * GET /api/v3/time
     *Test connectivity to the Rest API and get the current server time.
     * Weight: 1
     * Parameters:
     *  NONE
    */
    //dd(json_decode(Http::get($Server . '/v3/time')));
    /*
    $time = json_decode(Http::get($Server . '/v3/time'));
    $serverTime = $time->serverTime;
    $this->line('$serverTime: ' . gmdate("Y-m-d H:i:s", $serverTime / 1000));
*/
    $this->line('Exchange Information:');
    /*
     *Exchange Information
     * Response:
     *  {
     *   "timezone": "UTC",
     *   "serverTime": 1565246363776,
     *   "rateLimits": [
     *     {
     *       //These are defined in the `ENUM definitions` section under `Rate Limiters (rateLimitType)`.
     *       //All limits are optional
     *     }
     *   ],
     *   "exchangeFilters": [
     *     //These are the defined filters in the `Filters` section.
     *     //All filters are optional.
     *   ],
     *   "symbols": [
     *     {
     *       "symbol": "ETHBTC",
     *       "status": "TRADING",
     *       "baseAsset": "ETH",
     *       "baseAssetPrecision": 8,
     *       "quoteAsset": "BTC",
     *       "quotePrecision": 8,
     *       "quoteAssetPrecision": 8,
     *       "orderTypes": [
     *         "LIMIT",
     *         "LIMIT_MAKER",
     *         "MARKET",
     *         "STOP_LOSS",
     *         "STOP_LOSS_LIMIT",
     *         "TAKE_PROFIT",
     *         "TAKE_PROFIT_LIMIT"
     *       ],
     *       "icebergAllowed": true,
     *       "ocoAllowed": true,
     *       "isSpotTradingAllowed": true,
     *       "isMarginTradingAllowed": true,
     *       "filters": [
     *         //These are defined in the Filters section.
     *         //All filters are optional
     *       ],
     *       "permissions": [
     *          "SPOT",
     *          "MARGIN"
     *       ]
     *     }
     *   ]
     *  }
     * GET /api/v3/exchangeInfo
     *Current exchange trading rules and symbol information
     * Weight: 1
     * Parameters:
     *  NONE
     */
    //dd(json_decode(Http::get($Server . '/v3/exchangeInfo')));
    $exchangeInfo = json_decode(Http::get($Server . '/v3/exchangeInfo'));
    /*
    $timezone = $exchangeInfo->timezone;
    $this->line('$timezone: ' . $timezone);
*/
    $serverTime = $exchangeInfo->serverTime;
    $this->line('$serverTime: ' . gmdate("Y-m-d H:i:s", $serverTime / 1000));
    /*
    $rateLimits = $exchangeInfo->rateLimits;
    $this->line('$rateLimits: ' . json_encode($rateLimits));
    foreach ($rateLimits as $key => $value) {
      $this->line($key . ': ' . json_encode($value));
    }
    $exchangeFilters = $exchangeInfo->exchangeFilters;
    $this->line('$exchangeFilters: ' . json_encode($exchangeFilters));
    foreach ($exchangeFilters as $key => $value) {
      $this->line($key . ': ' . json_encode($value));
    }
*/
    $symbols = $exchangeInfo->symbols;
    //$this->line('$symbols: ' . json_encode($symbols));
    $oldSymbols = ["BCHSVUSDT", "STORMUSDT", "BCCBTC", "HSRBTC", "OAXETH", "DNTETH", "MCOETH", "ICNETH", "MCOBTC", "WTCETH", "STRATBTC", "STRATETH", "SNGLSETH", "SNMETH", "SALTBTC", "SALTETH", "MDAETH", "SUBBTC", "SUBETH", "MTHETH", "ENGBTC", "ENGETH", "ASTETH", "ICNBTC", "BTGETH", "EVXETH", "REQETH", "HSRETH", "ARKETH", "YOYOETH", "MODBTC", "MODETH", "STORJETH", "VENBNB", "YOYOBNB", "POWRBNB", "VENBTC", "VENETH", "NULSBNB", "RCNETH", "RCNBNB", "NULSETH", "RDNETH", "RDNBNB", "DLTBNB", "DLTETH", "AMBETH", "AMBBNB", "BCCETH", "BCCUSDT", "BCCBNB", "BCPTBTC", "BCPTETH", "BCPTBNB", "ARNBTC", "ARNETH", "GVTETH", "POEBTC", "POEETH", "QSPBNB", "BTSETH", "BTSBNB", "XZCBTC", "XZCETH", "XZCBNB", "LSKBNB", "TNTBTC", "TNTETH", "FUELBTC", "FUELETH", "BCDETH", "DGDBTC", "DGDETH", "ADXBNB", "PPTETH", "CMTBTC", "CMTETH", "CMTBNB", "CNDETH", "CNDBNB", "LENDBTC", "LENDETH", "WABIETH", "TNBBTC", "TNBETH", "GTOETH", "GTOBNB", "OSTBNB", "AIONBNB", "NEBLBNB", "BRDBNB", "MCOBNB", "EDOBTC", "EDOETH", "WINGSBTC", "WINGSETH", "NAVBNB", "LUNBTC", "LUNETH", "TRIGBTC", "TRIGETH", "TRIGBNB", "APPCETH", "APPCBNB", "VIBEBTC", "VIBEETH", "RLCBNB", "INSBTC", "INSETH", "PIVXBNB", "CHATBTC", "CHATETH", "STEEMBNB", "NANOBNB", "VIAETH", "VIABNB", "AEBTC", "AEETH", "AEBNB", "RPXBTC", "RPXETH", "RPXBNB", "NCASHBTC", "NCASHBNB", "POAETH", "POABNB", "STORMBTC", "STORMETH", "STORMBNB", "QTUMBNB", "XEMBNB", "WPRETH", "SYSETH", "SYSBNB", "QLCBNB", "GRSETH", "CLOAKBTC", "CLOAKETH", "GNTBTC", "GNTETH", "GNTBNB", "LOOMBNB", "BCNBTC", "BCNETH", "BCNBNB", "REPBNB", "TUSDBTC", "TUSDETH", "TUSDBNB", "SKYETH", "SKYBNB", "CVCBNB", "AGIETH", "AGIBNB", "NXSETH", "NXSBNB", "NPXSBTC", "NPXSETH", "VENUSDT", "KEYBTC", "NASBNB", "MFTBTC", "DENTBTC", "ARDRETH", "ARDRBNB", "HOTBTC", "DOCKETH", "POLYBNB", "PHXBTC", "PHXETH", "PHXBNB", "HCBTC", "HCETH", "GOBNB", "PAXBTC", "PAXBNB", "PAXETH", "DCRBNB", "USDCBNB", "BCHABCBTC", "BCHSVBTC", "BCHABCUSDT", "EOSPAX", "XLMPAX", "RENBNB", "XLMTUSD", "XLMUSDC", "NEOTUSD", "XZCXRP", "PAXTUSD", "USDCTUSD", "USDCPAX", "LINKPAX", "WAVESTUSD", "WAVESPAX", "WAVESUSDC", "BCHABCTUSD", "BCHABCPAX", "BCHABCUSDC", "BCHSVTUSD", "BCHSVPAX", "BCHSVUSDC", "BTTBTC", "BNBUSDS", "BTCUSDS", "USDSUSDT", "USDSPAX", "USDSTUSD", "USDSUSDC", "BTTPAX", "ONGBNB", "ZRXBNB", "ZECPAX", "ZECTUSD", "ADAPAX", "NEOPAX", "OMGBNB", "ATOMPAX", "ATOMTUSD", "ETCUSDC", "ETCPAX", "ETCTUSD", "BATPAX", "BATTUSD", "PHBBNB", "PHBUSDC", "PHBPAX", "TFUELBNB", "TFUELUSDC", "TFUELTUSD", "TFUELPAX", "ONETUSD", "ONEPAX", "ONEUSDC", "FTMTUSD", "FTMPAX", "FTMUSDC", "BTCBBTC", "BCPTTUSD", "BCPTPAX", "BCPTUSDC", "ALGOPAX", "ALGOUSDC", "USDSBUSDT", "USDSBUSDS", "GTOPAX", "GTOTUSD", "GTOUSDC", "ERDBNB", "ERDBTC", "ERDUSDT", "ERDPAX", "ERDUSDC", "DOGEBNB", "DOGEPAX", "DOGEUSDC", "DUSKBNB", "DUSKUSDC", "DUSKPAX", "BGBPUSDC", "ANKRTUSD", "ANKRPAX", "ANKRUSDC", "ONTPAX", "ONTUSDC", "WINBTC", "TUSDBTUSD", "NPXSUSDT", "NPXSUSDC", "COCOSBTC", "TOMOBNB", "TOMOUSDC", "PERLUSDC", "BEAMBNB", "HCUSDT", "NKNBNB", "BCHABCBUSD", "BUSDNGN", "BNBNGN", "MCOUSDT", "CTXCBNB", "VITEBNB", "DREPBNB", "BULLUSDT", "BULLBUSD", "BEARUSDT", "BEARBUSD", "ETHBULLUSDT", "ETHBULLBUSD", "ETHBEARUSDT", "ETHBEARBUSD", "TCTBNB", "BTSBUSD", "LTOBNB", "EOSBULLUSDT", "EOSBULLBUSD", "EOSBEARUSDT", "EOSBEARBUSD", "XRPBULLUSDT", "XRPBULLBUSD", "XRPBEARUSDT", "XRPBEARBUSD", "STRATBUSD", "STRATBNB", "STRATUSDT", "AIONBUSD", "MBLBTC", "BNBBULLUSDT", "BNBBULLBUSD", "BNBBEARUSDT", "BNBBEARBUSD", "STPTBNB", "BTCZAR", "ETHZAR", "BNBZAR", "USDTZAR", "BUSDZAR", "BTCBKRW", "ETHBKRW", "BNBBKRW", "XZCUSDT", "BUSDIDRT", "HIVEBNB", "ERDBUSD", "LENDUSDT", "MDTBNB", "REPBUSD", "COMPBNB", "BKRWUSDT", "BKRWBUSD", "VTHOBUSD", "DCRBUSD", "STORJBUSD", "IRISBNB", "IRISBUSD", "DAIBNB", "DAIBTC", "DAIUSDT", "DAIBUSD", "LENDBUSD", "XRPBKRW", "ADABKRW", "USDTBKRW", "BUSDBKRW", "BALBNB", "BLZBUSD", "KMDBUSD", "PAXGBUSD", "WNXMBUSD", "TRBBNB", "ETHNGN", "BZRXBNB", "SRMBIDR", "ONEBIDR", "LENDBKRW", "LINKBKRW", "FLMBNB", "TRXNGN", "AAVEBKRW", "DOTBKRW", "BOTBTC", "BOTBUSD", "LTCNGN", "XRPNGN", "SUSDETH", "LINKNGN", "DOTNGN"];    $this->line('$symbols:');
    foreach ($symbols as $key => $value) {
      $symbol = $value->symbol;
      if ($value->status == 'TRADING') {
        $klines = json_decode(Http::get($Server . '/v3/klines?symbol=' . $symbol . '&interval=1m&limit=15'));
        if ($value->quoteAsset == 'USDT') {
          //dd($klines[0][1]);
          $tick1 = (isset($klines[4]) && isset($klines[0])) ? 100 * ($klines[4][4] - $klines[0][1]) / $klines[0][1] : 0;
          $tick2 = (isset($klines[9]) && isset($klines[5])) ? 100 * ($klines[9][4] - $klines[5][1]) / $klines[5][1] : 0;
          $tick3 = (isset($klines[14]) && isset($klines[10])) ? 100 * ($klines[14][4] - $klines[10][1]) / $klines[10][1] : 0;
          if ($tick3 > 2) $this->line($klines[10][0] . ' ' . gmdate("Y-m-d H:i:s", $klines[10][0] / 1000) . ' ' . $symbol . ' ' . $tick1 . ' ' . $tick2 . ' ' . $tick3);
        }
      }
    }
    /* Kline/Candlestick Data */
    //dd(json_decode(Http::get($Server . '/v3/klines?symbol=BNBUSDT&interval=1m&limit=10')));
    /*
    $klines = json_decode(Http::get($Server . '/v3/klines?symbol=TRXUSDT&interval=1m&limit=5'));
    foreach ($klines as $key => $value) {
      //dd($value);
      $this->line($key . ': ' . gmdate("Y-m-d H:i:s", $value[0] / 1000) . ' ' . $value[1] . ' -> ' . $value[4] . ' '  . gmdate("Y-m-d H:i:s", $value[6] / 1000));
    }
*/
    $time = json_decode(Http::get($Server . '/v3/time'));
    $serverTime = $time->serverTime;
    $this->line('$serverTime: ' . gmdate("Y-m-d H:i:s", $serverTime / 1000) . ' ' . $serverTime);
  }
}
