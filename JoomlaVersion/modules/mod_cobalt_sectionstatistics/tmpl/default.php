<?php
/**
 * Cobalt by MintJoomla
 * a component for Joomla! 1.7 - 2.5 CMS (http://www.joomla.org)
 * Author Website: http://www.mintjoomla.com/
 * @copyright Copyright (C) 2012 MintJoomla (http://www.mintjoomla.com). All rights reserved.
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die('Restricted access'); ?>
<ul class="unstyled">
<?php foreach ($data as $title => $value):?>
<li><?php echo JText::_(strtoupper($params->get($title))) .": <b><big>$value</big></b>";?></li>
<?php endforeach;?>
</ul>
<div style="clear: both;"></div>