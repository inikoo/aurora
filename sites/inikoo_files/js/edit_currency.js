


function change_currency(currency) {


    request = 'ar_basket.php?tipo=set_currency&currency='+currency
   
    YAHOO.util.Connect.asyncRequest('GET', request, {
        success: function(o) {
           
            var r = YAHOO.lang.JSON.parse(o.responseText);

            if (r.state == 200) {
            
            	
          
            	location.reload()
            
            }
            
            else {


            }



        },
        failure: function(o) {
           
        },
        scope: this
    });

}



function show_currencies_dialog(){





}