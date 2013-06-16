<h1>Login</h1>

<h3><a href="<?php echo Yii::app()->params['http_addr'].answersFunc::Adres();?>/default/register">Register new user</a></h3>
<div class="form">


<?php if ($error) {
    echo '<div class="errorSummary"><p>Please fix the following input errors:</p><ul>';
        foreach ($error as $er) {
            echo '<li>'.$er.'</li>';
        }
    echo '</ul></div>';
} ?>
<form action="#" method="post" >
	<div class="row">
        <table>
        <tr>
            <td width="80"><label class="required">Username </label></td> <td><input name="login" type="text" value="" /></td>
        </tr>
        <tr>
            <td width="80"><label class="required">Password</label></td> <td><input name="password" type="password" value="" /></td>
        </tr>
        </table>
    </div>
	<div class="row submit">
		<input type="submit" value="Login" />
	</div>
</form>

</div><!-- form -->
