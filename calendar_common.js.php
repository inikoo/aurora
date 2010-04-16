<?php include_once('common.php')?> 

    function CalhandleSelect(type,args,obj) {
  
    var dates = args[0];
    var date = dates[0];
    var year = date[0], month = date[1], day = date[2];
    
    if(month<10)
	month='0'+month;
    if(day<10)
	day='0'+day;
    
    Dom.get('input_'+this.id).value=day + "-" + month + "-" + year;
    this.hide();
  }

function updateCal() {
    
    var Dom   = YAHOO.util.Dom;
    
 
   
    var txtDate1 = Dom.get('input_'+this.id);
   

    if(typeof(txtDate1.value)=='undefined')
	return;
    if (txtDate1.value != "" ) {

	temp = txtDate1.value.split('-');
	var date=temp[1]+'/'+temp[0]+'/'+temp[2];

	this.select(date);
	    
	var selectedDates = this.getSelectedDates();
	
	if (selectedDates.length > 0) {
		var firstDate = selectedDates[0];
		this.cfg.setProperty("pagedate", (firstDate.getMonth()+1) + "/" + firstDate.getFullYear());
		this.render();
	    } else {
	    alert("<?php echo _("Cannot select a date before 1/1/2006 or after 12/31/2008")?>");
	}
	
    }
    
    
}