<?php 
// *********************************************************************//
// Project      : jSchuetze for Joomla                                  //
// @package     : com_jSchuetze                                         //
// @file        : admin/models/lending.php                              //
// @implements  : Class jSchuetzeModellending                           //
// @description : Model for the DB-Manipulation of a single             //
//                jSchuetze-lending; not for the list                   //
// Version      : 2.1.0                                                 //
// *********************************************************************//
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted Access' ); 
jimport( 'joomla.application.component.modeladmin' );

class jSchuetzeModelLending extends JModelAdmin
{
    /**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param	type	The table type to instantiate
	 * @param	string	A prefix for the table class name. Optional.
	 * @param	array	Configuration array for model. Optional.
	 * @return	JTable	A database object
	 * @since	1.6
	 */
    public function getTable($type = 'lending', $prefix = 'jSchuetzeTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}
    
	/**
	 * Method to get the record form.
	 *
	 * @param	array	$data		Data for the form.
	 * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
	 * @return	mixed	A JForm object on success, false on failure
	 * @since	1.6
	 */
	public function getForm($data = array(), $loadData = true)
	{
        $form = $this->loadForm(
                'com_jschuetze.lending', 
                'lending', 
                 array('control' => 'jform', 'load_data' => $loadData));
        if (empty($form))
        {
            return false;
        }
     
        return $form;
	}	
     
    /**
     * Method to get the data that should be injected in the form.
     *
     * @return      mixed   The data for the form.
     * @since       1.6
     */
    protected function loadFormData()
    {
        // Check the session for previously entered form data.
        $data = JFactory::getApplication()->getUserState('com_jschuetze.edit.lending.data', array());
        if (empty($data))
        {
            $data = $this->getItem();
            if ($data->ausgabe == 0) {
            	$data->ausgabe = '';
            }            
            if ($data->rueckgabe == 0) {
            	$data->rueckgabe = '';
            }            
            if ($data->ordering == 0)
            {
                $data->ordering = $this->getNextOrderingNr();
            }
        }
        return $data;	
    }
    
    function getNextOrderingNr()
    {
        $db    = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('max(ordering)');
        $query->from('#__jschuetze_lending');
		$db->setQuery( $query );
		$maxOrdering = $db->loadResult();

		return ($maxOrdering + 1)  ;
	}
    
    public function returnOneItem($id)
    {
		$db	=& JFactory::getDBO();
        $query  = 'UPDATE #__jschuetze_lending';
		$query .= ' SET anzahl_rueck = anzahl_rueck + 1,';
		$query .= ' rueckgabe = CURRENT_DATE';
		$query .= ' WHERE id = '. $id;
		$db->setQuery( $query );
        if (!$db->query()) {
            return -1;
        };
        
        $query = $db->getQuery(true);
        $query->select('fk_fundus');
        $query->from('#__jschuetze_lending');
        $query->where('id = '.$id);
        $db->setQuery( $query );
        $fk_asset = $db->loadResult();
        
        $query  = 'UPDATE #__jschuetze_fundus';
		$query .= ' SET bestand = bestand + 1';
		$query .= ' WHERE id = '. $fk_asset;
		$db->setQuery( $query );
        if (!$db->query()) {
            return -1;
        };

        return $db->getAffectedRows();
    }
  
    public function updateBestand($fk_fundus, $differenz) {
        $db	=& JFactory::getDBO();
        $query  = 'UPDATE #__jschuetze_fundus';
		$query .= ' SET bestand = bestand - '.$differenz;
		$query .= ' WHERE id = '. $fk_fundus;
		$db->setQuery( $query );
        if (!$db->query()) {
            return -1;
        };
        return 0;
    }
  
    public function save($array)
    {
        if ($array[id] == 0) {
            // Neuer Datensatz !
            $result = $this->updateBestand($array[fk_fundus], $array[anzahl_aus]);
        }
      	if ($array['ausgabe'] == '') {
       		$array['ausgabe'] = 0;
       	}else {
       		$array['ausgabe'] = JFactory::getDate($array['ausgabe'], 'UTC')->toSQL();
       	}
       	if ($array['rueckgabe'] == '') {
      		$array['rueckgabe'] = 0;
       	}else {
       		$array['rueckgabe'] = JFactory::getDate($array['rueckgabe'], 'UTC')->toSQL();
       	}
        
        return parent::save($array);
    }

    
}
?>