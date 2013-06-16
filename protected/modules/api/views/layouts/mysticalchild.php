<html>
    <head></head>
    <body style="background: url('<?php echo Yii::app()->params['http_addr']; ?>images/page-mc.jpg') repeat-x scroll center top #002342; margin: 0 auto;">
        <center>
        <table width="800" callpadding="0" cellspacing="0">
            <tr>
                <td align="center" style="background: url('<?php echo Yii::app()->params['http_addr']; ?>images/header-mc.jpg') repeat-x scroll center top #002342">
                    <img src="<?php echo Yii::app()->params['http_addr']; ?>images/mystical-child-logo.png">
                </td>
            </tr>
            <tr>
                <td style="color: white; font-size: 12px;">
                    <?php echo $content; ?>
                </td>
            </tr>
        </table>        
        </center>
    </body>
</html>
