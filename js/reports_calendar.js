var Event = YAHOO.util.Event;
var Dom = YAHOO.util.Dom;
var dialog_calendar;
var calendar_browser;
var dialog_choose_day;

function submit_quick_calendar_link() {





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
    from = Dom.get(calendar_id + "_in").value;
    to = Dom.get(calendar_id + "_out").value;
    location.href = link + "?tipo=f&from=" + from + "&to=" + to + extra_argument

}

function submit_choose_day() {
    extra_argument = '';
    if (Dom.get('link_extra_argument') != undefined) {
        extra_argument = Dom.get('link_extra_argument').value;
    }
    pick_date = Dom.get(calendar_id + "_pick_date").value;
    location.href = link + "?tipo=day&from=" + pick_date + extra_argument

}

function submit_quick_calendar_link() {
    tipo = this.getAttribute('period');
    dates = get_dates(tipo)

    change_period(tipo, dates.from, dates.to)

};

function submit_interval() {

    from = Dom.get(Dom.get('calendar_id').value + "_in").value;
    to = Dom.get(Dom.get('calendar_id').value + "_out").value;
    change_period('f', from, to)
    dialog_calendar.hide()

}

function submit_choose_day() {

    from = Dom.get(Dom.get('calendar_id').value + "_pick_date").value;
    to = from
    change_period('day', from, to)
    dialog_choose_day.hide()

}


function change_period(period,from,to){

   
    Dom.removeClass(Dom.getElementsByClassName('state_details', 'span', Dom.get(Dom.get('calendar_id').value + '_period_container')), 'selected');
    Dom.addClass(Dom.get('calendar_id').value+'_'+period, 'selected')
   var request = 'ar_sessions.php?tipo=change_period&period=' + period + '&parent='+Dom.get('subject').value+'&from='+from+'&to='+to;
//alert(request)

    YAHOO.util.Connect.asyncRequest('POST', request, {
        success: function(o) {
         // alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
				
				from=r.from
				to=r.to
				
				post_change_period_actions(period,from,to)
                if (r.period_label == '') {
                    Dom.setStyle('period_label_container', 'display', 'none')

                    Dom.get('period_label').html = ''

                } else {
                    Dom.setStyle('period_label_container', 'display', '')
                    Dom.get('period_label').innerHTML = r.period_label

                }

            } else{
            
            }
            
        }

    });


	

    

}

function post_change_period_actions(period,from,to){

}


function show_calendar_div() {
    Dom.setStyle(calendar_id + "_calendar_div", 'display', '');
    Dom.setStyle(calendar_id + "_show_calendar_div", 'display', 'none');
    Dom.setStyle(calendar_id + "_hide_calendar_div", 'display', '');
}

function hide_calendar_div() {
    Dom.setStyle(calendar_id + "_calendar_div", 'display', 'none');
    Dom.setStyle(calendar_id + "_show_calendar_div", 'display', '');
    Dom.setStyle(calendar_id + "_hide_calendar_div", 'display', 'none');
}


function show_other_dates(e, calendar_id) {

    region1 = Dom.getRegion(calendar_id + "_f");
    region2 = Dom.getRegion(calendar_id + "_dialog_calendar_splinter");
    var pos = [region1.left, region1.bottom]
    Dom.setXY(calendar_id + "_dialog_calendar_splinter", pos);

    dialog_calendar.show()
    dialog_choose_day.hide()

}

function show_choose_day() {

    region1 = Dom.getRegion(calendar_id + "_day");
    region2 = Dom.getRegion(calendar_id + "_dialog_calendar_date_splinter");
    var pos = [region1.left, region1.bottom]
    Dom.setXY(calendar_id + "_dialog_calendar_date_splinter", pos);
    dialog_choose_day.show()
    dialog_calendar.hide()
}



