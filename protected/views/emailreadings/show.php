<center><h2>Email Reading</h2></center>
<?php echo CHtml::beginForm(); ?>
<table width="100%">
    <tr bgcolor="#336699">
        <td colspan="2" width="416" align="center">
            <font color="#FFFFFF"><b>Email Reading</b></font>
        </td>
    </tr>
    <tr>
            <td bgcolor="#F5F5F5"><b><font size="-1">ID</font></b><iput type="hidden" name="quest_id" value="<?php echo $reading->qs_id; ?>"></td>
            <td bgcolor="#FFFFFF"><font><?php echo $reading->qs_id; ?></font>&nbsp;</td>
    </tr>
    <tr>

            <td bgcolor="#F5F5F5"><b><font>First name</font>&nbsp;</b></td>
            <td bgcolor="#FFFFFF"><font><?php echo $reading->first_name; ?></font>&nbsp;</td>
    </tr>
    <tr>
            <td bgcolor="#F5F5F5"><b><font size="-1">City</font>&nbsp;</b></td>
            <td bgcolor="#FFFFFF"><font><?php echo $reading->t_contact_city; ?></font>&nbsp;</td>
    </tr>
    <tr>
            <td bgcolor="#F5F5F5"><b><font size="-1">State or Province</font>&nbsp;</b></td>
            <td bgcolor="#FFFFFF"><font><?php echo $reading->u_contact_state_or_province; ?></font></td>
    </tr>
    <tr>
            <td bgcolor="#F5F5F5"><b><font size="-1">Country</font>&nbsp;</b></td>
            <td bgcolor="#FFFFFF"><font><?php echo $reading->v_contact_country; ?></font></td>
    </tr>

    <tr>
            <td bgcolor="#F5F5F5"><b><font size="-1">Method of Reading</font>&nbsp;</b></td>
            <td bgcolor="#FFFFFF"><font><?php echo $reading->za_reading_deliv; ?></font>&nbsp;</td>
    </tr>
    <tr>
            <td bgcolor="#F5F5F5"><b><font size="-1">Sex</font>&nbsp;</b></td>
            <td bgcolor="#FFFFFF"><font><?php echo $reading->c_sex; ?></font>&nbsp;</td>

    </tr>
    <tr>
            <td bgcolor="#F5F5F5"><b><font>DOB Month</font>&nbsp;</b></td>
            <td bgcolor="#FFFFFF"><font><?php echo $reading->d_date_of_birth_Month1; ?></font>&nbsp;</td>
    </tr>
    <tr>
            <td bgcolor="#F5F5F5"><b><font size="-1">DOB Date</font>&nbsp;</b></td>

            <td bgcolor="#FFFFFF"><font size="-1"><?php echo $reading->e_date_of_birth_Date2; ?></font>&nbsp;</td>
    </tr>
    <tr>
            <td bgcolor="#F5F5F5"><b><font size="-1">DOB Year</font>&nbsp;</b></td>
            <td bgcolor="#FFFFFF"><font size="-1"><?php echo $reading->f_date_of_birth_YR3; ?></font>&nbsp;</td>
    </tr>
    <tr>

            <td bgcolor="#F5F5F5"><b><font size="-1">Birth time</font>&nbsp;</b></td>
            <td bgcolor="#FFFFFF"><font size="-1"><?php echo $reading->g_birth_time; ?>  <?php echo $reading->h_time_of; ?> </font>&nbsp;</td>
    </tr>
    <tr>
            <td bgcolor="#F5F5F5"><b><font size="-1">Place of birth</font>&nbsp;</b></td>
            <td bgcolor="#FFFFFF"><font size="-1"><?php echo $reading->i_place_of_birth; ?></font>&nbsp;</td>
    </tr>

    <tr>
            <td bgcolor="#F5F5F5"><b><font size="-1">Numerology name</font>&nbsp;</b></td>
            <td bgcolor="#FFFFFF"><font size="-1"><?php echo $reading->j_numerology_name; ?></font></td>
    </tr>
    <tr>
            <td bgcolor="#F5F5F5"><b><font size="-1">Numerology date</font>&nbsp;</b></td>
            <td bgcolor="#FFFFFF"><font size="-1"><?php echo $reading->k_numerology_date; ?></font>&nbsp;</td>
    </tr>

    <tr>
            <td bgcolor="#F5F5F5"><b><font size="-1">Topic</font>&nbsp;</b></td>
            <td bgcolor="#FFFFFF"><font size="-1"><?php echo $reading->l_topic; ?></font>&nbsp;</td>
    </tr>
    <tr>
            <td bgcolor="#F5F5F5"><b><font size="-1">Reading type</font>&nbsp;</b></td>
            <td bgcolor="#FFFFFF"><font size="-1">$ <?php echo $reading->b_reading_type; ?></font>&nbsp;</td>

    </tr>
    <tr>
            <td bgcolor="#F5F5F5"><b><font size="-1">Special instructions</font>&nbsp;</b></td>
            <td bgcolor="#FFFFFF"><font size="-1"><?php echo $reading->m_special_instructions; ?></font>&nbsp;</td>
    </tr>
    <tr>
            <td bgcolor="#F5F5F5"><b><font size="-1">Additional info</font>&nbsp;</b></td>
            <td bgcolor="#FFFFFF"><font size="-1"><?php echo $reading->n_additional_info; ?></font>&nbsp;</td>

    </tr>
    <tr>
            <td bgcolor="#F5F5F5"><b><font size="-1">Date</font>&nbsp;</b></td>
            <td bgcolor="#FFFFFF"><font size="-1"><?php $time = strtotime($reading->date); echo date('M d, Y h:i', $reading->date); ?></font>&nbsp;</td>
    </tr>
    <tr>
            <td bgcolor="#F5F5F5"><b><font size="-1">Order Number</font>&nbsp;</b></td>

            <td bgcolor="#FFFFFF"><font size="-1"><?php echo $reading->transaction; ?></font>&nbsp;</td>
    </tr>
    <tr>
            <td colspan="2" align="center" bgcolor="#F5F5F5"><b><font size="-1">Reading</font>&nbsp;</b></td>
    </tr>
    <tr>
            <td colspan="2" bgcolor="#FFFFFF"><font size="-1"><?php echo $reading->reading; ?></font></td>
    </tr>
    <tr>
            <td bgcolor="#F5F5F5"><b><font size="-1">Reader's fee</font>&nbsp;</b></td>
            <td bgcolor="#FFFFFF">
                <font size="-1">$ <?php echo $rate; ?> <b><?php echo ($type == "SPECIAL") ? $type : "Q".$type; ?></b></font>&nbsp;
            </td>
    </tr>        
<?php if($active)  {?>
    <tr>
            <td bgcolor="#F5F5F5"><b><font size="-1">Reading</font>&nbsp;</b></td>
            <td bgcolor="#FFFFFF"><textarea name="reply" rows="10" cols="50"></textarea></td>
    </tr>
    <tr>
            <td colspan="2" bgcolor="#FFFFFF">
                <input name="extra_info" value="extra_info" type="checkbox">Extra information / not reading itself
            </td>
    </tr>
    <tr>
            <td colspan="2" bgcolor="#FFFFFF"><input value="Send" name="goReply" type="submit"></td>
    </tr>
<?php } else {?>
    <tr>
            <td colspan="2"><b>Waiting for client's payment...</b></td>
    </tr>
<?php }?>

</table>
<?php echo CHtml::endForm(); ?>