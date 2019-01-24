(function (factory) {
    var root = (typeof self == 'object' && self.self == self && self) ||
        (typeof global == 'object' && global.global == global && global);

    if (typeof define === 'function' && define.amd) {
        define(['snap', 'exports'], function (Snap, exports) {
            root.AlertDial = factory(root, exports, Snap);
        });
    } else if (typeof exports !== 'undefined') {
        var Snap = require('snapsvg');
        factory(root, exports, Snap);
    } else {
        root.AlertDial = factory(root, {}, root.Snap);
    }

}(function (root, exports, Snap) {

    var lerp = function (angle, a, b) {
        var t = (angle % 90) / 90;
        return a * (1 - t) + b * t;
    };

    var calculateDropShadowAngle = function (angle) {
        var topLeftQuadrant = angle >= 0 && angle < 90;
        var topRightQuadrant = angle >= 90 && angle < 180;
        var bottomRightQuadrant = angle >= 180 && angle < 270;
        var bottomLeftQuadrant = angle >= 270 && angle <= 360;

        if (topLeftQuadrant) {
            return [lerp(angle, 0, 8), lerp(angle, 8, 0)];
        } else if (topRightQuadrant) {
            return [lerp(angle, 8, 0), lerp(angle, 0, -8)];
        } else if (bottomRightQuadrant) {
            return [lerp(angle, 0, -8), lerp(angle, -8, 0)];
        } else if (bottomLeftQuadrant) {
            return [lerp(angle, -8, 0), lerp(angle, 0, 8)];
        }
    };

    var defaults = function (base, defaults) {
        var result = {};
        var transferProps = function (obj) {
            for (key in obj) {
                if (obj.hasOwnProperty(key)) {
                    result[key] = obj[key];
                }
            }
        }

        transferProps(defaults);
        transferProps(base);

        return result;
    };

    exports.AlertDial = function (el, options) {
        this.el = el;
        this.options = defaults(options || {}, {
            disabled: false,
            frameSize: 200,
            ringWidth: 50,
            frameBackgroundColor: 'white',
            ringBackgroundColor: '#888',
            innerBackgroundColor: 'white',
            fontFamily: 'impact',
            fontSize: 24,
            fontStyle: 'none',
            fontWeight: 'none',
            textType: 'percentage'
        });

        this.initialize();
    };

    exports.AlertDial.create = function (el, options) {
        return new exports.AlertDial(el, options);
    }

    exports.AlertDial.prototype = {

        config: function (options) {
            this.options = defaults(options, this.options);
        },

        // expecting 0...1
        setValue: function (number, total, min_percentage, max_percentage) {

            var percentage = number / total;


            //console.log(percentage)
            //console.log(min_percentage)

            //console.log(max_percentage)



            var tmp=percentage - min_percentage;
            var tmp2=max_percentage - min_percentage;


            var shifted_percentage = tmp/tmp2;

            if (shifted_percentage > 1)shifted_percentage = 1;
            if (shifted_percentage < 0)shifted_percentage = 0;

            //   if(percentage>=max_percentage){
            //    shifted_percentage=1
            //  }else{
            //  shifted_percentage=percentage/max_percentage;
            //  }

            var angle = this.convertPercentageToAngle(shifted_percentage);


            this.updateDial(number, total, percentage, angle);
        },

        initialize: function () {
            this.c = this.buildCanvas();
            this.dial = this.buildDial();

            var onChange = function (sx, sy, ax, ay, e) {
                this.updateDial(this.getAngle(ax, ay));
                this.executeCallback('onChange', [this.percentage]);
            };
            var onStart = function (x, y, e) {
                this.centerCoordinates = this.calculateDialCenterCoordinates();
                this.updateDial(this.getAngle(x, y));
                this.executeCallback('onStart', [this.percentage]);
            };
            var onEnd = function (x, y, e) {
                this.executeCallback('onEnd', [this.percentage]);
            };

            this.moveKnob(this.convertPercentageToAngle(0));
            this.innerCircle.drag(onChange, onStart, onEnd, this, this, this);
            this.outerCircle.drag(onChange, onStart, onEnd, this, this, this);

            this.executeCallback('onReady');
        },

        getAngle: function (x, y) {
            return Snap.angle(this.centerCoordinates.x, this.centerCoordinates.y, x, y);
        },

        buildCanvas: function () {
            var el;
            var svgHtml = '<svg style="width: ' + this.options.frameSize + 'px; height: ' + this.options.frameSize + 'px;"></svg>';

            if (this.el.jquery) {
                el = this.el[0];
            } else if (this.el instanceof HTMLElement) {
                el = this.el;
            } else {
                el = document.querySelector(this.el);
            }

            el.innerHTML = svgHtml;
            return Snap(el.getElementsByTagName('svg')[0]);
        },

        buildDial: function () {
            this.outerCircle = this.buildOuterCircle();
            this.innerCircle = this.buildInnerCircle();
            this.text = this.buildText();
            this.subtext = this.buildSubText();
        },

        buildOuterCircle: function () {
            var attributes = this.outerCircleAttributes = {
                x: this.options.frameSize / 2,
                y: this.options.frameSize / 2,
                radius: this.options.frameSize / 2
            }

            // describe triangle that extends from bottom left to center to bottom right
            var trianglePoints = function (frameSize) {
                return [0, 0, 0, frameSize, frameSize / 2, frameSize / 2, frameSize, frameSize, frameSize, 0];
            };

            var outerCircle = this.c.circle(
                attributes.x,
                attributes.y,
                attributes.radius
            );

            outerCircle.attr({
                fill: this.c.gradient(this.calculateFillColor(this.options.ringBackgroundColor)),
                mask: this.c.polyline(trianglePoints(this.options.frameSize)).attr({fill: this.options.frameBackgroundColor})
            });

            return outerCircle;
        },

        buildInnerCircle: function () {
            var attributes = this.innerCircleAttributes = {
                x: this.outerCircleAttributes.x,
                y: this.outerCircleAttributes.y,
                radius: this.outerCircleAttributes.radius - this.options.ringWidth
            };

            var buildDialKnob = function () {
                var left,
                    right,
                    top = this.options.ringWidth + 5,
                    distance = (attributes.x - attributes.radius) + 5;

                left = this.outerCircleAttributes.radius + (top * 0.5);
                right = this.outerCircleAttributes.radius - (top * 0.5);

                return this.c.polyline(
                    distance, right,
                    distance, left,
                    distance - top, this.outerCircleAttributes.radius
                );
            };

            var buildKnobCircle = function () {
                return this.c.circle(
                    attributes.x,
                    attributes.y,
                    attributes.radius
                )
            };

            var dropShadow = this.generateDropShadow();
            var dialKnob = buildDialKnob.call(this);

            var baseCircle = buildKnobCircle.call(this).attr({
                fill: this.calculateFillColor(this.options.innerBackgroundColor),
                filter: dropShadow
            });

            var innerGrouping = this.c.group(dialKnob).attr({
                fill: this.calculateFillColor(this.options.innerBackgroundColor),
                filter: dropShadow,
                transform: ['rotate(0', this.options.frameSize / 2, this.options.frameSize / 2].join(' ')
            });

            var capCircle = buildKnobCircle.call(this).attr({
                fill: this.calculateFillColor(this.options.innerBackgroundColor)
            });

            return innerGrouping;
        },

        buildText: function () {
            return this.c.text(
                this.options.frameSize / 2,
                this.options.frameSize / 2 + (this.options.fontSize / 3) - 10,
                '0%'
            ).attr({
                fontFamily: this.options.fontFamily,
                fontSize: this.options.fontSize,
                fontWeight: this.options.fontWeight,
                fontStyle: this.options.fontStyle,
                textAnchor: 'middle'
            });
        },

        buildSubText: function () {
            return this.c.text(
                this.options.frameSize / 2,
                this.options.frameSize / 2 + (this.options.fontSize / 3) + 10,
                '0%'
            ).attr({
                fontFamily: this.options.fontFamily,
                fontSize: this.options.fontSize * .7,
                fontWeight: this.options.fontWeight,
                fontStyle: this.options.fontStyle,
                textAnchor: 'middle'
            });
        },

        generateDropShadow: function (options) {
            var opts = defaults(options || {}, {
                x: 0,
                y: this.options.ringWidth / 20,
                blur: 3,
                color: '#000',
                opacity: 0.2
            });

            return this.c.filter(Snap.filter.shadow(opts.x, opts.y, opts.blur, opts.color, opts.opacity));
        },

        updateText: function (number, total, percentage) {


            var subtext = [Math.round(percentage * 100), '%'].join('')

            var text = number

            this.text.attr({
                text: text
            });

            this.subtext.attr({
                text: subtext
            });

        },

        calculateFillColor: function (background) {
            background = (background instanceof Array) ? background : [background];
            return 'l(0, 0.5, 1, 0.5)' + background.join('-');
        },

        convertAngleToPercentage: function (angle) {
            var startAngle = 315,
                endAngle = 225,
                value;

            if (startAngle <= angle && 360 >= angle) {
                value = 45 - (360 - angle);
            } else {
                value = angle + 45
            }

            return value / (endAngle + 45);
        },

        convertPercentageToAngle: function (percentage) {
            var startAngle = 315,
                endAngle = 225;

            var unadjustedAngle = (percentage * (endAngle + 45)) + startAngle;
            return (unadjustedAngle > 360) ? unadjustedAngle - 360 : unadjustedAngle;
        },

        moveKnob: function (angle) {

           // console.log(angle)

            if(isNaN(angle)){
                angle=315;
            }
           // console.log(angle)

            var dropShadowAlignment = calculateDropShadowAngle(angle);

       // console.log(dropShadowAlignment)
            var dropShadow = this.generateDropShadow({x: dropShadowAlignment[0], y: dropShadowAlignment[1]});

            this.innerCircle.attr({
                transform: ['rotate(', angle, this.options.frameSize / 2, this.options.frameSize / 2, ')'].join(' '),
                filter: dropShadow
            });
        },

        updateDial: function (number, total, percentage, angle) {


            if (this.options.disabled) {
                return;
            }

            // if the angle is in the white triangle area, don't do anything
            if (angle <= 315 && angle > 270) {
                angle = 315;
            } else if (angle <= 270 && angle > 225) {
                angle = 225;
            }

            this.moveKnob(angle);
            this.percentage = this.convertAngleToPercentage(angle);
            this.updateText(number, total, percentage);
        },

        calculateDialCenterCoordinates: function () {
            var boundingBox = this.outerCircle.node.getBoundingClientRect();

            return {
                x: boundingBox.left + (boundingBox.height / 2),
                y: boundingBox.top + (boundingBox.height / 2)
            }
        },

        executeCallback: function (type, args) {
            if (this.options[type]) {
                this.options[type].apply(null, args);
            }
        }

    };

    return exports.AlertDial;

}));