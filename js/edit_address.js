var dialog_country_list;


var Address_Changes = 0;
var Address_Items_Changes = 0;
var Address_Type_Changes = 0;
var Address_Function_Changes = 0;

var postcode_help = '';
var postal_regex = new RegExp('.?');

var Address_Keys = ["use_tel", "telephone", "use_contact", "contact", "key", "country", "country_code", "country_d1", "country_d2", "town", "postal_code", "town_d1", "town_d2", "fuzzy", "street", "building", "internal", "description"];
var Address_Meta_Keys = ["type", "function"];







function cancel_edit_address(prefix) {


    // Dom.setStyle([address_identifier+'address_form'], 'display', 'none');
    if (Dom.get(prefix + "reset_address_button").getAttribute('close_if_reset') == 'Yes') {
        Dom.get(prefix + 'address_form').style.display = 'none';
        Dom.get(prefix + "reset_address_button").style.visibility = 'visible';
        Dom.removeClass(prefix + "reset_address_button", 'disabled');


    } else {


        Dom.addClass(prefix + "reset_address_button", 'disabled');
        Dom.addClass(prefix + "save_address_button", 'disabled');


    }


};

function change_main_address(address_key, options) {


    if (Dom.get(options.prefix + 'main_no_img_' + address_key) != undefined) {
        Dom.get(options.prefix + 'main_no_img_' + address_key).src = "art/loading.gif";
    }


    var request = 'ar_edit_contacts.php?tipo=set_main_address&value=' + address_key + '&key=' + options.type + '&subject=' + options.Subject + '&subject_key=' + options.subject_key;
    //alert(request);
    YAHOO.util.Connect.asyncRequest('POST', request, {
        success: function(o) {
            // alert(o.responseText);
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
                if (r.action == 'changed') {

                    if (Dom.get(options.prefix + 'current_address') != undefined) {
                        Dom.get(options.prefix + 'current_address').innerHTML = r.new_main_address;
                    }

                    buttons = Dom.getElementsByClassName(options.prefix + 'main_no', 'img', options.prefix + 'address_showcase');

                    for (var i = buttons.length - 1; i >= 0; --i) {


                        Dom.setStyle(buttons[i], 'display', '');
                    }
                    buttons = Dom.getElementsByClassName(options.prefix + 'main_yes', 'img', options.prefix + 'address_showcase');

                    for (var i = buttons.length - 1; i >= 0; --i) {

                        Dom.setStyle(buttons[i], 'display', 'none');
                    }

                    if (Dom.get(options.prefix + 'address_showcase_bis')) {
                        buttons = Dom.getElementsByClassName(options.prefix + 'main_no', 'img', options.prefix + 'address_showcase_bis');

                        for (var i = buttons.length - 1; i >= 0; --i) {


                            Dom.setStyle(buttons[i], 'display', '');
                        }
                        buttons = Dom.getElementsByClassName(options.prefix + 'main_yes', 'img', options.prefix + 'address_showcase_bis');

                        for (var i = buttons.length - 1; i >= 0; --i) {

                            Dom.setStyle(buttons[i], 'display', 'none');
                        }
                    }

                    if (Dom.get(options.prefix + 'main_yes_img_' + address_key) != undefined) {
                        Dom.setStyle(options.prefix + 'main_yes_img_' + address_key, 'display', '');
                    }
                    if (Dom.get(options.prefix + 'main_no_img_' + address_key) != undefined) {
                        Dom.setStyle(options.prefix + 'main_no_img_' + address_key, 'display', 'none');
                        Dom.get(options.prefix + 'main_no_img_' + address_key).src = "art/icons/star_dim.png";
                    }

                    //Dom.addClass(options.prefix + 'set_main' + address_key, 'hide');
                    if (options.type == 'Delivery' && options.Subject == 'Customer') {
                        // if(Dom.get(options.prefix+'current_address_bis')!=undefined)
                        // Dom.get(options.prefix+'current_address_bis').innerHTML=r.new_main_address_bis;
                        post_change_main_delivery_address();
                    }

                    if (options.type == 'Billing' && options.Subject == 'Customer') {

                        post_change_main_billing_address();
                    }




                }

            } else {
                alert('E1 ' + r.msg);
            }
        }
    });


}


function post_change_main_delivery_address() {}

function post_change_main_billing_address() {}



