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
 *
 * build 1.12.0.789
 *
 */

defined('_JEXEC') or die();

class plgSystemWbamp extends JPlugin
{
	/**
	 * @var string Quick access to plugin version
	 */
	private $wbampVersion = '1.12.0.790';

	/**
	 * Static class instance storage, for proxy access
	 *
	 * @var null|plgSystemWbamp
	 */
	static private $that = null;

	/**
	 * @var bool Whether current request is for an AMP page
	 */
	public $isAmpPage = false;

	/**
	 * @var bool Whether the current request has an AMP version
	 */
	public $hasAmpVersion = true;

	/**
	 * Application object.
	 *
	 * @var    JApplicationCms
	 * @since  3.2
	 */
	protected $app;

	/**
	 * Affects constructor behavior. If true, language files will be loaded automatically.
	 *
	 * @var    boolean
	 * @since  3.1
	 */
	protected $autoloadLanguage = true;

	/**
	 * List of document types we need to clean from
	 * wbamp custom tags when page is not an AMP page
	 *
	 * @var array
	 */
	private $documentTypesToScrub = array('html', 'feed');

	/**
	 * @var bool Internal on/off switch, saves time
	 */
	private $_enabled = true;

	/**
	 * @var WbampModel_Manager AMP manager model instance
	 */
	private $_manager = null;

	/**
	 * @var array Base URL to fetch updates
	 */
	private $baseUrls = array(
		'https://u1.weeblr.com/dist/wbamp/full'               => 'wbamp',
		'https://u1.weeblr.com/dist/wbamp-themes-taylor/full' => 'wbamp-themes-taylor'
	);

	/**
	 * @var    String  extension identifier, to retrieve its params
	 */
	private $extension = 'plg_wbamp';

	/**
	 * @var    String    An id for your product, to be used by the web site when deciding to allow access
	 *                    Not mandatory, depends on subscription management system
	 */
	private $productId = 'wbamp';

	/**
	 * Cache whether site or admin
	 * @var bool
	 */
	private $_isSite = true;

	/**
	 * Stores a copy of the requested URI before
	 * the Joomla router can modify it
	 * (and remove trailing slash!)
	 *
	 * @var JUri|null
	 */
	private $_originalUri = null;

	/**
	 * Save the name of the currently
	 * selected template
	 *
	 * @var string
	 */
	private $_currentTemplate = '';

	/**
	 * Prevent parsing the request multiple times, which may
	 * happen with some SEF extensions, if the router::parse()
	 * method is used before the onAfterRoute event is triggered
	 * (for which we also have a flag to avoid multiple routing)
	 *
	 * @var bool
	 */
	private $_alreadyParsed = false;

	/**
	 * Flag to prevent multiple execution
	 *
	 * @var bool
	 */
	private $_alreadyRouted = false;

	/**
	 * Storage for joomla error handler
	 * to pass thru if we can't handle ourselves
	 *
	 * @var null
	 */
	private $_joomlaErrorHandler = null;

	/**
	 * @var bool Flag for single initialization
	 */
	private $librariesInited = false;

	/**
	 * @var bool Flag, if raised, there a change in parameters format, they have been updated and need to be saved
	 */
	private $paramsFormatUpdated = false;

	/**
	 * @var JRegistry Working copy of parameters, in case of update.
	 */
	private $updatedParams = null;

	/**
	 * Constructor detect if plugin should be started
	 * Enable and setup autoloader if so
	 *
	 * @param object $subject
	 * @param array  $config
	 */
	public function __construct(&$subject, $config = array())
	{
		parent::__construct($subject, $config);

		// AMP pages only on front end
		if (!$this->app->isSite())
		{
			$this->_enabled = false;
			$this->_isSite = false;
			$inited = $this->init();

			// convert parameters, in case of configuration
			// setup changes
			if ($inited)
			{
				$this->convertParameters();
			}

			return;
		}

		// methods is GET
		$method = $this->app->input->getMethod();
		$allowedMethods = array('GET', 'HEAD');
		if (!in_array($method, $allowedMethods))
		{
			$this->_enabled = false;
			return;
		}

		// store for facade
		self::$that = $this;

		// run init procedure
		$inited = $this->init();
		if (!$inited)
		{
			return;
		}

		// convert parameters, in case of configuration
		// setup changes
		$this->convertParameters();

		// register event handlers
		$joomlaRouter = $this->app->getRouter();
		$joomlaRouter->attachParseRule(array($this, 'preprocessParseRule'), JRouter::PROCESS_BEFORE);

		// store pristine URI, before J! router modifies it
		$this->_originalUri = JUri::getInstance();
	}

