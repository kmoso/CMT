<?php
/**
 * a component for Joomla! 1.7 - 2.5 CMS (http://www.joomla.org)
 * Author: Vadim Kozhukhov
 * Author Website: http://www.torbara.com/
 * @copyright Copyright (C) 2015 torbara (http://www.torbara.com). All rights reserved.
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die();

$Match_Date = trim( strip_tags($this->dates[0]) );
$Match_Date = explode(" ", $Match_Date); ?>

<span><?php echo $Match_Date[0]; ?></span>
<?php echo $Match_Date[1]; ?>
