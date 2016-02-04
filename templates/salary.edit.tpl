 	{if isset($salary.data.payday)}{assign "payday" $salary.data.payday}{else}{assign "payday" ""}{/if} 
 	{if isset($salary.data.frequency)}{assign "frequency" $salary.data.frequency}{else}{assign "frequency" ""}{/if} 
 	{if isset($salary.data.type)}{assign "type" $salary.data.type}{else}{assign "type" ""}{/if} 
 	{if isset($salary.data.amount)}{assign "amount" $salary.data.amount}{else}{assign "amount" ""}{/if} 
 	{if isset($salary.data.amount_weekdays)}{assign "amount_weekdays" $salary.data.amount_weekdays}{else}{assign "amount_weekdays" ""}{/if} 
 	{if isset($salary.data.amount_saturday)}{assign "amount_saturday" $salary.data.amount_saturday}{else}{assign "amount_saturday" ""}{/if} 
 	{if isset($salary.data.amount_sunday)}{assign "amount_sunday" $salary.data.amount_sunday}{else}{assign "amount_sunday" ""}{/if} 


<input id="{$field.id}" type="hidden" class="salary_input_field" value="{$field.value}" has_been_valid="0" />
<div id="salary" class="salary hide">
	<table border="0">
		<tr>
			<td class="label"> {t}Frequency{/t} </td>
			<td class="header_field salary_frequency inline_options medium"> 
			<span id="salary_frequency_monthy" value="monthy" class="{if  $frequency=='monthy' or $frequency==''  }selected{/if}">{t}Monthly{/t}</span> 
			<span id="salary_frequency_weekly" value="weekly" class="{if $frequency=='weekly'}selected{/if}">{t}Weekly{/t}</span> </td>
			<td colspan="2"> <i id="{$field.id}_save_button" class="fa fa-cloud  save " onclick="save_field('{$state._object->get_object_name()}','{$state.key}','{$field.id}')"></i> <span id="{$field.id}_msg" class="msg"></span> </td>
		</tr>
	<tr id="monthy_pay_day"  class=" pay_day monthy {if !($frequency=='monthy' or $frequency=='')}hide{else}{/if}" >	
		
		<td  > {t}Pay day{/t} </td>
		<td colspan="3"> 
		<input id="pay_day_day_of_the_month" class="salary_input_field potentially_valid day_of_the_month  " max="31" maxlength="2" value="{$payday}" >  {t}th of the month{/t} </td>
	</tr>
	<tr id="weekly_pay_day"   class="pay_day weekly {if isset($frequency) and $frequency!='weekly'}hide{else}hide{/if}" >
		<td class="label"> {t}Pay day{/t} </td>
		<td id="salary_pay_day_of_week" colspan="3" class="inline_options salary_pay_day_of_week small">
		 <span value="1" class="{if isset($salary.data.payday) and $frequency=='weekly' and $salary.data.payday==1 }selected{else}selected{/if}">{t}Mon{/t}</span> 
		 <span value="2" class="{if isset($salary.data.payday) and $frequency=='weekly' and $salary.data.payday==2 }selected{/if}">{t}Tue{/t}</span> 
		 <span value="3" class="{if isset($salary.data.payday) and $frequency=='weekly' and $salary.data.payday==3 }selected{/if}">{t}Wed{/t}</span> 
		 <span value="4" class="{if isset($salary.data.payday) and $frequency=='weekly' and $salary.data.payday==4 }selected{/if}">{t}Thu{/t}</span> 
		 <span value="5" class="{if isset($salary.data.payday) and $frequency=='weekly' and $salary.data.payday==5 }selected{/if}">{t}Fri{/t}</span> 
		 <span value="6" class="{if isset($salary.data.payday) and $frequency=='weekly' and $salary.data.payday==6 }selected{/if}">{t}Sat{/t}</span> 
		 <span value="7" class="{if isset($salary.data.payday) and $frequency=='weekly' and $salary.data.payday==7 }selected{/if}">{t}Sun{/t}</span> 
		 </td>
		<tr>
			<td> {t}Type{/t} </td>
			<td colspan="3" id="salary_type" class="inline_options salary_type "> 
			<span value="fixed_month" id="type_fixed_month"  class="{if !($frequency=='monthy' or $frequency=='')  }hide{else}{/if} {if  $type=='fixed_month' or  $type==''}selected{/if} ">{t}Fixed{/t} ({t}per month{/t})</span> 
			<span value="fixed_week" id="type_fixed_week"  class="pay_day monthy {if $type=='' or  $frequency=='monthy'}hide{/if} {if  $type=='fixed_week' }selected{/if}  ">{t}Fixed{/t} ({t}per week{/t})</span> 
			<span value="prorata_hour" class="{if  $type=='prorata_hour' }selected{/if} "  id="type_prorata_hour">{t}Pro rata{/t} ({t}per hour{/t})</span> 
			</td>
		</tr>
		<tr>
			<td class="label"> {t}Amount{/t} ({$account->get('Account Currency')}) </td>
			<td colspan="3"> 
			<table border=0>
				<tr id="compressed_salary_amount_components"  class="{if $amount==''}hide{/if}">
					<td colspan="3">
					<i  id="expand_rate_components" class="fa fa-expand fa-fw  {if isset($salary.type) and $salary.type!=prorata_hour}hide{else}hide{/if} " "></i>
					<input id="salary_amount" class="salary_input_field amount potentially_valid" value="{$amount}"></td>
				</tr>
				<tbody id="expanded_salary_amount_components" class="{if $amount!=''}hide{/if}">

				<tr>
					<td class="compress"><i  id="compress_rate_components" class="fa fa-compress fa-fw" o></i></td>
					<td class="small_label">{t}Weekdays{/t}</td>
					<td>
					<input id="salary_amount_weekdays" class="salary_input_field amount potentially_valid" value="{$amount_weekdays}"></td>
				</tr>
				<tr>
					<td></td>
					<td class="small_label">{t}Saturday{/t}</td>
					<td>
					<input id="salary_amount_saturday" class="salary_input_field amount potentially_valid" value="{$amount_saturday}"></td>
				</tr>
				<tr>
					<td></td>
					<td class="small_label">{t}Sunday{/t}</td>
					<td>
					<input id="salary_amount_sunday" class="salary_input_field amount potentially_valid" value="{$amount_sunday}"></td>
					<tr>
					</table>
					</td>
				</tr>
				</tbody>
			</table>
		</div>


