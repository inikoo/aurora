var dialog_check_tax_number;

function save_tax_details_match(e, value) {

    Dom.setStyle('check_tax_number_wait', 'display', '');
    Dom.setStyle('check_tax_number_buttons', 'display', 'none');

    Dom.setStyle('check_tax_number_result_tr', 'display', 'none');

    var request = 'ar_edit_contacts.php?tipo=update_tax_number_match&customer_key=' + Dom.get('customer_key').value + '&value=' + value


    YAHOO.util.Connect.asyncRequest('POST', request, {
        success: function(o) {
            Dom.setStyle(['submit_register', 'cancel_register'], 'visibility', 'visible');

            //	alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == '200') {

                if (r.match) {
                    Dom.get('check_tax_number').src = 'art/icons/taxation_green.png';
                } else {
                    Dom.get('check_tax_number').src = 'art/icons/taxation_yellow.png';

                }

                dialog_check_tax_number.hide();
            }

        },
        failure: function(o) {

        }

    });


}



function show_dialog_check_tax_number(tax_number) {

    region1 = Dom.getRegion('check_tax_number');
    region2 = Dom.getRegion('dialog_check_tax_number');
    var pos = [region1.right - region2.width, region1.top]
    Dom.setXY('dialog_check_tax_number', pos);


    Dom.get('tax_number_to_check').innerHTML = tax_number


    Dom.get('check_tax_number_result').innerHTML = '';
    Dom.setStyle('check_tax_number_result_tr', 'display', 'none');
    Dom.setStyle('check_tax_number_buttons', 'display', 'none');
    Dom.setStyle('check_tax_number_wait', 'display', '');


    if (Dom.get('save_tax_details_not_match') != undefined) Dom.setStyle('save_tax_details_not_match', 'display', 'none')
    if (Dom.get('save_tax_details_match') != undefined) Dom.setStyle('save_tax_details_match', 'display', 'none')

    Dom.setStyle('close_check_tax_number', 'display', 'none')
    Dom.setStyle('check_tax_number_name_tr', 'display', 'none')
    Dom.setStyle('check_tax_number_address_tr', 'display', 'none')


    dialog_check_tax_number.show()

    Dom.get('check_tax_number_result').innerHTML = '';

    var request = 'ar_edit_contacts.php?tipo=check_tax_number&customer_key=' + Dom.get('customer_key').value



    YAHOO.util.Connect.asyncRequest('POST', request, {
        success: function(o) {
            Dom.setStyle(['submit_register', 'cancel_register'], 'visibility', 'visible');

            //alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);
            Dom.get('check_tax_number_result').innerHTML = r.msg;
            Dom.setStyle('check_tax_number_result_tr', 'display', '');
            Dom.setStyle('check_tax_number_buttons', 'display', '');
            Dom.setStyle('check_tax_number_wait', 'display', 'none');
            if (r.state == '200') {
                if (Dom.get('customer_tax_number_valid') != undefined) Dom.get('customer_tax_number_valid').innerHTML = r.tax_number_valid
                if (r.result.valid) {
                    Dom.get('check_tax_number').src = 'art/icons/taxation_green.png';


                } else {
                    Dom.get('check_tax_number').src = 'art/icons/taxation_error.png';

                }



                if ((r.result.name != undefined || r.result.address != undefined) && r.result.valid) {

                    if (r.result.name != undefined) {
                        Dom.setStyle('check_tax_number_name_tr', 'display', '')
                        Dom.get('check_tax_number_name').innerHTML = r.result.name

                    }
                    if (r.result.address != undefined) {
                        Dom.setStyle('check_tax_number_address_tr', 'display', '')
                        Dom.get('check_tax_number_address').innerHTML = r.result.address

                    }
                    if (Dom.get('save_tax_details_not_match') != undefined) Dom.setStyle('save_tax_details_not_match', 'display', '')
                    if (Dom.get('save_tax_details_match') != undefined) {
                        Dom.setStyle('save_tax_details_match', 'display', '')
                    } else {
                        Dom.setStyle('close_check_tax_number', 'display', '')
                    }

                } else {

                    Dom.setStyle('close_check_tax_number', 'display', '')
                }



            } else {

                Dom.setStyle('close_check_tax_number', 'display', '')
            }



        },
        failure: function(o) {

        }

    });


}


function close_dialog_check_tax_number() {
    dialog_check_tax_number.hide()
}



function init_edit_tax_number() {

    if (Dom.get("dialog_check_tax_number") != undefined) {
        dialog_check_tax_number = new YAHOO.widget.Dialog("dialog_check_tax_number", {
            visible: false,
            close: true,
            underlay: "none",
            draggable: false
        });
        dialog_check_tax_number.render();
    }




    //   Event.addListener("check_tax_number", "click", show_dialog_check_tax_number);
    Event.addListener(["close_check_tax_number"], "click", close_dialog_check_tax_number);
    if (Dom.get('save_tax_details_not_match') != undefined) Event.addListener(["save_tax_details_not_match"], "click", save_tax_details_match, 'No');
    if (Dom.get('save_tax_details_match') != undefined) Event.addListener(["save_tax_details_match"], "click", save_tax_details_match, 'Yes');




}

YAHOO.util.Event.onDOMReady(init_edit_tax_number);
