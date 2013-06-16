<?php 
//readfile(Yii::app()->basePath.'/../public_html/chat/content/index.txt'); 
echo $content;


?>
<script>

function calcHeight(re)
{
  //find the height of the internal page
  var the_height=document.getElementById(re).contentWindow.document.body.scrollHeight;

  //change the height of the iframe
  document.getElementById(re).height=the_height;
}

function reload_page()
{
	document.getElementById('rdr_list').contentWindow.location.href=document.getElementById('rdr_list').contentWindow.location;
}

var mytimer = setInterval("reload_page()",60000) ;

</script>

<iframe src="<?php echo Yii::app()->params['site_domain']; ?>/out/rl_adv.php?oo=1&dc=2" noresize="resize" id="rdr_list" onload="calcHeight('rdr_list')" width="100%" frameborder="0" scrolling="NO">
</iframe>

<table width="522" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td align="center"><img src="<?php echo Yii::app()->params['http_addr']; ?>images/geo_trust.gif"><img src="<?php echo Yii::app()->params['http_addr']; ?>images/paypal.jpg"></td>
    </tr>
</table>
