<?php// *********************************************************************//// Project      : jSchuetze for Joomla                                  //// @package     : com_jSchuetze                                         //// @file        : admin/tables/memberaward.php                          //// @implements  : Class jSchuetzeTableMemberaward                       //// @description : Table-Structure-Class of the Title-Table              //// Version      : 1.0.0                                                 //// *********************************************************************//// no direct access to this filedefined('_JEXEC') or die('Restricted access');class jSchuetzeTableMemberaward extends JTable{	/**	 * Constructor	 *	 * @param object Database connector object	 */	function __construct(& $_db) {		parent::__construct('#__jschuetze_mitgliedsausz', 'id', $_db);	}}?>