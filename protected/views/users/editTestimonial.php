<script language="JavaScript">
    tinyMCE.init({
        mode:"textareas",
        theme:"simple"
    });
</script>
<center>
    Testimonial edit<br><br>
<form action="<?php echo Yii::app()->params['http_addr'] ?>users/editTestimonial" method="POST">
    <input type="hidden" name="id" value="<?php echo $testimonial->id; ?>">
    <table border="0" cellpadding="0" cellspacing="0">
        <tr>
            <th>
                Testimonial date (yyyy-mm-dd)
            </th>
            <th>
                Text
            </th>
        </tr>
        <tr>
            <td valign="top">
                <input type="text" name="date" size="25" value="<?php echo $testimonial->tm_date; ?>">
            </td>            
            <td>
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
<br><br>
<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>$data,
    'columns' => array(
        array(
            'name' => 'Save date',//Yii::t('lang', 'Date'),
            'value' => '$data->ts'
        ),
        array(
            'name' => 'Testimonial Date',//Yii::t('lang', 'Amount'),
            'value' => '$data->tm_date'
        ),
        array(
            'name' => 'Text',//Yii::t('lang', 'Currency'),
            'value' => '$data->tm_text'
        ), 
        array(
            'template' => '{update} {delete}',
            'buttons' => array(
                'update' => array(
                    'url' => 'Yii::app()->params["http_addr"]."users/editTestimonial?id=".$data->id'
                ),
                'delete' => array(
                    'url' => 'Yii::app()->params["http_addr"]."users/delTestimonial?id=".$data->id'
                ),
            ),
            'class'=>'CButtonColumn',
        )
    ),
));
?>       
</center>
