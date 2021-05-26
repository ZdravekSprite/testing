import ccxt
#print(ccxt.exchanges)
#for exchange in ccxt.exchanges:
#  print(exchange)
exchange = ccxt.binance()
#print(exchange)
#symbols = exchange.symbols                 # get a list of symbols
#print(symbols)
#currencies = exchange.currencies           # a dictionary of currencies
#print(currencies)
markets = exchange.load_markets()
#print(markets)
for market in markets:
#  print(market)
  print(exchange.markets[market])
#  print(exchange.markets[market]['symbol'],exchange.markets[market]['base'],exchange.markets[market]['quote'])
#  for row in market:
#    print(row)
#print (exchange.load_markets ())

#exchange.load_markets ()

#print(exchange.markets['BTC/USD'])