<center>
<a href="<?php echo Yii::app()->params['http_addr']; ?>users/mainmenu">Your account</a> | 
<a href="<?php echo Yii::app()->params['http_addr']; ?>emailreadings/list">Back to History</a>
<br><br>
</center>
<center><h2>Email Reading info</h2></center>
<table>
    <tr bgcolor="lightblue">
        <td colspan="2" width="416" align="center">
            <font color="#FFFFFF"><b>Question</b></font>
        </td>
    </tr>
    <tr>
        <td align="left"><?php echo $reading->l_topic ?></td>
    </tr>
    <tr bgcolor="lightblue">
        <td colspan="2" width="416" align="center">
            <font color="#FFFFFF"><b>Answer</b></font>
        </td>
    </tr>
    <tr>
        <td><?php echo $reading->reading; ?></td>
    </tr>   
</table>
    <table width="100%">
    <tr bgcolor="lightblue">
        <td colspan="2" width="416" align="center">
            <font color="#FFFFFF"><b>Additional data</b></font>
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
            <td bgcolor="#FFFFFF"><font size="-1"><?php echo (is_int($readingType)) ? $readingType.' Question' : $readingType; ?></font>&nbsp;</td>

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
            <td bgcolor="#FFFFFF"><font size="-1"><?php echo date('M d, Y h:i', $reading->date); ?></font>&nbsp;</td>
    </tr>
    <tr>
            <td bgcolor="#F5F5F5"><b><font size="-1">Order Number</font>&nbsp;</b></td>

            <td bgcolor="#FFFFFF"><font size="-1"><?php echo $reading->transaction; ?></font>&nbsp;</td>
    </tr>    
</table>