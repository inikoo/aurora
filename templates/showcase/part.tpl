
	    
<div class="asset_profile" >
<div id="main_categories_position" >
<div class="discreet">
{if $family_data.id}{t}Family{/t} <span onClick="change_view('products/{$part->get('Store Key')}/category/{$family_data.id}')" class="id link">{$family_data.code}</span>  {/if}

</div>
</div>

	<div id="asset_data">
		<div class="data_container">
			
			<div class="data_field" >
				<h1 ><span class="Part_Unit_Description">{$part->get('Part Unit Description')}</span> <span class="Store_Product_Price">{$part->get('Price')}</span></h1>
			</div>
			
		</div>
		<div class="data_container">
			
			
		</div>
		<div style="clear:both">
		</div>
		<div class="data_container">
			<div style="min-height:80px;float:left;width:28px">
				<i class="fa fa-camera-retro"></i> 
			</div>
			
			{assign "image_key" $part->get_main_image_key()}
			<div class="wraptocenter main_image {if $image_key==''}hide{/if}" >
				
				<img src="/{if $image_key}image_root.php?id={$image_key}&size=small{else}art/nopic.png{/if}"  >
				
				</span>
				
			</div>	
			{include file='upload_main_image.tpl' object='Part'  key=$part->id class="{if $image_key!=''}hide{/if}"}

			
			
				
				
				
			
		</div>
		{include file='sticky_note.tpl' object='Category'  key=$part->id sticky_note_field='Store_Product_Sticky_Note' _object=$part}

	
		
		
		
		
		<div style="clear:both">
		</div>
	</div>
	<div id="info" style="position:relative;top:-10px">
	
	
	
	
		<div id="overviews">
		
			<table id="stock_table" border="0" class="overview">
				<tbody class="info">
				<tr  class="main ">
					
					<td class=" highlight">{$part->get('Status')} </td>
					
					<td class="aright highlight" style="font-size:200%">
					<span class="button Current_On_Hand_Stock" onClick="open_edit_stock()"  >{$part->get('Current On Hand Stock')}</span> <span class="Stock_Status_Icon">{$part->get('Stock Status Icon')}</span>
					
					</td>
					
				</tr>
				<tr >
					
						<td class="aright" colspan="2" style="padding-top:0;color:#777;font-size:90%"> <b class="Current_Stock" title="{t}Current stock{/t}">{$part->get('Current Stock')}</b> <b>-[<span class="Current_Stock_Picked" title="{t}Stock picked{/t}">{$part->get('Current Stock Picked')}</span>]</b> -(<span id="Current_Stock_In_Process" title="{t}Waiting to be picked{/t}">{$part->get('Current Stock In Process')}</span>) &rarr; <span title="{t}Available to sell{/t}" class="Current_Stock_Available">{$part->get('Current Stock Available')}</span></td>
					
				</tr>
				<tr >
				<td></td>
				<td class="aright Available_Forecast">{$part->get('Available Forecast')}</td>
				</tr>
				</tbody>
				
					<tr  class="main hide edit_controls" id="edit_stock_controls">
					
					<td   colspan=2> 
					
					<table style="width:100%">
					<tr >
					<td class="highlight Current_On_Hand_Stock" style="font-size:200%">{$part->get('Current On Hand Stock')}</td>
					<td id="stock_diff" class="acenter"> </td>
					<td id="new_stock" class="aright highlight" style="font-size:200%"></td>
					</tr>
					<tr>
					<td><i class="fa  fa-times button discreet" aria-hidden="true" title="{t}Close edit{/t}"  onClick="close_edit_stock()"></i> </td>
					<td></td>
					<td  id="saving_buttons" class="aright discreet "><span class="save">{t}Save{/t} </span><i class="fa  fa-cloud   save " aria-hidden="true" title="{t}Save{/t}" id="save_stock" onClick="save_stock()" ></i> </td>

					</tr>
					
					</table>
					
					</td>
					
				</tr>
				
			</table>
			
			<table id="locations_table" border="0" class="overview" part_sku="{$part->id}">
			
			<tr id="move_stock_tr" class="discreet button hide " style="border-bottom:1px solid #ccc" max="">
				<td colspan=2 ><span id="move_from"></span> <i class="fa fa-fw fa-caret-square-o-right " aria-hidden="true"  ></i>  <span id="move_to"></span></td> 
					
			<td class="aright" >
			<i class="fa fa-fw fa-times button discreet" aria-hidden="true" title="{t}Close{/t}" onClick="close_move()"></i>
			<input  id="move_stock_qty" style="width:80px" value="" placeholder="{t}Move stock{/t}"> 
			<i  id="move_stock" class="fa fa-fw fa-level-down button save  " aria-hidden="true" title="{t}Move from{/t}" onClick="apply_move()" ></i> 
			</td>
			
				</tr>
			<tbody id="part_locations" class="Part_Locations">
			{include file='part_locations.edit.tpl' locations_data=$part->get_locations(true)}
			</tbody>	
			
				
			</table>
			
			<table id="barcode_data" border="0" class="overview {if $part->get('Part Barcode Number')==''}hide{/if} ">
				<tr  class="main">
					<td class="label" ><i   {if $part->get('Part Barcode Key')} class="fa fa-barcode button" onClick="change_view('inventory/barcode/{$part->get('Part Barcode Key')}')"{else}  class="fa fa-barcode"{/if}   ></i></td>
					<td class="Part_Barcode_Number highlight">{$part->get('Part Barcode Number')} </td>
					<td class="barcode_labels aright {if !$part->get('Part Barcode Key')}hide{/if}" >
					<a title="{t}Stock keeping unit (Outer){/t}" href="/asset_label.php?object=part&key={$part->id}&type=package"><i class="fa fa-tag "></i></a>
					<a class="padding_left_10" title="{t}Commercial unit label{/t}" href="/asset_label.php?object=part&key={$part->id}&type=unit"><i class="fa fa-tags "></i></a>
                    </td>
					
				</tr>
				
				
			</table>
			
		</div>
	</div>
	<div style="clear:both">
	</div>
	
	
	
