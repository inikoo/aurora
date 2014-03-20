function calculate_num_changed_in_telecom() {
    var changed = new Object();
    var to_delete = 0;
    var invalid = 0;
    var new_telecom = 0;

    var elements_array = Dom.getElementsByClassName('Telecom', 'input');
    for (var i in elements_array) {
        var input_element = elements_array[i];
        var telecom_key = input_element.getAttribute('telecom_key');
        if (telecom_key.match('new')) {

            if (input_element.value != '' && input_element.getAttribute('valid') == 1 && input_element.getAttribute('to_delete') == 0) new_telecom++;
        } else if (input_element.getAttribute('to_delete') == 1) {
            to_delete++;
        } else if (telecom_key > 0 && input_element.getAttribute('ovalue') != input_element.value) {
            //alert(telecom_key)
            changed[telecom_key] = 1;
            if (input_element.getAttribute('valid') == 0) invalid++;


        }
    }


    var elements_array = Dom.getElementsByClassName('Mobile_Is_Main', 'input');
    for (var i in elements_array) {
        var input_element = elements_array[i];
        var telecom_key = input_element.getAttribute('telecom_key');
        if (telecom_key > 0 && input_element.getAttribute('ovalue') != input_element.value) {
            changed[telecom_key] = 1;
            break;
        }
    }




    var changes = 0;
    for (i in changed)
    changes++;

    Contact_Telecoms_Changes = changes - invalid + to_delete + new_telecom;
    Contact_Telecoms_to_edit = changes - invalid;
    Contact_Telecoms_to_delete = to_delete;
    Contact_Telecoms_to_add = new_telecom;

    render_after_contact_item_change();


}


function telecom_change() {
    calculate_num_changed_in_telecom();
    render_after_contact_item_change();

}


function show_details_telecom(o) {
    var action = o.getAttribute('action');
    var telecom_key = o.getAttribute('telecom_key');
    var telecom_type = o.getAttribute('telecom_type');
    var components = Telecom_Components[telecom_type];
    for (i in components) {
        if (action == 'Show') {
            o.innerHTML = '<img src="art/icons/application_ungo.png" alt="H"/>';
            o.setAttribute('action', 'Hide');
            Dom.setStyle("tr_telecom" + components[i] + telecom_key, 'display', '');
        } else {
            o.innerHTML = '<img src="art/icons/application_put.png" alt="D"/>';
            o.setAttribute('action', 'Show');
            Dom.setStyle("tr_telecom" + components[i] + telecom_key, 'display', 'none');

        }
    }
}

function validate_telecom(o) {
    var telecom = o.value;
    var telecom_key = o.getAttribute('telecom_key');

    if (isValidTelecom(telecom)) {
        o.setAttribute('valid', 1);
        Dom.removeClass(o, 'invalid');
    } else {
        o.setAttribute('valid', 0);
        Dom.addClass(o, 'invalid');
    }

}


function mark_telecom_to_delete(o) {

    var telecom_key = o.getAttribute('telecom_key');
    var telecom = Dom.get('Telecom' + telecom_key).value;


    Dom.setStyle(["telecom_to_delete" + telecom_key, 'undelete_telecom_button' + telecom_key], 'display', '');
    Dom.setStyle(["Telecom" + telecom_key, 'delete_telecom_button' + telecom_key, "Telecom_Details" + telecom_key], 'display', 'none');

    Dom.setStyle("Telecom" + telecom_key, 'display', 'none');
    Dom.get('telecom_to_delete' + telecom_key).innerHTML = telecom;
    Dom.get('Telecom' + telecom_key).setAttribute('to_delete', 1);
    //Dom.setStyle('[show_details_telecom_button'+telecom_key,"Telecom_Details"+telecom_key],'display','none');
    //Dom.get('show_details_telecom_button'+telecom_key).innerHTML='Edit Details';
    //Dom.get('show_details_telecom_button'+telecom_key).setAttribute('action','Show');
    calculate_num_changed_in_telecom();

}

function unmark_telecom_to_delete(o) {
    var telecom = o.value;
    var telecom_key = o.getAttribute('telecom_key');
    Dom.setStyle(["telecom_to_delete" + telecom_key, 'undelete_telecom_button' + telecom_key], 'display', 'none');
    Dom.setStyle(["Telecom" + telecom_key, 'delete_telecom_button' + telecom_key], 'display', '');

    //Dom.setStyle('[show_details_telecom_button'+telecom_key,"Telecom_Details"+telecom_key],'display','');
    Dom.get('telecom_to_delete' + telecom_key).innerHTML = '';
    Dom.get('Telecom' + telecom_key).setAttribute('to_delete', 0);
    calculate_num_changed_in_telecom();
}

