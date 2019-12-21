<?php

$m = "";
if( isset($_GET['m']) )
   { $m = $_GET['m']; }

$u = "";
if( isset($_GET['u']) )
   { $u = $_GET['u']; }
   
   
if ($m)
   {
   if ($m == "##clear")
      {
      file_put_contents('chat.txt', "Welcome to Fluxcam Pi edition\n");  
      }
   else
      {
      $fp = fopen('chat.txt', 'a');//opens file in append mode  
      fwrite($fp, "<span class=user>&lt;" . $u . "&gt;</span> <span class=message>" . $m . "</span>\n");  
      //fwrite($fp, 'appending data');  
      fclose($fp);
      echo "Message added OK";
      }
   }

?>

