<table border="0" id="webpage_related_products" class="{if $mode=='edit'}hide{/if}  " style="margin-bottom:20px"   >
	
	<tr class="add_new_webpage_tr">
<td></td>
		<td class="aright padding_right_20 "><i id="Webpage_Related_Products_save_button" onclick="save_this_related_products_lists()" class="fa  fa-cloud save {if $mode=='new'}hide{/if} "></i></td>
	</tr>
	
	<tbody id="webpage_related_products_links" >
		{foreach from=$data.links item=webpage_data} 
		<tr class="webpage_tr">
			<td style="width:20%"></td>
			<td> <i class="fa fa-trash button margin_right_10" aria-hidden="true" onclick="remove_webpage_related_product(this)"></i>
			<input type="hidden" class="related_products_list_value product_id" value="{$webpage_data.product_id}">
			<span class="Webpage_Code">{$webpage_data.code}</span> </td>
		</tr>
		{/foreach} 
	</tbody>
	<tbody id="related_products_manual_fields">
		<tr id="new_related_products_new_product_clone" class="webpage_tr  hide">
			<td style="width:20%"></td>
			<td> <i class="fa fa-trash-o button margin_right_10" aria-hidden="true" onclick="remove_webpage_related_product(this)"></i> 
			<input type="hidden" class="related_products_list_value product_id" value="" >
			<span class="Webpage_Code hide"></span> 
			<input class="Webpage_Code_value" value="" ovalue="" placeholder="{t}Product code{/t}" parent_key="{$data.website_key}" parent="website" scope="product_webpages">
			<div class="search_results_container">
				<table class="results" border="1">
					<tr class="hide search_result_template" field="" value="" formatted_value="" onclick="select_dropdown_webpage(this)">
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
			<td><span onclick="add_webpage_related_product()" class="button"><i class="fa fa-plus"></i> {t}Add a product{/t} </span></td>
		</tr>
	</tbody>
</table>
</div>


<script>

var original_related_product_ids = [];
$('#webpage_related_products_links  input.product_id').each(function(i, obj) {


    if (!$(obj).closest('tr').hasClass('very_discreet')) {

        if ($(obj).val() != '') {
            original_related_product_ids.push($(obj).val())
        }
    }

});
console.log(original_related_product_ids)


{if $mode=='new'}add_webpage_related_product(){/if}

function remove_webpage_related_product(element) {


        if ($(element).closest('tr').hasClass('very_discreet')) {

            $(element).closest('tr').removeClass('very_discreet')
        //    $(element).closest('tr').find('.Webpage_Code').removeClass('deleted')

        } else {
            $(element).closest('tr').addClass('very_discreet')
          //  $(element).closest('tr').find('.Webpage_Code').addClass('deleted')
        }
 
    
    on_change_related_products_list()
  
}

function delayed_on_change_related_products_webpages_dropdown_select_field(object, timeout) {
    //var field = object.attr('id');
    //var field_element = $('#' + field);
    var new_value = $(object).val()



    window.clearTimeout(object.data("timeout"));

    object.data("timeout", setTimeout(function() {

        get_related_products_webpage_dropdown_select(object, new_value)
    }, timeout));
}

function get_related_products_webpage_dropdown_select(object, new_value) {

    var parent_key = $(object).attr('parent_key')
    var parent = $(object).attr('parent')
    var scope = $(object).attr('scope')


 var current_manual_webpages = [];
    $('#webpage_related_products_links  input.product_id').each(function(i, obj) {



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

            // console.log(clone)
            results_container.find(".results").append(clone)
            //console.log(results_container.find(".results"))
            //   console.log($('#' + field + '_result_' + result_key).data('metadata'))
        }

    })


}

function select_dropdown_webpage(element) {

    $(element).closest('.webpage_tr').removeClass('in_process')
    $(element).closest('.webpage_tr').find('.fa-trash-o').removeClass('fa-trash-o').addClass('fa-trash')

    $(element).closest('td').find('.Webpage_Code').html($(element).attr('formatted_value')).removeClass('hide')
    $(element).closest('td').find('.Webpage_Code_value').remove()



    $(element).closest('td').find('.product_id').val($(element).attr('value'))


//console.log($(element).closest('td').find('.product_id'))


    $(element).closest('td').find('.search_results_container').remove()


on_change_related_products_list()

}

