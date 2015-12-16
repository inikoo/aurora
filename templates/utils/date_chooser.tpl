<div id="date_chooser" class="date_chooser" ">
	<div onClick="toogle_interval_dialog()" id="interval" class="interval {if  $period=='interval'}selected{/if}" ><img src="/art/icons/mini-calendar_interval.png"  /> {t}Interval{/t}</div> 
	<div onClick="toogle_date_dialog()"  id="date" class="day {if  $period=='date'}selected{/if}" ><img src="/art/icons/mini-calendar.png"  /> {t}Day{/t}</div> 
	<div onclick="change_period('ytd')"period="ytd" id="ytd" class="fixed_interval {if  $period=='ytd'}selected{/if}" >{t}YTD{/t}</div> 
	<div onclick="change_period('mtd')"period="mtd" id="mtd" class="fixed_interval {if  $period=='mtd'}selected{/if}" >{t}MTD{/t}</div> 
	<div onclick="change_period('wtd')"period="wtd" id="wtd" class="fixed_interval {if  $period=='wtd'}selected{/if}" >{t}WTD{/t}</div> 
	<div onclick="change_period('today')" period="today" id="today" class="fixed_interval {if  $period=='today'}selected{/if}" >{t}Today{/t}</div> 
	<div onclick="change_period('yesterday')" period="yesterday" id="yesterday" class="fixed_interval {if  $period=='yesterday'}selected{/if}" >{t}Y'day{/t}</div> 
	<div onclick="change_period('last_w')" period="last_w" id="last_w" class="fixed_interval {if  $period=='last_w'}selected{/if}" >{t}Last W{/t}</div> 
	<div onclick="change_period('last_m')" period="last_m" id="last_m" class="fixed_interval {if  $period=='last_m'}selected{/if}" >{t}Last M{/t}</div> 
	<div onclick="change_period('1w')" period="1w" id="1w" class="fixed_interval {if  $period=='1w'}selected{/if}" >{t}1W{/t}</div> 
	<div onclick="change_period('10d')" period="10d" id="10d" class="fixed_interval {if  $period=='10d'}selected{/if}" >{t}10d{/t}</div> 
	<div onclick="change_period('1m')" period="1m" id="1m" class="fixed_interval {if  $period=='1m'}selected{/if}" >{t}1m{/t}</div> 
	<div onclick="change_period('1q')" period="1q" id="1q" class="fixed_interval {if  $period=='1q'}selected{/if}" >{t}1q{/t}</div> 
	<div onclick="change_period('1y')" period="1y" id="1y" class="fixed_interval {if  $period=='1y'}selected{/if}" >{t}1Y{/t}</div> 
	<div onclick="change_period('3y')" period="3y" id="3y" class="fixed_interval {if  $period=='3y'}selected{/if}" >{t}3Y{/t}</div> 
	<div onclick="change_period('all')" period="all"  id="all" class="fixed_interval {if  $period=='all'}selected{/if}" >{t}All{/t}</div>
</div>

<div  >
	<input id="select_date" type="hidden" value="{$from}" has_been_valid="0" />

	<input id="select_interval_from" type="hidden" value="{$from_mmddyy}" has_been_valid="0" />

	<input id="select_interval_to" type="hidden" value="{$to_mmddyy}" has_been_valid="0" />


	<input id="select_date_time" type="hidden" value="" />

    <div id="select_date_control_panel" class="hide">
	<div id="select_date_datepicker" class="datepicker" style="float:left">
	</div>
	<div class="date_chooser_form">
		<div class="label">{t}Date{/t}</div>
		<input id="select_date_formated" class="" value="{$from_locale}" />
		<i onclick="submit_date()" id="select_date_save" class="fa button fa-play save"></i> 
	</div>
	<div style="clear:both"></div>
    </div>


 <div id="select_interval_control_panel" class="hide">
	<div id="select_interval_datepicker" class="datepicker" style="float:left">
	</div>
	<div class="date_chooser_form">
		<div class="label from">{t}From{/t}</div>
		<input id="select_interval_from_formated" class="" value="{$from_locale}" readonly/>
		<div class="label until">{t}Until{/t}</div>
		<input id="select_interval_to_formated" class="" value="{$to_locale}" readonly/>
		<i onclick="submit_interval()" id="select_interval_save" class="fa button fa-play save"></i> 
	</div>
	<div style="clear:both"></div>
    </div>

	<div style="clear:both"></div>


</div>

