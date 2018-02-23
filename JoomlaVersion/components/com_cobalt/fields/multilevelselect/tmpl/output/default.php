<?php
/**
 * Cobalt by MintJoomla
 * a component for Joomla! 1.7 - 2.5 CMS (http://www.joomla.org)
 * Author Website: http://www.mintjoomla.com/
 * @copyright Copyright (C) 2012 MintJoomla (http://www.mintjoomla.com). All rights reserved.
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die();
$value = array();
?>
<?php if(count($this->value)) :?>
	<?php foreach ($this->value as $item) :

		$full = array();
		$title = array();
		foreach ($item as $id => $level)
		{
			$level = JText::_($level);
			$full[] = $level;
			if ($this->params->get('params.filter_enable'))
			{
				$tip = ($this->params->get('params.filter_tip') ? JText::sprintf($this->params->get('params.filter_tip'), '<b>' . $this->label . '</b>', "<b>".implode($this->params->get('params.separator', ' '), $full)."</b>") : NULL);
				switch ($this->params->get('params.filter_linkage'))
				{
					case 1 :
						$level = FilterHelper::filterLink('filter_' . $this->id, $id, $level, $this->type_id, $tip, $section);
						break;

					case 2 :
						$level = $level . ' ' . FilterHelper::filterButton('filter_' . $this->id, $id, $this->type_id, $tip, $section, $this->params->get('params.filter_icon', 'funnel-small.png'));
						break;
				}
			}
			$title[] = $level;
		}
		$value[] = implode($this->params->get('params.separator', ' '), $title);
		?>
	<?php endforeach;?>
<?php endif;?>

<?php if(count($value) == 1):?>
	<?php echo $value[0];?>
<?php elseif(count($value) > 1):?>
	<ul>
	  <li><?php echo implode('</li><li>', $value);?></li>
	</ul>
<?php endif;?>