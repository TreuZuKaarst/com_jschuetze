<?php// *************************************************************************//// Project      : jSchuetze for Joomla                                      //// @package     : com_jschuetze                                             //// @file        : /admin/controllers/titles.php                             //// @implements  : Class jSchuetzeControllerTitles                           //// @description : Controller for editing the titles-List                    //// Version      : 1.0.0                                                     //// *************************************************************************//// No direct access.defined('_JEXEC') or die;jimport('joomla.application.component.controlleradmin');// Eventlist controller class.class jSchuetzeControllerTitles extends JControllerAdmin{	// constructor (registers additional tasks to methods)	// @return void	function __construct()	{		parent::__construct();    }    	/**	 * Proxy for getModel.	 * @since	1.6	 */	public function getModel($name = 'Title', $prefix = 'jSchuetzeModel', 							 $config = array('ignore_request' => true))	{		$model = parent::getModel($name, $prefix, $config);		return $model;	}	  }