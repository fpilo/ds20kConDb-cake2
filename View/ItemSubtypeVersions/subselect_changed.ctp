<?php
	$tag = '<option value="%s" title="%s" %s>%s</option>';
 	if(empty($options)) {
 		$args = array(-1, 'Nothing found', 'disabled', 'Nothing found');
 		echo vsprintf($tag, $args);	
	} else {
		foreach($options as $option) {
			$disabled = (isset($option['disabled']) && ($option['disabled'])) ? 'disabled' : '';
			$args = array($option['value'], $option['title'], $disabled, $option['name']);
 			echo vsprintf($tag, $args);
		}
	}
?>