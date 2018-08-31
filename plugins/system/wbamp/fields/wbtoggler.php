<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Form
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;

JFormHelper::loadFieldClass('checkbox');

/**
 * Form Field class for the Joomla Platform.
 * Supports a multi line area for entry of plain text
 *
 * @link   http://www.w3.org/TR/html-markup/textarea.html#textarea
 * @since  11.1
 */
class JFormFieldWbtoggler extends JFormFieldCheckbox
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  11.1
	 */
	protected $type = 'wbtoggler';

	protected function getLabel()
	{
		return $this->renderToggler();
	}

	protected function getInput()
	{
		return '';
	}

	protected function renderToggler()
	{
		$html = parent::getInput();
		// add a class
		if (strpos($html, 'class="') === false)
		{
			$html = str_replace('<input', '<input class="wb-form-toggle"', $html);
		}
		else
		{
			$html = str_replace('class="', 'class="wb-form-toggle "', $html);
		}

		// add a label we can style
		$html .= '<label for="' . $this->id . '"><span class="wb-form-toggle-closed">'
			. JText::_('PLG_SYSTEM_WBAMP_SETTINGS_MORE') . '</span><span class="wb-form-toggle-opened">'
			. JText::_('PLG_SYSTEM_WBAMP_SETTINGS_LESS') . '</span></label>';

		return $html;
	}
}
