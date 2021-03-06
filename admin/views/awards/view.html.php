<?php
// *********************************************************************//
// Project      : jSchuetze for Joomla                                  //
// @package     : com_jSchuetze                                         //
// @file        : admin/views/awards/view.html.php                      //
// @implements  : Class jSchuetzeViewAwards                             //
// @description : Main-entry for the awards-ListView                    //
// Version      : 2.0.0                                                 //
// *********************************************************************//
// no direct access to this file
defined('_JEXEC') or die( 'Restricted Access' );
jimport('joomla.application.component.view');

class jSchuetzeViewAwards extends JViewLegacy
{
    function display($tpl = null)
    {
        // Get data from the model
        $this->pagination = $this->get( 'Pagination' );
        $this->items	  = $this->get( 'Items' );
        $this->state      = $this->get( 'State' );

        // Get order state
        $this->listOrder = $this->escape($this->state->get( 'list.ordering'  ));
        $this->listDirn  = $this->escape($this->state->get( 'list.direction' ));

        // Add Toolbar to View
        jschuetzeHelper::addSubmenu('awards');
        $this-> addToolbar();
        $this->sidebar = JHtmlSidebar::render();

        parent::display($tpl);
    }

    function addToolbar()
    {
        // Set Headline
        JHtml::stylesheet('com_schuetze/views.css', array(), true, false, false);
        JToolBarHelper::title(   JText::_( 'COM_JSCHUETZE_HEAD_AWARDS_MANAGER' ), 'jschuetze' );
        // Toolbar-Buttons
        JToolBarHelper::addNew('award.add');
        JToolBarHelper::editList('award.edit');
        JToolBarHelper::deleteList('COM_JSCHUETZE_DELETE_QUESTION', 'awards.delete');
        JToolBarHelper::divider();
        JToolBarHelper::publishList('awards.publish');
        JToolBarHelper::unpublishList('awards.unpublish');

       JHtmlSidebar::setAction('index.php?option=com_jschuetze');

       JHtmlSidebar::addFilter(
          JText::_('JOPTION_SELECT_PUBLISHED'),
          'filter_state',
          JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.state'), true)
       );

    }

   protected function getSortFields()
   {
      return array(
         'ordering' => JText::_('JGRID_HEADING_ORDERING'),
         'published' => JText::_('JSTATUS'),
         'id' => JText::_('JGRID_HEADING_ID')
      );
   }

}
?>