<script>


$('.salary_frequency').on('click', 'span', function() {


    $('.salary_frequency span').removeClass('selected')
    $(this).addClass('selected')
    $('#monthy_pay_day').addClass('hide')
    $('#weekly_pay_day').addClass('hide')

    var value = $(this).attr('value')

    if (value == 'monthy') {
        $('#monthy_pay_day').removeClass('hide')
        $('#type_fixed_month').removeClass('hide')
        $('#type_fixed_week').addClass('hide')

        if ($('#type_fixed_week').hasClass('selected')) {
            $('#type_fixed_week').removeClass('selected')
            $('#type_fixed_month').addClass('selected')

        }


    } else if (value == 'weekly') {
        $('#weekly_pay_day').removeClass('hide')
        $('#type_fixed_month').addClass('hide')
        $('#type_fixed_week').removeClass('hide')

        if ($('#type_fixed_month').hasClass('selected')) {
            $('#type_fixed_month').removeClass('selected')
            $('#type_fixed_week').addClass('selected')

        }


    }



    validate_salary()



});


$('.salary_pay_day_of_week').on('click', 'span', function() {

    $('.salary_pay_day_of_week span').removeClass('selected')
    $(this).addClass('selected')
    validate_salary()

});

$('.salary_type').on('click', 'span', function() {

    $('.salary_type span').removeClass('selected')
    $(this).addClass('selected')


    var value = $(this).attr('value')

    if (value == 'prorata_hour') {
        $('#expand_rate_components').removeClass('hide')
    } else {
        $('#expand_rate_components').addClass('hide')
        $('#compressed_salary_amount_components').removeClass('hide')
        $('#expanded_salary_amount_components').addClass('hide')
    }


    validate_salary()

});

$('#salary').on('click', '#expand_rate_components', function() {

    $('#compressed_salary_amount_components').addClass('hide')
    $('#expanded_salary_amount_components').removeClass('hide')

    validate_salary()

});

$('#salary').on('click', '#compress_rate_components', function() {

    $('#compressed_salary_amount_components').removeClass('hide')
    $('#expanded_salary_amount_components').addClass('hide')

    validate_salary()

});

 $('#salary  input.salary_input_field').each(function(i, obj) {
               
                
                  if ($(obj).hasClass('amount')) var field_type = 'amount'
    else var field_type = 'day_of_month'
                
             var component_validation = validate_field($(obj).attr('id'), $(obj).val(), field_type, true, false)
             if(component_validation.class=='invalid' && $(obj).val()==''){
             component_validation.class='potentially_valid'
             }
             
                 $(obj).removeClass('invalid potentially_valid valid').addClass(component_validation.class)

 console.log(component_validation)
 console.log($(obj).attr('id'))

            });



