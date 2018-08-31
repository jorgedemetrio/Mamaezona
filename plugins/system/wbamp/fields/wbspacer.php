<?php
/**
 * wbAMP - Accelerated Mobile Pages for Joomla!
 *
 * @author       Yannick Gaultier
 * @copyright    (c) Yannick Gaultier - Weeblr llc - 2017
 * @package      wbAmp
 * @license      http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @version      1.12.0.790
 * @date        2018-05-16
 */

defined('_JEXEC') or die;

/**
 * Form Field class for the Joomla Platform.
 * Supports a generic list of options.
 *
 * @package     Joomla.Platform
 * @subpackage  Form
 */
class JFormFieldWbspacer extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 */
	protected $type = 'wbspacer';

	public function getInput()
	{
		ShlHtml_Manager::getInstance()->addStylesheet('wbamp_be', array('files_path' => '/media/plg_wbamp/assets/default', 'assets_bundling' => false));
		$class = 'wbspacer';
		if (!empty($this->element['class']))
		{
			$class .= ' ' . $this->element['class'];
		}
		$html = array();
		$html[] = '<h3 class="' . $class . '">' . JText::_($this->element['label']) . '</h3>';

		return implode("\n", $html);
	}

	public function getLabel()
	{
		return '';
	}
}
