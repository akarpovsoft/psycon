<?php
	$footer_nav = array(
		array('title'=>$lang['Register_here'],'link'=>'register.php','class'=>'redtxt_11_und','visible'=>1),
		array('title'=>$lang['Customer_service'],'link'=>'customer_service.php','class'=>'redtxt_11_und','visible'=>1),
		array('title'=>$lang['Affiliate_program'],'link'=>'affiliateadd.php','class'=>'redtxt_11_und','visible'=>1),
		array('title'=>$lang['Employment'],'link'=>'employment.php','class'=>'redtxt_11_und','visible'=>1),
		array('title'=>$lang['Disclaimer'],'link'=>'disclaimer.php','class'=>'redtxt_11_und','visible'=>1),
		array('title'=>$lang['Contact_us'],'link'=>'customer_service.php','class'=>'redtxt_11_und','visible'=>1),
		array('title'=>$lang['Privacy_policy'],'link'=>'privacy_policy.php','class'=>'redtxt_11_und','visible'=>1),
	);
	
	if(!session_is_registered('login_account_id')) { // if guest
		$footer_nav[] = array('title'=>$lang['Login'],'link'=>'loginhere.php','class'=>'redtxt_11_und','visible'=>1);
	}else {
        $footer_nav[] = array('title'=>$lang['Log_out'],'link'=>'chatlogout.php?Reason=OnRequest','class'=>'redtxt_11_und','visible'=>1);
	}	
?>

   
<div class="footer">
	<ul class="footer-nav">
<?php
	$count = 0;
	foreach ($footer_nav as $item) {
		$count++;
?>
		<li style="margin-left: 10px;"><a href="<?php echo $item['link']; ?>" class="<?php echo $item['class']; ?>"><?php echo $item['title']; ?></a></li>
<?php
		
	} 
?>
	</ul>
	<div id="copyright">
		<?php echo $lang['Copyright'];?> 
	</div>
</div>


</body>
</html>
