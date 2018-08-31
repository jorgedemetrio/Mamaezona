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

// no direct access
defined('_JEXEC') or die;

class WbampModel_Renderer
{
	const AMP_SCRIPTS_VERSION = 0.1;
	const AMP_SCRIPTS_PATTERN = 'https://cdn.ampproject.org/v0/amp-%s-%s.js';

	private $_request       = null;
	private $_isAmpPage     = null;
	private $_postProcessor = null;
	private $_manager       = null;

	private $_userSetTags    = array(
		'doc_type',
		'image',
		'author',
		'publisher',
		'date_published',
		'date_modified',
		'no_ads'
	);
	private $_extractedImage = null;

	private $_userSetData = array();

	/**
	 * Stores request data
	 *
	 */
	public function __construct($request, $manager, $isAmpPage)
	{
		$this->_request = $request;
		$this->_manager = $manager;
		$this->_isAmpPage = $isAmpPage;
	}

	/**
	 * Builds an array of all data required to display the
	 * AMP page. Suitable for use with JLayouts.
	 *
	 * @return array
	 */
	public function getData()
	{
		$data = array();
		$document = JFactory::getDocument();

		$data['params'] = WbampHelper_Runtime::$params;
		$data['joomla_config'] = WbampHelper_Runtime::$joomlaConfig;

		// essentially so that layouts can add scripts to the page
		$data['renderer'] = $this;
		$data['assets_collector'] = WbampModel_Assetscollector::getInstance();

		// get the user set content max width
		/**
		 * Filter the content max width, normally set through the Taylor theme.
		 *
		 * @api
		 * @package wbAMP\filter\data
		 * @var wbamp_content_max_width
		 * @since   1.9.3
		 *
		 * @param int $contentMaxWidth An integer representing the content maximum width in pixels.
		 *
		 * @return int
		 */
		$data['content_max_width'] = ShlHook::filter(
			'wbamp_content_max_width',
			WbampHelper_Runtime::CONTENT_MAX_WIDTH
		);

		// joomla-rendered content needs to be reprocessed
		$this->_postProcessor = new WbampModel_Postprocess(
			$this->_isAmpPage,
			array(
				'content_max_width' => $data['content_max_width']
			)
		);
		$data['post_processor'] = $this->_postProcessor;

		// default meta data set
		$data['metadata'] = array(
			'title'        => $document->getTitle(),
			'description'  => $document->getDescription(),
			'keywords'     => $document->getMetaData('keywords'),
			'robots'       => '',
			'ogp'          => '',
			'tcards'       => '',
			'tweet_via'    => $data['params']->get('tweet_via', ''),
			'publisher_id' => $data['params']->get('publisher_id')
		);

		// sh404SEF integration
		$data['sh404sef_custom_data'] = array();
		if (WbampHelper_Edition::$id == 'full')
		{
			if (WbampHelper_Runtime::isStandaloneMode())
			{
				// update sh404SEF shURL, but only in standalone mode. In other modes,
				// sh404SEF is doing it itself
				Sh404sefHelperShurl::updateShurls();
			}
			WbampHelper_Sh404sef::processMetaData($data, $document, $this->_manager);
		}

		/**
		 * Filter the html metdata to be included in the page.
		 *
		 * @api
		 * @package wbAMP\filter\data
		 * @var wbamp_data_metadata
		 * @since   1.9.0
		 *
		 * @param array $metadat An array of the meta data collected for the page (title, description,...).
		 *
		 * @return string
		 */
		$data['metadata'] = ShlHook::filter(
			'wbamp_data_metadata',
			$data['metadata']
		);

		// document data
		$data['document_language'] = JFactory::getLanguage()->getTag();
		$data['document_direction'] = JFactory::getDocument()->direction;
		// page data
		$data['canonical'] = $this->_manager->getCanonicalUrl();
		$data['shURL'] = $this->_manager->getShURL();
		$data['amp_url'] = JUri::current();
		$data['amp_path'] = str_replace(JUri::base(), '/', $data['amp_url']);

		// headers
		$this->getHeaders($data);

		// custom content
		$data['custom_style'] = $data['params']->get('custom_css', '');
		$data['custom_links'] = $data['params']->get('custom_links', '');

		// wbamp theme and joomla template used
		$data['theme'] = $data['params']->get('global_theme', 'default.default');
		$templateId = (int) $data['params']->get('rendering_template', 0);
		$data['joomla_template'] = WbampHelper_Media::getTemplateName($templateId);

		// collect navigation menu data, for rendering
		$data['navigation_menu'] = $this->getElementData('navigation', $data, '');
		// if menu is sidebar, add sidebar script
		if (WbampHelper_Edition::$id == 'full'
			&& $data['params']->get('menu_location', 'hidden') != 'hidden'
			&& WbampHelper_Amphtml::isSlidingMenu($data['params'])
		)
		{
			$data['assets_collector']->addScripts(
				array(
					'amp-sidebar' => sprintf(WbampModel_Renderer::AMP_SCRIPTS_PATTERN, 'sidebar', WbampModel_Renderer::AMP_SCRIPTS_VERSION)
				)
			);
		}

		// collect social buttons data, for rendering
		$data['social_buttons'] = $this->getElementData(
			'socialbuttons',
			$data,
			array(
				'types' => array(),
				'theme' => 'colors',
				'style' => 'rounded'
			)
		);

		// collect main Joomla rendered component content
		$rawContent = JFactory::getDocument()->getBuffer('component');

		// extract any instruction set for the current menu item
		// through custom page CSS class. Those are also stored in $this->_userSetData.
		$this->getUserDataFromMenuItem();

		// extract content from tags set by user in original content (and remove them)
		$rawContent = $this->getUserSetData($rawContent);

		// combine and store
		$data['user_set_data'] = $this->_userSetData;

		// process all links in content, to make them absolute and SEF if needed
		$rawContent = WbampHelper_Route::sef($rawContent);

		$data['disqus_comments_data'] = array();
		if (WbampHelper_Edition::$id == 'full')
		{
			if ($data['params']->get('disqus_comments_enabled', false) && !empty(WbampHelper_Runtime::$disqusModel))
			{
				$data['disqus_comments_data'] = WbampHelper_Runtime::$disqusModel->getData($data, $this);
			}

			if (!empty($data['disqus_comments_data']))
			{
				WbampHelper_Runtime::$disqusModel->registerCleanup();
				$data['assets_collector']->addScripts(
					array(
						'amp-iframe' => sprintf(WbampModel_Renderer::AMP_SCRIPTS_PATTERN, 'iframe', WbampModel_Renderer::AMP_SCRIPTS_VERSION),
					)
				);
			}
		}

		// convert standard HTML to AMP compliant HTML
		$data['main_content'] = $this->_postProcessor->convert($rawContent);

		// remove tags only used when the regular HTML page is displayed
		$data['main_content'] = str_replace('{wbamp-no-scrub}', '', $data['main_content']);

		// ads, make sure amp-ad script is loaded
		$data['main_content'] = $this->getElementData('ad', $data, $data['main_content']);

		// process social networks tags, or raw URLs
		$data['main_content'] = $this->getElementData('embedtags', $data['main_content'], $data['main_content']);

		// restore any form in the content
		$data['main_content'] = WbampHelper_Content::injectProtectedForms($data['main_content']);

		// header
		$data['header_module'] = $this->processRenderedModules(
			array($this->doRenderModule($data['params']->get('header_module')))
		);
		$data['header_module'] = array_pop($data['header_module']);

		// top and bottom module positions
		$data['top_module_position'] = $this->processRenderedModules(
			$this->renderModulePosition('wbamp-top')
		);

		$data['bottom_module_position'] = $this->processRenderedModules(
			$this->renderModulePosition('wbamp-bottom')
		);

		$data['site_name'] = $data['params']->get('site_name', '');
		$data['site_link'] = $data['joomla_config']->get('live_site', JUri::base());
		$data['site_image'] = $data['params']->get('site_image', '');
		$data['site_image_size'] = array();
		$data['site_image_size']['width'] = (int) $data['params']->get('site_image_width', 0);
		$data['site_image_size']['height'] = (int) $data['params']->get('site_image_height', 0);
		$data['site_image_size'] = WbampHelper_Media::findImageSizeIfMissing($data['site_image'], $data['site_image_size']);

		// footer, a custom HTML module created by user
		$data['footer'] = $this->renderModule($data['params']->get('footer_module'));
		$data['footer'] = $this->_postProcessor->convert($data['footer']);
		// restore any form in the content
		$data['footer'] = WbampHelper_Content::injectProtectedForms($data['footer']);

		// user notification
		// @TODO: move to method
		$data['user-notification'] = array(
			'text'   => $data['params']->get('notification_text', ''),
			'button' => $data['params']->get('notification_button', ''),
			'theme'  => $data['params']->get('notification_theme', 'light')
		);
		if (!empty($data['user-notification']) && !empty($data['user-notification']['text']))
		{
			$data['assets_collector']->addScripts(
				array(
					'amp-user-notification' => sprintf(WbampModel_Renderer::AMP_SCRIPTS_PATTERN, 'user-notification', WbampModel_Renderer::AMP_SCRIPTS_VERSION),
				)
			);
		}

		// turn links found in user generated content, marked with a wbamp-link class, into their AMP equivalent
		// protect email addresses against bots
		$data['navigation_menu'] = $this->_postProcessor->ampifyLinks($data['navigation_menu'], $this);

		$data['main_content'] = $this->_postProcessor->ampifyLinks($data['main_content'], $this);
		$data['main_content'] = $this->_postProcessor->applyFilters($data['main_content']);

		$data['header_module']['content'] = $this->_postProcessor->ampifyLinks(
			wbArrayGet($data['header_module'], 'content', ''),
			$this
		);
		$data['header_module']['content'] = $this->_postProcessor->applyFilters(
			wbArrayGet($data['header_module'], 'content', '')
		);

		foreach ($data['top_module_position'] as $id => $datum)
		{
			$data['top_module_position'][$id]['content'] = $this->_postProcessor->ampifyLinks(
				wbArrayGet($datum, 'content', ''),
				$this
			);
		}
		foreach ($data['top_module_position'] as $id => $datum)
		{
			$data['top_module_position'][$id]['content'] = $this->_postProcessor->applyFilters(
				wbArrayGet($datum, 'content', '')
			);
		}

		foreach ($data['bottom_module_position'] as $id => $datum)
		{
			$data['bottom_module_position'][$id]['content'] = $this->_postProcessor->ampifyLinks(
				wbArrayGet($datum, 'content', ''),
				$this
			);
		}
		foreach ($data['bottom_module_position'] as $id => $datum)
		{
			$data['bottom_module_position'][$id]['content'] = $this->_postProcessor->applyFilters(
				wbArrayGet($datum, 'content', '')
			);
		}

		$data['footer'] = $this->_postProcessor->ampifyLinks($data['footer'], $this);
		$data['footer'] = $this->_postProcessor->applyFilters($data['footer']);

		// insert analytics AMP element
		$data['analytics_data'] = $this->getElementData('analytics', $data, '');

		// let plugins build json-ld data
		$data['json-ld'] = $this->getJsonldData($data);
		$data['json-ld'] =
			/**
			 * Filter the json-ld data to be included on any AMP page.
			 *
			 * @api
			 * @package wbAMP\filter\data
			 * @var wbamp_data_json_ld
			 * @since   1.9.0
			 *
			 * @param array $jsonLd An array representing exactly the json-ld data to be output into the AMP page.
			 * @param array $params The full set of data collected to build the current AMP page.
			 *
			 * @return string
			 */
			ShlHook::filter(
				'wbamp_data_json_ld',
				$data['json-ld'],
				$data
			);

		// collect possible structured data from sh404SEF
		if (WbampHelper_Edition::$id == 'full')
		{
			$structuredData = WbampHelper_Sh404sef::getStructuredData();
			if (!empty($structuredData))
			{
				$data['structured_data'] = $structuredData;
			}
		}

		// collect additional scripts to insert
		$data['amp_scripts'] = $data['assets_collector']->getScripts();
		$data['amp_templates'] = $data['assets_collector']->getTemplates();

		$data =
			/**
			 * Filter the full set of data collected to build the current AMP page.
			 *
			 * @api
			 * @package wbAMP\filter\data
			 * @var wbamp_data_all
			 * @since   1.9.0
			 *
			 * @param array $data The full set of data collected to build the current AMP page.
			 *
			 * @return string
			 */
			ShlHook::filter(
				'wbamp_data_all',
				$data
			);

		return $data;
	}

