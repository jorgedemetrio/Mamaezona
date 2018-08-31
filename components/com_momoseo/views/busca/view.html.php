<?php
/*------------------------------------------------------------------------
# view.html.php - MomoSEO Component
# ------------------------------------------------------------------------
# author    Jorge Demetrio
# copyright Copyright (C) 2015. All Rights Reserved
# license   GNU/GPL Version 3 || later - http://www.gnu.org/licenses/gpl-3.0.html
# website   www.alldreams.com.br
-------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') || die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

/**
 * Temas View
 */
class MomoseoViewbusca extends JViewLegacy
{
	/**
	 * Temas view display method
	 * @return void
	 */
	function display($tpl = null) 
	{


		// Set the document
		$this->setDocument();
		
	

		// Set the toolbar
		$this->addToolBar();
		

		// Display the template
		parent::display($tpl);


	}

	/**
	 * Setting the toolbar
	 */
	protected function addToolBar() 
	{
		$document = JFactory::getDocument();
		$pathway = JFactory::getApplication()->getPathway();


		$document->setMetadata('APPLICATION-NAME','Momo SEO Busca');
		

		$busca = JRequest::getVar('q');
		$descricao = 'Buscando por ' . $busca;
		$pathway->addItem($descricao,'');
		$document->setTitle($descricao);
		$document->setDescription($descricao);
		$document->setMetadata('Keywords', 'buscar '.$busca.',localizar '.$busca.','.str_replace(' ', ',',$busca));

	}

	/**
	 * Method to set up the document properties
	 *
	 *
	 * @return void
	 */
	protected function setDocument() 
	{
	}
}
?>