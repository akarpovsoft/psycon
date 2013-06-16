<center>
    <h2>ARTICLES</h2><br>
<table>
    <?php $i=0; foreach($artList as $art): ?>
        <?php if($i%2==0) echo "<tr>"; ?>
        <td align="center">
           <?php if(!empty($art->id)): ?><a href="<?php echo Yii::app()->params['http_addr'] ?>blog/articles/show?id=<?php echo $art->id; ?>"> <?php echo $art->title; ?></a><br>by <?php echo $art->author; endif;?>
        </td>
        <?php if($i%2==1) echo "</tr>"; ?>
    <?php $i++; endforeach; ?>
</table>


</center>