	public function buildTag($type, $displayData)
	{
		$tag = ShlMvcLayout_Helper::render('wbamp.tags.' . $type, $displayData, WbampHelper_Runtime::$layoutsBasePaths);
		// finally add script to execute the tag
		WbampHelper_Content::addScripts(
			array(
				WbampHelper_Runtime::$embedTags[$type]['amp_tag'] => sprintf(WbampModel_Renderer::AMP_SCRIPTS_PATTERN, WbampHelper_Runtime::$embedTags[$type]['script'], WbampModel_Renderer::AMP_SCRIPTS_VERSION)
			)
		);

		return $tag;
	}

	/**
	 * Applies suitable transformation to a rendered module, including expansion of embedded tags
	 * and AMP conversion.
	 *
	 * @param array $moduleData
	 *
	 * @return string
	 */
	protected function processRenderedModules($moduleData)
	{
		if (WbampHelper_Edition::$id == 'full')
		{
			foreach ($moduleData as $id => $datum)
			{
				$content = wbArrayGet($datum, 'content', '');
				$content = $this->getElementData('embedtags', $content, $content);
				$content = $this->_postProcessor->convert($content);
				// restore any form in the content
				$moduleData[$id]['content'] = WbampHelper_Content::injectProtectedForms($content);
			}
		}

		return $moduleData;
	}

