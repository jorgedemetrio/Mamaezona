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
defined('_JEXEC') || die;

?>
<div submit-success class="wbamp-form-status-success">
    <template type="amp-mustache">
        <div class="wbamp-form-status wbamp-form-status-success">{{^message}}Thank you for sending a message!{{/message}}{{#message}}{{{message}}}{{/message}} {{#link}}<a class="button wbamp-form-status-success" href="{{link}}"><?php echo JText::_('JNEXT'); ?></a>{{/link}}</div>
    </template>
</div>
<div submit-error class="wbamp-form-status-error">
    <template type="amp-mustache">
        <div class="wbamp-form-status wbamp-form-status-success-error">{{^message}}Sorry, something went wrong. Please try again later.{{/message}}{{#message}}<div class="message">{{{message}}}</div>{{/message}}{{#has_errors}}<ul>{{/has_errors}}{{#errors}}<li>{{{error_detail}}}</li>{{/errors}}{{#has_errors}}</ul>{{/has_errors}}</div>
    </template>
</div>
