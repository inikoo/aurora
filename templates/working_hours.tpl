	{if isset($metadata.compact_weekdays)}{assign "compact_weekdays" $metadata.compact_weekdays}{else}{assign "compact_weekdays" 1}{/if} 
	{if isset($metadata.compact_weekend)}{assign "compact_weekend" $metadata.compact_weekend}{else}{assign "compact_weekend" 1}{/if} 

	
<input id="{$field.id}" type="hidden" class="input_field " value="{$field.value}" has_been_valid="0"/>
<div class="working_hours" >
	<table border="0" style="" >
		<tr class="title">
			<td class="expand_days"></td>
			<td class="day_labels"></td>
			<td>{t}Start{/t}</td>
			<td>{t}Finish{/t}</td>
			<td style="width:280px;padding-left:20px">{t}Unpaid breaks{/t}</td>
			<td style="width:30%" id="">
			
		
		<i id="{$field.id}_save_button" class="fa fa-cloud  save " onclick="save_field('{$state._object->get_object_name()}','{$state.key}','{$field.id}')"></i> 
		<span id="{$field.id}_msg" class="msg"></span> 
		
			</td>
		</tr>

		{for $i=0 to 8}
		<tr id="day_{$i}" class="{if  ($i>=0 and $i<6) }weekday{else}weekend{/if}  {if $i==0 or $i==6}group{else}day{/if}      {if  ($i>0 and $i<6) and $compact_weekdays}hide{/if}  {if  ( $i==7 or $i==8 )and $compact_weekend}hide{/if} {if  ( $i==6 )and !$compact_weekend}hide{/if} {if  ( $i==0 )and !$compact_weekdays}hide{/if}  "  >
			<td class="expand_days">
			{if $i==0}
			<i class="fa fa-expand" onClick="expand_weekday()"></i>
			{else if $i==1}
			<i class="fa fa-compress"  onClick="compress_weekday()"></i>
            {else if $i==6}
			<i class="fa fa-expand"  onClick="expand_weekend()"></i>
			{else if $i==7}
			<i class="fa fa-compress"  onClick="compress_weekend()"></i>
			{/if}
			
			</td>
			<td class="day_labels">{$day_labels[$i]}</td>
			<td> 
			<input maxlength="5" id="wa_weekdays_start_{$i}" index='{$i}' class="start time_input_field working_hours_input_field valid" placeholder="09:00" value="{if isset($data[$i].start)}$data[$i].start{/if}" />
			</td>
			<td> 
			<input  maxlength="5" id="wa_weekdays_end_{$i}" index='{$i}' class="end time_input_field working_hours_input_field valid" placeholder="17:00" value="{if isset($data[$i].end)}$data[$i].end{/if}" />
			</td>
			<td style="padding-left:20px"> 
			<table id="breaks_{$i}" border=0>
			    
			    {if isset($data[{$i}].breaks)}
					<tr>
					<td> {t}Starting{/t}: 
					<input id="wa_weekdays_start" maxlength="5"  placeholder="12:00" class="break_input_field  time_input_field" value="{if isset($data[0].start)}$data[0].start{/if}" />
					<input id="wa_weekdays_start" maxlength="4" placeholder="30" class="break_input_field minutes_input_field" value="{if isset($data[0].start)}$data[0].start{/if}" />
					{t}minutes{/t} </td>
				</tr>
				
				
				{/if}
				
				
				
				
				<tr id="add_break_tr_{$i}">
				<td  >
				<span onClick="add_break('{$i}')"  style="color:#aaa" class="link" ><i class="fa fa-plus"></i> {t}add break{/t}</span>
				
				</td>
					</tr>
					
					
					
				
							
			
				
			</table>
			
			<table class="hide">
			<tr  id="new_break_{$i}" class="break" break_id=""  >
				<td >
		<i class="fa fa-times link delete_break" ></i> 
				 {t}Starting{/t}: 
					<input  placeholder="12:00" class="time_input_field break_input_field valid" value="" />
					<input  placeholder="30" class="minutes_input_field break_input_field valid" value="" />
					{t}minutes{/t} 
			
				
				</td>
					</tr>
				</table>	
			
			</td>
			<td></td>
		</tr>
		
		{/for}
	</table>
