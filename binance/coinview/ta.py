import numpy
import talib
from numpy import genfromtxt

my_data = genfromtxt('2021_1minutes.csv', delimiter=',')

# print(my_data)

# close = numpy.random.random(100)
close = my_data[:,4]

# print(close)

# moving_average = talib.SMA(close, timeperiod=10)

# print(moving_average)

rsi = talib.RSI(close)

print(rsi)