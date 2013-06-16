<?php
    $app_path = '';

	require_once($app_path."common/chat_header.php");
    $left_nav = array(
        array('title'=>$lang['Home'],'link'=>'/chat/index.php','class'=>'left-nav-link','visible'=>1),
        array('title'=>$lang['Login'],'link'=>'/loginhere.php','class'=>'left-nav-link','visible'=>(!session_is_registered('login_account_id'))),
		array('title'=>$lang['Start_your_reading'],'link'=>'/chatstart.php','class'=>'left-nav-link','visible'=>(session_is_registered('login_account_id'))),
		array('title'=>$lang['Our_readers'],'link'=>'/chatourreaders_guest.php?all=1','class'=>'left-nav-link','visible'=>(!session_is_registered('login_account_id'))),
		array('title'=>$lang['Select_your_reader'],'link'=>'/chatourreaders.php','class'=>'left-nav-link','visible'=>(session_is_registered('login_account_id'))),
		array('title'=>$lang['Register_here'],'link'=>'/chatsignup.php','class'=>'left-nav-link','visible'=>(!session_is_registered('login_account_id'))),
		array('title'=>$lang['Your_account'],'link'=>'/chatmain.php','class'=>'left-nav-link','visible'=>(session_is_registered('login_account_id'))),
		array('title'=>'NEW Clients - 10 FREE minutes','link'=>'/ref.php?id='.$default_affiliate_code.'&gotourl='.$HTTP_BASE_URL.'/info.php?page=faq','class'=>'left-nav-link','visible'=>(!session_is_registered('login_account_id'))),
		array('title'=>'FREEBIE F.A.Q.s','link'=>'/info.php?page=faq','class'=>'left-nav-link','visible'=>(!session_is_registered('login_account_id'))),
		array('title'=>$lang['Fund_Account'],'link'=>'/chataddfunds.php','class'=>'left-nav-link','visible'=>(session_is_registered('login_account_id'))),
		array('title'=>$lang['History'],'link'=>'/chat.php','class'=>'left-nav-link','visible'=>(session_is_registered('login_account_id'))),
		array('title'=>$lang['Profile'],'link'=>'/client_edit.php','class'=>'left-nav-link','visible'=>(session_is_registered('login_account_id'))),
		array('title'=>$lang['Customer_service'],'link'=>'/contact_us.php','class'=>'left-nav-link','visible'=>(!session_is_registered('login_account_id'))),
		array('title'=>'Contact Support','link'=>'/contact_us.php','class'=>'left-nav-link','visible'=>1),
		array('title'=>$lang['Log_out'],'link'=>'/chatlogout.php?Reason=OnRequest','class'=>'left-nav-link','visible'=>(session_is_registered('login_account_id'))),
	);
          
        
?>
	<ul class="left-nav">
<?php 	foreach ($left_nav as $item) {
			if($item['visible']) {?>
				<li class="left-nav-item"> <a href="<?php echo $item['link']; ?>" class="<?php echo $item['class']; ?>" target="main"><?php echo $item['title']; ?></a></li>
<?php		}
		}
?>
	</ul>
	
	
