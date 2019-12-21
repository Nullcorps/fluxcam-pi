<?php
require_once('i_subs.php');
?>
<html>
<head>
<title>FluxcamPi</title>
<script src="jquery-3.4.1.min.js"></script>
<link rel="stylesheet" type="text/css" href="main.css">
</head>
<body>

<div id=master_container>
<div id=video_container style="awidth: 95%; border: 0px solid red; text-align: center; display: block;">
<img src="html/cam_pic.php?ddfsdf" id=campic style="width: 640px" ondblclick="togglefullscreen()" onload="picready=true">
</div>

<div id=files_container style=" overflow-y: scroll; display: none; awidth: 99%; background-color: #efefef; height: 200px; border: 0px solid blue; text-align: left; padding: 20px; font-size: 24px; line-height: 120%; border-bottom: 2px solid #333333;">
Files:<br>
</div>



</div>

<div id=halp style=" ">
<li>Set your nickname and then click "set ID" to begin chatting</li>
<li>You can pause the chat if you need to scroll up and copy/paste something</li>
<li>Double click on the video to fill the browser</li>
<li>If you get red lots of flashing borders around the video that means it's dropping frames, try a lower FPS for smoother video</li>
<li>Show this message by clicking the ? button</li>
<br>
<center><input class=normal_button type=button value="close" onclick="popuphelp()"></center>
</div>


<div id=messages_container style="border: 0px solid green; vertical-align: top;">
<div id=messages_raw style="display: none; border: 0px solid orange; height 100px;"></div>
<div id=messages style="display: inline-block; width: 70%; border: 0px solid purple; height: 200px; overflow-y: scroll; " onmousedown="pause=true" onmouseup="pause=false">
Loading messages...
</div><div id=userlist style="vertical-align: top; display: inline-block; width: 150px; overflow-y: scroll; height: 200px; >USERS HERE</div>
<div id=chat_container style="width: 100%; border: 0px solid teal"></div>
<input id=chat_input type=text style="width: 100%;">
</div>


<div id=status></div>


<div id=controls_container style="padding: 2px; border: 0px solid blue; text-align: center;">
<input class=normal_textfield type=text id=userid size=1><input class=normal_button type=button value="Set" onclick="setuserid()" title="Set your chosen user id">

<select id=fpschooser class=normal_textfield onchange="dofps()" title="change the video framerate">
   <option value="2">2fps</option>
   <option value="3">3fps</option>
   <option value="5" selected>5fps</option>
   <option value="7">7fps</option>
   <option value="10">10fps</option>
   <option value="12">12fps</option>
   <option value="15">15fps</option>
   <option value="20">20fps</option>
   <option value="25">25fps</option>
</select>

<input id=scrollbutton class=normal_button type=button value="Pause" onclick="togglescrolling()" title="Pause the chat scrolling">

<input id=videobuttonbigger class=normal_button type=button value="-" onclick="video_smaller()" title="Make the video smaller">
Video
<input id=videobuttonsmaller class=normal_button type=button value="+" onclick="video_bigger()" title="Make the video bigger">

<input id=chatbuttonbigger class=normal_button type=button value="-" onclick="chat_smaller()" title="Make the chat window smaller">
Chat
<input id=chatbuttonsmaller class=normal_button type=button value="+" onclick="chat_bigger()" title="Make the chat window taller">

<input id=textbuttonbigger class=normal_button type=button value="-" onclick="text_smaller()" title="Make the text smaller">
Text
<input id=textbuttonsmaller class=normal_button type=button value="+" onclick="text_bigger()" title="Make the text bigger">

<input id=helpbutton class=normal_button type=button value="?" onclick="popuphelp()">

<input type=checkbox id=popswitch onchange="togglepop()" style="height: 20px; width: 20px;"> Pop

