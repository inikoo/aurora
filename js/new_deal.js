var Dom   = YAHOO.util.Dom;
    

function campaigns_changed(value) {


    if (value == 'new_campaign') {
        Dom.setStyle('campaign_fields', 'display', '')
    } else if (value == '') {
        Dom.setStyle('campaign_fields', 'display', 'none')

        Dom.get('campaigns_select').value = 'choose_campaign'
    } else if (value == 'choose_campaign') {
        Dom.setStyle('campaign_fields', 'display', 'none')

    } else {
        Dom.setStyle('campaign_fields', 'display', 'none')

    }

}

34 62235 61 41
44 7984 903265

function init(){

init_search('products_store');

  validate_scope_data = {

        'deal': {
            'code': {
                'changed': false,
                'validated': false,
                'required': true,
                'dbname': 'Deal Code',
                'group': 1,
                'type': 'item',
                'name': 'Code',
                'validation': [{
                    'regexp': "[a-z\d]+",
                    'invalid_msg': Dom.get('invalid_deal_code').value
                }],
                'ar': 'find',
                'ar_request': 'ar_deals.php?tipo=is_deal_code&query='
            },
            'name': {
                'changed': false,
                'validated': false,
                'required': true,
                'dbname': 'Deal Name',
                'group': 1,
                'type': 'item',
                'name': 'Name',

                'validation': [{
                    'regexp': "[a-z\d]+",
                    'invalid_msg': Dom.get('invalid_deal_name').value
                }],
                'ar': false
            },
            'country': {
                'changed': true,
                'validated': true,
                'required': true,
                'dbname': 'Country Code',
                'group': 1,
                'type': 'item',
                'name': 'Country',

                'validation': false,
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

                'validation': false,
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

}

YAHOO.util.Event.onDOMReady(init);






