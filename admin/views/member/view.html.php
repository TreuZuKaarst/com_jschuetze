<?php
// *********************************************************************//
// Project      : jSchuetze for Joomla                                  //
// @package     : com_jSchuetze                                         //
// @file        : admin/views/member/view.html.php                      //
// @implements  : Class jSchuetzeViewMember                             //
// @description : Main-entry for the single Member-View                 //
// Version      : 1.0.0                                                 //
// *********************************************************************//
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted Access!');
jimport( 'joomla.application.component.view' );

class jSchuetzeViewMember extends JViewLegacy
{
	/**
	 * display method of ToDo view
	 * @return void
	 **/
	function display($tpl = null)
	{
		$this->form = $this->get('Form');
        $this->item = $this->get('Item');
		$isNew	    = ($this->item->id == 0);

        $this->AddToolBar($isNew);
        
		parent::display($tpl);
	}
    
    protected function AddToolBar($isNew)
    {
		$text = $isNew ? JText::_( 'COM_JSCHUETZE_NEW' ) : JText::_( 'COM_JSCHUETZE_EDIT' );
        JHtml::stylesheet('com_jschuetze/views.css', array(), true, false, false);
		JToolBarHelper::title(   JText::_( 'COM_JSCHUETZE_HEAD_MEMBERS_MANAGER' ).': <small>[ ' . $text.' ]</small>' , 'jschuetze');
		JToolBarHelper::apply('member.apply');
        JToolBarHelper::save2new('member.save2new');
		JToolBarHelper::save('member.save');
		JToolBarHelper::cancel('member.cancel', $isNew ? 'JTOOLBAR_CANCEL' : 'JTOOLBAR_CLOSE');
    }
    
    
}
