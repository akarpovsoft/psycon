<b>Email rates for you</b>
<table border width="100%">
    <tr>
        <td>1 Q</td>
        <td>2 Qs</td>
        <td>3 Qs</td>
        <td>4 Qs</td>
        <td>5 Qs</td>
    </tr>
    <tr>
        <td>$<?php echo $reader->q1; ?></td>
        <td>$<?php echo $reader->q2; ?></td>
        <td>$<?php echo $reader->q3; ?></td>
        <td>$<?php echo $reader->q4; ?></td>
        <td>$<?php echo $reader->q5; ?></td>
    </tr>
</table><br>
<b>Email rates for each affiliate type</b>
<table border width="100%">
    <tr>
        <td>Affiliate</td>
        <td>1 Q</td>
        <td>2 Qs</td>
        <td>3 Qs</td>
        <td>4 Qs</td>
        <td>5 Qs</td>
    </tr>
    <tr>
        <td>A-1</td>
        <td><?php echo $aff['A-1']['q1']; ?></td>
        <td><?php echo $aff['A-1']['q2']; ?></td>
        <td><?php echo $aff['A-1']['q3']; ?></td>
        <td><?php echo $aff['A-1']['q4']; ?></td>
        <td><?php echo $aff['A-1']['q5']; ?></td>
    </tr>
    <tr>
        <td>A-2</td>
        <td><?php echo $aff['A-2']['q1']; ?></td>
        <td><?php echo $aff['A-2']['q2']; ?></td>
        <td><?php echo $aff['A-2']['q3']; ?></td>
        <td><?php echo $aff['A-2']['q4']; ?></td>
        <td><?php echo $aff['A-2']['q5']; ?></td>
    </tr>
</table>
<br>
<center>
    <form action="<?php echo Yii::app()->params['http_addr']; ?>emailreadings/rates" method="POST">
        <table border>
            <tr>
                <td colspan="2" align="center"><b>Specials</b></td>
            </tr>
            <tr>
                <td>Total Price For Clients<br>You as the Reader will get 50%</td>
                <td>
                    <input type="text" size="4" name="special" value="<?php echo $reader->special ?>">
                </td>
            </tr>
            <tr>
                <td>Enable "Special"</td>
                <td>
                    <input type="checkbox" name="enable" <?php echo (!empty($reader->special)) ? 'checked' : '' ?>> Yes
                </td>
            </tr>
            <tr>
                <td colspan="2" align="center">
                    <input type="submit" name="save" value="Save">
                </td>
            </tr>
        </table>
    </form>
</center>