function create_address(options) {

    var address_prefix = '';
    if (options.prefix != undefined) {
        address_prefix = options.prefix;
    }


    var value = new Object();
    items = Address_Keys;
    count = 0;
    for (i in items) {
        if (items.length <= count++) break;
        //alert(address_prefix+'address_'+items[i]+':'+Dom.get(address_prefix+'address_'+items[i]).value);
        value[items[i]] = Dom.get(address_prefix + 'address_' + items[i]).value;

    }

    var address_type_values = new Array();
    var elements_array = Dom.getElementsByClassName(address_prefix + 'address_type', 'span');
    count = 0;
    for (var i in elements_array) {
        if (elements_array.length <= count++) break;
        var element = elements_array[i];
        var label = element.getAttribute('label');
        if (Dom.hasClass(element, 'selected')) {
            address_type_values.push(label);
        }

    }
    value['type'] = address_type_values;

    var address_function_values = new Array();
    var elements_array = Dom.getElementsByClassName(address_prefix + 'address_function', 'span');
    count = 0
    for (var i in elements_array) {
        if (elements_array.length <= count++) break;
        var element = elements_array[i];
        var label = element.getAttribute('label');
        if (Dom.hasClass(element, 'selected')) {
            address_function_values.push(label);
        }

    }
    value['function'] = address_function_values;



    var json_value = my_encodeURIComponent(YAHOO.lang.JSON.stringify(value));



    var request = 'ar_edit_contacts.php?tipo=new_' + options.type + '_address&value=' + json_value + '&subject=' + options.subject + '&subject_key=' + options.subject_key;
    //alert(request);return;  
    YAHOO.util.Connect.asyncRequest('POST', request, {
        success: function(o) {
          //  alert(o.responseText);
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.action == 'created') {

                count = 0
                var new_address_data = new Object;
                for (i in r.updated_data) {
                    if (r.updated_data.length <= count++) break;
                    var address_item_value = r.updated_data[i];
                    if (address_item_value == null) address_item_value = '';
                    new_address_data[i] = address_item_value;
                }


                cancel_edit_address(address_prefix);




                if (address_prefix == 'delivery_') {

                    var new_address_container = Dom.get(address_prefix + 'address_container0').cloneNode(true);
                    new_address_container.id = address_prefix + 'address_container' + r.address_key;
                    Dom.setStyle(new_address_container, 'display', '');




                    display_element = Dom.getElementsByClassName(address_prefix + 'address_tel_label', 'span', new_address_container);
                    display_element[0].id = address_prefix + 'address_tel_label' + r.address_key;
                    display_element = Dom.getElementsByClassName(address_prefix + 'address_tel', 'span', new_address_container);
                    Dom.setAttribute(display_element[0], 'id', address_prefix + 'address_tel' + r.address_key);





                    display_element = Dom.getElementsByClassName('address_display', 'div', new_address_container);



                    display_element[0].innerHTML = r.xhtml_address;
                    display_element[0].id = address_prefix + 'address_display' + r.address_key;
                    display_element = Dom.getElementsByClassName('address_buttons', 'div', new_address_container);
                    display_element[0].id = address_prefix + 'address_buttons' + r.address_key;


                    var address_buttons_div = display_element[0];


                    display_element = Dom.getElementsByClassName('set_main', 'img', address_buttons_div);



                    display_element[0].id = address_prefix + 'main_yes_img_' + r.address_key;
                    display_element[1].id = address_prefix + 'main_no_img_' + r.address_key;


                    display_element[1].setAttribute('onClick', "change_main_address(" + r.address_key + ",{type:'Delivery',prefix:'" + address_prefix + "',Subject:'" + options.subject + "',subject_key:" + options.subject_key + "})");


                    display_element = Dom.getElementsByClassName('small_button_edit', 'button', address_buttons_div);



                    display_element[0].id = address_prefix + 'use_this' + r.address_key;
                    display_element[1].id = address_prefix + 'delete_address_button' + r.address_key;
                    display_element[2].id = address_prefix + 'edit_address_button' + r.address_key;

                    display_element[0].setAttribute('onCLick', "use_this_delivery_address_in_order(" + r.address_key + ",true)");


                    display_element[1].setAttribute('onCLick', "delete_address(" + r.address_key + ",{type:'Delivery',prefix:'" + address_prefix + "',Subject:'" + options.subject + "',subject_key:" + options.subject_key + "})");


                    button_img_element = Dom.getElementsByClassName('button_img', 'img', display_element[1]);
                    button_img_element[0].id = address_prefix + 'remove_img_' + r.address_key;




                    display_element[2].setAttribute('onCLick', "display_edit_delivery_address(" + r.address_key + ",'" + address_prefix + "')");






                    Dom.get(address_prefix + 'address_showcase').appendChild(new_address_container);



                    if (Dom.get(address_prefix + "reset_address_button").getAttribute('close_if_reset') == 'Yes') {
                        Dom.get(address_prefix + 'address_form').style.display = 'none';
                        Dom.get(address_prefix + "reset_address_button").style.visibility = 'visible';

                    }


                    if (r.updated_data.telephone != '') {
                        Dom.setStyle(address_prefix + 'address_tel_label' + r.address_key, 'visibility', 'visible');
                        Dom.get(address_prefix + 'address_tel' + r.address_key).innerHTML = r.updated_data.telephone;
                    }
                    post_create_delivery_address_function(r);
                } 
                else if (address_prefix == 'billing_') {


                  

                    var new_address_container = Dom.get(address_prefix + 'address_container0').cloneNode(true);
                    new_address_container.id = address_prefix + 'address_container' + r.address_key;
                    Dom.setStyle(new_address_container, 'display', '');




                    display_element = Dom.getElementsByClassName(address_prefix + 'address_tel_label', 'span', new_address_container);
                    display_element[0].id = address_prefix + 'address_tel_label' + r.address_key;
                    display_element = Dom.getElementsByClassName(address_prefix + 'address_tel', 'span', new_address_container);
                    Dom.setAttribute(display_element[0], 'id', address_prefix + 'address_tel' + r.address_key);





                    display_element = Dom.getElementsByClassName('address_display', 'div', new_address_container);



                    display_element[0].innerHTML = r.xhtml_address;
                    display_element[0].id = address_prefix + 'address_display' + r.address_key;
                    display_element = Dom.getElementsByClassName('address_buttons', 'div', new_address_container);
                    display_element[0].id = address_prefix + 'address_buttons' + r.address_key;


                    var address_buttons_div = display_element[0];


                    display_element = Dom.getElementsByClassName('set_main', 'img', address_buttons_div);



                    display_element[0].id = address_prefix + 'main_yes_img_' + r.address_key;
                    display_element[1].id = address_prefix + 'main_no_img_' + r.address_key;


                    display_element[1].setAttribute('onClick', "change_main_address(" + r.address_key + ",{type:'Billing',prefix:'" + address_prefix + "',Subject:'" + options.subject + "',subject_key:" + options.subject_key + "})");


                    display_element = Dom.getElementsByClassName('small_button_edit', 'button', address_buttons_div);



                    display_element[0].id = address_prefix + 'use_this' + r.address_key;
                    display_element[1].id = address_prefix + 'delete_address_button' + r.address_key;
                    display_element[2].id = address_prefix + 'edit_address_button' + r.address_key;

                    display_element[0].setAttribute('onCLick', "use_this_billing_address_in_order(" + r.address_key + ",true)");


                    display_element[1].setAttribute('onCLick', "delete_address(" + r.address_key + ",{type:'Billing',prefix:'" + address_prefix + "',Subject:'" + options.subject + "',subject_key:" + options.subject_key + "})");


                    button_img_element = Dom.getElementsByClassName('button_img', 'img', display_element[1]);
                    button_img_element[0].id = address_prefix + 'remove_img_' + r.address_key;




                    display_element[2].setAttribute('onCLick', "display_edit_billing_address(" + r.address_key + ",'" + address_prefix + "')");






                    Dom.get(address_prefix + 'address_showcase').appendChild(new_address_container);



                    if (Dom.get(address_prefix + "reset_address_button").getAttribute('close_if_reset') == 'Yes') {
                        Dom.get(address_prefix + 'address_form').style.display = 'none';
                        Dom.get(address_prefix + "reset_address_button").style.visibility = 'visible';

                    }


                    if (r.updated_data.telephone != '') {
                        Dom.setStyle(address_prefix + 'address_tel_label' + r.address_key, 'visibility', 'visible');
                        Dom.get(address_prefix + 'address_tel' + r.address_key).innerHTML = r.updated_data.telephone;
                    }
                    post_create_billing_address_function(r);
                


                }
                
     






            } else if (r.action == 'nochange') {
                if (address_prefix == 'delivery_') {
                    post_create_delivery_address_function(r);
                 
                }
   if (address_prefix == 'billing_') {
                    post_create_billing_address_function(r);
                 
                }

            } else if (r.action == 'error') {
                alert('E3 ' + r.msg);
            }



        }
    });

}


