#!/usr/bin/python
import os
import requests
import json
import re
import urllib2
import sys
import time
#import ystockquote
import pprint
# Changes:
# Google started to block me again on Mar 20th 2018, switching to Alphavantage

# ToDo:
#  - sometimes reqeusts timeout, I need to increase the timeout/retries in requests
#
# issues with the following URI started September 2017
#  https://github.com/hongtaocai/googlefinance/issues/39
# old URIs:
#  alphabetFinance= 'http://finance.google.com/finance/info?q='
#  alphabetFinance = 'https://finance.google.com/finance?q='

alphaVantageAPIKey = os.environ.get('ALPHAVANTAGE_API_KEY')

CLOSE = '4. close'
INTRADAY_TIME_SERIES = 'Time Series (1min)'
DAILY_TIME_SERIES = 'Time Series (Daily)'
ERROR_KEY = 'Error Message'

path_string = '/home/osbjmg/code/isanet-dev/'
path_string = '/home/osbjmg/isanetbelow60.com/'

#tickers = ['ANET','TSLA', 'F']
tickers = ['ANET']
tickerString = ''
if len(tickers) <= 1:
    tickerString = tickers[0]
else :
    tickerString = ','.join(tickers)

alphaVantageIntraday = 'https://www.alphavantage.co/query?function=TIME_SERIES_INTRADAY&'\
+'symbol=%s&interval=1min&outputsize=compact&apikey=%s' %(
tickerString, alphaVantageAPIKey )

alphaVantageDaily = 'https://www.alphavantage.co/query?function=TIME_SERIES_DAILY&'\
+'symbol=%s&outputsize=compact&apikey=%s' %(
tickerString, alphaVantageAPIKey )

try:
    response = requests.get(alphaVantageIntraday)
except requests.exceptions.ConnectionError:
    response.status_code = "Connection refused"
    time.sleep(5)
    response = requests.get(alphaVantageIntraday)


intraday_json = response.json()
if response.status_code != 200 or ERROR_KEY in intraday_json:
    sys.exit(0)

intraday_data = intraday_json[INTRADAY_TIME_SERIES]
sorted_intraday = sorted(intraday_data.keys(), reverse=True)
latest_intraday = intraday_data[sorted_intraday[0]]

# enforce a sleep of 2 seconds to be fair to the API provider
time.sleep(2)

try:
    response = requests.get(alphaVantageDaily)
except requests.exceptions.ConnectionError:
    response.status_code = "Connection refused"
    time.sleep(5)
    response = requests.get(alphaVantageDaily)

daily_json = response.json()
if response.status_code != 200 or ERROR_KEY in daily_json:
    sys.exit(0)

daily_data = daily_json[DAILY_TIME_SERIES]
sorted_daily = sorted(daily_data.keys(), reverse=True)
penultimate_daily = daily_data[sorted_daily[1]]

change = float(latest_intraday[CLOSE]) - float(penultimate_daily[CLOSE])
change_percent = 100 * change / float(penultimate_daily[CLOSE])
if change >= 0 :
    change_sign = '+'
else :
    change_sign = ''

#print(str(change) + ' ' + str(change_percent))

metaData = intraday_json['Meta Data']
outerlist = []
fin_data_structure = {}
fin_data_structure['t'] = metaData['2. Symbol']
fin_data_structure['l'] = format(float(latest_intraday[CLOSE]), '.2f')
fin_data_structure['c'] = change_sign + format(change, '.2f')
fin_data_structure['cp'] = format(change_percent, '.2f')
fin_data_structure['lt_dts'] = metaData['3. Last Refreshed']
fin_data_structure['ltt'] = metaData['3. Last Refreshed'].split()[1]
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
