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

if (!empty($displayData['amp_scripts'])):

	foreach ($displayData['amp_scripts'] as $element => $script) :
		?>
<script custom-element="<?php echo $this->escape($element, ENT_QUOTES); ?>" src="<?php echo JRoute::_($script); ?>" async></script>
		<?php
	endforeach;
endif;

if (!empty($displayData['amp_templates'])):

foreach ($displayData['amp_templates'] as $element => $template) :
	?>
    <script custom-template="<?php echo $this->escape($element, ENT_QUOTES); ?>" src="<?php echo JRoute::_($template); ?>" async></script>
	<?php
endforeach;

endif;
