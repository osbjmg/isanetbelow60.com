#!/usr/bin/python
import os
from pprint import pprint
import ystockquote
import requests
import json
import re

# maybe look into https://github.com/hongtaocai/googlefinance
alphabetFinance= 'http://finance.google.com/finance/info?q='

tickers = ['ANET','CSCO']
tickerString = ''
if len(tickers) <= 1:
    tickerString = tickers[0]
else :
    tickerString = ','.join(tickers)
url = alphabetFinance + tickerString
r = requests.get(url)
tehJason = re.sub('^\s//\s','',r.text)
stockInfo = json.loads(tehJason)
'''
for item in stockInfo:
    print '\n'
    print 'Ticker', item['t']
    print 'Last', item['l']
    print 'Change', item['c']
    print '% change', item['cp']
    print 'Last trade', item['lt_dts']
print json.dumps(stockInfo ,indent=4)
'''
# I can ask ystockqote for the previous week/month of closing prices, and plot them
# let's try the past 30 days
stockInfo_hist = ystockquote.get_historical_prices('ANET', '2016-05-18', '2016-05-19')

f_rt = open('/home/osbjmg/isanetbelow60.com/bin/STOCK_RT.json', 'w+')
f_rt.write(json.dumps(stockInfo,indent=4))
f_rt.close()
f_hist = open ('/home/osbjmg/isanetbelow60.com/bin/STOCK_HIST.json', 'w+')
f_hist.write(json.dumps(stockInfo_hist,indent=4))
f_hist.close()