function add_telecom(o) {
    var container_key = o.getAttribute('container_key');
    var telecom_type = o.getAttribute('telecom_type');


    if (Number_New_Empty_Telecoms[telecom_type] == 0) {

        var telecom_key = 'new' + telecom_type + Number_New_Telecoms;
        clone_telecom(telecom_type, container_key, telecom_key);


        if (telecom_type == 'mobile') var telecom_numbers = Contact_Data[Current_Contact_Index]['Number_Of_Mobiles'] + Number_New_Telecoms[telecom_type];
        else if (telecom_type == 'telephone') var telecom_numbers = Contact_Data[Current_Contact_Index]['Addresses'][container_key]['Number_Of_Telephones'] + Number_New_Telecoms[telecom_type];
        else if (telecom_type == 'fax') var telecom_numbers = Contact_Data[Current_Contact_Index]['Addresses'][container_key]['Number_Of_Faxes'] + Number_New_Telecoms[telecom_type];


        if (telecom_type == 'mobile') {
            if (telecom_numbers == 0) {
                Dom.get('Mobile_Is_Main' + telecom_key).value = 'Yes';
                Dom.get('Mobile_Is_Main' + telecom_key).setAttribute('ovalue', 'Yes');
                Dom.get('Mobile_Is_Main' + telecom_key).checked = true;
            } else {
                Dom.get('Mobile_Is_Main' + telecom_key).value = 'No';
                Dom.get('Mobile_Is_Main' + telecom_key).setAttribute('ovalue', 'No');
                Dom.get('Mobile_Is_Main' + telecom_key).checked = false;
            }
        } else {
            if (telecom_numbers == 0) {
                Dom.get('Telecom_Is_Main' + telecom_key).value = 'Yes';
                Dom.get('Telecom_Is_Main' + telecom_key).setAttribute('ovalue', 'Yes');
                Dom.get('Telecom_Is_Main' + telecom_key).checked = true;
            } else {
                Dom.get('Telecom_Is_Main' + telecom_key).value = 'No';
                Dom.get('Telecom_Is_Main' + telecom_key).setAttribute('ovalue', 'No');
                Dom.get('Telecom_Is_Main' + telecom_key).checked = false;
            }
        }


        Number_New_Empty_Telecoms[telecom_type]++;
        Number_New_Telecoms[telecom_type]++;
    }
}

function clone_telecom(telecom_type, container_key, telecom_key) {

    mould_key = telecom_type + '_mould' + container_key;

    var new_telecom_container = Dom.get(mould_key).cloneNode(true);
    var the_parent = Dom.get(mould_key).parentNode;

    var insertedElement = the_parent.insertBefore(new_telecom_container, Dom.get(mould_key));
    Dom.addClass(insertedElement, 'cloned_editor');
    Dom.setStyle(insertedElement, 'display', '');
    insertedElement.id = "tr_telecom" + telecom_key;
    insertedElement.setAttribute('telecom_key', telecom_key);

    var element_array = Dom.getElementsByClassName('show_details_telecom', 'span', insertedElement);
    element_array[0].setAttribute('telecom_key', telecom_key);
    element_array[0].setAttribute('telecom_type', telecom_type);

    element_array[0].id = 'show_details_telecom_button' + telecom_key;


    var element_array = Dom.getElementsByClassName('Telecom', 'input', insertedElement);
    element_array[0].setAttribute('telecom_key', telecom_key);
    element_array[0].setAttribute('container_key', container_key);

    element_array[0].id = 'Telecom' + telecom_key;

    if (telecom_type == 'mobile') {
        var element_array = Dom.getElementsByClassName('Mobile_Is_Main', 'input', insertedElement);
        element_array[0].setAttribute('telecom_key', telecom_key);
        element_array[0].id = 'Mobile_Is_Main' + telecom_key;

    } else {

        var element_array = Dom.getElementsByClassName('Telecom_Is_Main', 'input', insertedElement);
        element_array[0].setAttribute('telecom_key', telecom_key);
        element_array[0].id = 'Telecom_Is_Main' + telecom_key;
    }
    var element_array = Dom.getElementsByClassName('telecom_to_delete', 'span', insertedElement);
    element_array[0].setAttribute('telecom_key', telecom_key);
    element_array[0].id = 'telecom_to_delete' + telecom_key;

    var element_array = Dom.getElementsByClassName('delete_telecom', 'span', insertedElement);
    element_array[0].setAttribute('telecom_key', telecom_key);
    element_array[0].id = 'delete_telecom_button' + telecom_key;

    var element_array = Dom.getElementsByClassName('undelete_telecom', 'span', insertedElement);
    element_array[0].setAttribute('telecom_key', telecom_key);
    element_array[0].id = 'undelete_telecom_button' + telecom_key;

    var components = Telecom_Components[telecom_type]

    for (i in components) {
        component_mould_key = telecom_type + container_key + '_' + components[i] + '_mould';

        var new_telecom_container = Dom.get(component_mould_key).cloneNode(true);

        var insertedElement = the_parent.insertBefore(new_telecom_container, Dom.get(mould_key));
        Dom.addClass(insertedElement, 'cloned_editor');
        insertedElement.id = "tr_telecom" + components[i] + telecom_key;
        insertedElement.setAttribute('telecom_key', telecom_key);
        var element_array = Dom.getElementsByClassName(components[i], 'input', insertedElement);
        element_array[0].id = components[i] + telecom_key;


    }

    //var element_array=Dom.getElementsByClassName('Telecom_Details', 'tbody',insertedElement);
    //element_array[0].id="Telecom_Details"+telecom_key;
}



