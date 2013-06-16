<script type="text/javascript">
    function commentApprove(comment_id){

        var url = "<?php echo Yii::app()->params['http_addr'].'blog/admin/approveComments' ?>";

        jQuery.post(
            url,
            { 'comment_id' : comment_id },
            function(html){ $('#comment_approve_'+comment_id).html(html); },
            'html');
    }

    function commentDecline(comment_id){

        var url = "<?php echo Yii::app()->params['http_addr'].'blog/admin/declineComments' ?>";

        jQuery.post(
            url,
            { 'comment_id' : comment_id },
            function(html){
                $('#comment_approve_'+comment_id).html(html);
                $('#comment_decline_'+comment_id).html(html);
            },
            'html');
    }
</script>
<center>
    <h2>APPROVE USERS</h2>
<?php
$this->widget('zii.widgets.grid.CGridView', array(
        'dataProvider'=>$data,
        'columns' => array(
                array(
                        'name' => 'User',
                        'value' => '$data->blog_users->login'
                ),
                array(
                        'name' => 'Article',
                        'value' => '$data->article_id'
                ),
                array(
                        'name' => 'Date',
                        'value' => '$data->date'
                ),
                array(
                        'name' => 'Comment',
                        'value' => '$data->text'
                ),
                array(
                        'name' => 'Approve comment',
                        'htmlOptions' => array('align' => 'center'),
                        'type' => 'raw',
                        'value' => '"<div id=\"comment_approve_".$data->id."\"><input type=\"button\" onClick=\"javascript:commentApprove(".$data->id.")\" value=\"Approve\"></div>"'
                ),
                array(
                        'name' => 'Decline comment',
                        'htmlOptions' => array('align' => 'center'),
                        'type' => 'raw',
                        'value' => '"<div id=\"comment_decline_".$data->id."\"><input type=\"button\" onClick=\"javascript:commentDecline(".$data->id.")\" value=\"Decline\"></div>"'
                )
        ),
        'emptyText' => 'There is no users for approve'
));

?>
</center>