function save_address(e, options) {


    var address_prefix = '';
    if (options.prefix != undefined) {
        address_prefix = options.prefix;
    }



    var table = 'address';
    //alert(Dom.get(address_prefix+'address_key').value)
    if (Dom.get(address_prefix + 'address_key').value == 0) {
        create_address(options);


        return;
    } else {
        var address_key = Dom.get(address_prefix + 'address_key').value;
    }
    save_address_elements = 0;



    if (Address_Items_Changes > 0) {
        items = Address_Keys;
        var value = new Object()
        count = 0
        for (i in items) {
            if (items.length <= count++) break;
            value[items[i]] = Dom.get(address_prefix + 'address_' + items[i]).value;
        }


        var json_value = my_encodeURIComponent(YAHOO.lang.JSON.stringify(value));


        var request = 'ar_edit_contacts.php?tipo=edit_address&value=' + json_value + '&id=' + address_key + '&key=' + options.type + '&subject=' + options.subject + '&subject_key=' + options.subject_key;


        cancel_edit_address(address_prefix);
        //  alert(address_prefix)
        if (address_prefix == 'delivery_') {
            hide_new_delivery_address();
        }
        if (address_prefix == 'billing_') {

            hide_new_billing_address();
        }
        //alert(request);

        YAHOO.util.Connect.asyncRequest('POST', request, {
            success: function(o) {
                // alert(o.responseText)
                var r = YAHOO.lang.JSON.parse(o.responseText);
                if (r.state == 200) {
                    if (r.action == 'updated') {
                        branch = 'address';
                        post_item_updated_actions(branch, r)

                        if (Dom.get(address_prefix + 'address_display' + r.key) != undefined) {
                       
                            Dom.get(address_prefix + 'address_display' + r.key).innerHTML = r.xhtml_address;
                        }

                        if (r.is_main == 'Yes') {
                            if (Dom.get(address_prefix + 'current_address') != undefined) {
                           
                            Dom.get(address_prefix + 'current_address').innerHTML = r.xhtml_address;
                            }
                        }


                        if (r.is_main_delivery == 'Yes') {
                            if (Dom.get('delivery_current_address') != undefined) {
                                Dom.get('delivery_current_address').innerHTML = r.xhtml_address;
                            }
                            post_change_main_delivery_address();
                        }


                        if (r.is_main_billing == 'Yes') {
                            if (Dom.get('billing_current_address') != undefined) {
                                Dom.get('billing_current_address').innerHTML = r.xhtml_address;
                            }
                            post_change_main_billing_address();
                        }



                        if (Dom.get('delivery_current_address_bis') != undefined) {
                            Dom.get('delivery_current_address_bis').innerHTML = r.xhtml_delivery_address_bis;
                        }


                        if (Dom.get('billing_current_address') != undefined) {
                            Dom.get('billing_current_address').innerHTML = r.xhtml_billing_address;
                        }





                        if (address_prefix == 'delivery_') {
                            if (Dom.get('delivery_address_display' + r.key) != undefined) {
                                Dom.get('delivery_address_tel' + r.key).innerHTML = r.updated_data.telephone;
                                if (r.updated_data.telephone == '') {
                                    Dom.setStyle('delivery_address_tel_label' + r.key, 'visibility', 'hidden');
                                } else {
                                    Dom.setStyle('delivery_address_tel_label' + r.key, 'visibility', 'visible');
                                }
                                Dom.get('delivery_address_display' + r.key).innerHTML = r.xhtml_address;
                            }


                            if (r.deleted_address > 0 && Dom.get('delivery_address_container' + r.key) != undefined) {
                                Dom.get('delivery_address_container' + r.key).style.display = 'none';
                                Dom.get('delivery_address_container' + r.key).parentNode.removeChild(Dom.get('delivery_address_container' + r.key));
                            }



                            if (r.created_address > 0 && Dom.get('delivery_address_container0') != undefined) {


                                var new_address_data = new Object;
                                for (i in r.updated_data) {
                                    var address_item_value = r.updated_data[i];
                                    if (address_item_value == null) address_item_value = '';
                                    new_address_data[i] = address_item_value;
                                }


                                cancel_edit_address(address_prefix);

                                var new_address_container = Dom.get('delivery_address_container0').cloneNode(true);
                                new_address_container.id = 'delivery_address_container' + r.key;
                                Dom.setStyle(new_address_container, 'display', '');
                                display_element = Dom.getElementsByClassName('address_display', 'div', new_address_container);
                                display_element[0].innerHTML = r.xhtml_address;
                                display_element[0].id = 'delivery_address_display' + r.key;
                                display_element = Dom.getElementsByClassName('address_buttons', 'div', new_address_container);
                                display_element[0].id = 'delivery_address_buttons' + r.key;

                                display_element2 = Dom.getElementsByClassName('small_button_edit', 'button', display_element[0]);

                                display_element2[0].id = 'delivery_set_main' + r.key;
                                display_element2[1].id = 'delivery_delete_address_button' + r.key;
                                display_element2[2].id = 'delivery_edit_address_button' + r.key;

                                display_element2[0].setAttribute('onClick', "change_main_address(" + r.key + ",{type:'Delivery',prefix:'" + address_prefix + "',Subject:'" + options.subject + "',subject_key:" + options.subject_key + "})");
                                display_element2[1].setAttribute('onCLick', "delete_address(" + r.key + ",{type:'Delivery',prefix:'" + address_prefix + "',Subject:'" + options.subject + "',subject_key:" + options.subject_key + "})");
                                display_element2[2].setAttribute('onCLick', "edit_address(" + r.key + ",'" + address_prefix + "')");
                                display_element2[1].style.display = 'none';
                                display_element2[2].style.display = 'none';

                                billing = document.createElement('span');



                                Dom.get('delivery_address_showcase').appendChild(new_address_container);
                                billing.innerHTML = '<img src="art/icons/lock.png" alt="lock"> <span  class="state_details" > Billing</span>'
                                display_element[0].appendChild(billing);




                            }
                        }


                        if (address_prefix == 'billing_') {



                            if (Dom.get('billing_address_display' + r.key) != undefined) {
                                Dom.get('billing_address_tel' + r.key).innerHTML = r.updated_data.telephone;
                                if (r.updated_data.telephone == '') {
                                    Dom.setStyle('billing_address_tel_label' + r.key, 'visibility', 'hidden');
                                } else {
                                    Dom.setStyle('billing_address_tel_label' + r.key, 'visibility', 'visible');
                                }
                                Dom.get('billing_address_display' + r.key).innerHTML = r.xhtml_address;

                                if (Dom.get('delivery_address_display' + r.key) != undefined) {
                                    Dom.get('delivery_address_display' + r.key).innerHTML = r.xhtml_address;
                                }


                            }


                            if (r.deleted_address > 0 && Dom.get('billing_address_container' + r.key) != undefined) {
                                Dom.get('billing_address_container' + r.key).style.display = 'none';
                                Dom.get('billing_address_container' + r.key).parentNode.removeChild(Dom.get('billing_address_container' + r.key));
                            }



                            if (r.created_address > 0 && Dom.get('billing_address_container0') != undefined) {


                                var new_address_data = new Object;
                                for (i in r.updated_data) {
                                    var address_item_value = r.updated_data[i];
                                    if (address_item_value == null) address_item_value = '';
                                    new_address_data[i] = address_item_value;
                                }


                                cancel_edit_address(address_prefix);

                                var new_address_container = Dom.get('billing_address_container0').cloneNode(true);
                                new_address_container.id = 'billing_address_container' + r.key;
                                Dom.setStyle(new_address_container, 'display', '');
                                display_element = Dom.getElementsByClassName('address_display', 'div', new_address_container);
                                display_element[0].innerHTML = r.xhtml_address;
                                display_element[0].id = 'billing_address_display' + r.key;
                                display_element = Dom.getElementsByClassName('address_buttons', 'div', new_address_container);
                                display_element[0].id = 'billing_address_buttons' + r.key;

                                display_element2 = Dom.getElementsByClassName('small_button_edit', 'button', display_element[0]);

                                display_element2[0].id = 'billing_set_main' + r.key;
                                display_element2[1].id = 'billing_delete_address_button' + r.key;
                                display_element2[2].id = 'billing_edit_address_button' + r.key;

                                display_element2[0].setAttribute('onClick', "change_main_address(" + r.key + ",{type:'Delivery',prefix:'" + address_prefix + "',Subject:'" + options.subject + "',subject_key:" + options.subject_key + "})");
                                display_element2[1].setAttribute('onCLick', "delete_address(" + r.key + ",{type:'Delivery',prefix:'" + address_prefix + "',Subject:'" + options.subject + "',subject_key:" + options.subject_key + "})");
                                display_element2[2].setAttribute('onCLick', "edit_address(" + r.key + ",'" + address_prefix + "')");
                                display_element2[1].style.display = 'none';
                                display_element2[2].style.display = 'none';

                                billing = document.createElement('span');



                                Dom.get('billing_address_showcase').appendChild(new_address_container);
                                billing.innerHTML = '<img src="art/icons/lock.png" alt="lock"> <span  class="state_details" > Billing</span>'
                                display_element[0].appendChild(billing);




                            }



                        }



                        for (i in r.updated_data) {

                            var address_item_value = r.updated_data[i];
                            if (address_item_value == null) address_item_value = '';

                        }



                        edit_address(r.key, address_prefix)

                        save_address_elements++;



                    }

                    post_edit_address();
                } else {
                    //  alert('E4 '+r.msg);
                }
            }
        });
    }



    if (Address_Type_Changes > 0) {

        var address_type_values = new Array();
        var elements_array = Dom.getElementsByClassName('address_type', 'span');
        for (var i in elements_array) {
            var element = elements_array[i];
            var label = element.getAttribute('label');
            if (Dom.hasClass(element, 'selected')) {
                address_type_values.push(label);
            }

        }

        var json_value = YAHOO.lang.JSON.stringify(address_type_values);
        var request = 'ar_edit_contacts.php?tipo=edit_' + escape(options.tipo) + '_type&value=' + json_value + '&id=' + address_key + '&subject=' + Subject + '&subject_key=' + Subject_Key;

        //     alert(request);
        //return;
        YAHOO.util.Connect.asyncRequest('POST', request, {
            success: function(o) {
                //alert(o.responseText);
                var r = YAHOO.lang.JSON.parse(o.responseText);
                if (r.action == 'updated') {



                    cancel_edit_address(address_prefix);
                    save_address_elements++;
                } else if (r.action == 'error') {
                    alert('E5 ' + r.msg);
                }



            }
        });


    }





};


