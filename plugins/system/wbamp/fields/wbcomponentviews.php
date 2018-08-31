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
 * Select list with views matching a component selected in another list
 *
 */
class JFormFieldWbcomponentviews extends JFormFieldList
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

	/**
	 * Override just to add a class
	 */
	protected function getInput()
	{
		// add a class for our js
		$this->class = strpos($this->class, 'wb-dynamic-list') === false ? $this->class . ' wb-dynamic-list' : $this->class;

		$input = str_replace(
			'<select',
			'<select data-data_source="' . $this->formControl . '_' . $this->group . '_' . $this->getAttribute('data_source', '') . '"',
			parent::getInput()
		);

		return $input;
	}

	protected function getOptions()
	{
		static $componentsListInjected = false;

		$options = array();

		// build the components list
		// @TODO: share with wbcomponentslist
		$items = array();
		$items = array_merge($items, (array) $this->getItems());

		// build the list of views per component
		// and inject that in javascript, can be then used to
		// display a dynamic list of view per component
		if (!$componentsListInjected)
		{
			$viewsPerComponent = $this->getViewsPerComponent($items);
			$selected['#' . $this->formControl . '_' . $this->group . '_' . $this->getAttribute('data_source', '')] = (array) $this->value;
			$js = '
window.weeblrApp = window.weeblrApp || {};
window.weeblrApp.wbAMPViewsPerComponent = window.weeblrApp.wbAMPViewsPerComponent || {};
window.weeblrApp.wbAMPViewsPerComponent["components"] = ' . json_encode($viewsPerComponent['components']) . ';
';
			JFactory::getDocument()->addScriptDeclaration($js);
			ShlHtml_Manager::getInstance()->addScript(
				'settings',
				array(
					'files_path' => '/media/plg_wbamp/assets/default'
				)
			);
			$componentsListInjected = true;
		}

		// inject selected views:
		$js = 'window.weeblrApp.wbAMPViewsPerComponent["selected"] = window.weeblrApp.wbAMPViewsPerComponent["selected"] || {}; 
window.weeblrApp.wbAMPViewsPerComponent["selected"]["' . '#' . $this->formControl . '_' . $this->group . '_' . $this->getAttribute('data_source', '') . '"] = ' . json_encode((array) $this->value) . ';
';
		JFactory::getDocument()->addScriptDeclaration($js);

		return $options;
	}

	private function getItems()
	{
		if (is_null($this->_items))
		{
			try
			{
				$this->_items = array();
				$items = ShlDbHelper::selectColumn(
					'#__extensions',
					array('element'),
					array('type' => 'component', 'enabled' => 1)
				);

				// normalize and remove common extensions:
				if (!empty($items))
				{
					foreach ($items as $key => $value)
					{
						$value = strtolower($value);
						if (!in_array($value, $this->_hiddenComponents))
						{
							$this->_items[$key] = $value;
						}
					}

					$this->_items = array_unique($this->_items);
				}
			}
			catch (Exception $e)
			{
				ShlSystem_Log::error('wbamp', __METHOD__ . ' ' . $e->getMessage());
			}
		}

		return $this->_items;
	}

	/**
	 * Build a list of views per component found on the site.
	 *
	 * @param array $components
	 *
	 * @return array
	 */
	private function getViewsPerComponent($components)
	{
		jimport('joomla.filesystem.folder');
		$viewsPerComponent = array(
			'components' => array(),
			'selected'   => array()
		);
		foreach ($components as $component)
		{
			$path = JPATH_ROOT . '/components/' . $component;
			if (file_exists($path . '/views'))
			{
				$viewFolders = JFolder::folders($path . '/views');
				if (!empty($viewFolders))
				{
					foreach ($viewFolders as $viewName)
					{
						$viewsPerComponent['components'][$component] = empty($viewsPerComponent['components'][$component]) ? array() : $viewsPerComponent['components'][$component];
						$viewsPerComponent['components'][$component][] = array(
							'name'         => $viewName,
							'display_name' => ucfirst($viewName)
						);
					}
				}
			}
		}

		/**
		 * Filter the list of views per components to be made available for user to select
		 * for AMPlification.
		 *
		 * There are 2 lists involved:
		 *
		 * $list = array(
		 *   'components' => array(
		 *      'com_content' => array(
		 *          'article', 'category', 'categories', ....
		 *          )
		 *   ),
		 *   'selected' => array(
		 *      '#jform_group_fieldname' => 'article'
		 *   )
		 * )
		 *
		 * In the "selected" part, the form html id is used to index the array.
		 *
		 * @api
		 * @package wbAMP\filter\options
		 * @var wbamp_admin_views_per_components_list
		 * @since   1.9.2
		 *
		 * @param array $options Array of (value => '',text => '') records, holding a select list of components.
		 *
		 * @return string
		 */
		$viewsPerComponent = ShlHook::filter(
			'wbamp_admin_views_per_components_list',
			$viewsPerComponent
		);

		return $viewsPerComponent;
	}
}
