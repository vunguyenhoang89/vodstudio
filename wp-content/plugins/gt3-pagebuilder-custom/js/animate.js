/*
 * jQuery appear plugin
 *
 * Copyright (c) 2012 Andrey Sidorov
 * licensed under MIT license.
 *
 * https://github.com/morr/jquery.appear/
 *
 * Version: 0.3.3
 */
(function($) {
    var selectors = [];

    var check_binded = false;
    var check_lock = false;
    var defaults = {
        interval: 250,
        force_process: false
    }
    var $window = jQuery(window);

    var $prior_appeared;

    function process() {
        check_lock = false;
        for (var index = 0; index < selectors.length; index++) {
            var $appeared = jQuery(selectors[index]).filter(function() {
                return jQuery(this).is(':appeared');
            });

            $appeared.trigger('appear', [$appeared]);

            if ($prior_appeared) {
                var $disappeared = $prior_appeared.not($appeared);
                $disappeared.trigger('disappear', [$disappeared]);
            }
            $prior_appeared = $appeared;
        }
    }

    // "appeared" custom filter
    jQuery.expr[':']['appeared'] = function(element) {
        var $element = jQuery(element);
        if (!$element.is(':visible')) {
            return false;
        }

        var window_left = $window.scrollLeft();
        var window_top = $window.scrollTop();
        var offset = $element.offset();
        var left = offset.left;
        var top = offset.top;

        if (top + $element.height() >= window_top &&
            top - ($element.data('appear-top-offset') || 0) <= window_top + $window.height() &&
            left + $element.width() >= window_left &&
            left - ($element.data('appear-left-offset') || 0) <= window_left + $window.width()) {
            return true;
        } else {
            return false;
        }
    }

    jQuery.fn.extend({
        // watching for element's appearance in browser viewport
        appear: function(options) {
            var opts = jQuery.extend({}, defaults, options || {});
            var selector = this.selector || this;
            if (!check_binded) {
                var on_check = function() {
                    if (check_lock) {
                        return;
                    }
                    check_lock = true;

                    setTimeout(process, opts.interval);
                };

                jQuery(window).scroll(on_check).resize(on_check);
                check_binded = true;
            }

            if (opts.force_process) {
                setTimeout(process, opts.interval);
            }
            selectors.push(selector);
            return jQuery(selector);
        }
    });

    jQuery.extend({
        // force elements's appearance check
        force_appear: function() {
            if (check_binded) {
                process();
                return true;
            };
            return false;
        }
    });
})(jQuery);

var isMobile = false;

(function( $ ) {
    "use strict";

    jQuery(function() {

        /* --- MOBILE DETECT --- */
        if (navigator.userAgent.match(/Android/i) ||
            navigator.userAgent.match(/webOS/i) ||
            navigator.userAgent.match(/iPhone/i) ||
            navigator.userAgent.match(/iPad/i)||
            navigator.userAgent.match(/iPod/i) ||
            navigator.userAgent.match(/BlackBerry/i)) {
            isMobile = true;
        }

        /* --- ADD NECESSARY CLASS --- */
        if (isMobile == true) {
            jQuery('.animated').removeClass('animated');
            jQuery('.op0').removeClass('op0');
        }

        /* --- ANIMATE CONTENT --- */
        if (isMobile == false) {
            jQuery('*[data-animated]').addClass('animated');
        }

        function animated_contents() {
            jQuery(".animated:appeared").each(function (i) {
                var $this    = jQuery(this),
                    animated = jQuery(this).data('animated');

                setTimeout(function () {
                    $this.addClass(animated);
                }, 100 * i);

            });
        }

        if (isMobile == false) {
            animated_contents();
            jQuery(window).scroll(function () {
                animated_contents();
            });
        }
    });

}(jQuery));