	/**
	 * Transparently upgrade plugin parameters saved, as we
	 * make changes to their format.
	 */
	protected function convertParameters()
	{
		if (strpos($this->wbampVersion, '_version_') !== false)
		{
			// dev version
			return;
		}

		// working copy
		$this->updatedParams = clone($this->params);

		// 1.9.0: new page selection format
		if (version_compare($this->wbampVersion, '1.9.0', 'ge'))
		{
			// check if not already updated
			$comContentItemid = $this->params->get('_com_content_itemid', array());
			if (!empty($comContentItemid) && is_string($comContentItemid))
			{
				$this->convertParameters190();
				$this->paramsFormatUpdated = true;
			}
		}

		// if modified, save new params
		if (!empty($this->paramsFormatUpdated))
		{
			// update current instance params
			$this->params = $this->updatedParams;

			// persist to db
			$updated = ShlSystem_Joomla::saveExtensionParams(
				$this->params,
				array('type' => 'plugin', 'folder' => 'system', 'element' => 'wbamp')
			);

			if (!$updated)
			{
				// NB: we cannot enqueue a message, even on backend, as this may break feeds or non-html requests
				ShlSystem_Log::error('wbamp', '%s::%d: %s', __METHOD__, __LINE__, 'Unable to update some wbamp settings to a newer format. Please review all settings to confirm they are all valid.');
				$msg = array(
					'scope'        => 'plg_wbamp',
					'type'         => 'wbamp.config.error',
					'sub_type'     => 'unable_to_update',
					'display_type' => ShlMsg_Manager::DISPLAY_TYPE_ERROR,
					'title'        => 'Unable to update some wbamp settings to a newer format. Please review all settings to confirm they are all valid.',
					'body'         => '',
					'action'       => ShlMsg_Manager::ACTION_CAN_CLOSE
				);

				ShlMsg_Manager::getInstance()->add($msg);
			}
		}
	}

	/**
	 * Apply parameters conversion based on changes
	 * introduced in version 1.9.0.
	 */
	protected function convertParameters190()
	{
		// convert article ids list
		// only convert empty and -. Not turned into an array
		$list = $this->params->get('_com_content_item_id', '*');
		if (empty($list) || '-' == $list)
		{
			$this->updatedParams->set('_com_content_item_id', '*');
		}

		// convert com_content views list
		$this->convertParam('_com_content_view');

		// convert com_content menu items
		$this->convertParam('_com_content_itemid');

		// per component rules
		for ($ruleNumber = 1; $ruleNumber <= WbampModel_Ruler::RULES_COUNT + 1; $ruleNumber++)
		{
			// views
			$this->convertParam($ruleNumber . '_view');

			// itemids
			$this->convertParam($ruleNumber . '_itemid');
		}
	}

	private function convertParam($paramName)
	{
		$list = $this->params->get($paramName, '');
		if (empty($list) || '-' == $list || '*' == $list)
		{
			$arrayList = array('__any__');
		}
		else
		{
			// convert list to array
			$arrayList = ShlSystem_Strings::stringToCleanedArray($list);
		}
		$this->updatedParams->set($paramName, $arrayList);
	}

	/**
	 * Static facade for quick access to whether current request
	 * is for AMP content or not
	 *
	 * @return bool
	 */
	public static function isAmpPage()
	{
		return self::$that->isAmpPage;
	}

