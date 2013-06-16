<h2><a href="<?php echo Yii::app()->params['http_addr'].answersFunc::Adres().'?'.answersFunc::addSessionToUrl(); ?>" > Back</a></h2>

<h1> <?php echo CHtml::encode($model->title); ?> 
<?php if($model->pub) { echo ' * ';} ?>
</h1>
<p style="color: #696969"> Posted by <?php echo answersFunc::userLogin($model->author_id); ?>
 at <?php echo date('F j, Y \a\t h:i a',$model->createtime); ?> </p>
<p> <?php echo $model->content; ?> </p>


<?php
if ($answers) {
    echo '<h3>Answers</h3>';
    $color = 0;
    foreach ($answers as $answer) {
        if ($color % 2) {echo '<div style="background-color: #FF99FF; padding: 5px;" >';} else {echo '<div style="background-color: #FFCCFF; padding: 5px;" >';}
        $color++;
        echo '<p> Answer by '.answersFunc::readerLogin($answer->author_id).' at '.date('F j, Y \a\t h:i a',$answer->createtime).
        '</p><p> '.$answer->content.' </p> </div>';
    }
} else { echo '<h3>No answer</h3>'; }

if ($canAnswer)  {
?>
<div style="padding: 5px;"></div>
Create answer : <br/><br />
<form action="#" method="post">
<?php 
if ($model->pub) {
    echo '<input type="checkbox" name="pub" checked="checked" value="1"/> Show the answer to other users?';
} else
?>
<br />
<textarea rows="10" cols="70" name="content"></textarea>
<br /><input type="submit" value="Send" />
</form>
 <?php } ?>
 
 