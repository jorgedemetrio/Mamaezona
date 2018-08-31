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
 * Wrapper class for config and runtime data
 *
 */
class WbampHelper_Runtime
{
	const CONTENT_MAX_WIDTH = 479;

	public static  $base             = null;
	public static  $basePath         = null;
	public static  $layoutsBasePaths = array();
	public static  $params           = null;
	public static  $joomlaConfig     = null;
	public static  $disqusModel      = null;
	private static $isStandaloneMode = null;
	private static $isMobile         = null;
	private static $isTablet         = null;
	private static $isForcedMobile   = null;

	/**
	 * List of embedable tags
	 * @TODO implement: vine, add: pinterest, vimeo
	 * @var array
	 */
	public static $embedTags = array(

		// auto embeddable URLs
		'twitter' => array(
			'url_regexp' => '#http(s|):\/\/twitter\.com(\/\#\!\/|\/)([a-zA-Z0-9_]{1,20})\/status(es)*\/(\d+)#iu',
			'script' => 'twitter',
			'amp_tag' => 'amp-twitter')
		,

		'instagram' => array(
			'url_regexp' => '#https?:\/\/(?:www\.)?instagr(?:\.am|am\.com)/p/([a-zA-Z0-9]+)#iu',
			'script' => 'instagram',
			'amp_tag' => 'amp-instagram')
		,
		'vine' => array(
			'url_regexp' => '#https?:\/\/vine\.co/v/([^/?]+)#iu',
			'script' => 'vine',
			'amp_tag' => 'amp-vine')
		,
		'youtube' => array(
			'url_regexp' => '#(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v=|(?:embed|v|e)\/))([a-zA-Z0-9-&;=]+)#iu',
			'script' => 'youtube',
			'amp_tag' => 'amp-youtube')
		,
		'dailymotion' => array(
			'url_regexp' => '#https?:\/\/(?:www\.)?dailymotion\.com\/video\/([^_]+)#iu',
			'script' => 'dailymotion',
			'amp_tag' => 'amp-dailymotion')
		,
		'facebook' => array(
			'url_regexp' => '#https?:\/\/(?:www\.)?(?:facebook\.com\/)([^\/]+)\/(posts|videos)\/(\d+)#iu',
			'script' => 'facebook',
			'amp_tag' => 'amp-facebook')
		,

		// AMP extended elements
		'carousel' => array(
			'url_regexp' => '',
			'script' => 'carousel',
			'amp_tag' => 'amp-carousel')
		,
		'user-notification' => array(
			'url_regexp' => '',
			'script' => 'user-notification',
			'amp_tag' => 'amp-user-notification')
	);

	public static $selfClosingTags = array(
		'br', 'meta', 'link'
	);

	/**
	 * Detect if standalone mode is enabled and valid.
	 *
	 * @return bool
	 */
	public static function isStandaloneMode()
	{
		if (is_null(self::$isStandaloneMode))
		{
			if (!WbampHelper_Edition::$id == 'full')
			{
				self::$isStandaloneMode = false;
			}
			else
			{
				self::$isStandaloneMode = self::$params->get('operation_mode', 'normal') == 'standalone';
			}
		}

		return self::$isStandaloneMode;
	}

	/**
	 * Detects if current request is from a mobile device.
	 *
	 * @return bool
	 */
	public static function isMobile()
	{
		if (is_null(self::$isMobile))
		{
			if (!WbampHelper_Edition::$id == 'full')
			{
				self::$isMobile = false;
			}
			else
			{
				self::$isMobile = ShlSystem_Mobiledetect::getInstance()->isMobile();
			}
		}

		return self::$isMobile;
	}

	/**
	 * Detects if current request is from a tablet device.
	 *
	 * @return bool
	 */
	public static function isTablet()
	{
		if (is_null(self::$isTablet))
		{
			if (!WbampHelper_Edition::$id == 'full')
			{
				self::$isTablet = false;
			}
			else
			{
				self::$isTablet = ShlSystem_Mobiledetect::getInstance()->isTablet();
			}
		}

		return self::$isTablet;
	}

	/**
	 * Whether the request is from a mobile AND user
	 * set to serve AMP on all mobile requests.
	 *
	 * @return bool
	 */
	public static function isForcedMobile()
	{
		if (is_null(self::$isForcedMobile))
		{
			if (!WbampHelper_Edition::$id == 'full')
			{
				self::$isForcedMobile = false;
			}
			else
			{
				$setting = self::$params->get('force_on_mobile', 'disabled');
				switch ($setting)
				{
					case 'mobile':
						$isUserMobile = self::isMobile() && !self::isTablet();
						$setting = 'mobile' == self::$params->get('force_on_mobile', 'disabled');
						break;
					case 'mobile_tablet';
						$isUserMobile = self::isMobile();
						$setting = 'mobile_tablet' == self::$params->get('force_on_mobile', 'disabled');
						break;
					default:
						$isUserMobile = false;
						$setting = false;
						break;
				}

				if ($isUserMobile)
				{
					// this is a mobile request, per user settings
					/**
					 * Allow overriding forced AMP on a mobile request. This filter is triggered only if
					 * the current request is a mobile one, as defined by the user setting (ie phones or phones & tablets)
					 *
					 * @api
					 * @package wbAMP\filter\system
					 * @var wbamp_force_amp_on_mobile_override
					 * @since   1.9.0
					 *
					 * @param bool $ampOnMobileOverride Set to true to force AMP on a mobile request, to false to force to non-amp.
					 *
					 * @return bool
					 */
					self::$isForcedMobile = ShlHook::filter('wbamp_force_amp_on_mobile_override', $setting);
				}
				else
				{
					self::$isForcedMobile = false;
				}
			}
		}

		return self::$isForcedMobile;
	}

	/**
	 * Testing hack
	 */
	public static function reset()
	{
		self::$isStandaloneMode = null;
		self::$isMobile = null;
		self::$isForcedMobile = null;
	}
}
