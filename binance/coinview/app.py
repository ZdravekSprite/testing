from flask import Flask, render_template, request, flash, redirect, jsonify
import config
import csv
from binance.client import Client
from binance.enums import *

app = Flask(__name__)
app.secret_key = b'htrshthdtzjjmmftmtdmtmtdz'

client = Client(config.API_KEY, config.API_SECRET)
# print(client.get_all_coins_info())


@app.route('/')
# def hello_world():
#  return 'Hello, World!'
def index():
    title = 'CoinView'

    account = client.get_account()

#  balances = account['balances']
    balances = []

    for balance in account['balances']:
        #    print(balance['free'])
        if float(balance['free']) + float(balance['locked']) > 0:
            balances.append(balance)

    exchange_info = client.get_exchange_info()
    symbols = exchange_info['symbols']

    return render_template('index.html', title=title, my_balances=balances, symbols=symbols)


@app.route('/pair/<string:pair>')
def pair(pair):
    title = 'CoinView (' + pair + ')'
    account = client.get_account()
    exchange_info = client.get_exchange_info()

    balances = []
    assets = []

    prices = client.get_all_tickers()
    # print(prices)
    for price in prices:
      print(price['symbol'])

    for balance in account['balances']:
        total = float(balance['free']) + float(balance['locked'])
        if total > 0:
            balance['eur'] = next(
              (float(price['price']) for price in prices if price['symbol'] == balance['asset'] + 'EUR'), 0)*total
            if balance['eur'] == 0:
              balance['eur'] = next(
                (float(price['price']) for price in prices if price['symbol'] == 'EUR' + balance['asset']), 0)*total
            balances.append(balance)
            # print(balance['asset'])
            assets.append(balance['asset'])

    # print(balances)
    # print(exchange_info['symbols'])

    symbols = []
    for symbol in exchange_info['symbols']:
        # print(symbol)
        # print(symbol['symbol'])
        if symbol['quoteAsset'] in assets:
            symbols.append(symbol)
            # print(symbol['symbol'])

    # print(symbols)

    return render_template('index.html', title=title, pair=pair, my_balances=balances, symbols=symbols)

# @app.route('/buy', methods=['POST'])
# def buy():
#   print(request.form)
#   try:
#     order = client.create_order(symbol=request.form['symbol'],
#       side=SIDE_BUY,
#       type=ORDER_TYPE_MARKET,
#       quantity=request.form['quantity'])
#   except Exception as e:
#     flash(e.message, "error")

#   return redirect('/')

# @app.route('/sell')
# def sell():
#   return 'sell'

# @app.route('/settings')
# def settings():
#   return 'settings'


@app.route('/history/<string:pair>')
def history(pair):
    candlesticks = client.get_historical_klines(
        pair, Client.KLINE_INTERVAL_15MINUTE, "1 Apr, 2021", "6 Apr, 2021")

    processed_candlesticks = []

    for data in candlesticks:
        candlestick = {
            "time": data[0] / 1000,
            "open": data[1],
            "high": data[2],
            "low": data[3],
            "close": data[4]
        }

        processed_candlesticks.append(candlestick)

    return jsonify(processed_candlesticks)
