<br>
<p align="left">Session Info</p>
<hr>
<center>
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td height="20" align="center" style="background-image: url(<?php echo Yii::app()->params['http_addr']; ?>images/bg_adv.gif); background-repeat: repeat-x; color: white;" >
                <b>Common session info</b>
            </td>
        </tr>
        <tr>
            <td>
                <table width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                        <td height="25">
                            Session id
                        </td>
                        <td>
                            <?php echo $info['session_id']; ?>
                        </td>
                        <td>
                            Date
                        </td>
                        <td>
                            <?php echo $info['Date'] ?>
                        </td>
                    </tr>
                    <tr>
                        <td height="25">
                            Client
                        </td>
                        <td>
                            <?php echo $client_name; ?>
                        </td>
                        <td>
                            Reader
                        </td>
                        <td>
                            <?php echo $reader_name ?>
                        </td>
                    </tr>
                    <tr>
                        <td height="25">
                            Paid time
                        </td>
                        <td>
                            <?php echo $info['Paid_time']; ?>
                        </td>
                        <td>
                            Free time
                        </td>
                        <td>
                            <?php echo $info['Free_time']; ?>
                        </td>
                    </tr>
                    <tr>
                        <td height="25">
                            BMT time
                        </td>
                        <td colspan="3" align="left">
                            <?php echo $info['bmt_time']; ?>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td height="20" align="center" style="background-image: url(<?php echo Yii::app()->params['http_addr']; ?>images/bg_adv.gif); background-repeat: repeat-x; color: white;" >
                <b>Client info</b>
            </td>
        </tr>
        <tr>
            <td>
                <table width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                        <td height="25">
                            Balance before
                        </td>
                        <td>
                            <?php echo ($info['old_balance']) ? $info['old_balance'] : "No information"; ?>
                        </td>
                        <td>
                            Balance after
                        </td>
                        <td>
                            <?php echo ($info['new_balance']) ? $info['new_balance'] : "No information"; ?>
                        </td>
                    </tr>
                    <tr>
                        <td height="25">
                            Free time before
                        </td>
                        <td>
                            <?php echo ($info['free_time_before']) ? $info['free_time_before'] : "No information"; ?>
                        </td>
                        <td>
                            Free time after
                        </td>
                        <td>
                            <?php echo $info['free_time_after']; ?>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td height="20" align="center" style="background-image: url(<?php echo Yii::app()->params['http_addr']; ?>images/bg_adv.gif); background-repeat: repeat-x; color: white;">
                <b>Browser & Useragent info</b>
            </td>
        </tr>
        <tr>
            <td>
                <table width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                        <td style="font-weight: bold;">Client browser</td>
                        <td style="font-weight: bold;">Client useragent</td>
                        <td style="font-weight: bold;">Reader browser</td>
                        <td style="font-weight: bold;">Reader useragent</td>
                    </tr>
                    <tr>
                        <td><?php echo $info['client_browser']; ?></td>
                        <td><?php echo $info['user_agent']; ?></td>
                        <td><?php echo $info['reader_browser']; ?></td>
                        <td><?php echo $info['reader_useragent']; ?></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td height="20" align="center" style="background-image: url(<?php echo Yii::app()->params['http_addr']; ?>images/bg_adv.gif); background-repeat: repeat-x; color: white;" >
                <b>Flags</b>
            </td>
        </tr>
        <tr>
            <td>
                <table width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                        <td height="25">
                            <b>NRR</b>
                        </td>
                        <td>
                            <b>ADDLST</b>
                        </td>
                        <td>
                            <b>SEr</b>
                        </td>
                        <td>
                            <b>SEc</b>
                        </td>
                        <td>
                            <b>NOTXT</b>
                        </td>
                    </tr>
                    <tr>
                        <td height="25">
                            <?php echo ($info['no_response_from_reader'] ? 'Yes' : 'No'); ?>
                        </td>
                        <td>
                            <?php echo ($info['add_lost_time'] ? 'Yes' : 'No'); ?>
                        </td>
                        <td>
                            <?php echo ($info['finish_initiator']=='reader' ? 'Yes' : 'No'); ?>
                        </td>
                        <td>
                            <?php echo ($info['finish_initiator']=='client' ? 'Yes' : 'No'); ?>
                        </td>
                        <td>
                            <?php echo ($info['client_msg_count']==0 && $info['reader_msg_count']==0 ? 'Yes' : 'No'); ?>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td height="20" align="center" style="background-image: url(<?php echo Yii::app()->params['http_addr']; ?>images/bg_adv.gif); background-repeat: repeat-x; color: white;" >
                <b>Payments deposited</b>
            </td>
        </tr>
        <tr>
            <td>
                <?php echo ($info['deposited_payments'] ? nl2br($info['deposited_payments']) : "No payments");?>
            </td>
        </tr>
        <tr>
            <td height="20" align="center" style="background-image: url(<?php echo Yii::app()->params['http_addr']; ?>images/bg_adv.gif); background-repeat: repeat-x; color: white;" >
                <b>Service info</b>
            </td>
        </tr>
        <tr>
            <td>
                <table width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                        <td height="25">
                            Duration
                        </td>
                        <td>
                            <?php echo $info['Duration'] ? $info['Duration']." sec" : "No information"; ?>
                        </td>
                        <td>
                            Chat end initiator
                        </td>
                        <td>
                            <?php echo $info['finish_initiator'];?>
                        </td>
                    </tr>
                    <tr>
                        <td height="25">
                            Client finished chat normally
                        </td>
                        <td>
                            <?php echo ($info['client_finished'] ? 'Yes' : 'No'); ?>
                        </td>
                        <td>
                            Time returned back
                        </td>
                        <td>
                            <?php echo ($info['payment']=='client' ? 'Yes' : 'No'); ?>
                        </td>
                    </tr>
                    <tr>
                        <td height="25">
                            Reader finished chat normally
                        </td>
                        <td>
                            <?php echo ($info['session_finished'] ? 'Yes' : 'No'); ?>
                        </td>
                        <td>
                            Finished by cron script
                        </td>
                        <td>
                            <?php echo ($info['fixed_by_cron'] ? 'Yes' : 'No'); ?>
                        </td>
                    </tr>
                    <tr>
                        <td height="25">
                            Reader posts count
                        </td>
                        <td>
                            <?php echo $info['reader_msg_count'];?>
                        </td>
                        <td>
                            Client posts count
                        </td>
                        <td>
                            <?php echo $info['client_msg_count'];?>
                        </td>
                    </tr>
                    <tr>
                        <td height="25">
                            Chat type 
                        </td>
                        <td>
                            <?php echo $chat_type; ?>
                        </td>
                        <td colspan="2"></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td height="20" align="center" style="background-image: url(<?php echo Yii::app()->params['http_addr']; ?>images/bg_adv.gif); background-repeat: repeat-x; color: white;" >
                <b>Add lost time</b>
            </td>
        </tr>
        <tr>
            <td>
                <form action="<?php echo Yii::app()->params['http_addr'] ?>users/addTime" method="POST">
                <input type="hidden" name="session_id" value="<?php echo $info['session_id']; ?>">
                <table width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                        <td height="25">
                            Select added PAID time
                        </td>
                        <td>
                            <select name="time">
                                <?php $j = 1; ?>
                                <?php for($i=1;$i<=$paid_time;$i+=$j): ?>
                                <?php 
                                switch($i){ 
                                    case 5:  
                                        $j = 2;
                                        break;
                                    case 7:
                                        $j = 3;
                                        break;
                                    case 10:
                                        $j = 5;
                                        break;
                                } 
                                ?>
                                <option value="<?php echo $i ?>"><?php echo $i; ?> minute(s)</option>
                                <?php endfor; ?>
                                <option value='<?php echo $all_time; ?>'>All time</option>
                            </select>
                        </td>                        
                    </tr>
                    <tr>
                        <td colspan="2">
                            <input type="submit" name="add_time" value="Add time">
                        </td>
                    </tr>
                </table>
                <hr>
                <table width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                        <td height="25">
                            (FOR NEW FREEBIE CLIENT/SESSIONS ONLY)<br>Add free time
                        </td>
                        <td>
                            <select name="free_time">
                                <?php for($i=1;$i<10;$i++): ?>                                
                                <option value="<?php echo $i ?>"><?php echo $i; ?> minute(s)</option>
                                <?php endfor; ?>                                
                            </select>
                        </td>                        
                    </tr>
                    <tr>
                        <td colspan="2">
                            <input type="submit" name="add_free_time" value="Add free time">
                        </td>
                    </tr>
                </table>
                </form>
            </td>
        </tr>
    </table>
</center>