</div>


<script>


var movements = []

open_edit_stock()
//open_add_location()

function open_edit_stock() {

    process_edit_stock()

    $('.unlink_operations').removeClass('hide')

    $('#stock_table tbody.info').addClass('hide')
    $('#edit_stock_controls').removeClass('hide')

    $('#locations_table .formatted_stock').addClass('hide')
    $('#locations_table .stock_input').removeClass('hide')

    $('#add_location_tr').removeClass('hide')


}

function close_edit_stock() {

    $('.unlink_operations').addClass('hide')

    $('#stock_table tbody.info').removeClass('hide')
    $('#edit_stock_controls').addClass('hide')

    $('#locations_table .formatted_stock').removeClass('hide')
    $('#locations_table .stock_input').addClass('hide')

    $('#add_location_tr').addClass('hide')

    $('#locations_table  input.stock ').each(function(i, obj) {

        $(obj).val($(obj).attr('ovalue'))
        stock_changed($(obj))
    })


}

function move(element) {

    $('#saving_buttons').addClass('hide')


    if ($(element).hasClass('fa-caret-square-o-right')) {

        $('#move_from').html($(element).closest('tr').find('.location_code').html())

        $('#locations_table  .fa-unlink ').addClass('invisible')
        $('#add_location_tr').addClass('hide')

        $('#locations_table  input.stock ').prop('readonly', true)

        $(element).removeClass('super_discreet').addClass('from')



        $('#move_stock_tr').removeClass('hide').attr('max', $(element).closest('tr').find('input').val())

        var possible_to_locations = 0;
        var to;
        $('#locations_table  .move_trigger ').each(function(i, obj) {




            if (!$(obj).hasClass('from')) {

                //console.log($(obj))

                $(obj).removeClass('fa-caret-square-o-right').addClass('fa-caret-square-o-left')
                possible_to_locations++;
                to = obj
            }

        })
        //console.log(possible_to_locations)
        if (possible_to_locations == 1) {
            move(to)
        }

        $('#move_stock_qty').focus()

    } else {
        $('#move_to').html($(element).closest('tr').find('.location_code').html())
        $(element).removeClass('super_discreet').addClass('to')
$('#move_stock_qty').focus()

    }

}


