<table border=0 id="parts_list" class="{if $mode=='edit'}hide{/if}  ">
<tr class="bold">
<td></td>
<td class="parts_per_product"></td>
<td class="parts">{t}Part{/t}</td>
<td class="notes">{t}Notes{/t}</td>
</tr>
<tbody id="parts_list_items">
{include file="parts_list_items.edit.tpl" parts_list=$parts_list} 
</tbody>
<tr id="new_part_clone" class="part_tr new in_process hide">
<td><i class="fa fa-trash-o button" aria-hidden="true" onclick="remove_part(this)"></i><input type="hidden" class="part_list_value product_part_key"  value=""  ovalue=""></td>


<td class="parts_per_products"><input class="part_list_value parts_per_product" value="1" ovalue="1" /> x </td>
<td class="parts"> 
<input type="hidden" class="part_list_value sku" value="" ovalue=""> 
<span class="Part_Reference hide"></span>
<input class="Part_Reference_value" value="" ovalue="" placeholder="{t}Part reference{/t}" parent_key="1" parent="account" scope="parts"  > 
<div  class="search_results_container" >
	<table class="results" border="1" >
		<tr class="hide search_result_template" field="" value="" formatted_value="" onclick="select_dropdown_part(this)">
			<td class="code"></td>
			<td style="width:85%" class="label"></td>
		</tr>
	</table>
</div>
</td>



<td class="notes"><input  class="part_list_value note" value="" ovalue="" placeholder="{t}Note for pickers{/t}"></td>

</tr>
<tr class="add_new_part_tr">
<td colspan=3 ><span onclick="add_part()" class="button">{t}Add a part{/t} <i  class="fa fa-plus"></i></span></td>
<td class="aright padding_right_20"><i id="Product_Parts_save_button" onclick="save_this_part_lists()" class="fa fa-cloud save {if $mode=='new'}hide{/if} "></i></td>
</tr>
</table>

<script>
{if $mode=='new'}

add_part()
{/if}

function remove_part(element) {

    if ($(element).closest('tr').hasClass('new')) {

        $(element).closest('tr').remove()


    } else {
        if ($(element).closest('tr').hasClass('very_discreet')) {

            $(element).closest('tr').removeClass('very_discreet')
            $(element).closest('tr').find('.Part_Reference').removeClass('deleted')
            $(element).closest('tr').find('.part_list_value').prop('readonly', false);

        } else {
            $(element).closest('tr').addClass('very_discreet')
            $(element).closest('tr').find('.Part_Reference').addClass('deleted')
            $(element).closest('tr').find('.part_list_value').prop('readonly', true);
        }
    }
    on_change_part_list()
}



function delayed_on_change_parts_dropdown_select_field(object, timeout) {
    //var field = object.attr('id');
    //var field_element = $('#' + field);
    var new_value = $(object).val()


/*
    key_scope = {
        type: 'dropdown_select',
        field: field_element.attr('field')
    };
*/

    window.clearTimeout(object.data("timeout"));

    object.data("timeout", setTimeout(function() {

        get_part_dropdown_select(object, new_value)
    }, timeout));
}

function get_part_dropdown_select(object, new_value) {

    var parent_key = $(object).attr('parent_key')
    var parent = $(object).attr('parent')
    var scope = $(object).attr('scope')



    var request = '/ar_find.php?tipo=find_object&query=' + fixedEncodeURIComponent(new_value) + '&scope=' + scope + '&parent=' + parent + '&parent_key=' + parent_key + '&state=' + JSON.stringify(state)
    //console.log(request)
    $.getJSON(request, function(data) {

        var results_container = $(object).closest('td.parts').find('.search_results_container')



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

            // console.log(clone)
            results_container.find(".results").append(clone)
            //console.log(results_container.find(".results"))
            //   console.log($('#' + field + '_result_' + result_key).data('metadata'))
        }

    })


}

function select_dropdown_part(element) {

$(element).closest('.part_tr').removeClass('in_process')
    $(element).closest('.part_tr').find('.fa-trash-o').removeClass('fa-trash-o').addClass('fa-trash')

    $(element).closest('td.parts').find('.Part_Reference').html($(element).attr('formatted_value')).removeClass('hide')
    $(element).closest('td.parts').find('.Part_Reference_value').remove()

    $(element).closest('td.parts').find('.sku').val($(element).attr('value'))


    $(element).closest('td.parts').find('.search_results_container').remove()


on_change_part_list()

}

$("#parts_list_items").on("input.Part_Reference_value propertychange", function(evt) {


    var delay = 100;
    if (window.event && event.type == "propertychange" && event.propertyName != "value") return;
    delayed_on_change_parts_dropdown_select_field($(evt.target), delay)
});


function add_part() {

    var clone = $('#new_part_clone').clone()
    clone.prop('id', '')
    clone.removeClass('hide')
    console.log(clone)
    $("#parts_list_items").append(clone);

}

function save_this_part_lists() {
    save_field($('#fields').attr('object'), $('#fields').attr('key'), 'Product_Parts')
}


$(document).on('input propertychange', '.part_list_value', function(evt) {
    on_change_part_list()
});

function on_change_part_list(element) {

    var changed = check_changes_part_list()
     var validation = validate_part_list()
     $("#Product_Parts_field").removeClass('valid invalid')
     
    if (changed) {
        $("#Product_Parts_field").addClass('changed')
        $("#Product_Parts_field").addClass(validation)
    } else {
        $("#Product_Parts_field").removeClass('changed')
    }
   
    
}


function check_changes_part_list() {

    var changed = false;
    $('#parts_list  input.part_list_value').each(function(i, obj) {
        if ($(obj).val() != $(obj).attr('ovalue')) {
            changed = true;
            return false;
        }
    });

    $('#parts_list_items  tr.part_tr').each(function(i, obj) {

        if ($(obj).hasClass('very_discreet')) {
            changed = true;
            return false;
        }else{
            if($(obj).hasClass('new') && !$(obj).hasClass('in_process')){
               changed = true;
            return false;
            }
        
        }
    })

    return changed

    }


function validate_part_list() {
    var validation = 'valid';
    
      $('#parts_list_items  tr.part_tr').each(function(i, obj) {

      var ratio = $(obj).find('.parts_per_product').val()
      
      if(ratio!=''){
      ratio_validation=validate_signed_integer(ratio,1000000)
      
      if(ratio_validation.class=='invalid'){
      validation = 'invalid';
      }
      console.log(ratio_validation)
      
      }
      
      
    })
    
    return validation;
}

function post_save_product_parts(data){
    $('parts_list_items').html(data.update_metadata.parts_list_items)
}

</script>