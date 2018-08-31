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

/**
 * Plugin Name: AMP
 * Description: Add AMP support to your WordPress site.
 * Plugin URI: https://github.com/automattic/amp-wp
 * Author: Automattic
 * Author URI: https://automattic.com
 * Version: 0.3
 * Text Domain: amp
 * Domain Path: /languages/
 * License: GPLv2 or later
 */

defined('_JEXEC') or die();

class WbampHelper_Dom
{

	public static function fromContent($content)
	{
		$content = empty($content) ? '' : self::escapeScripts($content);

		$libxml_previous_state = libxml_use_internal_errors(true);

		$dom = new DOMDocument;
		// Wrap in dummy tags, since XML needs one parent node.
		// It also makes it easier to loop through nodes.
		// We can later use this to extract our nodes.
		// Add utf-8 charset so loadHTML does not have problems parsing it. See: http://php.net/manual/en/domdocument.loadhtml.php#78243
		$result = $dom->loadHTML('<html><head><meta http-equiv="content-type" content="text/html; charset=utf-8"></head><body>' . $content . '</body></html>');

		libxml_clear_errors();
		libxml_use_internal_errors($libxml_previous_state);

		if (!$result)
		{
			return false;
		}

		return $dom;
	}

	/**
	 * Escape closing tags inside of script tags in incoming content, as
	 * this otherwise breaks DOMDocument parsing.
	 *
	 * <script type="text/javascript">
	 * /* <![CDATA[ * /
	 *function bad()
	 * {
	 * var html = '<div>Sample text</div>';
	 * }
	 * /* ]]> * /
	 * </script>
	 *
	 * Without escaping, the closing </div> tag is stripped by DOMDocument.
	 *
	 * @param string $content
	 *
	 * @return string
	 */
	private static function escapeScripts($content)
	{
		$content = preg_replace_callback(
			"#<script(.*?)</script>#isu",
			function ($matches) {

				if (empty($matches[1]))
				{
					return $matches[0];
				}

				$filtered = str_replace(
					'</',
					'__WBAMP_ESCAPE_HTML_CLOSING_TAG__',
					$matches[1]
				);

				return '<script' . $filtered . '</script>';
			},
			$content
		);

		return $content;
	}

	public static function fromDom($dom, $config = null)
	{
		// Only want children of the body tag, since we have a subset of HTML.
		$out = '';
		$body = $dom->getElementsByTagName('body')->item(0);

		// remove CDATA from places where they cause amp elements to choke
		// #1: http://stackoverflow.com/questions/6685117/how-to-stop-php-domdocumentsavexml-from-inserting-cdata
		// --> use DOM processing
		// #2: http://stackoverflow.com/questions/8283588/how-to-remove-cdata-and-end
		// --> use a global regexp
		// Using #1 in case there are other script tags in the future that may require CDATA?
		$replaced = false;
		$scripts = $body->getElementsByTagName('script');
		if (!empty($scripts))
		{
			foreach ($scripts as $scriptElement)
			{
				$replaced = true;
				// avoid unterminated entity error if scripts contains ampersands
				$scriptElement->nodeValue = str_replace(
					array(
						'&',
						'<',
						'>'
					),
					array(
						'__WBAMP_ESCAPE_CHAR_AMPERSAND__',
						'__WBAMP_ESCAPE_CHAR_LT__',
						'__WBAMP_ESCAPE_CHAR_GT__'
					),
					$scriptElement->nodeValue
				);
			}
		}

		// finally output to text
		foreach ($body->childNodes as $node)
		{
			$out .= $dom->saveXML($node, LIBXML_NOEMPTYTAG);
		}

		// put back in replaced characters
		if ($replaced)
		{
			$out = str_replace(
				array(
					'__WBAMP_ESCAPE_CHAR_AMPERSAND__',
					'__WBAMP_ESCAPE_CHAR_LT__',
					'__WBAMP_ESCAPE_CHAR_GT__',
					'__WBAMP_ESCAPE_HTML_CLOSING_TAG__',
				),
				array(
					'&',
					'<',
					'>',
					'</'
				),
				$out
			);
		}

		return $out;
	}
}
