<?php
/**
 * class MessagesCheckBox
 *
 * Renders a checkbox element for message center table
 *
 * @package CGridView
 * @author  Den Kazka den.smart[at]gmail.com
 * @since   2010
 */
class MessagesCheckBox extends CCheckBoxColumn
{
    public function renderDataCellContent($row,$data)
	{
		if($this->value!==null)
			$value=$this->evaluateExpression($this->value,array('data'=>$data,'row'=>$row));
		else if($this->name!==null)
			$value=CHtml::value($data,$this->name);
		else
			$value=$this->grid->dataProvider->keys[$row];
		$options=$this->checkBoxHtmlOptions;
		$options['value']=$value;
		$options['id']=$this->id.'_'.$row;
		echo '<input type="checkbox" name="messages[]" value='.$options['value'].'>';
	}
}

?>