	/**
	 * Use Joomla to render a module identified by its id
	 * System-non chrome used when rendering, ie no chrome at all
	 *
	 * @param int $moduleId
	 *
	 * @return mixed|string
	 */
	public function renderModule($moduleId)
	{
		return wbArrayGet(
			$this->doRenderModule($moduleId),
			'content',
			''
		);
	}

	/**
	 * Use Joomla to render a module identified by its id
	 * System-non chrome used when rendering, ie no chrome at all
	 *
	 * Returns both the rendered content and the module details.
	 *
	 * @param int $moduleId
	 *
	 * @return array
	 */
	protected function doRenderModule($moduleId)
	{
		static $moduleList = null;

		if (WbampHelper_Edition::$id != 'full')
		{
			return array(
				'content' => '',
				'def'     => null
			);
		}

		$renderedModule = '';
		$module = null;
		try
		{
			if (!empty($moduleId))
			{
				$moduleList = is_null($moduleList) ? JModuleHelper::getModuleList() : $moduleList;
				foreach ($moduleList as $module)
				{
					if ($module->id == $moduleId)
					{
						$attribs['style'] = 'System-none';
						$renderedModule = JModuleHelper::renderModule($module, $attribs);
						$renderedModule = WbampHelper_Route::sef($renderedModule);
						$renderedModule = str_replace('{wbamp_current_year}', date('Y'), $renderedModule);

						// make sure module content is AMP compliant
						$renderedModule = $this->_postProcessor->convert($renderedModule);
					}
				}
			}
		}
		catch (Exception $e)
		{
			ShlSystem_Log::error('wbamp', __METHOD__ . ' ' . $e->getMessage());
			$renderedModule = '';
		}

		return array(
			'content' => $renderedModule,
			'def'     => $module
		);
	}

