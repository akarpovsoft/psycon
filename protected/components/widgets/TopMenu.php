<?php
/**
 * Show Top Menu 
 * 
 * To use widget specify links array.
 * Each link should be an array with next keys:
 * - visible: boolean, whether this item is visible;
 * - label: string, label of this menu item. Make sure you HTML-encode it if needed;
 * - url: string|array, the URL that this item leads to. Use a string to
 *   represent a static URL, while an array for constructing a dynamic one.
 * - width: width of menu table column 
 */

class TopMenu extends CWidget {

	public $links;
	
	public function run() {
		
		$items = array();
		
		foreach ($this->links as $item) {
			if(isset($item['visible']) && !$item['visible'])
				continue;
			$item2 = array();
			$item2['label'] = $item['label'];	
			if(is_array($item['url']))
				$item2['url']=Yii::app()->params['site_domain'].'/'.$item['url'][0];
			else
				$item2['url']=$item['url'];
			$item2['width'] = $item['width'];
			$item2['visible'] = $item['visible'];
			$items[]=$item2;	
		}
		
		$this->render('topMenu',array('items'=>$items));
	}
	
	
}
?>