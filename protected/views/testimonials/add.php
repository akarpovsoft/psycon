<center>
    Add new testimonial<br><br>
<?php if(isset($error)): ?>
    <font color="red"><b><?php 
    if(is_array($error))
    {
        foreach($error as $key=>$val)
            echo $val[0].'<br>';
    }
    else
        echo $error[0]; 
    ?></b></font><br><br>
<?php endif; ?>
<form action="<?php echo Yii::app()->params['http_addr'] ?>testimonials/add" method="POST">
    <input type="hidden" name="id" value="<?php echo $testimonial->id; ?>">
    <table border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td nowrap>
                Date of testimonial (yyyy-mm-dd)
            </td>
            <td>
                <input type="text" name="date" size="25" value="">
            </td>                        
        </tr>    
        <tr>
            <td nowrap>
                From Member
            </td>
            <td>
                <input type="text" name="member" size="25" value="">
            </td>                        
        </tr>
        <tr>
            <td colspan="2">
                <br> TEXT <br>
                <textarea cols="60" rows="10" name="text"></textarea>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <input type="submit" name="add" value="Save">
            </td>
        </tr>
    </table>
</form>      
</center>

