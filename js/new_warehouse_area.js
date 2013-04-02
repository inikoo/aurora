
var area_data = new Object;


function get_area_data() {
    area_data['Warehouse Key'] = Dom.get('warehouse_key').value;
    area_data['Warehouse Area Name'] = Dom.get('area_name').value;
    area_data['Warehouse Area Code'] = Dom.get('area_code').value;
    area_data['Warehouse Area Description'] = Dom.get('area_description').value;

}

function reset_area_data() {
    Dom.get('warehouse_key').value = Dom.get('warehouse_key').getAttribute('ovalue');
    Dom.get('area_name').value = Dom.get('area_name').getAttribute('ovalue');
    Dom.get('area_code').value = Dom.get('area_code').getAttribute('ovalue');
    Dom.get('area_description').value = Dom.get('area_description').getAttribute('ovalue');

}

function add_area() {

    get_area_data();
    var json_value = YAHOO.lang.JSON.stringify(area_data);
    var request = 'ar_edit_warehouse.php?tipo=new_area&values=' + encodeURIComponent(json_value);

    YAHOO.util.Connect.asyncRequest('POST', request, {
        success: function(o) {
            alert(o.responseText);
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.action == 'created') {

                window.location = 'warehouse_area.php?r=nc&id=' + r.warehouse_area_key;
                return;

                reset_area_data();
                var table = tables['table0']
                var datasource = tables['dataSource0'];

                datasource.sendRequest('', table.onDataReturnInitializeTable, table);
            } else if (r.action == 'error') {
                alert(r.msg);
            }



        }
    });
}



function cancel_add_area(){
	                window.location = 'edit_warehouse.php?id='+Dom.get('warehouse_key').value+'&view=areas';

}

function init() {


    YAHOO.util.Event.addListener('add_area', "click", add_area);
    YAHOO.util.Event.addListener('cancel_add_area', "click", cancel_add_area);




}
YAHOO.util.Event.onDOMReady(init);
