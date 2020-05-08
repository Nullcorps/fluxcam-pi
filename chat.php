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
      if ( strpos(strtolower($m), "http://") > 0 || substr(strtolower($m), 0 , 7) == "http://")
        {
         //$m .= "LINK FOUND";
         $mm = explode("http://", $m);
         $m .= "array count: " . count($mm);
         $nn = explode(" ", $mm[count($mm)-1]);
         $m .= "NN count: " . count($nn);
         $out .= $mm[0] . "<a href=\"http://" . $nn[0] . "\" target=\"_blank\">http://" . $nn[0] . "</a> " . substr($mm[count($mm)-1], strlen($nn[0]) );
         $m .= " output string: " . $out;
         $m = $out;
        }
      
      if ( strpos(strtolower($m), "https://") > 0 || substr(strtolower($m), 0 , 8) == "https://")
        {
         //$m .= "LINK FOUND";
         $mm = explode("https://", $m);
         $m .= "array count: " . count($mm);
         $nn = explode(" ", $mm[count($mm)-1]);
         $m .= "NN count: " . count($nn);
         $out .= $mm[0] . "<a href=\"https://" . $nn[0] . "\" target=\"_blank\">https://" . $nn[0] . "</a> " . substr($mm[count($mm)-1], strlen($nn[0]) );
         $m .= " output string: " . $out;
         $m = $out;
        }
      
      //echo "strpos: " . strpos(strtolower($m), "http://") . $nl;
      
      
      $fp = fopen('chat.txt', 'a');//opens file in append mode  
      fwrite($fp, "<span class=user>&lt;" . $u . "&gt;</span> <span class=message>" . $m . "</span>\n");  
      //fwrite($fp, 'appending data');  
      fclose($fp);
      echo "Message added OK";
      }
   }

?>

