/**
 * jQuery vkSlidedoor 1.1
 *
 * Copyright 2012-2014, Van Khuong (www.sothichweb.com)
 * Licensed under the MIT
 * Released on: Nov 21, 2012
 *
 * Tut: http://sothichweb.com/article/xay-dung-plugin-slide-door-voi-jquery/d967abd
 * Demo: http://sothichweb.com/Demos/slidedoor/Demo-xay-dung-plugin-slidedoor-voi-jquery.html
 *
 * Git: https://github.com/vankhuong/slidedoor/
 */

(function ($) {

    var def = {
        speed: 500,
        easing: ''
    };

    $.fn.vkSlidedoor = function (cfg) {

        // configuration application
        var ops = {
            wrapper: '.slidedoor-wrap',
            dl: 'dl', // children of wrapper 
            trigger: 'dt',
            autoplay: false,
            direction: 'ltr', // or 'rtl'(right to left), direction slide-door, apply for the mode autoplay is true
            looptimeout: 5000, // set timeout for slide-door, apply for the mode autoplay is true
            dtwidth: 45,
            ddpadleft: 1,
            ddwidth: 334
        };

        $.extend(ops, cfg);
        $.extend(def, cfg);

        return this.each(function () {
            var $this = $(this),
                $dls = $this.find(ops.dl),
                $dts = $this.find(ops.trigger),
                $ele = $this.find(ops.wrapper);

            // Default set left CSS for dl elements
            var i = 0;
            $dls.each(function () {
                $(this).css("left", i + "px");
                i += ops.dtwidth;
            });
            //caculate width of over element
            var overwidth = $dls.length * ops.dtwidth + ops.ddwidth - ops.ddpadleft,
                mainwidth = overwidth + ops.ddwidth + 10;
            $this.width(overwidth);

            // Default set width css dor slidedoor-wrap element
            $ele.width(mainwidth);

            // Set CSS default open door for current dl elements
            $this.find(ops.dl + '.current').nextAll(ops.dl).elemsMove(ops.ddwidth);

            if (ops.autoplay) {
                var timeout = setInterval(function () {
                    $ele.activeDoors(ops);
                }, ops.looptimeout);
            }
            $dts.click(function () {
                if (ops.autoplay) {
                    clearInterval(timeout);
                }
                // get index of this element
                var indexthis = $dts.index(this);
                $ele.activeDoors(ops, indexthis);
            }); //end click
        }); //end return 
    };

    // active animation the doors
    $.fn.activeDoors = function (ops, indexthis) {
        // get current element and index, get dl elements
        var $curr = this.find('.current').removeClass('current'),
            indexcurr = $curr.index(),
            $dls = this.find(ops.dl),
            dlslen = $dls.length;

        // animation with current element and this element with click event context
        if (arguments.length == 2) {
            $dls.each(function (index) {
                // if this element affter current element
                if (index <= indexthis && index > indexcurr) {
                    $(this).elemsMove(-ops.ddwidth);
                    // if this element before current element
                } else if (index <= indexcurr && index > indexthis) {
                    $(this).elemsMove(ops.ddwidth);
                }
                // add current class for this element
                if (index == indexthis) {
                    $(this).addClass('current');
                }
            });
            // animation for element next in the automatic mode
        } else if (ops.direction == 'ltr') {
            // if current element is last child dl element
            if (indexcurr == (dlslen - 1)) {
                this.find(ops.dl + ':first-child').addClass('current').nextAll(ops.dl).elemsMove(ops.ddwidth);
            } else {
                $curr.next().elemsMove(-ops.ddwidth).addClass('current');
            }
        } else if (ops.direction == 'rtl') {
            // if current element is first child dl element
            if (indexcurr == 0) {
                $curr.nextAll(ops.dl).elemsMove(-ops.ddwidth);
                this.find(ops.dl + ':last-child').addClass('current');
            } else {
                $curr.elemsMove(ops.ddwidth);
                $curr.prev().addClass('current');
            }
        }
    };

    // animation the doors with value of position
    $.fn.elemsMove = function (val) {
        return this.each(function () {
            var leftcurr = $(this).css('left'),
                newcurr = parseInt(leftcurr) + val;
            $(this).stop(true, true).animate({
                left: newcurr
            }, def.speed, def.easing);
        });
    };

})(jQuery);

