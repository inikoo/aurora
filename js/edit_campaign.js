var Dom = YAHOO.util.Dom;

var validate_scope_data;
var validate_scope_metadata;


function change_block(e) {

    var ids = ["description", "state"];
    var block_ids = ["d_description", "d_state"];

    Dom.setStyle(block_ids, 'display', 'none');
    Dom.setStyle('d_' + this.id, 'display', '');

    Dom.removeClass(ids, 'selected');
    Dom.addClass(this, 'selected');

    YAHOO.util.Connect.asyncRequest('POST', 'ar_sessions.php?tipo=update&keys=campaign-edit_block_view&value=' + this.id, {});

}



YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {






    };
});



function validate_campaign_code(query) {

    validate_general('campaign_description', 'code', unescape(query));
}

function validate_campaign_name(query) {

    validate_general('campaign_description', 'name', unescape(query));
}

function validate_campaign_description(query) {
    validate_general('campaign_description', 'description', unescape(query));
}

function save_description() {
    save_edit_general('campaign_description');
}

function reset_description() {
    reset_edit_general('campaign_description');

}

function init() {
    validate_scope_data = {
        'campaign_description': {
            'description': {
                'changed': false,
                'validated': true,
                'required': true,
                'group': 1,
                'type': 'item',
                'name': 'campaign_description',
                'dbname' : 'Deal Campaign Description',
                'ar': false,
                'validation': [{
                    'regexp': "[a-z\d]+",
                    'invalid_msg': Dom.get('invalid_campaign_description').value
                }]
            },
            'name': {
                'changed': false,
                'validated': true,
                'required': false,
                'group': 1,
                'type': 'item',
                'name': 'campaign_name',
                'dbname' : 'Deal Campaign Name',
                'validation': [{
                    'regexp': "[a-z\d]+",
                    'invalid_msg': Dom.get('invalid_campaign_name').value
                }]
            },
            'code': {
                'ar': 'find',
                'ar_request': 'ar_assets.php?tipo=is_campaign_code&store_key=' + Dom.get('store_key').value + '&query=',
                'changed': false,
                'validated': true,
                'required': false,
                'group': 1,
                'type': 'item',
                'name': 'campaign_code',
                'dbname' : 'Deal Campaign Code',
                'validation': false
            }
        }
    };
    validate_scope_metadata = {
        'campaign_description': {
            'type': 'edit',
            'ar_file': 'ar_edit_deals.php',
            'key_name': 'campaign_key',
            'key': Dom.get('campaign_key').value
        }

    };

    var campaign_code_oACDS = new YAHOO.util.FunctionDataSource(validate_campaign_code);
    campaign_code_oACDS.queryMatchContains = true;
    var campaign_code_oAutoComp = new YAHOO.widget.AutoComplete("campaign_code", "campaign_code_Container", campaign_code_oACDS);
    campaign_code_oAutoComp.minQueryLength = 0;
    campaign_code_oAutoComp.queryDelay = 0.1;

    var campaign_name_oACDS = new YAHOO.util.FunctionDataSource(validate_campaign_name);
    campaign_name_oACDS.queryMatchContains = true;
    var campaign_name_oAutoComp = new YAHOO.widget.AutoComplete("campaign_name", "campaign_name_Container", campaign_name_oACDS);
    campaign_name_oAutoComp.minQueryLength = 0;
    campaign_name_oAutoComp.queryDelay = 0.1;


    var campaign_description_oACDS = new YAHOO.util.FunctionDataSource(validate_campaign_description);
    campaign_description_oACDS.queryMatchContains = true;
    var campaign_description_oAutoComp = new YAHOO.widget.AutoComplete("campaign_description", "campaign_description_Container", campaign_description_oACDS);
    campaign_description_oAutoComp.minQueryLength = 0;
    campaign_description_oAutoComp.queryDelay = 0.1;




    init_search('products_store');

    var ids = ["description", "state"];
    Event.addListener(ids, "click", change_block);

    Event.addListener('save_edit_campaign_description', "click", save_description);
    Event.addListener('reset_edit_campaign_description', "click", reset_description);



}

YAHOO.util.Event.onDOMReady(init);
