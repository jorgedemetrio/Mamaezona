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

// Security check to ensure this file is being included by a parent file.
defined('_JEXEC') || die;

class WbampModel_Assetscollector
{
	private $_scripts   = array();
	private $_templates = array();
	private $_styles    = array();

	static $instance = null;

	/**
	 * Singleton.
	 *
	 * @return WbampModel_Assetscollector
	 */
	public static function getInstance()
	{
		if (is_null(self::$instance))
		{
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Add an amp tag handler script definition
	 * to the list of scripts to load in the page
	 *
	 * @param array $scripts
	 */
	public function addScripts($scripts)
	{
		$this->_scripts = array_merge($this->_scripts, (array) $scripts);

		return $this;
	}

	/**
	 * Removes a script from the script list. Used by some user-set tags
	 * to remove scripts on a page by page basis.
	 *
	 * @param string $scriptKey Unique id for the script to remove
	 */
	public function removeScript($scriptKey)
	{
		if (array_key_exists($scriptKey, $this->_scripts))
		{
			unset($this->_scripts[$scriptKey]);
		}
	}

	/**
	 * Add an amp tag handler template definition
	 * to the list of template scripts to load in the page
	 *
	 * @param $scripts
	 */
	public function addTemplates($templates)
	{
		$this->_templates = array_merge($this->_templates, (array) $templates);

		return $this;
	}

	/**
	 * Collect all scripts added by renderer or postprocessor
	 *
	 * @return array
	 */
	public function getScripts()
	{
		return $this->_scripts;
	}

	/**
	 * Collect all template scripts added by renderer or postprocessor
	 *
	 * @return array
	 */
	public function getTemplates()
	{
		return $this->_templates;
	}

	/**
	 * Add an amp tag handler style definition
	 * to the list of styles to load in the page
	 *
	 * @param array $style
	 */
	public function addStyle($style)
	{

		if (!empty($style) && !in_array($style, $this->_styles))
		{
			$this->_styles = array_merge($this->_styles, (array) $style);
		}

		return $this;
	}

	/**
	 * Getter for collected CSS styles
	 *
	 * @return array
	 */
	public function getStyles()
	{
		return $this->_styles;
	}
}