	/**
	 * Put our hands on the request before it's parsed, to identify
	 * the AMP suffix, and alter the request so that it's parsed
	 * normally. We raise a flag so that at a later stage we know
	 * this is an AMP request.
	 *
	 * That's also were we disable plugins. It should be done at onAfterIniitialize
	 * to have a better chance to nullify effectively all system plugins
	 * (that still can't prevent what they do in their constructors)
	 * but at this stage the request has not been parsed yet, and it's kinda hard
	 * to determine if the request is for an AMP page or not.
	 * So a compromise here is to do it just before the request parsing:
	 * - we can find out whether this is an AMP request (not fully, as user set rules cannot be checked yet)
	 * - good chances to nuke most system plugins effect. Only those that have preProcessParseRules, and are
	 * ordered earlier than wbAMP can escape. That's deemed to be acceptable.
	 *
	 * @param $router
	 * @param $uri
	 */
	public function preProcessParseRule(&$router, &$uri)
	{
		if (!$this->_isSite || !$this->_enabled || $this->_alreadyParsed || $this->_alreadyRouted)
		{
			return;
		}

		// share some immutable data
		WbampHelper_Runtime::$base = $uri->base();
		WbampHelper_Runtime::$basePath = $uri->base(true);

		// instantiate a new model, ask it to do the job
		$this->_manager = new WbampModel_Manager($router, $uri, $this->_originalUri);
		$this->isAmpPage = $this->_manager->parseAndUpdateRequest();
		$this->_alreadyParsed = true;

		// disable plugins, per settings
		if (
			$this->isAmpPage
			&&
			WbampHelper_Edition::$id == 'full'
			&&
			$this->params->get('cleanup_plugins_enabled', false)
		)
		{
			WbampHelper_Plugins::disable(
				'system',
				$this->params->get('cleanup_plugins_system', array())
			);
		}

		// setup the API
		include_once('api.php');
		WbAMP::init($this->_manager);
	}

	/**
	 * After routing, we validate the AMP page detection
	 * for various situations
	 */
	public function onAfterRoute()
	{
		if (!$this->_isSite || $this->_alreadyRouted)
		{
			return;
		}
		$this->_alreadyRouted = true;

		if (!$this->_enabled)
		{
			return;
		}

		$docType = JFactory::getDocument()->getType();
		if ($docType != 'html')
		{
			$this->isAmpPage = false;
			$this->hasAmpVersion = false;
			return;
		}

		if (
			$this->isAmpPage
			&&
			WbampHelper_Edition::$id == 'full'
			&&
			$this->params->get('cleanup_plugins_enabled', false)
		)
		{
			WbampHelper_Plugins::disable(
				'content',
				$this->params->get('cleanup_plugins_content', array())
			);
		}

		// initialize Layouts possible paths
		// this is always done, even if rules do not pass
		// so that an AMP error page can be displayed even
		// when in standalone mode
		JPluginHelper::importPlugin('wbampthemes');
		ShlSystem_Factory::dispatcher()
		                 ->trigger(
			                 'onWbAMPGetLayoutsPaths',
			                 array($this->params->get('global_theme', 'weeblr.default'), & WbampHelper_Runtime::$layoutsBasePaths)
		                 );

		$getInput = $this->app->input;
		$ruler = new WbampModel_Ruler($getInput, $this->_manager, $this->params, JFactory::getConfig());
		$passUserRules = $ruler->checkRules();
		if ($passUserRules)
		{
			$this->hasAmpVersion = true;

			// prepare rendering by Joomla, switch to our template
			// set template, to perform alternate template output, if set to
			if ($this->isAmpPage)
			{
				$this->_setAlternateTemplate();

				// disable caching: progressive caching may leave some bits
				// we actually don't want to see on the AMP pages
				// as some parts of the page are rendered without going
				// through our custom html and jlayouts overrides
				// because they are fetched directly from the cache
				WbampHelper_Runtime::$joomlaConfig->set('caching', 0);
			}
		}
		else
		{
			// did not pass rules, but standalone mode:
			// display an AMP 404
			if (
				$this->isAmpPage
				&&
				WbampHelper_Runtime::isStandaloneMode()
			)
			{
				$this->loadLanguage();
				throw new JException(JText::_('PLG_SYSTEM_WBAMP_AMP_NOT_FOUND'), 404);
			}
			// should have been an AMP page, display an AMP 404, except if we
			// thought it was an AMP page only because AMP is forced for mobiles
			else if ($this->isAmpPage && !WbampHelper_Runtime::isForcedMobile())
			{
				// load template language, it may not have been loaded yet
				$this->loadTemplateLanguage();
				$this->isAmpPage = false;
				throw new JException('', 404);
			}
			// Should have been an AMP, because AMP was forced on mobile and it has the amp suffix
			// trigger AMP 404
			else if ($this->isAmpPage && WbampHelper_Runtime::isForcedMobile() && $this->_manager->hasSuffix())
			{
				// load template language, it may not have been loaded yet
				$this->loadTemplateLanguage();
				$this->isAmpPage = false;
				throw new JException('', 404);
			}
			else
			{
				// regular html request, simply avoid advertising an AMP version
				$this->hasAmpVersion = false;
				if (WbampHelper_Runtime::isForcedMobile())
				{
					$this->isAmpPage = false;
				}
			}
		}
	}

