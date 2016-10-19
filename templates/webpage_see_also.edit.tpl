<table border="0" id="webpage_see_also" class="{if $mode=='edit'}hide{/if}  " style="margin-bottom:20px"  otype="{$data.type}" >
	<tr class="add_new_webpage_tr">
		<td><span>{t}Automatic{/t}</span> <i id="see_also_type" onclick="toggle_see_also_type(this)" oclass="{if $data.type=='Auto'}fa-toggle-on{else}fa-toggle-off{/if}" class="fa {if $data.type=='Auto'}fa-toggle-on{else}fa-toggle-off{/if} button padding_left_10" aria-hidden="true"></i></td>
		<td class="aright padding_right_20 "><i id="Webpage_See_Also_save_button" onclick="save_this_see_also_lists()" class="fa  fa-cloud save {if $mode=='new'}hide{/if} "></i></td>
	</tr>
	<tbody id="see_also_auto_fields" class=" {if $data.type!='Auto'}hide{/if}">
		<tr>
			<td style="width:20%">{t}Number links{/t}</td>
			<td>
			<input id="see_also_number_links" class="width_100" style="width:50px" value="{$data.number_links}" ovalue="{$data.number_links}">
			</td>
		</tr>
		<tr id="see_also_last_updated" class="{if $data.type!='Auto'}hide{/if}"  >
			<td>{t}Last updated{/t}</td>
			<td><span id="see_also_last_updated_date" class="padding_right_10">{$data.last_updated}</span> <i onclick="refresh_see_also(this,{$data.webpage_key})" class="fa fa-refresh button" aria-hidden="true"></i></td>
		</tr>
	</tbody>
	<tbody id="auto_links" class=" {if $data.type!='Auto'}hide{/if}">
		{foreach from=$data.links item=webpage_data} 
		<tr class="webpage_tr">
			<td></td>
			<td>{$webpage_data.code}</td>
		</tr>
		{/foreach} 
	</tbody>
	<tbody id="manual_links" class="{if $data.type!='Manual'}hide{/if}">
		{foreach from=$data.links item=webpage_data} 
		<tr class="webpage_tr">
			<td style="width:20%"></td>
			<td> <i class="fa fa-trash button margin_right_10" aria-hidden="true" onclick="remove_webpage(this)"></i>
			<input type="hidden" class="see_also_list_value webpage_key" value="{$webpage_data.key}">
			<span class="Webpage_Code">{$webpage_data.code}</span> </td>
		</tr>
		{/foreach} 
	</tbody>
	<tbody id="see_also_manual_fields" class="{if $data.type!='Manual'}hide{/if}">
		<tr id="new_webpage_clone" class="webpage_tr  hide">
			<td style="width:20%"></td>
			<td> <i class="fa fa-trash-o button margin_right_10" aria-hidden="true" onclick="remove_webpage(this)"></i> 
			<input type="hidden" class="see_also_list_value product_part_key" value="" ovalue="">
			<input type="hidden" class="see_also_list_value webpage_key" value="" ovalue="">
			<span class="Webpage_Code hide"></span> 
			<input class="Webpage_Code_value" value="" ovalue="" placeholder="{t}Webpage code{/t}" parent_key="{$data.website_key}" parent="website" scope="webpages">
			<div class="search_results_container">
				<table class="results" border="1">
					<tr class="hide search_result_template" field="" value="" formatted_value="" onclick="select_dropdown_see_also_webpage(this)">
						<td class="code"></td>
						<td style="width:85%" class="label"></td>
					</tr>
				</table>
			</div>
			</td>
			<td></td>
		</tr>
		<tr class="add_new_webpage_tr">
			<td></td>
			<td><span onclick="add_webpage()" class="button"><i class="fa fa-plus"></i> {t}Add a webpage{/t} </span></td>
		</tr>
	</tbody>
</table>
</div>


<script>