<div style="padding-top: 8px;">
  <form action="" method="POST" enctype="multipart/form-data">
      <input type="file" name="image" class=normal_button />
      <input type="submit" class=normal_button value="Upload file"/>
      <input type=button id=showfiles_button class=normal_button value="Show files" onclick="showfiles();" />
   </form>
</div>
<?php

if(isset($_FILES['image']))
   {
   echo "IN DOING THE FILES BIT" . $nl;
   $errors= array();
   $file_name = $_FILES['image']['name'];
   $file_size =$_FILES['image']['size'];
   $file_tmp =$_FILES['image']['tmp_name'];
   $file_type=$_FILES['image']['type'];
   //$file_ext=strtolower(end(explode('.',$_FILES['image']['name'])));
   $tmp = explode('.', $file_name);
   $file_ext = end($tmp);
   
   
   if(in_array($file_ext,$extensions)=== false)
      {
      $errors[]="That extension is not allowed, allowed are: mp3, mp4, zip, rar, 7z, jpg, jpeg, gif, png";
      }
   
   if($file_size > 20971520)
      { $errors[]='File size must be under 20 MB'; }
   
      
   if(empty($errors)==true)
      {
      //move_uploaded_file($file_tmp,"/home/pi/www/server_home/".$file_name);
      //echo "Moving to: " . $uploadpath . "/" . $file_name . $nl;
      move_uploaded_file($file_tmp,$uploadpath . "/" . $file_name);
      echo "Upload successful!" . $nl;
      echo "<script>location.href='index.php';</script>";
      //$dest = $path . $folder;
      //echo "Moving file to stream folder: " . $dest . $nl;
      //echo rename("/home/pi/www/server_home/".$file_name, $dest . "/" . $file_name);
      }   
   else
      {
      print_r($errors);
      }
   }
?>
</div>

<!-- <input type=button value="test" onclick="sendcmd()">-->
<div id=command style="display: none"></div>





<script language=javascript>

var picready = false;
var pause = false;
var msgslen = 0;
var msgslen_old = 0;
var fullscreen = false;
var mainInterval;
var dopop = true;

var pop;

var scaleinterval = 20;

var fpsinterval = 200;

var refreshfiles = false;
var refreshfilesinterval;

var usertmp = getCookie('userid');
if (usertmp == null)
   { usertmp ='guest'; }
document.getElementById('userid').value = usertmp;
document.getElementById('status').innerHTML = 'Userid is set';   

var picupdate = setInterval('updatepic()', fpsinterval);

var chatupdate = setInterval('updatechat()', 500);


var dopop = getCookie('dopop')
if (dopop !== '' && dopop !== null)
   {
   if (dopop == 'yes')
      {
      document.getElementById('popswitch').checked = true;
      pop = new Audio('pop.m4a');
      }
   else
      {
      document.getElementById('popswitch').checked = false;
      pop = new Audio('silence.mp3');
      }
   }


var videosize = getCookie('videosize');
if (videosize !== '' && videosize !== null)
   { document.getElementById('campic').style.width = videosize; }

   
var chatsize = getCookie('chatsize');
if (chatsize !== '' && chatsize !== null)
   {
   //alert(chatsize);
   document.getElementById('messages').style.height = chatsize; 
   var cs2 = chatsize.substr(0,chatsize.length-2)
   cs2 = parseInt(cs2) + 0;
   //alert(cs2)
   document.getElementById('userlist').style.height = cs2 + 'px';
   }


var fontsize = getCookie('fontsize');
if (fontsize !== '' && fontsize !== null)
   {
   document.getElementById('messages').style.fontSize = fontsize;
   document.getElementById('chat_input').style.fontSize = fontsize;
   document.getElementById('files_container').style.fontSize = fontsize;
   document.getElementById('userlist').style.fontSize = fontsize;
   }


var fps = getCookie('fps')
if (fps !== '' && fps !== null)
   {
   //alert('loading fps');
   dofps(fps);
   }

