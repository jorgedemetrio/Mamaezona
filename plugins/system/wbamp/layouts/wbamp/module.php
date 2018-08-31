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

$moduleData = wbArrayGet($displayData['module'], 'def', null);
$definition = wbArrayGet($displayData, array('module', 'def'), null);
if (empty($definition))
{
	return '';
}

$params = new JRegistry();
$params->loadString($definition->params);
$title = '';
$showTitle = !empty($definition->showtitle);
if ($showTitle)
{
	$title = empty($definition->title) ? '' : $definition->title;
	if (!empty($title))
	{
		$tag = $params->get('header_tag', 'h3');
		$class = $params->get('header_class', '');
		$class = empty($class) ? '' : ' class="' . $class . '"';
		$title = '<' . $tag . $class . '>' . $this->escape($title) . '</' . $tag . '>';
	}
}

?>

<div class="wbamp-container wbamp-module wbamp-module-id-<?php echo $definition->id; ?>">
	<?php
	echo $title;
	echo wbArrayGet($displayData['module'], 'content', '');
	?>
</div>
