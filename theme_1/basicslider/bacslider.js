/*
    Simple jQuery Carousel
    Author: Meeraj Wadhwa
*/

$.fn.sliderbac = function(opt) {

    // current slider tab default to first
    var _this = this,

    options = {
        // crousel length
        crouselLength: 3,
        // crousel per element width
        crouselWidth: 257,
        currentTab : 1
    },

    // selectors use by plugin
    selector = {
        ul: ".bacslider-container ul",
        prev: ".prevli",
        next: ".nextli",
        container: ".bacslider-container",
        mainContainer: $(this),
        li: ".bacslider-container ul li"
    },

    // bind click events
    bindEvents = function() {
    	selector.mainContainer.find(selector.prev).click(function() { _this.moveBack() });
        selector.mainContainer.find(selector.next).click(function() { _this.moveForward() });
    },

    // add classes to elements
    addClassToElements = function(sel, cls) {
        for(var i in sel)
            selector.mainContainer.find(sel[i]).addClass(cls);
    },

    // remove classes from element
    removeClassToElements = function(sel, cls) {
        for(var i in sel)
            selector.mainContainer.find(sel[i]).removeClass(cls);
    },


    init = function() {
    	// set crousel layout
    	_this.setCrousel();
        bindEvents();
    };

    // merging the objects
    $.extend(options, opt);

    // setting carousel on DOM
    this.setCrousel = function() {
    	// disable prev link
    	addClassToElements([selector.prev], "disable-link");
    	selector.mainContainer.find(selector.ul).css({width: (options.crouselLength * options.crouselWidth) + "px"});
    	selector.mainContainer.css({width: options.crouselWidth + "px"}).find(selector.container +"," + selector.li ).css({width: options.crouselWidth + "px"});
    },

    // move back
    this.moveBack = function() {
    	if(options.currentTab > 1) {
	    	removeClassToElements([selector.next, selector.prev], "disable-link");
    		options.currentTab--;
    		_this.moveCrousel();
    	} 

    	if(options.currentTab == 1) addClassToElements([selector.prev], "disable-link");
    },

    // move forward
    this.moveForward = function() {
    	if(options.currentTab < options.crouselLength) {	
            removeClassToElements([selector.next, selector.prev], "disable-link");
    		options.currentTab++;
    		_this.moveCrousel();
    	} 

    	if(options.currentTab == options.crouselLength) addClassToElements([selector.next], "disable-link");
    },

    // move crousel
    this.moveCrousel = function() {
		selector.mainContainer.find(selector.ul).animate({ 'left': -((options.currentTab - 1)*options.crouselWidth)+'px' }, 100, function() {});
    };

    // calling the init function on the fly
    init();
};