var donehelp = getCookie('donehelp')
if (donehelp !== '' && donehelp !== null)
   {
   // help already shown, do nothing
   }
else
   {
   popuphelp();
   }


var fov = getCookie('fov');
if (fov !== '' && fov !== null)
   {
   if (fov == 'files')
      { showfiles(); }
   else
      {
      // do nothing
      }
   }



resizemessages();

mainInterval = setInterval('doMainInterval()',3000);


window.addEventListener("resize", resizemessages);


function doMainInterval()
   {
   $('#userlist').load('userlist.php');
   var u = document.getElementById('userid');
   var userid = u.value;
   //alert(userid);
   $('#command').load( 'user.php?u='+userid );
   }



function resizemessages()
   {
   var m = document.getElementById('messages');
   var u = document.getElementById('userlist');
   var w = window.innerWidth
   m.style.width = (w-216) + 'px';
   }



function togglepop()
   {
   var p = document.getElementById('popswitch');
   if (p.checked)
      { 
      //alert('pop'); 
      dopop = 'yes';
      pop = new Audio('pop.m4a');
      setCookie('dopop', 'yes', 30);
      }
   else
      {
      //alert('no pop'); 
      dopop = 'no';
      pop = new Audio('silence.mp3');
      setCookie('dopop', 'no', 30);
      }
   }




function dofps(fps)
   {
   var f, ff
   f = document.getElementById('fpschooser')
   
   if (fps)
      {
      //alert('do this instead');
      var i
      for (i=0;i<f.options.length;i++)
         {
         //alert(f.options[i].value);
         if (f.options[i].value == fps)
            { 
            //alert('this one: '+f.options[i].value); 
            f.options[i].selected = true;
            ff = fps;
            }         
         }
      }
   else
      {
      
      //alert(f.selectedIndex);
      ff = f.options[f.selectedIndex].value;
      //alert(ff);
      }
   fpsinterval = Math.round(1000/ff);
   //alert('interval: ' + fpsinterval);
   clearInterval(picupdate);
   picupdate = setInterval('updatepic()', fpsinterval);
   statusupdate('Framerate set to '+ff+'fps');
   setCookie('fps',ff,30);
   }



function setuserid()
   {
   usertmp = document.getElementById('userid').value
   setCookie('userid',usertmp,30);
   statusupdate('UserID set to '+usertmp)
   }

   
function togglescrolling()
   {
   if (!pause)
      {
      pause = true;
      document.getElementById('scrollbutton').value = 'Resume';
      clearInterval(chatupdate);
      statusupdate('chat updates paused');
      }
   else
      {
      pause = false;
      document.getElementById('scrollbutton').value = 'Pause';
      chatupdate = setInterval('updatechat()', 500);
      statusupdate('chat updates resumed');
      }
   }
   

   
function statusupdate(u)
   {
   document.getElementById('status').innerHTML = u;
   }
   
function updatechat()
   {
   if (!pause)
      {
      var d = new Date();
      var t = d.getTime();
      var messages
      $("#messages_raw").load('chat.txt?'+t);
      var msgs = document.getElementById('messages_raw').innerHTML;
      msgs = msgs.replace(/(?:\r\n|\r|\n)/g, '<br>');
      document.getElementById('messages').innerHTML = msgs;
      document.getElementById('messages').scrollTop = 9999999999999;
      var tmp = document.getElementById('messages').innerHTML;
      msgslen = tmp.length
      //alert(dopop);
      if (msgslen > msgslen_old )
         { 
         pop.play();
         }
      
      msgslen_old = msgslen
      
      }
   }


function updatepic()
   {
   //alert(t);
   var p = document.getElementById('campic');
   if (!picready)
      { p.style.border = '1px solid red';  }
   else
      { p.style.border = '0px solid red'; }
      
   var d = new Date();
   var t = d.getTime();
   
   p.src = 'html/cam_pic.php?'+t;
      
   }


