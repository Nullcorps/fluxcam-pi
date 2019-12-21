<?php
require_once('i_subs.php');

// here's where the users register that they're still active
$ul = "";
$arul = "";
$arul2 = "";
$foundself = false;

$timeout = 10;


if (file_exists($path . "/active_users.txt") )
   {
   $ul = file_get_contents($path . "/active_users.txt");
   $arul = explode("\n", $ul);
   }




$u = "";

$t = time();

if ( isset($_GET['u']) )
   { $u = $_GET['u']; }
   

if ($u <> "") 
   {
   echo "Registering user: " . $u . " at time " . $t . $nl;   
   
   if ( is_array($arul) )
      {
      echo $nl;
      echo "Check thru array of users and update this one if present" . $nl . $nl;   
      foreach( $arul as $user)
         {
         if ($user <> "")
            {
            echo "Found user in list: " . $user . $nl;
            $uu = explode("||", $user);
            
            if ($uu[0] == $u)
               {
               if (!$foundself)
                  {
                  echo "That's this user, update time" . $nl;   
                  $arul2 .= $u . "||" . $t . "\n";
                  $foundself = true;
                  }
               }
            else
               {
               echo "That's not this user, add it to the output list and carry on" . $nl;
               echo "User age: " . ($t - $uu[1]) . $nl;
               if ($t - $uu[1] > $timeout)
                  {
                  echo "User is older than " . $timeout . " sec, delete" . $nl;
                  }
               else
                  {
                  $arul2  .= $uu[0] . "||" . $uu[1] . "\n";
                  }
               }
            
            
            }
         echo $nl;
         }
      
      if (!$foundself)
         {
         echo "Self not found, adding" . $nl;
         $arul2 .= $u . "||" . $t . "\n";
         }
      
      echo "<pre>";
      print_r($arul2);
      echo "</pre>";
      
      file_put_contents($path . "/active_users.txt", $arul2);
      
      }
   else
      {
      echo "No userlist found, must be furst run, create and save" . $nl;   
      file_put_contents($path . "/active_users.txt", $u . "||" . $t . "\n");
      }
   }