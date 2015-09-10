function close_dialog(tipo){
    switch(tipo){
 case('received'):
	received_dialog.hide();
	Dom.get('tr_manual_received_date').style.display="";
	Dom.get('tbody_manual_received_date').style.display="none";
	Dom.get('date_type').value='auto';

	break;
    case('staff'):
	staff_dialog.hide();


	break;
    case('location'):
	location_dialog.hide();
case('delete'):
	delete_dialog.hide();

	break;
 case('checked'):
	checked_dialog.hide();

	break;
    }
  
} 