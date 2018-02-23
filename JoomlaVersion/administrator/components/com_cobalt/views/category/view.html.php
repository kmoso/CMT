<?php
/**
 * Cobalt by MintJoomla
 * a component for Joomla! 1.7 - 2.5 CMS (http://www.joomla.org)
 * Author Website: http://www.mintjoomla.com/
 * @copyright Copyright (C) 2012 MintJoomla (http://www.mintjoomla.com). All rights reserved.
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * HTML View class for the Categories component
 *
 * @static
 * @package		Joomla.Administrator
 * @subpackage	com_categories
 */
class CobaltViewCategory extends JViewLegacy
{
	protected $form;
	protected $item;
	protected $state;
	protected $section;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		global $app;
		$this->section = JRequest::getInt('section_id');
		$this->state	= $this->get('State');
		$this->item		= $this->get('Item');
		$this->form		= $this->get('Form');

		$this->canDo = MRHelper::getActions ('category', $this->state->get ( 'category.id' ) );
		$this->params_groups = array (
		'params' => JText::_('FS_GENERAL'),
		'publishing-details' => JText::_('JGLOBAL_FIELDSET_PUBLISHING')
		);

		if (!$this->section) {
			JError::raiseWarning(100, JText::_('C_MSG_SELECTSECTIO'));
			//$app->redirect('index.php?option=com_cobalt&view=sections');

		}

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}


		$this->addToolbar();
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addToolbar()
	{
		// Avoid nonsense situation.
		if (!$this->section) {
			return;
		}
		JFactory::getApplication()->input->set('hidemainmenu', true);

		$user = JFactory::getUser ();
		$isNew		= ($this->item->id == 0);
		$checkedOut	= !($this->item->checked_out == 0 || $this->item->checked_out == $user->get('id'));

		JToolBarHelper::title ( ($isNew ? JText::_ ('CNEWCATEGORY') : JText::_ ('CEDITCATEGORY')), ($isNew ? 'category-add.png' : 'categories.png') );

		if (!$checkedOut)
		{
			JToolBarHelper::apply ( 'category.apply', 'JTOOLBAR_APPLY' );
			JToolBarHelper::save ( 'category.save', 'JTOOLBAR_SAVE' );
			JToolBarHelper::custom ( 'category.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false );
			if (! $isNew)
				JToolBarHelper::custom ( 'category.save2copy', 'save-copy.png', 'save-copy_f2.png', 'JTOOLBAR_SAVE_AS_COPY', false );
		}
		JToolBarHelper::cancel ( 'category.cancel', 'JTOOLBAR_CANCEL' );
		MRToolBar::helpW ( 'http://help.mintjoomla.com/cobalt/index.html?category.htm', 1000, 500 );
		JToolBarHelper::divider ();

	}
}
