<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.application.component.view');

class JshoppingViewResize extends JViewLegacy
{
    function display($tpl = null){
        
        JToolBarHelper::title( _JSHOP_RESIZE, 'generic.png' ); 
        parent::display($tpl);
	}
}
?>