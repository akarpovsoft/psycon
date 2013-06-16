<table bgcolor="#aaaaaa" border="0" cellpadding="1" cellspacing="0" width="100%">
    <tr>
        <td>
            <table bgcolor="#ffffcc" border="0" cellpadding="4" cellspacing="0" width="100%">
                <tbody>
                    <tr>
                        <td colspan="3" align="center" style="color: black;"><b>You have some errors:</b></td>
                    </tr>
                    <tr>
                        <td><img src="<?php echo Yii::app()->params['http_addr']; ?>images/iconinformation.gif" border="align=absmiddle" width="40" height="40" ></td>
                        <td><img src="<?php echo Yii::app()->params['http_addr']; ?>images/transp.gif" width="5" height="1"></td>
                        <td class="pperrorbold" width="100%" style="color: black;">
                                <?php if(is_array($msg)) {
                                    foreach($msg as $error)
                                        echo " - ".$error[0].'<br />';
                                } else {
                                    echo $msg.'<br />';
                                }
                                ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </td>
    </tr>
</table>
