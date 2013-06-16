<?php
if (isset($_GET['do']))
{    $refUrl = 'https://www.psychic-contact.com/chat/reading_request.php';
}
else
{    $refUrl = '';
}
?>

<center>
<table border="0" width="450" id="table1" cellspacing="0" cellpadding="0">
        <tr>
                <td width="450" valign="top" align="center">
                <form name="main" action="<?php echo $http_addr;?>/chatlogin.php" method="post">
                <input name="ref" type="hidden" value="<?php echo $refUrl;?>">

                                                <div align="center">
  <center>
  <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111" width="350">
    <tr>
      <td width="50%">Username:</td>
      <td width="50%"><input style="width:80px" class="InputBoxDouble" size="7" maxlength=50 name="LoginName" value="<?php if (isset($_LoginName)) echo $_LoginName?>"></td>
    </tr>
    <tr>
      <td width="50%">Password:</td>
      <td width="50%"><input style="width:80px" class="InputBoxDouble" type="password" maxlength="50" size="7" name="LoginPassword" value="<?php if (isset($_LoginPassword)) echo $_LoginPassword?>"></td>
    </tr>
    <tr>
      <td width="100%" colspan="2"><input type="submit" value="Login"></td>
    </tr>
  </table>
  </center>
</div>

                                                <?php
                                                if ($ACCOUNT_TYPE == $ACCOUNT_CHAT){
                                                ?>
                                                <br>No Account?<br><br>
                                                <a href='<?php echo $http_addr;?>/chatsignup.php'>Signup Here!</a><br><br>
                                                <a href="<?php echo $http_addr;?>/chatlogin.php">Forgot password?</a><br><br>
                                                <a href="<?php echo $http_addr;?>/contact_us.php">Contact Us</a><br>
                                                </span>
                                                <?php
                                                }
                                                ?>
                                                </SPAN>
                                                <br><br>
                                                <a href="http://www.taroflash.com" target="_blank" class=LinkSmall>FREE Tarot Reading</a>

                                                <br>
 <br>
												<script src="flash/Scripts/AC_RunActiveContent.js" type="text/javascript"></script>
<table width="522" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><script type="text/javascript">
AC_FL_RunContent( 'codebase','http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0','width','530','height','50','src','flash/blink_text','quality','high','pluginspage','http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash','movie','flash/blink_text' ); //end AC code
</script><noscript><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="530" height="50">
      <param name="movie" value="flash/blink_text.swf" />
      <param name="quality" value="high" />
      <embed src="flash/blink_text.swf" quality="high" pluginspage="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" width="530" height="50"></embed>
    </object></noscript></td>
  </tr>
</table>
                                                </form></td></tr></table>
                                                </center>