function post_edit_billing_address() {}

function post_edit_address() {





}



function post_create_address_function(r) {

}


var update_address_buttons = function() {
        if (changes_address > 0) {
            Dom.setStyle(['save_edit_address'], 'display', '');
        }

    };

function reset_address(e, address_prefix) {






    address_key = Dom.get(address_prefix + "reset_address_button").getAttribute('address_key');






    var request = 'ar_contacts.php?tipo=get_address_data&address_key=' + address_key + '&subject=' + Dom.get('subject').value + '&subject_key=' + Dom.get('subject_key').value;
    YAHOO.util.Connect.asyncRequest('POST', request, {
        success: function(o) {
            //alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
                var data = r.address_data
                for (key in data) {


                    var country_2acode = 'xx';


                    item_ = Dom.get(address_prefix + 'address_' + key);

                    item_.value = data[key];
                    item_.setAttribute('ovalue', data[key]);

                    if (data[key] != '') {
                        if (key == 'country_d2') {
                            Dom.setStyle(address_prefix + 'tr_address_country_d1', 'display', '')
                            Dom.setStyle(address_prefix + 'tr_address_country_d2', 'display', '')

                        } else if (key == 'country_d3') {
                            Dom.setStyle(address_prefix + 'tr_address_country_d1', 'display', '')
                            Dom.setStyle(address_prefix + 'tr_address_country_d2', 'display', '')
                            Dom.setStyle(address_prefix + 'tr_address_country_d3', 'display', '')

                        } else if (key == 'country_d4') {
                            Dom.setStyle(address_prefix + 'tr_address_country_d1', 'display', '')
                            Dom.setStyle(address_prefix + 'tr_address_country_d2', 'display', '')
                            Dom.setStyle(address_prefix + 'tr_address_country_d3', 'display', '')
                            Dom.setStyle(address_prefix + 'tr_address_country_d4', 'display', '')

                        } else if (key == 'country_d5') {
                            Dom.setStyle(address_prefix + 'show_country_subregions', 'display', 'none')
                            Dom.setStyle(address_prefix + 'tr_address_country_d1', 'display', '')
                            Dom.setStyle(address_prefix + 'tr_address_country_d2', 'display', '')
                            Dom.setStyle(address_prefix + 'tr_address_country_d3', 'display', '')
                            Dom.setStyle(address_prefix + 'tr_address_country_d4', 'display', '')
                            Dom.setStyle(address_prefix + 'tr_address_country_d5', 'display', '')

                        } else if (key == 'town_d2') {
                            Dom.setStyle(address_prefix + 'show_town_subdivisions', 'display', 'none')
                            Dom.setStyle(address_prefix + 'tr_address_town_d1', 'display', '')
                            Dom.setStyle(address_prefix + 'tr_address_town_d2', 'display', '')
                        } else if (key == 'telephone') {
                            if (data['use_tel']) Dom.setStyle(address_prefix + 'tr_address_' + key, 'display', '')

                        } else if (Dom.get(address_prefix + 'tr_address_' + key) != undefined) {
                            Dom.setStyle(address_prefix + 'tr_address_' + key, 'display', '')
                        }
                    }


                    if (key == 'country_2acode') {
                        country_2acode = data[key]
                        Dom.get(address_prefix + '_address_country_select').value = data[key]
                    }

                    if (key == 'country_code') {
                        update_address_labels(data[key], address_prefix);
                    }



                    if (key == 'function') {
                        var address_function = data[key];
                        for (address_function_key in address_function) {
                            Dom.addClass(address_prefix + 'address_function_' + address_function[address_function_key], 'selected')
                        }
                    }
                    if (key == 'type') {
                        var address_type = data[key];
                        for (address_type_key in address_type) {
                            Dom.addClass(address_prefix + 'address_type_' + address_type[address_type_key], 'selected')
                        }
                    }
                }



                changes_address = 0;
                Dom.setStyle(['address_showcase', 'move_address_button', 'add_address_button'], 'display', '');
                Dom.setStyle(['address_form', 'cancel_edit_address', 'save_address_button'], 'display', 'none');





                Address_Type_Changes = 0;
                Address_Items_Changes = 0;
                Address_Function_Changes = 0;
                render_after_address_item_change(address_prefix);


                if (Dom.get(address_prefix + "reset_address_button").getAttribute('close_if_reset') == 'Yes') {
                    Dom.get(address_prefix + 'address_form').style.display = 'none';
                    Dom.removeClass(address_prefix + "reset_address_button", "disabled")

                }


                //  set_country(address_prefix,country_2acode)
            } else {

            }
        }
    });



};

