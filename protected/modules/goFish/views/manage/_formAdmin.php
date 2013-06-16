<div class="form">

<?php

$form = $this->beginWidget('CActiveForm', array('id' => 'go-fish-answers-form',
    'enableAjaxValidation' => false,  'htmlOptions'=>array('enctype' => 'multipart/form-data'),  ));

?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

    <div class="row">
		<b> Reader name <span class="required">*</span></b>
		<select name="reader_id">
        <?php

//        $readers = Readers::getGoFishReadersList();
//        foreach($readers as $r)
//        {
//            echo $r->name.'<br>';
//        }
//        die();
        foreach (Readers::getGoFishReadersList() as $reader)
        {
        	if (GoFishFishes::haveModel($reader->rr_record_id)) {
	            if ($model->author_id == $reader->rr_record_id)
	            {
	                echo '<option value="' . $reader->rr_record_id . '" selected>' . $reader->name .
	                    '</option>';
	            } else
	            {
	                echo '<option value="' . $reader->rr_record_id . '">' . $reader->name .
	                    '</option>';
	            }
			}
        }

        ?>
        </select>
	</div>
    
    <div class="row">
		<?php echo $form->labelEx($model, 'content');?>
		<?php echo $form->textArea($model, 'content', array('rows' => 6, 'cols' => 50));?>
		<?php echo $form->error($model, 'content');?>
	</div>


    <div class="row">
        <?php echo $form->labelEx($model, 'sound .mp3');?>
        <?php echo CHtml::activeFileField($model, 'sound_mp3'); ?>
        <?php echo $form->error($model, 'sound_mp3');?>
    </div>
    <div class="row">
        <?php echo $form->labelEx($model, 'sound .ogg');?>
        <?php echo CHtml::activeFileField($model, 'sound_ogg'); ?>
        <?php echo $form->error($model, 'sound_ogg');?>
    </div>

    <div class="row buttons">
            <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save');?>
    </div>

<?php $this->endWidget(); ?>

</div>