<?php
/**
 * Html Helper class file.
 *
 * Simplifies the construction of HTML elements.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       Cake.View.Helper
 * @since         CakePHP(tm) v 0.9.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('HtmlHelper', 'View/Helper');

/**
 * Html Helper class for easy use of HTML widgets.
 *
 * HtmlHelper encloses all methods needed while working with HTML pages.
 *
 * @package       Cake.View.Helper
 * @link http://book.cakephp.org/2.0/en/core-libraries/helpers/html.html
 */
class ExtendedHtmlHelper extends HtmlHelper {

	var $helpers = array("Session");


	protected $_myTags = array(
		'dl' => '<dl>%s</dl>',
		'dlitem' => '<dt>%s</dt><dd>%s</dd>'
	);

/**
 * Creates an HTML link.
 *
 * If $url starts with "http://" this is treated as an external link. Else,
 * it is treated as a path to controller/action and parsed with the
 * HtmlHelper::url() method.
 *
 * If the $url is empty, $title is used instead.
 *
 * ### Options
 *
 * - `escape` Set to false to disable escaping of title and attributes.
 * - `confirm` JavaScript confirmation message.
 *
 * @param string $title The content to be wrapped by <a> tags.
 * @param mixed $url Cake-relative URL or array of URL parameters, or external URL (starts with http://)
 * @param array $options Array of HTML attributes.
 * @param string $confirmMessage JavaScript confirmation message.
 * @return string An `<a />` element.
 * @link http://book.cakephp.org/2.0/en/core-libraries/helpers/html.html#HtmlHelper::link
 */
	public function link($title, $url = null, $options = array(), $confirmMessage = false) {
		$escapeTitle = true;
		if ($url !== null) {
			$url = $this->url($url);
		} else {
			$url = $this->url($title);
			$title = h(urldecode($url));
			$escapeTitle = false;
		}

		if (isset($options['escape'])) {
			$escapeTitle = $options['escape'];
		}

		if ($escapeTitle === true) {
			$title = h($title);
		} elseif (is_string($escapeTitle)) {
			$title = htmlentities($title, ENT_QUOTES, $escapeTitle);
		}

		if (!empty($options['confirm'])) {
			$confirmMessage = $options['confirm'];
			unset($options['confirm']);
		}
		if ($confirmMessage) {
			$confirmMessage = str_replace("'", "\'", $confirmMessage);
			$confirmMessage = str_replace('"', '\"', $confirmMessage);
			$options['onclick'] = "return confirm('{$confirmMessage}');";
		} elseif (isset($options['default']) && $options['default'] == false) {
			if (isset($options['onclick'])) {
				$options['onclick'] .= ' event.returnValue = false; return false;';
			} else {
				$options['onclick'] = 'event.returnValue = false; return false;';
			}
			unset($options['default']);
		}
				
		/** 
         * Override link method for Acl rights checking
		 *
		 * http://bakery.cakephp.org/articles/aalexgabi/2011/07/26/override_htmlhelper_for_acl_component_hide_links_where_users_don_t_have_privileges
		 * http://bakery.cakephp.org/articles/thanos/2011/01/17/acl_checking_permissions_in_views
         */
		if(parent::$checkAuthorization) {
			$normUrl = Router::normalize($url);
			
			$parsedUrl = Router::parse($normUrl);
			
			if ( $url == '#' )
				return sprintf($this->_tags['link'], $url, $this->_parseAttributes($options), $title);
			elseif(substr($url,0,4) == 'http') {
				return sprintf($this->_tags['link'], $url, $this->_parseAttributes($options), $title);
			}
			
			if (isset($options['external']) && $options['external'] == true) {
				return sprintf($this->_tags['link'], $url, $this->_parseAttributes($options), $title);
			}
				
			if (	isset($parsedUrl['plugin']) && 
					isset($parsedUrl['controller']) && 
					isset($parsedUrl['action']) && 
					(strpos($parsedUrl['controller'], 'mailto:') !== false || 
					$this->Session->check('Auth.User.Permissions.controllers/' . Inflector::camelize($parsedUrl['plugin']) . '/' . Inflector::camelize($parsedUrl['controller']) . '/' . $parsedUrl['action'])))
					return sprintf($this->_tags['link'], $url, $this->_parseAttributes($options), $title);
			elseif (isset($parsedUrl['controller']) && 
					isset($parsedUrl['action']) && 
					(strpos($parsedUrl['controller'], 'mailto:') !== false || 
					$this->Session->check('Auth.User.Permissions.controllers/' . Inflector::camelize($parsedUrl['controller']) . '/' . $parsedUrl['action']))) 
					return sprintf($this->_tags['link'], $url, $this->_parseAttributes($options), $title);
			else 	return false;
		}
        /** 
         * End override 
         */ 

		return sprintf($this->_tags['link'], $url, $this->_parseAttributes($options), $title);  // alle links zeigen
	}
	
