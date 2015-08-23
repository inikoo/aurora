$(document).ready(function(){       
        $('a').click(function(event) {
            
            var $iframe = $('#inikoo_content');
    if ( $iframe.length ) {
        $iframe.attr('src',this.href);   
        return false;
    }
            
            
            event.preventDefault();

        }); 
});