var original_manual_webpages = [];
$('#manual_links  input.webpage_key').each(function(i, obj) {


    if (!$(obj).closest('tr').hasClass('very_discreet')) {

        if ($(obj).val() != '') {
            original_manual_webpages.push($(obj).val())
        }
    }

});
original_manual_webpages.sort


{if $mode=='new'}add_webpage(){/if}

function remove_webpage(element) {


        if ($(element).closest('tr').hasClass('very_discreet')) {

            $(element).closest('tr').removeClass('very_discreet')
        //    $(element).closest('tr').find('.Webpage_Code').removeClass('deleted')

        } else {
            $(element).closest('tr').addClass('very_discreet')
          //  $(element).closest('tr').find('.Webpage_Code').addClass('deleted')
        }
 
    
    on_change_see_also_list()
  
}

function delayed_on_change_see_also_webpages_dropdown_select_field(object, timeout) {
    //var field = object.attr('id');
    //var field_element = $('#' + field);
    var new_value = $(object).val()



    window.clearTimeout(object.data("timeout"));

    object.data("timeout", setTimeout(function() {

        get_see_also_webpage_dropdown_select(object, new_value)
    }, timeout));
}

function get_see_also_webpage_dropdown_select(object, new_value) {

    var parent_key = $(object).attr('parent_key')
    var parent = $(object).attr('parent')
    var scope = $(object).attr('scope')


 var current_manual_webpages = [];
    $('#manual_links  input.webpage_key').each(function(i, obj) {



            if ($(obj).val() != '') {
                current_manual_webpages.push($(obj).val())
            }
        

    });


    var request = '/ar_find.php?tipo=find_object&query=' + fixedEncodeURIComponent(new_value) + '&scope=' + scope + '&parent=' + parent + '&parent_key=' + parent_key + '&state=' + JSON.stringify(state)+'&metadata='+JSON.stringify({ option:'only_online','exclude':current_manual_webpages})
  //  console.log(request)
    $.getJSON(request, function(data) {

        var results_container = $(object).closest('td').find('.search_results_container')



        if (data.number_results > 0) {
            results_container.removeClass('hide').addClass('show')
        } else {



            results_container.addClass('hide').removeClass('show')
            //  $('#' + field).val('')
            // on_changed_value(field, '')
        }


        $(" .result").remove();

        var first = true;

        for (var result_key in data.results) {

            var clone = results_container.find('.search_result_template').clone()
            //         clone.prop('id', field + '_result_' + result_key);



            clone.addClass('result').removeClass('hide search_result_template')
            clone.attr('value', data.results[result_key].value)
            clone.attr('formatted_value', data.results[result_key].formatted_value)


            //  console.log(data.results[result_key].metadata)
            clone.data('metadata', data.results[result_key].metadata)



            //    clone.attr('field', field)
            if (first) {
                clone.addClass('selected')
                first = false
            }

            clone.children(".code").html(data.results[result_key].code)

            clone.children(".label").html(data.results[result_key].description)

             //console.log(clone)
            results_container.find(".results").append(clone)
            //console.log(results_container.find(".results"))
            //   console.log($('#' + field + '_result_' + result_key).data('metadata'))
        }

    })


}

function select_dropdown_see_also_webpage(element) {


    $(element).closest('.webpage_tr').removeClass('in_process')
    $(element).closest('.webpage_tr').find('.fa-trash-o').removeClass('fa-trash-o').addClass('fa-trash')

    $(element).closest('td').find('.Webpage_Code').html($(element).attr('formatted_value')).removeClass('hide')
    $(element).closest('td').find('.Webpage_Code_value').remove()

    $(element).closest('td').find('.webpage_key').val($(element).attr('value'))


    $(element).closest('td').find('.search_results_container').remove()

on_change_see_also_list()

}

$("#webpage_see_also").on("input.Webpage_Code_value propertychange", function(evt) {

    var delay = 100;
    if (window.event && event.type == "propertychange" && event.propertyName != "value") return;
    delayed_on_change_see_also_webpages_dropdown_select_field($(evt.target), delay)
});