function delete_address(address_key, options) {
    var request = 'ar_edit_contacts.php?tipo=delete_address&address_key=' + address_key + '&type=' + options.type + '&subject=' + options.Subject + '&subject_key=' + options.subject_key;
//alert(request)
    if (Dom.get(options.prefix + 'remove_img_' + address_key) != undefined) Dom.get(options.prefix + 'remove_img_' + address_key).src = "art/loading.gif"

    YAHOO.util.Connect.asyncRequest('POST', request, {
        success: function(o) {
           // alert(o.responseText);
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.action == 'deleted') {



                Dom.setStyle(options.prefix + 'address_container' + address_key, 'display', 'none');

/*
                if(options.prefix=='billing_'){
                    if( Dom.get('delivery_address_container'+address_key)!=undefined){
                         Dom.setStyle('delivery_address_container'+address_key,'display','none')
                    }
                
                }
                */

                if (Dom.get('delivery_current_address') != undefined) Dom.get('delivery_current_address').innerHTML = r.xhtml_delivery_address;

                if (Dom.get('billing_current_address') != undefined) Dom.get('billing_current_address').innerHTML = r.xhtml_billing_address;


                buttons = Dom.getElementsByClassName(options.prefix + 'main_no', 'img', options.prefix + 'address_showcase');

                for (var i = buttons.length - 1; i >= 0; --i) {


                    Dom.setStyle(buttons[i], 'display', '');
                }
                buttons = Dom.getElementsByClassName(options.prefix + 'main_yes', 'img', options.prefix + 'address_showcase');

                for (var i = buttons.length - 1; i >= 0; --i) {

                    Dom.setStyle(buttons[i], 'display', 'none');
                }

                if (Dom.get(options.prefix + 'address_showcase_bis')) {
                    buttons = Dom.getElementsByClassName(options.prefix + 'main_no', 'img', options.prefix + 'address_showcase_bis');

                    for (var i = buttons.length - 1; i >= 0; --i) {


                        Dom.setStyle(buttons[i], 'display', '');
                    }
                    buttons = Dom.getElementsByClassName(options.prefix + 'main_yes', 'img', options.prefix + 'address_showcase_bis');

                    for (var i = buttons.length - 1; i >= 0; --i) {

                        Dom.setStyle(buttons[i], 'display', 'none');
                    }
                }



                if (Dom.get(options.prefix + 'main_yes_img_' + r.address_main_delivery_key) != undefined) {
                    Dom.setStyle(options.prefix + 'main_yes_img_' + r.address_main_delivery_key, 'display', '');
                }
                if (Dom.get(options.prefix + 'main_no_img_' + r.address_main_delivery_key) != undefined) {
                    Dom.setStyle(options.prefix + 'main_no_img_' + r.address_main_delivery_key, 'display', 'none');
                }

                // alert(r.address_main_billing_key)
                if (Dom.get(options.prefix + 'main_yes_img_' + r.address_main_billing_key) != undefined) {
                    Dom.setStyle(options.prefix + 'main_yes_img_' + r.address_main_billing_key, 'display', '');
                }
                if (Dom.get(options.prefix + 'main_no_img_' + r.address_main_billing_key) != undefined) {
                    Dom.setStyle(options.prefix + 'main_no_img_' + r.address_main_billing_key, 'display', 'none');
                }


                if (Dom.get('order_key') != undefined) {

                    use_this_delivery_address_in_order(r.address_main_delivery_key, false)
                }

            } else if (r.action == 'error') {
                alert('E6 ' + r.msg);
            }
        }
    });



};