$("#webpage_related_products").on("input.Webpage_Code_value propertychange", function(evt) {

    var delay = 100;
    if (window.event && event.type == "propertychange" && event.propertyName != "value") return;
    delayed_on_change_related_products_webpages_dropdown_select_field($(evt.target), delay)
});


function add_webpage_related_product() {

    var clone = $('#new_related_products_new_product_clone').clone()
    clone.prop('id', '')
    clone.removeClass('hide')
    console.log(clone)
    $("#webpage_related_products_links").append(clone);
    on_change_related_products_list()

}

function save_this_related_products_lists() {
    save_field($('#fields').attr('object'), $('#fields').attr('key'), 'Webpage_Related_Products')
}


$(document).on('input propertychange', '#related_products_number_links', function(evt) {
    on_change_related_products_list()
});

function on_change_related_products_list() {

      $("#Webpage_Related_Products_save_button").closest('tr').removeClass('valid invalid')
     $("#Webpage_Related_Products_field").removeClass('changed valid invalid potentially_valid')


    var changed = check_changes_related_products_list()
     var validation = validate_related_products_list()
     

     
    if (changed) {
             $("#Webpage_Related_Products_field").addClass('changed')

      
    } else {
        if(validation=='valid'){
        validation=''
        }
    }
   
       $("#Webpage_Related_Products_field").addClass(validation)
}
function check_changes_related_products_list() {

    var changed = false;




    var current_webpage_related_products = [];
    
    
   // console.log('caca')
    
$('#webpage_related_products  input.product_id').each(function(i, obj) {
//console.log('xxx')
//console.log($(obj).val() )
        if (!$(obj).closest('tr').hasClass('very_discreet')) {

            if ($(obj).val() != '') {
                current_webpage_related_products.push($(obj).val())
            }
        }

    });
   // current_manual_webpages.sort

    if (!_.isEqual(current_webpage_related_products, original_related_product_ids)) {
        changed = true
    }

//console.log(original_related_product_ids)
console.log(changed)

    return changed

}



function validate_related_products_list() {
    var validation = 'valid';
    
    
    if($('#related_products_type').hasClass('fa-toggle-on')){
    
          var number_links = $('#related_products_number_links').val()

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
    
  
  console.log(validation)
    return validation;
}



function post_save_product_parts(data){
    $('webpage_related_products_items').html(data.update_metadata.webpage_related_products_items)
}

function refresh_related_products(element, webpage_key) {

    var scope = 'webpage'
    $(element).addClass('fa-spin')
    var request = '/ar_edit.php?tipo=refresh_webpage_related_products&object=' + scope + '&key=' + webpage_key
    $.getJSON(request, function(data) {
        $(element).removeClass('fa-spin')
        $('#auto_links').html(data.links)
        $('#related_products_last_updated').html(data.related_products_last_updated)

    })

}

function toggle_related_products_type(element) {


    if ($(element).hasClass('fa-toggle-on')) {
        $(element).removeClass('fa-toggle-on')
        $(element).addClass('fa-toggle-off')
        $('#related_products_auto_fields').addClass('hide')
        $('#related_products_manual_fields').removeClass('hide')
        $('#auto_links').addClass('hide')
        $('#webpage_related_products').removeClass('hide')

    } else {
        $(element).addClass('fa-toggle-on')
        $(element).removeClass('fa-toggle-off')
        $('#related_products_auto_fields').removeClass('hide')
        $('#related_products_manual_fields').addClass('hide')

        if ($('#webpage_related_products').attr('otype') == 'Auto') {
            $('#auto_links').removeClass('hide')
        }
        $('#webpage_related_products').addClass('hide')

    }


    on_change_related_products_list()
}

</script>