	/**
	 * Update Disqus relay file if shortname was modified
	 *
	 * @param $context
	 * @param $data
	 * @param $isNew
	 */
	public function onExtensionBeforeSave($context, $data, $isNew)
	{
		if ('com_plugins.plugin' != $context)
		{
			return;
		}
		if (!$data instanceof JTableExtension)
		{
			return;
		}
		if ($data->folder != 'system' && $data->element != 'wbamp')
		{
			return;
		}
		$params = json_decode($data->params);
		$newShortname = empty($params->disqus_comments_shortname) ? '' : trim($params->disqus_comments_shortname);
		$currentShortname = trim($this->params->get('disqus_comments_shortname'));

		if (!empty($newShortname) && $newShortname != $currentShortname)
		{
			// update the relay file
			$relayFileContent = ShlMvcLayout_Helper::render(
				'wbamp.features.wbamp_disqus_tpl',
				array('shortname' => $newShortname),
				JPATH_PLUGINS . '/system/wbamp/layouts'
			);
			$targetFile = JPATH_ROOT . '/media/plg_wbamp/wbamp_disqus.html';
			$written = file_put_contents(
				$targetFile,
				$relayFileContent
			);
			if (empty($written))
			{
				JFactory::getApplication()->enqueueMessage(
					JText::sprintf('PLG_SYSTEM_WBAMP_DISQUS_ERROR_RELAY_FILE_WRITE_ERROR', $targetFile),
					'error'
				);
			}
		}
	}

	/**
	 * Kill the Joomla email cloaker, which uses javascript
	 * We'll replace it with our own, encoding-based
	 *
	 * @param     $context
	 * @param     $row
	 * @param     $params
	 * @param int $page
	 *
	 * @return bool
	 */
	public function onContentPrepare($context, &$row, &$params, $page = 0)
	{
		if (!$this->isAmpPage)
		{
			return true;
		}

		// email cloaking
		if ($this->params->get('email_protection', true))
		{
			if (is_object($row))
			{
				$row->text = '{emailcloak=off}' . $row->text;
			}
			else
			{
				$row = '{emailcloak=off}' . $row;
			}
		}

		// Disqus handling
		if ($this->params->get('disqus_comments_enabled', false))
		{
			$model = new WbampModelElement_Disqus;

			// check context
			if ($model->shouldShow($this->app->input, $context, $row, $page))
			{
				// if OK, make the disqus model available for use by
				// renderer later on. The model has all the input data required
				WbampHelper_Runtime::$disqusModel = $model;
			}
		}

		$filtered =
			/**
			 * Filter the raw joomla content as created by the component rendered in this request,
			 * during the Joomla onContentPrepare event.
			 *
			 * @api
			 * @package wbAMP\filter\data
			 * @var wbamp_on_content_prepare
			 * @since   1.9.3
			 *
			 * @param array $data An array with the data passed by Joomla to onBeforeContent
			 *      'context' => content context string
			 *      'row' => Joomla object (can be a string in some cases) holding the html (usually) content
			 *      'params' => Params, depends on context
			 *      'page' => optional page number
			 *
			 * @return string
			 */
			ShlHook::filter(
				'wbamp_on_content_prepare',
				array(
					'context' => $context,
					'row'     => $row,
					'params'  => $params,
					'page'    => $page
				)
			);

		$row = wbArrayGet($filtered, 'row');
		$params = wbArrayGet($filtered, 'params');

		return true;
	}

