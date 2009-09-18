
var individual_location_data =new Array;

function get_block(){
    Dom.get('wellcome').innerHTML='<?php echo _('Adding new location')?>';
    Dom.get('the_chooser').style.display='none';
    Dom.get('block_'+this.id).style.display='';
	
}


function get_location_data(){
   
    individual_location_data['Location Code']=Dom.get('location_code').value;
    individual_location_data['Location Area Code']=Dom.get('location_area_code').value;
    individual_location_data['Location Warehouse Key']=Dom.get('location_warehouse_key').value;
    individual_location_data['Location Mainly Used For']=Dom.get('location_used_for').value;
    individual_location_data['Location Max Weight']=Dom.get('location_max_weight').value;
    individual_location_data['Location Shape Type']=Dom.get('location_shape_type').value;
    individual_location_data['Location Width']=Dom.get('location_width').value;
    individual_location_data['Location Deepth']=Dom.get('location_deepth').value;
    individual_location_data['Location Heigth']=Dom.get('location_heigth').value;
    individual_location_data['Location Radius']=Dom.get('location_radius').value;
}

function save_individual_location(){
    

}


function init(){
    var ids = ["individual","shelf","rack","floor"]; 
    YAHOO.util.Event.addListener(ids, "click", get_block);

    YAHOO.util.Event.addListener('save_location', "click", save_individual_location);


}
YAHOO.util.Event.onDOMReady(init);