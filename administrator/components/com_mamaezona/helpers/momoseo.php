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

/**
 * MomoSEO component helper.
 */
abstract class MomoseoHelper
{
	/**
	 *	Configure the Linkbar.
	 */
	public static function addSubmenu($submenu) 
	{
		JHtmlSidebar::addEntry(JText::_('MomoSEO'), 'index.php?option=com_momoseo&view=momoseositemap', $submenu == 'sitemap');


		// set some global property
		$document = JFactory::getDocument();
		if ($submenu == 'categories'){
			$document->setTitle(JText::_('Categories - MomoSEO'));
		}
	}

	/**
	 *	Get the actions
	 */
	public static function getActions($Id = 0)
	{
		jimport('joomla.access.access');

		$user	= JFactory::getUser();
		$result	= new JObject;

		if (empty($Id)){
			$assetName = 'com_momoseo';
		} else {
			$assetName = 'com_momoseo.message.'.(int) $Id;
		}

		$actions = JAccess::getActions('com_momoseo', 'component');

		foreach ($actions as $action){
			$result->set($action->name, $user->authorise($action->name, $assetName));
		}

		return $result;
	}
}
?>