$("#chat_input").on('keyup', function (e) {
   if (e.keyCode === 13) {
      if(document.getElementById('chat_input').value !== "")
         {
         $('#status').load('chat.php?u='+encodeURIComponent(document.getElementById('userid').value)+'&m='+encodeURIComponent(document.getElementById('chat_input').value));
         document.getElementById('chat_input').value = '';
         }
    }
});


$("#userid").on('keyup', function (e) {
    if (e.keyCode === 13) {
        //$('#status').load('chat.php?u='+encodeURI(document.getElementById('userid').value)+'&m='+encodeURI(document.getElementById('chat_input').value));
        setuserid();
    }
});


function setCookie(name,value,days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days*24*60*60*1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "")  + expires + "; path=/";
}



function getCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}


function eraseCookie(name) {   
    document.cookie = name+'=; Max-Age=-99999999;';  
}


function popuphelp()
   {
   var h = document.getElementById('halp');
   if (h.style.display == 'block')
      { h.style.display = 'none'; }
   else
      { h.style.display = 'block'; }
   setCookie('donehelp',1,30);
   }


function togglefullscreen()
   {
   if (!fullscreen)
      {
      document.getElementById('messages_container').style.display = 'none';
      document.getElementById('controls_container').style.display = 'none';
      document.getElementById('status').style.display = 'none';
      //alert(window.innerWidth);
      //document.getElementById('campic').style.width = (window.innerWidth-scaleinterval) + 'px';
      document.getElementById('campic').style.width = '90%';
      fullscreen = true;
      statusupdate('switched to full screen - double click the video to exit');
      }
   else
      {
      document.getElementById('messages_container').style.display = 'block';
      document.getElementById('controls_container').style.display = 'block';
      document.getElementById('status').style.display = 'block';
      document.getElementById('campic').style.width = videosize;
      fullscreen=false;
      statusupdate('exited full screen');
      }
   }



function video_bigger()
   {
   var c = document.getElementById('campic')
   var w = c.style.width.substring(0, c.style.width.length-2);
   //alert((parseInt(w)+scaleinterval)+'px')
   c.style.width = (parseInt(w)+scaleinterval)+'px';
   setCookie('videosize',c.style.width,30);
   statusupdate('video increased to '+ c.style.width);
   }



function video_smaller()
   {
   var c = document.getElementById('campic')
   var w = c.style.width.substring(0, c.style.width.length-2);
   //alert((parseInt(w)+scaleinterval)+'px')
   c.style.width = (parseInt(w)-scaleinterval)+'px';
   setCookie('videosize',c.style.width,30);
   statusupdate('video reduced to '+ c.style.width);
   }


function video_smaller2()
   {
   var c = document.getElementById('campic')
   var w = c.style.height.substring(0, c.style.height.length-2);
   //alert((parseInt(w)+scaleinterval)+'px')
   c.style.height = (parseInt(w)-scaleinterval)+'px';
   setCookie('videosize',c.style.height,30);
   
   var f = document.getElementById('files_container');
   //f.style.height = (parseInt(w)-scaleinterval)+'px';
   f.style.height = '100px';
   statusupdate('video reduced to '+ c.style.height);
   }
   
   
function chat_bigger()
   {
   var c = document.getElementById('messages')
   var u = document.getElementById('userlist')
   var h = c.style.height.substring(0, c.style.height.length-2);
   //alert((parseInt(h)+scaleinterval)+'px')
   c.style.height = (parseInt(h)+scaleinterval)+'px';
   u.style.height = (parseInt(h)+scaleinterval+0)+'px';
   
   setCookie('chatsize',c.style.height,30);
   statusupdate('chat box increased to '+ c.style.height);
   }   

   
