<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr>
    <td bgcolor="#F7F7F7" nowrap>
        <?php if ($layout == 1): ?>
            <?php foreach ($links as $item): ?>
            <tr>
                <td height="26" align="left" class="redtxt14" style="padding-left:25px"><a href="<?php echo $item['link']; ?>" class="redtxt14"><?php echo $item['title']; ?></a></td>
            </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        <?php if ($layout == 2): ?>
            <?php foreach ($links as $item): ?>
                &nbsp;&nbsp;&nbsp; <img width="3" height="6" border="0" alt="" src="<?php echo Yii::app()->params['http_addr']; ?>images/pointer1.gif"/>&nbsp;
                    <a class="<?php echo $item['class']; ?>" href="<?php echo $item['link']; ?>"><?php echo $item['title']; ?></a>
                <br/>
            <?php endforeach; ?>
        <?php endif; ?>
        <?php if ($layout == 'default'): ?>
            <?php foreach ($links as $item): ?>
                &nbsp;&nbsp;&nbsp; <img width="3" height="6" border="0" alt="" src="<?php echo Yii::app()->params['http_addr']; ?>images/pointer1.gif"/>&nbsp;
                    <a class="<?php echo $item['class']; ?>" 
                       href="<?php echo $item['link']; ?>" 
                       <?php echo ($item['link'] == Yii::app()->params['ssl_addr'].'site/login') ? 'title="Secure Login"' : ''; ?>
                    >
                    <?php echo $item['title']; ?>
                    </a>
                <br/>
            <?php endforeach; ?>
        <?php endif; ?>
        <?php if(!empty($leftArt)): ?>
        <?php foreach($leftArt as $thread): ?>
            &nbsp;&nbsp;&nbsp; <img src="<?php echo Yii::app()->params['http_addr']; ?>images/pointer1.gif" width="3" height="6" alt="" border=0>
                <a href="<?php echo Yii::app()->params['http_addr'] ?>articles/thread?id=<?php echo $thread['alias']; ?>" class="navigation">&nbsp; <?php echo $thread['title']; ?></a><br>
        <?php endforeach; ?>
        <?php endif; ?>
        <br/>
     </td>
</tr>
</table>
<table width="100%">
<tr>
    <td align="center">
        <table border="1" borderwidth="0" bordercolor="#660099" cellspacing="0" cellpadding="0" width="95%">
        <tr>
           <td align="center">
                <strong><font color="#0000ff"><a href="http://www.psychic-contact.com/blog">Psychic Contact Blog</a></font><br>
                Join our Psychics<br>
                for Spiritual discussions <br>
                from Dreams to Tea Leaves <br>
                & Predictions to the Tarot
           </td>
        </tr>
        </table>
    </td>
</tr>
</table>
<table width="100%">
<tr>
    <td align="center">
        <table border="1" borderwidth="0" bordercolor="#660099" cellspacing="0" cellpadding="0" width="95%">
        <tr>
           <td align="center">
                <br/>
                <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
                <input type="hidden" name="cmd" value="_s-xclick">
                <input type="hidden" name="hosted_button_id" value="9933063">
                <input type="image" src="https://www.paypal.com/en_US/i/btn/btn_giftCC_LG.gif" border="0" name="submit" alt="<?php echo Yii::t('lang', 'PayPal_The_safer_easier'); ?>">
                <img alt="" border="0" src="<?php echo Yii::app()->params['site_domain']; ?>/chat/images/paypal.jpg" height="40">
                <img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
                </form>
                <span style="color: rgb(160, 64, 255); font-weight: bold;"><?php echo Yii::t('lang', 'celebrate_the_new_year'); ?></span>
                <br/>
                 $19.95: 20<?php echo Yii::t('lang', 'mins_chat_session'); ?><br/><br/>
                <span style="font-size: 10px;"> <?php echo Yii::t('lang', 'Please_note_NS'); ?></span>
                <br/><br/>
                <a href="<?php echo Yii::app()->params['ssl_addr']; ?>site/signupCert"><?php echo Yii::t('lang', 'Redeem_Gift_Certificate'); ?></a>
           </td>
        </tr>
        </table>
    </td>
</tr>
</table>
<br />
<table width="100%">
<tr>
    <td align="center">
        <table border="1" borderwidth="0" bordercolor="#660099" cellspacing="0" cellpadding="0" width="95%">
            <tr>
                <td align="center">
                    <a href="<?php echo Yii::app()->params['http_addr']?>site/login" style="text-decoration:none">
                    <b><?php echo Yii::t('lang', 'SPECIAL_SPRING'); ?><br /></b>
                    <span style="color:#000000">
                    $19.95 20<?php echo Yii::t('lang', 'mins_Chat_Session02'); ?><br />
                    </span>
                    </a>
                </td>
            </tr>
        </table>
    </td>
</tr>
</table>
<br />
<?php 
$this->widget('FeaturedReaderShow');
?>
<br />
&nbsp;&nbsp;&nbsp; &nbsp;
<a href="<?php echo Yii::app()->params['http_addr']; ?>articles" target="_top"  class="navigation"><?php echo Yii::t('lang', 'Metaphysical_Articles'); ?></a><br>
&nbsp;&nbsp;&nbsp; &nbsp;
<a href="<?php echo Yii::app()->params['http_addr']; ?>site/sitemap" target="_top"  class="navigation"><?php echo Yii::t('lang', 'Site_Map'); ?></a><br>
&nbsp;&nbsp;&nbsp; &nbsp;
<a href="<?php echo Yii::app()->params['http_addr']; ?>site/ourreaders?cat=tarot" target="_top"  class="navigation"><?php echo Yii::t('lang', 'Tarot_Readings'); ?> </a><br>
&nbsp;&nbsp;&nbsp; &nbsp;
<a href="<?php echo Yii::app()->params['http_addr']; ?>site/ourreaders?cat=clairvoyance" target="_top"  class="navigation"><?php echo Yii::t('lang', 'Clairvoyants'); ?></a><br>
&nbsp;&nbsp;&nbsp; &nbsp;
<a href="<?php echo Yii::app()->params['http_addr']; ?>site/ourreaders?cat=spirit" target="_top"  class="navigation"><?php echo Yii::t('lang', 'Spirit_Guides'); ?></a><br>
&nbsp;&nbsp;&nbsp; &nbsp;
<a href="<?php echo Yii::app()->params['http_addr']; ?>site/ourreaders?cat=angel" target="_top"  class="navigation"><?php echo Yii::t('lang', 'Angel_Readings'); ?></a><br>
&nbsp;&nbsp;&nbsp; &nbsp;
<a href="<?php echo Yii::app()->params['http_addr']; ?>site/ourreaders?cat=clairvoyance" target="_top"  class="navigation"><?php echo Yii::t('lang', 'Dream_Interpretation'); ?></a><br>
&nbsp;&nbsp;&nbsp; &nbsp;
<a href="<?php echo Yii::app()->params['http_addr']; ?>site/ourreaders?cat=astrology" target="_top"  class="navigation"><?php echo Yii::t('lang', 'Astrology'); ?></a><br>
&nbsp;&nbsp;&nbsp; &nbsp;
<a href="<?php echo Yii::app()->params['http_addr']; ?>site/ourreaders?cat=reiki" target="_top"  class="navigation"><?php echo Yii::t('lang', 'Reiki'); ?></a><br>
&nbsp;&nbsp;&nbsp; &nbsp;
<a href="<?php echo Yii::app()->params['http_addr']; ?>site/ourreaders?cat=numerology" target="_top"  class="navigation"><?php echo Yii::t('lang', 'Numerology'); ?></a><br><br>