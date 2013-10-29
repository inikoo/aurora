var Event = YAHOO.util.Event;
var Dom = YAHOO.util.Dom;
var dialog_calendar;
var calendar_browser;
var dialog_choose_day;
function quick_link() {


    extra_argument = '';
    if (Dom.get('link_extra_argument') != undefined) {
        extra_argument = Dom.get('link_extra_argument').value;
    }

    tipo = this.getAttribute('period');
    //alert(link + '?tipo=' + tipo + extra_argument)
    location.href = link + '?tipo=' + tipo + extra_argument;
};

function submit_interval() {
extra_argument = '';
    if (Dom.get('link_extra_argument') != undefined) {
        extra_argument = Dom.get('link_extra_argument').value;
    }
    from = Dom.get(calendar_id+"_in").value;
    to = Dom.get(calendar_id+"_out").value;
    location.href = link + "?tipo=f&from=" + from + "&to=" + to+ extra_argument

}

function submit_choose_day(){
extra_argument = '';
    if (Dom.get('link_extra_argument') != undefined) {
        extra_argument = Dom.get('link_extra_argument').value;
    }
    pick_date = Dom.get(calendar_id+"_pick_date").value;
    location.href = link + "?tipo=day&from=" + pick_date + extra_argument

}





function show_calendar_div() {
    Dom.setStyle(calendar_id+"_calendar_div", 'display', '');
    Dom.setStyle(calendar_id+"_show_calendar_div", 'display', 'none');
    Dom.setStyle(calendar_id+"_hide_calendar_div", 'display', '');
}

function hide_calendar_div() {
    Dom.setStyle(calendar_id+"_calendar_div", 'display', 'none');
    Dom.setStyle(calendar_id+"_show_calendar_div", 'display', '');
    Dom.setStyle(calendar_id+"_hide_calendar_div", 'display', 'none');
}


function show_other_dates(){
dialog_calendar.show()
}
function show_choose_day(){
dialog_choose_day.show()
}



function init_calendar() {
calendar_id=Dom.get('calendar_id').value


    YAHOO.util.Event.addListener([calendar_id+"_mtd", calendar_id+"_ytd", calendar_id+"_wtd", calendar_id+"_today", calendar_id+"_yesterday", calendar_id+"_last_w", calendar_id+"_last_m", calendar_id+"_1w", calendar_id+"_10d", calendar_id+"_1m", calendar_id+"_1q", calendar_id+"_1y", calendar_id+"_3y", calendar_id+"_all"], "click", quick_link);
    dialog_calendar = new YAHOO.widget.Dialog(calendar_id+"_dialog_calendar_splinter", {
        context: [calendar_id+"_other", "tl", "bl"],
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_calendar.render();
    Event.addListener(calendar_id+"_other", "click", show_other_dates);
    
    
     dialog_choose_day = new YAHOO.widget.Dialog(calendar_id+"_dialog_calendar_date_splinter", {
        context: [calendar_id+"_day", "tl", "bl"],
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_choose_day.render();
    Event.addListener(calendar_id+"_day", "click", show_choose_day );
    

    calendar_browser = new YAHOO.widget.Dialog(calendar_id+"_calendar_browser", {
        context: [calendar_id+"_show_calendar_browser", "tl", "bl"],
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    calendar_browser.render();
    Event.addListener(calendar_id+"_show_calendar_browser", "click", calendar_browser.show, calendar_browser, true);

    var inTxt = YAHOO.util.Dom.get(calendar_id+"_in"),
        outTxt = YAHOO.util.Dom.get(calendar_id+"_out"),
        dayTxt = YAHOO.util.Dom.get(calendar_id+"_pick_date"),
        inDate, outDate, interval;

    inTxt.value = "";
    outTxt.value = "";
dayTxt.value = "";
    var cal = new YAHOO.example.calendar.IntervalCalendar(calendar_id+"_cal1Container", {
        pages: 2
    });

    cal.selectEvent.subscribe(function() {
        interval = this.getInterval();

        if (interval.length == 2) {
            inDate = interval[0];
            day = inDate.getDate();
            month = (inDate.getMonth() + 1);
            day = day < 10 ? "0" + day : day;
            month = month < 10 ? "0" + month : month;
            inTxt.value = inDate.getFullYear() + "-" + month + "-" + day;

            if (interval[0].getTime() != interval[1].getTime()) {
                outDate = interval[1];
                day = outDate.getDate();
                month = (outDate.getMonth() + 1);
                day = day < 10 ? "0" + day : day;
                month = month < 10 ? "0" + month : month;

                outTxt.value = outDate.getFullYear() + "-" + month + "-" + day;
            } else {
                outTxt.value = "";
            }
        }
    }, cal, true);

    cal.render();


var cal2 = new YAHOO.widget.Calendar(calendar_id+"_cal2Container", {
        pages: 1
    });
    
       cal2.selectEvent.subscribe(function(type,args,obj) {
       
       
        var selected = args[0];
var inDate = this.toDate(selected[0]); 
            day = inDate.getDate();


            month = (inDate.getMonth() + 1);
            day = day < 10 ? "0" + day : day;
            month = month < 10 ? "0" + month : month;
           
            dayTxt.value = inDate.getFullYear() + "-" + month + "-" + day;
      
    }, cal2, true);
    
    
  cal2.render();
    YAHOO.util.Event.addListener(calendar_id+"_submit_interval", "click", submit_interval);
    YAHOO.util.Event.addListener(calendar_id+"_submit_choose_day", "click", submit_choose_day);



}

YAHOO.util.Event.onDOMReady(init_calendar);