	/**
	 * Renders all modules published on a given module position. This is used
	 * to render modules published in the wbamp-top and wbamp-bottom positions.
	 *
	 * @param string $position
	 *
	 * @return string
	 */
	public function renderModulePosition($position)
	{
		$renderedPosition = array();

		$modules = JModuleHelper::getModules($position);
		if (!empty($modules))
		{
			foreach ($modules as $module)
			{
				$renderedPosition[] = array(
					'position' => $position,
					'content'  => ShlMvcLayout_Helper::render(
						'wbamp.module',
						array(
							'module'   => $this->doRenderModule($module->id),
							'position' => $position
						),
						WbampHelper_Runtime::$layoutsBasePaths
					),
					'def'      => $module
				);
			}
		}

		return $renderedPosition;
	}

	/**
	 * Instantiate an element-specific renderer model
	 * and use its getData() method to collect
	 * some piece of content
	 *
	 * @param $element
	 * @param $currentData
	 *
	 * @return array
	 */
	private function getElementData($element, $currentData, $default = array())
	{
		if (WbampHelper_Edition::$id == 'full')
		{
			$name = 'WbampModelElement_' . ucfirst(str_replace('-', '', $element));
			$element = new $name();
			$result = $element->getData($currentData, $this);
			$data = isset($result['data']) ? $result['data'] : array();
			WbampHelper_Content::addScripts(
				isset($result['scripts']) ? $result['scripts'] : array()
			);
		}
		else
		{
			$data = $default;
		}

		return $data;
	}

