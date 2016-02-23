<?php
session_start();
$_SESSION['ftp'] = 'ftp://'.urlencode('labolionet@atmedia.fr').':labolionet2014@ftp.atmedia.fr/'.$_GET['path'];
echo "<script>\r";
echo "alert('".$_SESSION['ftp']."');\r";
echo "</script>\r";
?>

<applet name="jumpLoaderApplet"
      code="jmaster.jumploader.app.JumpLoaderApplet.class"
      archive="i18n/messages_fr.zip,jumploader_z.jar,ftp_z.jar"
      width="600" height="400" mayscript>
   <param name="ac_fireAppletInitialized" value="true"/>
</applet>

<script language="javascript">
   function appletInitialized( applet ) {

      var xhr;
      try {  xhr = new ActiveXObject('Msxml2.XMLHTTP');   }
      catch (e)
      {
          try {   xhr = new ActiveXObject('Microsoft.XMLHTTP');    }
          catch (e2)
          {
            try {  xhr = new XMLHttpRequest();     }
            catch (e3) {  xhr = false;   }
          }
       }
      
      xhr.onreadystatechange  = function()
      {
           if(xhr.readyState  == 4)
           {
                if(xhr.status  == 200)
                   applet.getUploaderConfig().setUploadUrl(xhr.responseText);
           }
      };

      xhr.open( "GET", "ftpcon.php",  true);
      xhr.send(null);
      
   }
</script> 