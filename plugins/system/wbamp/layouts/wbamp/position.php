<?php
/**
 * wbAMP - Accelerated Mobile Pages for Joomla!
 *
 * @author      Yannick Gaultier
 * @copyright   (c) Yannick Gaultier - Weeblr llc - 2017
 * @package     wbAmp
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @version     1.12.0.790
 * @date        2018-05-16
 */

// no direct access
defined('_JEXEC') or die;

if (empty($displayData) || !is_array($displayData))
{
	return '';
}
$position = wbArrayGet($displayData[0], 'position', 'default');

?>
<div class="wbamp-container <?php echo $position; ?>-modules">
	<?php
	foreach ($displayData as $module)
	{
		echo wbArrayGet($module, 'content', '');
	}
	?>
</div>