	/**
	 * Builds an array of raw headers, to be output
	 * at rendering
	 *
	 * @param $data
	 */
	private function getHeaders(&$data)
	{
		$data['headers'] = array(
			'X-amphtml: wbAMP'
		);
		if (!empty($data['shURL']))
		{
			// add header for shortURL, mostly for HEAD requests
			$data['headers'][] = 'Link: <' . $data['shURL'] . '>; rel=shortlink';
		}
		if ($data['params']->get('adv-gzip', 0))
		{
			$maxAge = $data['params']->get('adv-max-age', '');
			if ($maxAge != '')
			{
				$data['headers'][] = 'Cache-control: max-age=' . (int) $maxAge . ', must-revalidate';
			}
		}
	}

	/**
	 * Extract and store meta data set by user using
	 * {wbamp-*} tags in the content
	 *
	 * Typically: link and size of image
	 *
	 * @param $content
	 */
	private function getUserSetData($content)
	{
		$regex = '#{wbamp\-meta([^}]*)}#m';
		$content = preg_replace_callback($regex, array($this, '_processUserSetData'), $content);

		return $content;
	}

	private function _processUserSetData($match)
	{
		// detect type we can handle
		if (!empty($match[1]))
		{
			$attributes = JUtility::parseAttributes($match[1]);
			$type = empty($attributes['name']) ? '' : $attributes['name'];
			if (in_array($type, $this->_userSetTags))
			{
				$this->_userSetData[$type] = $attributes;
			}

			return '';
		}

		return $match[0];
	}

	/**
	 * Look up the current menu item custom CSS page class for
	 * wbAMP specific ones, and set flags accordingly.
	 * Handled:
	 *
	 * - wbamp-no-ads
	 *
	 */
	private function getUserDataFromMenuItem()
	{
		$menuItem = JFactory::getApplication()->getMenu()->getActive();
		if (empty($menuItem))
		{
			return;
		}

		if (is_callable(array($menuItem, 'getParams')))
		{
			$menuParams = $menuItem->getParams();
		}
		else
		{
			// pre-3.7
			$menuParams = $menuItem->params;
		}
		$classes = trim($menuParams->get('pageclass_sfx', ''));
		$classes = ShlSystem_Strings::stringToCleanedArray($classes, ' ', ShlSystem_Strings::LOWERCASE);
		foreach ($classes as $class)
		{
			switch ($class)
			{
				case 'wbamp-no-ads':
					$this->_userSetData['no_ads'] = true;
					break;
			}
		}
	}

