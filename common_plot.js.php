<?php
include_once('common.php');


$page=$_REQUEST['page'];
?>

var category_labels={'sales':'<?php echo _('Net Item Sales')?>','profit':'<?php echo _('Profits')?>'};
var period_labels={'m':'<?php echo _('Montly')?>','y':'<?php echo _('Yearly')?>','w':'<?php echo _('Weekly')?>','q':'<?php echo _('Quarterly')?>'};
var pie_period_labels={'m':'<?php echo _('Month')?>','y':'<?php echo _('Year')?>','w':'<?php echo _('Week')?>','q':'<?php echo _('Quarter')?>'};

var plot='<?php echo$_SESSION['state'][$page]['plot']?>';


var plot_interval_data={
    'y':{'bins':<?php echo $_SESSION['state'][$page]['plot_interval']['y']['plot_bins']?>,'forecast_bins':<?php echo $_SESSION['state'][$page]['plot_interval']['y']['plot_forecast_bins']?>},
    'q':{'bins':<?php echo $_SESSION['state'][$page]['plot_interval']['q']['plot_bins']?>,'forecast_bins':<?php echo $_SESSION['state'][$page]['plot_interval']['q']['plot_forecast_bins']?>},
    'm':{'bins':<?php echo $_SESSION['state'][$page]['plot_interval']['m']['plot_bins']?>,'forecast_bins':<?php echo $_SESSION['state'][$page]['plot_interval']['m']['plot_forecast_bins']?>},
    'w':{'bins':<?php echo $_SESSION['state'][$page]['plot_interval']['w']['plot_bins']?>,'forecast_bins':<?php echo $_SESSION['state'][$page]['plot_interval']['w']['plot_forecast_bins']?>}    
    };


function change_plot_category(category){
    o=Dom.get('plot_'+plot);
    Dom.get('plot_info').setAttribute("category",category);
yes
    change_plot(o);
}


function change_plot_interval(from){


o=Dom.get('plot_'+plot);
plot_interval_data[ Dom.get('plot_info').getAttribute("period")].bins=from;


Dom.get('plot_info').setAttribute("from",from);

    change_plot(o);
}

function change_pie_interval(periods){


o=Dom.get('plot_'+plot);


Dom.get('plot_info').setAttribute("interval",periods);

    change_plot(o);
}

function change_plot_forecast_interval(to){
o=Dom.get('plot_'+plot);
    Dom.get('plot_info').setAttribute("to",to);
plot_interval_data[ Dom.get('plot_info').getAttribute("period")].forecast_bins=to;

    change_plot(o);
}

