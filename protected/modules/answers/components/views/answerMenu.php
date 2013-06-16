<?php
if (($type == 'reader') || ($type == 'Administrator'))
{
?>
 View: 
<a href="<?php echo Yii::app()->params['http_addr'] . answersFunc::Adres();?>?view=active">Active</a> |
<a href="<?php echo Yii::app()->params['http_addr'] . answersFunc::Adres();?>?view=arhive">Arhive</a> |
<a href="<?php echo Yii::app()->params['http_addr'] . answersFunc::Adres();?>?view=all">All</a> 
<br />
<?php

}
if ($type == 'client')
{
    if ((count(SiteQuestion::model()->findAllByAttributes(array('author_id' =>
        answersFunc::userId())))) < Yii::app()->params['num_question'])
    {
        echo '<h2><a href="' . Yii::app()->params['http_addr'] . answersFunc::Adres() .
            '/default/create?' . answersFunc::addSessionToUrl() .
            '">Create question</a></h2>';
    }
    echo '<h3><a href="' . Yii::app()->params['http_addr'] . answersFunc::Adres() .
        '/default/logout?' . answersFunc::addSessionToUrl() . '">Logout</a></h3>';

}

?>