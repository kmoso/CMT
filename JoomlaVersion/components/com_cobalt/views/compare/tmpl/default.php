<?php
/**
 * Cobalt by MintJoomla
 * a component for Joomla! 1.7 - 2.5 CMS (http://www.joomla.org)
 * Author Website: http://www.mintjoomla.com/
 * @copyright Copyright (C) 2012 MintJoomla (http://www.mintjoomla.com). All rights reserved.
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

defined('_JEXEC') or die(); ?>
<h1 class="title"><?php echo JText::_('CCOMPAREVIEW') ?></h1>

<div class="controls controls-row">
	<div class="pull-right">
		<button class="btn" onclick="Cobalt.CleanCompare('<?php echo $this->back;?>', '<?php echo @$this->section->id ?>')">
			<?php echo JText::_('CCLEANCOMPARE') ?>
		</button>
	</div>
	<a href="<?php echo $this->back;?>" class="btn btn-primary">
		<?php echo HTMLFormatHelper::icon('arrow-180.png');  ?>
		<?php echo JText::_('CGOBACK') ?>
	</a>
</div>
<div class="clearfix"></div>

<br>	
<div id="compare">
	<?php echo $this->html; ?>
</div>
		
	
