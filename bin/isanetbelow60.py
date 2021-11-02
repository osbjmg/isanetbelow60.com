#!/usr/bin/python3
import os
import requests
import json
import re
#import urllib2
#import urllib.request
import sys
import time
#import ystockquote
import pprint
import datetime

# Changes:
# Google started to block me again on Mar 20th 2018, switching to Alphavantage
# Alphavantage doesn't understand daylight savings and stopped updating stock prices during the day, switching to IEX


# ToDo:
#  - sometimes reqeusts timeout, I need to increase the timeout/retries in requests
#
# issues with the following URI started September 2017
#  https://github.com/hongtaocai/googlefinance/issues/39
# old URIs:
#  alphabetFinance= 'http://finance.google.com/finance/info?q='
#  alphabetFinance = 'https://finance.google.com/finance?q='

iexAPIKey = os.environ.get('IEX_API_KEY')

price = 'latestPrice'
change = 'change'
changePercent = 'changePercent'

path_string = '/home/osbjmg/code/isanet-dev/'
path_string = '/home/osbjmg/isanetbelow60.com/'

#tickers = ['ANET','TSLA', 'F']
tickers = ['ANET']
tickerString = ''
if len(tickers) <= 1:
    tickerString = tickers[0]
else :
    tickerString = ','.join(tickers)

payload = {
    'token' : iexAPIKey,
}
iexQuote = ('https://cloud.iexapis.com/stable/stock/%s/quote' % tickerString)
try:
    stock = requests.get(iexQuote, params=payload)
except requests.exceptions.ConnectionError:
    stock.status_code = "Connection refused"
    time.sleep(5)
    stock = requests.get(iexQuote, params=payload)


intraday_json = stock.json()
if stock.status_code != 200:
    sys.exit(0)

# enforce a sleep of 2 seconds to be fair to the API provider
time.sleep(2)

change = intraday_json[change]
change_percent = 100 * intraday_json[changePercent]
if change >= 0 :
    change_sign = '+'
else :
    change_sign = ''
ltt_dts = datetime.datetime.fromtimestamp(intraday_json['lastTradeTime']/1000.0)
ltt_dts = ltt_dts.strftime('%Y-%m-%d %H:%M:%S.%f')

#print(str(change) + ' ' + str(change_percent))

outerlist = []
fin_data_structure = {}
fin_data_structure['t'] = intraday_json['symbol']
fin_data_structure['l'] = format(float(intraday_json['latestPrice']), '.2f')
fin_data_structure['c'] = change_sign + format(change, '.2f')
fin_data_structure['cp'] = format(change_percent, '.2f')
fin_data_structure['lt_dts'] = ltt_dts
fin_data_structure['ltt'] = ltt_dts.split()[1]

#fin_data_structure['lt_dts'] = 'xxxx-02-22T11:35:01Z'

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

f_rt = open(path_string + 'bin/STOCK_RT.json', 'w+')
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