</div>

<script>



function add_break(i) {

    var clone = $("#new_break_" + i).clone()

    if ($('#breaks_' + i + ' tr').length == 1) {
        break_id = 0;
    } else {

        var last_tr = $('#breaks_' + i + ' tr[id^="break_"]:last');
        break_id = parseInt(last_tr.attr('break_id')) + 1
    }

    clone.prop('id', 'break_' + i + '_' + break_id);




    $("#add_break_tr_" + i).before(clone)


    $('#break_' + i + '_' + break_id).attr('break_id', break_id)

}

function working_hours_delayed_on_change_field(object, timeout) {

    var field = object.attr('id');
    var field_element = $('#' + field);
    var new_value = field_element.val()
    window.clearTimeout(object.data("timeout"));
    object.data("timeout", setTimeout(function() {
        on_changed_working_hours_value(field, new_value)
    }, timeout));
}




function break_input_delayed_on_change_field(object, timeout) {

    var new_value = object.val()



    window.clearTimeout(object.data("timeout"));
    object.data("timeout", setTimeout(function() {
        on_changed_break_value(object, new_value)
    }, timeout));
}



function on_changed_break_value(object, new_value) {




    $('#{$field.id}_save_button').removeClass('invalid valid potentially_valid')
    $('#{$field.id}_msg').removeClass('invalid valid potentially_valid')

    $('#{$field.id}_validation').removeClass('invalid valid potentially_valid')



    $('#{$field.id}_save_button').removeClass('fa-cloud').addClass('fa-spinner fa-spin')




    if (object.hasClass('minutes_input_field')) var field_type = 'minutes_in_break'
    else var field_type = 'time'



    var component_validation = validate_field('{$field.id}', new_value, field_type, false, false)




    object.removeClass('invalid potentially_valid valid').addClass(component_validation.class)





    var validation = validate_individual_times()

    tmp = validation;


    if (validation.class == 'valid') {
        validation = validate_working_hours()

        // console.log('V:'+tmp.class+' '+validation.class)
    } else {
        // console.log('V:'+tmp.class)
    }



    process_validation(validation, '{$field.id}')
}

function on_changed_working_hours_value(field, new_value) {
    // console.log(field + ' : ' + new_value)
    if (new_value != $('#' + field + '_value').html()) {
        $("#" + field + '_editor').addClass('changed')
        var changed = true;
    } else {
        $("#" + field + '_editor').removeClass('changed')
        var changed = false;
    }

    $('#{$field.id}_save_button').removeClass('invalid valid potentially_valid')
    $('#{$field.id}_msg').removeClass('invalid valid potentially_valid')

    $('#{$field.id}_validation').removeClass('invalid valid potentially_valid')


    if (changed) {
        $('#' + field + '_save_button').removeClass('fa-cloud').addClass('fa-spinner fa-spin')
        var component_validation = validate_field(field, new_value, 'time', false, false)

        $('#' + field).removeClass('invalid potentially_valid valid').addClass(component_validation.class)


    }


    var validation = validate_individual_times()
    tmp = validation
    if (validation.class == 'valid') {
        validation = validate_working_hours()

        // console.log('V:'+tmp.class+' '+validation.class)
    } else {
        // console.log('V:'+tmp.class)
    }




    process_validation(validation, '{$field.id}')
}