function close_move() {

    $('#saving_buttons').removeClass('hide')


    $('#move_from').html('')
    $('#move_to').html('')
    $('#locations_table  .fa-unlink ').removeClass('invisible')
    $('#locations_table  input.stock ').prop('readonly', false)
    $('#move_stock_tr').removeClass('valid invalid')
    $('#move_stock_qty').val('')
    $('#add_location_tr').removeClass('hide')


    $('#locations_table .move_trigger').removeClass('fa-caret-square-o-left from to').addClass('fa-caret-square-o-right very_discreet')
    $('#move_stock_tr').addClass('hide')

}



function move_qty_changed(element) {

    var value = element.val()


    if (value == '') {
        $('#move_stock_tr').removeClass('valid invalid')
    } else {
        validation = client_validation('smallint_unsigned', false, value, '')

        if (validation.class == 'valid') {
console.log($('#locations_table  .from ').closest('tr').find('input.stock').val())


            if (parseInt(value) > parseInt(
            $('#locations_table  .from ').closest('tr').find('input.stock').val()
            )
            ) {
                validation.class = 'invalid'
            }
        }
        $('#move_stock_tr').removeClass('valid invalid').addClass(validation.class)
    }

}

function apply_move() {



    var move_qty = parseFloat($('#move_stock_qty').val())

    if (isNaN(move_qty)) return

    $('#move_stock_qty').val('');
    var from_input = $('#locations_table  .from ').closest('tr').find('input.stock')

    old_from_input = from_input.val()
    from_input.val(from_input.val() - move_qty)

    stock_changed($(from_input))

    var to_input = $('#locations_table  .to ').closest('tr').find('input.stock')
    old_to_input = to_input.val()


console.log($('#locations_table  .to ') )
 //to_input.val('cc')
    to_input.val(parseFloat(to_input.val()) + move_qty)
    stock_changed(to_input)


    movements.push({
        part_sku: $('#locations_table').attr('part_sku'),
        from_location_key: from_input.attr('location_key'),
        from_location_stock: old_from_input,
        to_location_key: to_input.attr('location_key'),
        to_location_stock: old_to_input,
        move_qty: move_qty
    })

    close_move()

}




function stock_changed(element) {
    var value = element.val()


    if (value == '') {
        value = 0
    }


    validation = client_validation('smallint_unsigned', false, value, '')

    element.closest('tr').removeClass('valid invalid').addClass(validation.class)





    if (element.attr('ovalue') != value) {

        if (validation.class == 'invalid') {
            element.closest('tr').find('.stock_change').html('')
            element.closest('tr').find('.set_as_audit').addClass('super_discreet').addClass('hide')

        } else {

            var diff = parseFloat(value) - parseFloat(element.attr('ovalue'))
            if (diff > 0) {
                diff = '+' + diff
            }

            var change = '(' + diff + ')';
            element.closest('tr').find('.stock_change').html(change)

            element.closest('tr').find('.set_as_audit').addClass('super_discreet').addClass('hide')
        }

    } else {
        element.closest('tr').find('.stock_change').html('')
        element.closest('tr').find('.set_as_audit').removeClass('hide')

    }

    process_edit_stock()

}

