<?php
$passfile = "/var/www/www/fluxcam-pi/.htpasswd";
$loginsfile = "/var/www/www/fcp_logins.txt";
$uploadpath = "/var/www/www/fluxcam-pi/upload";
$nl = "<br>";
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="../main.css">
</head>
<body>
 
<h3><a href="index.php">ADMIN:</a></h3>
<div style="font-size: 20px;">
<br>
Add a user:<br><br>
<form action="" method=post>
Username:<br>
<input type=text name=username class=normal_textfield size=10><br>
<br>
Password:<br>
<input type=text name=password class=normal_textfield size=10><br>
<br>
<input type=submit class=normal_button value="Add user">
</form>

<?php
$username = "";
$password = "";

if ( isset($_POST['username']) && isset($_POST['password']) )
   {
   $username = $_POST['username'];
   $password = $_POST['password'];
   
      // Password to be encrypted for a .htpasswd file
   
   echo "Using password: " . $password . $nl; 
   // Encrypt password
   $cpassword = crypt($clearTextPassword, base64_encode($password));
   
   $hash = base64_encode(sha1($password, true));
   echo "Hash: " . $hash . $nl;
   
   $person = $username . ':{SHA}' . $hash . "\n";

   
   
   // Print encrypted password
   
   //$person = $username . ":" . $cpassword;
   
   echo "Adding user: " . $person . " with password " . $password . " to " . $passfile . $nl;
   $f = file_put_contents($passfile, $person, FILE_APPEND);
   $ff = file_put_contents($loginsfile, $username . " : " . $password . "\n", FILE_APPEND);
   echo "Done: " . $f;
   echo "<script>location.href=location.href</script>";
   
   }


echo $nl . $nl;
echo "Listing logins file:" . $nl;
$logins = file_get_contents($loginsfile);
$arlogins = explode("\n", $logins);
echo "<pre>";
print_r($arlogins);
echo "</pre>";




echo $nl . $nl;
echo "Listing htpasswd file:" . $nl;

$users = file_get_contents($passfile);

$arusers = explode("\n", $users);
echo "<pre>";
print_r($arusers);
echo "</pre>";



echo "<B>UPLOADS</B>" . $nl;
echo "<div style=\"padding: 20px;\">";

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
      echo "<li><a href=\"upload/" . $file . "\" target=\"_blank\">" . $file . "</a> - <a href=\"index.php?d=" . urlencode($file) . "\">[del]</a></li>" . $nl;         
      }
   }
   
echo "</div>";

$d = "";

if ( isset($_GET['d']) )
   { $d = $_GET['d']; }

if ($d <> "")
   {
   echo "Delete file: " . $d . $nl;
   
   $f = $uploadpath . "/" . $d;
   unlink($f);
   echo "<script>location.href='index.php';</script>";
   

   }

echo $nl;


?>

<br><br><br>
</div>
</body>
</html>