function validate_working_hours() {

    var valid_state = {
        class: 'valid',
        type: ''
    }

    if ($('#day_0').hasClass('hide')) {

        for (i = 1; i < 6; i++) {
            var day_validation = validate_day(i)
            if (day_validation.class == 'invalid') return day_validation
        }

    } else {

        var day_validation = validate_day('0')

        if (day_validation.class == 'invalid') {
            return day_validation
        }

        var breaks_validation = validate_breaks('0')
        console.log(breaks_validation)

        if (breaks_validation.class != 'valid') {



            return breaks_validation
        }

    }

    if ($('#day_6').hasClass('hide')) {

        for (i = 7; i < 9; i++) {
            var day_validation = validate_day(i)
            if (day_validation.class == 'invalid') return day_validation
        }

    } else {

        var day_validation = validate_day('6')
        if (day_validation.class == 'invalid') return day_validation

    }

    return valid_state

}


function validate_breaks(i) {


    if ($('#breaks_' + i + ' .break_input_field').length) {

        var valid_state = {
            class: 'valid',
            type: ''
        }

        $('#breaks_' + i + ' .break_input_field').each(function() {

            var break_start = $(this)
            var break_duration = $(this).next()


            var time_components = clean_time(break_start.val()).split(':');

            //   console.log(break_start.val())
            var d = new Date(2000, 0, 1, time_components[0], parseInt(time_components[1]) + parseInt(break_duration.val()), time_components[2])
            //    console.log(d)
            if (d.getDate() == 2) {
                valid_state = {
                    class: 'invalid',
                    type: 'wrong_break'
                }
                return false;

            }




            if ((break_start.val() != '' && break_duration.val() != '' && break_start.hasClass('valid') && break_duration.hasClass('valid'))) {


                if ($('#wa_weekdays_start_' + i).val() == '' || $('#wa_weekdays_end_' + i).val() == '') {
                    valid_state = {
                        class: 'potentially_valid',
                        type: ''
                    }
                    return false;

                } else if ($('#wa_weekdays_start_' + i).hasClass('valid') && $('#wa_weekdays_end_' + i).hasClass('valid')) {


                    var start = clean_time($('#wa_weekdays_start_' + i).val())
                    var end = clean_time($('#wa_weekdays_end_' + i).val())



                    var break_start = clean_time(break_start.val())

                    var break_end = add_minutes_to_time(break_start, break_duration.val())

                    console.log(break_end)



                    if (break_start <= start) {
                        valid_state = {
                            class: 'invalid',
                            type: 'break_before_start'
                        }
                        return false;

                    }

                    if (break_end >= end) {
                        valid_state = {
                            class: 'invalid',
                            type: 'break_ends_after_end'
                        }
                        return false;

                    }



                }


            }



        })


        //==


    } else {

        var valid_state = {
            class: 'valid',
            type: ''
        }
    }




    return valid_state




}


function validate_day(i) {

    var valid_state = {
        class: 'valid',
        type: ''
    }

    if ($('#wa_weekdays_start_' + i).val() != '' && $('#wa_weekdays_end_' + i).val() != '' && $('#wa_weekdays_start_' + i).hasClass('valid') && $('#wa_weekdays_end_' + i).hasClass('valid')) {



        var start = clean_time($('#wa_weekdays_start_' + i).val())
        var end = clean_time($('#wa_weekdays_end_' + i).val())

        if (start >= end) {
            return valid_state = {
                class: 'invalid',
                type: 'end_less_start'
            }

        }






    } else {

        return {
            class: 'potentially_valid',
            type: ''
        }
    }

    return valid_state;

}


function validate_individual_times() {

    var valid_state = {
        class: 'valid',
        type: ''
    }

    if ($('#day_0').hasClass('hide')) {
        for (i = 1; i < 6; i++) {
            var validation = get_validation_of_times_in_day(i)
            if (validation.class != 'valid') return validation
            var validation = get_validation_of_breaks_in_day(i)
            if (validation.class != 'valid') return validation


        }
    } else {
        var validation = get_validation_of_times_in_day('0')
        if (validation.class != 'valid') {

            return validation
        }

        var validation = get_validation_of_breaks_in_day('0')
        if (validation.class != 'valid') {
            //  console.log(validation)
            return validation
        }
    }

    if ($('#day_6').hasClass('hide')) {

        for (i = 7; i < 9; i++) {
            var validation = get_validation_of_times_in_day(i)
            if (validation.class != 'valid') return validation

            var validation = get_validation_of_breaks_in_day(i)
            if (validation.class != 'valid') return validation

        }

    } else {

        var validation = get_validation_of_times_in_day('6')
        if (validation.class != 'valid') return validation

        var validation = get_validation_of_breaks_in_day('6')
        if (validation.class != 'valid') return validation

    }





    return valid_state
}


