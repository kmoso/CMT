<?php
/**
* @package   yoo_master2
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

// get warp
$warp = require(__DIR__.'/warp.php');

// Removed from here and added in the Extensions scripts include_once('analytics_tracking.php');

// load main theme file, located in /layouts/theme.php
echo $warp['template']->render('theme');

