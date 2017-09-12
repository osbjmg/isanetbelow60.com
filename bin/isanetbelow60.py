#!/usr/bin/python
import os
from pprint import pprint
import ystockquote
import requests
import json
import re
import urllib2

# ToDo:
#  - fix 'lt' and 'ltt' dates for last trade date and time

# issues with the following URI started September 2017
#  https://github.com/hongtaocai/googlefinance/issues/39
# old URI:
#  alphabetFinance= 'http://finance.google.com/finance/info?q='

alphabetFinance = 'https://finance.google.com/finance?q='

#tickers = ['ANET','TSLA', 'F']
tickers = ['ANET']
tickerString = ''
if len(tickers) <= 1:
    tickerString = tickers[0]
else :
    tickerString = ','.join(tickers)
url = alphabetFinance + tickerString + '&output=json'
r = requests.get(url)
#tehJason = re.sub('^\s//\s','',r.text)
#stockInfo = json.loads(tehJason)

stockInfo = json.loads(r.content[6:-2].decode('unicode_escape'))
outerlist = []
fin_data_structure = {}
fin_data_structure["t"] = stockInfo['t']
fin_data_structure["l"] = stockInfo['l']
fin_data_structure["c"] = stockInfo['c']
fin_data_structure["cp"] = stockInfo['cp']
#obviously data is fake, looks like new google json has no date
fin_data_structure["lt"] = 'Feb 22, 11:34AM EST'
fin_data_structure["ltt"] = '11:34AM EST'

outerlist.append(fin_data_structure)

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
f_rt = open('/home/osbjmg/isanetbelow60.com/bin/STOCK_RT.json', 'w+')
f_rt.write(json.dumps(outerlist, indent=4))
f_rt.close()

# I can ask ystockqote for the previous week/month of closing prices, and plot them
# let's try the past 30 days
#try:
#    stockInfo_hist = ystockquote.get_historical_prices('ANET', '2016-05-18', '2016-05-19')
#    f_hist = open ('/home/osbjmg/isanetbelow60.com/bin/STOCK_HIST.json', 'w+')
#    f_hist.write(json.dumps(stockInfo_hist,indent=4))
#    f_hist.close()
#except urllib2.HTTPError, error:
#    print ('ystockquote caught an HTTP error:')
#    print ('    {}'.format(error))
#except:
#    print('An error has occurred.')
