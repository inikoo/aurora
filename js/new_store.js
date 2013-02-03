var Event = YAHOO.util.Event;
var Dom = YAHOO.util.Dom;





function save_new_store(){
	    save_new_general('store');
	

}

function post_new_create_actions(branch, response) {
    window.location = "store.php?view=details&id="+ response.store_key

  
}

function cancel_add_store() {
    window.location = "edit_stores.php"
}


function validate_code(query) {
    validate_general('store', 'code', unescape(query));

}


function validate_name(query) {
    validate_general('store', 'name', unescape(query));

}


function change_locate(o){
	
	ids=Dom.getElementsByClassName('radio','button','locale_container')
	Dom.removeClass(ids,'selected');
	Dom.addClass(o,'selected');

	Dom.get('locale').value=o.getAttribute('radio_value')
	
	
	
}


function init() {

    validate_scope_data = {

        'store': {
            'code': {
                'changed': false,
                'validated': false,
                'required': true,
                'dbname': 'Store Code',
                'group': 1,
                'type': 'item',
                'name': 'Code',
                'validation': [{
                    'regexp': "[a-z\d]+",
                    'invalid_msg': Dom.get('invalid_store_code').value
                }],
                'ar': 'find',
                'ar_request': 'ar_assets.php?tipo=is_store_code&query='
            },
             'name': {
                'changed': false,
                'validated': false,
                'required': true,
                'dbname': 'Store Name',
                'group': 1,
                'type': 'item',
                'name': 'Name',

                'validation': [{
                    'regexp': "[a-z\d]+",
                    'invalid_msg': Dom.get('invalid_store_name').value
                }],
                'ar': false
            },
                 'locale': {
                'changed': true,
                'validated': true,
                'required': true,
                'dbname': 'Store Locale',
                'group': 1,
                'type': 'item',
                'name': 'locale',

                'validation':false,
                'ar': false
            }
        }
        
           
      
    };




    validate_scope_metadata = {
        'store': {
            'type': 'new',
            'ar_file': 'ar_edit_assets.php',
            'key_name': 'store_key',
            'key': ''
        }


    };




    init_search('products');



    YAHOO.util.Event.addListener('save_new_store', "click", save_new_store);
    YAHOO.util.Event.addListener('close_add_store', "click", cancel_add_store);


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


}





YAHOO.util.Event.onDOMReady(init);
