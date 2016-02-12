#!/usr/bin/python
import os
from pprint import pprint
import requests
import json
import re

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
f = open('/home/osbjmg/isanetbelow60.com/bin/STOCKS.json', 'w+')
f.write(json.dumps(stockInfo,indent=4))
f.close()