function update_is_main_mobile(o) {


    if (o.value == 'Yes') {

        o.checked = true;
        return;
    }




    telecom_key = o.getAttribute('telecom_key');
    if (Dom.get('Telecom' + telecom_key).getAttribute('valid') == 0 || Dom.get('Telecom' + telecom_key).value == '') {

        o.checked = false;
        return;
    }

    var elements_array = Dom.getElementsByClassName('Mobile_Is_Main', 'input');
    for (i in elements_array) {
        var input_element = elements_array[i];
        var telecom_key = input_element.getAttribute('telecom_key');
        if (telecom_key != null && (telecom_key.match('new') || telecom_key > 0)) {
            if (input_element.value == 'Yes') old_is_main_key = telecom_key;
            input_element.value = 'No';
            input_element.checked = false;

        }
    }

    o.value = 'Yes';
    o.checked = true;
    calculate_num_changed_in_telecom();
}


function update_is_main_telecom(o) {


    if (o.value == 'Yes') {
        alert('is valid')
        o.checked = true;
        return;
    }




    telecom_key = o.getAttribute('telecom_key');
    if (Dom.get('Telecom' + telecom_key).getAttribute('valid') == 0 || Dom.get('Telecom' + telecom_key).value == '') {

        o.checked = false;
        return;
    }

    var elements_array = Dom.getElementsByClassName('Telecom_Is_Main', 'input');
    for (i in elements_array) {
        var input_element = elements_array[i];
        var telecom_key = input_element.getAttribute('telecom_key');
        if (telecom_key != null && (telecom_key.match('new') || telecom_key > 0)) {
            if (input_element.value == 'Yes') old_is_main_key = telecom_key;
            input_element.value = 'No';
            input_element.checked = false;

        }
    }


    o.value = 'Yes';
    o.checked = true;
    calculate_num_changed_in_telecom();
}

function set_main_mobile(main_mobile_key) {

    var mobiles = data['Mobiles'];
    for (i in mobiles) {
        if (i == main_mobile_key) {
            data['Mobiles'][i]['Telecom_Is_Main'] = 'Yes';
            //Dom.get('Mobile_Is_Main'+i).checked=true;
            //Dom.get('Mobile_Is_Main'+i).value='Yes';
        } else {
            data['Mobiles'][i]['Telecom_Is_Main'] = 'No';
            //   Dom.get('Mobile_Is_Main'+i).checked=false;
            //             Dom.get('Mobile_Is_Main'+i).value='No';
        }
    }
}


function set_main_telecom(main_telecom_key) {

    var telecoms = data['Telecoms'];
    for (i in telecoms) {
        if (i == main_telecom_key) data['Telecoms'][i]['Telecom_Is_Main'] = 'Yes';
        else data['Telecoms'][i]['Telecom_Is_Main'] = 'No';
    }

}
