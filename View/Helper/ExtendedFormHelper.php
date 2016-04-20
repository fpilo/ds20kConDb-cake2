<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright   Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link        http://cakephp.org CakePHP(tm) Project
 * @package       Cake.View.Helper
 * @since       CakePHP(tm) v 0.10.0.1076
 * @license     MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('FormHelper', '/View/Helper');

/**
 * Form helper library.
 *
 * Automatic generation of HTML FORMs from given data.
 *
 * @package       Cake.View.Helper
 * @property      HtmlHelper $Html
 * @link http://book.cakephp.org/2.0/en/core-libraries/helpers/form.html
 */
class ExtendedFormHelper extends FormHelper {

/**
 * Other helpers used by FormHelper
 *
 * @var array
 */
	public $helpers = array('Html','Session');

/**
 * Creates an HTML link, but access the url using method POST.
 * Requires javascript to be enabled in browser.
 *
 * This method creates a `<form>` element. So do not use this method inside an existing form.
 * Instead you should add a submit button using FormHelper::submit()
 *
 * ### Options:
 *
 * - `data` - Array with key/value to pass in input hidden
 * - `confirm` - Can be used instead of $confirmMessage.
 * - Other options is the same of HtmlHelper::link() method.
 * - The option `onclick` will be replaced.
 *
 * @param string $title The content to be wrapped by <a> tags.
 * @param mixed $url Cake-relative URL or array of URL parameters, or external URL (starts with http://)
 * @param array $options Array of HTML attributes.
 * @param string $confirmMessage JavaScript confirmation message.
 * @return string An `<a />` element.
 * @link http://book.cakephp.org/2.0/en/core-libraries/helpers/form.html#FormHelper::postLink
 */
	public function postLink($title, $url = null, $options = array(), $confirmMessage = false) {
		if (!empty($options['confirm'])) {
			$confirmMessage = $options['confirm'];
			unset($options['confirm']);
		}

		$formName = uniqid('post_');
		$formUrl = $this->url($url);
		$out = $this->Html->useTag('form', $formUrl, array('name' => $formName, 'id' => $formName, 'style' => 'display:none;', 'method' => 'post'));
		$out .= $this->Html->useTag('hidden', '_method', ' value="POST"');
		$out .= $this->_csrfField();
		
		/** 
         * Override link method for Acl rights checking
		 *
		 * Use $options['extern'] = true to skip right check.
		 * http://bakery.cakephp.org/articles/aalexgabi/2011/07/26/override_htmlhelper_for_acl_component_hide_links_where_users_don_t_have_privileges
		 * http://bakery.cakephp.org/articles/thanos/2011/01/17/acl_checking_permissions_in_views
         */
		if(parent::$checkAuthorization) {
			$normUrl = Router::normalize($url);
			
			$parsedUrl = Router::parse($normUrl); 
			
			if (	isset($parsedUrl['controller']) && 
					isset($parsedUrl['action']) && 
					strpos($parsedUrl['controller'], 'mailto:') !== 0 && 
					!$this->Session->check('Auth.User.Permissions.controllers/' . Inflector::camelize($parsedUrl['controller']) . '/' . $parsedUrl['action'])) 
					return false;
		}
        /** 
         * End override 
         */ 

		$fields = array();
		if (isset($options['data']) && is_array($options['data'])) {
			foreach ($options['data'] as $key => $value) {
				$fields[$key] = $value;
				$out .= $this->hidden($key, array('value' => $value, 'id' => false));
			}
			unset($options['data']);
		}
		$out .= $this->secure($fields);
		$out .= $this->Html->useTag('formend');

		$url = '#';
		$onClick = 'document.' . $formName . '.submit();';
		if ($confirmMessage) {
			$confirmMessage = str_replace(array("'", '"'), array("\'", '\"'), $confirmMessage);
			$options['onclick'] = "if (confirm('{$confirmMessage}')) { {$onClick} }";
		} else {
			$options['onclick'] = $onClick;
		}
		$options['onclick'] .= ' event.returnValue = false; return false;';

		$out .= $this->Html->link($title, $url, $options);
		return $out;
	}
}
