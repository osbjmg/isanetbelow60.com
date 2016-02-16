# isanetbelow60.com

This is the website: <a href =http://www.isanetbelow60.com>isanetbelow60</a>, a personal website for me to check Arista stock price.

# notes
The site creates two files:

bin/STOCK_RT.json to store the real-time price and timestamp
bin/STOCK_HIST.json to store historical prices, used to generate trend/histogram

I use a crontab to run this the isanetbelow60.py file periodically:
/usr/bin/python ~/isanetbelow60.com/bin/isanetbelow60.py
