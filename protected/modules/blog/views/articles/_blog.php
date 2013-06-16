<table width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <td></td>
        <td>
            <hr width="100%" size="2">
        </td>
    </tr>
    <tr>
        <td width="47">
            <img src="<?php echo Yii::app()->params['http_addr'].'images/pencil.png'; ?>" vspace="0" hspace="0">
        </td>
        <td bgcolor="#FFE4E1">
            <b><?php echo $data->blog_users->first_name.' '.$data->blog_users->last_name; ?></b> wrote<br>
            <?php echo $data->date; ?>
        </td>
    </tr>
    <tr>
        <td></td>
        <td>
            <hr width="100%" size="1">
        </td>
    </tr>
    <tr>
        <td></td>
        <td>
            <?php echo $data->text; ?>
        </td>
    </tr>
    <tr>
        <td></td>
        <td>
            <hr width="100%" size="1">
        </td>
    </tr>
</table>
