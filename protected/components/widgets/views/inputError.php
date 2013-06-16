<table bgcolor="#aaaaaa" border="0" cellpadding="1" cellspacing="0">
    <tr>
        <td>
            <table bgcolor="#ffffcc" border="0" cellpadding="4" cellspacing="0">
                <tbody>
                    <tr>
                        <td align="center" nowrap>
                            <font color="red">
                            <?php
                            if(is_array($err)) {
                                foreach($err as $error)
                                    echo " - ".$error[0].'<br />';
                            } else {
                                echo $err.'<br />';
                            }
                            ?>
                            </font>
                        </td>
                    </tr>
                </tbody>
            </table>
        </td>
    </tr>
</table>