<?php 
require_once('i_subs.php');

listfiles();


function listfiles()
   {
   global $uploadpath;
   global $nl;
   
   echo "Files: " . $nl;
   
   $f = scandir($uploadpath);
   $files = array();   
   foreach ($f as $file)
      {
      $files[$file] = filemtime($uploadpath . '/' . $file);
      }

    arsort($files);
    $files = array_keys($files);
    
   foreach ($files as $file)
      {
      if ($file == "." || $file == "..")
         {
         // do nothing
         }
      else
         {
         echo "<li><a href=\"upload/" . $file . "\" target=\"_blank\">" . $file . "</a></li>\n";         
         }
      }
   
   
    //return ($files) ? $files : false;
   }

?>