function process_edit_stock() {

    var total_new_stock = 0;
    var diff_up = 0
    var diff_down = 0

    var has_invalid = false;

    var set_as_audit = 0
    var disassociate = 0

    var potential_more_outs = 0;
    var editable_locations = 0;

    $('#locations_table  input.stock ').each(function(i, obj) {

        var can_move_out = true;
        var editable_location = true;

        if (!$(obj).closest('tr').find('.set_as_audit').hasClass('super_discreet')) {
            set_as_audit++;
            can_move_out = false;
            editable_location = false;



        }

        if (!$(obj).closest('tr').find('.unlink_operations i').hasClass('fa-unlink')) {
            disassociate++;
            can_move_out = false;
            editable_location = false;
        }


        if ($(obj).closest('tr').hasClass('invalid')) {
            has_invalid = true;
            can_move_out = false;
            editable_location = false;
        }



        if ($(obj).val() == 0 || $(obj).val() == '') {
            can_move_out = false;
        }

        var new_stock = $(obj).val();
        var old_stock = $(obj).attr('ovalue');

        if (new_stock == '') new_stock = 0;
        if (old_stock == '') old_stock = 0;

        new_stock = parseFloat(new_stock)
        old_stock = parseFloat(old_stock)


        var diff = new_stock - old_stock;

        if (diff > 0) {
            diff_up += diff

        } else {
            diff_down += diff
        }


        total_new_stock += new_stock



        if (can_move_out) {
            potential_more_outs++;


        } else {}

        if (editable_location) {
            editable_locations++;
            $(obj).closest('tr').find('.move_trigger').removeClass('invisible')

        } else {

            $(obj).closest('tr').find('.move_trigger').addClass('invisible')

        }

    })
    var diff_msg = '';
    //console.log(potential_more_outs)

    if (editable_locations < 2) {
        $('.move_trigger').addClass('invisible')
    }

    if (disassociate == 1) {
        diff_msg = '<i class="fa fa-unlink" aria-hidden="true"></i> '

    } else if (disassociate > 1) {
        diff_msg = disassociate + '<i class="fa fa-unlink" aria-hidden="true"></i>'
    }


    if (set_as_audit == 1) {
        diff_msg += ' <i class="fa fa-dot-circle-o" aria-hidden="true"></i> '

    } else if (set_as_audit > 1) {
        diff_msg += set_as_audit + ' <i class="fa fa-dot-circle-o" aria-hidden="true"></i>'
    }


    if (diff_down != 0) {
        diff_msg += ' (' + diff_down + ') '
    }
    if (diff_up != 0) {
        diff_msg += ' (+' + diff_up + ') '
    }

    $('#new_stock').html(total_new_stock)





    $('#stock_diff').html(diff_msg)

    if (has_invalid) {
        $('#saving_buttons').removeClass('valid').addClass('invalid')
    } else {
        $('#saving_buttons').removeClass('invalid')

        if (diff_down != 0 || diff_up != 0 || set_as_audit > 0 || disassociate > 0) {
            $('#saving_buttons').addClass('valid')
        } else {
            $('#saving_buttons').removeClass('valid')
        }
    }

}



$(document).on('input propertychange', '.min_max', function(evt) {

    if (window.event && event.type == "propertychange" && event.propertyName != "value") return;
    min_max_changed($(this))
});

$(document).on('input propertychange', '.recommended_move', function(evt) {

    if (window.event && event.type == "propertychange" && event.propertyName != "value") return;
    recommended_move_changed($(this))
});


$(document).on('input propertychange', '.stock', function(evt) {

    if (window.event && event.type == "propertychange" && event.propertyName != "value") return;
    stock_changed($(this))
});

$(document).on('input propertychange', '#move_stock_qty', function(evt) {

    if (window.event && event.type == "propertychange" && event.propertyName != "value") return;
    move_qty_changed($(this))
});


function disassociate_location(element) {


    if ($(element).hasClass('fa-unlink')) {

        $(element).removeClass('fa-unlink').addClass('fa-chain')
        $(element).closest('tr').find('.stock').val('').attr('action', 'disassociate')
        $(element).closest('tr').find('.location_code').addClass('deleted')
        $(element).closest('tr').find('.move_trigger').addClass('invisible')
        $(element).closest('tr').find('input').prop('readonly', true)
        $(element).closest('tr').find('.set_as_audit').addClass('invisible')
        $(element).closest('tr').find('.recommendations').addClass('hide')
        stock_changed($(element).closest('tr').find('.stock'))
    } else {

        $(element).addClass('fa-unlink').removeClass('fa-chain')
        $(element).closest('tr').find('.stock').val($(element).closest('tr').find('.stock').attr('ovalue')).attr('action', '')
        $(element).closest('tr').find('.location_code').removeClass('deleted')
        $(element).closest('tr').find('.move_trigger').removeClass('invisible')
        $(element).closest('tr').find('input').prop('readonly', false)
        $(element).closest('tr').find('.set_as_audit').removeClass('invisible')
        $(element).closest('tr').find('.recommendations').removeClass('hide')
        stock_changed($(element).closest('tr').find('.stock'))
    }

}

function set_as_audit(element) {

    if ($(element).hasClass('super_discreet')) {
        $(element).removeClass('super_discreet')
        $(element).closest('tr').find('input').prop('readonly', true)

    } else {
        $(element).addClass('super_discreet')
        $(element).closest('tr').find('input').prop('readonly', false)

    }

    process_edit_stock()

}


