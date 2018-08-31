<?php
/**
 * wbAMP - Accelerated Mobile Pages for Joomla!
 *
 * @author      Yannick Gaultier
 * @copyright   (c) Yannick Gaultier - Weeblr llc - 2017
 * @package     wbAmp
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @version     1.12.0.790
 * @date		2018-05-16
 */

defined('_JEXEC') or die();

class WbampHelper_Edition
{
	public static $version = '1.12.0.790';
	public static $id = 'community';
	public static $name = 'Community Edition';
	public static $url = 'https://weeblr.com/joomla-accelerated-mobile-pages/wbamp';

	public static function is($edition)
	{
		return $edition == self::$id;
	}
}
