<h1>Bonus clients</h1>
<h2><a href="<?php echo Yii::app()->params['http_addr'].GoFishFunc::Adres().'/manage/index';?>" > Back</a></h2>
<form action="<?php echo Yii::app()->params['http_addr'].GoFishFunc::Adres(); ?>/manage/bonusClients" method="post">
    Bonus clients counter: <input name="bonus_cnt" size="5" type="text" value="<?php echo $bonus ?>"> <input type="submit" value="Save">
</form>
<?php
$this->widget('zii.widgets.grid.CGridView', array(
        'dataProvider'=>$clients,
        'columns'=>array(
            array(
               'name' => 'UserName',
               'value' => '$data->fname." ".$data->lname'
            ),
            array(
                'name' => 'Register date',
                'value' => '$data->ts',
            ),
            array(
                'name' => 'Date of Birth',
                'value' => '$data->DOB',
            ),
            array(
                'name' => 'Email',
                'value' => '$data->email',
            ),
            array(
                'name' => 'Bonus client code',
                'value' => '$data->code',
            ),
//            array(
//                'class' => 'CButtonColumn',
//                'template' => '{delete}'
//            )
        ),
    ));

?>
