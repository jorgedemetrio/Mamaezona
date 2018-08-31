<?php
/*------------------------------------------------------------------------
# MomoSEO.php - MomoSEO Component
# ------------------------------------------------------------------------
# author    Jorge Demetrio
# copyright Copyright (C) 2015. All Rights Reserved
# license   GNU/GPL Version 2 || later - http://www.gnu.org/licenses/gpl-2.0.html
# website   www.alldreams.com.br
-------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') || die('Restricted access');

//define('TRADUZIR_CHAR_DE','�������������������� ');
//define('TRADUZIR_CHAR_POR','AEIOUYAEIOUAEIOUAONC-');

// Added for Joomla 3.0
if(!defined('DS')){
	define('DS',DIRECTORY_SEPARATOR);
}

if(!defined('VERSAO_MOMOSEO')){
	define('VERSAO_MOMOSEO','1.0');
}

// Set the component css/js
$document = JFactory::getDocument();


// Require helper file
JLoader::register('MomoseoHelper', dirname(__FILE__) . DS . 'helpers' . DS . 'momoseo.php');



// import joomla controller library
jimport('joomla.application.component.controller');

// Get an instance of the controller prefixed by Momoseo
$controller = JControllerLegacy::getInstance('Momoseo');

// Perform the request task
$controller->execute(JRequest::getCmd('task'));



// Redirect if set by the controller
$controller->redirect();
///templates/protostar/css/template.css

unset($document->_scripts[JURI::root(true) . '/media/system/js/mootools-more.js']);
