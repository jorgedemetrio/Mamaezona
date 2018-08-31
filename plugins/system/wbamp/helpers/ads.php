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

class WbampHelper_Ads
{
	/**
	 * Finds out whether ads should be displayed on a given position.
	 *
	 * @param string $position The name of the display position.
	 * @param array  $displayData The full set of data gathered for page rendering.
	 *
	 * @return bool
	 */
	public static function shouldShow($position, $displayData)
	{
		$shouldShow = false;

		if (WbampHelper_Edition::$id != 'full')
		{
			return $shouldShow;
		}

		$adsDisabled = wbArrayGet($displayData, array('user_set_data', 'no_ads'), false);
		if ($adsDisabled)
		{
			return $shouldShow;
		}

		$params = wbArrayGet($displayData, 'params');
		if (empty($params))
		{
			return $shouldShow;
		}

		$shouldShow = $params->get('ads_location', 'hidden') == $position;

		return $shouldShow;
	}
}
