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

/*! Copyright Weeblr llc 2017 - Licence: http://www.gnu.org/copyleft/gpl.html GNU/GPL */

;
(function (_app, window, document, $) {
    "use strict";

    /**
     * Implementation
     */

    function refresh() {
        var $this = $(this);
        var dataTarget = $this.data('data_target');
        var $target = $(dataTarget);
        if (!$target.length) {
            console.error('wbAMP: Target not found after change');
            return;
        }
        var component = $this.val();

        // rebuild target select option
        refreshTarget(component, '#' + $this.attr('id'), dataTarget, $target);
    }

    function refreshTarget(component, dataSource, dataTarget, $target) {
        $target.empty();
        if (!component || !weeblrApp.wbAMPViewsPerComponent['components'] || !weeblrApp.wbAMPViewsPerComponent['components'][component]) {
            $target.trigger('liszt:updated');
            return;
        }
        $.each(
            weeblrApp.wbAMPViewsPerComponent['components'][component],
            function (key, value) {

                var selectedItems = weeblrApp.wbAMPViewsPerComponent['selected'][dataSource] || [];
                var selected = value['name'] == selectedItems || selectedItems.indexOf(value['name']) !== -1;

                var content = '<option value="' + value['name'] + '"' + (selected ? ' selected="selected"' : '') + '>' + value['display_name'] + '</option>';
                $target.append(
                    $(content)
                );
            }
        );
        $target.trigger('liszt:updated');
    }

    function initializeElement() {
        var $element = $(this);
        var elementId = $element.attr('id');
        var dataSource = $element.data('data_source');
        var $dataSource = dataSource ? $('#' + dataSource) : null;
        if (!$dataSource || !$dataSource.length) {
            console.error('wbAMP: DataSource not found');
            return;
        }
        $dataSource.on('change', refresh);
        $dataSource.data('data_target', '#' + elementId);

        // data first load
        var t = document.getElementById(dataSource);
        refresh.bind(t)();
    }

    function initialize(className) {
        $(className).each(initializeElement);
    }

    function onReady() {
        try {
            initialize('.wb-dynamic-list');
        }
        catch (e) {
            console.log('Error setting up dynamic list: ' + e.message);
        }
    }

    $(document).ready(onReady);

    return _app;

})
(window.weeblrApp = window.weeblrApp || {}, window, document, jQuery);


