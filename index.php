<?php
$filename = 'STOCK_RT.json';
$filepath = 'bin/'.$filename;
$theTicker = 'ANET';
$tzOffset = '04:00'; // EDT 4, EST 5

// Get current stock info
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
            $theDateZ = str_replace("Z","-$tzOffset", $_val);
        }
    }
} else {
     $price = NULL;
     $change = NULL;
     $percentChange = NULL;
     echo "Oops.  I have no clue at what price ANET is trading.";
}

// color the change and percent change number based on positive or negative change
if (!is_null($percentChange)) {
    if ((double)$percentChange > 0){
        $pos_or_neg = 'pctChgPos';
    } elseif ((double)$percentChange < 0) {
        $pos_or_neg = 'pctChgNeg';
    }
}

// Read URI and determine the website we are on, ie.: isanetabove100.com, isanetbelow60.com, ...
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
} elseif (strpos($this_site,'150') !== false) {
   $this_site_number='150';
   $above_or_below='above';
   $good='Yes';
   $bad='No';
} else {
   $this_site_number='0';
   $above_or_below='unknown';
   $good='unknown';
   $bad='unknown';
}

// Define Emoji
$emojiHeartEyes ="\u{1F60D}";
$emojiHeadBandage = "\u{1F915}";
$emojiSeeNoEvil = "\u{1F648}";
$emojiPersevere = "\u{1F623}";
$emojiRollingEyes = "\u{1F644}";
$emojiFire = "\u{1F525}";
$emojiJudge = "\u{1F469}";
$emojiGrin = "\u{1F601}";
$emojiHug = "\u{1F917}";

// logic for site name to price decisions
if($above_or_below == 'below' && $price > (double)$this_site_number) { // Website is a pessimistic URL (isanetbelow60.com), but we beat it
    $answer = $good;
    $displayedImage  = 'kenneth_duda.jpg';
    $fontStyleGoodOrBad = 'good';
    $emoji = $emojiHeartEyes;
} elseif ($above_or_below == 'below' && $price <= (double)$this_site_number) { // Website is a pessimistic URL, and we did not beat it
    $answer = $bad;
    $displayedImage = 'm.chandler.jpg';
    $fontStyleGoodOrBad = 'bad';
    $emoji = $emojiJudge;
} else { // Website is an optimistic URL (isanetabove100.com)
    if ($price > (double)$this_site_number + ((double)$this_site_number*0.10)) { // 10% or more above the website price
        $answer = $good;
        $displayedImage = 'duda_hug.jpg'; 
        $fontStyleGoodOrBad = 'good';
        $emoji = $emojiHug;
    } elseif ($price > (double)$this_site_number && $price <= ((double)$this_site_number + ((double)$this_site_number*0.10))) { // Between just above price and 10% above the website price
        $answer = $good;
        $displayedImage = 'adam_pokemon.jpg';
        $fontStyleGoodOrBad = 'good';
        $emoji = $emojiGrin;
    } elseif($price > (double)$this_site_number - ((double)$this_site_number *0.50)) { // Between 50% below and  just below the website price
        $answer = $bad;
        $displayedImage = 'kenneth_duda.jpg';
        $fontStyleGoodOrBad = 'bad';
        $emoji = $emojiPersevere;
    } else { // Below 50% of the website price
        $answer = $bad;
        $displayedImage = 'm.chandler.jpg';
        $fontStyleGoodOrBad = 'bad';
        $emoji = $emojiJudge;
    }
}

if ($displayedImage == 'duda_hug.jpg') {
    $displayedImageAltText = 'Kenneth Duda';
} elseif ($displayedImage == 'kenneth_duda.jpg') {
    $displayedImageAltText = 'Kenneth Duda';
} elseif ($displayedImage == 'adam_pokemon.jpg') {
    $displayedImageAltText = 'A pokemon';
} elseif ($displayedImage == 'm.chandler.jpg') {
    $displayedImageAltText = 'A lawyer';
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
 echo '<meta name="twitter:label1" value="Updated"><meta name="twitter:data1" value="'.$theDate.'">';
 echo '<meta name="twitter:label2" value="Reading time"><meta name="twitter:data2" value="~ 1 minute">';
 #echo '<meta property="article:published_time" content="'.$theDateZ.'" />';
 echo '<meta name="description" content=" '.$answer.': $'.$price.', '.$change.' ('.$percentChange.'%)"/>';
 echo '<meta property="og:image" content="http://'.$this_site.'/images/'.$displayedImage.'"/>';
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
   <?php
   echo '<b><span class="'.$fontStyleGoodOrBad.'">'.strtoupper($answer).' '.$emoji.'<span></b> <br><br>';
   echo '<img src="images/'.$displayedImage.'" alt="'.$displayedImageAltText.'"><br><br>';
   echo '<h1>ANET <a href="https://www.google.com/finance?q=anet" target=_blank>$'.$price.'</a></font></h2>';
   echo '<h3><span class="'.$pos_or_neg.'">'.$change.'('.$percentChange.'%)</font></h3>';
   echo '<br><span class="tiny"> * as of '.$theTime.'</font>';
   ?>
   <!-- <br>
   <iframe width="900" height="800" frameborder="0" scrolling="no" src="https://plot.ly/~osbjmg/10.embed"></iframe>
   -->
 </div>

 </body>
</html>
