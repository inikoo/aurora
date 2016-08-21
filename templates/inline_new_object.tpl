<div id="inline_form" style="float:right" class="hide" data-dropdown_select_metadata="{if isset($data.dropdown_select_metadata)}{$data.dropdown_select_metadata}{/if}" field_id="{$data.field_id}" >
<span id="invalid_msg" class="hide">{t}Invalid value{/t}</span> 

<span id="fields" object='{$data.object}' key='' parent='{$data.parent}' parent_key='{$data.parent_key}' >
<span id="inline_new_object" class=" hide"   object='{$data.object}' key='' parent='{$data.parent}' parent_key='{$data.parent_key}' field='{$data.field_id}' >
<span id="{$data.field_id}_container" class="value " _required="true" field_type='{$data.field_edit}' field='{$data.field_id}'  server_validation=''> 
{$data.field_label} 

<input id="{$data.field_id}" class="{$data.field_edit}_input_field inline_input" value="" placeholder="{if isset($data.placeholder)}{$data.placeholder}{/if}">
{if $data.field_edit=='time'}
<input type="hidden" id="{$data.field_id}_date"  value="{$data.date}">
{/if}


<div id="inline_new_object_results_container" class="search_results_container hide" style="width:400px;">
		
		<table id="inline_new_object_results" border="0" style="background:white;font-size:90%" >
			<tr class="hide" style="" id="inline_new_object_search_result_template" field="" value="" item_historic_key="" formatted_value="" onClick="select_inline_new_object_option(this)">
				<td class="code" style="padding-left:5px;"></td>
				<td class="label" style="padding-left:5px;"></td>
				
			</tr>
		</table>
	
</div>

<i id="{$data.object}_save"  class=" fa fa-cloud fa-fw save"></i> </span> 
</span> 
</span>
</div>
<script>

$('#{$trigger}').on("click", function() {

    toggle_inline_new_object_form('{$trigger}')

});



$("#{$data.field_id}").keypress(function(e) {

    if (!$('#{$data.object}_save').hasClass('fa-cloud')) {
        e.preventDefault(e);
        return;
    }
});


$("#{$data.field_id}").on("input propertychange", function(evt) {

    if (!$('#{$data.object}_save').hasClass('fa-cloud')) {
        evt.preventDefault(ent);
        return;
    }
    var delay = 50;
    if (window.event && event.type == "propertychange" && event.propertyName != "value") return;
 
 if('{$data.field_edit}'=='dropdown'){
  delayed_on_change_inline_new_object_dropdown_field($(this), delay)
  }else{
 delayed_on_change_field($(this), delay)
 }
 
 
});

$("#{$data.object}_save").on("click", function(evt) {
    save_inline_new_object('{$trigger}')
});

$("#inline_new_object_msg").on("click", function(evt) {
   $("#inline_new_object_msg").html('').removeClass('success error')
   
});


function delayed_on_change_inline_new_object_dropdown_field(object, timeout) {

    window.clearTimeout(object.data("timeout"));

    object.data("timeout", setTimeout(function() {

        get_inline_new_object_items_select()
    }, timeout));
}

function get_inline_new_object_items_select() {

var form_element=$('#inline_form');


      form_element.removeClass('invalid')
  input_element=$('#'+form_element.attr('field_id'))
  

    var request = '/ar_find.php?tipo=find_object&query=' + fixedEncodeURIComponent(input_element.val()) + '&scope='+jQuery.parseJSON(atob(form_element.data("dropdown_select_metadata"))).scope + '&metadata=' + atob(form_element.data("dropdown_select_metadata")) + '&state=' + JSON.stringify(state)

    $.getJSON(request, function(data) {


        if (data.number_results > 0) {
            $('#inline_new_object_results_container').removeClass('hide').addClass('show')
            input_element.removeClass('invalid')

        } else {



            $('#inline_new_object_results_container').addClass('hide').removeClass('show')

            //console.log(data)
            if (input_element.val() != '') {
                input_element.addClass('invalid')
            } else {
                input_element.removeClass('invalid')
            }

            $('#save_inline_new_object').attr('item_key', '')
            $('#save_inline_new_object').attr('item_historic_key', '')


        }


        $("#inline_new_object_results .result").remove();

        var first = true;

        for (var result_key in data.results) {

            var clone = $("#inline_new_object_search_result_template").clone()
            clone.prop('id', 'inline_new_object_result_' + result_key);
            clone.addClass('result').removeClass('hide')
            clone.attr('value', data.results[result_key].value)
            clone.attr('item_historic_key', data.results[result_key].item_historic_key)

            clone.attr('formatted_value', data.results[result_key].formatted_value)
            // clone.attr('field', field)
            if (first) {
                clone.addClass('selected')
                first = false
            }

            clone.children(".label").html(data.results[result_key].description)
            clone.children(".code").html(data.results[result_key].code)

            $("#inline_new_object_results").append(clone)


        }

    })



}

function select_inline_new_object_option(element){
    
  $('#{$data.field_id}').val($(element).attr('formatted_value'))
              $('#inline_new_object_results_container').addClass('hide').removeClass('show')

   on_changed_value('{$data.field_id}', $(element).attr('formatted_value'))
    
}


</script>