function init_calendar() {
    calendar_id = Dom.get('calendar_id').value




    YAHOO.util.Event.addListener([calendar_id + "_mtd", calendar_id + "_ytd", calendar_id + "_wtd", calendar_id + "_today", calendar_id + "_yesterday", calendar_id + "_last_w", calendar_id + "_last_m", calendar_id + "_1w", calendar_id + "_10d", calendar_id + "_1m", calendar_id + "_1q", calendar_id + "_1y", calendar_id + "_3y", calendar_id + "_all"], "click", submit_quick_calendar_link);
    dialog_calendar = new YAHOO.widget.Dialog(calendar_id + "_dialog_calendar_splinter", {
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_calendar.render();
    Event.addListener(calendar_id + "_f", "click", show_other_dates, calendar_id);


    dialog_choose_day = new YAHOO.widget.Dialog(calendar_id + "_dialog_calendar_date_splinter", {

        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_choose_day.render();
    Event.addListener(calendar_id + "_day", "click", show_choose_day, calendar_id);


    calendar_browser = new YAHOO.widget.Dialog(calendar_id + "_calendar_browser", {
        context: [calendar_id + "_show_calendar_browser", "tl", "bl"],
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    calendar_browser.render();




    Event.addListener(calendar_id + "_show_calendar_browser", "click", calendar_browser.show, calendar_browser, true);




    var inTxt = YAHOO.util.Dom.get(calendar_id + "_in"),
        outTxt = YAHOO.util.Dom.get(calendar_id + "_out"),
        dayTxt = YAHOO.util.Dom.get(calendar_id + "_pick_date"),
        inDate, outDate, interval;

    inTxt.value = "";
    outTxt.value = "";
    dayTxt.value = "";

    if (Dom.get('from') == undefined) {
        from = '';
    } else {
        from = Dom.get('from').value
    }

    if (Dom.get('to') == undefined) {
        to = '';
    } else {
        to = Dom.get('to').value
    }




    var cal = new YAHOO.example.calendar.IntervalCalendar(calendar_id + "_cal1Container", {
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




    if (from != '' && to != '') {
        var d1 = new Date(from);
        var d2 = new Date(to);


        cal.cfg.setProperty("pagedate", d1, false);
        cal.setInterval(d1, d2)

        Dom.get(calendar_id + '_in').value = from
        Dom.get(calendar_id + '_out').value = to


    }


    cal = localize_calendar(cal, Dom.get('locale').value)
    cal.render();


    var cal2 = new YAHOO.widget.Calendar(calendar_id + "_cal2Container", {
        pages: 1
    });

        Dom.get(calendar_id + '_pick_date').value = from



    cal2 = localize_calendar(cal2, Dom.get('locale').value)

    cal2.selectEvent.subscribe(function(type, args, obj) {


        var selected = args[0];
        var inDate = this.toDate(selected[0]);
        day = inDate.getDate();


        month = (inDate.getMonth() + 1);
        day = day < 10 ? "0" + day : day;
        month = month < 10 ? "0" + month : month;

        dayTxt.value = inDate.getFullYear() + "-" + month + "-" + day;

    }, cal2, true);
  if (from != '') {
 var d1 = new Date(from);
cal2.cfg.setProperty("pagedate",d1,false);

//alert( formated_date(d1,'/'))
        cal2.cfg.setProperty("selected", (d1.getMonth() + 1)+'/'+d1.getDate()+'/'+d1.getFullYear(), false);

}
    cal2.render();
    YAHOO.util.Event.addListener(calendar_id + "_submit_interval", "click", submit_interval);
    YAHOO.util.Event.addListener(calendar_id + "_submit_choose_day", "click", submit_choose_day);



}


function get_dates(tipo) {
    var from = ''
    var to = ''
    switch (tipo) {
    case ('ytd'):
        from = formated_date(new Date(new Date().getFullYear(), 0, 1))
        to = formated_date(new Date())
        break;
    case ('mtd'):
        from = formated_date(new Date(new Date().getFullYear(), new Date().getMonth(), 1))
        to = formated_date(new Date())
        break;
    case ('wtd'):
        from = new Date()
        var day = from.getDay() || 7;
        if (day !== 1) from.setHours(-24 * (day - 1));

        from = formated_date(from)
        to = formated_date(new Date())
        break;
    case ('today'):
        from = formated_date(new Date())
        to = from
        break;
    case ('yesterday'):

        from = formated_date((function() {
            this.setDate(this.getDate() - 1);
            return this
        }).call(new Date))
        to = from
        break;
    case ('last_w'):
        var curr = new Date; // get current date
        var first = curr.getDate() - curr.getDay(); // First day is the day of the month - the day of the week
        var last = first + 6; // last day is the first day + 6
        var from = new Date(curr.setDate(first));
        var to = new Date(curr.setDate(last));
        from = formated_date(from)
        to = formated_date(to)
        break;
    case ('last_m'):
        var d = new Date();
        d.setMonth(d.getMonth() - 1);
        d.setDate(1);
        from = formated_date(d)
        var d = new Date();
        d.setMonth(d.getMonth());
        d.setDate(-1);
        to = formated_date(d)
        break;
    case ('1w'):
        var d = new Date();

        d.setDate(d.getDate() - 7);
        from = formated_date(d)
        to = formated_date(new Date())
        break;
    case ('10d'):
        var d = new Date();

        d.setDate(d.getDate() - 10);
        from = formated_date(d)
        to = formated_date(new Date())
        break;
    case ('1m'):
        var d = new Date();
        d.setMonth(d.getMonth() - 1);

        from = formated_date(d)
        to = formated_date(new Date())
        break;
    case ('1q'):
        var d = new Date();
        d.setMonth(d.getMonth() - 3);

        from = formated_date(d)
        to = formated_date(new Date())
        break;
    case ('1y'):
        var d = new Date();
        d.setYear(d.getFullYear() - 1);

        from = formated_date(d)
        to = formated_date(new Date())
        break;

    case ('3y'):
        var d = new Date();
        d.setYear(d.getFullYear() - 3);

        from = formated_date(d)
        to = formated_date(new Date())
        break;
    }

    result = {
        'to': to,
        'from': from
    }

    return result

}


function formated_date(date,separator) {

if(separator==undefined)
	separator='-';

    var d = date.getDate();
    var m = date.getMonth() + 1; //Months are zero based
    var y = date.getFullYear();
    return y + separator + (m <= 9 ? '0' + m : m) + separator + (d <= 9 ? '0' + d : d)

}


YAHOO.util.Event.onDOMReady(init_calendar);