	/**
	 * Add default JLayout folder to list. Can be extended by themes
	 * in the future
	 *
	 * @param $layoutPaths
	 */
	public function onWbAMPGetLayoutsPaths($theme, & $layoutPaths)
	{
		// always add the built-in, default layouts, as themes
		// may only partially override them
		array_unshift($layoutPaths, JPATH_ROOT . '/plugins/system/wbamp/layouts');
		return true;
	}

	/**
	 * Adds/modify CSS to the page
	 *
	 * @param $theme
	 * @param $css
	 * @param $displayData
	 *
	 * @return bool
	 */
	public function onWbAMPGetCss($theme, & $css, $displayData)
	{
		return true;
	}

	/**
	 * If current request has been found to be an AMP one
	 * we take over from there, at onAfterDispatch, as the
	 * main component has been rendered by Joomla and is available
	 * in the response object
	 */
	public function onAfterDispatch()
	{
		if (!$this->_isSite || !$this->_enabled)
		{
			return;
		}

		// check for HTTP status == 404 if sh404SEF
		// With Joomla SEF, onAfterDispatch is not triggered, so we don't
		// have to do this check
		if (defined('SH404SEF_IS_RUNNING')
			&& Sh404sefFactory::getPageInfo()->httpStatus == 404
		)
		{
			if (!WbampHelper_Runtime::isStandaloneMode() && !WbampHelper_Runtime::isForcedMobile())
			{
				// not handling 404s as an AMP page, returning control to Joomla
				return;
			}

			// standalone mode, jump to rendering wbAMP 404
			$error = new JException(JFactory::getDocument()->getBuffer('component'), 404);
			$this->errorHandler($error);
			return;
		}

		// not a 404, page must be rendered
		// - by us if we detected earlier this was an AMP page
		// - by Joomla otherwise, through we need to insert an rel=amphtml tag in such case
		if ($this->isAmpPage)
		{
			$displayData = $this->getData(JFactory::getApplication()->input);
			$this->renderAMPPage($displayData, 'wbamp.template');
		}
		else
		{
			$document = JFactory::getDocument();
			if ($this->hasAmpVersion && $this->params->get('operation_mode', 'normal') != 'development')
			{
				// not and AMP page, ie this is a regular page, for which
				// we must advertise the AMP page alternative page
				$ampLink = $this->_manager->getAMPUrl();
				$document->addHeadLink(htmlspecialchars($ampLink, ENT_COMPAT, 'UTF-8'), 'amphtml');
			}
			if ($this->hasAmpVersion && defined('SH404SEF_IS_RUNNING'))
			{
				// filter sh404SEF analytics data, to insert Client ID API code
				ShlHook::add(
					'sh404sef_universal_analytics_data',
					function ($displayData) {
						$displayData['options'] = array_merge(
							$displayData['options'],
							array(
								'useAmpClientId' => true
							)
						);

						return $displayData;
					}
				);
			}
		}
	}

	/**
	 * Disable voting display on AMP, as Joomla output (9/2016)
	 * is not valid structured data, thus breaking AMP SD validation
	 *
	 * @param     $context
	 * @param     $row
	 * @param     $params
	 * @param int $page
	 *
	 * @return bool
	 */
	public function onContentBeforeDisplay($context, &$row, &$params, $page = 0)
	{
		if (!$this->isAmpPage)
		{
			return;
		}

		if (!wbStartsWith($context, 'com_content'))
		{
			return;
		}

		// disabled voting
		if (!empty($params) && is_object($params))
		{
			$params->set('show_vote', false);
		}
	}

