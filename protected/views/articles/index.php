<center>
<?php if(isset($single)): ?>    
    <font face="Arial" size="2" color="navy">
        <?php echo $single; ?>
    </font><br><br>
    <a href="<?php echo Yii::app()->params['http_addr'] ?>reader/Psychic_<?php echo $author; ?>">Order a reading from "<?php echo $author; ?>"</a>
<?php elseif(is_array($artlist)): ?>
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
                    echo "<td width='50%'><a href='".Yii::app()->params['http_addr']."article/".$artlist[$i]['author']."_".$artlist[$i]['alias']."'>".$artlist[$i]['title']."</a> <br/> by ".$artlist[$i]['author']."</td>\r\n";
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