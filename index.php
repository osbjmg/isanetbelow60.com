<?php
$filename = 'ANET.txt';
$filepath = 'bin/'.$filename;
$sixty = 60.00;
?>
<?php
if (file_exists($filepath)) {
    $handle	= fopen($filepath, "r");
    $i = 0;
    while (!feof($handle)) {
        $line=fgets($handle);
        if ($i == 0) {
            $price = number_format((float) $line, 2);
        } elseif ($i == 1) {
            $theTime = $line;
        } else {
            echo "Error: source datafile format unrecognized.";
        }
        $i++;
    }
}
else {
     echo "Oops.  I have no clue what ANET is trading at.";
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
     echo '<meta name="description" content="Is ANET below $60? - YES: '.$price.'"/>';
     echo '<meta property="og:image" content="http://isanetbelow60.com/images/m.chandler.jpg" />';
 }
 else {
     echo '<meta name="description" content="Is ANET below $60? - NO: '.$price.'" />';
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
   echo "<font id=tiny color='#ccc'>*as of ".$theTime."</font>";
   #echo "</div>";
   ?>

   <br>
  </div>
 </body>
</html>
