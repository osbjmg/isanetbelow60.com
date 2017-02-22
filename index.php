<?php
$filename = 'STOCK_RT.json';
$filepath = 'bin/'.$filename;
$sixty = 60.00;
$hundy = 100.00;
$ninty = 90.00;
$theTicker = 'ANET';
$price ='';
$change='';
$percentChange='';
$pos_or_neg='';
$this_site='';
$this_site_number='';
$above_or_below='';
$good='';
$bad='';

if (file_exists($filepath)) {
    $stockInfo = json_decode(file_get_contents($filepath),true);
    $interesting_key = array_search($theTicker, array_column($stockInfo, 't'));
    //echo '$interesting_key: '.$interesting_key.' is interesting.'."\r\n";
    //print_r($stockInfo);
    //var_dump($stockInfo);
    // The idea here is to find the array with ANET, and then get its key/index
    //  and then pull out l, c, cp, lt_dts
    foreach ($stockInfo[$interesting_key] as $_key => $_val) {
        //echo 'key of '. $value . ' is: '. $key.'+  '."\r\n";
        //echo $_key . ' : ' . $_val . "\r\n";
        if ($_key == 'l') {
            $price = $_val;
        } elseif ($_key == 'c') {
            $change = $_val;
        } elseif ($_key == 'cp') {
            $percentChange = $_val;
        } elseif ($_key == 'ltt') {
            $theTime = $_val;
        } elseif ($_key == 'lt') {
            $theDate = $_val;
        } elseif ($_key == 'lt_dts') {
            $theDateZ = $_val;
        }
    }
} else {
     echo "Oops.  I have no clue at what price ANET is trading.";
}

$this_site = $_SERVER['HTTP_HOST'];
if (strpos($this_site,'60') !== false) {
   $this_site_number='60';
   $above_or_below='below';
   $good='No';
   $bad='Yes';
} elseif (strpos($this_site,'100') !== false) {
   $this_site_number='100';
   $above_or_below='above';
   $good='Yes';
   $bad='No';
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
 <head>
 <?php
 echo '<title>Is ANET '.$above_or_below.' $'.$this_site_number.'</title>';
 echo '<link rel="canonical" href="http://'.$this_site.'" />';
 echo '<link href="styles.css" rel="stylesheet" type="text/css" />';
 echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
 echo '<meta http-equiv="refresh" content="305" />';
 echo '<meta property="og:site_name" content="'.$this_site.'" />';
 echo '<meta name="twitter:label1" value="Reading time"><meta name="twitter:data1" value="1 min">';
 #echo '<meta name="twitter:label2" value="Updated"><meta name="twitter:data2" value="'.$theDate.'">';
 echo '<meta property="article:published_time" content="'.$theDateZ.'-05:00" />';

 if($price < $sixty) {
     echo '<meta name="description" content=" '.$bad.': $'.$price.', '.$change.' ('.$percentChange.'%)"/>';
     echo '<meta property="og:image" content="http://'.$this_site.'/images/m.chandler.jpg" />';
 } else {
     echo '<meta name="description" content=" '.$good.': $'.$price.', '.$change.' ('.$percentChange.'%)"/>';
     if ($price > $hundy) {
         echo '<meta property="og:image" content="http://'.$this_site.'/images/duda_hug.jpg" />';
     } elseif($price >= $ninty) {
         echo '<meta property="og:image" content="http://'.$this_site.'/images/adam_pokemon.jpg" />';
     } else {
        echo '<meta property="og:image" content="http://'.$this_site.'/images/kenneth_duda.jpg" />';
     }
 }
 ?>

 </head>
 <body>
  <div id=top align=center>
   <?php
   echo '<h1>Is ANET '.$above_or_below.' $'.$this_site_number.'?</h1>';
   ?>
  </div>
  <div id=middle  align=center>
   <br>
   <br>
   <br>
   <?php
   if( $price < $sixty ) {
       echo "<b><font size=100 color=#990012>".strtoupper($bad)."</font></b> <br><br>";
       echo "<img src='images/m.chandler.jpg' alt='A Lawyer'><br><br><br>";
   } else {
       echo "<b><font size=128 color=green>".strtoupper($good)."</font></b> <br><br>";
       if ($price > $hundy) {
           echo "<img src='images/duda_hug.jpg' alt='Kenneth Duda'><br><br><br>";
       } elseif ($price >= $ninty) {
           echo "<img src='images/adam_pokemon.jpg' alt='Pokemon'><br><br><br>";
       } else {
           echo "<img src='images/kenneth_duda.jpg' alt='Kenneth Duda'><br><br><br>";
       }
   }

   echo "<h2><font size=100>ANET <a href='https://www.google.com/finance?q=anet' target=_blank>$".$price."</a></font></h2>";
   if((float)$percentChange > 0){
       $pos_or_neg = 'green';
   } elseif ((float)$percentChange < 0) {
       $pos_or_neg = '#990012';
   }
   echo "<h3><font color=".$pos_or_neg.">".$change." (".$percentChange."%)</font></h3>";
   #echo "<br><br><br><br>";
   #echo "<div align=right>";
   echo "<br><font id=tiny color='#ccc'> * as of ".$theTime."</font>";
   #echo "</div>";
   ?>
   <!-- <br>
   <iframe width="900" height="800" frameborder="0" scrolling="no" src="https://plot.ly/~osbjmg/10.embed"></iframe>
   -->
 </div>

 </body>
</html>
