## isanetbelow60.com

This is the source for the "is anet" series of websites: <a href =http://www.isanetbelow60.com>isanetbelow60</a>, <a href =http://www.isanetabove100.com>isanetabove100</a>.  I use it to check the Arista stock price.

### notes
Two files are created:
<br>
*bin/STOCK_RT.json* to store the real-time price and timestamp
<br>
*bin/STOCK_HIST.json* to store historical prices, used to generate trend/histogram

A crontab runs the isanetbelow60.py file periodically:
> /usr/bin/python ~/isanetbelow60.com/bin/isanetbelow60.py
