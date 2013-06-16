<center>
<?php echo '<font color="red">'.$error.'</font>'; ?>
<?php echo '<font color="green">'.$success.'</font>'; ?>    
<form enctype="multipart/form-data" action="<?php echo Yii::app()->params['http_addr'] ?>profile/userprofile" method="POST">
<table border="0" cellspacing="1" cellpadding="5" bgcolor="#CCCCCC" width="500">
        <tr>
                <td bgcolor="#336699" colspan="2" width="100%" align="center"><font color="#FFFFFF"><b>Edit a Reader</font></b></td>
        </tr>
        <tr>
                <td bgcolor="#336699" width="200"><font color="#FFFFFF"><b>UserName&nbsp;</font></b></td>
                <td bgcolor="#F5F5F5"><font size="-1"><input type="text" name="login" size=30 maxlength=100 class="form" value="<?php echo $reader->login; ?>" readonly style="color:#B1B1B1"></font>&nbsp;</td>

        </tr>
        <tr>
                <td bgcolor="#336699"><font color="#FFFFFF"><b>Password&nbsp;</font></b></td>
                <td bgcolor="#F5F5F5"><font size="-1"><input  type="password" name="password" value="<?php echo $reader->password; ?>" class="form" maxlength=100 id="password"><input type="button" value="view password" onclick="alert(document.getElementById('password').value)"></font>&nbsp;</td>
        </tr>
        <tr>
                <td bgcolor="#336699"><font color="#FFFFFF"><b>Email&nbsp;</font></b></td>
                <td bgcolor="#F5F5F5"><font size="-1"><input type="text" name="emailaddress" value="<?php echo $reader->emailaddress; ?>" size=30 maxlength=100 class="form"></font>&nbsp;</td>

        </tr>
        <tr>
                <td bgcolor="#336699"><font color="#FFFFFF"><b>Nick&nbsp;</font></b></td>
                <td bgcolor="#F5F5F5"><font size="-1"><input type="text" name="name" value="<?php echo $reader->name; ?>" size=30 maxlength=100 class="form"></font>&nbsp;</td>
        </tr>
         <tr>
                <td bgcolor="#336699"><font color="#FFFFFF"><b>Full Name&nbsp;</font></b></td>
                <td bgcolor="#F5F5F5"><font size="-1"><input type="text" name="reader_real_name" value="<?php echo $reader->reader_real_name; ?>" size=30 maxlength=100 class="form"></font>&nbsp;</td>

        </tr>
        <tr>
                <td bgcolor="#336699"><font color="#FFFFFF"><b>Address</font></b></td>
                <td bgcolor="#F5F5F5"><font size="-1"><input type="text" name="address" value="<?php echo $reader->address; ?>" size=30 maxlength=255 class="form"></font>&nbsp;</td>
        </tr>
        <tr>
                <td bgcolor="#336699"><font color="#FFFFFF"><b>Phone</font></b></td>
                <td bgcolor="#F5F5F5"><font size="-1"><input type="text" name="phone" value="<?php echo $reader->phone; ?>" size=30 maxlength=60 class="form"></font>&nbsp;</td>

        </tr>
        <tr>
                <td bgcolor="#336699"><font color="#FFFFFF"><b>Astrologers</font></b></td>
                <td bgcolor="#F5F5F5">
                    <select name="astrologers" class="form">
                        <option value="">Off
                        <option value="rr_ignorethisvalue">Off
                        <option value="on">On
                    </select>
                </td>
        </tr>

        <tr>
                <td bgcolor="#336699"><font color="#FFFFFF"><b>Tarotreaders</font></b></td>
                <td bgcolor="#F5F5F5">
                    <select name="tarotreaders" class="form">
                        <option value="">Off<option value="rr_ignorethisvalue">Off
                        <option value="on">On
                    </select>
                </td>
        </tr>
        <tr>

                <td bgcolor="#336699"><font color="#FFFFFF"><b>Clairvoyants</font></b></td>
                <td bgcolor="#F5F5F5">
                    <select name="clairvoyants" class="form">
                        <option value="">Off<option value="rr_ignorethisvalue">Off
                        <option value="on">On
                    </select>
                </td>
        </tr>
        <tr>
                <td bgcolor="#336699">
                    <font color="#FFFFFF"><b>Area</b></font>
                </td>
                <td bgcolor="#F5F5F5">
                    <font size="-1">
                        <input type="text" name="area" value="<?php echo $reader->area; ?>" size=30 maxlength=255 class="form">
                    </font>&nbsp;
                </td>        </tr>
        <tr>
                <td bgcolor="#336699" valign="top"><font color="#FFFFFF"><b>Testimonials</font></b></td>
                <td bgcolor="#F5F5F5"><font size="-1">Please put testimonials <a href="<?php echo Yii::app()->params['http_addr']; ?>testimonials">here</a> </td>
        </tr>
        <tr>
                <td bgcolor="#336699" valign="top"><font color="#FFFFFF"><b>Comments</font></b></td>
                <td bgcolor="#F5F5F5"><font size="-1"><textarea rows="10" name="comments" cols="27"><?php echo $reader->comments; ?></textarea></td>
        </tr>
        <tr>
                <td bgcolor="#336699" valign="top"><font color="#FFFFFF"><b>Special reading</font></b></td>
                <td bgcolor="#F5F5F5"><font size="-1"><textarea rows="10" name="special_reading" cols="27"><?php echo $reader->special_reading; ?></textarea></td>
        </tr>
        <tr>
                <td bgcolor="#336699"><font color="#FFFFFF"><b>Photo</b><br>(.gif .jpg .png)</font></td>
                <td bgcolor="#F5F5F5"><input type="file" name="photo" value="" size="10"></td>
        </tr>
</table>
<input type="submit" name="save" value="Save">
</form>
</center>

