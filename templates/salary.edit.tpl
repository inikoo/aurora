 
<input id="{$field.id}" type="hidden" class="input_field " value="{$field.value}" has_been_valid="0" />
<div id="salary" class="salary hide">
	<table border="0">
		<tr>
			<td class="label"> {t}Frequency{/t} </td>
			<td class="header_field salary_frequency inline_options medium"> 
			<span value="monthy" class="{if isset($salary.frequency) and $salary.frequency=='monthy'}selected{else}selected{/if}">{t}Monthly{/t}</span> 
			<span value="weekly" class="{if isset($salary.frequency) and $salary.frequency=='weekly'}selected{else}{/if}">{t}Weekly{/t}</span> </td>
			<td colspan="2"> <i id="{$field.id}_save_button" class="fa fa-cloud  save " onclick="save_field('{$state._object->get_object_name()}','{$state.key}','{$field.id}')"></i> <span id="{$field.id}_msg" class="msg"></span> </td>
		</tr>
	<tr id="monthy_pay_day"  class="pay_day monthy {if isset($salary.frequency) and $salary.frequency!='monthy'}hide{else}{/if}" >	
		
		<td  > {t}Pay day{/t} </td>
		<td colspan="3"> 
		<input class="day_of_the_month" max="31" maxlength="2" >  {t}th of the month{/t} </td>
	</tr>
	<tr id="weekly_pay_day"   class="pay_day weekly {if isset($salary.frequency) and $salary.frequency!='weekly'}hide{else}hide{/if}" >
		<td class="label"> {t}Pay day{/t} </td>
		<td colspan="3" class="inline_options salary_pay_day_of_week small">
		 <span class="{if isset($salary.payday) and $salary.frequency=='weekly' and $salary.payday==1 }selected{else}selected{/if}">{t}Mon{/t}</span> 
		 <span class="{if isset($salary.payday) and $salary.frequency=='weekly' and $salary.payday==2 }selected{/if}">{t}Tue{/t}</span> 
		 <span class="{if isset($salary.payday) and $salary.frequency=='weekly' and $salary.payday==3 }selected{/if}">{t}Wed{/t}</span> 
		 <span class="{if isset($salary.payday) and $salary.frequency=='weekly' and $salary.payday==4 }selected{/if}">{t}Thu{/t}</span> 
		 <span class="{if isset($salary.payday) and $salary.frequency=='weekly' and $salary.payday==5 }selected{/if}">{t}Fri{/t}</span> 
		 <span class="{if isset($salary.payday) and $salary.frequency=='weekly' and $salary.payday==6 }selected{/if}">{t}Sat{/t}</span> 
		 <span class="{if isset($salary.payday) and $salary.frequency=='weekly' and $salary.payday==7 }selected{/if}">{t}Sun{/t}</span> 
		 </td>
		<tr>
			<td> {t}Type{/t} </td>
			<td colspan="3" class="inline_options salary_type "> 
			<span value="fixed_month" id="type_fixed_month"  class="{if isset($salary.frequency) and $salary.frequency!='monthy'}hide{else}{/if} {if isset($salary.type) and $salary.type=='fixed_month'}selected{else}selected{/if} ">{t}Fixed{/t} ({t}per month{/t})</span> 
			<span value="fixed_week" id="type_fixed_week"  class="pay_day monthy {if isset($salary.frequency) and $salary.frequency!='monthy'}hide{else}hide{/if}">{t}Fixed{/t} ({t}per week{/t})</span> 
			<span value="prorata_hour"  id="type_prorata_hour">{t}Pro rata{/t} ({t}per hour{/t})</span> 
			</td>
		</tr>
		<tr>
			<td class="label"> {t}Amount{/t} ({$account->get('Account Currency')}) </td>
			<td colspan="3"> 
			<table border=0>
				<tr id="compressed_salary_amount_components"  class="{if isset($salary.rate_expanded) and $rate_expanded}hide{else}{/if}">
					<td colspan="3">
					<i  id="expand_rate_components" class="fa fa-expand fa-fw  {if isset($salary.type) and $salary.type!=prorata_hour}hide{else}hide{/if} " "></i>
					<input class="amount"></td>
				</tr>
				<tbody id="expanded_salary_amount_components" class="{if isset($salary.rate_expanded) and !$rate_expanded}hide{else}hide{/if}">
				<tr>
					<td class="compress"><i  id="compress_rate_components" class="fa fa-compress fa-fw" o></i></td>
					<td class="small_label">{t}Weekdays{/t}</td>
					<td>
					<input class="amount"></td>
				</tr>
				<tr>
					<td></td>
					<td class="small_label">{t}Saturday{/t}</td>
					<td>
					<input class="amount"></td>
				</tr>
				<tr>
					<td></td>
					<td class="small_label">{t}Sunday{/t}</td>
					<td>
					<input class="amount"></td>
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
    validate_salary()
    
     var value = $(this).attr('value')
     
    if(value=='prorata_hour'){
    $('#expand_rate_components').removeClass('hide')
    
    
    
    }else{
        $('#expand_rate_components').addClass('hide')
$('#compressed_salary_amount_components').removeClass('hide')
      $('#expanded_salary_amount_components').addClass('hide')
    
    }
    
    

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




function  validate_salary(){

}

</script>