<?php
defined('_JEXEC') or die('Restricted access');

class plgJshoppingProductsJcomments extends JPlugin
{
	function plgJshoppingProductsJcomments(&$subject, $config){        
		parent::__construct($subject, $config);
    }
	
	function onBeforeDisplayProductView(&$view) {
		$comments = JPATH_SITE . '/components/com_jcomments/jcomments.php';
        if (file_exists($comments)) { 
            require_once($comments);
            $view->_tmp_product_html_before_review = '<div class="jcomments_comment">'.JComments::showComments($view->product->product_id, 'com_jshopping', $view->product->name).'</div>';
        }
	}
	
	function onBeforeDisplayProductList(&$products) {
		if (count($products)) {
			$product_id_array = array();
			foreach ($products as $product) {
				$product_id_array[] = $product->product_id;
			}
			$db = &JFactory::getDBO();
			$query = "SELECT `object_id` as `id`, count(`object_id`) as `count`
					  FROM `#__jcomments`
					  WHERE `published`='1' AND `object_group`='com_jshopping' AND `object_id` IN ('".implode("','", $product_id_array)."')
					  GROUP BY `object_id`";
			$db->setQuery($query);
			$result = $db->LoadObjectList();
			$count_array = array();
			foreach ($result as $value) {
				$count_array[$value->id] = $value->count;
			}
			unset($result);
			foreach ($products as $key=>$value) {
				$products[$key]->_tmp_var_bottom_foto = '<div class="count_commentar">'.sprintf(_JSHOP_X_COMENTAR, ($count_array[$value->product_id] ? $count_array[$value->product_id] : 0)).'</div>';
			}
		}
	}
}