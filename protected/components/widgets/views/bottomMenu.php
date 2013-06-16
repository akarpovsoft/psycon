<?php foreach($links as $link): ?>
<td align="center">
    <a href="<?php echo $link['link'] ?>" 
       class="<?php echo $link['class']?>"
       <?php echo ($link['link'] == Yii::app()->params['ssl_addr'].'site/login') ? 'title="Secure Login"' : ''; ?>
       >
       <? echo $link['title'] ?>
    </a>&nbsp;&nbsp;&nbsp;</td>
<?php endforeach; ?>
