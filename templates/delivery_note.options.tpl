<div id="set_out_of_stock_items_dialog" class="hide" style="position:absolute;border:1px solid #ccc;background-color: white;padding:10px 20px;z-index: 100">
    <table>

    <tr>
<td>{t}Out of stock{/t}

    <input class="picked_qty width_50" value="" ovalue="" /> <i onClick="save_item_out_of_stock_qty_change(this)" class="fa  fa-plus fa-fw button add_picked %s" aria-hidden="true"/>


</td>
    </tr>

        <tr class="hide">
            <td class="out_of_stock_location_code"></td>  <td class="out_of_stock_part_reference"></td> <td class="out_of_stock_part_stock"></td>

        </tr>

    </table>
</div>


<span id="dn_data" class="hide"  dn_key="{$dn->id}"   picker_key="{$dn->get('Delivery Note Assigned Picker Key')}"  packer_key="{$dn->get('Delivery Note Assigned Packer Key')}"
      no_picker_msg="{t}Please assign picker{/t}"

></span>
<div class="table_new_fields">
  
    <div id="picking_options" style="align-items: stretch;flex: 1;padding:10px 20px;border-left:1px solid #eee">
       {include file="delivery_note.options.picking.tpl"}
    </div>
    <div id="packing_options" style="align-items: stretch;flex: 1;padding:10px 20px;border-left:1px solid #eee">
        {include file="delivery_note.options.packing.tpl"}
    </div>
    
    
     </div>





<script>

    var out_of_stock_dialog_open=false;


    $('#table').on('click', 'span.item_quantity', function() {
        if(out_of_stock_dialog_open){
        }else{
            $(this).closest('tr').find('.picking').val($(this).attr('qty')).trigger('propertychange')
        }

    });

    $('#table').on('click', 'i.no_stock_location', function() {

        var settings =  $(this).closest('tr').find('.picking').parent().data('settings')
        var offset =$(this).offset()
        $('#set_out_of_stock_items_dialog').removeClass('hide').offset({ top:offset.top-15, left:offset.left-$('#set_out_of_stock_items_dialog').width()-50.0}).attr('transaction_key',settings.transaction_key).attr('item_key',settings.item_key)
        out_of_stock_dialog_open=true;

    });



    $('#table').on('input propertychange', '.picking', function() {
       if($(this).val()!=$(this).attr('ovalue')){
            $(this).next('i').removeClass('fa-plus').addClass('fa-cloud')
       }

    });


    function select_dropdown_handler(type,element) {


        field = $(element).attr('field')
        value = $(element).attr('value')

        if(value==0){
            return;
        }



        formatted_value = $(element).attr('formatted_value')
        //metadata = $(element).data('metadata')


        $('#' + field + '_dropdown_select_label').val(formatted_value)


        $('#' + field).val(value)

        $('#' + field + '_results_container').addClass('hide').removeClass('show')





        var request = '/ar_edit_orders.php?tipo=set_'+type+'&delivery_note_key='+$('#dn_data').attr('dn_key')+'&staff_key='+value
        console.log(request)




        $.getJSON(request, function (data) {

            if(data.state==200){

                $('#dn_data').attr(type+'_key',data.staff_key)






            }

        })



    }

    $( "#start_picking" ).click(function() {

        var request = '/ar_edit_orders.php?tipo=set_state&object=delivery_note&key='+$('#dn_data').attr('dn_key')+'&value=Picking'
        $.getJSON(request, function (data) {
            if(data.state==200){


            }
        })
    })

    $( "#start_packing" ).click(function() {

        var request = '/ar_edit_orders.php?tipo=set_state&object=delivery_note&key='+$('#dn_data').attr('dn_key')+'&value=Packing'
        $.getJSON(request, function (data) {
            if(data.state==200){


            }
        })
    })

</script>