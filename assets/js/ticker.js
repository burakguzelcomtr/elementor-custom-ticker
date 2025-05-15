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

        // Get the original items
        var $originalItems = $ticker.find('.elementor-ticker-item');

        // If no items, return
        if (!$originalItems.length) {
            return;
        }

        // Clone the items multiple times to ensure we have enough for a continuous loop
        var itemsHtml = $tickerItems.html();
        // Add multiple copies to ensure there's always content visible
        $tickerItems.append(itemsHtml + itemsHtml + itemsHtml);

        // Calculate the total width of all ticker items
        var totalWidth = 0;
        $ticker.find('.elementor-ticker-item').each(function() {
            totalWidth += $(this).outerWidth(true);
        });

        // Get the width of one set of items (original items)
        var originalWidth = totalWidth / 4; // Divide by 4 since we now have 4 copies (1 original + 3 clones)

        // Make sure originalWidth is not zero
        if (originalWidth <= 0) {
            originalWidth = 500; // Default width if calculation fails
        }

        // Set the vertical alignment based on the data attribute
        var verticalTransform;
        if (verticalAlignment === 'flex-start') {
            $tickerItems.css('top', '0');
            verticalTransform = '0';
        } else if (verticalAlignment === 'flex-end') {
            $tickerItems.css('top', '100%');
            verticalTransform = '-100';
        } else {
            $tickerItems.css('top', '50%');
            verticalTransform = '-50';
        }

        // Create a truly unique ID for this ticker instance
        var tickerId = 'ticker-' + Date.now() + '-' + Math.floor(Math.random() * 1000000);

        console.log('Ticker ID:', tickerId);

        // Calculate the animation duration based on speed and width
        var duration = originalWidth / speed * 1000; // Convert to milliseconds

        // Make sure duration is not too short or too long
        if (duration < 1000) {
            duration = 1000; // Minimum 1 second
        } else if (duration > 60000) {
            duration = 60000; // Maximum 1 minute
        }

        // Create the keyframes for the animation with the unique animation name
        var keyframes;
        if (direction === 'right') {
            // For right direction (items move from left to right)
            // Start from negative position (off-screen left) to create a continuous effect
            keyframes = '@keyframes ticker-' + tickerId + ' {' +
                '0% { transform: translate3d(-' + originalWidth + 'px, ' + verticalTransform + '%, 0); }' +
                '100% { transform: translate3d(0, ' + verticalTransform + '%, 0); }' +
            '}';
        } else {
            // For left direction (items move from right to left)
            // End at negative position (off-screen left) to create a continuous effect
            keyframes = '@keyframes ticker-' + tickerId + ' {' +
                '0% { transform: translate3d(0, ' + verticalTransform + '%, 0); }' +
                '100% { transform: translate3d(-' + originalWidth + 'px, ' + verticalTransform + '%, 0); }' +
            '}';
        }

        console.log('Keyframes:', keyframes);
        console.log('Original width:', originalWidth);

        // Add the keyframes to the document
        var styleSheet = document.createElement('style');
        styleSheet.textContent = keyframes;
        document.head.appendChild(styleSheet);

        // Position the ticker items initially for a smooth start
        if (direction === 'right') {
            // For right direction, start from off-screen left
            $tickerItems.css('transform', 'translate3d(-' + originalWidth + 'px, ' + verticalTransform + '%, 0)');
        } else {
            // For left direction, start from the beginning
            $tickerItems.css('transform', 'translate3d(0, ' + verticalTransform + '%, 0)');
        }

        // Apply the animation to the ticker items with the unique animation name
        $tickerItems.css({
            'animation-name': 'ticker-' + tickerId,
            'animation-duration': duration + 'ms',
            'animation-timing-function': 'linear',
            'animation-iteration-count': 'infinite',
            'animation-delay': '0s',
            'animation-direction': 'normal',
            'animation-fill-mode': 'forwards',
            'animation-play-state': 'running'
        });

        console.log('Animation applied:', 'ticker-' + tickerId, duration + 'ms');

        // When the animation completes one cycle, reset it to create a seamless loop
        $tickerItems.on('animationiteration', function() {
            // The animation will automatically restart due to the 'infinite' setting
        });

        // Make the ticker visible
        setTimeout(function() {
            $ticker.css('visibility', 'visible');
        }, 100);
    };

    // Make sure we initialize the widget when it's added to the page
    $(window).on('elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/ticker.default', TickerHandler);
    });

})(jQuery);
