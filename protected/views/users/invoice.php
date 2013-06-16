<br>
<p align="left"><?php echo Yii::t('lang', 'Reader_Invoice'); ?> </p>
<hr>
<center>
<table border="1" cellpadding="5" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111">
    <tr bgcolor="#336699">
        <td><font color="white" style="font-weight: bold;">Options</font></td>
        <td><font color="white" style="font-weight: bold;">Reader id</font></td>
        <td><font color="white" style="font-weight: bold;">Date submited</font></td>
        <td><font color="white" style="font-weight: bold;">Earning type</font></td>
        <td><font color="white" style="font-weight: bold;">Month</font></td>
        <td><font color="white" style="font-weight: bold;">Period</font></td>
        <td><font color="white" style="font-weight: bold;">Reader status</font></td>
        <td><font color="white" style="font-weight: bold;">Total</font></td>
        <td><font color="white" style="font-weight: bold;">Reader supposed amount</font></td>
    </tr>
<?php foreach($invoices as $inv): ?>
    <tr>
        <td nowrap>
            <a href="<?php echo $PHP_SELF.'?invoice_id='.$inv['Invoice_id'] ?>">edit</a>
        </td>
        <td nowrap>
            <?php echo $reader->name.' ('.$inv['Reader_id'].')'; ?>
        </td>
        <td nowrap>
            <?php echo $inv['Date_submited']; ?>
        </td>
        <td nowrap>
            <?php echo $inv['Earning_type']; ?>
        </td>
        <td nowrap>
            <?php echo $inv['Month']; ?>
        </td>
        <td nowrap>
            <?php echo $inv['Period']; ?>
        </td>
        <td nowrap>
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
        <td nowrap>
            <?php echo $inv['amount']; ?>
        </td>
        <td nowrap>
            <?php echo $inv['reader_supposed_amount']; ?>
        </td>
    </tr>
<?php endforeach; ?>
</table>
</center>