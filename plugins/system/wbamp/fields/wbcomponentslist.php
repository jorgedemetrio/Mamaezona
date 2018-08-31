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

defined('_JEXEC') or die();

JFormHelper::loadFieldClass('list');

/**
 * Select list with installed and published components
 *
 */
class JFormFieldWbcomponentslist extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 */
	protected $type = 'wbcomponentslist';

	private $_items = null;

	private $_hiddenComponents = array(
		'com_admin',
		'com_ajax',
		'com_associations',
		'com_banners',
		'com_cache',
		'com_categories',
		'com_checkin',
		'com_config',
		'com_contenthistory',
		'com_cpanel',
		'com_fields',
		'com_finder',
		'com_installer',
		'com_joomlaupdate',
		'com_languages',
		'com_login',
		'com_mailto',
		'com_media',
		'com_menus',
		'com_messages',
		'com_modules',
		'com_newsfeeds',
		'com_plugins',
		'com_postinstall',
		'com_redirect',
		'com_search',
		'com_templates',
		'com_users',
		'com_wrapper',
		/* 3rd-party */
		'com_acymailing',
		'com_akeeba',
		'com_jce',
		'com_jedchecker',
		'com_josetta',
		'com_sh404sef',
		'sh404SEF',
		'sh404sef',
		'com_widgetkit'
	);

	protected function getOptions()
	{
		$options = array();

		$items = array();
		$items = array_merge($items, (array) $this->getItems());
		foreach ($items as $key => $item)
		{
			$tmp = array(
				'value' => $key,
				'text'  => $item['name']
			);
			$options[] = (object) $tmp;
		}

		$default = array(
			'value' => '',
			'text'  => JText::_('PLG_SYSTEM_WBAMP_RULES_SELECT_COMPONENT')
		);
		array_unshift($options, $default);
		reset($options);

		/**
		 * Filter the list of components to be made available for user to select
		 * for AMPlification.
		 *
		 * @api
		 * @package wbAMP\filter\options
		 * @var wbamp_admin_components_list
		 * @since   1.9.2
		 *
		 * @param array $options Array of (value => '',text => '') records, holding a select list of components.
		 *
		 * @return string
		 */
		$options = ShlHook::filter(
			'wbamp_admin_components_list',
			$options
		);

		return $options;
	}

	private function getItems()
	{
		if (is_null($this->_items))
		{
			try
			{
				$this->_items = array();
				$items = ShlDbHelper::selectAssocList(
					'#__extensions',
					array('name', 'element'),
					array('type' => 'component', 'enabled' => 1),
					$aWhereData = array(), $orderBy = array(), $offset = 0, $lines = 0,
					$key = 'element',
					$opType = ''
				);

				// normalize and remove common extensions:
				if (!empty($items))
				{
					foreach ($items as $key => $value)
					{
						$key = strtolower($key);
						if (!in_array($key, $this->_hiddenComponents))
						{
							$this->_items[$key] = $value;
						}
					}
				}
			}
			catch (Exception $e)
			{
				ShlSystem_Log::error('wbamp', __METHOD__ . ' ' . $e->getMessage());
			}
		}

		return $this->_items;
	}
}
