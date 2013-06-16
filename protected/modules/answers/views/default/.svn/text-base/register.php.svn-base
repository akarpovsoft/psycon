<h1>Register new user</h1>
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
            <td width="150"><label class="required">Login </label></td> <td><input name="login" type="text"  /></td>
        </tr>
        <tr>
            <td ><label class="required">Password</label></td> <td><input name="password" type="password"  /></td>
        </tr>
        <tr>
            <td ><label class="required">Confirm Password</label></td> <td><input name="passwordConfirm" type="password"  /></td>
        </tr>
        <tr>
            <td ><label class="required">E-mail</label></td> <td><input name="email" type="text"  /></td>
        </tr>
        <tr>
            <td ><label class="required">Date of Birth</label></td> <td>
            <?php
					echo CExHtml::selectStringMonthBox('month',$month,array('class'=>'dropdown_box'));
					echo CExHtml::selectDaysBox('day',$day,array('class'=>'dropdown_box'));
					echo CExHtml::selectYearsBox('year',$years,array('class'=>'dropdown_box'));
			?>
            </td>
        </tr>
        
        </table>
    </div>
	<div class="row submit">
		<input type="submit" value="Register" />
	</div>
</form>

</div><!-- form -->