function get_validation_of_breaks_in_day(i) {

    if ($('#breaks_' + i + ' .break_input_field').length) {

        var valid_state = {
            class: 'potentially_valid',
            type: ''
        }



        $('#breaks_' + i + ' .break_input_field').each(function() {

            var break_start = $(this)
            var break_duration = $(this).next()
            if (break_start.val() == '' && break_duration.val() == '') {
                 valid_state = {
                    class: 'valid',
                    type: ''
                }
                return false

            }

            if (break_start.hasClass('invalid') ) {
                 valid_state = {
                    class: 'invalid',
                    type: 'invalid_time'
                }
                return false

            }
            
            
            if ( break_duration.hasClass('invalid')) {
                valid_state = {
                    class: 'invalid',
                    type: 'invalid_break_duration'
                }
                return false

            }


            if (break_start.val() != '' && break_duration.val() != '' && break_start.hasClass('valid') && break_duration.hasClass('valid')) {
                 valid_state = {
                    class: 'valid',
                    type: ''
                }
                return false

            }

        })

    } else {

        var valid_state = {
            class: 'valid',
            type: ''
        }
    }

    return valid_state

}


function get_validation_of_times_in_day(i) {
    var valid_state = {
        class: 'potentially_valid',
        type: ''
    }

    if ($('#wa_weekdays_start_' + i).val() == '' && $('#wa_weekdays_end_' + i).val() == '') {
        return valid_state = {
            class: 'valid',
            type: ''
        }

    }
    if ($('#wa_weekdays_start_' + i).val() != '' && $('#wa_weekdays_end_' + i).val() != '' && $('#wa_weekdays_start_' + i).hasClass('valid') && $('#wa_weekdays_end_' + i).hasClass('valid')) {
        return valid_state = {
            class: 'valid',
            type: ''
        }

    }

    if ($('#wa_weekdays_start_' + i).hasClass('invalid') || $('#wa_weekdays_end_' + i).hasClass('invalid')) {
        return valid_state = {
            class: 'invalid',
            type: 'invalid_time'
        }

    }

    return valid_state

}



$(".working_hours_input_field").on("input propertychange", function(evt) {

    var delay = 10;
    if (window.event && event.type == "propertychange" && event.propertyName != "value") return;
    working_hours_delayed_on_change_field($(this), delay)
});


$('.working_hours').on('input propertychange', '.break_input_field', function() {


    var delay = 10;
    if (window.event && event.type == "propertychange" && event.propertyName != "value") return;
    break_input_delayed_on_change_field($(this), delay)

});



$('.working_hours').on('click', '.delete_break', function() {


    $(this).parent().parent().remove()

    var validation = validate_individual_times()

    tmp = validation;


    if (validation.class == 'valid') {
        validation = validate_working_hours()

        // console.log('V:'+tmp.class+' '+validation.class)
    } else {
        // console.log('V:'+tmp.class)
    }



    process_validation(validation, '{$field.id}',false)

});



function expand_weekday() {
    $('.day.weekday').removeClass('hide')
    $('.group.weekday').addClass('hide')
}

function compress_weekday() {
    $('.day.weekday').addClass('hide')
    $('.group.weekday').removeClass('hide')
}

function expand_weekend() {
    $('.day.weekend').removeClass('hide')
    $('.group.weekend').addClass('hide')
}

function compress_weekend() {
    $('.day.weekend').addClass('hide')
    $('.group.weekend').removeClass('hide')
}


</script>