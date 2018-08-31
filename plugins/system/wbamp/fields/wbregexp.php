<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Form
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;

/**
 * Form Field class for the Joomla Platform.
 * Supports a multi line area for entry of plain text
 *
 * @link   http://www.w3.org/TR/html-markup/textarea.html#textarea
 * @since  11.1
 */
class JFormFieldWbregexp extends JFormFieldTextarea
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  11.1
	 */
	protected $type = 'wbregexp';

	/**
	 * Method to get the textarea field input markup.
	 * Use the rows and columns attributes to specify the dimensions of the area.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   11.1
	 */
	protected function getInput()
	{
		if (!class_exists('WbampModel_Config'))
		{
			JFactory::getApplication()->enqueueMessage('Cannot find WbampModel_Config class. Our shLib system plugin is probably not enabled. Make sure it is enabled, and located <strong>before</strong> the wbAMP system plugin.');
		}
		if (empty($this->value) && class_exists('WbampModel_Config'))
		{
			$config = new WbampModel_Config();
			$this->value = $config->defaulCleanupRegexp;
		}

		return parent::getInput();
	}
}
