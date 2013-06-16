<?php if(isset($success)): ?>
Thank You!
<?php else: ?> 
<?php if(isset($errors)): ?>
<center>
    <?php $this->widget('InputError', array('errors' => $errors)); ?>
</center>
<?php endif; ?>
<form name="Send" action="<?php echo Yii::app()->params['http_addr'] ?>support/feedBack" method="post">
<input type="hidden" name="action" value="send">
<input type="hidden" name="reader_id" value="<?php echo $reader_id; ?>">
<input type="hidden" name="client_id" value="<?php echo $client_id; ?>">
<input type="hidden" name="type" value="<?php echo $type; ?>">
<input type="hidden" name="pre" value="<?php echo $pre; ?>">
<input type="hidden" name="session_key" value="<?php echo $_GET['session_key']; ?>">
<table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111" width="100%" id="AutoNumber1">
  <tr>
    <td width="100%" colspan="2"><b>Feedback:</b></td>
  </tr>
  <tr>
    <td width="20%" valign="top">To:</td>
<?php if($type == "client"): //client about reader ?>
    <td width="50%">
    	<input name="to" type="radio" value="<?php echo $reader_id; ?>" CHECKED>	Send feedback/message to reader for your session that just ended <br>
    	<input name="to" type="radio" value="admin">		Send message/feedback to Admin									 <br>
    	<input name="to" type="radio" value="both">			Send to Both
    </td>
<?php elseif($type == "reader"): //reader about client ?>
    <td width="50%">
    	<input name="to" type="radio" value="<?php echo $client_id; ?>" CHECKED>	Send feedback/message to client for your session that just ended <br>
    	<input name="to" type="radio" value="admin">		Send message/feedback to Admin									 <br>
    	<input name="to" type="radio" value="both">			Send to Both													 <br>
    </td>
<?php else: ?>
    <td width="50%">
        <select size="1" name="to">
            <option value="Admin">Admin</option>
        </select>
    </td>
<?php endif; ?>
  </tr>
  <tr>
    <td width="20%">UserName:</td>
    <td width="50%"><b><?php echo $user_name; ?></b>
        <input type="hidden" maxlength="50" name="from_name" value="<?php echo $from_name; ?>">
    </td>
  </tr>
  <tr>
    <td width="20%">Subject:</td>
    <td width="50%"><input class="InputBoxFront" size="40" maxlength="50" name="subject"></td>
  </tr>
  <tr>
    <td width="20%">Body:</td>
    <td width="50%"><textarea rows="5" name="text" cols="34"></textarea></td>
  </tr>
</table>
<input type="submit" name="send" value="Submit">
<br>
<br>
NOTE! If you would like to send a message or feedback to a different Reader - please use the message center found on your account page
</form>
<?php endif; ?>