function change_plot_period(period){
    o=Dom.get('plot_'+plot);
    alert('plot_'+plot)
    Dom.get('plot_info').setAttribute("period",period);
    Dom.get('plot_info').setAttribute("from",plot_interval_data[period].bins);
    Dom.get('plot_info').setAttribute("to",plot_interval_data[period].forecast_bins);
    change_plot(o);
}
function change_plot(o){
    //  if(!Dom.hasClass(o,'selected')){

	var keys=Dom.get("plot_info").getAttribute("keys");
	

	var tipo=o.getAttribute("tipo");
	var category=Dom.get('plot_info').getAttribute("category");
	var period=Dom.get('plot_info').getAttribute("period");
	var from=Dom.get('plot_info').getAttribute("from");
	var to=Dom.get('plot_info').getAttribute("to");




alert(tipo)
	if(tipo=='pie'){
	    plot='pie';
			var pie_interval=o.getAttribute("interval");

	    var forecast=o.getAttribute("forecast");
	    var date=o.getAttribute("date");

	    

	   // Dom.get("the_plot").width="500px";
	    var plot_url='pie.php?tipo=children_share&item=store&interval='+pie_interval+'&category='+category+'&forecast='+forecast+'&date='+date+'&keys='+keys;
	    //alert(plot_url)
	    plot_code=tipo;
	    //Dom.get("pie_options").style.display='';
	    //Dom.get("plot_options").style.display='none';

	    //old_selected=Dom.getElementsByClassName('selected', 'td', 'pie_period_options');
	    //for( var i in old_selected )
		//Dom.removeClass(old_selected[i],'selected');
	    //Dom.addClass("pie_period_"+period,'selected');
	    //old_selected=Dom.getElementsByClassName('selected', 'td', 'pie_category_options');
	    //for( var i in old_selected )
		//Dom.removeClass(old_selected[i],'selected');
	    //Dom.addClass("pie_category_"+category,'selected');
	    
	}else if(tipo=='top_departments'){
	    plot='top_departments';
	    top_children=3;
	    //Dom.get("pie_options").style.display='none';
	    var plot_url='plot.php?tipo=store&top_children='+top_children+'&category='+category+'&period='+period+'&keys='+keys+'&from='+from+'&to='+to;
	   //Dom.get("the_plot").width="100%";
	    plot_code=tipo+'_'+category+'_'+period;

	    Dom.get("plot_category").innerHTML=category_labels[category];
	    Dom.get("plot_period").innerHTML=period_labels[period];
	    Dom.get("plot_options").style.display='';
	     if(from==100)
        from='∀';
	    Dom.get("plot_interval").innerHTML=from+':'+to;

	}else if(tipo=='store' || tipo=='department' || tipo=='family' || tipo=='product'  ){
	    plot='store';
	    Dom.get("pie_options").style.display='none';
	    var plot_url='plot.php?tipo='+tipo+'&category='+category+'&period='+period+'&keys='+keys+'&from='+from+'&to='+to;
	    alert(plot_url);


	    //Dom.get("the_plot").width="100%";
	    plot_code=tipo+'_'+category+'_'+period;

        if(from==100)
        from='∀';
	    Dom.get("plot_interval").innerHTML=from+':'+to;

	    Dom.get("plot_category").innerHTML=category_labels[category];
	    Dom.get("plot_period").innerHTML=period_labels[period];
	    Dom.get("plot_options").style.display='';
	}else if(tipo=='part_outs' || tipo=='part_stock_history' ){
	    plot='part';
	    
	    //plot.php?tipo=part&category=stock_history&period=m&keys=16841&currency=GBP&from=18&to=3
	    
	   // Dom.get("pie_options").style.display='none';
	    var plot_url='plot.php?tipo='+tipo+'&category='+category+'&period='+period+'&keys='+keys+'&from='+from+'&to='+to;
	    alert(plot_url);


	    //Dom.get("the_plot").width="100%";
	    plot_code=tipo+'_'+category+'_'+period;

        if(from==100)
        from='∀';
	    Dom.get("plot_interval").innerHTML=from+':'+to;

	    Dom.get("plot_category").innerHTML=category_labels[category];
	    Dom.get("plot_period").innerHTML=period_labels[period];
	    Dom.get("plot_options").style.display='';
	}


	
        Dom.get("the_plot").src=plot_url; 
	old_selected=Dom.getElementsByClassName('selected', 'span', 'plot_chooser');
	for( var i in old_selected ){
	    Dom.removeClass(old_selected[i],'selected');
	}
	Dom.addClass(o,'selected');

    
}


YAHOO.util.Event.onContentReady("plot_period_menu", function () {
	 var oMenu = new YAHOO.widget.Menu("plot_period_menu", { context:["plot_period","br", "tr"]  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 YAHOO.util.Event.addListener("plot_period", "click", oMenu.show, null, oMenu);
    });
YAHOO.util.Event.onContentReady("plot_category_menu", function () {
	 var oMenu = new YAHOO.widget.Menu("plot_category_menu", { context:["plot_category","br", "tr"]  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 YAHOO.util.Event.addListener("plot_category", "click", oMenu.show, null, oMenu);
    });
YAHOO.util.Event.onContentReady("plot_interval_menu", function () {
	 var oMenu = new YAHOO.widget.Menu("plot_interval_menu", { context:["plot_interval","br", "tr"]  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 YAHOO.util.Event.addListener("plot_interval", "click", oMenu.show, null, oMenu);
    });
YAHOO.util.Event.onContentReady("pie_interval_menu", function () {
	 var oMenu = new YAHOO.widget.Menu("pie_interval_menu", { context:["pie_interval","br", "tr"]  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 YAHOO.util.Event.addListener("pie_interval", "click", oMenu.show, null, oMenu);
    });