function change_currency(currency) {


    request = 'ar_basket.php?tipo=set_currency&currency=' + currency

    YAHOO.util.Connect.asyncRequest('GET', request, {
        success: function(o) {

            var r = YAHOO.lang.JSON.parse(o.responseText);

            if (r.state == 200) {
               location.reload()

            } else {


            }



        },
        failure: function(o) {

        },
        scope: this
    });

}
function hide_currencies_dialog() {

    Dom.setStyle(['currency_dialog','hide_currency_dialog'], 'display', 'none')
  
    Dom.setStyle(['show_currency_dialog'], 'display', '')


}



function show_currencies_dialog() {

region1 = Dom.getRegion('show_currency_dialog');
    Dom.setStyle(['show_currency_dialog'], 'display', 'none')


    Dom.setStyle(['currency_dialog','hide_currency_dialog'], 'display', '')
    
    region2 = Dom.getRegion('currency_dialog');

    var pos = [region1.right - region2.width, region1.bottom]
    Dom.setXY('currency_dialog', pos);


}
