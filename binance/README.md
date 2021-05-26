# Binance

[Part Time Larry](https://www.youtube.com/channel/UCY2ifv8iH1Dsgjrz-h3lWLQ)

- [Binance API](https://www.youtube.com/playlist?list=PLvzuUVysUFOuB1kJQ3S2G-nB7_nHhD7Ay)

## Part 2

```bash
npm install -g wscat
wscat -c wss://stream.binance.com:9443/ws/btcusdt@trade
```
```json
{"e":"trade","E":1617523773474,"s":"BTCUSDT","t":744644693,"p":"57497.07000000","q":"0.00034900","b":5441766279,"a":5441765967,"T":1617523773473,"m":false,"M":true}
```
```bash
wscat -c wss://stream.binance.com:9443/ws/btcusdt@kline_5m
```
```json
{"e":"kline","E":1617524175601,"s":"BTCUSDT","k":{"t":1617524100000,"T":1617524399999,"s":"BTCUSDT","i":"5m","f":744649686,"L":744651050,"o":"57479.99000000","c":"57520.11000000","h":"57527.97000000","l":"57446.11000000","v":"89.93967900","n":1365,"x":false,"q":"5170139.40269239","V":"40.02467100","Q":"2301420.17603603","B":"0"}}
```
```bash
wscat -c wss://stream.binance.com:9443/ws/btcusdt@kline_5m | tee dataset.txt
```
## Part 3
- [Lightweight Charts](https://github.com/tradingview/lightweight-charts)
## Part 4
- [python-binance](https://python-binance.readthedocs.io/en/latest/)
```bash
pip install python-binance
python get_data.py
```
## Part 5
- [Python wrapper for TA-Lib](https://mrjbq7.github.io/ta-lib/install.html)
- [How to install Ta-Lib in python on Windows](https://medium.com/@keng16302/how-to-install-ta-lib-in-python-on-window-9303eb003fbb)
```bash
pip install TA-Lib
pip install TA_Lib-0.4.19-cp38-cp38-win_amd64.whl
pip install numpy
python ta.py
```
## Part 6
- [Flask](https://flask.palletsprojects.com/en/1.1.x/)
```bash
pip install flask
export FLASK_APP=app.py
export FLASK_ENV=development
flask run
```
## Part 9
```bash
python get_data.py
pip install backtrader
pip install matplotlib
```
```bash
  File "C:\Program Files\Python38\lib\site-packages\backtrader\plot\locator.py", line 35, in <module>
    from matplotlib.dates import (HOURS_PER_DAY, MIN_PER_HOUR, SEC_PER_MIN,
ImportError: cannot import name 'warnings' from 'matplotlib.dates' (/Users/ch4r0n/.pyenv/versions/3.7.5/lib/python3.7/site-packages/matplotlib/dates.py)
backtrader/plot/locator.py
```
```py
from matplotlib.dates import (HOURS_PER_DAY, MIN_PER_HOUR, SEC_PER_MIN,
                              MONTHS_PER_YEAR, DAYS_PER_WEEK,
                              SEC_PER_HOUR, SEC_PER_DAY,
                              num2date, rrulewrapper, YearLocator,
                              MicrosecondLocator, warnings)
```
- The 'warnings' modules has been remove in the matplotlib3.3.0 so you should delete it warnings
```py
from matplotlib.dates import (HOURS_PER_DAY, MIN_PER_HOUR, SEC_PER_MIN,
                              MONTHS_PER_YEAR, DAYS_PER_WEEK,
                              SEC_PER_HOUR, SEC_PER_DAY,
                              num2date, rrulewrapper, YearLocator,
                              MicrosecondLocator)
```
```bash
python backtest.py
```
## Part CCXT
```bash
pip install -r requirements.txt
python app_ccxt.py
```
## Laravel
```bash
composer create-project --prefer-dist laravel/laravel command
php artisan list
php artisan make:command GetPrice
php artisan binance:get-prices
php artisan make:command GetAccountSnapshot
php artisan binance:get-account-snapshot
php artisan make:command GetMarketDataEndpoints
php artisan binance:get-market-data-endpoints
php artisan make:command SpotAccountTrade
php artisan binance:spot-account-trade
```

```bash
git add .
git commit -am "trades temp [binance]"
```