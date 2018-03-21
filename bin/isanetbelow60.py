#!/usr/bin/python
import os
import requests
import json
import re
import urllib2
import sys
#import ystockquote

# Changes:
# Google started to block me again on Mar 20th 2018, switching to Alphavantage

# ToDo:
#  - fix 'lt' and 'ltt' dates for last trade date and time
#
# issues with the following URI started September 2017
#  https://github.com/hongtaocai/googlefinance/issues/39
# old URIs:
#  alphabetFinance= 'http://finance.google.com/finance/info?q='
#  alphabetFinance = 'https://finance.google.com/finance?q='


alphaVantageAPIKey = os.environ.get('ALPHAVANTAGE_API_KEY')

CLOSE = '4. close'
DAILY_TIME_SERIES = 'Time Series (Daily)'
ERROR_KEY = 'Error Message'

#tickers = ['ANET','TSLA', 'F']
tickers = ['ANET']
tickerString = ''
if len(tickers) <= 1:
    tickerString = tickers[0]
else :
    tickerString = ','.join(tickers)

alphaVantageURI = 'https://www.alphavantage.co/query?function=TIME_SERIES_DAILY&'\
+'symbol=%s&outputsize=compact&apikey=%s' %(
tickerString, alphaVantageAPIKey )

response = requests.get(alphaVantageURI)
json_response = response.json()

if response.status_code != 200 or ERROR_KEY in json_response:
    sys.exit(0)

daily_data = json_response[DAILY_TIME_SERIES]
sorted_dates = sorted(daily_data.keys(), reverse=True)
latest_data = daily_data[sorted_dates[0]]
prev_days_data = daily_data[sorted_dates[1]]

change = float(latest_data[CLOSE]) - float(prev_days_data[CLOSE])
change_percent = 100 * change / float(prev_days_data[CLOSE])
if change >= 0 :
    change_sign = '+'
else :
    change_sign = ''

metaData = json_response['Meta Data']
outerlist = []
fin_data_structure = {}
fin_data_structure['t'] = metaData['2. Symbol']
fin_data_structure['l'] = format(float(latest_data[CLOSE]), '.2f')
fin_data_structure['c'] = change_sign + format(change, '.2f')
fin_data_structure['cp'] = format(change_percent, '.2f')
fin_data_structure['lt'] = metaData['3. Last Refreshed']
fin_data_structure['ltt'] = '11:34AM EST'
fin_data_structure['lt_dts'] = '2017-02-22T11:35:01Z'

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

f_rt = open('/home/osbjmg/code/isanet-dev/bin/STOCK_RT.json', 'w+')
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