function edit_address(address_key, address_prefix) {


    if (address_key == false) address_key = 0;

    if (address_prefix == undefined) address_prefix = '';

    Current_Address_Index = address_key;
    changes_address = 0;

    if (address_prefix == '') {
        Dom.setStyle(['address_showcase', 'move_address_button', 'add_address_button'], 'display', 'none');
    }

    //      -->  Dom.get(address_prefix+'address_key').value=



    Dom.setStyle([address_prefix + 'address_form'], 'display', '');
    Dom.setStyle([address_prefix + 'address_components'], 'display', '');

    Dom.get(address_prefix + "reset_address_button").setAttribute('address_key', address_key);






    var request = 'ar_contacts.php?tipo=get_address_data&address_key=' + address_key + '&subject=' + Dom.get('subject').value + '&subject_key=' + Dom.get('subject_key').value;
   //alert(request)
   YAHOO.util.Connect.asyncRequest('POST', request, {
        success: function(o) {
            //alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
                var data = r.address_data
                for (key in data) {


                    var country_2acode = 'xx';


                    item_ = Dom.get(address_prefix + 'address_' + key);

                    item_.value = data[key];
                    item_.setAttribute('ovalue', data[key]);

                    if (data[key] != '') {
                        if (key == 'country_d2') {
                            Dom.setStyle(address_prefix + 'tr_address_country_d1', 'display', '')
                            Dom.setStyle(address_prefix + 'tr_address_country_d2', 'display', '')

                        } else if (key == 'country_d3') {
                            Dom.setStyle(address_prefix + 'tr_address_country_d1', 'display', '')
                            Dom.setStyle(address_prefix + 'tr_address_country_d2', 'display', '')
                            Dom.setStyle(address_prefix + 'tr_address_country_d3', 'display', '')

                        } else if (key == 'country_d4') {
                            Dom.setStyle(address_prefix + 'tr_address_country_d1', 'display', '')
                            Dom.setStyle(address_prefix + 'tr_address_country_d2', 'display', '')
                            Dom.setStyle(address_prefix + 'tr_address_country_d3', 'display', '')
                            Dom.setStyle(address_prefix + 'tr_address_country_d4', 'display', '')

                        } else if (key == 'country_d5') {
                            Dom.setStyle(address_prefix + 'show_country_subregions', 'display', 'none')
                            Dom.setStyle(address_prefix + 'tr_address_country_d1', 'display', '')
                            Dom.setStyle(address_prefix + 'tr_address_country_d2', 'display', '')
                            Dom.setStyle(address_prefix + 'tr_address_country_d3', 'display', '')
                            Dom.setStyle(address_prefix + 'tr_address_country_d4', 'display', '')
                            Dom.setStyle(address_prefix + 'tr_address_country_d5', 'display', '')

                        } else if (key == 'town_d2') {
                            Dom.setStyle(address_prefix + 'show_town_subdivisions', 'display', 'none')
                            Dom.setStyle(address_prefix + 'tr_address_town_d1', 'display', '')
                            Dom.setStyle(address_prefix + 'tr_address_town_d2', 'display', '')
                        } else if (key == 'telephone') {
                            if (data['use_tel']) Dom.setStyle(address_prefix + 'tr_address_' + key, 'display', '')

                        } else if (Dom.get(address_prefix + 'tr_address_' + key) != undefined) {
                            Dom.setStyle(address_prefix + 'tr_address_' + key, 'display', '')
                        }
                    }


                    if (key == 'country_2acode') {
                        country_2acode = data[key]
                        Dom.get(address_prefix + '_address_country_select').value = data[key]
                    }

                    if (key == 'country_code') {
                        update_address_labels(data[key], address_prefix);
                    }



                    if (key == 'function') {
                        var address_function = data[key];
                        for (address_function_key in address_function) {
                            Dom.addClass(address_prefix + 'address_function_' + address_function[address_function_key], 'selected')
                        }
                    }
                    if (key == 'type') {
                        var address_type = data[key];
                        for (address_type_key in address_type) {
                            Dom.addClass(address_prefix + 'address_type_' + address_type[address_type_key], 'selected')
                        }
                    }
                }


                //  set_country(address_prefix,country_2acode)
            } else {

            }
        }
    });






};