	/**
	 * Clean up wbamp tags at the latest possible stage,
	 * to catch feeds
	 *
	 */
	public function onAfterRender()
	{
		// If not an AMP page, clean it
		if ($this->_isSite && !$this->isAmpPage)
		{
			$document = JFactory::getDocument();
			if (in_array($document->getType(), $this->documentTypesToScrub))
			{
				// scrub custom wbamp tags from regular HTML content
				include_once 'helpers/content.php';
				$body = WbampHelper_Content::scrubRegularHtmlPage(
					$this->app->getBody()
				);
				$this->app->setBody($body);
			}
		}
	}

	/**
	 * Handle adding credentials to package download request
	 *
	 * @param   string $url url from which package is going to be downloaded
	 * @param   array  $headers headers to be sent along the download request (key => value format)
	 *
	 * @return  boolean    true        always true
	 */
	public function onInstallerBeforePackageDownload(&$url, &$headers)
	{
		// are we trying to update one of our extensions?
		$oneOfOurs = false;
		foreach ($this->baseUrls as $baseUrl => $productId)
		{
			if (strpos($url, $baseUrl) === 0)
			{
				$oneOfOurs = true;
				$this->productId = $productId;
				break;
			}
		}

		if (!$oneOfOurs)
		{
			return true;
		}

		// needed to identify which edition is being used
		include_once JPATH_ROOT . '/plugins/system/wbamp/helpers/edition.php';

		// read credentials from extension params or any other source
		$credentials = $this->fetchCredentials($url, $headers);

		// bind credentials to request, either in the urls, or using headers
		// or a combination of both
		$this->bindCredentials($credentials, $url, $headers);

		return true;
	}

	/**
	 * Handle all breaking errors output when
	 * in standalone mode, where we cannot rely
	 * on Joomla for output
	 *
	 * @param $error
	 */
	public function errorHandler($error)
	{
		$code = $error->getCode();

		// gather display data
		$displayData = $this->getData(JFactory::getApplication()->input);

		$this->loadLanguage();

		// render page
		JFactory::getConfig()->set('caching', 0);
		@ob_end_clean();
		ob_start();
		if (!headers_sent())
		{
			$displayData['headers'][] = 'Status: ' . $code;
		}

		// insert our error message
		$displayData['error_code'] = $code;
		$displayData['error_message'] = $error->getMessage();
		$codeBit = $code == 404 ? '_404' : '';
		$displayData['error_title'] = JText::sprintf('PLG_SYSTEM_WBAMP_ERROR' . $codeBit . '_TITLE', $displayData['error_code'], $displayData['error_message'], $displayData['amp_url']);
		$displayData['error_body'] = JText::sprintf('PLG_SYSTEM_WBAMP_ERROR' . $codeBit . '_BODY', $displayData['error_message'], $displayData['error_code'], $displayData['amp_url']);
		// sh404SEF integration
		if (
			defined('SH404SEF_IS_RUNNING')
			&&
			Sh404sefFactory::getPageInfo()->httpStatus == 404
			&&
			(
				WbampHelper_Runtime::isStandaloneMode()
				||
				WbampHelper_Runtime::isForcedMobile()
			)
		)
		{
			// sh404SEF enabled, its similar URLs suggestions have been rendered
			// and passed in error message
			$displayData['error_title'] = '';
			$displayData['error_body'] = $displayData['error_message'];
		}

		$displayData['error_footer'] = JText::sprintf('PLG_SYSTEM_WBAMP_ERROR' . $codeBit . '_FOOTER', $displayData['error_code'], $displayData['error_message'], $displayData['amp_url']);

		$displayData['main_content'] = ShlMvcLayout_Helper::render('error_content', $displayData, WbampHelper_Runtime::$layoutsBasePaths);

		// finally render and exit
		$this->renderAMPPage($displayData, 'wbamp.error');
	}

