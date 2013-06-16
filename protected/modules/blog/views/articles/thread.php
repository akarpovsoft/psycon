<script type="text/javascript" src="/advanced/assets/f1b037f5/jquery.js"></script>
<script type="text/javascript" src="http://code.jquery.com/jquery-1.4.2.js"></script>

<script type="text/javascript" src="/advanced/js/jquery.ajax_queue.js"></script>
<script type="text/javascript" src="/advanced/js/jquery.json-2.2.js"></script>
<script type="text/javascript">
    function addComment(){
        comment = document.getElementById('comment').value;
        user = document.getElementById('user_id').value;
        art_id = document.getElementById('art_id').value;
        var url = "<?php echo Yii::app()->params['http_addr'].'blog/articles/addComment' ?>";

        jQuery.post(
            url,
            { 'comment' : comment, 'user_id' : user, 'art_id' : art_id },
            function(html){ $('#comments_message').html(html); },
            'html');
    }
</script>
<?php echo $artText; ?>
<br>
<br>
<center>
<?php foreach($archive as $ar): ?>
<a href="<?php echo Yii::app()->params['http_addr'] ?>blog/articles/show?id=<?php echo $ar->article_id ?>"><?php echo $ar->blog_articles->title.' ('.$ar->date.')'; ?></a><br>
<?php endforeach; ?>
<br><br>
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