function open_add_location() {

    if ($('#add_location_label').hasClass('hide')) {
        close_add_location()
    } else {

        $('#add_location_label').addClass('hide')
        $('#add_location').removeClass('hide').focus()
        $('#save_add_location').removeClass('hide')
    }

}

function close_add_location() {

    $('#add_location_label').removeClass('hide')
    $('#add_location').addClass('hide').val('')
    $('#save_add_location').addClass('hide')

}



$("#add_location_tr").on("input propertychange", function(evt) {

    var delay = 100;
    if (window.event && event.type == "propertychange" && event.propertyName != "value") return;
    delayed_on_change_add_location_field($(this), delay)
});

function delayed_on_change_add_location_field(object, timeout) {

    window.clearTimeout(object.data("timeout"));

    object.data("timeout", setTimeout(function() {

        get_locations_select()
    }, timeout));
}

function get_locations_select() {

    $('#add_location_tr').removeClass('invalid')

    var request = '/ar_find.php?tipo=find_object&query=' + fixedEncodeURIComponent($('#add_location').val()) + '&scope=locations&state=' + JSON.stringify(state)

    $.getJSON(request, function(data) {


        if (data.number_results > 0) {
            $('#add_location_results_container').removeClass('hide').addClass('show')
        } else {



            $('#add_location_results_container').addClass('hide').removeClass('show')

            //console.log(data)
            if ($('#add_location').val() != '') {
                $('#add_location_tr').addClass('invalid')
            }

            // $('#add_location').val('')
            // on_changed_value(field, '')
        }


        $("#add_location_results .result").remove();

        var first = true;

        for (var result_key in data.results) {

            var clone = $("#add_location_search_result_template").clone()
            clone.prop('id', 'add_location_result_' + result_key);
            clone.addClass('result').removeClass('hide')
            clone.attr('value', data.results[result_key].value)
            clone.attr('formatted_value', data.results[result_key].formatted_value)
            // clone.attr('field', field)
            if (first) {
                clone.addClass('selected')
                first = false
            }

            // clone.children(".code").html(data.results[result_key].code)
            clone.children(".label").html(data.results[result_key].description)

            $("#add_location_results").append(clone)


        }

    })


}

function select_add_location_option(element) {



    $('#add_location').val($(element).attr('formatted_value'))
    $('#save_add_location').attr('location_key', $(element).attr('value'))

    $('#save_add_location').addClass('valid')
    $('#add_location_tr').addClass('valid')
    $('#add_location_results_container').addClass('hide').removeClass('show')
    //console.log($(element).attr('value'))

    //console.log($('#save_add_location').attr('location_key'))

}

function save_add_location() {


    $('#save_add_location').removeClass('fa-cloud').addClass('fa-spinner fa-spin')



    var request = '/ar_edit.php?tipo=new_part_location&object=part&part_sku='+$('#locations_table').attr('part_sku')+'&location_key=' + $('#save_add_location').attr('location_key')
    console.log(request)
    //return;
    //=====
    var form_data = new FormData();
    form_data.append("tipo", 'new_part_location')
    form_data.append("object", 'part')
    form_data.append("part_sku", $('#locations_table').attr('part_sku'))
    form_data.append("location_key", $('#save_add_location').attr('location_key'))

    var request = $.ajax({

        url: "/ar_edit.php",
        data: form_data,
        processData: false,
        contentType: false,
        type: 'POST',
        dataType: 'json'

    })

    request.done(function(data) {

        if (state.tab == 'part.stock.transactions') {
            rows.fetch({
                reset: true
            });
        }
        close_add_location()

        $('#save_add_location').addClass('fa-cloud').removeClass('fa-spinner fa-spin')


        var clone = $("#add_location_template").clone().removeClass('hide')

        clone.find(".location_info").attr('onclick', "change_view(" + data.location_link + ")")

        clone.find(".location_used_for_icon").html(data.location_used_for_icon)
        clone.find(".location_code").html(data.location_code)
        clone.find(".formatted_stock").html(data.formatted_stock)
        clone.find("._stock").addClass('stock').removeClass('_stock')
        clone.find("._move_trigger").addClass('move_trigger').removeClass('_move_trigger')



        clone.find(".stock").val(data.stock).attr('ovalue', data.stock).attr('location_key', data.location_key)



        $("#add_location_template").before(clone)



        for (var key in data.updated_fields) {

            $('.' + key).html(data.updated_fields[key])
        }


    })

    request.fail(function(jqXHR, textStatus) {});



}

