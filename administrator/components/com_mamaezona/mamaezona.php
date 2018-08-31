<?php
/*------------------------------------------------------------------------
# momoseo.php - Momo SEO Component
# ------------------------------------------------------------------------
# author    Jorge Demetrio
# copyright Copyright (C) 2015. All Rights Reserved
# license   GNU/GPL Version 3 || later - http://www.gnu.org/licenses/gpl-3.0.html
# website   www.AllDreams.com.br
-------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') || die('Restricted access');

// Added for Joomla 3.0
if(!defined('DS')){
	define('DS',DIRECTORY_SEPARATOR);
}

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_momoseo')){
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

// Load cms libraries
JLoader::registerPrefix('J', JPATH_PLATFORM . '/cms');
// Load joomla libraries without overwrite
JLoader::registerPrefix('J', JPATH_PLATFORM . '/joomla',false);

// require helper files
JLoader::register('MomoseoHelper', dirname(__FILE__) . DS . 'helpers' . DS . 'momoseo.php');

// import joomla controller library
jimport('joomla.application.component.controller');




// Get an instance of the controller prefixed by momoseo
$controller = JControllerLegacy::getInstance('Momoseo');


$controller->execute(JRequest::getCmd('task'));

// Redirect if set by the controller
$controller->redirect();

?>