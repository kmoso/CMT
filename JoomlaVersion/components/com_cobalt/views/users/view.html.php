<?php
/**
 * Cobalt by MintJoomla
 * a component for Joomla! 1.7 - 2.5 CMS (http://www.joomla.org)
 * Author Website: http://www.mintjoomla.com/
 *
 * @copyright Copyright (C) 2012 MintJoomla (http://www.mintjoomla.com). All rights reserved.
 * @license   GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die();

jimport('joomla.application.component.view');

/**
 * View class for a list of users.
 *
 * @package        Joomla.Administrator
 * @subpackage     com_users
 * @since          1.6
 */
class CobaltViewUsers extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;

	/**
	 * Display the view
	 */
	public function display($tpl = NULL)
	{
		$app    = JFactory::getApplication();
		$user   = JFactory::getUser();
		$access = TRUE;
		$field = $app->input->get->get('field', FALSE);

		$this->items = $this->get('Items');
		$this->state = $this->get('State');

		if($type_id = $app->input->get->getInt('type_id', FALSE))
		{
			$type        = ItemsStore::getType($type_id);
			$tmpl_params = CTmpl::prepareTemplate('default_form_', 'properties.tmpl_articleform', $type->params);
			if(!in_array($tmpl_params->get('tmpl_core.form_show_user_id'), $user->getAuthorisedViewLevels()))
			{
				$access = FALSE;
			}
		}
		elseif(!empty($field) && $field != 'jform_user_id')
		{
			list($field_id, $record_id) = explode('_', $field);
			$record  = ItemsStore::getRecord($record_id);
			$field   = CobaltApi::getField($field_id, $record);
			$section = ItemsStore::getSection($record->section_id);

			if(
				!($record->user_id && $record->user_id == $user->get('id') && $field->params->get('params.manual_author')) &&
				!(in_array($field->params->get('params.manual_who'), $user->getAuthorisedViewLevels())) &&
				!(MECAccess::allowRestricted($user, $section))
			)
			{
				$access = FALSE;
			}

		}
		elseif(!MECAccess::isModerator($user->get('id'), $this->state->get('filter.section')))
		{
			$access = FALSE;
		}

		if(!$access)
		{
			JError::raise(E_WARNING, 403, JText::_('CERR_NOPAGEACCESS'));

			return;
		}

		$this->pagination = $this->get('Pagination');

		// Check for errors.
		if(count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));

			return FALSE;
		}

		parent::display($tpl);
	}

}
