<?php require_once Yii::app()->params['project_root'].'/chat/setup.php'; ?>
<?php echo Yii::t('lang', 'Price_Rate'); ?>
<hr>
<center>
<table border="1" cellpadding="0" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111" width="65%">
  <tr>
    <td width="100%" colspan="2" align="center" bgcolor="#336699"><font color="#FFFFFF"><b>Your Rates/Pricing</b></font></td>
  </tr>
  <tr>
    <td width="50%" align="center"><b>Chat Duration (mins.)</b></td>
    <td width="50%" align="center"><b>Per minute Price ($)</b></td>

  </tr>
  <tr>
    <td width="100%" colspan="2" align="center" bgcolor="#F5F5F5"><b>No Affiliate</b></td>
  </tr>
  <tr>
    <td  align="center">< 70</td>
    <td  align="center"><?php echo $price_old_0_1;?></td>

  </tr>
  <tr>
    <td  align="center">>= 70</td>
    <td  align="center"><?php echo $price_old_0_2;?></td>
  </tr>


  <tr>
    <td width="100%" colspan="2" align="center" bgcolor="#F5F5F5"><b>A-1</b></td>

  </tr>
  <tr>
    <td  align="center"><= 25</td>
    <td  align="center"><?php echo $price_old_a1_1;?></td>
  </tr>
  <tr>
    <td  align="center"><= 40</td>

    <td  align="center"><?php echo $price_old_a1_2;?></td>
  </tr>
  <tr>
    <td  align="center"><= 55</td>
    <td  align="center"><?php echo $price_old_a1_3;?></td>
  </tr>
  <tr>

    <td  align="center">> 55</td>
    <td  align="center"><?php echo $price_old_a1_4;?></td>
  </tr>

  <tr>
    <td width="100%" colspan="2" align="center" bgcolor="#F5F5F5"><b>A-2</b></td>
  </tr>
  <tr>

    <td  align="center"><= 25</td>
    <td  align="center"><?php echo $price_old_a2_1;?></td>
  </tr>
  <tr>
    <td  align="center"><= 40</td>
    <td  align="center"><?php echo $price_old_a2_2;?></td>
  </tr>

  <tr>
    <td  align="center"><= 70</td>
    <td  align="center"><?php echo $price_old_a2_3;?></td>
  </tr>
  <tr>
    <td  align="center">> 70</td>
    <td  align="center"><?php echo $price_old_a2_4;?></td>

  </tr>
</table>
<br><br>
<table border="1" cellpadding="0" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111" width="65%">
  <tr>
    <td width="100%" colspan="2" align="center" bgcolor="#336699"><font color="#FFFFFF"><b>Your Rates/Pricing (BMT)</b></font></td>
  </tr>
  <tr>
    <td width="50%" align="center"><b>Chat Duration (mins.)</b></td>

    <td width="50%" align="center"><b>Per minute Price ($)</b></td>
  </tr>
  <tr>
    <td width="100%" colspan="2" align="center" bgcolor="#F5F5F5"><b>No Affiliate</b></td>
  </tr>
  <tr>
    <td  align="center"><= 15</td>

    <td  align="center"><?php echo $price_bmtold_0_1;?></td>
  </tr>
  <tr>
    <td  align="center"><= 30</td>
    <td  align="center"><?php echo $price_bmtold_0_2;?></td>
  </tr>
  <tr>

    <td  align="center">< 60</td>
    <td  align="center"><?php echo $price_bmtold_0_3;?></td>
  </tr>
  <tr>
    <td  align="center">>= 60</td>
    <td  align="center"><?php echo $price_bmtold_0_4;?></td>
  </tr>

  <tr>
    <td width="100%" colspan="2" align="center" bgcolor="#F5F5F5"><b>A-1</b></td>
  </tr>
  <tr>
    <td  align="center"><= 15</td>
    <td  align="center"><?php echo $price_bmtold_a1_1;?></td>
  </tr>

  <tr>
    <td  align="center"><= 30</td>
    <td  align="center"><?php echo $price_bmtold_a1_2;?></td>
  </tr>
  <tr>
    <td  align="center">< 60</td>
    <td  align="center"><?php echo $price_bmtold_a1_3;?></td>

  </tr>
  <tr>
    <td  align="center">>= 60</td>
    <td  align="center"><?php echo $price_bmtold_a1_4;?></td>
  </tr>

  <tr>
    <td width="100%" colspan="2" align="center" bgcolor="#F5F5F5"><b>A-2</b></td>

  </tr>
  <tr>
    <td  align="center"><= 15</td>
    <td  align="center"><?php echo $price_bmtold_a1_1;?></td>
  </tr>
  <tr>
    <td  align="center"><= 30</td>

    <td  align="center"><?php echo $price_bmtold_a1_2;?></td>
  </tr>
  <tr>
    <td  align="center">< 60</td>
    <td  align="center"><?php echo $price_bmtold_a1_3;?></td>
  </tr>
  <tr>

    <td  align="center">>= 60</td>
    <td  align="center"><?php echo $price_bmtold_a1_4;?></td>
  </tr>
</table>
</center>
