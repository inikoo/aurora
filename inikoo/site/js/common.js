$(document).ready(function(){

	// start Superfish for header menu and customization options
		$('ul.sf-menu').superfish({ 
			delay:       0,                            // Delay on mouseout 
			animation:   {opacity:'show',height:'show',filter:'none'},   // Fade-in and slide-down animation 
			speed:       'fast'                          // Animation speed 
		}); 
	
	
	// start tweet timeline feed in footer and customization options
		$(".tweet").tweet({
			username: "envatowebdev",  // Twitter account user.
			avatar_size: 32,  // Size of avatar. Change to, null, to hide avatar
			count: 1,  //  Number of tweets to display from timeline
			loading_text: "loading tweets..."  //  Text displayed while tweet is loading
		});

});  