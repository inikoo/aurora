function show_export_dialog(e, tag) {

  //  Dom.get('export_xls').onclick = function() {
   //     window.location = 'export.php?ar_file=ar_contacts&tipo=customers&parent=store&parent_key=' + Dom.get('store_key').value + '&output=xls'
  //  };
  //  Dom.get('export_csv').onclick = function() {
  //      window.location = 'export.php?ar_file=ar_contacts&tipo=customers&parent=store&parent_key=' + Dom.get('store_key').value + '&output=csv'
   // };



	Dom.setStyle('dialog_export_'+tag,'display','');
	
    region1 = Dom.getRegion('export_' + tag);
    region2 = Dom.getRegion('dialog_export');

    var pos = [region1.right - 20, region1.bottom]
    Dom.setXY('dialog_export_'+tag, pos);
		   
		   
	Dom.setStyle(['dialog_export_form_'+tag,'export_result_wait_'+tag],'display','')	   
	Dom.setStyle(['dialog_export_maps_'+tag,'dialog_export_fields_'+tag,'dialog_export_result_'+tag,'export_result_download_'+tag],'display','none')	   
	Dom.get('export_result_download_link_'+tag).href='';
		Dom.get('dialog_export_progress_'+tag).innerHTML='';	   

    dialog_export.show()

}
function map_field_changed() {
    //Dom.getElementsByClassName('')
}
function update_map_field(o) {
    if (o.getAttribute('checked') == 1) {
        o.src = 'art/icons/checkbox_unchecked.png';
        o.setAttribute('checked', 0)
    } else {
        o.src = 'art/icons/checkbox_checked.png';
        o.setAttribute('checked', 1)

    }

}
function get_export_table_wait_info(fork_key,table) {
    request = 'ar_export.php?tipo=get_wait_info&fork_key=' + fork_key+'&table='+table
    //alert(request)
    YAHOO.util.Connect.asyncRequest('POST', request, {
        success: function(o) {
            //   alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
                if (r.fork_state == 'Queued') {
                    setTimeout(function() {
                        get_export_table_wait_info(r.fork_key,r.table)
                    }, 1000);


                } else if (r.fork_state == 'In Process') {
                    // alert(r.msg)
                    //Dom.get('dialog_edit_subjects_wait_done').innerHTML = r.msg
                    		Dom.get('dialog_export_progress_'+r.table).innerHTML=r.progress;	   

                    
                    setTimeout(function() {
                        get_export_table_wait_info(r.fork_key,r.table)
                    }, 1000);

                } else if (r.fork_state == 'Finished') {


                    Dom.setStyle('export_result_wait_'+r.table, 'display', 'none')
                    Dom.get('export_result_download_link_'+r.table).href = 'download.php?f='+r.result;

                    Dom.setStyle('export_result_download_'+r.table, 'display', '')



                }


            }
        }

    });

}
function export_table(e, data) {

    request = 'ar_export.php?tipo=export&table='+data.table+'&parent='+data.parent+'&parent_key='+data.parent_key+'&output='+data.output
//alert(request)
    YAHOO.util.Connect.asyncRequest('POST', request, {

        success: function(o) {
		//	alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == '200') {
            
           		 Dom.setStyle(['dialog_export_form_'+r.table,'dialog_export_maps_'+r.table,'dialog_export_fields_'+r.table],'display','none')
				 Dom.setStyle('dialog_export_result_'+r.table,'display','')
				 
				  get_export_table_wait_info(r.fork_key,r.table);
				 

            } else {

                //Dom.get('send_reset_password_msg').innerHTML = r.msg;
            }


        }
    });

}
function download_export_file(){
	 dialog_export.hide()

}