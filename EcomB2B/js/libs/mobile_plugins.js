
 /*

    Template Name:      Enabled Mobile & Tablet Templates
    Theme URL:          http://enableds.com
    Author:             Enabled
    Author URI:         http://themeforest.net/user/Enabled?ref=Enabled
    Author Facebook:    https://www.facebook.com/enabled.labs
    Author Twitter:     https://twitter.com/iEnabled
    Version:            4.0
    Envato License:     Regular or Extended via ThemeForest
    Plugin Licenses:    GPL / MIT - Redistribution Allowed
                        Each Plugin has it's indivudal license attached

    Description:        The framework plugins is built into one single JS file to allow the
                        template universal, fast access to all the items. As -webkit- browsers
                        cache the JS on load, this asures best loading times on all platforms
                        and at the same time asures you will find everything you need in one
                        single place.

                        The custom.js file is where all scripts should be imported for usage
                        throughout the template. If you wish to import scripts inline that's
                        completly up to you, but for a perfect function on all mobile devices.
                        Mobile devices such as Nokia and Blackberrry handle JS better if it's
                        implemented globally rather than inline throughout items.

                        Please Note! Not following the structure presented in the documentation
                        or altering custom.js and framework.plugins without proper experience
                        can lead to the item malfunctioning. We are not responsible for any
                        custom alterations and edits you make outside the as is item.

    Warning:            All the plugins in the pack have been tested on numerous mobile devices
                        running different versions of their Native OS and differen browsers.
                        Some of the plugins have been heavily altered to increase performance
                        or fix issues with differnet devices. We strongly recommend NOT to update
                        these plugins to newer versions. We constantly check and modify and update
                        them when a new, stable, proper quality version is released.
*/

 $(function () {


     /* @preserve FastClick: polyfill to remove click delays on browsers with touch UIs.  * @codingstandard ftlabs-jsv2     * @copyright The Financial Times Limited [All Rights Reserved]     * @license MIT License   */
     !function(){"use strict";function t(e,o){function i(t,e){return function(){return t.apply(e,arguments)}}var r;if(o=o||{},this.trackingClick=!1,this.trackingClickStart=0,this.targetElement=null,this.touchStartX=0,this.touchStartY=0,this.lastTouchIdentifier=0,this.touchBoundary=o.touchBoundary||10,this.layer=e,this.tapDelay=o.tapDelay||200,this.tapTimeout=o.tapTimeout||700,!t.notNeeded(e)){for(var a=["onMouse","onClick","onTouchStart","onTouchMove","onTouchEnd","onTouchCancel"],c=this,s=0,u=a.length;u>s;s++)c[a[s]]=i(c[a[s]],c);n&&(e.addEventListener("mouseover",this.onMouse,!0),e.addEventListener("mousedown",this.onMouse,!0),e.addEventListener("mouseup",this.onMouse,!0)),e.addEventListener("click",this.onClick,!0),e.addEventListener("touchstart",this.onTouchStart,!1),e.addEventListener("touchmove",this.onTouchMove,!1),e.addEventListener("touchend",this.onTouchEnd,!1),e.addEventListener("touchcancel",this.onTouchCancel,!1),Event.prototype.stopImmediatePropagation||(e.removeEventListener=function(t,n,o){var i=Node.prototype.removeEventListener;"click"===t?i.call(e,t,n.hijacked||n,o):i.call(e,t,n,o)},e.addEventListener=function(t,n,o){var i=Node.prototype.addEventListener;"click"===t?i.call(e,t,n.hijacked||(n.hijacked=function(t){t.propagationStopped||n(t)}),o):i.call(e,t,n,o)}),"function"==typeof e.onclick&&(r=e.onclick,e.addEventListener("click",function(t){r(t)},!1),e.onclick=null)}}var e=navigator.userAgent.indexOf("Windows Phone")>=0,n=navigator.userAgent.indexOf("Android")>0&&!e,o=/iP(ad|hone|od)/.test(navigator.userAgent)&&!e,i=o&&/OS 4_\d(_\d)?/.test(navigator.userAgent),r=o&&/OS [6-7]_\d/.test(navigator.userAgent),a=navigator.userAgent.indexOf("BB10")>0;t.prototype.needsClick=function(t){switch(t.nodeName.toLowerCase()){case"button":case"select":case"textarea":if(t.disabled)return!0;break;case"input":if(o&&"file"===t.type||t.disabled)return!0;break;case"label":case"iframe":case"video":return!0}return/\bneedsclick\b/.test(t.className)},t.prototype.needsFocus=function(t){switch(t.nodeName.toLowerCase()){case"textarea":return!0;case"select":return!n;case"input":switch(t.type){case"button":case"checkbox":case"file":case"image":case"radio":case"submit":return!1}return!t.disabled&&!t.readOnly;default:return/\bneedsfocus\b/.test(t.className)}},t.prototype.sendClick=function(t,e){var n,o;document.activeElement&&document.activeElement!==t&&document.activeElement.blur(),o=e.changedTouches[0],n=document.createEvent("MouseEvents"),n.initMouseEvent(this.determineEventType(t),!0,!0,window,1,o.screenX,o.screenY,o.clientX,o.clientY,!1,!1,!1,!1,0,null),n.forwardedTouchEvent=!0,t.dispatchEvent(n)},t.prototype.determineEventType=function(t){return n&&"select"===t.tagName.toLowerCase()?"mousedown":"click"},t.prototype.focus=function(t){var e;o&&t.setSelectionRange&&0!==t.type.indexOf("date")&&"time"!==t.type&&"month"!==t.type?(e=t.value.length,t.setSelectionRange(e,e)):t.focus()},t.prototype.updateScrollParent=function(t){var e,n;if(e=t.fastClickScrollParent,!e||!e.contains(t)){n=t;do{if(n.scrollHeight>n.offsetHeight){e=n,t.fastClickScrollParent=n;break}n=n.parentElement}while(n)}e&&(e.fastClickLastScrollTop=e.scrollTop)},t.prototype.getTargetElementFromEventTarget=function(t){return t.nodeType===Node.TEXT_NODE?t.parentNode:t},t.prototype.onTouchStart=function(t){var e,n,r;if(t.targetTouches.length>1)return!0;if(e=this.getTargetElementFromEventTarget(t.target),n=t.targetTouches[0],o){if(r=window.getSelection(),r.rangeCount&&!r.isCollapsed)return!0;if(!i){if(n.identifier&&n.identifier===this.lastTouchIdentifier)return t.preventDefault(),!1;this.lastTouchIdentifier=n.identifier,this.updateScrollParent(e)}}return this.trackingClick=!0,this.trackingClickStart=t.timeStamp,this.targetElement=e,this.touchStartX=n.pageX,this.touchStartY=n.pageY,t.timeStamp-this.lastClickTime<this.tapDelay&&t.preventDefault(),!0},t.prototype.touchHasMoved=function(t){var e=t.changedTouches[0],n=this.touchBoundary;return Math.abs(e.pageX-this.touchStartX)>n||Math.abs(e.pageY-this.touchStartY)>n?!0:!1},t.prototype.onTouchMove=function(t){return this.trackingClick?((this.targetElement!==this.getTargetElementFromEventTarget(t.target)||this.touchHasMoved(t))&&(this.trackingClick=!1,this.targetElement=null),!0):!0},t.prototype.findControl=function(t){return void 0!==t.control?t.control:t.htmlFor?document.getElementById(t.htmlFor):t.querySelector("button, input:not([type=hidden]), keygen, meter, output, progress, select, textarea")},t.prototype.onTouchEnd=function(t){var e,a,c,s,u,l=this.targetElement;if(!this.trackingClick)return!0;if(t.timeStamp-this.lastClickTime<this.tapDelay)return this.cancelNextClick=!0,!0;if(t.timeStamp-this.trackingClickStart>this.tapTimeout)return!0;if(this.cancelNextClick=!1,this.lastClickTime=t.timeStamp,a=this.trackingClickStart,this.trackingClick=!1,this.trackingClickStart=0,r&&(u=t.changedTouches[0],l=document.elementFromPoint(u.pageX-window.pageXOffset,u.pageY-window.pageYOffset)||l,l.fastClickScrollParent=this.targetElement.fastClickScrollParent),c=l.tagName.toLowerCase(),"label"===c){if(e=this.findControl(l)){if(this.focus(l),n)return!1;l=e}}else if(this.needsFocus(l))return t.timeStamp-a>100||o&&window.top!==window&&"input"===c?(this.targetElement=null,!1):(this.focus(l),this.sendClick(l,t),o&&"select"===c||(this.targetElement=null,t.preventDefault()),!1);return o&&!i&&(s=l.fastClickScrollParent,s&&s.fastClickLastScrollTop!==s.scrollTop)?!0:(this.needsClick(l)||(t.preventDefault(),this.sendClick(l,t)),!1)},t.prototype.onTouchCancel=function(){this.trackingClick=!1,this.targetElement=null},t.prototype.onMouse=function(t){return this.targetElement?t.forwardedTouchEvent?!0:t.cancelable&&(!this.needsClick(this.targetElement)||this.cancelNextClick)?(t.stopImmediatePropagation?t.stopImmediatePropagation():t.propagationStopped=!0,t.stopPropagation(),t.preventDefault(),!1):!0:!0},t.prototype.onClick=function(t){var e;return this.trackingClick?(this.targetElement=null,this.trackingClick=!1,!0):"submit"===t.target.type&&0===t.detail?!0:(e=this.onMouse(t),e||(this.targetElement=null),e)},t.prototype.destroy=function(){var t=this.layer;n&&(t.removeEventListener("mouseover",this.onMouse,!0),t.removeEventListener("mousedown",this.onMouse,!0),t.removeEventListener("mouseup",this.onMouse,!0)),t.removeEventListener("click",this.onClick,!0),t.removeEventListener("touchstart",this.onTouchStart,!1),t.removeEventListener("touchmove",this.onTouchMove,!1),t.removeEventListener("touchend",this.onTouchEnd,!1),t.removeEventListener("touchcancel",this.onTouchCancel,!1)},t.notNeeded=function(t){var e,o,i,r;if("undefined"==typeof window.ontouchstart)return!0;if(o=+(/Chrome\/([0-9]+)/.exec(navigator.userAgent)||[,0])[1]){if(!n)return!0;if(e=document.querySelector("meta[name=viewport]")){if(-1!==e.content.indexOf("user-scalable=no"))return!0;if(o>31&&document.documentElement.scrollWidth<=window.outerWidth)return!0}}if(a&&(i=navigator.userAgent.match(/Version\/([0-9]*)\.([0-9]*)/),i[1]>=10&&i[2]>=3&&(e=document.querySelector("meta[name=viewport]")))){if(-1!==e.content.indexOf("user-scalable=no"))return!0;if(document.documentElement.scrollWidth<=window.outerWidth)return!0}return"none"===t.style.msTouchAction||"manipulation"===t.style.touchAction?!0:(r=+(/Firefox\/([0-9]+)/.exec(navigator.userAgent)||[,0])[1],r>=27&&(e=document.querySelector("meta[name=viewport]"),e&&(-1!==e.content.indexOf("user-scalable=no")||document.documentElement.scrollWidth<=window.outerWidth))?!0:"none"===t.style.touchAction||"manipulation"===t.style.touchAction?!0:!1)},t.attach=function(e,n){return new t(e,n)},"function"==typeof define&&"object"==typeof define.amd&&define.amd?define(function(){return t}):"undefined"!=typeof module&&module.exports?(module.exports=t.attach,module.exports.FastClick=t):window.FastClick=t}();



     /*! Lazy Load 1.9.5 - MIT license - Copyright 2010-2015 Mika Tuupola */
     !function(a,b,c,d){var e=a(b);a.fn.lazyload=function(f){function g(){var b=0;i.each(function(){var c=a(this);if(!j.skip_invisible||c.is(":visible"))if(a.abovethetop(this,j)||a.leftofbegin(this,j));else if(a.belowthefold(this,j)||a.rightoffold(this,j)){if(++b>j.failure_limit)return!1}else c.trigger("appear"),b=0})}var h,i=this,j={threshold:0,failure_limit:0,event:"scroll",effect:"show",container:b,data_attribute:"original",skip_invisible:!1,appear:null,load:null,placeholder:"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAANSURBVBhXYzh8+PB/AAffA0nNPuCLAAAAAElFTkSuQmCC"};return f&&(d!==f.failurelimit&&(f.failure_limit=f.failurelimit,delete f.failurelimit),d!==f.effectspeed&&(f.effect_speed=f.effectspeed,delete f.effectspeed),a.extend(j,f)),h=j.container===d||j.container===b?e:a(j.container),0===j.event.indexOf("scroll")&&h.bind(j.event,function(){return g()}),this.each(function(){var b=this,c=a(b);b.loaded=!1,(c.attr("src")===d||c.attr("src")===!1)&&c.is("img")&&c.attr("src",j.placeholder),c.one("appear",function(){if(!this.loaded){if(j.appear){var d=i.length;j.appear.call(b,d,j)}a("<img />").bind("load",function(){var d=c.attr("data-"+j.data_attribute);c.hide(),c.is("img")?c.attr("src",d):c.css("background-image","url('"+d+"')"),c[j.effect](j.effect_speed),b.loaded=!0;var e=a.grep(i,function(a){return!a.loaded});if(i=a(e),j.load){var f=i.length;j.load.call(b,f,j)}}).attr("src",c.attr("data-"+j.data_attribute))}}),0!==j.event.indexOf("scroll")&&c.bind(j.event,function(){b.loaded||c.trigger("appear")})}),e.bind("resize",function(){g()}),/(?:iphone|ipod|ipad).*os 5/gi.test(navigator.appVersion)&&e.bind("pageshow",function(b){b.originalEvent&&b.originalEvent.persisted&&i.each(function(){a(this).trigger("appear")})}),a(c).ready(function(){g()}),this},a.belowthefold=function(c,f){var g;return g=f.container===d||f.container===b?(b.innerHeight?b.innerHeight:e.height())+e.scrollTop():a(f.container).offset().top+a(f.container).height(),g<=a(c).offset().top-f.threshold},a.rightoffold=function(c,f){var g;return g=f.container===d||f.container===b?e.width()+e.scrollLeft():a(f.container).offset().left+a(f.container).width(),g<=a(c).offset().left-f.threshold},a.abovethetop=function(c,f){var g;return g=f.container===d||f.container===b?e.scrollTop():a(f.container).offset().top,g>=a(c).offset().top+f.threshold+a(c).height()},a.leftofbegin=function(c,f){var g;return g=f.container===d||f.container===b?e.scrollLeft():a(f.container).offset().left,g>=a(c).offset().left+f.threshold+a(c).width()},a.inviewport=function(b,c){return!(a.rightoffold(b,c)||a.leftofbegin(b,c)||a.belowthefold(b,c)||a.abovethetop(b,c))},a.extend(a.expr[":"],{"below-the-fold":function(b){return a.belowthefold(b,{threshold:0})},"above-the-top":function(b){return!a.belowthefold(b,{threshold:0})},"right-of-screen":function(b){return a.rightoffold(b,{threshold:0})},"left-of-screen":function(b){return!a.rightoffold(b,{threshold:0})},"in-viewport":function(b){return a.inviewport(b,{threshold:0})},"above-the-fold":function(b){return!a.belowthefold(b,{threshold:0})},"right-of-fold":function(b){return a.rightoffold(b,{threshold:0})},"left-of-fold":function(b){return!a.rightoffold(b,{threshold:0})}})}(jQuery,window,document);


   (function () {
       'use strict'

       var sr
       var _requestAnimationFrame

       function ScrollReveal (config) {
         // Support instantiation without the `new` keyword.
         if (typeof this === 'undefined' || Object.getPrototypeOf(this) !== ScrollReveal.prototype) {
           return new ScrollReveal(config)
         }

         sr = this // Save reference to instance.
         sr.version = '3.3.2'
         sr.tools = new Tools() // *required utilities

         if (sr.isSupported()) {
           sr.tools.extend(sr.defaults, config || {})

           sr.defaults.container = _resolveContainer(sr.defaults)

           sr.store = {
             elements: {},
             containers: []
           }

           sr.sequences = {}
           sr.history = []
           sr.uid = 0
           sr.initialized = false
         } else if (typeof console !== 'undefined' && console !== null) {
           // Note: IE9 only supports console if devtools are open.
           console.log('ScrollReveal is not supported in this browser.')
         }

         return sr
       }

       /**
        * Configuration
        * -------------
        * This object signature can be passed directly to the ScrollReveal constructor,
        * or as the second argument of the `reveal()` method.
        */

       ScrollReveal.prototype.defaults = {
         // 'bottom', 'left', 'top', 'right'
         origin: 'bottom',

         // Can be any valid CSS distance, e.g. '5rem', '10%', '20vw', etc.
         distance: '0px',

         // Time in milliseconds.
         duration: 500,
         delay: 0,

         // Starting angles in degrees, will transition from these values to 0 in all axes.
         rotate: { x: 0, y: 0, z: 0 },

         // Starting opacity value, before transitioning to the computed opacity.
         opacity: 0,

         // Starting scale value, will transition from this value to 1
         scale: 1,

         // Accepts any valid CSS easing, e.g. 'ease', 'ease-in-out', 'linear', etc.
         easing: 'cubic-bezier(0.6, 0.2, 0.1, 1)',

         // `<html>` is the default reveal container. You can pass either:
         // DOM Node, e.g. document.querySelector('.fooContainer')
         // Selector, e.g. '.fooContainer'
         container: window.document.documentElement,

         // true/false to control reveal animations on mobile.
         mobile: true,

         // true:  reveals occur every time elements become visible
         // false: reveals occur once as elements become visible
         reset: false,

         // 'always' — delay for all reveal animations
         // 'once'   — delay only the first time reveals occur
         // 'onload' - delay only for animations triggered by first load
         useDelay: 'always',

         // Change when an element is considered in the viewport. The default value
         // of 0.20 means 20% of an element must be visible for its reveal to occur.
         viewFactor: 0.0000001,

         // Pixel values that alter the container boundaries.
         // e.g. Set `{ top: 48 }`, if you have a 48px tall fixed toolbar.
         // --
         // Visual Aid: https://scrollrevealjs.org/assets/viewoffset.png
         viewOffset: { top: 0, right: 0, bottom: 0, left: 0 },

         // Callbacks that fire for each triggered element reveal, and reset.
         beforeReveal: function (domEl) {},
         beforeReset: function (domEl) {},

         // Callbacks that fire for each completed element reveal, and reset.
         afterReveal: function (domEl) {},
         afterReset: function (domEl) {}
       }

       /**
        * Check if client supports CSS Transform and CSS Transition.
        * @return {boolean}
        */
       ScrollReveal.prototype.isSupported = function () {
         var style = document.documentElement.style
         return 'WebkitTransition' in style && 'WebkitTransform' in style ||
             'transition' in style && 'transform' in style
       }

       /**
        * Creates a reveal set, a group of elements that will animate when they
        * become visible. If [interval] is provided, a new sequence is created
        * that will ensure elements reveal in the order they appear in the DOM.
        *
        * @param {Node|NodeList|string} [target]   The node, node list or selector to use for animation.
        * @param {Object}               [config]   Override the defaults for this reveal set.
        * @param {number}               [interval] Time between sequenced element animations (milliseconds).
        * @param {boolean}              [sync]     Used internally when updating reveals for async content.
        *
        * @return {Object} The current ScrollReveal instance.
        */
       ScrollReveal.prototype.reveal = function (target, config, interval, sync) {
         var container
         var elements
         var elem
         var elemId
         var sequence
         var sequenceId

         // No custom configuration was passed, but a sequence interval instead.
         // let’s shuffle things around to make sure everything works.
         if (config !== undefined && typeof config === 'number') {
           interval = config
           config = {}
         } else if (config === undefined || config === null) {
           config = {}
         }

         container = _resolveContainer(config)
         elements = _getRevealElements(target, container)

         if (!elements.length) {
           //console.log('ScrollReveal: reveal on "' + target + '" failed, no elements found.')
           return sr
         }

         // Prepare a new sequence if an interval is passed.
         if (interval && typeof interval === 'number') {
           sequenceId = _nextUid()

           sequence = sr.sequences[sequenceId] = {
             id: sequenceId,
             interval: interval,
             elemIds: [],
             active: false
           }
         }

         // Begin main loop to configure ScrollReveal elements.
         for (var i = 0; i < elements.length; i++) {
           // Check if the element has already been configured and grab it from the store.
           elemId = elements[i].getAttribute('data-sr-id')
           if (elemId) {
             elem = sr.store.elements[elemId]
           } else {
             // Otherwise, let’s do some basic setup.
             elem = {
               id: _nextUid(),
               domEl: elements[i],
               seen: false,
               revealing: false
             }
             elem.domEl.setAttribute('data-sr-id', elem.id)
           }

           // Sequence only setup
           if (sequence) {
             elem.sequence = {
               id: sequence.id,
               index: sequence.elemIds.length
             }

             sequence.elemIds.push(elem.id)
           }

           // New or existing element, it’s time to update its configuration, styles,
           // and send the updates to our store.
           _configure(elem, config, container)
           _style(elem)
           _updateStore(elem)

           // We need to make sure elements are set to visibility: visible, even when
           // on mobile and `config.mobile === false`, or if unsupported.
           if (sr.tools.isMobile() && !elem.config.mobile || !sr.isSupported()) {
             elem.domEl.setAttribute('style', elem.styles.inline)
             elem.disabled = true
           } else if (!elem.revealing) {
             // Otherwise, proceed normally.
             elem.domEl.setAttribute('style',
                 elem.styles.inline +
                 elem.styles.transform.initial
             )
           }
         }

         // Each `reveal()` is recorded so that when calling `sync()` while working
         // with asynchronously loaded content, it can re-trace your steps but with
         // all your new elements now in the DOM.

         // Since `reveal()` is called internally by `sync()`, we don’t want to
         // record or intiialize each reveal during syncing.
         if (!sync && sr.isSupported()) {
           _record(target, config, interval)

           // We push initialization to the event queue using setTimeout, so that we can
           // give ScrollReveal room to process all reveal calls before putting things into motion.
           // --
           // Philip Roberts - What the heck is the event loop anyway? (JSConf EU 2014)
           // https://www.youtube.com/watch?v=8aGhZQkoFbQ
           if (sr.initTimeout) {
             window.clearTimeout(sr.initTimeout)
           }
           sr.initTimeout = window.setTimeout(_init, 0)
         }

         return sr
       }

       /**
        * Re-runs `reveal()` for each record stored in history, effectively capturing
        * any content loaded asynchronously that matches existing reveal set targets.
        * @return {Object} The current ScrollReveal instance.
        */
       ScrollReveal.prototype.sync = function () {
         if (sr.history.length && sr.isSupported()) {
           for (var i = 0; i < sr.history.length; i++) {
             var record = sr.history[i]
             sr.reveal(record.target, record.config, record.interval, true)
           }
           _init()
         } else {
           console.log('ScrollReveal: sync failed, no reveals found.')
         }
         return sr
       }

       /**
        * Private Methods
        * ---------------
        */

       function _resolveContainer (config) {
         if (config && config.container) {
           if (typeof config.container === 'string') {
             return window.document.documentElement.querySelector(config.container)
           } else if (sr.tools.isNode(config.container)) {
             return config.container
           } else {
             console.log('ScrollReveal: invalid container "' + config.container + '" provided.')
             console.log('ScrollReveal: falling back to default container.')
           }
         }
         return sr.defaults.container
       }

       /**
        * check to see if a node or node list was passed in as the target,
        * otherwise query the container using target as a selector.
        *
        * @param {Node|NodeList|string} [target]    client input for reveal target.
        * @param {Node}                 [container] parent element for selector queries.
        *
        * @return {array} elements to be revealed.
        */
       function _getRevealElements (target, container) {
         if (typeof target === 'string') {
           return Array.prototype.slice.call(container.querySelectorAll(target))
         } else if (sr.tools.isNode(target)) {
           return [target]
         } else if (sr.tools.isNodeList(target)) {
           return Array.prototype.slice.call(target)
         }
         return []
       }

       /**
        * A consistent way of creating unique IDs.
        * @returns {number}
        */
       function _nextUid () {
         return ++sr.uid
       }

       function _configure (elem, config, container) {
         // If a container was passed as a part of the config object,
         // let’s overwrite it with the resolved container passed in.
         if (config.container) config.container = container
         // If the element hasn’t already been configured, let’s use a clone of the
         // defaults extended by the configuration passed as the second argument.
         if (!elem.config) {
           elem.config = sr.tools.extendClone(sr.defaults, config)
         } else {
           // Otherwise, let’s use a clone of the existing element configuration extended
           // by the configuration passed as the second argument.
           elem.config = sr.tools.extendClone(elem.config, config)
         }

         // Infer CSS Transform axis from origin string.
         if (elem.config.origin === 'top' || elem.config.origin === 'bottom') {
           elem.config.axis = 'Y'
         } else {
           elem.config.axis = 'X'
         }
       }

       function _style (elem) {
         var computed = window.getComputedStyle(elem.domEl)

         if (!elem.styles) {
           elem.styles = {
             transition: {},
             transform: {},
             computed: {}
           }

           // Capture any existing inline styles, and add our visibility override.
           // --
           // See section 4.2. in the Documentation:
           // https://github.com/jlmakes/scrollreveal.js#42-improve-user-experience
           elem.styles.inline = elem.domEl.getAttribute('style') || ''
           elem.styles.inline += '; visibility: visible; '

           // grab the elements existing opacity.
           elem.styles.computed.opacity = computed.opacity

           // grab the elements existing transitions.
           if (!computed.transition || computed.transition === 'all 0s ease 0s') {
             elem.styles.computed.transition = ''
           } else {
             elem.styles.computed.transition = computed.transition + ', '
           }
         }

         // Create transition styles
         elem.styles.transition.instant = _generateTransition(elem, 0)
         elem.styles.transition.delayed = _generateTransition(elem, elem.config.delay)

         // Generate transform styles, first with the webkit prefix.
         elem.styles.transform.initial = ' -webkit-transform:'
         elem.styles.transform.target = ' -webkit-transform:'
         _generateTransform(elem)

         // And again without any prefix.
         elem.styles.transform.initial += 'transform:'
         elem.styles.transform.target += 'transform:'
         _generateTransform(elem)
       }

       function _generateTransition (elem, delay) {
         var config = elem.config

         return '-webkit-transition: ' + elem.styles.computed.transition +
             '-webkit-transform ' + config.duration / 1000 + 's ' +
             config.easing + ' ' +
             delay / 1000 + 's, opacity ' +
             config.duration / 1000 + 's ' +
             config.easing + ' ' +
             delay / 1000 + 's; ' +

             'transition: ' + elem.styles.computed.transition +
             'transform ' + config.duration / 1000 + 's ' +
             config.easing + ' ' +
             delay / 1000 + 's, opacity ' +
             config.duration / 1000 + 's ' +
             config.easing + ' ' +
             delay / 1000 + 's; '
       }

       function _generateTransform (elem) {
         var config = elem.config
         var cssDistance
         var transform = elem.styles.transform

         // Let’s make sure our our pixel distances are negative for top and left.
         // e.g. origin = 'top' and distance = '25px' starts at `top: -25px` in CSS.
         if (config.origin === 'top' || config.origin === 'left') {
           cssDistance = /^-/.test(config.distance)
               ? config.distance.substr(1)
               : '-' + config.distance
         } else {
           cssDistance = config.distance
         }

         if (parseInt(config.distance)) {
           transform.initial += ' translate' + config.axis + '(' + cssDistance + ')'
           transform.target += ' translate' + config.axis + '(0)'
         }
         if (config.scale) {
           transform.initial += ' scale(' + config.scale + ')'
           transform.target += ' scale(1)'
         }
         if (config.rotate.x) {
           transform.initial += ' rotateX(' + config.rotate.x + 'deg)'
           transform.target += ' rotateX(0)'
         }
         if (config.rotate.y) {
           transform.initial += ' rotateY(' + config.rotate.y + 'deg)'
           transform.target += ' rotateY(0)'
         }
         if (config.rotate.z) {
           transform.initial += ' rotateZ(' + config.rotate.z + 'deg)'
           transform.target += ' rotateZ(0)'
         }
         transform.initial += '; opacity: ' + config.opacity + ';'
         transform.target += '; opacity: ' + elem.styles.computed.opacity + ';'
       }

       function _updateStore (elem) {
         var container = elem.config.container

         // If this element’s container isn’t already in the store, let’s add it.
         if (container && sr.store.containers.indexOf(container) === -1) {
           sr.store.containers.push(elem.config.container)
         }

         // Update the element stored with our new element.
         sr.store.elements[elem.id] = elem
       }

       function _record (target, config, interval) {
         // Save the `reveal()` arguments that triggered this `_record()` call, so we
         // can re-trace our steps when calling the `sync()` method.
         var record = {
           target: target,
           config: config,
           interval: interval
         }
         sr.history.push(record)
       }

       function _init () {
         if (sr.isSupported()) {
           // Initial animate call triggers valid reveal animations on first load.
           // Subsequent animate calls are made inside the event handler.
           _animate()

           // Then we loop through all container nodes in the store and bind event
           // listeners to each.
           for (var i = 0; i < sr.store.containers.length; i++) {
             sr.store.containers[i].addEventListener('scroll', _handler)
             sr.store.containers[i].addEventListener('resize', _handler)
           }

           // Let’s also do a one-time binding of window event listeners.
           if (!sr.initialized) {
             window.addEventListener('scroll', _handler)
             window.addEventListener('resize', _handler)
             sr.initialized = true
           }
         }
         return sr
       }

       function _handler () {
         _requestAnimationFrame(_animate)
       }

       function _setActiveSequences () {
         var active
         var elem
         var elemId
         var sequence

         // Loop through all sequences
         sr.tools.forOwn(sr.sequences, function (sequenceId) {
           sequence = sr.sequences[sequenceId]
           active = false

           // For each sequenced elemenet, let’s check visibility and if
           // any are visible, set it’s sequence to active.
           for (var i = 0; i < sequence.elemIds.length; i++) {
             elemId = sequence.elemIds[i]
             elem = sr.store.elements[elemId]
             if (_isElemVisible(elem) && !active) {
               active = true
             }
           }

           sequence.active = active
         })
       }

       function _animate () {
         var delayed
         var elem

         _setActiveSequences()

         // Loop through all elements in the store
         sr.tools.forOwn(sr.store.elements, function (elemId) {
           elem = sr.store.elements[elemId]
           delayed = _shouldUseDelay(elem)

           // Let’s see if we should revealand if so,
           // trigger the `beforeReveal` callback and
           // determine whether or not to use delay.
           if (_shouldReveal(elem)) {
             elem.config.beforeReveal(elem.domEl)
             if (delayed) {
               elem.domEl.setAttribute('style',
                   elem.styles.inline +
                   elem.styles.transform.target +
                   elem.styles.transition.delayed
               )
             } else {
               elem.domEl.setAttribute('style',
                   elem.styles.inline +
                   elem.styles.transform.target +
                   elem.styles.transition.instant
               )
             }

             // Let’s queue the `afterReveal` callback
             // and mark the element as seen and revealing.
             _queueCallback('reveal', elem, delayed)
             elem.revealing = true
             elem.seen = true

             if (elem.sequence) {
               _queueNextInSequence(elem, delayed)
             }
           } else if (_shouldReset(elem)) {
             //Otherwise reset our element and
             // trigger the `beforeReset` callback.
             elem.config.beforeReset(elem.domEl)
             elem.domEl.setAttribute('style',
                 elem.styles.inline +
                 elem.styles.transform.initial +
                 elem.styles.transition.instant
             )
             // And queue the `afterReset` callback.
             _queueCallback('reset', elem)
             elem.revealing = false
           }
         })
       }

       function _queueNextInSequence (elem, delayed) {
         var elapsed = 0
         var delay = 0
         var sequence = sr.sequences[elem.sequence.id]

         // We’re processing a sequenced element, so let's block other elements in this sequence.
         sequence.blocked = true

         // Since we’re triggering animations a part of a sequence after animations on first load,
         // we need to check for that condition and explicitly add the delay to our timer.
         if (delayed && elem.config.useDelay === 'onload') {
           delay = elem.config.delay
         }

         // If a sequence timer is already running, capture the elapsed time and clear it.
         if (elem.sequence.timer) {
           elapsed = Math.abs(elem.sequence.timer.started - new Date())
           window.clearTimeout(elem.sequence.timer)
         }

         // Start a new timer.
         elem.sequence.timer = { started: new Date() }
         elem.sequence.timer.clock = window.setTimeout(function () {
           // Sequence interval has passed, so unblock the sequence and re-run the handler.
           sequence.blocked = false
           elem.sequence.timer = null
           _handler()
         }, Math.abs(sequence.interval) + delay - elapsed)
       }

       function _queueCallback (type, elem, delayed) {
         var elapsed = 0
         var duration = 0
         var callback = 'after'

         // Check which callback we’re working with.
         switch (type) {
           case 'reveal':
             duration = elem.config.duration
             if (delayed) {
               duration += elem.config.delay
             }
             callback += 'Reveal'
             break

           case 'reset':
             duration = elem.config.duration
             callback += 'Reset'
             break
         }

         // If a timer is already running, capture the elapsed time and clear it.
         if (elem.timer) {
           elapsed = Math.abs(elem.timer.started - new Date())
           window.clearTimeout(elem.timer.clock)
         }

         // Start a new timer.
         elem.timer = { started: new Date() }
         elem.timer.clock = window.setTimeout(function () {
           // The timer completed, so let’s fire the callback and null the timer.
           elem.config[callback](elem.domEl)
           elem.timer = null
         }, duration - elapsed)
       }

       function _shouldReveal (elem) {
         if (elem.sequence) {
           var sequence = sr.sequences[elem.sequence.id]
           return sequence.active &&
               !sequence.blocked &&
               !elem.revealing &&
               !elem.disabled
         }
         return _isElemVisible(elem) &&
             !elem.revealing &&
             !elem.disabled
       }

       function _shouldUseDelay (elem) {
         var config = elem.config.useDelay
         return config === 'always' ||
             (config === 'onload' && !sr.initialized) ||
             (config === 'once' && !elem.seen)
       }

       function _shouldReset (elem) {
         if (elem.sequence) {
           var sequence = sr.sequences[elem.sequence.id]
           return !sequence.active &&
               elem.config.reset &&
               elem.revealing &&
               !elem.disabled
         }
         return !_isElemVisible(elem) &&
             elem.config.reset &&
             elem.revealing &&
             !elem.disabled
       }

       function _getContainer (container) {
         return {
           width: container.clientWidth,
           height: container.clientHeight
         }
       }

       function _getScrolled (container) {
         // Return the container scroll values, plus the its offset.
         if (container && container !== window.document.documentElement) {
           var offset = _getOffset(container)
           return {
             x: container.scrollLeft + offset.left,
             y: container.scrollTop + offset.top
           }
         } else {
           // Otherwise, default to the window object’s scroll values.
           return {
             x: window.pageXOffset,
             y: window.pageYOffset
           }
         }
       }

       function _getOffset (domEl) {
         var offsetTop = 0
         var offsetLeft = 0

         // Grab the element’s dimensions.
         var offsetHeight = domEl.offsetHeight
         var offsetWidth = domEl.offsetWidth

         // Now calculate the distance between the element and its parent, then
         // again for the parent to its parent, and again etc... until we have the
         // total distance of the element to the document’s top and left origin.
         do {
           if (!isNaN(domEl.offsetTop)) {
             offsetTop += domEl.offsetTop
           }
           if (!isNaN(domEl.offsetLeft)) {
             offsetLeft += domEl.offsetLeft
           }
           domEl = domEl.offsetParent
         } while (domEl)

         return {
           top: offsetTop,
           left: offsetLeft,
           height: offsetHeight,
           width: offsetWidth
         }
       }

       function _isElemVisible (elem) {
         var offset = _getOffset(elem.domEl)
         var container = _getContainer(elem.config.container)
         var scrolled = _getScrolled(elem.config.container)
         var vF = elem.config.viewFactor

         // Define the element geometry.
         var elemHeight = offset.height
         var elemWidth = offset.width
         var elemTop = offset.top
         var elemLeft = offset.left
         var elemBottom = elemTop + elemHeight
         var elemRight = elemLeft + elemWidth

         return confirmBounds() || isPositionFixed()

         function confirmBounds () {
           // Define the element’s functional boundaries using its view factor.
           var top = elemTop + elemHeight * vF
           var left = elemLeft + elemWidth * vF
           var bottom = elemBottom - elemHeight * vF
           var right = elemRight - elemWidth * vF

           // Define the container functional boundaries using its view offset.
           var viewTop = scrolled.y + elem.config.viewOffset.top
           var viewLeft = scrolled.x + elem.config.viewOffset.left
           var viewBottom = scrolled.y - elem.config.viewOffset.bottom + container.height
           var viewRight = scrolled.x - elem.config.viewOffset.right + container.width

           return top < viewBottom &&
               bottom > viewTop &&
               left > viewLeft &&
               right < viewRight
         }

         function isPositionFixed () {
           return (window.getComputedStyle(elem.domEl).position === 'fixed')
         }
       }

       /**
        * Utilities
        * ---------
        */

       function Tools () {}

       Tools.prototype.isObject = function (object) {
         return object !== null && typeof object === 'object' && object.constructor === Object
       }

       Tools.prototype.isNode = function (object) {
         return typeof window.Node === 'object'
             ? object instanceof window.Node
             : object && typeof object === 'object' &&
             typeof object.nodeType === 'number' &&
             typeof object.nodeName === 'string'
       }

       Tools.prototype.isNodeList = function (object) {
         var prototypeToString = Object.prototype.toString.call(object)
         var regex = /^\[object (HTMLCollection|NodeList|Object)\]$/

         return typeof window.NodeList === 'object'
             ? object instanceof window.NodeList
             : object && typeof object === 'object' &&
             regex.test(prototypeToString) &&
             typeof object.length === 'number' &&
             (object.length === 0 || this.isNode(object[0]))
       }

       Tools.prototype.forOwn = function (object, callback) {
         if (!this.isObject(object)) {
           throw new TypeError('Expected "object", but received "' + typeof object + '".')
         } else {
           for (var property in object) {
             if (object.hasOwnProperty(property)) {
               callback(property)
             }
           }
         }
       }

       Tools.prototype.extend = function (target, source) {
         this.forOwn(source, function (property) {
           if (this.isObject(source[property])) {
             if (!target[property] || !this.isObject(target[property])) {
               target[property] = {}
             }
             this.extend(target[property], source[property])
           } else {
             target[property] = source[property]
           }
         }.bind(this))
         return target
       }

       Tools.prototype.extendClone = function (target, source) {
         return this.extend(this.extend({}, target), source)
       }

       Tools.prototype.isMobile = function () {
         return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)
       }

       /**
        * Polyfills
        * --------
        */

       _requestAnimationFrame = window.requestAnimationFrame ||
           window.webkitRequestAnimationFrame ||
           window.mozRequestAnimationFrame ||
           function (callback) {
             window.setTimeout(callback, 1000 / 60)
           }

       /**
        * Module Wrapper
        * --------------
        */
       if (typeof define === 'function' && typeof define.amd === 'object' && define.amd) {
         define(function () {
           return ScrollReveal
         })
       } else if (typeof module !== 'undefined' && module.exports) {
         module.exports = ScrollReveal
       } else {
         window.ScrollReveal = ScrollReveal
       }
     }())



     /* @fileOverview TouchSwipe - jQuery Plugin | @version 1.6.15 | author Matt Bryson http://www.github.com/mattbryson | see https://github.com/mattbryson/TouchSwipe-Jquery-Plugin | see http://labs.rampinteractive.co.uk/touchSwipe/ | see http://plugins.jquery.com/project/touchSwipe | Copyright (c) 2010-2015 Matt Bryson | Dual licensed under the MIT or GPL Version 2 licenses. */
     (function(a){if(typeof define==="function"&&define.amd&&define.amd.jQuery){define(["jquery"],a)}else{if(typeof module!=="undefined"&&module.exports){a(require("jquery"))}else{a(jQuery)}}}(function(f){var y="1.6.15",p="left",o="right",e="up",x="down",c="in",A="out",m="none",s="auto",l="swipe",t="pinch",B="tap",j="doubletap",b="longtap",z="hold",E="horizontal",u="vertical",i="all",r=10,g="start",k="move",h="end",q="cancel",a="ontouchstart" in window,v=window.navigator.msPointerEnabled&&!window.navigator.pointerEnabled&&!a,d=(window.navigator.pointerEnabled||window.navigator.msPointerEnabled)&&!a,C="TouchSwipe";var n={fingers:1,threshold:75,cancelThreshold:null,pinchThreshold:20,maxTimeThreshold:null,fingerReleaseThreshold:250,longTapThreshold:500,doubleTapThreshold:200,swipe:null,swipeLeft:null,swipeRight:null,swipeUp:null,swipeDown:null,swipeStatus:null,pinchIn:null,pinchOut:null,pinchStatus:null,click:null,tap:null,doubleTap:null,longTap:null,hold:null,triggerOnTouchEnd:true,triggerOnTouchLeave:false,allowPageScroll:"auto",fallbackToMouseEvents:true,excludedElements:"label, button, input, select, textarea, a, .noSwipe, .swiper-wrapper, .coverpage-slider, .coverflow-slider, .coverflow-thumbnails",preventDefaultEvents:false};f.fn.swipe=function(H){var G=f(this),F=G.data(C);if(F&&typeof H==="string"){if(F[H]){return F[H].apply(this,Array.prototype.slice.call(arguments,1))}else{f.error("Method "+H+" does not exist on jQuery.swipe")}}else{if(F&&typeof H==="object"){F.option.apply(this,arguments)}else{if(!F&&(typeof H==="object"||!H)){return w.apply(this,arguments)}}}return G};f.fn.swipe.version=y;f.fn.swipe.defaults=n;f.fn.swipe.phases={PHASE_START:g,PHASE_MOVE:k,PHASE_END:h,PHASE_CANCEL:q};f.fn.swipe.directions={LEFT:p,RIGHT:o,UP:e,DOWN:x,IN:c,OUT:A};f.fn.swipe.pageScroll={NONE:m,HORIZONTAL:E,VERTICAL:u,AUTO:s};f.fn.swipe.fingers={ONE:1,TWO:2,THREE:3,FOUR:4,FIVE:5,ALL:i};function w(F){if(F&&(F.allowPageScroll===undefined&&(F.swipe!==undefined||F.swipeStatus!==undefined))){F.allowPageScroll=m}if(F.click!==undefined&&F.tap===undefined){F.tap=F.click}if(!F){F={}}F=f.extend({},f.fn.swipe.defaults,F);return this.each(function(){var H=f(this);var G=H.data(C);if(!G){G=new D(this,F);H.data(C,G)}})}function D(a5,au){var au=f.extend({},au);var az=(a||d||!au.fallbackToMouseEvents),K=az?(d?(v?"MSPointerDown":"pointerdown"):"touchstart"):"mousedown",ax=az?(d?(v?"MSPointerMove":"pointermove"):"touchmove"):"mousemove",V=az?(d?(v?"MSPointerUp":"pointerup"):"touchend"):"mouseup",T=az?(d?"mouseleave":null):"mouseleave",aD=(d?(v?"MSPointerCancel":"pointercancel"):"touchcancel");var ag=0,aP=null,a2=null,ac=0,a1=0,aZ=0,H=1,ap=0,aJ=0,N=null;var aR=f(a5);var aa="start";var X=0;var aQ={};var U=0,a3=0,a6=0,ay=0,O=0;var aW=null,af=null;try{aR.bind(K,aN);aR.bind(aD,ba)}catch(aj){f.error("events not supported "+K+","+aD+" on jQuery.swipe")}this.enable=function(){aR.bind(K,aN);aR.bind(aD,ba);return aR};this.disable=function(){aK();return aR};this.destroy=function(){aK();aR.data(C,null);aR=null};this.option=function(bd,bc){if(typeof bd==="object"){au=f.extend(au,bd)}else{if(au[bd]!==undefined){if(bc===undefined){return au[bd]}else{au[bd]=bc}}else{if(!bd){return au}else{f.error("Option "+bd+" does not exist on jQuery.swipe.options")}}}return null};function aN(be){if(aB()){return}if(f(be.target).closest(au.excludedElements,aR).length>0){return}var bf=be.originalEvent?be.originalEvent:be;var bd,bg=bf.touches,bc=bg?bg[0]:bf;aa=g;if(bg){X=bg.length}else{if(au.preventDefaultEvents!==false){be.preventDefault()}}ag=0;aP=null;a2=null;aJ=null;ac=0;a1=0;aZ=0;H=1;ap=0;N=ab();S();ai(0,bc);if(!bg||(X===au.fingers||au.fingers===i)||aX()){U=ar();if(X==2){ai(1,bg[1]);a1=aZ=at(aQ[0].start,aQ[1].start)}if(au.swipeStatus||au.pinchStatus){bd=P(bf,aa)}}else{bd=false}if(bd===false){aa=q;P(bf,aa);return bd}else{if(au.hold){af=setTimeout(f.proxy(function(){aR.trigger("hold",[bf.target]);if(au.hold){bd=au.hold.call(aR,bf,bf.target)}},this),au.longTapThreshold)}an(true)}return null}function a4(bf){var bi=bf.originalEvent?bf.originalEvent:bf;if(aa===h||aa===q||al()){return}var be,bj=bi.touches,bd=bj?bj[0]:bi;var bg=aH(bd);a3=ar();if(bj){X=bj.length}if(au.hold){clearTimeout(af)}aa=k;if(X==2){if(a1==0){ai(1,bj[1]);a1=aZ=at(aQ[0].start,aQ[1].start)}else{aH(bj[1]);aZ=at(aQ[0].end,aQ[1].end);aJ=aq(aQ[0].end,aQ[1].end)}H=a8(a1,aZ);ap=Math.abs(a1-aZ)}if((X===au.fingers||au.fingers===i)||!bj||aX()){aP=aL(bg.start,bg.end);a2=aL(bg.last,bg.end);ak(bf,a2);ag=aS(bg.start,bg.end);ac=aM();aI(aP,ag);be=P(bi,aa);if(!au.triggerOnTouchEnd||au.triggerOnTouchLeave){var bc=true;if(au.triggerOnTouchLeave){var bh=aY(this);bc=F(bg.end,bh)}if(!au.triggerOnTouchEnd&&bc){aa=aC(k)}else{if(au.triggerOnTouchLeave&&!bc){aa=aC(h)}}if(aa==q||aa==h){P(bi,aa)}}}else{aa=q;P(bi,aa)}if(be===false){aa=q;P(bi,aa)}}function M(bc){var bd=bc.originalEvent?bc.originalEvent:bc,be=bd.touches;if(be){if(be.length&&!al()){G(bd);return true}else{if(be.length&&al()){return true}}}if(al()){X=ay}a3=ar();ac=aM();if(bb()||!am()){aa=q;P(bd,aa)}else{if(au.triggerOnTouchEnd||(au.triggerOnTouchEnd==false&&aa===k)){if(au.preventDefaultEvents!==false){bc.preventDefault()}aa=h;P(bd,aa)}else{if(!au.triggerOnTouchEnd&&a7()){aa=h;aF(bd,aa,B)}else{if(aa===k){aa=q;P(bd,aa)}}}}an(false);return null}function ba(){X=0;a3=0;U=0;a1=0;aZ=0;H=1;S();an(false)}function L(bc){var bd=bc.originalEvent?bc.originalEvent:bc;if(au.triggerOnTouchLeave){aa=aC(h);P(bd,aa)}}function aK(){aR.unbind(K,aN);aR.unbind(aD,ba);aR.unbind(ax,a4);aR.unbind(V,M);if(T){aR.unbind(T,L)}an(false)}function aC(bg){var bf=bg;var be=aA();var bd=am();var bc=bb();if(!be||bc){bf=q}else{if(bd&&bg==k&&(!au.triggerOnTouchEnd||au.triggerOnTouchLeave)){bf=h}else{if(!bd&&bg==h&&au.triggerOnTouchLeave){bf=q}}}return bf}function P(be,bc){var bd,bf=be.touches;if(J()||W()){bd=aF(be,bc,l)}if((Q()||aX())&&bd!==false){bd=aF(be,bc,t)}if(aG()&&bd!==false){bd=aF(be,bc,j)}else{if(ao()&&bd!==false){bd=aF(be,bc,b)}else{if(ah()&&bd!==false){bd=aF(be,bc,B)}}}if(bc===q){if(W()){bd=aF(be,bc,l)}if(aX()){bd=aF(be,bc,t)}ba(be)}if(bc===h){if(bf){if(!bf.length){ba(be)}}else{ba(be)}}return bd}function aF(bf,bc,be){var bd;if(be==l){aR.trigger("swipeStatus",[bc,aP||null,ag||0,ac||0,X,aQ,a2]);if(au.swipeStatus){bd=au.swipeStatus.call(aR,bf,bc,aP||null,ag||0,ac||0,X,aQ,a2);if(bd===false){return false}}if(bc==h&&aV()){clearTimeout(aW);clearTimeout(af);aR.trigger("swipe",[aP,ag,ac,X,aQ,a2]);if(au.swipe){bd=au.swipe.call(aR,bf,aP,ag,ac,X,aQ,a2);if(bd===false){return false}}switch(aP){case p:aR.trigger("swipeLeft",[aP,ag,ac,X,aQ,a2]);if(au.swipeLeft){bd=au.swipeLeft.call(aR,bf,aP,ag,ac,X,aQ,a2)}break;case o:aR.trigger("swipeRight",[aP,ag,ac,X,aQ,a2]);if(au.swipeRight){bd=au.swipeRight.call(aR,bf,aP,ag,ac,X,aQ,a2)}break;case e:aR.trigger("swipeUp",[aP,ag,ac,X,aQ,a2]);if(au.swipeUp){bd=au.swipeUp.call(aR,bf,aP,ag,ac,X,aQ,a2)}break;case x:aR.trigger("swipeDown",[aP,ag,ac,X,aQ,a2]);if(au.swipeDown){bd=au.swipeDown.call(aR,bf,aP,ag,ac,X,aQ,a2)}break}}}if(be==t){aR.trigger("pinchStatus",[bc,aJ||null,ap||0,ac||0,X,H,aQ]);if(au.pinchStatus){bd=au.pinchStatus.call(aR,bf,bc,aJ||null,ap||0,ac||0,X,H,aQ);if(bd===false){return false}}if(bc==h&&a9()){switch(aJ){case c:aR.trigger("pinchIn",[aJ||null,ap||0,ac||0,X,H,aQ]);if(au.pinchIn){bd=au.pinchIn.call(aR,bf,aJ||null,ap||0,ac||0,X,H,aQ)}break;case A:aR.trigger("pinchOut",[aJ||null,ap||0,ac||0,X,H,aQ]);if(au.pinchOut){bd=au.pinchOut.call(aR,bf,aJ||null,ap||0,ac||0,X,H,aQ)}break}}}if(be==B){if(bc===q||bc===h){clearTimeout(aW);clearTimeout(af);if(Z()&&!I()){O=ar();aW=setTimeout(f.proxy(function(){O=null;aR.trigger("tap",[bf.target]);if(au.tap){bd=au.tap.call(aR,bf,bf.target)}},this),au.doubleTapThreshold)}else{O=null;aR.trigger("tap",[bf.target]);if(au.tap){bd=au.tap.call(aR,bf,bf.target)}}}}else{if(be==j){if(bc===q||bc===h){clearTimeout(aW);clearTimeout(af);O=null;aR.trigger("doubletap",[bf.target]);if(au.doubleTap){bd=au.doubleTap.call(aR,bf,bf.target)}}}else{if(be==b){if(bc===q||bc===h){clearTimeout(aW);O=null;aR.trigger("longtap",[bf.target]);if(au.longTap){bd=au.longTap.call(aR,bf,bf.target)}}}}}return bd}function am(){var bc=true;if(au.threshold!==null){bc=ag>=au.threshold}return bc}function bb(){var bc=false;if(au.cancelThreshold!==null&&aP!==null){bc=(aT(aP)-ag)>=au.cancelThreshold}return bc}function ae(){if(au.pinchThreshold!==null){return ap>=au.pinchThreshold}return true}function aA(){var bc;if(au.maxTimeThreshold){if(ac>=au.maxTimeThreshold){bc=false}else{bc=true}}else{bc=true}return bc}function ak(bc,bd){if(au.preventDefaultEvents===false){return}if(au.allowPageScroll===m){bc.preventDefault()}else{var be=au.allowPageScroll===s;switch(bd){case p:if((au.swipeLeft&&be)||(!be&&au.allowPageScroll!=E)){bc.preventDefault()}break;case o:if((au.swipeRight&&be)||(!be&&au.allowPageScroll!=E)){bc.preventDefault()}break;case e:if((au.swipeUp&&be)||(!be&&au.allowPageScroll!=u)){bc.preventDefault()}break;case x:if((au.swipeDown&&be)||(!be&&au.allowPageScroll!=u)){bc.preventDefault()}break}}}function a9(){var bd=aO();var bc=Y();var be=ae();return bd&&bc&&be}function aX(){return !!(au.pinchStatus||au.pinchIn||au.pinchOut)}function Q(){return !!(a9()&&aX())}function aV(){var bf=aA();var bh=am();var be=aO();var bc=Y();var bd=bb();var bg=!bd&&bc&&be&&bh&&bf;return bg}function W(){return !!(au.swipe||au.swipeStatus||au.swipeLeft||au.swipeRight||au.swipeUp||au.swipeDown)}function J(){return !!(aV()&&W())}function aO(){return((X===au.fingers||au.fingers===i)||!a)}function Y(){return aQ[0].end.x!==0}function a7(){return !!(au.tap)}function Z(){return !!(au.doubleTap)}function aU(){return !!(au.longTap)}function R(){if(O==null){return false}var bc=ar();return(Z()&&((bc-O)<=au.doubleTapThreshold))}function I(){return R()}function aw(){return((X===1||!a)&&(isNaN(ag)||ag<au.threshold))}function a0(){return((ac>au.longTapThreshold)&&(ag<r))}function ah(){return !!(aw()&&a7())}function aG(){return !!(R()&&Z())}function ao(){return !!(a0()&&aU())}function G(bc){a6=ar();ay=bc.touches.length+1}function S(){a6=0;ay=0}function al(){var bc=false;if(a6){var bd=ar()-a6;if(bd<=au.fingerReleaseThreshold){bc=true}}return bc}function aB(){return !!(aR.data(C+"_intouch")===true)}function an(bc){if(!aR){return}if(bc===true){aR.bind(ax,a4);aR.bind(V,M);if(T){aR.bind(T,L)}}else{aR.unbind(ax,a4,false);aR.unbind(V,M,false);if(T){aR.unbind(T,L,false)}}aR.data(C+"_intouch",bc===true)}function ai(be,bc){var bd={start:{x:0,y:0},last:{x:0,y:0},end:{x:0,y:0}};bd.start.x=bd.last.x=bd.end.x=bc.pageX||bc.clientX;bd.start.y=bd.last.y=bd.end.y=bc.pageY||bc.clientY;aQ[be]=bd;return bd}function aH(bc){var be=bc.identifier!==undefined?bc.identifier:0;var bd=ad(be);if(bd===null){bd=ai(be,bc)}bd.last.x=bd.end.x;bd.last.y=bd.end.y;bd.end.x=bc.pageX||bc.clientX;bd.end.y=bc.pageY||bc.clientY;return bd}function ad(bc){return aQ[bc]||null}function aI(bc,bd){bd=Math.max(bd,aT(bc));N[bc].distance=bd}function aT(bc){if(N[bc]){return N[bc].distance}return undefined}function ab(){var bc={};bc[p]=av(p);bc[o]=av(o);bc[e]=av(e);bc[x]=av(x);return bc}function av(bc){return{direction:bc,distance:0}}function aM(){return a3-U}function at(bf,be){var bd=Math.abs(bf.x-be.x);var bc=Math.abs(bf.y-be.y);return Math.round(Math.sqrt(bd*bd+bc*bc))}function a8(bc,bd){var be=(bd/bc)*1;return be.toFixed(2)}function aq(){if(H<1){return A}else{return c}}function aS(bd,bc){return Math.round(Math.sqrt(Math.pow(bc.x-bd.x,2)+Math.pow(bc.y-bd.y,2)))}function aE(bf,bd){var bc=bf.x-bd.x;var bh=bd.y-bf.y;var be=Math.atan2(bh,bc);var bg=Math.round(be*180/Math.PI);if(bg<0){bg=360-Math.abs(bg)}return bg}function aL(bd,bc){var be=aE(bd,bc);if((be<=45)&&(be>=0)){return p}else{if((be<=360)&&(be>=315)){return p}else{if((be>=135)&&(be<=225)){return o}else{if((be>45)&&(be<135)){return x}else{return e}}}}}function ar(){var bc=new Date();return bc.getTime()}function aY(bc){bc=f(bc);var be=bc.offset();var bd={left:be.left,right:be.left+bc.outerWidth(),top:be.top,bottom:be.top+bc.outerHeight()};return bd}function F(bc,bd){return(bc.x>bd.left&&bc.x<bd.right&&bc.y>bd.top&&bc.y<bd.bottom)}}}));


     /* Copyright (c) 2007-2015 Ariel Flesler - aflesler ○ gmail • com | http://flesler.blogspot.com |  Licensed under MIT */
     ;(function(f){"use strict";"function"===typeof define&&define.amd?define(["jquery"],f):"undefined"!==typeof module&&module.exports?module.exports=f(require("jquery")):f(jQuery)})(function($){"use strict";function n(a){return!a.nodeName||-1!==$.inArray(a.nodeName.toLowerCase(),["iframe","#document","html","body"])}function h(a){return $.isFunction(a)||$.isPlainObject(a)?a:{top:a,left:a}}var p=$.scrollTo=function(a,d,b){return $(window).scrollTo(a,d,b)};p.defaults={axis:"xy",duration:0,limit:!0};$.fn.scrollTo=function(a,d,b){"object"=== typeof d&&(b=d,d=0);"function"===typeof b&&(b={onAfter:b});"max"===a&&(a=9E9);b=$.extend({},p.defaults,b);d=d||b.duration;var u=b.queue&&1<b.axis.length;u&&(d/=2);b.offset=h(b.offset);b.over=h(b.over);return this.each(function(){function k(a){var k=$.extend({},b,{queue:!0,duration:d,complete:a&&function(){a.call(q,e,b)}});r.animate(f,k)}if(null!==a){var l=n(this),q=l?this.contentWindow||window:this,r=$(q),e=a,f={},t;switch(typeof e){case "number":case "string":if(/^([+-]=?)?\d+(\.\d+)?(px|%)?$/.test(e)){e= h(e);break}e=l?$(e):$(e,q);case "object":if(e.length===0)return;if(e.is||e.style)t=(e=$(e)).offset()}var v=$.isFunction(b.offset)&&b.offset(q,e)||b.offset;$.each(b.axis.split(""),function(a,c){var d="x"===c?"Left":"Top",m=d.toLowerCase(),g="scroll"+d,h=r[g](),n=p.max(q,c);t?(f[g]=t[m]+(l?0:h-r.offset()[m]),b.margin&&(f[g]-=parseInt(e.css("margin"+d),10)||0,f[g]-=parseInt(e.css("border"+d+"Width"),10)||0),f[g]+=v[m]||0,b.over[m]&&(f[g]+=e["x"===c?"width":"height"]()*b.over[m])):(d=e[m],f[g]=d.slice&& "%"===d.slice(-1)?parseFloat(d)/100*n:d);b.limit&&/^\d+$/.test(f[g])&&(f[g]=0>=f[g]?0:Math.min(f[g],n));!a&&1<b.axis.length&&(h===f[g]?f={}:u&&(k(b.onAfterFirst),f={}))});k(b.onAfter)}})};p.max=function(a,d){var b="x"===d?"Width":"Height",h="scroll"+b;if(!n(a))return a[h]-$(a)[b.toLowerCase()]();var b="client"+b,k=a.ownerDocument||a.document,l=k.documentElement,k=k.body;return Math.max(l[h],k[h])-Math.min(l[b],k[b])};$.Tween.propHooks.scrollLeft=$.Tween.propHooks.scrollTop={get:function(a){return $(a.elem)[a.prop]()}, set:function(a){var d=this.get(a);if(a.options.interrupt&&a._last&&a._last!==d)return $(a.elem).stop();var b=Math.round(a.now);d!==b&&($(a.elem)[a.prop](b),a._last=this.get(a))}};return p});


     /*Toggle Classes Licensed to be used by Enabled only, only in items purcahsed from ThemeForest.net */
     $.fn.toggle2classes=function(s,a){return s&&a?this.each(function(){var t=$(this);t.hasClass(s)||t.hasClass(a)?t.toggleClass(s+" "+a):t.addClass(s)}):this};

     /*! * smoothState.js is jQuery plugin that progressively enhances * page loads to behave more like a single-page application. * * @author  Miguel Ángel Pérez   reachme@miguel-perez.com * @see     http://smoothstate.com * */
     !function(t){"use strict";"object"==typeof module&&"object"==typeof module.exports?t(require("jquery"),window,document):t(jQuery,window,document)}(function(t,e,n,o){"use strict";if(!e.history.pushState)return t.fn.smoothState=function(){return this},void(t.fn.smoothState.options={});if(!t.fn.smoothState){var r=t("html, body"),a=e.console,i={debug:!1,anchors:"a",hrefRegex:"",forms:"form",allowFormCaching:!1,repeatDelay:500,blacklist:".no-smoothState",prefetch:!1,prefetchOn:"mouseover touchstart",prefetchBlacklist:".no-prefetch",cacheLength:0,loadingClass:"is-loading",scroll:!0,alterRequest:function(t){return t},alterChangeState:function(t,e,n){return t},onBefore:function(t,e){},onStart:{duration:0,render:function(t){}},onProgress:{duration:0,render:function(t){}},onReady:{duration:0,render:function(t,e){t.html(e)}},onAfter:function(t,e){}},s={isExternal:function(t){var n=t.match(/^([^:\/?#]+:)?(?:\/\/([^\/?#]*))?([^?#]+)?(\?[^#]*)?(#.*)?/);return"string"==typeof n[1]&&n[1].length>0&&n[1].toLowerCase()!==e.location.protocol?!0:"string"==typeof n[2]&&n[2].length>0&&n[2].replace(new RegExp(":("+{"http:":80,"https:":443}[e.location.protocol]+")?$"),"")!==e.location.host},stripHash:function(t){return t.replace(/#.*/,"")},isHash:function(t,n){n=n||e.location.href;var o=t.indexOf("#")>-1,r=s.stripHash(t)===s.stripHash(n);return o&&r},translate:function(e){var n={dataType:"html",type:"GET"};return e="string"==typeof e?t.extend({},n,{url:e}):t.extend({},n,e)},shouldLoadAnchor:function(t,e,n){var r=t.prop("href");return!(s.isExternal(r)||s.isHash(r)||t.is(e)||t.prop("target")||typeof n!==o&&""!==n&&-1===t.prop("href").search(n))},clearIfOverCapacity:function(t,e){return Object.keys||(Object.keys=function(t){var e,n=[];for(e in t)Object.prototype.hasOwnProperty.call(t,e)&&n.push(e);return n}),Object.keys(t).length>e&&(t={}),t},storePageIn:function(e,n,o,r,a){var i=t("<html></html>").append(t(o));return e[n]={status:"loaded",title:i.find("title").first().text(),html:i.find("#"+r),doc:o,state:a},e},triggerAllAnimationEndEvent:function(e,n){n=" "+n||"";var o=0,r="animationstart webkitAnimationStart oanimationstart MSAnimationStart",a="animationend webkitAnimationEnd oanimationend MSAnimationEnd",i="allanimationend",l=function(n){t(n.delegateTarget).is(e)&&(n.stopPropagation(),o++)},u=function(n){t(n.delegateTarget).is(e)&&(n.stopPropagation(),o--,0===o&&e.trigger(i))};e.on(r,l),e.on(a,u),e.on("allanimationend"+n,function(){o=0,s.redraw(e)})},redraw:function(t){t.height()}},l=function(n){if(null!==n.state){var o=e.location.href,r=t("#"+n.state.id),a=r.data("smoothState"),i=a.href!==o&&!s.isHash(o,a.href),l=n.state!==a.cache[a.href].state;(i||l)&&(l&&a.clear(a.href),a.load(o,!1))}},u=function(i,l){var u=t(i),c=u.prop("id"),f=null,h=!1,d={},p={},g=e.location.href,m=function(t){t=t||!1,t&&d.hasOwnProperty(t)?delete d[t]:d={},u.data("smoothState").cache=d},y=function(e,n){n=n||t.noop;var o=s.translate(e);if(d=s.clearIfOverCapacity(d,l.cacheLength),!d.hasOwnProperty(o.url)||"undefined"!=typeof o.data){d[o.url]={status:"fetching"};var r=t.ajax(o);r.done(function(t){s.storePageIn(d,o.url,t,c),u.data("smoothState").cache=d}),r.fail(function(){d[o.url].status="error"}),n&&r.always(n)}},v=function(){if(f){var e=t(f,u);if(e.length){var n=e.offset().top;r.scrollTop(n)}f=null}},S=function(o){var i="#"+c,s=d[o]?t(d[o].html.html()):null;s.length?(n.title=d[o].title,u.data("smoothState").href=o,l.loadingClass&&r.removeClass(l.loadingClass),l.onReady.render(u,s),u.one("ss.onReadyEnd",function(){h=!1,l.onAfter(u,s),l.scroll&&v(),O(u)}),e.setTimeout(function(){u.trigger("ss.onReadyEnd")},l.onReady.duration)):!s&&l.debug&&a?a.warn("No element with an id of "+i+" in response from "+o+" in "+d):e.location=o},w=function(t,n,o){var i=s.translate(t);"undefined"==typeof n&&(n=!0),"undefined"==typeof o&&(o=!0);var f=!1,h=!1,g={loaded:function(){var t=f?"ss.onProgressEnd":"ss.onStartEnd";h&&f?h&&S(i.url):u.one(t,function(){S(i.url),o||m(i.url)}),n&&(p=l.alterChangeState({id:c},d[i.url].title,i.url),d[i.url].state=p,e.history.pushState(p,d[i.url].title,i.url)),h&&!o&&m(i.url)},fetching:function(){f||(f=!0,u.one("ss.onStartEnd",function(){l.loadingClass&&r.addClass(l.loadingClass),l.onProgress.render(u),e.setTimeout(function(){u.trigger("ss.onProgressEnd"),h=!0},l.onProgress.duration)})),e.setTimeout(function(){d.hasOwnProperty(i.url)&&g[d[i.url].status]()},10)},error:function(){l.debug&&a?a.log("There was an error loading: "+i.url):e.location=i.url}};d.hasOwnProperty(i.url)||y(i),l.onStart.render(u),e.setTimeout(function(){l.scroll&&r.scrollTop(0),u.trigger("ss.onStartEnd")},l.onStart.duration),g[d[i.url].status]()},E=function(e){var n,o=t(e.currentTarget);s.shouldLoadAnchor(o,l.blacklist,l.hrefRegex)&&!h&&(e.stopPropagation(),n=s.translate(o.prop("href")),n=l.alterRequest(n),y(n))},b=function(e){var n=t(e.currentTarget);if(!e.metaKey&&!e.ctrlKey&&s.shouldLoadAnchor(n,l.blacklist,l.hrefRegex)&&(e.stopPropagation(),e.preventDefault(),!T())){A();var o=s.translate(n.prop("href"));h=!0,f=n.prop("hash"),o=l.alterRequest(o),l.onBefore(n,u),w(o)}},C=function(e){var n=t(e.currentTarget);if(!n.is(l.blacklist)&&(e.preventDefault(),e.stopPropagation(),!T())){A();var r={url:n.prop("action"),data:n.serialize(),type:n.prop("method")};h=!0,r=l.alterRequest(r),"get"===r.type.toLowerCase()&&(r.url=r.url+"?"+r.data),l.onBefore(n,u),w(r,o,l.allowFormCaching)}},P=0,T=function(){var t=null===l.repeatDelay,e=parseInt(Date.now())>P;return!(t||e)},A=function(){P=parseInt(Date.now())+parseInt(l.repeatDelay)},O=function(t){l.anchors&&l.prefetch&&t.find(l.anchors).not(l.prefetchBlacklist).on(l.prefetchOn,null,E)},x=function(t){l.anchors&&(t.on("click",l.anchors,b),O(t)),l.forms&&t.on("submit",l.forms,C)},R=function(){var t=u.prop("class");u.removeClass(t),s.redraw(u),u.addClass(t)};return l=t.extend({},t.fn.smoothState.options,l),null===e.history.state?(p=l.alterChangeState({id:c},n.title,g),e.history.replaceState(p,n.title,g)):p={},s.storePageIn(d,g,n.documentElement.outerHTML,c,p),s.triggerAllAnimationEndEvent(u,"ss.onStartEnd ss.onProgressEnd ss.onEndEnd"),x(u),{href:g,cache:d,clear:m,load:w,fetch:y,restartCSSAnimations:R}},c=function(e){return this.each(function(){var n=this.tagName.toLowerCase();this.id&&"body"!==n&&"html"!==n&&!t.data(this,"smoothState")?t.data(this,"smoothState",new u(this,e)):!this.id&&a?a.warn("Every smoothState container needs an id but the following one does not have one:",this):"body"!==n&&"html"!==n||!a||a.warn("The smoothstate container cannot be the "+this.tagName+" tag")})};e.onpopstate=l,t.smoothStateUtility=s,t.fn.smoothState=c,t.fn.smoothState.options=i}});


 })