	/**
	 * Handles an ajax request originating from an AMP page.
	 *
	 * Returns a Json response, possibly a redirect header. Requests use the Joomla ajax format:
	 * index.php?option=com_ajax&plugin=wbAmp&format=json&group=wbampajax&scope=xxx
	 *
	 * Plugins in the wbampajax groups are automatically loaded by com_ajax, which gives others
	 * a way to provide support for handling AMP ajax requests.
	 *
	 * scope can be:
	 * - com_contact: built-in support
	 *
	 */
	public function onAjaxWbAmp()
	{
		// setup autoloader
		$this->initLibraries();

		// instantiate an AMP submit handler and let it do the rest
		$ampSubmitHandler = new WbampClass_Ampsubmit(
			$this->app->input,
			JFactory::getUser()
		);
		$ampSubmitHandler->process();

		return null;
	}

	/**
	 * Exception proxy for our error handler
	 *
	 * @param $exception
	 */
	public function exceptionErrorHandler($exception)
	{
		$this->errorHandler($exception);
	}

	protected function loadTemplateLanguage()
	{
		$template = JFactory::getApplication()->getTemplate();

		// Load the language file for the template
		$lang = JFactory::getLanguage();

		// 1.5 or core then 1.6
		$lang->load('tpl_' . $template, JPATH_BASE, null, false, true)
		|| $lang->load('tpl_' . $template, 'templates' . '/' . $template, null, false, true);

		return $this;
	}

	/**
	 * Sets an alternate template, by default our own wbAMP template
	 * to reset all view output to a known markup
	 * (that can be later processed and fixed for AMP compliance as needed)
	 */
	protected function _setAlternateTemplate()
	{
		$templateId = (int) $this->params->get('rendering_template', 0);
		$templateName = WbampHelper_Media::getTemplateName($templateId);
		if (!empty($templateName))
		{
			// switch template
			$this->_currentTemplate = $this->app->getTemplate();
			$this->app->setTemplate($templateName);

			// combat user set layout, which can cause
			// Joomla to go back to the original template

			// com_content hack for categories
			$layout = $this->app->input->get('layout');
			$layoutOverride = $layout;
			if (
				'com_content' == $this->app->input->getCmd('option')
				&&
				'category' == $this->app->input->getCmd('view')
			)
			{
				// for categories, default layout is list, which
				// is rendered as a form, which is thus stripped
				// from AMP page. In such case, we can force a blog
				// view, at least something is displayed
				$layoutOverride = 'blog';
			}

			/**
			 * Filter the name of the layout to use when overriding the current template
			 * with the wbAMP template.
			 *
			 * @api
			 * @package wbAMP\filter\template
			 * @var wbamp_template_layout_override
			 * @since   1.9.0
			 *
			 * @param string $layoutOverride Name of the layout to use when overriding.
			 * @param array  $params Various params describing the request:
			 *      string 'layout' => current layout used.
			 *      'input' => Joomla JInput object for the the request.
			 *      'current_template' => current template selected.
			 *      'amp_template' => AMP template selected.
			 *
			 * @return string
			 */
			$layoutOverride = ShlHook::filter(
				'wbamp_template_layout_override',
				$layoutOverride,
				array(
					'layout'           => $layout,
					'input'            => $this->app->input,
					'current_template' => $this->_currentTemplate,
					'amp_template'     => $templateName
				)
			);

			if (!empty($layoutOverride))
			{
				$this->app->input->set('layout', $layoutOverride);

				$menuItem = $this->app->getMenu()->getActive();
				if (!empty($menuItem))
				{
					if (!empty($menuItem->query['layout']))
					{
						$menuItem->query['layout'] = $layoutOverride;
					}
				}
			}
		}
	}

