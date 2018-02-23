<?php
/*------------------------------------------------------------------------
# plg_yshare
# ------------------------------------------------------------------------
# author &nbsp; &nbsp;Buyanov Danila - Saity74 LLC.
# copyright Copyright (C) 2012 saity74.ru. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.saity74.ru
# Technical Support: &nbsp; http://saity74.ru/yshare-joomla.html
# Admin E-mail: admin@saity74.ru
-------------------------------------------------------------------------*/

// No direct access
defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

class plgSystemYShare extends JPlugin
{
    
    public function onBeforeRender()
    {
	$app =& JFactory::getApplication();
	$doc =& JFactory::getDocument();
	
	if( $app->isAdmin() )
	{
	    $style = '
	    #jform_params_services{width: 600px}
	    #jform_params_services ul{list-style-type: none; margin: 0;}
	    #jform_params_services li{width: 200px; float: left}
	    #jform_params_services input[type="checkbox"]{float: left}
	    #jform_params_services label{padding-left: 10px; float: left}
	    ';
	    $doc->addStyleDeclaration($style);
	    return true;
	}
	
	$doc->addScript('//yandex.st/share/share.js');
	
    }
    public function onAfterRender()
    {
	    $content = JResponse::getBody();

	    if (JString::strpos($content, '<!--yandex-share-module-->') === false) {
		    return true;
	    }

	    $regex = "#<!--yandex-share-module-->#s";

	    $content = preg_replace_callback($regex, array(&$this, '_replace'), $content);

	    JResponse::setBody($content);
	    return true;
    }

	protected function _replace(&$matches)
	{
	    $args['data-yashareType'] = $this->params->get('type', 'button');
	    $services = $this->params->get('services', array());
	    $args['data-yashareQuickServices'] = !empty($services) ? implode(',', $services) : 'yaru,vkontakte,facebook,twitter,odnoklassniki';
	    
	    $str = '';
	    foreach ($args as $key => $value)
	    {
		$str .= $key.'="'.$value.'"';
	    }
	    $text = '<div class="yashare-auto-init" data-yashareL10n="ru" '.$str.'></div>';
	    return $text;
	}
}