function chat_smaller()
   {
   var c = document.getElementById('messages')
   var u = document.getElementById('userlist')
   var h = c.style.height.substring(0, c.style.height.length-2);
   //alert((parseInt(h)+scaleinterval)+'px')
   c.style.height = (parseInt(h)-scaleinterval)+'px';
   u.style.height = (parseInt(h)-scaleinterval+0)+'px';
   setCookie('chatsize',c.style.height,30);
   statusupdate('chat box reduced to '+ c.style.height);
   }      


   
function text_bigger()
   {
   var c = document.getElementById('messages');
   var cmpfontsize = window.getComputedStyle(document.getElementById('messages')).fontSize;
   //alert(cmpfontsize);
   var fs = cmpfontsize.substring(0,cmpfontsize.length-2);
   c.style.fontSize = (parseInt(fs)+2)+'px';
   var ci = document.getElementById('chat_input');
   ci.style.fontSize = c.style.fontSize;
   
   var fi = document.getElementById('files_container');
   fi.style.fontSize = c.style.fontSize;
   
   var u = document.getElementById('userlist');
   u.style.fontSize = c.style.fontSize;
   
   //var h = document.getElementById('halp');
   //h.style.fontSize = c.style.fontSize;
   
   setCookie('fontsize',c.style.fontSize,30);
   statusupdate('font size increased to '+ c.style.fontSize);
   }  

   
function text_smaller()
   {
   var c = document.getElementById('messages');
   var cmpfontsize = window.getComputedStyle(document.getElementById('messages')).fontSize;
   //alert(cmpfontsize);
   var fs = cmpfontsize.substring(0,cmpfontsize.length-2);
   c.style.fontSize = (parseInt(fs)-2)+'px';
   var ci = document.getElementById('chat_input');
   ci.style.fontSize = c.style.fontSize;
   
   var fi = document.getElementById('files_container');
   fi.style.fontSize = c.style.fontSize;

   var u = document.getElementById('userlist');
   u.style.fontSize = c.style.fontSize;

   //var h = document.getElementById('halp');
   //h.style.fontSize = c.style.fontSize;
   
   setCookie('fontsize',c.style.fontSize,30);
   statusupdate('font size increased to '+ c.style.fontSize);
   }



function showfiles()
   {
   var f = document.getElementById('files_container');
   var v = document.getElementById('video_container');
   var b = document.getElementById('showfiles_button');
   var c = document.getElementById('campic');
   
   var elem = document.getElementById("campic");
   var theCSSprop = window.getComputedStyle(elem, null).getPropertyValue("height");
   //alert(theCSSprop);
   var fh = theCSSprop.substring(0,theCSSprop.length-2);
   //alert(fh)
   f.style.height = (fh - 40) + 'px';
   
   if (v.style.display == 'block')
      {
      v.style.display = 'none';
      f.style.display = 'block';
      $('#files_container').load('listfiles.php'); 
      refreshfilesinterval = setInterval('refreshFiles()',5000); 
      refreshfiles = true;
      b.value = 'show video';
      setCookie('fov','files',30);
      }
   else
      {
      v.style.display = 'block';
      f.style.display = 'none';
      refreshfilesinterval = null;
      refreshfiles = false;
      b.value = 'show files';
      setCookie('fov','video',30);
      }
   }


function refreshFiles()
   {
   $('#files_container').load('listfiles.php');
   }



function sendcmd()
   {
   var cmd = "an " + encodeURI('hello');
    $('#command').load('html/cmd_pipe.php?cmd=' + encodeCmd(cmd));  
      
   }


var ajax_cmd;

if(window.XMLHttpRequest) {
  ajax_cmd = new XMLHttpRequest();
}
else {
  ajax_cmd = new ActiveXObject("Microsoft.XMLHTTP");
}

function encodeCmd(s) {
   return s.replace(/&/g,"%26").replace(/#/g,"%23").replace(/\+/g,"%2B");
}

function send_cmd (cmd) {
  ajax_cmd.open("GET","cmd_pipe.php?cmd=" + encodeCmd(cmd),true);
  ajax_cmd.send();
}

   
</script>

</body>
</html>
