<center>
<?php if(isset($single)): ?>
<?php echo $single; ?>
<?php else: ?>
<table align="center">
    <tr >
        <td></td>
    </tr>
    <tr align="center">
        <td align="center">
            <font size='5'>Articles</font>
            <br /><br />
            <table width='100%' cellspacing='20'>
            <?php for($i=0; $i<count($artlist); $i++) {
                    if($i%2==0)
                        echo "<tr>";
                    echo "<td width='50%'><a href='".Yii::app()->params['http_addr']."site/articles?a=".$artlist[$i]['link']."'>".$artlist[$i]['title']."</a> <br/> by ".$artlist[$i]['author']."</td>\r\n";
                    if($i%2==1)
			echo "</tr>";
                  }
                  if($i%2==1)
                        echo "</tr>";
            ?>
            </table>
        </td>
    </tr>
</table>
<?php endif; ?>
</center>