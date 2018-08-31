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

if (empty($displayData['main_content']))
{
	return;
}

?>
<div class="wbamp-container wbamp-content">
	<?php echo $displayData['main_content']; ?>
	<?php
	// Optional disqus
	if (WbampHelper_Edition::$id == 'full')
	{
		if ($displayData['params']->get('disqus_comments_enabled', false))
		{
			echo ShlMvcLayout_Helper::render(
				'wbamp.features.comments_disqus',
				$displayData,
				WbampHelper_Runtime::$layoutsBasePaths
			);
		}
	}
	?>
</div>
