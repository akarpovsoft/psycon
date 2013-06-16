<center>
Your chat session with <?php echo $reader; ?> has ended.<br><br>

Was this chat session satisfactory & to your liking?<br>
IF NOT - PLEASE USE THE <a href="<?php echo ChatHelper::baseUrl(); ?>support/nrrRequest?session_key=<?php echo $session_key; ?>">NRR FORM</a> 
NOW TO LET THE READER KNOW ABOUT ANY PROBLEMS THAT OCCURRED<br><br>

IF YOU ARE SATISFIED WITH THIS READER'S ABILITIES AND THEIR HELPFULNESS TO YOU- PLEASE EMAIL THEM FEEDBACK!
<br><br>
Please check your email or your account message center for important account info.<br>
You can <br>
         <table cellpadding="0" cellspacing="0">
             <tr>
                 <td>
                     1.
                 </td>
                 <td>
                     <a href="<?php echo $links['chatStart']; ?>"><?php echo Yii::t('lang', 'Go_into_another_chat'); ?></a>
                 </td>
             </tr>
             <tr>
                 <td>
                     2.
                 </td>
                 <td>
                     <a href="<?php echo $links['addFunds'];?>"><?php echo Yii::t('lang', 'Fund_your_account'); ?></a>
                 </td>
             </tr>
             <tr>
                 <td>
                     3.
                 </td>
                 <td>
                     <a href="<?php echo ChatHelper::baseUrl(); ?>support/feedBack?session_key=<?php echo $session_key ?>"><?php echo Yii::t('lang', 'Leave_feedback'); ?></a>
                 </td>
             </tr>
             <tr>
                 <td>
                     4.
                 </td>
                 <td>
                     <a href="<?php echo $links['contactUs'] ?>"><?php echo Yii::t('lang', 'Send_an_email'); ?></a>
                 </td>
             </tr>
             <tr>
                 <td>
                     5.
                 </td>
                 <td>
                     <a href="<?php echo ChatHelper::baseUrl(); ?>support/nrrRequest?session_key=<?php echo $session_key; ?>"><?php echo Yii::t('lang', 'NRR_form'); ?></a>
                 </td>
             </tr>
             <tr>
                 <td>
                     6.
                 </td>
                 <td>
                     <a href="<?php echo $links['chatHistory'] ?>"><?php echo Yii::t('lang', 'View_Transcripts'); ?></a>
                 </td>
             </tr>
             <tr>
                 <td>
                     7.
                 </td>
                 <td>
                     <a href="<?php echo $links['mainMenu']; ?>"><?php echo Yii::t('lang', 'Go_to_your_account_main_page'); ?></a>
                 </td>
             </tr>
         </table>
</center>
