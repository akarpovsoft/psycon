<br>
<p align="left"><?php echo Yii::t('lang', 'Reader_Invoice'); ?> </p>
<hr>
<center>
<form action="<?php echo Yii::app()->params['http_addr']; ?>users/invoice" method="POST">
<table border="1" cellpadding="5" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111">
    <tr bgcolor="#336699">
        <td><font color="white" style="font-weight: bold;">ID Invoice</font></td>
        <td><font color="white" style="font-weight: bold;">Reader id</font></td>
        <td><font color="white" style="font-weight: bold;">Date submited</font></td>
        <td><font color="white" style="font-weight: bold;">Month</font></td>
        <td><font color="white" style="font-weight: bold;">Period</font></td>
        <td><font color="white" style="font-weight: bold;">Total</font></td>
        <td><font color="white" style="font-weight: bold;">Reader status</font></td>
        <td><font color="white" style="font-weight: bold;">Reader supposed amount</font></td>
    </tr>
    <?php foreach($invoices as $inv): ?>
    <tr>
        <td>
            <?php echo $inv['Invoice_id']; ?>
            <input type="hidden" name="id[]" value="<?php echo $inv['Invoice_id']; ?>">
        </td>
        <td>
            <?php echo $reader->name.' ('.$inv['Reader_id'].')'; ?>
        </td>
        <td>
            <?php echo $inv['Date_submited']; ?>
        </td>
        <td>
            <?php echo $inv['Month']; ?>
        </td>
        <td>
            <?php
                switch($inv['Period']){
                    case 1:
                        echo '1-15';
                        break;
                    case 2:
                        echo '16-31';
                        break;
                }
            ?>
        </td>
        <td>
            <?php echo $inv['Total']; ?>
        </td>
        <td>
            <?php
                switch($inv['Reader_status']){
                    case 0:
                        echo 'Pending';
                        break;
                    case 1:
                        echo 'Accepted';
                        break;
                    case 2:
                        echo 'Declined';
                        break;
                }
            ?>
        </td>
        <td>
            <input type="text" name="reader_supposed_amount[<?php echo $inv['Invoice_id']; ?>]" value="<?php echo $inv['reader_supposed_amount']; ?>">
        </td>
    </tr>
    <?php endforeach; ?>
    <tr>
        <td colspan="8" align="right">
        <?php
        $this->widget('CLinkPager', array(
            'pages' => $pages,
        ));
        ?>
        </td>
    </tr>
</table>
    <p align="left">
        <input type="submit" name="Accept" value="Submit Invoice"> <input type="submit" name="Decline" value="Decline">
    </p>
</form>
</center>