function get_offers_elements_numbers() {
    var ar_file = 'ar_deals.php';
    var request = 'tipo=get_offer_elements_numbers&parent=' + Dom.get('subject').value + '&parent_key=' + Dom.get('subject_key').value
 //alert(request)
    //Dom.get(['elements_Error_number','elements_Excess_number','elements_Normal_number','elements_Low_number','elements_VeryLow_number','elements_OutofStock_number']).innerHTML='<img src="art/loading.gif" style="height:12.9px" />';
    YAHOO.util.Connect.asyncRequest('POST', ar_file, {
        success: function(o) {

          //  alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
                for (i in r.elements_numbers) {
              //  alert('offer_elements_'+ i +'_number '+'  '+Dom.get('offer_elements_'+ i +'_number')+'  '+r.elements_numbers[i])
                    Dom.get('offer_elements_' + i + '_number').innerHTML = r.elements_numbers[i]
                }
            }
        },
        failure: function(o) {
            // alert(o.statusText);
        },
        scope: this
    }, request

    );
}


function offers_myrenderEvent() {


    ostate = this.getState();
    paginator = ostate.pagination

    if (paginator.totalRecords <= paginator.rowsPerPage) {
        Dom.setStyle('paginator' + this.table_id, 'display', 'none')
    }
   
    get_offers_elements_numbers()

}

function campaigns_myrenderEvent() {

    ostate = this.getState();
    paginator = ostate.pagination

    if (paginator.totalRecords <= paginator.rowsPerPage) {
        Dom.setStyle('paginator' + this.table_id, 'display', 'none')
    }
   
   // get_offers_elements_numbers()

}