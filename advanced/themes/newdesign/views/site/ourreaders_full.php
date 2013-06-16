<script>
function showReaderList(first_load)
{
    var url = "/advanced/site/reloadReadersList?type=OurReaders<?php echo ($cat) ? "&cat=".$cat : ''; ?>&online=0&type=rest&count=18&title=no_title";
    
    $.ajax({    
    url: url,  
    cache: false,  
    success: function(html){  
        $("#readersList").html(html);  
    }  
    });    
}
var mytimer = setInterval("showReaderList()",10000) ;
$(document).ready(function(){
        showReaderList();
});
</script>
<center>
<h2>Meet our Professional Psychic Readers!</h2>
<h4>Login to read more information about each reader and select your favorite.</h4>

<br><br>
<form class="reader_search" name="chart" action="<?php echo Yii::app()->params['http_addr'] ?>site/ourreaders" method="POST">        	
    <img src="<?php echo Yii::app()->params['http_addr']; ?>new_images/blue-cnr-right.jpg" style="float:right;"/> <img src="<?php echo Yii::app()->params['http_addr']; ?>new_images/blue-cnr-left.jpg" style="float:left;"/>
    <label name="category" class="categorytext">Category</label>
    <select name="cat" class="category_select">
    <option value='-1' >Search by keyword --></option>
    <option value='astrology' <?php echo ($_POST['cat'] == 'astrology') ? 'selected' : ''; ?>><?php echo Yii::t('lang', 'Astrologers'); ?></option>
    <option value='tarot' <?php echo ($_POST['cat'] == 'tarot') ? 'selected' : ''; ?>><?php echo Yii::t('lang', 'Tarot_Readers'); ?></option>
    <option value='clairvoyance' <?php echo ($_POST['cat'] == 'clairvoyance') ? 'selected' : ''; ?>><?php echo Yii::t('lang', 'Clairvoyants'); ?> </option>
    <option value='spirit' <?php echo ($_POST['cat'] == 'spirit') ? 'selected' : ''; ?>><?php echo Yii::t('lang', 'Spirit_Guides'); ?></option>
    <option value='numerology' <?php echo ($_POST['cat'] == 'numerology') ? 'selected' : ''; ?>><?php echo Yii::t('lang', 'Numerology'); ?></option>
    <option value='reiki' <?php echo ($_POST['cat'] == 'reiki') ? 'selected' : ''; ?>><?php echo Yii::t('lang', 'Reiki'); ?></option>
    <option value='angel' <?php echo ($_POST['cat'] == 'angel') ? 'selected' : ''; ?>><?php echo Yii::t('lang', 'Angel_Readers'); ?></option>
    </select>
    <label class="keywordtext">Keyword</label>
    <input type="text" name="keyword"  value="<?php echo $_POST['keyword']; ?>" class="keyword"/>
    <input type="submit" value="" class="submit_button"/>                 
</form>
<div id="readersList">
  <center>
    <img style='margin-top: 100px;' src='<?php echo Yii::app()->params['http_addr'] ?>images/ajax_spinner.gif'>
  </center>
</div>
</center>