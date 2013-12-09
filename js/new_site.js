var Event = YAHOO.util.Event;
var Dom = YAHOO.util.Dom;




function save_new_site() {



    save_new_general('site');


}

function post_action(branch, response) {
    window.location = "edit_site.php?view=details&id=" + response.object_key


}

function cancel_add_site() {
    window.location = "edit_store.php?id="+Dom.get('stote_key').value
}


function validate_code(query) {
    validate_general('site', 'code', unescape(query));

}


function validate_name(query) {
    validate_general('site', 'name', unescape(query));

}

function validate_url(query) {
    validate_general('site', 'url', unescape(query));

}




function change_locate(o) {

    ids = Dom.getElementsByClassName('radio', 'button', 'locale_container')
    Dom.removeClass(ids, 'selected');
    Dom.addClass(o, 'selected');

    Dom.get('locale').value = o.getAttribute('radio_value')



}

function select_country(oArgs) {

    geo_constraints = tables.table2.getRecord(oArgs.target).getData('code').replace(/<.*?>/g, '');
    Dom.get('Country').value = geo_constraints;
    dialog_country_list.hide();
    hide_filter(true, 2)
    
    validate_scope_data['site']['country']['changed']=true;
        validate_scope_data['site']['country']['validated']=true;

    validate_scope('site')
}

function show_dialog_country_list() {
    region1 = Dom.getRegion('Country');
    region2 = Dom.getRegion('dialog_country_list');
    var pos = [region1.left, region1.bottom]
    Dom.setXY('dialog_country_list', pos);
    dialog_country_list.show()
}

function init() {

    dialog_country_list = new YAHOO.widget.Dialog("dialog_country_list", {

        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_country_list.render();
    Event.addListener("country_button", "click", show_dialog_country_list);

    var oACDS2 = new YAHOO.util.FunctionDataSource(mygetTerms);
    oACDS2.queryMatchContains = true;
    oACDS2.table_id = 2;
    var oAutoComp2 = new YAHOO.widget.AutoComplete("f_input2", "f_container2", oACDS2);
    oAutoComp2.minQueryLength = 0;
    YAHOO.util.Event.addListener('clean_table_filter_show2', "click", show_filter, 2);
    YAHOO.util.Event.addListener('clean_table_filter_hide2', "click", hide_filter, 2);

    validate_scope_data = {

        'site': {
            'code': {
                'changed': false,
                'validated': false,
                'required': true,
                'dbname': 'Site Code',
                'group': 1,
                'type': 'item',
                'name': 'Code',
                'validation': [{
                    'regexp': "[a-z\d]+",
                    'invalid_msg': Dom.get('invalid_site_code').value
                }],
                'ar': 'find',
                'ar_request': 'ar_sites.php?tipo=is_site_code&query='
            },
            'name': {
                'changed': false,
                'validated': false,
                'required': true,
                'dbname': 'Site Name',
                'group': 1,
                'type': 'item',
                'name': 'Name',

                'validation': [{
                    'regexp': "[a-z\d]+",
                    'invalid_msg': Dom.get('invalid_site_name').value
                }],
                'ar': false
            },
    
         'url': {
                'changed': false,
                'validated': false,
                'required': true,
                'dbname': 'Site URL',
                'group': 1,
                'type': 'item',
                'name': 'URL',

                'validation': [{
                    'regexp': "[a-z\d]+",
                    'invalid_msg': Dom.get('invalid_site_url').value
                }],
                'ar': 'find',
                'ar_request': 'ar_sites.php?tipo=is_site_url&query='
            },
    
    
            'locale': {
                'changed': true,
                'validated': true,
                'required': true,
                'dbname': 'Site Locale',
                'group': 1,
                'type': 'item',
                'name': 'locale',

                'validation': false,
                'ar': false
            }



        }



    };




    validate_scope_metadata = {
        'site': {
            'type': 'new',
            'ar_file': 'ar_edit_sites.php',
            'key_name': 'store_key',
            'key': Dom.get('store_key').value
        }


    };




    init_search('products');



    YAHOO.util.Event.addListener('save_new_site', "click", save_new_site);
    YAHOO.util.Event.addListener('close_add_site', "click", cancel_add_site);


    var code_oACDS = new YAHOO.util.FunctionDataSource(validate_code);
    code_oACDS.queryMatchContains = true;
    var code_oAutoComp = new YAHOO.widget.AutoComplete("Code", "Code_Container", code_oACDS);
    code_oAutoComp.minQueryLength = 0;
    code_oAutoComp.queryDelay = 0.1;
    var name_oACDS = new YAHOO.util.FunctionDataSource(validate_name);
    name_oACDS.queryMatchContains = true;
    var name_oAutoComp = new YAHOO.widget.AutoComplete("Name", "Name_Container", name_oACDS);
    name_oAutoComp.minQueryLength = 0;
    name_oAutoComp.queryDelay = 0.1;
    var name_oACDS = new YAHOO.util.FunctionDataSource(validate_url);
    name_oACDS.queryMatchContains = true;
    var name_oAutoComp = new YAHOO.widget.AutoComplete("URL", "URL_Container", name_oACDS);
    name_oAutoComp.minQueryLength = 0;
    name_oAutoComp.queryDelay = 0.1;

}





YAHOO.util.Event.onDOMReady(init);
