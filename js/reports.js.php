<?
include_once('../common.php');
?>
var Event = YAHOO.util.Event;
var Dom   = YAHOO.util.Dom;
function init(){


    	cal2 = new YAHOO.widget.Calendar("cal2","cal2Container", { title:"<?=_('Choose a date')?>:", close:true } );
	cal2.update=updateCal;cal2.id=2;cal2.render();cal2.update();cal2.selectEvent.subscribe(handleSelect, cal2, true); 
	cal1 = new YAHOO.widget.Calendar("cal1","cal1Container", { title:"<?=_('Choose a date')?>:", close:true } );
	cal1.update=updateCal;cal1.id=1;cal1.render();cal1.update();cal1.selectEvent.subscribe(handleSelect, cal1, true); 
	YAHOO.util.Event.addListener("calpop1", "click", cal1.show, cal1, true);
	YAHOO.util.Event.addListener("calpop2", "click", cal2.show, cal2, true);
	

	var change_plot = function(e){
		
	    if(this.name=='net_sales_gmonth'){
		if(this.checked){
		    document.getElementById('the_plot').src = 'plot.php?tipo=net_sales_gmonth';
		}else{
		    document.getElementById('the_plot').src = 'plot.php?tipo=net_sales_month';

		}
		
		//document.getElementById('the_plot').src = 'plot.php?tipo='+plot_name;
		//		YAHOO.util.Connect.asyncRequest('POST','ar_assets.php?tipo=changereportplot&value=' + escape(plot_name) ); 
	    }
	}
	
	var ids = ["net_sales_gmonth"]; 
 	Event.addListener(ids, "change", change_plot);

	var go_free = function(e){
	    
	    var from=Dom.get('v_calpop1').value;
	    var to=Dom.get('v_calpop2').value;
	    
	    location.href='report_sales.php?tipo=f&from='+from+'&to='+to; 
	}
	var ids = ["go_free_report"]; 
	Event.addListener(ids, "change", go_free);

}

Event.onDOMReady(init);