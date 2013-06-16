<script type="text/javascript" src="<?php echo Yii::app()->params['http_addr']; ?>js/jquery-1.4.4-tuned.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->params['http_addr']; ?>/js/jquery.json-2.2.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->params['http_addr']; ?>/js/util.js"></script>
<script>
var runAjax = function(settings)
	{
		settings.type		= 'POST';
		settings.dataType	= 'json';
		settings.cache		= false;
		settings.timeout	= 10000;
		$.ajax(settings);
	};
function unban(user_id){
    if(window.confirm('Are you sure?')){
        parent.location.href = "<?php echo Yii::app()->params['http_addr'] ?>users/userBan?unban="+user_id;
    }
}

function ban_confirm(frm){
    if(window.confirm('Are you sure?')){
        frm.form.submit();
    }
}

function getUser(){

    runAjax({
			data    : {
				'query'	: document.getElementById('user_search').value
			},
			url     : "<?php echo Yii::app()->params['http_addr']; ?>users/getUser",
			success : function(json) {
                            document.getElementById('new_ban').style.display = 'block';
                            document.getElementById('ban_client_login').innerHTML = json.login;
                            document.getElementById('ban_client_signup').innerHTML = json.signup;
                            document.getElementById('ban_user_id').value = json.user;
			},
			error	: function(XMLHttpRequest, errorType)
			{
				try {
					handleAjaxError({place :'Connection error.', error :errorType, responseText :XMLHttpRequest.responseText});
				} catch (err) {
					handleAjaxError({place :'Connection error.'});
				}
			}
		});

}

</script>

<?php
    $this->widget('zii.widgets.grid.CGridView', array(
        'dataProvider'=>$banned,
        'selectableRows'=>3,
        'columns'=>array(
            array(
               'name' => 'Username',
               'value' => '$data->users->login'
            ),
            array(
                'name' => 'Reason',
                'value' => '$data->reason',
            ),
            array(
                'type' => 'raw',
                'htmlOptions' => array('align' => 'center'),
                'value' => '"<a href=\'javascript:unban(".$data->ban_id.")\'>Unban user"',
            ),
        ),
    ));
?>
Username, User ID or E-mail address: <input type="text" id="user_search" size="10"> <input type="button" value="Find" onClick="getUser();"><br>
<center>
<div id="new_ban" style="display: none">
    <form action="<?php echo Yii::app()->params['http_addr'] ?>users/userBan" method="POST">
        <input type="hidden" id="ban_user_id" name="ban_user_id" value="">
        <table>
            <tr>
                <td>
                    Client login:
                </td>
                <td>
                    <div id="ban_client_login"></div>
                </td>
            </tr>
            <tr>
                <td>
                    Sign up date:
                </td>
                <td>
                    <div id="ban_client_signup"></div>                    
                </td>
            </tr>
            <tr>
                <td>
                    Reason:
                </td>
                <td>
                    <textarea cols="10" rows="5" name="reason"></textarea>
                </td>
            </tr>
        </table>
        <input type="submit" name="new_ban" value="Ban" onClick="ban_confirm(this);">
    </form>
</div>
</center>
