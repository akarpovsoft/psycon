<?php
$typestr = GetTypeStr($operator['type']);

if (empty($typestr)) {
	$typestr = $lang['Status_Basic'];
}

?>
<table cellspacing=0 cellpadding=0 border=0 width="100%">
       <tr>
       <td nowrap align=left>
       <span class=TextMedium>
       <b><?php echo $lang['Name'];?>:</b> <?=htmlarrayvalue($operator,'name')?><br>
       <b><?php echo $lang['Email'];?>:</b> <a class=LinkMedium href="mailto:<?=htmlarrayvalue($operator,'emailaddress')?>"><?=htmlarrayvalue($operator,'emailaddress')?></a><br>
       </span>
       <br>
       <table cellspacing=0 cellpadding=0 border=0>
        <tr>
        	<td nowrap><span class=TextMedium><b><?php echo $lang['Status'];?>:</b> <?php echo $typestr;?> </td>
        	<td width=15 nowrap></td>
        	<td align="right"><?php if (Strcasecmp($operator['type'],'reader')==0) DrawStatus("$login_account_id","$login_operator_id");?></span></td>
        	</tr>
       </table>
       </td>
       </tr>
</table>
