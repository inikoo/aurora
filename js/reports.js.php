<?
include_once('../common.php');
print "var plot = new Object;";
foreach($_SESSION['state']['reports'] as $key => $value){
    if(is_array($value) and array_key_exists('plot', $value))
	print "plot.$key='".$value['plot']."';";
}
?>

var Event = YAHOO.util.Event;
var Dom   = YAHOO.util.Dom;
var current_view='<?=$_SESSION['state']['reports']['view']?>';

function init(){

	var attributes = {opacity: { to: 1 }};
	var myAnim = new YAHOO.util.Anim('plot_div', attributes);
	myAnim.duration = 3; 

	
    	cal2 = new YAHOO.widget.Calendar("cal2","cal2Container", { title:"<?=_('Choose a date')?>:", close:true } );
	cal2.update=updateCal;cal2.id=2;cal2.render();cal2.update();cal2.selectEvent.subscribe(handleSelect, cal2, true); 
	cal1 = new YAHOO.widget.Calendar("cal1","cal1Container", { title:"<?=_('Choose a date')?>:", close:true } );
	cal1.update=updateCal;cal1.id=1;cal1.render();cal1.update();cal1.selectEvent.subscribe(handleSelect, cal1, true); 
	YAHOO.util.Event.addListener("calpop1", "click", cal1.show, cal1, true);
	YAHOO.util.Event.addListener("calpop2", "click", cal2.show, cal2, true);
	

	var change_plot = function(e){
		
	    if(this.name=='net_sales_gmonth'){
		if(this.checked){
		    Dom.get('the_plot').src = 'plot.php?tipo=total_sales_groupby_month';
		    YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=reports-sales-plot&value=total_sales_groupby_month' );
		    plot_sales='net_sales_gmonth';
		}else{
		    Dom.get('the_plot').src = 'plot.php?tipo=total_sales_month';
		    YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=reports-sales-plot&value=total_sales_month' );
		    plot_sales='net_sales_month';
		}

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

	var change_front_page = function(e){
	    tipo=this.id;

	    if(tipo!=current_view){
		if(tipo=='prod')
		    Dom.get('front_plot').style.display='none';
		else{
		    Dom.get('front_plot').style.display='';
		    
		}

		
		Dom.get('plot_div').style.opacity=0;
		Dom.get('the_plot').src = 'plot.php?tipo='+plot[tipo];
		Dom.get('plot_options_'+current_view).style.display='none';
		
		if(Dom.get('plot_options_'+tipo))
		    Dom.get('plot_options_'+tipo).style.display='';


		Dom.get('header_'+current_view).style.display='none';
		Dom.get('header_'+tipo).style.display='';
		Dom.get('plot_options_'+current_view).style.display='none';

		YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=reports-'+tipo+'-plot&value=' + escape(plot[tipo]) );

		Dom.get(tipo).className='selected';
		Dom.get(current_view).className='';

		YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=reports-view&value=' + escape(tipo) );


		current_view=tipo;
		
		myAnim.animate();
		//setTimeout("myAnim.animate()",2000);
		//		setTimeout(myAnim.animate(), 1000000);



	    }
	}

	var ids = ["sales","geosales","customers","times","prod","stock","products"]; 
	Event.addListener(ids, "click", change_front_page);

}

Event.onDOMReady(init);