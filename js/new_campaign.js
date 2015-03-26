var Dom = YAHOO.util.Dom;

function post_new_create_actions(branch, response) {
    window.location = "campaign.php?id=" + response.campaign_key
}

function reset_new_campaign() {
    reset_edit_general('campaign')
}


function save_new_campaign() {

    save_new_general('campaign');
}


function validate_campaign_code(query) {
    validate_general('campaign', 'code', query);
}

function validate_campaign_name(query) {
    validate_general('campaign', 'name', query);
}

function validate_campaign_description(query) {
    validate_general('campaign', 'description', query);
}

function date_changed() {

    if (this.id == 'v_calpop1') {

        validate_general('campaign', 'from', this.value);
    } else if (this.id == 'v_calpop2') {
        validate_general('campaign', 'to', this.value);

    }
}

function handleSelect(type, args, obj) {

    var dates = args[0];
    var date = dates[0];
    var year = date[0],
        month = date[1],
        day = date[2];


    if (month < 10) month = '0' + month;
    if (day < 10) day = '0' + day;
    var txtDate1 = document.getElementById("v_calpop" + this.id);
    txtDate1.value = day + "-" + month + "-" + year;
    this.hide();

    if (this.id == 1) {
        validate_general('campaign', 'from', txtDate1.value);
    } else if (this.id == 2) {
        validate_general('campaign', 'to', txtDate1.value);
    }
}

function start_now() {

    if (Dom.hasClass("start_now", "selected")) {
        Dom.removeClass("start_now", "selected")
        Dom.setStyle(['v_calpop1', 'calpop1'], 'display', '');
    } else {
        Dom.addClass("start_now", "selected")
         Dom.setStyle(['v_calpop1', 'calpop1'], 'display', 'none');
        var d = new Date()
        year = d.getFullYear(),
            month = d.getMonth(),
            day = d.getDate();
        if (month < 10) month = '0' + month;
        if (day < 10) day = '0' + day;
        var date = day + "-" + month + "-" + year;
        Dom.get("v_calpop1").value=date
       
        validate_general('campaign', 'from', date);
    }
}

function permanent_campaign() {

    if (Dom.hasClass("to_permanent", "selected")) {
        Dom.removeClass("to_permanent", "selected")
        Dom.setStyle(['v_calpop2', 'calpop2'], 'display', '');
        validate_scope_data.campaign.to.validated = false;
        validate_scope('campaign');
    } else {
        Dom.addClass("to_permanent", "selected")
        Dom.setStyle(['v_calpop2', 'calpop2'], 'display', 'none');

        validate_scope_data.campaign.to.validated = true;
        validate_scope_data.campaign.to.changed = true;

        Dom.get("v_calpop2").value = '';
        validate_scope('campaign');

    }
}

function init() {

    init_search('products_store');


    validate_scope_data = {
        'deal': {
            'code': {
                'changed': false,
                'validated': false,
                'required': true,
                'group': 1,
                'type': 'item',
                'name': 'campaign_code',
                'ar': 'find',
                'ar_request': 'ar_deals.php?tipo=is_campaign_code_in_store&store_key=' + Dom.get('store_key').value + '&query=',
                'dbname': 'Deal Campaign Code'
            }

            ,
            'name': {
                'changed': false,
                'validated': false,
                'required': true,
                'group': 1,
                'type': 'item',
                'name': 'campaign_name',
                'ar': false,
                'dbname': 'Deal Campaign Name',
                'validation': [{
                    'regexp': "[a-z\\d]+",
                    'invalid_msg': 'Invalid name'
                }]
            },
            'description': {
                'changed': false,
                'validated': false,
                'required': false,
                'group': 1,
                'type': 'item',
                'name': 'campaign_description',
                'ar': false,
                'dbname': 'Deal Campaign Description',
                'validation': [{
                    'regexp': "[a-z\\d]+",
                    'invalid_msg': 'Invalid description'
                }]
            },
            'from': {
                'changed': false,
                'validated': false,
                'required': true,
                'group': 1,
                'type': 'item',
                'name': 'v_calpop1',
                'ar': false,
                'dbname': 'Deal Campaign Valid From',
                'validation': [{
                    'regexp': "\\d{2}-\\d{2}-\\d{4}",
                    'invalid_msg': Dom.get('invalid_date').value
                }]
            },
            'to': {
                'changed': false,
                'validated': false,
                'required': true,
                'group': 1,
                'type': 'item',
                'name': 'v_calpop2',
                'ar': false,
                'dbname': 'Deal Campaign Valid To',
                'validation': [{
                    'regexp': "\\d{2}-\\d{2}-\\d{4}",
                    'invalid_msg': Dom.get('invalid_date').value
                }]
            }

        }
    };




    validate_scope_metadata = {
        'campaign': {
            'type': 'new',
            'ar_file': 'ar_edit_deals.php',
            'key_name': 'store_key',
            'key': Dom.get('store_key').value
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

    cal1 = new YAHOO.widget.Calendar("calpop1", "campaign_from_Container", {
        title: "Start:",
        mindate: new Date(),
        close: true
    });
    cal1.update = updateCal;
    cal1.id = '1';
    cal1.render();
    cal1.update();
    cal1.selectEvent.subscribe(handleSelect, cal1, true);

    YAHOO.util.Event.addListener("calpop1", "click", cal1.show, cal1, true);


    cal2 = new YAHOO.widget.Calendar("calpop2", "campaign_to_Container", {
        title: "Until:",
        mindate: new Date(),
        close: true
    });
    cal2.update = updateCal;
    cal2.id = '2';
    cal2.render();
    cal2.update();
    cal2.selectEvent.subscribe(handleSelect, cal2, true);

    YAHOO.util.Event.addListener("calpop2", "click", cal2.show, cal2, true);



    Event.addListener(['v_calpop1', 'v_calpop2'], "keyup", date_changed);

    YAHOO.util.Event.addListener('save_new_campaign', "click", save_new_campaign)
    YAHOO.util.Event.addListener('to_permanent', "click", permanent_campaign)
    YAHOO.util.Event.addListener('start_now', "click", start_now)




}

YAHOO.util.Event.onDOMReady(init);
