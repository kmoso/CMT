<?php
/**
 * @copyright	Copyright (c) 2015 akFixsGmap. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

/**
 * System - akFixsGmap Plugin
 *
 * @package		Joomla.Plugin
 * @subpakage	akFixsGmap.akFixsGmap
 */
class plgSystemakFixsGmap extends JPlugin {

	/**
	 * Constructor.
	 *
	 * @param 	$subject
	 * @param	array $config
	 */
	function __construct(&$subject, $config = array()) {
		// call parent constructor
		parent::__construct($subject, $config);
	}
        
    function onAfterRender() {
        $text = JResponse::getBody();
        if(substr_count($text, "maps.googleapis.com/maps/api/js") > 1 ){
            //'<script src="//maps.googleapis.com/maps/api/js?language=en&region=GB&key=&sensor=true&libraries=weather,panoramio,visualization,places" type="text/javascript"></script>';
            $pattern = "/<script src=\"\/\/maps.googleapis.com\/maps\/api\/js(.*)\" type=\"text\/javascript\"><\/script>/";
            $replacement = '';
            $text = preg_replace($pattern, $replacement, $text);
        }
        JResponse::setBody($text);
    }
}