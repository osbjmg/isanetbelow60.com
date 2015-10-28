#!/usr/bin/python
#ystockquote from: https://pypi.python.org/pypi/ystockquote
import ystockquote
import os
from pprint import pprint
import time
import datetime

os.environ['TZ'] = 'US/Eastern'
time.tzset()

ANET_PRICE = ystockquote.get_price('ANET')
ANET_CHANGE = ystockquote.get_change('ANET')
ANET_VOL = ystockquote.get_volume('ANET')
DJIA_PRICE = ystockquote.get_price('INDU')
DJIA_CHANGE = ystockquote.get_change('INDU')
NDAQ_PRICE = ystockquote.get_price('CCMP')
NDAQ_CHANGE = ystockquote.get_change('CCMP')
CSCO_PRICE = ystockquote.get_price('CSCO')
CSCO_CHANGE = ystockquote.get_change('CSCO')

f = open('/home/osbjmg/isanetbelow60.com/bin/ANET.txt', 'w+')
f.write(ystockquote.get_price('ANET') + '\n')
ts = time.time()
st = datetime.datetime.fromtimestamp(ts).strftime('%H:%M:%S %m-%d-%Y')
f.write(st)
#f.write(ystockquote.get_all('ANET'))

"""
print "Current Arista Share Price: \n\n   $" + (ANET_PRICE) + "  " + (ANET_CHANGE) + "  " + (ANET_VOL) + "\n\n"
print "======================================================================\n"
print "NASDAQ (CCMP): \t\t" + (NDAQ_PRICE) + " " + (NDAQ_CHANGE)
print "Dow Jones (INDU):\t" + (DJIA_PRICE) + " " + (DJIA_CHANGE)
print "cisco (CSCO): \t\t" + (CSCO_PRICE) + " " + (CSCO_CHANGE)
"""

f.close()
