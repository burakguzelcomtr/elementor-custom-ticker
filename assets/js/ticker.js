/**
 * Elementor Custom Ticker JavaScript
 */
(function($) {
    'use strict';

    /**
     * Ticker Widget
     */
    var TickerHandler = function($scope, $) {
        var $ticker = $scope.find('.elementor-ticker-wrapper'),
            speed = $ticker.data('speed'),
            direction = $ticker.data('direction'),
            verticalAlignment = $ticker.data('vertical-alignment') || 'center',
            $tickerItems = $ticker.find('.elementor-ticker-items');

        if (!$ticker.length) {
            return;
        }

        // Calculate the total width of all ticker items
        var totalWidth = 0;
        $ticker.find('.elementor-ticker-item').each(function() {
            totalWidth += $(this).outerWidth(true);
        });

        // Make sure we have enough items to create a continuous scroll effect
        // by duplicating items until we have enough to fill the screen width multiple times
        var viewportWidth = $(window).width();
        var itemsHtml = $tickerItems.html();
        var multiplier = Math.ceil((viewportWidth * 3) / totalWidth);

        if (multiplier > 1) {
            var newHtml = '';
            for (var i = 0; i < multiplier; i++) {
                newHtml += itemsHtml;
            }
            $tickerItems.html(newHtml);

            // Recalculate total width after duplication
            totalWidth = 0;
            $ticker.find('.elementor-ticker-item').each(function() {
                totalWidth += $(this).outerWidth(true);
            });
        }

        // Set animation duration based on speed and total width
        var duration = totalWidth / speed * 1000; // Convert to milliseconds

        // Apply the animation duration
        $tickerItems.css({
            'animation-duration': duration + 'ms'
        });

        // Ensure the ticker starts correctly
        setTimeout(function() {
            $ticker.css('visibility', 'visible');
        }, 100);
    };

    // Make sure we initialize the widget when it's added to the page
    $(window).on('elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/ticker.default', TickerHandler);
    });

})(jQuery);
