<script type="text/javascript">
    function userApprove(user_id){

        var url = "<?php echo Yii::app()->params['http_addr'].'blog/admin/approveUsers' ?>";

        jQuery.post(
            url,
            { 'user_id' : user_id },
            function(html){ $('#user_approve_'+user_id).html(html); },
            'html');
    }
    function userDecline(user_id){

        var url = "<?php echo Yii::app()->params['http_addr'].'blog/admin/declineUsers' ?>";

        jQuery.post(
            url,
            { 'user_id' : user_id },
            function(html){
                $('#user_approve_'+user_id).html(html);
                $('#user_decline_'+user_id).html(html);
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
                        'name' => 'First Name',
                        'value' => '$data->first_name'
                ),
                array(
                        'name' => 'Last Name',
                        'value' => '$data->last_name'
                ),
                array(
                        'name' => 'Login',
                        'value' => '$data->login'
                ),
                array(
                        'name' => 'Password',
                        'value' => '$data->password'
                ),
                array(
                        'name' => 'Register date',
                        'value' => '$data->register_date'
                ),
                array(
                        'name' => 'Approve user',
                        'htmlOptions' => array('align' => 'center'),
                        'type' => 'raw',
                        'value' => '"<div id=\"user_approve_".$data->id."\"><input type=\"button\" onClick=\"javascript:userApprove(".$data->id.")\" value=\"Approve\"></div>"'
                ),
                array(
                        'name' => 'Decline user',
                        'htmlOptions' => array('align' => 'center'),
                        'type' => 'raw',
                        'value' => '"<div id=\"user_decline_".$data->id."\"><input type=\"button\" onClick=\"javascript:userDecline(".$data->id.")\" value=\"Decline\"></div>"'
                )
        ),
        'emptyText' => 'There is no users for approve'
));

?>
</center>