function save_stock() {

    $('#save_stock').removeClass('fa-cloud').addClass('fa-spinner fa-spin')

    var parts_locations_data = []

    $('#locations_table  input.stock ').each(function(i, obj) {

        parts_locations_data.push({
            qty: $(obj).val(),
            location_key: $(obj).attr('location_key'),
            part_sku: $('#locations_table').attr('part_sku'),
            audit: ($(obj).closest('tr').find('.set_as_audit').hasClass('super_discreet') ? false : true),
            disassociate: ($(obj).closest('tr').find('.unlink_operations i').hasClass('fa-unlink') ? false : true)
        })
    })



    // used only for debug
    var request = '/ar_edit.php?tipo=edit_stock&object=part&key='+$('#locations_table').attr('part_sku')+'&parts_locations_data=' + JSON.stringify(parts_locations_data) + '&movements=' + JSON.stringify(movements)
    console.log(request)
    //return;
    //=====
    var form_data = new FormData();
    form_data.append("tipo", 'edit_stock')
    form_data.append("object", 'part')
    form_data.append("key", $('#locations_table').attr('part_sku'))
    //        form_data.append("parent_key", $('#fields').attr('parent_key'))
    form_data.append("parts_locations_data", JSON.stringify(parts_locations_data))
    form_data.append("movements", JSON.stringify(movements))

    var request = $.ajax({

        url: "/ar_edit.php",
        data: form_data,
        processData: false,
        contentType: false,
        type: 'POST',
        dataType: 'json'

    })

    request.done(function(data) {

        if (state.tab == 'part.stock.transactions') {
            rows.fetch({
                reset: true
            });
        }


        $('#save_stock').addClass('fa-cloud').removeClass('fa-spinner fa-spin')
        $('#saving_buttons').removeClass('valid')



        close_edit_stock()

        for (var key in data.updated_fields) {

            $('.' + key).html(data.updated_fields[key])
        }


    })

    request.fail(function(jqXHR, textStatus) {});

}


function open_edit_min_max(element) {

    $(element).addClass('invisible').next().removeClass('hide').find('input:first').focus()
    $(element).closest('tr').find('.stock_input').addClass('hide')
    //$(element).closest('tr').find('.location_used_for_icon').addClass('hide')
    $(element).closest('td').attr('colspan', 2)

    $(element).closest('tr').find('.unlink_operations').addClass('invisible')



}

function open_edit_recommended_move(element) {

    $(element).addClass('invisible').next().removeClass('hide').find('input:first').focus()
    $(element).closest('tr').find('.stock_input').addClass('hide')
    $(element).closest('td').attr('colspan', 2)
    $(element).closest('tr').find('.unlink_operations').addClass('invisible')



}

function close_edit_recommended_move(element) {

    var input = $(element).closest('span.edit_move').find('.recommended_move').removeClass('valid invalid')


    input.val(input.attr('ovalue'))

    $(element).closest('span.edit_move').removeClass('valid invalid')



    element = $(element).closest('span').prev()

    $(element).removeClass('invisible').next().addClass('hide')
    $(element).closest('tr').find('.stock_input').removeClass('hide')
    $(element).closest('td').attr('colspan', 1)

    $(element).closest('tr').find('.unlink_operations').removeClass('invisible')



}

function close_edit_min_max(element) {

    var min_input = $(element).closest('span.edit_min_max').find('.recommended_min').removeClass('valid invalid')
    min_input.val(min_input.attr('ovalue'))
    var max_input = $(element).closest('span.edit_min_max').find('.recommended_max').removeClass('valid invalid')
    max_input.val(max_input.attr('ovalue'))

    $(element).closest('span.edit_min_max').removeClass('valid invalid')

    element = $(element).closest('span').prev()

    $(element).removeClass('invisible').next().addClass('hide')
    $(element).closest('tr').find('.stock_input').removeClass('hide')
    $(element).closest('td').attr('colspan', 1)

    $(element).closest('tr').find('.unlink_operations').removeClass('invisible')








}