function add_webpage() {

    var clone = $('#new_webpage_clone').clone()
    clone.prop('id', '')
    clone.removeClass('hide')
    //console.log(clone)
    $("#manual_links").append(clone);
    on_change_see_also_list()

}

function save_this_see_also_lists() {
    save_field($('#fields').attr('object'), $('#fields').attr('key'), 'Webpage_See_Also')
}


$(document).on('input propertychange', '#see_also_number_links', function(evt) {
    on_change_see_also_list()
});

function on_change_see_also_list() {

      $("#Webpage_See_Also_save_button").closest('tr').removeClass('valid invalid')
     $("#Webpage_See_Also_field").removeClass('changed valid invalid potentially_valid')

    var changed = check_changes_see_also_list()
     var validation = validate_see_also_list()
     

     
    if (changed) {
             $("#Webpage_See_Also_field").addClass('changed')

      
    } else {
        if(validation=='valid'){
        validation=''
        }
    }
   
       $("#Webpage_See_Also_field").addClass(validation)
}

function check_changes_see_also_list() {

    var changed = false;



    if (!$('#see_also_type').hasClass($('#see_also_type').attr('oclass'))) {
        changed = true
    }


    if ($('#see_also_type').hasClass('fa-toggle-on')) {
       // console.log($('#see_also_type').attr('oclass'))
        if ($('#see_also_number_links').val() != $('#see_also_number_links').attr('ovalue')) {
            changed = true
        }

    }else{

    var current_manual_webpages = [];
    $('#manual_links  input.webpage_key').each(function(i, obj) {


        if (!$(obj).closest('tr').hasClass('very_discreet')) {

            if ($(obj).val() != '') {
                current_manual_webpages.push($(obj).val())
            }
        }

    });
 //   current_manual_webpages.sort

console.log(original_manual_webpages)
console.log(current_manual_webpages)

    if (!_.isEqual(current_manual_webpages, original_manual_webpages)) {
        changed = true
    }
    
    }

    return changed

}


function validate_see_also_list() {
    var validation = 'valid';
    
    
    if($('#see_also_type').hasClass('fa-toggle-on')){
    
          var number_links = $('#see_also_number_links').val()

        if (number_links != '') {
            number_links_validation = validate_signed_integer(number_links, 1000000)

            if (number_links_validation.class == 'invalid') {
                validation = 'invalid';
            }

        }else{
         validation = 'potentially_valid';
        }
    
    }else{
    
    
    }
    
  
  //console.log(validation)
    return validation;
}



function post_save_product_parts(data){
    $('webpage_see_also_items').html(data.update_metadata.webpage_see_also_items)
}

function refresh_see_also(element, webpage_key) {

    var scope = 'webpage'
    $(element).addClass('fa-spin')
    var request = '/ar_edit.php?tipo=refresh_webpage_see_also&object=' + scope + '&key=' + webpage_key
    $.getJSON(request, function(data) {
        $(element).removeClass('fa-spin')
        $('#auto_links').html(data.links)
        $('#see_also_last_updated_date').html(data.see_also_last_updated)

    })

}

function toggle_see_also_type(element) {


    if ($(element).hasClass('fa-toggle-on')) {
        $(element).removeClass('fa-toggle-on')
        $(element).addClass('fa-toggle-off')
        $('#see_also_auto_fields').addClass('hide')
        $('#see_also_manual_fields').removeClass('hide')
        $('#auto_links').addClass('hide')
        $('#manual_links').removeClass('hide')

    } else {
        $(element).addClass('fa-toggle-on')
        $(element).removeClass('fa-toggle-off')
        $('#see_also_auto_fields').removeClass('hide')
        $('#see_also_manual_fields').addClass('hide')

        if ($('#webpage_see_also').attr('otype') == 'Auto') {
            $('#auto_links').removeClass('hide')
        }
        $('#manual_links').addClass('hide')

    }


    on_change_see_also_list()
}

</script>





