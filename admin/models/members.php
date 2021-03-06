<?php
// *********************************************************************//
// Project      : jSchuetze for Joomla                                  //
// @package     : com_jSchuetze                                         //
// @file        : admin/models/members.php                              //
// @implements  : Class jSchuetzeModelMembers                           //
// @description : Model for the DB-Manipulation of the                  //
//                jSchuetze-Members-List                                //
// Version      : 2.1.0                                                 //
// *********************************************************************//
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted Access' );
jimport( 'joomla.application.component.modellist' );

class jSchuetzeModelMembers extends JModelList
{
    /**
     * Constructor.
     *
     * @param array	An optional associative array of configuration settings.
     * @see		JController
     * @since	1.6
     */
    public function __construct($config = array())
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array('id', 'name', 'vorname', 'memberstatus', 'beitritt', 'published', 'member.ordering', 'rang', 'funktion', 'funktion_seit', 'funktion_bis', 'scet_mail_notification');
        }
        parent::__construct($config);
    }

   /**
    * Returns the query
    * @return string The query to be used to retrieve the rows from the database
    */
   protected function getListQuery()
   {
        $db    = JFactory::getDBO();
        $query = $db->getQuery(true);

        // Select some fields
        $query->select('member.id AS id, member.name AS name, member.vorname AS vorname, member.ort AS ort, member.beitritt AS beitritt, member.published AS published, member.ordering as ordering, rank.name as rang, funktion.name AS funktion, memberrank.funktion_seit, memberrank.funktion_bis, scet_mail_notification, users.username as juser, status.name as memberstatus');
        $query->from('#__jschuetze_mitglieder    AS member');
        $query->join('LEFT', '#__jschuetze_memberranks AS memberrank ON (memberrank.fk_mitglied = member.id)');
        $query->join('LEFT', '#__jschuetze_titel       AS rank       ON (memberrank.fk_funktion = rank.id)');
        $query->join('LEFT', '#__jschuetze_titel       AS funktion   ON (member.fk_funktion     = funktion.id)');
        $query->join('LEFT', '#__jschuetze_status      AS status     ON (member.fk_status       = status.id)');
        $query->join('LEFT', '#__users                 AS users      ON (member.fk_juser        = users.id)');
        $query->group('member.name, member.vorname, member.strasse, member.beitritt');

        // Filter by search in title
        $search = $this->getState('filter.search');
        if (!empty($search))
        {
           if (stripos($search, 'id:') === 0)
           {
              $query->where('id = ' . (int) substr($search, 3));
           }
           else
           {
              $search = $db->quote('%' . $db->escape($search, true) . '%');
              $query->where('(member.name  LIKE ' . $search . ')');
           }
        }

        // Filter by published state
        $published = $this->getState('filter.state');
        if (is_numeric($published)) {
            $query->where('member.published = '.(int) $published);
        } else if ($published == '') {
            $query->where('(member.published IN (0, 1))');
        }

        //Add the list ordering clause.
        $orderCol  = $this->state->get('list.ordering');
        $orderDirn = $this->state->get('list.direction');
        if (empty($orderCol)){
            $orderCol  = 'member.ordering';
            $orderDirn = 'asc';
        }
        $query->order($db->escape($orderCol.' '.$orderDirn));

        return $query;
   }


    protected function populateState($ordering = null, $direction = null)
    {
        $search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
        $this->setState('filter.search', $search);

        $state = $this->getUserStateFromRequest($this->context.'.filter.state', 'filter_state', '', 'string');
        $this->setState('filter.state', $state);

        // List state information.
        parent::populateState('member.ordering', 'asc');
    }

}
?>