function min_max_changed(element) {


    var min_input = $(element).closest('span.edit_min_max').find('.recommended_min')
    var max_input = $(element).closest('span.edit_min_max').find('.recommended_max')

    var min_validation = client_validation('smallint_unsigned', false, min_input.val(), '')
    var max_validation = client_validation('smallint_unsigned', false, max_input.val(), '')

    //console.log(min_validation)
    //console.log(max_validation)
    if (min_input.val() != '' && max_input.val() != '' && min_validation.class == 'valid' && max_validation.class == 'valid' && parseFloat(min_input.val()) > parseFloat(max_input.val())) {

        min_validation.class = 'invalid'
        max_validation.class = 'invalid'

    }



    if (min_validation.class == 'invalid' || max_validation.class == 'invalid') {
        validation = 'invalid'
    } else {
        validation = 'valid'
    }

    min_input.removeClass('valid invalid').addClass(min_validation.class)
    max_input.removeClass('valid invalid').addClass(max_validation.class)





    //console.log($(element).closest('span.edit_min_max'))
    $(element).closest('span.edit_min_max').removeClass('valid invalid').addClass(validation)


}


function recommended_move_changed(element) {

    var move_input = $(element).closest('span.edit_move').find('.recommended_move')
    var validation = client_validation('smallint_unsigned', false, move_input.val(), '')
    //console.log(validation)

    $(element).closest('span.edit_move').removeClass('valid invalid').addClass(validation.class)

}

function save_recomendations(type, element) {

    if (type == 'min_max') {
        if (!$(element).closest('span.edit_min_max').hasClass('valid')) {
            return
        }


        $(element).closest('span.edit_min_max').find('.save').removeClass('fa-cloud').addClass('fa-spinner fa-spin')
        var min = $(element).closest('span.edit_min_max').find('.recommended_min').val()
        var max = $(element).closest('span.edit_min_max').find('.recommended_max').val()
        var location_key = $(element).closest('tr').find('.stock').attr('location_key')

        var value = JSON.stringify({
            min: min,
            max: max
        });
        var field = 'Part_Location_min_max'
    } else {

        if (!$(element).closest('span.edit_move').hasClass('valid')) {
            return
        }

        $(element).closest('span.edit_move').find('.save').removeClass('fa-cloud').addClass('fa-spinner fa-spin')
        var value = $(element).closest('span.edit_move').find('.recommended_move').val()
        var location_key = $(element).closest('tr').find('.stock').attr('location_key')


        var field = 'Part_Location_Moving_Quantity'
    }



    var request = '/ar_edit.php?tipo=edit_field&object=part_location&key='+$('#locations_table').attr('part_sku')+'_' + location_key + '&field=' + field + '&value=' + fixedEncodeURIComponent(value)

    console.log(request)
    var form_data = new FormData();
    form_data.append("tipo", 'edit_field')
    form_data.append("object", 'part_location')
    form_data.append("key", $('#locations_table').attr('part_sku')+'_' + location_key)
    form_data.append("field", field)
    form_data.append("value", value)

    var request = $.ajax({

        url: "/ar_edit.php",
        data: form_data,
        processData: false,
        contentType: false,
        type: 'POST',
        dataType: 'json'

    })

    request.done(function(data) {
        // console.log(data)
        if (data.state == 200) {

            //console.log(type)
            if (type == 'min_max') {
                $(element).closest('span.edit_min_max').find('.save').addClass('fa-cloud').removeClass('fa-spinner fa-spin')
                $(element).closest('span.edit_min_max').find('.recommended_min').val(data.value[0]).attr('ovalue', data.value[0])
                $(element).closest('tr').find('.formatted_recommended_min').html(data.formatted_value[0])
                $(element).closest('span.edit_min_max').find('.recommended_max').val(data.value[1]).attr('ovalue', data.value[1])
                $(element).closest('tr').find('.formatted_recommended_max').html(data.formatted_value[1])
                close_edit_min_max($(element).closest('tr').find('.close_min_max'))
            } else {


                $(element).closest('span.edit_move').find('.save').addClass('fa-cloud').removeClass('fa-spinner fa-spin')

                $(element).closest('tr').find('.recommended_move').val(data.value).attr('ovalue', data.value)
                $(element).closest('tr').find('.formatted_recommended_move').html(data.formatted_value)

                close_edit_recommended_move($(element).closest('tr').find('.close_move'))
            }
        }

    })

    request.fail(function(jqXHR, textStatus) {});




}




</script>
