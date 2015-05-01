function validate_product_code(query) {
    validate_general('product', 'product_code', unescape(query));
}

function validate_units_per_case(query) {
    validate_general('product', 'units_per_case', unescape(query));
}

function validate_case_cost(query) {
    validate_general('product', 'case_cost', unescape(query));
}

function validate_product_name(query) {
    validate_general('product', 'product_name', unescape(query));
}

function validate_product_description(query) {
    validate_general('product', 'product_description', unescape(query));
}


function save_new_product() {
    save_new_general('product');
}

function post_action(branch, r) {
    window.location.href = 'supplier_product.php?pid=' + r.object_key;
}

function init() {
    init_search('supplier_products_supplier');

    session_data = YAHOO.lang.JSON.parse(base64_decode(Dom.get('session_data').value));
    labels = session_data.label;

    validate_scope_data = {

        'product': {
            'product_code': {
                'changed': false,
                'validated': false,
                'required': true,
                'group': 1,
                'type': 'item',
                'validation': [{
                    'regexp': "[a-z\d]+",
                    'invalid_msg': labels.Invalid_code
                }],
                'name': 'product_code',
                'ar': 'find',
                'ar_request': 'ar_suppliers.php?tipo=is_supplier_product_code&supplier_key=' + Dom.get('supplier_key').value + '&query=',
                'dbname': 'Supplier Product Code'
            },
            'product_name': {
                'changed': false,
                'validated': false,
                'required': true,
                'group': 1,
                'type': 'item',
                'validation': [{
                    'regexp': "[a-z\\d]+",
                    'invalid_msg': labels.Invalid_description
                }],
                'name': 'product_name',
                'ar': 'find',
                'ar_request': 'ar_suppliers.php?tipo=is_supplier_product_name&supplier_key=' + Dom.get('supplier_key').value + '&query=',
                'dbname': 'Supplier Product Name'
            },
            'units_per_case': {
                'changed': false,
                'validated': false,
                'required': true,
                'group': 1,
                'type': 'item',
                'name': 'units_per_case',
                'ar': false,
                'dbname': 'SPH Units Per Case',
                'validation': [{
                    'numeric': "positive integer",
                    'invalid_msg': labels.Invalid_number
                }]
            },
            'case_cost': {
                'changed': false,
                'validated': false,
                'required': true,
                'dbname': 'SPH Case Cost',
                'group': 1,
                'type': 'item',
                'name': 'case_cost',
                'ar': false,
                'validation': [{
                   'numeric': "money",
                    'invalid_msg': labels.Invalid_amount
                }]
            },
            'product_description': {
                'changed': false,
                'validated': false,
                'required': false,
                'dbname': 'Supplier Product Description',
                'group': 1,
                'type': 'item',
                'name': 'product_description',
                'ar': false,
                'validation': [{
                    'regexp': "[a-z\\d]+",
                    'invalid_msg': labels.Invalid_description
                }]
            }


        }
    };



    validate_scope_metadata = {
        'product': {
            'type': 'new',
            'ar_file': 'ar_edit_suppliers.php',
            'key_name': 'supplier_key',
            'key': Dom.get('supplier_key').value
        }


    };





    var product_units_oACDS = new YAHOO.util.FunctionDataSource(validate_product_code);
    product_units_oACDS.queryMatchContains = true;
    var product_units_oAutoComp = new YAHOO.widget.AutoComplete("product_code", "product_code_Container", product_units_oACDS);
    product_units_oAutoComp.minQueryLength = 0;
    product_units_oAutoComp.queryDelay = 0.1;

    var product_units_oACDS = new YAHOO.util.FunctionDataSource(validate_units_per_case);
    product_units_oACDS.queryMatchContains = true;
    var product_units_oAutoComp = new YAHOO.widget.AutoComplete("units_per_case", "units_per_case_Container", product_units_oACDS);
    product_units_oAutoComp.minQueryLength = 0;
    product_units_oAutoComp.queryDelay = 0.1;

    var product_units_oACDS = new YAHOO.util.FunctionDataSource(validate_case_cost);
    product_units_oACDS.queryMatchContains = true;
    var product_units_oAutoComp = new YAHOO.widget.AutoComplete("case_cost", "case_cost_Container", product_units_oACDS);
    product_units_oAutoComp.minQueryLength = 0;
    product_units_oAutoComp.queryDelay = 0.1;

    var product_units_oACDS = new YAHOO.util.FunctionDataSource(validate_product_name);
    product_units_oACDS.queryMatchContains = true;
    var product_units_oAutoComp = new YAHOO.widget.AutoComplete("product_name", "product_name_Container", product_units_oACDS);
    product_units_oAutoComp.minQueryLength = 0;
    product_units_oAutoComp.queryDelay = 0.1;

    var product_units_oACDS = new YAHOO.util.FunctionDataSource(validate_product_description);
    product_units_oACDS.queryMatchContains = true;
    var product_units_oAutoComp = new YAHOO.widget.AutoComplete("product_description", "product_description_Container", product_units_oACDS);
    product_units_oAutoComp.minQueryLength = 0;
    product_units_oAutoComp.queryDelay = 0.1;


    YAHOO.util.Event.addListener('save_new_product', "click", save_new_product)
    
}


YAHOO.util.Event.onDOMReady(init);
