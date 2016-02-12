<?php
$filename = 'STOCKS.json';
$filepath = 'bin/'.$filename;
$sixty = 60.00;
$theTicker = 'ANET';
$price ='';
$change='';
$percentChange='';
$theTime='';
?>
<?php
if (file_exists($filepath)) {
    $stockInfo = json_decode(file_get_contents($filepath),true);
    //print_r($stockInfo);
    //var_dump($stockInfo);
    // The idea here is to find the array with ANET, and then get its key/index
    //  and then pull out l, c, cp, lt_dts
    foreach ($stockInfo as $key => $value) {
        //echo "\r\n" . $key . ' > ' . $value ."\r\n";
        if (!is_array($value)) {
            //echo $key . '>>' . $value ."\r\n";
        } else {
            foreach ($value as $_key => $_val) {
                //echo 'key of '. $value . ' is: '. $key.'+  '."\r\n";
                if (in_array($theTicker, $value)) {
                    //echo $_key . ' : ' . $_val . "\r\n";
                    if ($_key == 'l') {
                        $price = $_val;
                    } elseif ($_key == 'c') {
                        $change = $_val;
                    } elseif ($_key == 'cp') {
                        $percentChange = $_val;
                    } elseif ($_key == 'ltt') {
                        $theTime = $_val;
                    }

                }
            }
        }
    }
} else {
     echo "Oops.  I have no clue at what price ANET is trading.";
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
 <head>
 <title>Is ANET below $60?</title>
 <link rel="canonical" href="http://isanetbelow60.com" />
 <link href="styles.css" rel="stylesheet" type="text/css" />
 <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
 <meta http-equiv="refresh" content="305" />
 <meta property="og:site_name" content="isanetbelow60.com" />
 <?php
 if($price < $sixty) {
     echo '<meta name="description" content=" Yes: $'.$price.', '.$change.' ('.$percentChange.'%)"/>';
     echo '<meta property="og:image" content="http://isanetbelow60.com/images/m.chandler.jpg" />';
 }
 else {
     echo '<meta name="description" content=" No: $'.$price.', '.$change.' ('.$percentChange.'%)"/>';
     echo '<meta property="og:image" content="http://isanetbelow60.com/images/kenneth_duda.jpg" />';
 }
 ?>

 </head>
 <body>
  <div id=top align=center>
   <h1>Is ANET below $60?</h1>
  </div>
  <div id=middle  align=center>
   <br>
   <br>
   <br>
   <?php
   if($price < $sixty) {
       echo "<b><font size=128 color=red>YES</font></b> <br><br>";
       echo "<img src='images/m.chandler.jpg' alt='A Lawyer'><br><br><br>";
       echo "ANET <a href=https://www.google.com/finance?q=anet target=_blank>$".$price."</a>";
   }
   else {
       echo "<b><font size=128 color=green>NO</font></b> <br><br>";
       echo "<img src='images/kenneth_duda.jpg' alt='Kenneth Duda'><br><br><br>";
       echo "<h2><font size=100>ANET <a href='https://www.google.com/finance?q=anet' target=_blank>$".$price."</a></font></h2>";
   }

   #echo "<br><br><br><br>";

   #echo "<div align=right>";
   echo "<font id=tiny color='#ccc'> *as of ".$theTime."</font>";
   #echo "</div>";
   ?>

   <br>
  </div>
 </body>
</html>