var update_address_labels = function(country_code, prefix) {
        var labels = new Object();

        if (Country_Address_Labels[country_code] == undefined) {
            return
        } else {
            labels = Country_Address_Labels[country_code];
        }
        if (prefix == undefined) prefix = '';
        for (index in Address_Keys) {
            key = Address_Keys[index];
            //	alert(Dom.get(prefix+'label_address_'+key)+' '+prefix+'label_address_'+key)
            if (labels[key] != undefined) {
                if (labels[key].name != undefined) {
                    //alert(Dom.get(prefix+'label_address_'+key)+' '+prefix+'label_address_'+key)
                    Dom.get(prefix + 'label_address_' + key).innerHTML = labels[key].name;
                }

                if (labels[key].in_use != undefined && !labels[key].in_use) {
                    //	alert(Dom.get(prefix+'tr_address_'+key)+' '+prefix+'tr_address_'+key)
                    Dom.setStyle(prefix + 'tr_address_' + key, 'display', 'none');
                } else {
                    Dom.setStyle(prefix + 'tr_address_' + key, 'display', '');


                    if (labels[key].hide != undefined && labels[key].hide) {
                        Dom.setStyle(prefix + 'tr_address_' + key, 'display', 'none');

                        if (key == 'country_d1') {
                            Dom.setStyle(prefix + 'show_' + key, 'display', '');
                        }

                    } else {
                        Dom.setStyle(prefix + 'tr_address_' + key, 'display', '');
                        if (key == 'country_d1') {
                            Dom.setStyle(prefix + 'show_' + key, 'display', 'none');
                        }

                    }
                }

            }
        }
    };

var on_address_type_change = function() {

        Address_Type_Changes = 0
        var address_type_values = new Array();
        var elements_array = Dom.getElementsByClassName('address_type', 'span');
        var has_other = false;
        for (var i in elements_array) {
            var element = elements_array[i];
            var label = element.getAttribute('label');
            if (Dom.hasClass(element, 'selected')) {
                if (label == 'Other') has_other = true;
                address_type_values.push(label);
            }

        }
        if (address_type_values.length == 0) {
            address_type_values.push('Other');
            Dom.addClass('address_type_Other', 'selected');
        }
        if (has_other && address_type_values.length > 1) {
            address_type_values.splice(address_type_values.indexOf('Other'), 1);
            Dom.removeClass('address_type_Other', 'selected');
        }

        // ovalue=Address_Data[Current_Address_Index]['type']
        //        if (!same_arrays(ovalue,address_type_values))
        //            Address_Type_Changes++;

        render_after_address_item_change();
    }

var on_address_function_change = function() {

        Address_Function_Changes = 0
        var address_function_values = new Array();
        var elements_array = Dom.getElementsByClassName('address_function', 'span');
        var has_other = false;
        for (var i in elements_array) {
            var element = elements_array[i];
            var label = element.getAttribute('label');
            if (Dom.hasClass(element, 'selected')) {
                if (label == 'Other') has_other = true;
                address_function_values.push(label);
            }

        }
        if (address_function_values.length == 0) {
            address_function_values.push('Other');
            Dom.addClass('address_function_Other', 'selected');
        }
        if (has_other && address_function_values.length > 1) {
            address_function_values.splice(address_function_values.indexOf('Other'), 1);
            Dom.removeClass('address_function_Other', 'selected');
        }

        //  ovalue=Address_Data[Current_Address_Index]['function']
        //         if (!same_arrays(ovalue,address_function_values))
        //            Address_Function_Changes++;
        render_after_address_item_change();
    }

var on_address_item_change_when_creating = function() {



    }


var on_address_item_change = function(o, prefix) {

        //alert('on change');
        var address_prefix = '';
        if (prefix != undefined) {
            address_prefix = prefix;
        }

        Address_Items_Changes = 0;
        var items = Address_Keys;
        //alert(YAHOO.lang.JSON.stringify(items));
        count = 0
        for (var i in items) {
            if (items.length <= count++) break;
            //count++
            key_ = items[i];
            //alert(i + ':' + key);
            //  alert(address_prefix+'address_'+key);
            // alert(i+' -> '+key +' : '+address_prefix+'address_'+key)
            // alert(key +' '+Dom.get(address_prefix+'address_'+key).value);
            //alert(Dom.get(address_prefix+'address_'+key_).value);
            //alert(Dom.get(address_prefix+'address_'+key_).getAttribute('ovalue'));
            //alert(key_)
            //alert(key_+' '+Dom.get(address_prefix+'address_'+key_))
            if (Dom.get(address_prefix + 'address_' + key_).value != Dom.get(address_prefix + 'address_' + key_).getAttribute('ovalue')) {
                Address_Items_Changes++;
            }

        }
        //alert(Address_Items_Changes)
        render_after_address_item_change(address_prefix);


    }


var render_after_address_item_change = function(prefix) {
        //alert('render');
        var address_prefix = '';
        if (prefix != undefined) {
            address_prefix = prefix;
        }

        Address_Changes = Address_Items_Changes + Address_Function_Changes + Address_Type_Changes;
        if (Address_Changes == 0) {

            Dom.addClass([address_prefix + 'save_address_button', address_prefix + 'reset_address_button'], 'disabled');


        } else {

            Dom.removeClass([address_prefix + 'save_address_button', address_prefix + 'reset_address_button'], 'disabled');

        }

        //alert('render_end')
    }


var toggle_address_type = function(o) {
        if (Dom.hasClass(o, 'selected')) {
            Dom.removeClass(o, 'selected')
        } else {
            Dom.addClass(o, 'selected')
        }
        on_address_type_change();
    };

var toggle_address_function = function(o) {
        if (Dom.hasClass(o, 'selected')) {
            Dom.removeClass(o, 'selected')
        } else {
            Dom.addClass(o, 'selected')
        }
        on_address_function_change();
    };
var show_description = function() {
        Dom.setStyle(['tr_address_description', 'hide_description'], 'display', '');
        Dom.setStyle('show_description', 'display', 'none');
    };

var hide_description = function() {
        Dom.setStyle(['tr_address_description', 'hide_description'], 'display', 'none');
        Dom.setStyle('show_description', 'display', '');
    };




function show_town_subdivisions(prefix) {
    Dom.setStyle(prefix + 'tr_address_town_d1', 'display', '');
    Dom.setStyle(prefix + 'tr_address_town_d2', 'display', '');
    Dom.setStyle(prefix + 'show_town_subdivisions', 'display', 'none');
}