	/**
	 * Check for shLib presence and enable autoloader
	 *
	 * @return bool
	 */
	private function init()
	{
		// check shLib is available
		if (!defined('SHLIB_VERSION'))
		{
			$this->_enabled = false;
			if (!$this->app->isSite())
			{
				$this->app->enqueueMessage('Missing shLib, or not enabled, AMP plugin cannot run.', 'error');
			}
			return false;
		}

		// autoloader
		$this->librariesInited = $this->initLibraries();
		if (!$this->librariesInited)
		{
			return false;
		}

		// store params
		WbampHelper_Runtime::$params = $this->params;
		WbampHelper_Runtime::$joomlaConfig = JFactory::getConfig();

		// initialize hooks
		ShlHook::load('', 'wbamp_functions.php');

		// register error handling, in standalone mode
		if (
			$this->app->isSite()
			&&
			WbampHelper_Edition::$id == 'full'
			&&
			(
				WbampHelper_Runtime::isStandaloneMode()
				||
				WbampHelper_Runtime::isForcedMobile()
			)
		)
		{
			// we store current handler, so as to pass-thru to it if needed
			$this->_joomlaErrorHandler = JError::getErrorHandling(E_ERROR);

			// then override Joomla! handler
			JError::setErrorHandling(E_ERROR, 'callback', array($this, 'errorHandler'));
			set_exception_handler(array($this, 'exceptionErrorHandler'));
		}

		return true;
	}

	/**
	 * Register autoloader prefix with shLib.
	 *
	 * @return bool
	 */
	private function initLibraries()
	{
		if ($this->librariesInited)
		{
			return true;
		}

		// autoload our stuff
		$registered = ShlSystem_Autoloader::registerPrefix('Wbamp', JPATH_ROOT . '/plugins/system/wbamp', $isPackage = false);
		if (!$registered)
		{
			$this->_enabled = false;
			ShlSystem_Log::error('wbamp', 'Failed registering AMP plugin autoloader with shLib, AMP plugin cannot run.');
			return false;
		}

		return true;
	}

	/**
	 * Gather all data required for a given AMP page output.
	 *
	 * @param JInput $getInput Joomla request input object.
	 *
	 * @return array
	 */
	private function getData($getInput)
	{
		// instantiate render model and view
		$renderer = new WbampModel_Renderer($getInput, $this->_manager, $this->isAmpPage);

		return $renderer->getData();
	}

	/**
	 * Renders and output a full AMP page,
	 * terminating the execution
	 *
	 * @param $getInput
	 */
	private function renderAMPPage($displayData, $mainLayout)
	{
		// let the view render the full page
		$view = new WbampView_Amp();
		$pageContent = $view->render($displayData, $mainLayout);

		// run hacks and custom compatibility code
		// required by 3rd-parties
		WbampHelper_Environment::handleSpecificEnvironment();

		// then terminate processing
		echo $pageContent;
		exit();
	}

	/**
	 * Bind credentials to the download request.
	 *
	 * @param    array $credentials whatever credentials were retrieved for the current user/website
	 * @param   string $url url from which package is going to be downloaded
	 * @param   array  $headers headers to be sent along the download request (key => value format)
	 *
	 * @return void
	 */
	private function bindCredentials($credentials, &$url, &$headers)
	{
		$headers['X-download-auth-ts'] = time();
		$headers['X-download-auth-id'] = $credentials['id'];
		$headers['X-download-auth-token'] = sha1($headers['X-download-auth-ts'] . mt_rand() . $credentials['secret'] . $url);
		$headers['X-download-auth-sig'] = sha1(
			$credentials['id'] . $headers['X-download-auth-token'] . $credentials['secret'] . $headers['X-download-auth-ts'] . $this->productId
			. WbampHelper_Edition::$id
		);
	}

	/**
	 * Retrieve user credentials
	 *
	 * @param $url
	 * @param $headers
	 *
	 * @return array credentials (id, secret)
	 */
	private function fetchCredentials($url, $headers)
	{
		// fetch credentials from extension parameters
		$credentials = array(
			'id'     => trim($this->params->get('update_credentials_access', '')),
			'secret' => trim($this->params->get('update_credentials_secret', ''))
		);

		if (
			WbampHelper_Edition::$id == 'full'
			&&
			(empty($credentials['id']) || empty($credentials['secret']))
		)
		{
			$this->app->enqueueMessage(
				JText::sprintf('COM_WBAMP_UPDATE_NO_CREDENTIALS', 'https://weeblr.com/documentation/products.wbamp/1/installation-update/updating.html'),
				'error'
			);
			$this->app->redirect('index.php?option=com_installer&view=update');
		}
		return $credentials;
	}
}
