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

class WbampHelper_Content
{

	/**
	 * @var WbampModel_Contentprotector Singleton for forms protector
	 */
	private static $formProtector = null;

	/**
	 * @var WbampModel_Contentprotector Singleton for forms converter
	 */
	private static $formConverter = null;

	/**
	 * Removes all {wbamp} tags from a string
	 *
	 * @param $content
	 *
	 * @return mixed
	 */
	public static function scrubRegularHtmlPage($content)
	{
		// shortcut
		if (empty($content) || strpos($content, '{wbamp') == false || strpos($content, '{wbamp-no-scrub}') != false)
		{
			$content = str_replace('{wbamp-no-scrub}', '', $content);

			return $content;
		}

		// remove content that should only be displayed on AMP pages
		$regExp = '#{wbamp-show start}.*{wbamp-show end}#iuUs';
		$content = preg_replace($regExp, '', $content);

		// remove all remaining {wbamp tags
		$regex = '#{wbamp([^}]*)}#um';
		$content = preg_replace($regex, '', $content);

		return $content;
	}

	/**
	 * Protect a piece of content against standard AMP sanitization, so that
	 * a form-specific sanitization can be applied instead.
	 *
	 * @param string $content
	 *
	 * @return string
	 */
	public static function protectForm($content)
	{
		if (is_null(self::$formProtector))
		{
			self::$formProtector = new WbampModel_Contentprotector;
			self::$formConverter = new WbampModel_Postprocess(
				true,
				array(
					'config' => new WbampModel_Configform()
				)
			);
		}

		// convert action tag
		$lContent = JString::strtolower($content);
		if (strpos($lContent, 'method="post"') !== false
			||
			strpos($lContent, "method='post'") !== false
		)
		{
			$content = preg_replace(
				'#<form(.*)action=([\'|"])#sUu',
				'<form$1action-xhr=$2',
				$content
			);
		}

		// target must be there as well
		if (strpos($lContent, 'target="') === false
			&&
			strpos($lContent, "target='") === false
		)
		{
			$content = str_replace(
				'<form ',
				'<form target="_top" ',
				$content
			);
		}

		// remove and store the form, and replace it with marker
		return self::$formProtector->protect(
			$content,
			self::$formConverter
		);
	}

	/**
	 * Injects back protected content, such as forms, after optionnally processing it
	 * with the provided AMP processor.
	 *
	 * @param string $pageContent
	 * @param string $tag
	 *
	 * @return string
	 */
	public static function injectProtectedForms($pageContent, $tag = null)
	{
		if (is_null(self::$formProtector))
		{
			return $pageContent;
		}

		return self::$formProtector->injectProtectedContent(
			$pageContent,
			$tag
		);
	}

	/**
	 * Add AMP scripts to the page.
	 *
	 * @param array $scripts
	 */
	public static function addScripts($scripts)
	{
		WbampModel_Assetscollector::getInstance()->addScripts($scripts);
	}

	/**
	 * Add AMP template to the page.
	 *
	 * @param array $templates
	 */
	public static function addTemplates($templates)
	{
		WbampModel_Assetscollector::getInstance()->addTemplates($templates);
	}
}
