<center><h2>Pending Emailreadings</h2></center>
<form action="<?php echo Yii::app()->params['http_addr'] ?>emailreadings/pending" method="POST">
    <input type="text" size="8" name="query" value="<?php echo $_POST['query']; ?>"> <input type="submit" name="search" value="Search"> <br>
</form>
<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>$data,
    'columns' => array(
        array(
            'name' => 'First Name',
            'value' => '$data->first_name'
        ),
        array(
            'name' => 'State/Province',
            'value' => '$data->u_contact_state_or_province'
        ),
        array(
            'name' => 'Country',
            'value' => '$data->v_contact_country'
        ),
        array(
            'name' => 'Sex',
            'value' => '$data->c_sex',
        ),
        array(
            'name' => 'DOB Month',
            'value' => '$data->d_date_of_birth_Month1'
        ),
        array(
            'name' => 'DOB Date',
            'value' => '$data->e_date_of_birth_Date2'
        ),
        array(
            'name' => 'DOB Year',
            'value' => '$data->f_date_of_birth_YR3'
        ),
        array(
            'name' => 'Topic',
            'value' => '$data->l_topic'
        ),
        array(
            'name' => 'Date',
            'value' => '$data->date'
        ),
        array(
            'name' => 'View',
            'type' => 'raw',
            'value' => '"<a href=".Yii::app()->params["http_addr"]."emailreadings/show?id=".$data->qs_id.">View</a>"',
        )
    ),
));
?>