	/**
	 * Build up an array of meta data that can be json_encoded and output
	 * directly to the page.
	 *
	 * @param $data
	 *
	 * @return array
	 */
	private function getJsonldData($data)
	{
		$jsonld = array();

		try
		{
			$config = new WbampModel_Config();
			$pluginsLoaded = JPluginHelper::importPlugin('wbamp');
			$isSingular = $pluginsLoaded ? WbampHelper_Route::isSingular($this->_request) : false;

			// global meta data
			// Item global meta data
			$jsonld['@context'] = 'http://schema.org';
			if ($isSingular)
			{
				$defaultArticleType = WbampHelper_Runtime::$params->get('default_doc_type', 'news');
				$jsonld['@type'] = array_key_exists($defaultArticleType, $config->documentTypes) ?
					$config->documentTypes[$defaultArticleType] : $config->documentTypes['news'];
			}
			else
			{
				$jsonld['@type'] = 'WebPage';
			}

			// optional description
			$description = JFactory::getDocument()->getDescription();
			if (!empty($description))
			{
				$jsonld['description'] = $description;
			}

			$jsonld['mainEntityOfPage'] = $this->_manager->getCanonicalUrl();
			$headlineMaxLength = $config->headlineMaxLength;
			$jsonld['headline'] = wbAbridge(JFactory::getDocument()->getTitle(), $headlineMaxLength, $headlineMaxLength - 3);

			// publisher
			$publisherImageUrl = WbampHelper_Runtime::$params->get('publisher_image', '');
			$publisherImageSize = array();
			$publisherImageSize['width'] = (int) WbampHelper_Runtime::$params->get('publisher_image_width', 0);
			$publisherImageSize['height'] = (int) WbampHelper_Runtime::$params->get('publisher_image_height', 0);
			$publisherImageSize = WbampHelper_Media::findImageSizeIfMissing($publisherImageUrl, $publisherImageSize);
			$jsonld['publisher'] = array(
				'@type' => 'Organization',
				'name'  => WbampHelper_Runtime::$params->get('publisher_name', ''),
				'logo'  => array(
					'@type'  => 'ImageObject',
					'url'    => ShlSystem_Route::absolutify($publisherImageUrl, true),
					'width'  => $publisherImageSize['width'],
					'height' => $publisherImageSize['height']

				)
			);

			// let plugins provide basic data
			if ($pluginsLoaded)
			{
				$option = $this->_request->getCmd('option', '');
				$eventArgs = array(
					$option,
					& $jsonld,
					$this->_request,
					$data
				);
				ShlSystem_Factory::dispatcher()
				                 ->trigger('onWbampGetJsonldData', $eventArgs);
			}
			else
			{
				throw new Exception('Unable to load wbAMP components support plugins.');
			}

			// then look for overrides set by user in content or otherwise (sh404SEF for instance)

			// publication date: {wbamp-meta name="date_published" content="2016-03-11 06:00:00"}
			if ($isSingular && wbArrayGet($data, array('user_set_data', 'date_published', 'content')))
			{
				try
				{
					$tz = JFactory::getUser()->getParam('timezone', JFactory::getConfig()->get('offset'));
					$jsonld['datePublished'] = JHtml::_('date', $data['user_set_data']['date_published']['content'] . $tz, DateTime::ATOM, 'UTC');
					if (substr($jsonld['datePublished'], -6) == '+00:00')
					{
						$jsonld['datePublished'] = substr($jsonld['datePublished'], 0, -6) . 'Z';
					}
				}
				catch (Exception $e)
				{
					ShlSystem_Log::error('wbamp', __METHOD__ . ' ' . $e->getMessage());
					if (isset($jsonld['datePublished']))
					{
						unset($jsonld['datePublished']);
					}
				}
			}

			// modification date: {wbamp-meta name="date_modified" content="2016-03-11 06:00:00"}
			if ($isSingular && wbArrayGet($data, array('user_set_data', 'date_modified', 'content')))
			{
				try
				{
					$tz = JFactory::getUser()->getParam('timezone', JFactory::getConfig()->get('offset'));
					$jsonld['dateModified'] = JHtml::_('date', $data['user_set_data']['date_modified']['content'] . $tz, DateTime::ATOM, 'UTC');
					if (substr($jsonld['dateModified'], -6) == '+00:00')
					{
						$jsonld['dateModified'] = substr($jsonld['dateModified'], 0, -6) . 'Z';
					}
				}
				catch (Exception $e)
				{
					ShlSystem_Log::error('wbamp', __METHOD__ . ' ' . $e->getMessage());
					if (isset($jsonld['dateModified']))
					{
						unset($jsonld['dateModified']);
					}
				}
			}

			// document type: wbamp-meta name="doc_type"
			if ($isSingular && wbArrayGet($data, array('user_set_data', 'doc_type', 'content')))
			{
				$jsonld['@type'] = JString::trim($data['user_set_data']['doc_type']['content']);
			}

			// author: {wbamp-meta name="author" type="Person" content="Yannick Gaultier"}
			if ($isSingular && wbArrayGet($data, array('user_set_data', 'author')))
			{
				if (!empty($data['user_set_data']['author']['type']))
				{
					$jsonld['author']['@type'] = JString::trim($data['user_set_data']['author']['type']);
				}
				if (!empty($data['user_set_data']['author']['content']))
				{
					$jsonld['author']['name'] = Jstring::trim($data['user_set_data']['author']['content']);
				}
			}

			// we can try find those set in sh404SEF.
			// However, we don't have width/height for those, meaning
			// we have to extract them from the file.
			if (empty($jsonld['image']) && !empty($data['sh404sef_custom_data']))
			{
				$image = empty($data['sh404sef_custom_data']->og_image) ? Sh404sefFactory::getConfig()->ogImage : $data['sh404sef_custom_data']->og_image;
				if (!empty($image))
				{
					$imageSize = ShlHtmlContent_Image::getImageSize($image);
					$imageUrl = ShlSystem_Route::absolutify( $image, true );
					if ($this->validatePageImageDimensions($imageUrl, $imageSize)) {
						$jsonld['image'] = array(
							'@type'  => 'ImageObject',
							'url'    => $imageUrl,
							'width'  => $imageSize['width'],
							'height' => $imageSize['height']

						);
					}
				}
			}

			// image, if set by user in regular content, {wbamp-meta name="image" url="" height="123" width="456"}
			if ($isSingular && wbArrayGet($data, array('user_set_data', 'image', 'url')))
			{
				$imageUrl = ShlSystem_Route::absolutify($data['user_set_data']['image']['url'], true);
				$imageSize = array();
				$imageSize['width'] = empty($data['user_set_data']['image']['width']) ? 0 : $data['user_set_data']['image']['width'];
				$imageSize['height'] = empty($data['user_set_data']['image']['height']) ? 0 : $data['user_set_data']['image']['height'];
				$imageSize = WbampHelper_Media::findImageSizeIfMissing($imageUrl, $imageSize);
				if ($this->validatePageImageDimensions($imageUrl, $imageSize)) {
					$jsonld['image'] = array(
						'@type'  => 'ImageObject',
						'url'    => $imageUrl,
						'width'  => $imageSize['width'],
						'height' => $imageSize['height'],

					);
				}
			}

			// fallback to finding an image automatically if none set
			if (WbampHelper_Edition::$id == 'full' && empty($jsonld['image']))
			{
				$jsonld['image'] = $this->_findImageIncontent($data['main_content']);
			}
		}
		catch (Exception $e)
		{
			ShlSystem_Log::error('wbamp', __METHOD__ . ' ' . $e->getMessage());
			$jsonld = array();
		}

		return $jsonld;
	}

