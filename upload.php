<?php
//require_once ('freioerijcer.php');
require_once ('i_subs.php');
?>
<html>
<head>
<title></title>
<link rel="stylesheet" type="text/css" href="main.css">
</head>

<body>
<h2>Upload tracks</h2>

<?php

//echo "path: " . $path . $nl;

handle_login();

$folder = "";
if ( isset($_GET['folder']) )
   { $folder = $_GET['folder']; }

   
if (strpos($folder, "../") > 0)
   { echo "OI, NO!"; die(); }

$maxfilesize = "";

   
if ( !$folder )
   {
   echo "Hmm looks like you're missing a folder there good buddy. How about you try that shit one more time"; die();
   }
    
 
if ($folder == "RANDOM_INCOMING")
   {
   $extensions= array("mp3", "wav", "zip", "rar", "7z");
   $maxfilesize = 500;
   }
else
   {
   $extensions= array("mp3");
   $maxfilesize = 20;
   }
      
if(isset($_FILES['image']))
   {
   echo "IN DOINGTHE FILES BIT" . $nl;
   $errors= array();
   $file_name = $_FILES['image']['name'];
   $file_size =$_FILES['image']['size'];
   $file_tmp =$_FILES['image']['tmp_name'];
   $file_type=$_FILES['image']['type'];
   //$file_ext=strtolower(end(explode('.',$_FILES['image']['name'])));
   $tmp = explode('.', $file_name);
   $file_ext = end($tmp);
   

   
   
   
   if(in_array($file_ext,$extensions)=== false){
      $errors[]="what are you trying to pull? That extension is not allowed, please choose an MP3 file or we will have to send someone round to have a word.";
   }
   
   
   if ($folder == "RANDOM_INCOMING")
      {
      if($file_size > 524288000)
         { $errors[]='File size must be under 500 MB'; }
      }
   else
      {
      if($file_size > 20971520)
         { $errors[]='File size must be under 20 MB'; }
      }
      
   if(empty($errors)==true){
      //move_uploaded_file($file_tmp,"/home/pi/www/fluxcam-pi/".$file_name);
      echo "Moving to: " . $path . $folder . "/" . $file_name . $nl;
      move_uploaded_file($file_tmp,$path . $folder . "/" . $file_name);
      echo "Upload successful!" . $nl;
      //$dest = $path . $folder;
      //echo "Moving file to stream folder: " . $dest . $nl;
      //echo rename("/home/pi/www/server_home/".$file_name, $dest . "/" . $file_name);
      
   }else{
      print_r($errors);
   }
   echo $nl .$nl;
   }
?>

<a href="index.php">Home</a> | 
<a href="files.php?folder=<?php echo urlencode($folder); ?>">Manage files for <?php echo $folder ?></a><br><br>


<?php
echo "File types allowed in this folder: <span style=\"color: #cc0000\">";
foreach ($extensions as $ext)
   { echo $ext . " "; }
echo "</span>" . $nl . $nl;
echo "Max file size for this folder: <span style=\"color: #cc0000\">" . $maxfilesize . "mb</span>" . $nl;
echo $nl . $nl;

if ($folder <> "RANDOM_INCOMING")
   {
?>
<b>PLEASE NOTE:</b><br><br>

<br><br>
<?php
   }
?>

   <form action="" method="POST" enctype="multipart/form-data">
      <input type="file" name="image" class=input_upload />
      <input type="submit" class=input_button value="Upload that badboy"/>
   </form>
<?php

if (isset($file_name))
   {
   //header('Location: files.php?folder=' . $folder );
   }


?>
<body>
</html>
