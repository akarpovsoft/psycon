<center>
    Testimonial edit<br><br>
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
<form action="<?php echo Yii::app()->params['http_addr'] ?>testimonials/edit" method="POST">
    <input type="hidden" name="id" value="<?php echo $testimonial->id; ?>">
    <table border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td>
                Date of testimonial (yyyy-mm-dd)
            </td>
            <td>
                <input type="text" name="date" size="25" value="<?php echo $testimonial->tm_date; ?>">
            </td>                        
        </tr>        
        <tr>
            <td align="center" colspan="2">
                <br>TEXT<br>
                <textarea cols="60" rows="10" name="text"><?php echo $testimonial->tm_text; ?></textarea>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <input type="submit" name="edit" value="Save">
            </td>
        </tr>
    </table>
</form>      
</center>
