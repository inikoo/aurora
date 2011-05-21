
var Event = YAHOO.util.Event;
var Dom   = YAHOO.util.Dom;
var dialog_calendar;
var calendar_browser;
function quick_link(){
tipo=this.id;

location.href=link+'?tipo='+tipo;
};

function submit_interval(){

from=Dom.get('in').value;
to=Dom.get('out').value;
location.href=link+"?tipo=f&from="+from+"&to="+to

}

function show_calendar_div(){
Dom.setStyle("calendar_div",'display','');
Dom.setStyle("show_calendar_div",'display','none');
Dom.setStyle("hide_calendar_div",'display','');
}

function hide_calendar_div(){
Dom.setStyle("calendar_div",'display','none');
Dom.setStyle("show_calendar_div",'display','');
Dom.setStyle("hide_calendar_div",'display','none');
}


function init(){
YAHOO.util.Event.addListener(["mtd","ytd","wtd","today","yesterday","last_w","last_m","1w","10d","1m","1q","1y","3y","all"], "click",quick_link);

  dialog_calendar = new YAHOO.widget.Dialog("dialog_calendar_splinter", {context:["other","tl","bl"]  ,visible : false,close:true,underlay: "none",draggable:false});
    dialog_calendar.render();
    Event.addListener("other", "click", dialog_calendar.show,dialog_calendar , true);



  calendar_browser = new YAHOO.widget.Dialog("calendar_browser", {context:["show_calendar_browser","tl","bl"]  ,visible : false,close:true,underlay: "none",draggable:false});
    calendar_browser.render();
    Event.addListener("show_calendar_browser", "click", calendar_browser.show,calendar_browser , true);
    
   var inTxt = YAHOO.util.Dom.get("in"),
        outTxt = YAHOO.util.Dom.get("out"),
        inDate, outDate, interval;

    inTxt.value = "";
    outTxt.value = "";

    var cal = new YAHOO.example.calendar.IntervalCalendar("cal1Container", {pages:2});

 cal.selectEvent.subscribe(function() {
        interval = this.getInterval();

        if (interval.length == 2) {
            inDate = interval[0];
            day=inDate.getDate();
            month=(inDate.getMonth() + 1);
            day = day < 10 ? "0" + day : day;
            month = month < 10 ? "0" + month : month;
            inTxt.value = inDate.getFullYear() + "-" + month + "-" + day;

            if (interval[0].getTime() != interval[1].getTime()) {
                outDate = interval[1];
                   day=outDate.getDate();
            month=(outDate.getMonth() + 1);
            day = day < 10 ? "0" + day : day;
            month = month < 10 ? "0" + month : month;
                
                outTxt.value = outDate.getFullYear() + "-" + month + "-" + day;
            } else {
                outTxt.value = "";
            }
        }
    }, cal, true);
    
    cal.render();


// cal2 = new YAHOO.widget.Calendar("cal2","cal2Container", {context:["other","tl","br"]  , title:"<?php echo _('Choose a date')?>:", close:true } );
// cal2.update=updateCal;
// cal2.id='2';
// cal2.render();
// cal2.update();
// cal2.selectEvent.subscribe(handleSelect, cal2, true); 
//YAHOO.util.Event.addListener("calpop2", "click", cal2.show, cal2, true);
// cal1 = new YAHOO.widget.Calendar("cal1","cal1Container", { title:"<?php echo _('Choose a date')?>:", close:true } );
// cal1.update=updateCal;
// cal1.id='1';
// cal1.render();
// cal1.update();
// cal1.selectEvent.subscribe(handleSelect, cal1, true); 
//YAHOO.util.Event.addListener("calpop1", "click", cal1.show, cal1, true);
YAHOO.util.Event.addListener("submit_interval", "click",submit_interval);



}

YAHOO.util.Event.onDOMReady(init);