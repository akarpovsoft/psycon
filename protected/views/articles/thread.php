<center>
<?php echo $text; ?>
<br><br>
<?php if(is_array($archive)) foreach($archive as $article): ?>
<a href="<?php echo Yii::app()->params['http_addr']; ?>article/Art_<?php echo urlencode($article["article"]) ?>">
    <?php echo $article["title"]; ?> ( <?php echo date("m/d/Y", (int)$article["time"]); ?> )
</a><br>
<?php endforeach; ?>
</center>