<h2><a href="<?php echo Yii::app()->params['http_addr'].answersFunc::Adres(); ?>/default/questions" > Back</a></h2>

<h1> <?php echo CHtml::encode($model->title); ?> 
</h1>
<p style="color: #696969"> Posted at <?php echo date('F j, Y \a\t h:i a',$model->createtime); ?> </p>
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