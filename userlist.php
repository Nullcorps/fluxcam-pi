<?php
//userlist
require_once('i_subs.php');

$u = file_get_contents($path . '/active_users.txt');


$au = explode("\n", $u);

foreach ($au as $user)
   {
   $uu = explode("||", $user);
   echo $uu[0] . $nl;   
   }
