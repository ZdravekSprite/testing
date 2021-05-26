import backtrader as bt
import datetime

class RSIStrategy(bt.Strategy):

  def __init__(self):
    self.rsi = bt.talib.RSI(self.data, period=14)

    self.rsi= bt.indicators.RelativeStrengthIndex()
    self.stoch = bt.indicators.Stochastic()
    self.ATR= bt.indicators.AverageTrueRange(period=7)
    self.MAC = bt.indicators.MACDHisto()
    self.order = None # Property to keep track of pending orders.  There are no orders when the strategy is initialized.
    self.buyprice = None
    self.buycomm = None

  def next(self):
#    if self.rsi < 30 and not self.position:
    if self.rsi < 45 and not self.position:
      self.buy(size=1)

#    if self.rsi > 70 and self.position:
    if self.rsi > 55 and self.position:
      self.close()

cerebro = bt.Cerebro()
cerebro.broker.setcash(100000.0)
cerebro.broker.setcommission(commission=0.001)

fromdate = datetime.datetime.strptime('2021-04-01', '%Y-%m-%d')
todate = datetime.datetime.strptime('2021-04-05', '%Y-%m-%d')

data = bt.feeds.GenericCSVData(dataname='data/BTCUSDT_2021_15MINUTE.csv', dtformat=2, compression=15, timeframe=bt.TimeFrame.Minutes, fromdate=fromdate, todate=todate)

cerebro.adddata(data)

cerebro.addstrategy(RSIStrategy)

cerebro.run()

cerebro.plot()