function validate_salary() {

    //$('#{$field.id}_save_button').removeClass('invalid valid potentially_valid')
    //$('#{$field.id}_msg').removeClass('invalid valid potentially_valid')
    //$('#{$field.id}_validation').removeClass('invalid valid potentially_valid')

   $('#{$field.id}_field').removeClass('invalid valid potentially_valid')
   
    var validation = validate_salary_components();
    $('#{$field.id}_save_button').addClass('fa-cloud').removeClass('fa-spinner fa-spin')

    //$('#{$field.id}_save_button').addClass(validation.class)
    //$('#{$field.id}_validation').addClass(validation.class)
    $('#{$field.id}_field').addClass(validation.class)

   // console.log(validation)
    
    
      process_validation(validation, '{$field.id}')
    
    if(validation.class=='valid'){
        var salary=process_salary()
        
       
    }else{
    var salary='invalid';
    }
    
     $('#{$field.id}').val(salary)
    
    
    //console.log(salary)
   //  console.log($('#{$field.id}_value').html())
      if (salary != $('#{$field.id}_value').html()) {
        $('#{$field.id}_field').addClass('changed')
        var changed = true;
    } else {
        $('#{$field.id}_field').removeClass('changed')
        var changed = false;
    }

}



function validate_salary_components() {

    var validation = {
        class: 'valid',
        type: ''
    }
    if ($('#salary_frequency_monthy').hasClass('selected')) {
        var pay_day_day_of_the_month = $('#pay_day_day_of_the_month');
        if (pay_day_day_of_the_month.hasClass('invalid')) {
            return {
                class: 'invalid',
                type: ''
            }
        } else if (pay_day_day_of_the_month.hasClass('potentially_valid')) {
            validation = {
                class: 'potentially_valid',
                type: ''
            }
        }
    }


    if ($('#compressed_salary_amount_components').hasClass('hide')) {

        var salary_amount_extended_components = ['#salary_amount_weekdays', '#salary_amount_saturday', '#salary_amount_sunday']
        for (var x in salary_amount_extended_components) {
            //   console.log(salary_amount_extended_components[x])
            var salary_amount = $(salary_amount_extended_components[x]);
            if (salary_amount.hasClass('invalid')) {
                
                return {
                    class: 'invalid',
                    type: ''
                }
            } else if (salary_amount.hasClass('potentially_valid')) {
                validation = {
                    class: 'potentially_valid',
                    type: ''
                }
            }


        }



    } else {

        var salary_amount = $('#salary_amount');
        if (salary_amount.hasClass('invalid')) {

            return {
                class: 'invalid',
                type: ''
            }
        } else if (salary_amount.hasClass('potentially_valid')) {
            validation = {
                class: 'potentially_valid',
                type: ''
            }
        }
    }
    //console.log(validation)
    return validation;
}

$('#salary').on('input propertychange', '.salary_input_field', function() {


    var delay = 10;
    if (window.event && event.type == "propertychange" && event.propertyName != "value") return;
    delayed_on_change_salary_field($(this), delay)

});

function delayed_on_change_salary_field(field_object, delay) {
    var new_value = field_object.val()
   
    window.clearTimeout(field_object.data("timeout"));
    
    field_object.data("timeout", setTimeout(function() {
     
        on_changed_salary_value(field_object, new_value)
    }, delay));
}

function on_changed_salary_value(field_object, new_value) {
 
    $('#{$field.id}_save_button').removeClass('fa-cloud').addClass('fa-spinner fa-spin')

    if (field_object.hasClass('amount')) var field_type = 'amount'
    else var field_type = 'day_of_month'

    var component_validation = validate_field('{$field.id}', new_value, field_type, true, false)
    field_object.removeClass('invalid potentially_valid valid').addClass(component_validation.class)

    validate_salary()
   
}

function process_salary() {
 var amount='';
 var amount_weekdays='';
 var amount_saturday='';
 var amount_sunday='';
 
    if ($('#salary_frequency_monthy').hasClass('selected')) {

        var frequency = 'monthy'
        var payday = $('#pay_day_day_of_the_month').val()

    } else {
        var frequency = 'weekly'

        //console.log($('#salary_pay_day_of_week span.selected'))
        var payday = $('#salary_pay_day_of_week span.selected').attr('value')
    }

        var type = $('#salary_type span.selected').attr('value')

var salary={
        'metadata': {

        },
        'data': {
            'frequency': frequency,
            'payday': payday,
            'type':type
           
        }
    }

 if ($('#compressed_salary_amount_components').hasClass('hide')) {
 
      
         salary['data']['amount_weekdays']=$('#salary_amount_weekdays').val()
    salary['data']['amount_saturday']=$('#salary_amount_saturday').val()
    salary['data']['amount_sunday']=$('#salary_amount_sunday').val()

 }else{
 
    salary['data']['amount']=$('#salary_amount').val()

 }
  

   // console.log(salary)

    return JSON.stringify(salary)

}


</script>