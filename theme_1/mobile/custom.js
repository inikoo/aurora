$(document).ready(function(){      
    'use strict'
	    
    function init_template(){
		
		var scroll_animations = 1; //1 = Automatic //2 = Disabled //3 = Manual Classes

		var sidebar_effect = 'over' /* push, over sidebar effect */
		var sidebar_submenu_numbers = 'false' /* set true to show menu numbers instead of + icon */

		if(sidebar_submenu_numbers ==='true'){$('.menu-options').addClass('submenu-numbers');}
		if(sidebar_submenu_numbers ==='false'){$('.menu-options').addClass('no-submenu-numbers');}
		
		if (sidebar_effect === 'push' ){$('.sidebars').addClass('push-body')}
		if (sidebar_effect === 'over' ){$('.sidebars').addClass('classic-body')}	

		//Adding Animation Effect after Page Loads to Stop Flick Effect*/
		
		setTimeout(function(){
			$('.sidebar').addClass('sidebar-visible');
		},250);
		
		//Toggle Menu Style
		
		$('.toggle-menu-style').on('click', function(){
			$('.menu-options').toggleClass('icon-background');
			$('.menu-options').toggleClass('no-icon-background');
			return false;
		});			
		
		$('.toggle-menu-numbers').on('click', function(){
			$('.menu-options').toggleClass('submenu-numbers');
			if($('.menu-options').hasClass('submenu-numbers')){
				$('.submenu-numbers a[data-sub]').each(function(i){
					$(this).find('strong em').hide(0);
					var sidebar_sub_data = $(this).data('sub');
					var sidebar_sub_id = '#' + sidebar_sub_data;
					var sidebar_numbers = $(sidebar_sub_id).children().length;
					$(this).find('strong').append('<ins class="sub-numbers">' + sidebar_numbers + '</ins>');
					$(this).find('strong').css('text-align','center');
				});	
			}	
			if(!$('.menu-options').hasClass('submenu-numbers')){
				$('.sub-numbers').hide(0);
				$('.menu-options strong em').show(0);
			}
			return false;
		});		
		
		$('.toggle-menu-color').on('click', function(){
			$('.header').toggleClass('header-light');
			$('.header').toggleClass('header-dark');
			$('.footer-fixed').toggleClass('footer-fixed-light');
			$('.footer-fixed').toggleClass('footer-fixed-dark');
			$('.footer').toggleClass('footer-dark');
			$('.footer').toggleClass('footer-light');
			$('.menu-options').toggleClass('menu-light');
			$('.menu-options').toggleClass('menu-dark');
			$('.sidebars').toggleClass('sidebars-light');
			$('.sidebars').toggleClass('sidebars-dark');
			return false;
		});
            
		$('body').append('<div id="sidebar-tap-close"></div>');
		
        //Generating Sidebar Functions
        $('.open-sidebar-left').on(' click ',function(){
			//Push Sidebar
            if($('.sidebars').hasClass('push-body')){                
				$('.sidebar-left').toggleClass('sidebar-left-active'); 
				$('#page-content, #sidebar-tap-close, .header').toggleClass('sidebar-large-left-body-active');
				$('#sidebar-tap-close').addClass('allow-tap');
            } 
            
			//Classic Sidebar
            if($('.sidebars').hasClass('classic-body')){
				$('.sidebar-left').toggleClass('sidebar-left-active'); 
				$('#page-content, .header').toggleClass('body-left-effect');
				$('#sidebar-tap-close').addClass('allow-tap');
            }
			return false;
        });        
        
        $('.open-sidebar-right').on('click',function(){
			//Push Sidebar
            if($('.sidebars').hasClass('push-body')){                
				$('.sidebar-right').toggleClass('sidebar-right-active'); 
				$('#page-content, .header').toggleClass('sidebar-large-right-body-active');
				$('#sidebar-tap-close').addClass('allow-tap');
            } 
            
			//Classic Sidebar
            if($('.sidebars').hasClass('classic-body')){
				$('.sidebar-right').toggleClass('sidebar-right-active'); 
				$('#page-content, .header').toggleClass('body-right-effect');
				$('#sidebar-tap-close').addClass('allow-tap');
            }
			return false;
        });
		    
        $('.close-sidebar, #sidebar-tap-close').on('click',function(){
			close_sidebar();
			return false;
		});
		
		//Close Sidebar Function
		function close_sidebar(){
			$('#sidebar-tap-close').removeClass();
            $('.sidebar').removeClass('sidebar-left-active sidebar-right-active');
            $('.header').removeClass('sidebar-large-left-body-active sidebar-large-right-body-active body-right-effect sidebar-small-right-body-active body-left-effect sidebar-small-left-body-active');
            $('#page-content').removeClass();
            $('.open-sidebar-left, .open-sidebar-right').find('em').removeClass('hm1a hm2a hm3a dm1a dm2a');	
		}
		
		//Header Menu Function
		function open_header_menu(){
			$('.header-menu').toggleClass('active-header-menu');	
			$('.header-menu-overlay').addClass('active-overlay');
		}
		
		function hide_header_menu(){
			$('.header-menu').removeClass('active-header-menu');
			$('.header-menu-overlay').removeClass('active-overlay');
			$('.open-header-menu').find('.hm1').removeClass('hm1a'); 
			$('.open-header-menu').find('.hm2').removeClass('hm2a'); 
			$('.open-header-menu').find('.hm3').removeClass('hm3a'); 
			$('.open-header-menu').find('.dm1').removeClass('dm1a'); 
			$('.open-header-menu').find('.dm2').removeClass('dm2a'); 
			$('.open-header-menu').find('.ph1').removeClass('ph1a'); 
			$('.open-header-menu').find('.ph2').removeClass('ph2a'); 
		}
		
		//Footer Menu Function
		function open_footer_menu(){
			$('.footer-menu').toggleClass('active-footer-menu');
			$('.footer-menu-overlay').addClass('active-overlay');
		}
				
		function hide_footer_menu(){
			$('.footer-menu').removeClass('active-footer-menu');
			$('.footer-menu-overlay').removeClass('active-overlay');
			$('.open-footer-menu').find('.hm1').removeClass('hm1a'); 
			$('.open-footer-menu').find('.hm2').removeClass('hm2a'); 
			$('.open-footer-menu').find('.hm3').removeClass('hm3a'); 
			$('.open-footer-menu').find('.dm1').removeClass('dm1a'); 
			$('.open-footer-menu').find('.dm2').removeClass('dm2a'); 
			$('.open-footer-menu').find('.ph1').removeClass('ph1a'); 
			$('.open-footer-menu').find('.ph2').removeClass('ph2a'); 
		}
		
		//Modal Menu Function
		function open_modal_menu(){
			$('.modal-menu').toggleClass('active-modal-menu');
			$('.modal-menu-overlay').addClass('active-overlay');
		}

		function hide_modal_menu(){
			$('.modal-menu').removeClass('active-modal-menu');
			$('.modal-menu-overlay').removeClass('active-overlay');
			$('.open-modal-menu').find('.hm1').removeClass('hm1a'); 
			$('.open-modal-menu').find('.hm2').removeClass('hm2a'); 
			$('.open-modal-menu').find('.hm3').removeClass('hm3a'); 
			$('.open-modal-menu').find('.dm1').removeClass('dm1a'); 
			$('.open-modal-menu').find('.dm2').removeClass('dm2a'); 
			$('.open-modal-menu').find('.ph1').removeClass('ph1a'); 
			$('.open-modal-menu').find('.ph2').removeClass('ph2a'); 
		}
		
		function toggle_modal_menu(){
			$('.modal-menu').toggleClass('active-modal-menu');
			$('.modal-menu-overlay').toggleClass('active-overlay');
		}
		
		$('.toggle-modal-menu').on('click', function(){
			toggle_modal_menu();
		});
		
		$('.demo-icon a').on('click',function(){
			return false;
		});
		
		function remove_icons(){
			$('.hamburger-animated').find('.hm1').removeClass('hm1a'); 
			$('.hamburger-animated').find('.hm2').removeClass('hm2a'); 
			$('.hamburger-animated').find('.hm3').removeClass('hm3a'); 
			$('.dropdown-animated').find('.dm1').removeClass('dm1a'); 
			$('.dropdown-animated').find('.dm2').removeClass('dm2a'); 
			$('.plushide-animated').find('.ph1').removeClass('ph1a'); 
			$('.plushide-animated').find('.ph2').removeClass('ph2a'); 
		}		
		
		$('.open-sidebar-left, .open-sidebar-right').on("click", function(){
			hide_footer_menu();
			hide_header_menu();
			hide_modal_menu();
			hide_header_menu();
		});
        		
		if($('.menu-wrapper').hasClass('modal-menu')){$('body').append('<div class="page-overlay modal-menu-overlay"></div>');}
		if($('.menu-wrapper').hasClass('header-menu')){$('#page-content').append('<div class="page-overlay header-menu-overlay"></div>');}
		if($('.menu-wrapper').hasClass('footer-menu')){$('body').append('<div class="page-overlay footer-menu-overlay"></div>');}

		//Activate Menu Functions
		$('.open-header-menu').on('click',function(){open_header_menu(); hide_footer_menu(); hide_modal_menu(); return false;});	
		$('.hide-header-menu').on('click', function(){hide_header_menu(); return false;});
		$('.open-modal-menu').on('click',function(){open_modal_menu(); hide_header_menu(); hide_footer_menu(); return false;});
		$('.hide-modal-menu').on('click',function(){hide_modal_menu(); return false;});
		$('.page-overlay').on('click',function(){hide_modal_menu(); hide_header_menu(); hide_footer_menu(); remove_icons(); return false;});
		$('.open-footer-menu').on('click',function(){open_footer_menu(); hide_modal_menu(); hide_header_menu(); return false;});
		$('.hide-footer-menu').on('click',function(){hide_footer_menu(); return false;});

		//Submenu Functions
		$('.submenu a').prepend('<i class="ion-ios-arrow-right"></i>')
		$('.submenu a').append('<i class="ion-record"></i>')
		
		$("a[data-sub]").on('click', function() {
			var subname = $(this).data('sub');
			var subsize = $('#'+ subname ).children().length;
			var subheight = $('#'+subname).height();
						
			//Individual classes for animated icons added here
			//This is essential. "if" can cause a small lag that will
			//deploy icons wrongly if placed in main trigger in generator
			
			if (subheight > 0){
				$('#'+ subname ).css("height", "0px");
				$(this).removeClass('active-item');
			    $(this).find('strong .hm1').removeClass('hm1a'); 
			    $(this).find('strong .hm2').removeClass('hm2a'); 
			    $(this).find('strong .hm3').removeClass('hm3a'); 
			    $(this).find('strong .dm1').removeClass('dm1a'); 
			    $(this).find('strong .dm2').removeClass('dm2a'); 
			    $(this).find('strong .ph1').removeClass('ph1a'); 
			    $(this).find('strong .ph2').removeClass('ph2a'); 
			}	
			
			if (subheight == 0){
				$(this).addClass('active-item');
				$('#'+ subname ).css("height", subsize * 55);
			    $(this).find('strong .hm1').addClass('hm1a'); 
			    $(this).find('strong .hm2').addClass('hm2a'); 
			    $(this).find('strong .hm3').addClass('hm3a'); 
			    $(this).find('strong .dm1').addClass('dm1a'); 
			    $(this).find('strong .dm2').addClass('dm2a'); 
			    $(this).find('strong .ph1').addClass('ph1a'); 
			    $(this).find('strong .ph2').addClass('ph2a'); 
			}		
			return false;
		});
		
		//Delaying Function is necssary for elements added by JS.
		setTimeout(function(){
			$('.submenu-numbers a[data-sub]').each(function(i){
				$(this).find('strong em').hide(0);
				var sidebar_sub_data = $(this).data('sub');
				var sidebar_sub_id = '#' + sidebar_sub_data;
				var sidebar_numbers = $(sidebar_sub_id).children().length;
				$(this).find('strong').append('<ins class="sub-numbers">' + sidebar_numbers + '</ins>');
				$(this).find('strong').css('text-align','center');
			});
		},10);
		
		//Activating the Submenu when active-item class is detetected
		if($('.menu-options a').hasClass('active-item')){
			var sidebar_sub_data = $('.active-item').data('sub');
			var sidebar_sub_id = '#' + sidebar_sub_data;
			var sidebar_numbers = $(sidebar_sub_id).children().length;
			$(sidebar_sub_id).css("height", sidebar_numbers * 55);
			
			setTimeout(function(){
				$('.active-item').find('strong .hm1').addClass('hm1a'); 
				$('.active-item').find('strong .hm2').addClass('hm2a'); 
				$('.active-item').find('strong .hm3').addClass('hm3a'); 
				$('.active-item').find('strong .dm1').addClass('dm1a'); 
				$('.active-item').find('strong .dm2').addClass('dm2a'); 
				$('.active-item').find('strong .ph1').addClass('ph1a'); 
				$('.active-item').find('strong .ph2').addClass('ph2a'); 
			},50);
		}
		
        //Generating Menu Icons
        $('.hamburger-animated').html('<em class="hm1"></em><em class="hm2"></em><em class="hm3"></em>');
        $('.hamburger-animated').on("click",function(){
           $(this).find('.hm1').toggleClass('hm1a'); 
           $(this).find('.hm2').toggleClass('hm2a'); 
           $(this).find('.hm3').toggleClass('hm3a'); 
        });     
        
        $('.dropdown-animated').html('<em class="dm1"></em><em class="dm2"><em>');
        $('.dropdown-animated').on("click",function(){
           $(this).find('.dm1').toggleClass('dm1a'); 
           $(this).find('.dm2').toggleClass('dm2a'); 
        });
		
		$('.plushide-animated').html('<em class="ph1"></em><em class="ph2"></em>');
        $('.plushide-animated').on("click",function(){
           $(this).find('.ph1').toggleClass('ph1a'); 
           $(this).find('.ph2').toggleClass('ph2a'); 
        });
		
		//Add Extra Space on Footer
		
		if($('.footer-fixed').length > 0 ){
			$('#page-content-scroll').append('<div class="footer-clear"></div>');
		}

        //Tabs
        $('tabs a').on("click", function(){
            preventDefault();
            return false;
        });
        
        //FastClick
        $(function() {FastClick.attach(document.body);});

        //Preload Image
        $(function() {
            $(".preload-image").lazyload({
                threshold : 2000
            });
        });
        
        $('.hide-notification').on( "click", function(){
            $(this).parent().slideUp(); 
            return false;
        });        
        $('.tap-hide').on( "click", function(){
            $(this).slideUp(); 
            return false;
        });
        
        $('.activate-toggle').on( "click", function(){
            $(this).parent().find('.toggle-content').slideToggle(250);
            $(this).parent().find('input').each(function () { this.checked = !this.checked; });
            $(this).parent().find('.toggle-45').toggleClass('rotate-45 color-red-dark');
            $(this).parent().find('.toggle-180').toggleClass('rotate-180 color-red-dark');
            return false;
        });
        
        $('.accordion-item').on( "click", function(){
            $(this).find('.accordion-content').slideToggle(250);
            $(this).find('i').toggleClass('rotate-135 color-red-dark');
            return false;
        });
        
        $('.dropdown-toggle').on( "click", function(){
            $(this).parent().find('.dropdown-content').slideToggle(250); 
            $(this).find('i:last-child').toggleClass('rotate-135');
            return false;
        });
        
        //Portfolio Wide
        
        $('.portfolio-wide-caption a').on( "click", function(){
           $(this).parent().parent().find('.portfolio-wide-content').slideToggle(250);
            return false;
        });
                
        //Detect if iOS WebApp Engaged and permit navigation without deploying Safari
        (function(a,b,c){if(c in b&&b[c]){var d,e=a.location,f=/^(a|html)$/i;a.addEventListener("click",function(a){d=a.target;while(!f.test(d.nodeName))d=d.parentNode;"href"in d&&(d.href.indexOf("http")||~d.href.indexOf(e.host))&&(a.preventDefault(),e.href=d.href)},!1)}})(document,window.navigator,"standalone")

        //Detecting Mobiles//
        var isMobile = {
            Android: function() {return navigator.userAgent.match(/Android/i);},
            BlackBerry: function() {return navigator.userAgent.match(/BlackBerry/i);},
            iOS: function() {return navigator.userAgent.match(/iPhone|iPad|iPod/i);},
            Opera: function() {return navigator.userAgent.match(/Opera Mini/i);},
            Windows: function() {return navigator.userAgent.match(/IEMobile/i);},
            any: function() {return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());}
        };

        if( !isMobile.any() ){
            $('.show-blackberry, .show-ios, .show-windows, .show-android').addClass('disabled');
            $('.show-no-detection').removeClass('disabled');
        }
        if(isMobile.Android()) {
            //Status Bar Color for Android
            $('head').append('<meta name="theme-color" content="#000000"> />');
            $('.show-android').removeClass('disabled');
            $('.show-blackberry, .show-ios, .show-windows, .show-download').addClass('disabled');
            $('.sidebar-scroll').css('right', '0px');
            $('.set-today').addClass('mobile-date-correction');
        }
        if(isMobile.BlackBerry()) {
            $('.show-blackberry').removeClass('disabled');
            $('.show-android, .show-ios, .show-windows, .show-download').addClass('disabled');
            $('.sidebar-scroll').css('right', '0px');
        }   
        if(isMobile.iOS()) {
            $('.show-ios').removeClass('disabled');
            $('.show-blackberry, .show-android, .show-windows, .show-download').addClass('disabled');
            $('.sidebar-scroll').css('right', '0px');
            $('.set-today').addClass('mobile-date-correction');
        }
        if(isMobile.Windows()) {
            $('.show-windows').removeClass('disabled');
            $('.show-blackberry, .show-ios, .show-android, .show-download').addClass('disabled');
            $('.sidebar-scroll').css('right', '0px');
        }
        
        //These are the splash images that show when adding to the homescreen, we're
        //delaying these to load only after the page loads to increase load speed and it's
        //also easier to generally edit them by only editing this area instead of each HTML file
        //This will also put a lower request rate on the page increasing it's load speed.
        $('head').prepend('<link rel="icon" type="image/png" href="images/splash/android-chrome-192x192.png" sizes="192x192">');
        $('head').prepend('<link rel="apple-touch-icon" sizes="180x180" href="images/splash/apple-touch-icon-180x180.png">');
        $('head').prepend('<link rel="apple-touch-icon" sizes="152x152" href="images/splash/apple-touch-icon-152x152.png">');
        $('head').prepend('<link rel="apple-touch-icon" sizes="144x144" href="images/splash/apple-touch-icon-144x144.png">');
        $('head').prepend('<link rel="apple-touch-icon" sizes="120x120" href="images/splash/apple-touch-icon-120x120.png">');
        $('head').prepend('<link rel="apple-touch-icon" sizes="114x114" href="images/splash/apple-touch-icon-114x114.png">');
        $('head').prepend('<link rel="apple-touch-icon" sizes="76x76" href="images/splash/apple-touch-icon-76x76.png">');
        $('head').prepend('<link rel="apple-touch-icon" sizes="72x72" href="images/splash/apple-touch-icon-72x72.png">');
        $('head').prepend('<link rel="apple-touch-icon" sizes="60x60" href="images/splash/apple-touch-icon-60x60.png">');
        $('head').prepend('<link rel="apple-touch-icon" sizes="57x57" href="images/splash/apple-touch-icon-57x57.png">');
        $('head').prepend('<link rel="icon" type="image/png" href="images/splash/favicon-96x96.png" sizes="96x96">');
        $('head').prepend('<link rel="icon" type="image/png" href="images/splash/favicon-32x32.png" sizes="32x32">');
        $('head').prepend('<link rel="icon" type="image/png" href="images/splash/favicon-16x16.png" sizes="16x16">');
        $('head').prepend('<link rel="shortcut icon" href="images/splash/favicon.ico" type="image/x-icon"/>');

        function animation_settings(){
            window.sr = ScrollReveal();
            //General Classes
            sr.reveal('.animate-left',  {origin:'left',  distance:'100%', easing:'cubic-bezier(0.1, 0.2, 0.1, 1)'  });
            sr.reveal('.animate-right', {origin:'right', distance:'100%', easing:'cubic-bezier(0.1, 0.2, 0.1, 1)'  });
            sr.reveal('.animate-top',   {origin:'top',   distance:'100%', easing:'cubic-bezier(0.1, 0.2, 0.1, 1)'  });
            sr.reveal('.animate-bottom',{origin:'bottom',distance:'100%', easing:'cubic-bezier(0.1, 0.2, 0.1, 1)'  });
            sr.reveal('.animate-fade',  {origin:'top',   easing:'ease-in-out'  });
            sr.reveal('.animate-zoom',  {origin:'top',   scale: 1.3, easing:'cubic-bezier(0.1, 0.2, 0.1, 1)'  });

            sr.reveal('.animate-time-500',  {duration: 500 });
            sr.reveal('.animate-time-1000',  {duration: 1000 });
            sr.reveal('.animate-time-2000',  {duration: 2000 });
            sr.reveal('.animate-time-3000',  {duration: 3000 });
            sr.reveal('.animate-time-4000',  {duration: 4000 });
            sr.reveal('.animate-time-5000',  {duration: 5000 });
            sr.reveal('.animate-time-6000',  {duration: 6000 });     

            sr.reveal('.animate-delay-50',  {delay: 50 });
            sr.reveal('.animate-delay-100',  {delay: 100 });
            sr.reveal('.animate-delay-150',  {delay: 150 });
            sr.reveal('.animate-delay-200',  {delay: 200 });
            sr.reveal('.animate-delay-250',  {delay: 250 });
            sr.reveal('.animate-delay-300',  {delay: 300 });
            sr.reveal('.animate-delay-350',  {delay: 350 });
            sr.reveal('.animate-delay-400',  {delay: 400 });
            sr.reveal('.animate-delay-450',  {delay: 450 });
            sr.reveal('.animate-delay-500',  {delay: 500 });
            sr.reveal('.animate-delay-550',  {delay: 550 });
            sr.reveal('.animate-delay-600',  {delay: 600 });
            sr.reveal('.animate-delay-650',  {delay: 650 });
            sr.reveal('.animate-delay-700',  {delay: 700 });
            sr.reveal('.animate-delay-750',  {delay: 750 });
            sr.reveal('.animate-delay-800',  {delay: 800 });
            sr.reveal('.animate-delay-850',  {delay: 850 });
            sr.reveal('.animate-delay-900',  {delay: 900 });
            sr.reveal('.animate-delay-950',  {delay: 950 });
            sr.reveal('.animate-delay-1000',  {delay: 1000 });   
            sr.reveal('.animate-delay-1050',  {delay: 1050 });   
            sr.reveal('.animate-delay-1100',  {delay: 1100 });   
            sr.reveal('.animate-delay-1150',  {delay: 1150 });   
            sr.reveal('.animate-delay-1200',  {delay: 1200 });   
            sr.reveal('.animate-delay-1250',  {delay: 1250 });   
            sr.reveal('.animate-delay-1300',  {delay: 1300 });   
            sr.reveal('.animate-delay-1350',  {delay: 1350 });   
            sr.reveal('.animate-delay-1400',  {delay: 1400 });   
            sr.reveal('.animate-delay-1450',  {delay: 1450 });   
            sr.reveal('.animate-delay-1500',  {delay: 1500 });  
        };
        
        //Page Animations 
        if(scroll_animations == 1){
            animation_settings();
            //Top Animations
            sr.reveal('.heading-strip h4, .heading-strip h3, .heading-block h4',{origin:'top',   distance:'100%', easing:'cubic-bezier(0.1, 0.2, 0.1, 1)'  });
            //Bottom Animations
            sr.reveal('.heading-strip p, .heading-block p',{origin:'bottom',distance:'100%', easing:'cubic-bezier(0.1, 0.2, 0.1, 1)'  });
            //Left Animations
            sr.reveal('.heading-line-1, .column-icon, .column-half-image-left',{origin:'left',  distance:'100%', easing:'cubic-bezier(0.1, 0.2, 0.1, 1)'  });
            //Right Animations
            sr.reveal('.heading-line-2, .column-half-image-right, .heading-strip i',{origin:'right', distance:'100%', easing:'cubic-bezier(0.1, 0.2, 0.1, 1)'  });
            //Zoom Animations
            sr.reveal('.heading-text i, .heading-block .button, .footer-logo', {origin:'center',   scale: 2.1, easing:'cubic-bezier(0.1, 0.2, 0.1, 1)'  }); 
            //Fade Animations
            sr.reveal('.center-socials a, .footer-socials, .footer p',  {origin:'top',   easing:'ease-in-out'  });
        }
        
        if(scroll_animations == 2){
            $('.animate-top, .animate-bottom, .animate-left, .animate-right, .animate-fade, .animate-zoom, .heading-strip h4, .heading-strip p').css({"visibility": "visible"});
            $('.heading-strip h4, .heading-strip p, .heading-strip i, .heading-strip h3, .heading-line-1, .heading-line-2').css({"visibility": "visible"});
            $('.heading-block h4, .heading-block p, .heading-text i, .center-socials a').css({"visibility": "visible"});
            $('.heading-block img, .footer-socials, .footer-logo, .footer p, .footer-socials').css({"visibility": "visible"});
        }
        
        if(scroll_animations == 3){
            animation_settings();
            $('.heading-strip h4, .heading-strip p, .heading-strip i, .heading-strip h3, .heading-line-1, .heading-line-2').css({"visibility": "visible"});
            $('.heading-block h4, .heading-block p, .heading-text i, .center-socials a').css({"visibility": "visible"});
            $('.heading-block img .footer-socials, .footer-logo, .footer p, .footer-socials').css({"visibility": "visible"});
        }
        
        //Galleries
        $(".gallery a, .show-gallery").swipebox();
        
        function apply_gallery_justification(){
            var screen_widths = $(window).width();
            if( screen_widths < 768){ 
                $('.gallery-justified').justifiedGallery({
                    rowHeight : 70,
                    maxRowHeight : 370,
                    margins : 5,
                    fixedHeight:false
                });
            };

            if( screen_widths > 768){
                $('.gallery-justified').justifiedGallery({
                    rowHeight : 150,
                    maxRowHeight : 370,
                    margins : 5,
                    fixedHeight:false
                });
            };
        };
        apply_gallery_justification();

        //Adaptive Folios
        $('.adaptive-one').on( "click", function(){
            $('.portfolio-switch').removeClass('active-adaptive');
            $(this).addClass('active-adaptive');
            $('.portfolio-adaptive').removeClass('portfolio-adaptive-two portfolio-adaptive-three');
            $('.portfolio-adaptive').addClass('portfolio-adaptive-one');
            return false;
        });    
        $('.adaptive-two').on( "click", function(){
            $('.portfolio-switch').removeClass('active-adaptive');
            $(this).addClass('active-adaptive');
            $('.portfolio-adaptive').removeClass('portfolio-adaptive-one portfolio-adaptive-three');
            $('.portfolio-adaptive').addClass('portfolio-adaptive-two'); 
            return false;
        });    
        $('.adaptive-three').on( "click", function(){
            $('.portfolio-switch').removeClass('active-adaptive');
            $(this).addClass('active-adaptive');
            $('.portfolio-adaptive').removeClass('portfolio-adaptive-two portfolio-adaptive-one');
            $('.portfolio-adaptive').addClass('portfolio-adaptive-three'); 
            return false;
        });

        //Show Back To Home When Scrolling
        $(window).on('scroll', function () {
            var total_scroll_height = document.body.scrollHeight
            var inside_header = ($(this).scrollTop() <= 200);
            var passed_header = ($(this).scrollTop() >= 0); //250
            var passed_header2 = ($(this).scrollTop() >= 150); //250
            var footer_reached = ($(this).scrollTop() >= (total_scroll_height - ($(window).height() + 300 )));

            if (inside_header === true) {
                $('.back-to-top-badge').removeClass('back-to-top-badge-visible');
                
            } else if (passed_header === true)  {
                $('.back-to-top-badge').addClass('back-to-top-badge-visible');
            } 
            if (footer_reached == true){            
                $('.back-to-top-badge').removeClass('back-to-top-badge-visible');
            }
        });
                
        //Back to top Badge
        $('.back-to-top-badge, .back-to-top').on( "click", function(e){
            e.preventDefault();
            $('html, body').animate({
                scrollTop: 0
            }, 1000);
        });
        
        //Back to top Badge
        $('.back-to-top-menu').on( "click", function(e){
            e.preventDefault();
            $('.landing-menu-scroll').animate({
                scrollTop: 0
            }, 1000);
        });     
                
        //Bottom Share Fly-up    
        $('body').append('<div class="share-bottom-tap-close"></div>');
        $('.show-share-bottom, .show-share-box').click(function(){
            $('.share-bottom-tap-close').addClass('share-bottom-tap-close-active');
            $('.share-bottom').toggleClass('active-share-bottom'); 
            return false;
        });    
        $('.close-share-bottom, .share-bottom-tap-close').click(function(){
           $('.share-bottom-tap-close').removeClass('share-bottom-tap-close-active');
           $('.share-bottom').removeClass('active-share-bottom'); 
            return false;
        });

        //Set inputs to today's date by adding class set-day
        var set_input_now = new Date();
        var set_input_month = (set_input_now.getMonth() + 1);               
        var set_input_day = set_input_now.getDate();
        if(set_input_month < 10) 
            set_input_month = "0" + set_input_month;
        if(set_input_day < 10) 
            set_input_day = "0" + set_input_day;
        var set_input_today = set_input_now.getFullYear() + '-' + set_input_month + '-' + set_input_day;
        $('.set-today').val(set_input_today);

        //Countdown Timer
        $(function() {$('.countdown-class').countdown({ date: "June 7, 2087 15:03:26"});});

        //Copyright Year 
        var dteNow = new Date();
        var intYear = dteNow.getFullYear();
        $('#copyright-year, .copyright-year').html(intYear);
        
        //Contact Form
        var formSubmitted="false";jQuery(document).ready(function(e){function t(t,n){formSubmitted="true";var r=e("#"+t).serialize();e.post(e("#"+t).attr("action"),r,function(n){e("#"+t).hide();e("#formSuccessMessageWrap").fadeIn(500)})}function n(n,r){e(".formValidationError").hide();e(".fieldHasError").removeClass("fieldHasError");e("#"+n+" .requiredField").each(function(i){if(e(this).val()==""||e(this).val()==e(this).attr("data-dummy")){e(this).val(e(this).attr("data-dummy"));e(this).focus();e(this).addClass("fieldHasError");e("#"+e(this).attr("id")+"Error").fadeIn(300);return false}if(e(this).hasClass("requiredEmailField")){var s=/^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;var o="#"+e(this).attr("id");if(!s.test(e(o).val())){e(o).focus();e(o).addClass("fieldHasError");e(o+"Error2").fadeIn(300);return false}}if(formSubmitted=="false"&&i==e("#"+n+" .requiredField").length-1){t(n,r)}})}e("#formSuccessMessageWrap").hide(0);e(".formValidationError").fadeOut(0);e('input[type="text"], input[type="password"], textarea').focus(function(){if(e(this).val()==e(this).attr("data-dummy")){e(this).val("")}});e("input, textarea").blur(function(){if(e(this).val()==""){e(this).val(e(this).attr("data-dummy"))}});e("#contactSubmitButton").click(function(){n(e(this).attr("data-formId"));return false})})

        // Image Sliders
        var pricing_table = new Swiper('.pricing-table-slider', {
            pagination: '.swiper-pagination',
            paginationClickable: true,
            slidesPerView: 3,
            nextButton: '.pricing-table-next',
            prevButton: '.pricing-table-prev',
            spaceBetween: 50,
            breakpoints: {
                1024: {slidesPerView: 2, spaceBetween: 40},
                768: {slidesPerView: 2, spaceBetween: 30},
                640: {slidesPerView: 1, spaceBetween: 20},
                320: {slidesPerView: 1,spaceBetween: 10}
            }
        });
        
		
        var swiper_store_thumbnail_slider = new Swiper('.footer-fixed', {
            pagination: '.swiper-pagination',
            nextButton:'.next-footer',
            prevButton:'.prev-footer',
            paginationClickable: true,
            slidesPerView: 6,
            spaceBetween: 20,
            breakpoints: {
                1024:{slidesPerView: 6, spaceBetween: 20},
                768: {slidesPerView: 5, spaceBetween: 10},
                640: {slidesPerView: 5, spaceBetween: 5},
                320: {slidesPerView: 4,spaceBetween: 5}
            }
        });   

        var swiper_coverpage = new Swiper('.coverpage-classic', {
            pagination: '.coverpage-slider .swiper-pagination',
            nextButton:'.flashing-arrows-1',
            prevButton:'.flashing-arrows-2',
            paginationClickable: true
        });
        
        var swiper_category_slider = new Swiper('.category-slider', {
        pagination: '.swiper-pagination',
        paginationClickable: true,
        slidesPerView: 5,
        spaceBetween: 20,
        breakpoints: {
            1024: {slidesPerView: 6, spaceBetween: 20},
            768: {slidesPerView: 5, spaceBetween: 10},
            640: {slidesPerView: 3, spaceBetween: 5},
            320: {slidesPerView: 3, spaceBetween: 5}
        }
        });
   
        setTimeout(function(){
        var swiper_coverflow_thumbnails = new Swiper('.coverflow-thumbnails', {
            pagination: '.swiper-pagination',
            effect: 'coverflow',
            autoplay:3000,
            autoplayDisableOnInteraction: false,
            spaceBetween:-30,
            loop:true,
            grabCursor: true,
            centeredSlides: true,
            slidesPerView: 'auto',
            coverflow: {
                rotate: 35,
                stretch: -50,
                depth: -190,
                modifier:1,
                slideShadows : true
            }
        });      
        },300);
            
        
        var swiper_coverflow_slider = new Swiper('.coverflow-slider', {
            pagination: '.swiper-pagination',
            effect: 'coverflow',
            autoplay:3000,
            autoplayDisableOnInteraction: false,
            loop:true,
            grabCursor: true,
            centeredSlides: true,
            slidesPerView: 'auto',
            coverflow: {
                rotate: 60,
                stretch: -60,
                depth: 400,
                modifier: 1,
                slideShadows : false
            }
        });
        
        var swiper_staff_slider = new Swiper('.staff-slider', {
            nextButton: '.next-staff-slider',
            prevButton: '.prev-staff-slider',
            autoplay:5000,
            loop:true,
            autoplayDisableOnInteraction: false,
            slidesPerView: 3,
            spaceBetween: 20,
            breakpoints: {
                1024: {slidesPerView: 3, spaceBetween: 20},
                768: {slidesPerView: 2, spaceBetween: 10},
                640: {slidesPerView: 1, spaceBetween: 5}
            }
        });        
        
        var expanding_slider = new Swiper('.expanding-slider', {
            autoplay:3000,
            autoplayDisableOnInteraction: false,
            slidesPerView: 4,
            spaceBetween: 20,
            breakpoints: {
                1024: {slidesPerView: 4, spaceBetween: 20},
                768: {slidesPerView: 3, spaceBetween: 10},
                640: {slidesPerView: 1, spaceBetween: 5},               
                0: {slidesPerView: 1, spaceBetween: 5}
            }
        });
        
        var swiper = new Swiper('.home-slider',{autoplay:4000, loop:true});
        var swiper = new Swiper('.home-round-slider',{autoplay:4000, loop:true});
        var swiper = new Swiper('.home-fader',{autoplay:400000,  autoHeight:true, loop:true, effect:'fade', preloadImages:true, lazyLoading:true});
        var swiper_news_slider = new Swiper('.news-slider',{autoplay:4000, loop:true});
        var swiper_single_item = new Swiper('.single-item',{autoplay:4000, loop:true});
        var swiper_quote_slider = new Swiper('.quote-slider',{autoplay:4000, loop:true});
        var swiper_text_slider = new Swiper('.text-slider',{autoplay:2000, loop:true});
        var swiper_call_to_action = new Swiper('.call-to-action-slider',{autoplay:4000, slidesPerView:1, loop:true});
        var swiper_store_slider = new Swiper('.store-slider', {autoplay:3000, loop:true});
        var swiper_store_slider2 = new Swiper('.store-slider-2', {autoplay:3000, loop:true});
     
		
		var swiper_store_thumbnail_slider = new Swiper('.store-thumbnails', {
            pagination: '.swiper-pagination',
            paginationClickable: true,
            slidesPerView: 5,
            spaceBetween: 20,
            breakpoints: {
                1024: {slidesPerView: 6, spaceBetween: 20},
                768: {slidesPerView: 5, spaceBetween: 10},
                640: {slidesPerView: 2, spaceBetween: 5},
                320: {slidesPerView: 2, spaceBetween: 5}
            }
        });  
		
        //Aligning Elements & Resize Handlers//
        
        function center_content(){
            var screen_width = $(window).width();
            var screen_height = $(window).height();
            var content_width = $('.content-center').width();
            var content_height = $('.content-center').height();            
            var content_full_width = $('.page-fullscreen-content').outerWidth();
            var content_full_height = $('.page-fullscreen-content').outerHeight();
            
            var cover_center_height = $('.coverpage-center').outerHeight();
            var cover_center_width = $('.coverpage-center').outerWidth();

            $('.content-center').css({
                "left":"50%",
                "top":"50%",
                "margin-left": (content_width/2)*(-1),
                "margin-top": (content_height/2)*(-1) - ($('.outter-elements').height()/2)
            });
            
            $('.page-fullscreen').css({
                "width":screen_width,
                "height":screen_height 
            });
                        
            $('.page-fullscreen-content').css({
                "left":"50%",
                "top":"50%",
                "margin-left": (content_full_width/2)*(-1),
                "margin-top": (content_full_height/2)*(-1)
            });                       
            
            $('.coverpage-classic').css({"height": screen_height});           
            $('.coverpage-clear').css({"height": screen_height - 60});
            $('.coverpage-cube').css({"height": screen_height - 100});
            $('#page-content').css({"min-height": screen_height});
            
            $('.coverpage-center').css({
                "left":"50%",
                "top":"50%",
                "margin-left": (cover_center_width/2)*(-1),
                "margin-top": (cover_center_height/2)*(-1)
            });       
            
            $('.map-fullscreen iframe').css('width', screen_width);
            $('.map-fullscreen iframe').css('height', screen_height);
                        
        
            var mobileui_home = (screen_height - 100); 
        
            $('.mobileui-home').css('height', mobileui_home);
            $('.mobileui-home-5 a').css('height', mobileui_home/5);
            $('.mobileui-home-4 a').css('height', mobileui_home/4);
            $('.mobileui-home-3 a').css('height', mobileui_home/3);
        };

        center_content();
        $(window).on("resize", function() {
            center_content(); 
        });
            
        //Fullscreen Map
        $('.map-text, .overlay').on( "click", function(){
            $('.map-text, .map-fullscreen .overlay').addClass('hide-map'); 
            $('.deactivate-map').removeClass('hide-map'); 
            return false;
        });   
        $('.deactivate-map').on( "click", function(){
            $('.map-text, .map-fullscreen .overlay').removeClass('hide-map'); 
            $('.deactivate-map').addClass('hide-map'); 
            return false;
        });
        
        //Classic Toggles
        $('.toggle-title').on( "click", function(){
            $(this).parent().find('.toggle-content').slideToggle(250); 
            $(this).find('i').toggleClass('rotate-toggle');
            return false;
        });
        
        //Checklist Item
        $('.checklist-item').on( "click", function(){
           $(this).find('.ion-ios-circle-outline').toggle(250); 
           $(this).find('strong').toggleClass('completed-checklist');
           $(this).find('.ion-checkmark, .ion-android-close, .ion-ios-checkmark-outline, .ion-checkmark-circled, .ion-close-circled, .ion-ios-close-outline').toggle(250); 
        });
        
        if($('.checklist-item').hasClass('checklist-item-complete')){
           $('.checklist-item-complete').find('.ion-ios-circle-outline').toggle(250); 
           $('.checklist-item-complete').find('strong').toggleClass('completed-checklist');
           $('.checklist-item-complete').find('.ion-checkmark, .ion-android-close, .ion-ios-checkmark-outline, .ion-checkmark-circled, .ion-close-circled, .ion-ios-close-outline').toggle(250); 
        }
        
        //Tasklist Item
        $('.tasklist-incomplete').on( "click", function(){
           $(this).removeClass('tasklist-incomplete'); 
           $(this).addClass('tasklist-completed'); 
            return false;
        });    
        $('.tasklist-item').on( "click", function(){
           $(this).toggleClass('tasklist-completed'); 
            return false;
        });
        
        //Interests
        $('.interest-box').on( "click", function(){
            $(this).toggleClass('transparent-background'); 
            $(this).find('.interest-first-icon, .interest-second-icon').toggleClass('hide-interest-icon');
            return false;
        });
        
        //Loading Thumb Layout for News, 10 articles at a time
        $(function(){
            $(".thumb-layout-page a").slice(0, 5).show(); // select the first ten
            $(".load-more-thumbs").click(function(e){ // click event for load more
                e.preventDefault();
                $(".thumb-layout-page a:hidden").slice(0, 5).show(0); // select next 10 hidden divs and show them
                if($(".thumb-layout-page a:hidden").length == 0){ // check if any hidden divs still exist
                    $(this).hide();
                }
            });
        });

        $(function(){
            $(".card-large-layout-page .card-large-layout").slice(0, 2).show(); // select the first ten
            $(".load-more-large-cards").click(function(e){ // click event for load more
                e.preventDefault();
                $(".card-large-layout-page .card-large-layout:hidden").slice(0, 2).show(0); // select next 10 hidden divs and show them
                if($(".card-large-layout-page div:hidden").length == 0){ // check if any hidden divs still exist
                    $(this).hide();
                }
            });
        });    

        $(function(){
            $(".card-small-layout-page .card-small-layout").slice(0, 3).show(); // select the first ten
            $(".load-more-small-cards").click(function(e){ // click event for load more
                e.preventDefault();
                $(".card-small-layout-page .card-small-layout:hidden").slice(0, 3).show(0); // select next 10 hidden divs and show them
                if($(".card-small-layout-page a:hidden").length == 0){ // check if any hidden divs still exist
                    $(this).hide();
                }
            });
        });

        //News Tabs
        $('.activate-tab-1').on( "click", function(){
            $('#tab-2, #tab-3').slideUp(250); $('#tab-1').slideDown(250);
            $('.home-tabs a').removeClass('active-home-tab');
            $('.activate-tab-1').addClass('active-home-tab');
            return false;
        });    
        $('.activate-tab-2').on( "click", function(){
            $('#tab-1, #tab-3').slideUp(250); $('#tab-2').slideDown(250);
            $('.home-tabs a').removeClass('active-home-tab');
            $('.activate-tab-2').addClass('active-home-tab');
            return false;
        });    
        $('.activate-tab-3').on( "click", function(){
            $('#tab-1, #tab-2').slideUp(250); $('#tab-3').slideDown(250);
            $('.home-tabs a').removeClass('active-home-tab');
            $('.activate-tab-3').addClass('active-home-tab');
            return false;
        });  
        
        //Tabs
    	$('ul.tabs li').on( "click", function(){
            var tab_id = $(this).attr('data-tab');
            
            $(this).parent().parent().find('ul.tabs li').removeClass('current');
            $(this).parent().parent().find('.tab-content').removeClass('current');

            $(this).addClass('current');
            $("#"+tab_id).addClass('current');
        })
    }//Init Template Function

    
    setTimeout(init_template, 0);//Activating all the plugins
    $(function(){
      'use strict';
      var options = {
        prefetch: false,
        cacheLength: 0,
        blacklist: '.default-link',
        forms: 'contactForm',
        onStart: {
          duration:500, // Duration of our animation
          render: function ($container) {
            // Add your CSS animation reversing class
            $container.addClass('is-exiting');

            // Restart your animation
            smoothState.restartCSSAnimations();
            $('.page-preloader').addClass('show-preloader');
            $('#page-transitions').css({"opacity":"0", "transition":"all 500ms ease"});
			$('#sidebar-tap-close').removeClass();
			$('.page-overlay').removeClass('active-overlay');
            $('.sidebar').removeClass('sidebar-left-active sidebar-right-active');
            $('.header').removeClass('sidebar-large-left-body-active sidebar-large-right-body-active body-right-effect sidebar-small-right-body-active body-left-effect sidebar-small-left-body-active');
            $('#page-content').removeClass();
            $('.open-sidebar-left, .open-sidebar-right, .open-header-menu, .open-footer-menu').find('em').removeClass('hm1a hm2a hm3a dm1a dm2a ph1a ph2a');	
          }
        },
        onReady: {
          duration: 0,
          render: function ($container, $newContent) {
            // Remove your CSS animation reversing class
            $container.removeClass('is-exiting');

            // Inject the new content
            $container.html($newContent);
            $('html, body').animate({ scrollTop: 0 }, 0);
            $('.page-preloader').addClass('show-preloader');
            $('#page-transitions').css({"opacity":"1", "transition":"all 500ms ease"});
            $('#page-transitions').removeClass('page-fade');

          }
        },

        onAfter: function($container, $newContent) {
            setTimeout(init_template, 0)//Timeout required to properly initiate all JS Functions. 
            $('.page-preloader').removeClass('show-preloader');
            $('#page-content').css({
                "opacity":"1",
                "transition":"all 500ms ease"
            });   
        }
      };
      var smoothState = $('#page-transitions').smoothState(options).data('smoothState');
    });
    
});