	/**
	 * Extract and store meta data set by user using
	 * {wbamp-*} tags in the content
	 *
	 * Typically: link and size of image
	 *
	 * @param $content
	 */
	private function _findImageIncontent($content)
	{
		$regex = '#<amp\-img([^>]*)>#Uum';
		preg_replace_callback($regex, array($this, '_extractImageFromIncontent'), $content);

		return $this->_extractedImage;
	}

	/**
	 * Stores the first image found in content to be used
	 * as page image
	 *
	 * @param $match
	 *
	 * @return mixed
	 */
	private function _extractImageFromIncontent($match)
	{
		// detect type we can handle
		if (!empty($match[1]) && empty($this->_extractedImage))
		{
			$attributes = JUtility::parseAttributes($match[1]);
			$imageUrl = ShlSystem_Route::absolutify($attributes['src'], true);
			$imageSize = array();
			$imageSize['width'] = empty($attributes['width']) ? 0 : $attributes['width'];
			$imageSize['height'] = empty($attributes['height']) ? 0 : $attributes['height'];
			$imageSize = WbampHelper_Media::findImageSizeIfMissing($imageUrl, $imageSize);

			// only insert image if we think it's ok, ie have dimensions and min width is ok
			if ($this->validatePageImageDimensions($imageUrl, $imageSize))
			{
				$this->_extractedImage = array(
					'@type'  => 'ImageObject',
					'url'    => $imageUrl,
					'width'  => $imageSize['width'],
					'height' => $imageSize['height']

				);
			}
		}

		return $match[0];
	}

	/**
	 * Validate a page image dimensions against known AMP rules.
	 *
	 * @param string $imageUrl
	 * @param array  $imageSize
	 */
	private function validatePageImageDimensions($imageUrl, $imageSize)
	{
		$valid = false;
		$config = new WbampModel_Config();
		if (empty($imageSize['width']) || empty($imageSize['height']) || $imageSize['width'] < $config->pageImageMinWidth)
		{
			return $valid;
		}

		$pixels = $imageSize['width'] * $imageSize['height'];
		if ($pixels < $config->pageImageMinPixels)
		{
			return $valid;
		}

		$extension = pathinfo($imageUrl, PATHINFO_EXTENSION);
		if (!in_array($extension, $config->pageImageTypes))
		{
			return $valid;
		}

		return true;
	}
}
