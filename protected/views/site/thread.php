<center>
<?php echo $text; ?>
<br><br>
<?php foreach($archive as $article): ?>
<a href="<?php echo Yii::app()->params['http_addr']; ?>site/articles?a=<?php echo urlencode($article["article"]) ?>">
    <?php echo $article["title"]; ?> ( <?php echo date("m/d/Y", (int)$article["time"]); ?> )
</a><br>
<?php endforeach; ?>
</center>