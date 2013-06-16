<script type="text/javascript">
function calcHeight(re)
{
  //find the height of the internal page
  var the_height=document.getElementById(re).contentWindow.document.body.scrollHeight;

  //change the height of the iframe
  document.getElementById(re).height=the_height;
}
function go_submit(frm)
{
	if(frm.selcat.value=='-1')
		frm.cat.value=frm.keyword.value;
	else
		frm.cat.value=frm.selcat.value;
	return true;
}
</script>
<iframe width="100%" height="149" frameborder="0" scrolling="NO" onload="calcHeight('rdr_list')" id="rdr_list" name="rdr_list" noresize="resize" src="<?php echo Yii::app()->params['site_domain']; ?>/out/rl_adv.php?dc=1&amp;&amp;oo=1">
</iframe>