function show_country_subregions(prefix) {
    Dom.setStyle(prefix + 'tr_address_country_d1', 'display', '');
    Dom.setStyle(prefix + 'tr_address_country_d2', 'display', '');
    Dom.setStyle(prefix + 'tr_address_country_d3', 'display', '');
    Dom.setStyle(prefix + 'tr_address_country_d4', 'display', '');
    Dom.setStyle(prefix + 'tr_address_country_d5', 'display', '');

    Dom.setStyle(prefix + 'show_country_subregions', 'display', 'none');
}

var match_country = function(sQuery) {
        // Case insensitive matching
        var query = sQuery.toLowerCase(),
            contact, i = 0,
            l = Country_List.length,
            matches = [];

        // Match against each name of each contact
        for (; i < l; i++) {
            contact = Country_List[i];
            if ((contact.name.toLowerCase().indexOf(query) > -1) || (contact.code.toLowerCase().indexOf(query) > -1)) {
                matches[matches.length] = contact;
            }
        }

        return matches;
    };

var onCountrySelected = function(sType, aArgs) {
        var myAC = aArgs[0]; // reference back to the AC instance
        var elLI = aArgs[1]; // reference to the selected LI element
        var oData = aArgs[2]; // object literal of selected item's result data

        if (this.prefix == undefined) this.prefix = '';

        myAC.getInputEl().value = oData.name + " (" + oData.code + ") ";
        //    alert("xx"+myAC.getInputEl().id)
        change_country(this.prefix, oData)



    };







function set_country(prefix, code) {

    var request = 'ar_regions.php?tipo=country_info_from_2alpha&2alpha=' + code + '&prefix=' + prefix;
    YAHOO.util.Connect.asyncRequest('POST', request, {
        success: function(o) {

            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
                //  Dom.get(r.prefix+'address_country').value=r.data['Country Name'] + "x (" + r.data['Country Code'] + ") ";
                change_country(r.prefix, {
                    'code': r.data['Country Code'],
                    'name': r.data['Country Name'],
                    'code2a': r.data['Country 2 Alpha Code'],
                    'postal_regex': r.data['Country Postal Code Regex'],
                    'postcode_help': r.data['Country Postal Code Format']

                });



            } else {

            }
        }
    });

}

function select_country_from_list(oArgs) {
    record = tables.table100.getRecord(oArgs.target)
    var data = {
        'code': record.getData('code3a'),
        'code2a': record.getData('code2a'),
        'name': record.getData('plain_name'),
        'postal_regex': record.getData('postal_regex'),
        'postcode_help': record.getData('postcode_help')

    }
    Dom.get(tables.table100.prefix + 'address_country').value = record.getData('plain_name') + " (" + record.getData('code3a') + ") ";

    change_country(tables.table100.prefix, data);
    dialog_country_list.hide();
    hide_filter(true, 2)
}


function change_country(prefix, oData) {

    // alert(prefix)


    Dom.setStyle(prefix + 'address_components', 'display', '')
    Dom.setStyle(prefix + 'default_country_selector', 'display', 'none');
    Dom.setStyle(prefix + 'show_country_subregions', 'display', '')
    Dom.get(prefix + "address_country_code").value = oData.code;
    Dom.get(prefix + "address_country_2acode").value = oData.code;
    Dom.get(prefix + "address_country").value = oData.name;





    postal_regex = new RegExp(oData.postal_regex, "i");
    postcode_help = oData.postcode_help;
    update_address_labels(oData.code, prefix);
    //alert(Dom.get(prefix+'address_street'))

    on_address_item_change(false, prefix)


    Dom.get(prefix + '_address_country_select').value = oData.code2a;


    //  Dom.get(prefix + 'address_street').focus()
    //  Dom.get(prefix + "address_country").value = oData.name + " (" + oData.code + ") ";
}


function show_country_options(prefix) {
    Dom.setStyle(prefix + 'country_options', 'display', '')
    Dom.setStyle(prefix + 'show_country_options', 'display', 'none')
}

function countries_format_results(oResultData, sQuery, sResultMatch) {

    var query = sQuery.toLowerCase(),
        name = oResultData.name,
        code = oResultData.code,
        query = sQuery.toLowerCase(),
        nameMatchIndex = name.toLowerCase().indexOf(query),
        codeMatchIndex = code.toLowerCase().indexOf(query),
        displayname, displaycode;


    if (nameMatchIndex > -1) {
        displayname = countries_highlightMatch(name, query, nameMatchIndex);
    } else {
        displayname = name;
    }

    if (codeMatchIndex > -1) {
        displaycode = countries_highlightMatch(code, query, codeMatchIndex);
    } else {
        displaycode = code;
    }
    return displayname + " (" + displaycode + ")";
}

var countries_highlightMatch = function(full, snippet, matchindex) {
        return full.substring(0, matchindex) + "<span class='match'>" + full.substr(matchindex, snippet.length) + "</span>" + full.substring(matchindex + snippet.length);
    };



function show_countries_list(o, prefix) {
    //alert(tables.table100.prefix);return;
    tables.table100.prefix = prefix

    region1 = Dom.getRegion(o);
    region2 = Dom.getRegion('dialog_country_list');

    var pos = [region1.right - region2.width + 20, region1.bottom]

    Dom.setXY('dialog_country_list', pos);


    dialog_country_list.show();
}




function init_address() {
    dialog_country_list = new YAHOO.widget.Dialog("dialog_country_list", {
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_country_list.render();
    Event.addListener('clean_table_filter_show100', "click", show_filter, 100);
    Event.addListener('clean_table_filter_hide100', "click", hide_filter, 100);

    var oACDS100 = new YAHOO.util.FunctionDataSource(mygetTerms);
    oACDS100.queryMatchContains = true;
    oACDS100.table_id = 100;
    var oAutoComp100 = new YAHOO.widget.AutoComplete("f_input100", "f_container100", oACDS100);
    oAutoComp100.minQueryLength = 0;
    YAHOO.util.Event.addListener('clean_table_filter_show100', "click", show_filter, 100);
    YAHOO.util.Event.addListener('clean_table_filter_hide100', "click", hide_filter, 100);


}
YAHOO.util.Event.onDOMReady(init_address);
