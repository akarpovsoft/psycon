<center>
    	 <?php echo Yii::t('lang', 'The_chat_session_is_ended'); ?>.<br>
         <?php echo Yii::t('lang', 'You_can'); ?>
         <table cellpadding="0" cellspacing="0">
             <tr>
                 <td>
                     1.
                 </td>
                 <td>
                     <a href="<?php echo Yii::app()->params['http_addr'] ?>chat/chatstart"><?php echo Yii::t('lang', 'Go_into_another_chat'); ?></a>
                 </td>
             </tr>
             <tr>
                 <td>
                     2.
                 </td>
                 <td>
                     <a href="<?php echo Yii::app()->params['ssl_addr'] ?>site/chataddfunds"><?php echo Yii::t('lang', 'Fund_your_account'); ?></a>
                 </td>
             </tr>
             <tr>
                 <td>
                     3.
                 </td>
                 <td>
                     <a href="<?php echo Yii::app()->params['http_addr'] ?>support/feedBack?session_key=<?php echo $session_key ?>"><?php echo Yii::t('lang', 'Leave_feedback'); ?></a>
                 </td>
             </tr>
             <tr>
                 <td>
                     4.
                 </td>
                 <td>
                     <a href="<?php echo Yii::app()->params['http_addr'] ?>site/contact"><?php echo Yii::t('lang', 'Send_an_email'); ?></a>
                 </td>
             </tr>
             <tr>
                 <td>
                     5.
                 </td>
                 <td>
                     <a href="<?php echo Yii::app()->params['http_addr'] ?>support/nrrRequest?session_key=<?php echo $session_key; ?>"><?php echo Yii::t('lang', 'NRR_form'); ?></a>
                 </td>
             </tr>
             <tr>
                 <td>
                     6.
                 </td>
                 <td>
                     <a href="<?php echo Yii::app()->params['http_addr'] ?>users/clientchathistory"><?php echo Yii::t('lang', 'View_Transcripts'); ?></a>
                 </td>
             </tr>
             <tr>
                 <td>
                     7.
                 </td>
                 <td>
                     <a href="<?php echo Yii::app()->params['http_addr'] ?>users/mainmenu"><?php echo Yii::t('lang', 'Go_to_your_account_main_page'); ?></a>
                 </td>
             </tr>
         </table>
</center>