<script>

    function submit_interval() {
        if ($('#select_interval_save').hasClass('valid')) {
            change_period('interval')
        }

    }

    function submit_date() {
        if ($('#select_date_save').hasClass('valid')) {
            change_period('date')
        }

    }


    $(function() {
        $("#select_date_datepicker").datepicker({
            showOtherMonths: true,
            selectOtherMonths: true,
            defaultDate: new Date("{$from}"),
            altField: "#select_date",
            altFormat: "yy-mm-dd",
            onSelect: function() {
                $('#select_date').change();
                $('#select_date_formated').val($.datepicker.formatDate("dd-mm-yy", $(this).datepicker("getDate")))
                validate_date()
            }
        });



        $("#select_interval_datepicker").datepicker({

            altFormat: "yy-mm-dd",
            defaultDate: new Date("{$from}"),

            numberOfMonths: 2,
            beforeShowDay: function(date) {
                var date1 = $.datepicker.parseDate($.datepicker._defaults.dateFormat, $("#select_interval_from").val());
                var date2 = $.datepicker.parseDate($.datepicker._defaults.dateFormat, $("#select_interval_to").val());
                return [true, date1 && ((date.getTime() == date1.getTime()) || (date2 && date >= date1 && date <= date2)) ? "dp-highlight" : ""];
            },
            onSelect: function(dateText, inst) {
                var date1 = $.datepicker.parseDate($.datepicker._defaults.dateFormat, $("#select_interval_from").val());
                var date2 = $.datepicker.parseDate($.datepicker._defaults.dateFormat, $("#select_interval_to").val());
                var selectedDate = $.datepicker.parseDate($.datepicker._defaults.dateFormat, dateText);

                date_iso_formated = $.datepicker.formatDate("yy-mm-dd", $(this).datepicker("getDate"))
                date_formated = $.datepicker.formatDate("dd/mm/yy", $(this).datepicker("getDate"))

                if (!date1 || date2) {
                    $("#select_interval_from").val(dateText);
                    $("#select_interval_to").val("");
                    $("#select_interval_from_formated").val(date_formated);
                    $("#select_interval_to_formated").val('');

                    $(this).datepicker();
                } else if (selectedDate < date1) {
                    $("#select_interval_to").val($("#select_interval_from").val());
                    $("#select_interval_from").val(dateText);

                    $("#select_interval_to_formated").val($("#select_interval_from_formated").val());
                    $("#select_interval_from_formated").val(date_formated);

                    $(this).datepicker();
                } else {
                    $("#select_interval_to").val(dateText);
                    $("#select_interval_to_formated").val(date_formated);

                    $(this).datepicker();
                }



                validate_interval()
            }







        });

    })




    $('#select_date_formated').on('input', function() {

        var _moment = moment($('#select_date_formated').val(), ["DD-MM-YYYY", "MM-DD-YYYY"], 'en');


        if (_moment.isValid()) {
            var date = new Date(_moment)
        } else {
            var date = chrono.parseDate($('#select_date_formated').val())
        }

        if (date == null) {
            var value = '';
        } else {
            var value = date.toISOString().slice(0, 10)
            $("#select_date_datepicker").datepicker("setDate", date);
        }
        $('#select_date').val(value)
        $('#select_date').change();

        validate_date()

    });



    $('#select_date').on('change', function() {
        //console.log($('#select_date').val())

    });



    function validate_date() {
        $('#select_date_save').removeClass('possible_valid valid invalid')

        if ($("#select_date_formated").val() == '') {
            validation = 'possible_valid';
        } else {
            validation = 'valid';

        }
        $('#select_date_save').addClass(validation)

    }


    function validate_interval() {
        $('#select_interval_save').removeClass('possible_valid valid invalid')

        if ($("#select_interval_from_formated").val() == '' || $("#select_interval_to_formated").val() == '') {
            validation = 'possible_valid';
        } else {
            validation = 'valid';

        }
        $('#select_interval_save').addClass(validation)

    }

    function toogle_interval_dialog() {
        if ($('#select_interval_control_panel').hasClass('hide')) {
            $('#select_interval_control_panel').removeClass('hide')
            $('#select_date_control_panel').addClass('hide')
            $('#date_chooser div').removeClass('selected')
            $('#interval' ).addClass('selected')

        } else {
            $('#select_interval_control_panel').addClass('hide')

        }

    }

    function toogle_date_dialog() {
        if ($('#select_date_control_panel').hasClass('hide')) {
            $('#select_date_control_panel').removeClass('hide')
            $('#select_interval_control_panel').addClass('hide')
            $('#date_chooser div').removeClass('selected')
            $('#date' ).addClass('selected')
        } else {
            $('#select_date_control_panel').addClass('hide')

        }

    }

</script> 