	public function tableLink($title, $url = null, $options = array(), $confirmMessage = false) {
		$escapeTitle = true;
		if ($url !== null) {
			$url = $this->url($url);
		} else {
			$url = $this->url($title);
			$title = h(urldecode($url));
			$escapeTitle = false;
		}

		if (isset($options['escape'])) {
			$escapeTitle = $options['escape'];
		}

		if ($escapeTitle === true) {
			$title = h($title);
		} elseif (is_string($escapeTitle)) {
			$title = htmlentities($title, ENT_QUOTES, $escapeTitle);
		}

		if (!empty($options['confirm'])) {
			$confirmMessage = $options['confirm'];
			unset($options['confirm']);
		}
		if ($confirmMessage) {
			$confirmMessage = str_replace("'", "\'", $confirmMessage);
			$confirmMessage = str_replace('"', '\"', $confirmMessage);
			$options['onclick'] = "return confirm('{$confirmMessage}');";
		} elseif (isset($options['default']) && $options['default'] == false) {
			if (isset($options['onclick'])) {
				$options['onclick'] .= ' event.returnValue = false; return false;';
			} else {
				$options['onclick'] = 'event.returnValue = false; return false;';
			}
			unset($options['default']);
		}
		
		/** 
         * Override link method for Acl rights checking
		 *
		 * Use $options['extern'] = true to skip right check.
		 * http://bakery.cakephp.org/articles/aalexgabi/2011/07/26/override_htmlhelper_for_acl_component_hide_links_where_users_don_t_have_privileges
         */
		if(parent::$checkAuthorization) {
			$normUrl = Router::normalize($url);
			
			$parsedUrl = Router::parse($normUrl); 
			
			if (	isset($parsedUrl['controller']) && 
					isset($parsedUrl['action']) && 
					(strpos($parsedUrl['controller'], 'mailto:') !== false || 
					$this->Session->check('Auth.User.Permissions.controllers/' . Inflector::camelize($parsedUrl['controller']) . '/' . $parsedUrl['action']))) 
					return sprintf($this->_tags['link'], $url, $this->_parseAttributes($options), $title);
			else	return $title;
        }
        /** 
         * End override 
         */ 
		 
		return sprintf($this->_tags['link'], $url, $this->_parseAttributes($options), $title);
	}

/**
 * Creates a formatted IMG element.
 *
 * This method will set an empty alt attribute if one is not supplied.
 *
 * ### Usage:
 *
 * Create a regular image:
 *
 * `echo $this->Html->image('cake_icon.png', array('alt' => 'CakePHP'));`
 *
 * Create an image link:
 *
 * `echo $this->Html->image('cake_icon.png', array('alt' => 'CakePHP', 'url' => 'http://cakephp.org'));`
 *
 * ### Options:
 *
 * - `url` If provided an image link will be generated and the link will point at
 *   `$options['url']`.
 * - `fullBase` If true the src attribute will get a full address for the image file.
 * - `plugin` False value will prevent parsing path as a plugin
 *
 * @param string $path Path to the image file, relative to the app/webroot/img/ directory.
 * @param array $options Array of HTML attributes.  See above for special options.
 * @return string completed img tag
 * @link http://book.cakephp.org/2.0/en/core-libraries/helpers/html.html#HtmlHelper::image
 */
	public function image($path, $options = array()) {
		$path = $this->assetUrl($path, $options + array('pathPrefix' => IMAGES_URL));
		$options = array_diff_key($options, array('fullBase' => '', 'pathPrefix' => ''));

		if (!isset($options['alt'])) {
			$options['alt'] = '';
		}

		$url = false;
		if (!empty($options['url'])) {
			$url = $options['url'];
			unset($options['url']);
		}
		
        if (!empty($options['linkOptions'])) {
            $linkOptions = $options['linkOptions'];
            unset($options['linkOptions']);
        }

		$image = sprintf($this->_tags['image'], $path, $this->_parseAttributes($options, null, '', ' '));

		if (!empty($url)) {
			/** 
	         * Override link method for Acl rights checking
			 *
			 * Use $options['extern'] = true to skip right check.
			 * http://bakery.cakephp.org/articles/aalexgabi/2011/07/26/override_htmlhelper_for_acl_component_hide_links_where_users_don_t_have_privileges
	         */
			if(parent::$checkAuthorization) {
				$normUrl = Router::normalize($url);
				
				$parsedUrl = Router::parse($normUrl);
                if (substr($normUrl, 0, 4) == "http" ) {
                    return sprintf($this->_tags['link'], $this->url($url), $this->_parseAttributes($linkOptions, null, '', ' '), $image); 
                }
                
				if (	isset($parsedUrl['controller']) && 
						isset($parsedUrl['action']) && 
						(strpos($parsedUrl['controller'], 'mailto:') !== false || 
						$this->Session->check('Auth.User.Permissions.controllers/' . Inflector::camelize($parsedUrl['controller']) . '/' . $parsedUrl['action']))) 
						return sprintf($this->_tags['link'], $this->url($url), null, $image);
				else	return false;
	        }
	        /** 
	         * End override 
	         */ 
		}
		return $image;
	}
/**
 * Returns a formatted string of table rows (TR's with TD's in them).
 *
 * @param array $data Array of table data
 * @param array $oddTrOptions HTML options for odd TR elements if true useCount is used
 * @param array $evenTrOptions HTML options for even TR elements
 * @param boolean $useCount adds class "column-$i"
 * @param boolean $continueOddEven If false, will use a non-static $count variable,
 *    so that the odd/even count is reset to zero just for that call.
 * @return string Formatted HTML
 * @link http://book.cakephp.org/2.0/en/core-libraries/helpers/html.html#HtmlHelper::tableCells
 */
	public function tableCells($data, $oddTrOptions = null, $evenTrOptions = null, $useCount = false, $continueOddEven = true) {
		/*	
		 * Sometimes the first data array key is not 0 or even not numeric (for example items[position] where position can be anything).
		 * array_values() returns all the values from the array and indexes the array numerically.
		 */
		$data = array_values($data); 
		  
		if (empty($data[0]) || !is_array($data[0])) {
			$data = array($data);
		}
		
		if ($oddTrOptions === true) {
			$useCount = true;
			$oddTrOptions = null;
		}

		if ($evenTrOptions === false) {
			$continueOddEven = false;
			$evenTrOptions = null;
		}

		if ($continueOddEven) {
			static $count = 0;
		} else {
			$count = 0;
		}

		foreach ($data as $line) {
			$count++;
			$cellsOut = array();
			$i = 0;
			foreach ($line as $cell) {
				$cellOptions = array();

				if (is_array($cell)) {
					$cellOptions = $cell[1];
					$cell = $cell[0];
				} elseif ($useCount) {
					$cellOptions['class'] = 'column-' . ++$i;
				}
				$cellsOut[] = sprintf($this->_tags['tablecell'], $this->_parseAttributes($cellOptions), $cell);
			}
			$options = $this->_parseAttributes($count % 2 ? $oddTrOptions : $evenTrOptions);
			$out[] = sprintf($this->_tags['tablerow'], $options, implode(' ', $cellsOut));
		}
		return implode("\n", $out);
	}


/**
 * Build a defnition list (DL) out of an associative array.
 *
 * @param array $list Set of elements to list
 * @return string The nested list
 */
	public function definitionList($list) {
		$items = $this->_definitionListItem($list);
		return sprintf($this->_myTags['dl'], $items);
	}

/**
 * Internal function to build a definition list (DL) out of an associative array.
 *
 * @param array $items Set of elements to list
 * @return string The definition list element
 * @see HtmlHelper::definitionList()
 */
	protected function _definitionListItem($items) {
		$out = array();

		$index = 1;
		foreach ($items as $key => $item) {
			$out[] = sprintf($this->_myTags['dlitem'], $key, $item);
			$index++;
		}
		$out = implode("\n", $out);
		return $out;
	}
}
