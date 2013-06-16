<br>
<p align="left">Who is chatting now?</p>
<hr>
<?php foreach($sessions as $session): ?>
<b><?php echo $session['reader_name'] ?></b> is chatting with <b><?php echo $session['client_name']; ?> (ID: <?php echo $session['client_id'] ?>, U: <?php echo $session['client_login'] ?>)</b><br>
<?php endforeach; ?>


