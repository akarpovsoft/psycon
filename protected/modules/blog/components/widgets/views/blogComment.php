<center>
    <table cellpadding="0" cellspacing="0">
        <tr>
            <td>
            User: <b><?php echo $user->first_name.' '.$user->last_name ?></b>
            <input type="hidden" name="user_id" id="user_id" value="<?php echo $user->id; ?>">
            <input type="hidden" name="art_id" id="art_id" value="<?php echo $art; ?>">
            </td>
            <td align="right">
                <?php echo date('Y-m-d H:i:s'); ?>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <textarea cols="50" rows="10" name="comment" id="comment"></textarea>
            </td>
        </tr>
        <tr>
            <td colspan="2" align="center">
                <input type="button" value="Send comment" onclick="addComment()">
            </td>
        </tr>
    </table>
</center>

