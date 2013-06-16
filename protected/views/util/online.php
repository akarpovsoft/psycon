<h1>Online reader sessions</h1>
<form method="post">
Date : <? echo CHtml::dropDownList("startDate", $startDate, $dates); ?> &nbsp; &nbsp; 
Reader: <? echo CHtml::dropDownList("readerId", $readerId, $readers); ?> &nbsp; &nbsp; 
<input type="submit" value="Online time"/> &nbsp; &nbsp; <a href="/advanced/util/clearonline">Remove old</a>
</form>
<?php
if($list)
$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>$list,
    'columns' => array(
        array( 'name' => 'login_time', 'value' => "date('h:i A', strtotime(\$data->login_time))"),
        array( 'name' => 'logout_time', 'value' => "date('h:i A', strtotime(\$data->logout_time))"),
    ),
));
