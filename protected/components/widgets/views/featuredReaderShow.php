<table borderwidth="0" width="95%" border="1" bordercolor="#660099" cellpadding="0" cellspacing="0" style="margin-left: 7px;">
<tbody>
    <tr>
        <td align="center">
            <a href="<?php echo Yii::app()->params['http_addr']; ?>reader/Psychic_<?php echo $FReader->login; ?>">
            <br><?php echo $FR->loadFRImage(); ?><br><br>
            <b><?php echo $FData['name']; ?></b></a><b><br><?php echo nl2br($FData['desc']); ?>
        </td>
    </tr>
</tbody>
</table>        
          
