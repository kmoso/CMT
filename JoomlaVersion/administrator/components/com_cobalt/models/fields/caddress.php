<?php
/**
 * Cobalt by MintJoomla
 * a component for Joomla! 1.7 - 2.5 CMS (http://www.joomla.org)
 * Author Website: http://www.mintjoomla.com/
 * @copyright Copyright (C) 2012 MintJoomla (http://www.mintjoomla.com). All rights reserved.
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

defined('_JEXEC') or die();

jimport('joomla.html.html');
jimport('joomla.form.formfield');
jimport( 'joomla.filesystem.folder' );
jimport( 'joomla.filesystem.file'   );

class JFormFieldCaddress extends JFormField
{
	public $type = 'Caddress';

	public function getInput()
	{
		include_once JPATH_ROOT. DIRECTORY_SEPARATOR .'components/com_cobalt/fields/geo/geo.php';

		$fields = JFormFieldCgeo::getAddressFields();
		$patern = '<tr class="row%d"><td nowrap="nowrap">%s</td><td>%s</td>
		<td nowrap="nowrap"><fieldset class="radio">%s</fieldset></td>
		<td nowrap="nowrap"><fieldset class="radio">%s</fieldset></td>
		</tr>';
		$req[] = JHtml::_('select.option', '0', JText::_('CNO'));
		$req[] = JHtml::_('select.option', '1', JText::_('CYES'));

		$html[] = '<table class="table table-striped">';
		$html[] = '<thead><tr><th>'.JText::_('CFIELD').'</th>';
		$html[] = '<th width="1%">'.JText::_('CSHOW').'</th>';
		$html[] = '<th width="10%">'.JText::_('CREQUIRE').'</th>';
		$html[] = '<th width="10%">'.JText::_('CICON').'</th>';
		$html[] = '</tr></thead><tbody>';

		$showopt = $this->_getShowOpt();


		foreach ($fields AS $name => $field)
		{
			$data = new JRegistry($field);
			$show = JHtml::_('select.genericlist', $showopt, $this->name.'['.$name.'][show]', 'style="max-width:100px;"', 'value', 'text', (isset($this->value->$name->show) ? (int)$this->value->$name->show : 0));
			$require = JHtml::_('Cobalt.yesno', $req, $this->name.'['.$name.'][req]', (isset($this->value->$name->req) ? $this->value->$name->req : 0));
			$icon = JHtml::_('Cobalt.yesno', $req, $this->name.'['.$name.'][icon]', (isset($this->value->$name->icon) ? $this->value->$name->icon : 1));

			$html[] = sprintf($patern, ($i = 1 - @$i), $data->get('label'), $show, $require, $icon);
		}
		//JHtmlSelect::radiolist($data, $name);
		$html[] = '</tbody></table>';

		return implode("\n", $html);
	}

	private function _getShowOpt()
	{
		$opt[] = JHtml::_('select.option', '0', JText::_('CNOWHERE'));
		$opt[] = JHtml::_('select.option', '1', JText::_('CARTLIST'));
		$opt[] = JHtml::_('select.option', '2', JText::_('CARTFULL'));
		$opt[] = JHtml::_('select.option', '3', JText::_('CBOTH'));

		return $opt;
	}
}
