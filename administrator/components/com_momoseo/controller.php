<?php
/*
 * ------------------------------------------------------------------------
 * # controller.php - Angel Girls Component
 * # ------------------------------------------------------------------------
 * # author Jorge Demetrio
 * # copyright Copyright (C) 2015. All Rights Reserved
 * # license GNU/GPL Version 3 || later - http://www.gnu.org/licenses/gpl-3.0.html
 * # website www.alldreams.com.br
 * -------------------------------------------------------------------------
 */

// No direct access to this file
defined ( '_JEXEC' ) || die ( 'Restricted access' );

// import Joomla controller library
jimport ( 'joomla.application.component.controller' );
jimport ( 'joomla.filesystem.file' );
jimport ( 'joomla.filesystem.folder' );
jimport ('joomla.application.component.helper');

/**
 * General Controller of MomoSEO component
 */
class MomoseoController extends JControllerLegacy {
	/**
	 * display task
	 *
	 * @return void
	 */
	function display($cachable = false, $urlparams = false) {
		// set default view if not set
		JRequest::setVar ( 'view', JRequest::getCmd ( 'view', 'Momoseo' ) );
		
		// call parent behavior
		parent::display ( $cachable );
		
		// set view
		$view = strtolower ( JRequest::getVar ( 'view' ) );
		
		// Set the submenu
		MomoseoHelper::addSubmenu ( $view );
	}
	

}
?>