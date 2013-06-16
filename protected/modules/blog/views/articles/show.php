<script type="text/javascript">
    function addComment(){
        comment = document.getElementById('comment').value;
        user = document.getElementById('user_id').value;
        art_id = document.getElementById('art_id').value;
        var url = "<?php echo Yii::app()->params['http_addr'].'blog/articles/addComment' ?>";
        $('#comments_message').html('<img src="<?php echo Yii::app()->params['http_addr']; ?>images/loading_21_21.gif">');
        jQuery.post(
            url,
            { 'comment' : comment, 'user_id' : user, 'art_id' : art_id },
            function(html){ $('#comments_message').html(html); },
            'html');
    }
</script>
<?php echo $text; ?>
<br>
<br>
<center>
    <?php
        $this->widget('zii.widgets.CListView', array(
                'dataProvider'=>$comments,
                'itemView'=>'_blog',   // refers to the partial view named '_post'
                'emptyText' => 'There is no comments',
        ));
    ?>
</center>
<br><br>
<center><div id="comments_message"></div></center>
<?php if($user != 'guest'): ?>
    <?php $this->widget('BlogComment', array('user_id' => $user['id'], 'art_id' => $id)); ?>
<?php else: ?>
<center>If you want to post some comments - please <a href="<?php echo Yii::app()->params['http_addr'] ?>blog/users/login">login here</a></center>
<?php endif;?>