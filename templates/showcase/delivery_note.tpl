<div class="timeline_horizontal">

    <input type="xhidden" id="Delivery_Note_State_Index" value="{$delivery_note->get('State Index')}">

    <ul class="timeline" id="timeline">


        <li id="order_node" class="li complete">
            <div class="label">
                <span class="state ">{t}Send to warehouse{/t}</span>
                <span class="start Order_Public_ID button" onClick="change_view('orders/{$order->get('Order Store Key')}/{$order->id}')">{$order->get('Public ID')} </span>
            </div>
            <div class="timestamp">
                <span class="Date_Created">&nbsp;{$delivery_note->get('Creation Date')}</span>
                <span class="start_date Order_Created_Date">{$order->get('Created Date')} </span>
            </div>
            <div class="dot"></div>
        </li>


        {if $delivery_note->get('State Index')<0 and $delivery_note->get('Dispatched Date')=='' and  $delivery_note->get('Received Date')==''  }
            <li id="received_node" class="li  cancelled">
                <div class="label">
                    <span class="state ">{t}Cancelled{/t}</span>
                </div>
                <div class="timestamp">
                    <span class="">&nbsp;{$delivery_note->get('Cancelled Date')}</span>
                </div>
                <div class="dot"></div>
            </li>
        {/if}





        <li id="start_picking_node" class="li  {if $delivery_note->get('State Index')>=20   }complete{/if}">
            <div class="label">
                <span class="state Delivery_Note_Packed_Label">{t}Start picking{/t}<span></i></span></span>
            </div>
            <div class="timestamp">
                <span class="Delivery_Note_Packed_Percentage_or_Datetime">&nbsp{$delivery_note->get('Start Picking Datetime')}&nbsp;</span>
            </div>
            <div class="dot"></div>
        </li>


        <li id="picked_node" class="li  {if $delivery_note->get('State Index')>=30   }complete{/if}">
            <div class="label">
                <span class="state Delivery_Note_Picked_Label">{if $delivery_note->get('State Index')==20 }{t}Picking{/t}{else}{t}Picked{/t}{/if}<span></i></span></span>
            </div>
            <div class="timestamp">
                <span class="Delivery_Note_Picked_Percentage_or_Datetime">&nbsp;{$delivery_note->get('Picked Percentage or Datetime')}&nbsp;</span>
            </div>
            <div class="dot"></div>
        </li>

        <li id="packed_node" class="li  {if $delivery_note->get('State Index')>=70   }complete{/if}">
            <div class="label">
                <span class="state Delivery_Note_Packed_Label">{if $delivery_note->get('State Index')==40 }{t}Packing{/t}{else}{t}Packed{/t}{/if}<span></i></span></span>
            </div>
            <div class="timestamp">
                <span class="Delivery_Note_Packed_Percentage_or_Datetime">&nbsp{$delivery_note->get('Packed Percentage or Datetime')}&nbsp;</span>
            </div>
            <div class="dot"></div>
        </li>


        <li id="packed_done_node" class="li  {if $delivery_note->get('State Index')>=80   }complete{/if}">
            <div class="label">
                <span class="state Delivery_Note_Packed_Label">{t}Sealed{/t}<span></i></span></span>
            </div>
            <div class="timestamp">
                <span class="Delivery_Note_Packed_Done_Datetime">&nbsp{$delivery_note->get('Done Approved Datetime')}&nbsp;</span>


            </div>
            <div class="dot"></div>
        </li>


        <li id="dispatch_approved_node" class="li  {if $delivery_note->get('State Index')>=90  }complete{/if}">
            <div class="label">
                <span class="state ">{t}Dispatch Approved{/t} <span></i></span></span>
            </div>
            <div class="timestamp">
                <span class="Delivery_Note_Dispatched_Approved_Datetime">&nbsp;{$delivery_note->get('Dispatched Approved Datetime')}</span>
            </div>
            <div class="dot"></div>
        </li>


        <li id="dispatched_node" class="li  {if $delivery_note->get('State Index')>=100  }complete{/if}">
            <div class="label">
                <span class="state ">{t}Dispatched{/t} <span></i></span></span>
            </div>
            <div class="timestamp">
                <span class="Delivery_Note_Dispatched_Datetime">&nbsp;{$delivery_note->get('Dispatched Datetime')}</span>
            </div>
            <div class="dot"></div>
        </li>


    </ul>
</div>

<div id="delivery_note" class="order" data-object="{$object_data}" dn_key="{$delivery_note->id}" style="display: flex;">
    <div class="block" style="padding:10px 20px;position: relative">


        <div style="margin-left:10px;">
             <span class="button" onclick="change_view('/customers/{$delivery_note->get('Delivery Note Store Key')}/{$delivery_note->get('Delivery Note Customer Key')}')">
                 <i class="fa fa-user " title="{$delivery_note->get('Delivery Note Customer Name')}"></i> <span class="marked_link">{$delivery_note->get('Delivery Note Customer Key')|string_format:"%05d"}</span>

        </div>

        <div style="margin-left:10px;min-width:250px;min-height:50px;margin-top:5px">
            {$delivery_note->get('Delivery Note Address Formatted')}
        </div>


    </div>


    <div class="block ">
        <div class="state" style="height:30px;margin-bottom:10px;position:relative;top:-5px;min-width: 250px">
            <div id="back_operations">
                <div id="delete_operations" class="order_operation {if $delivery_note->get('Delivery Note Number Picked Items')>0}hide{/if}">
                    <div class="square_button left" xstyle="padding:0;margin:0;position:relative;top:-5px" title="{t}delete{/t}">
                        <i class="fa fa-trash very_discreet " aria-hidden="true" onclick="toggle_order_operation_dialog('delete')"></i>
                        <table id="delete_dialog" border="0" class="order_operation_dialog hide">
                            <tr class="top">
                                <td colspan="2" class="label">{t}Delete delivery note{/t}</td>
                            </tr>
                            <tr class="changed buttons">
                                <td><i class="fa fa-sign-out fa-flip-horizontal button" aria-hidden="true" onclick="close_dialog('delete')"></i></td>
                                <td class="aright"><span data-data='{ "object": "DeliveryNote", "key":"{$delivery_note->id}"    }' id="delete_save_buttons" class="error save button" onclick="delete_object(this)"><span
                                                class="label">{t}Delete{/t}</span> <i class="fa fa-trash fa-fw" aria-hidden="true"></i></span>
                                </td>

                            </tr>
                        </table>
                    </div>
                </div>

                <div id="undo_picking_operations" class="order_operation {if $delivery_note->get('State Index')!=20}hide{/if}">
                    <div class="square_button left" title="{t}Undo start picking{/t}">
												<span class="fa-stack" onclick="toggle_order_operation_dialog('undo_picking')">
						<i class="fa fa-hand-lizard-o  fa-rotate-270 very_discreet " aria-hidden="true"></i>
						<i class="fa fa-ban fa-stack-1x discreet error"></i>
						</span>


                        <table id="undo_picking_dialog" border="0" class="order_operation_dialog hide">
                            <tr class="top">
                                <td class="label" colspan="2">{t}Set back to waiting to be picked{/t}</td>
                            </tr>
                            <tr class="changed buttons">
                                <td><i class="fa fa-sign-out fa-flip-horizontal button" aria-hidden="true" onclick="close_dialog('undo_picking')"></i></td>
                                <td class="aright"><span data-data='{  "field": "Delivery Note State","value": "Ready to be Picked","dialog_name":"undo_picking"}' id="undo_picking_save_buttons" class="valid save button"
                                                         onclick="save_order_operation(this)"><span class="label">{t}Save{/t}</span> <i class="fa fa-cloud fa-fw  " aria-hidden="true"></i></span></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div id="cancel_operations" class="order_operation {if $delivery_note->get('Delivery Note Number Picked Items')==0}hide{/if}">
                    <div class="square_button left" title="{t}Cancel{/t}">
                        <i class="fa fa-minus-circle error " aria-hidden="true" onclick="toggle_order_operation_dialog('cancel')"></i>
                        <table id="cancel_dialog" border="0" class="order_operation_dialog hide">
                            <tr class="top">
                                <td colspan="2">{t}Cancel order{/t}</td>
                            </tr>
                            <tr class="changed">
                                <td><i class="fa fa-sign-out fa-flip-horizontal button" aria-hidden="true" onclick="close_dialog('cancel')"></i></td>
                                <td class="aright"><span id="received_save_buttons" class="error save button" onclick="save_order_operation('cancel','Cancelled')"><span class="label">{t}Cancel{/t}</span> <i
                                                class="fa fa-cloud fa-fw  " aria-hidden="true"></i></span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

            </div>
            <span style="float:left;padding-left:10px;padding-top:5px" class="Delivery_Note_State"> {$delivery_note->get('State')} </span>
            <div id="forward_operations">


                <div id="fast_track_packing_operations" class="order_operation {if $delivery_note->get('State Index')!=20    }hide{/if}">
                    <div class="square_button right  " title="{t}Fast track packing{/t}">
                        <i id="show_fast_track_packing_button" class="fa fa-bolt  fa-fw  very_discreet " aria-hidden="true" onclick="show_fast_track_packing(this)"></i>

                    </div>
                </div>

                <div id="start_picking_operations" class="order_operation {if $delivery_note->get('State Index')!=10    }hide{/if}">
                    <div class="square_button right  " title="{t}Start picking{/t}">
                        <i id="start_picking_save_buttons" class="fa button fa-hand-lizard-o  fa-rotate-270 fa-fw  very_discreet "
                           data-data='{  "field": "Delivery Note State","value": "Picking","dialog_name":"start_picking"}' aria-hidden="true" onclick="save_order_operation(this)"></i>

                    </div>
                </div>


                <div id="packed_done_operations" class="order_operation {if $delivery_note->get('State Index')!=70    }hide{/if}">
                    <div class="square_button right  " title="{t}Set asn packed and sealed{/t}">
                        <i id="packed_done_save_buttons" class="fa button fa-archive  fa-fw  {if $delivery_note->get('Delivery Note Weight Source')!='Given'  or  $delivery_note->get('Delivery Note Number Parcels')=='' }  very_discreet{/if} "
                           data-data='{  "field": "Delivery Note State","value": "Packed Done","dialog_name":"packed_done"}' aria-hidden="true" onclick="save_order_operation(this)"></i>

                    </div>
                </div>


                <div id="dispatch_operations" class="order_operation {if $delivery_note->get('State Index')!=90    } hide{/if}">
                    <div id="dispatch_operation" class="square_button right  " title="{t}Dispatch{/t}">


                        <i id="dispatch_save_buttons" class="fa button fa-paper-plane fa-fw   "
                           data-data='{  "field": "Delivery Note State","value": "Dispatched","dialog_name":"dispatch"}' aria-hidden="true" onclick="save_order_operation(this)"></i>


                    </div>
                </div>
            </div>
        </div>


        <div class="state"  style="height:30px;margin-bottom:10px;position:relative;top:-5px;text-align: center">
            <span class="button"  onclick="change_view('orders/{$order->get('Order Store Key')}/{$order->id}')" ><i class="fa fa-shopping-cart" aria-hidden="true"></i> {$order->get('Public ID')}</span>
        </div>


        <table border="0" class="info_block acenter">



            <tr>


                <td>
                    <span style=""><i class="fa fa-square fa-fw discreet" aria-hidden="true"></i>
                          <span class="Number_Ordered_Parts">{$delivery_note->get('Number Ordered Parts')}</span> (<span class="Number_Ordered_Items">{$delivery_note->get('Number Ordered Items')}</span>)
                    <span style="padding-left:20px"><i class="fa fa-balance-scale fa-fw discreet " aria-hidden="true"></i> <span class="Weight_Details">{$delivery_note->get('Weight Details')}</span></span>
                    <span class="error {if $delivery_note->get('Order Number Items Out of Stock')==0}hide{/if}" style="padding-left:20px"><i class="fa fa-cube fa-fw  " aria-hidden="true"></i> <span
                                class="Order_Number_Items_with_Out_of_Stock">{$delivery_note->get('Number Items Out of Stock')}</span></span>
                    <span class="error {if $delivery_note->get('Order Number Items Returned')==0}hide{/if}" style="padding-left:20px"><i class="fa fa-thumbs-o-down fa-fw   " aria-hidden="true"></i> <span
                                class="Order_Number_Items_with_Returned">{$delivery_note->get('Number Items Returned')}</span></span>
                </td>
            </tr>


        </table>

    </div>


    <div class="block">


        <table border="0" class="info_block  {if $delivery_note->get('State Index')<70 or $delivery_note->get('State Index')>90 }hide{/if} ">




            <tr>
                <td class="aright"> {t}Parcels{/t}:</td>
                <td class="">
                    <input id="number_parcel_field" style="width:75px" value="{$delivery_note->get('Delivery Note Number Parcels')}" ovalue="{$delivery_note->get('Delivery Note Number Parcels')}"
                           placeholder="{t}number{/t}"> <i onCLick="save_number_parcels(this)" class="fa fa-plus button" aria-hidden="true"></i>

                </td>
                <td class="aright"> {t}Weight{/t}:</td>
                <td class=""><span><input id="weight_field" style="width:75px" value="{$delivery_note->get('Weight For Edit')}" ovalue="{$delivery_note->get('Weight For Edit')}" placeholder="{t}Kg{/t}"> <i
                                onCLick="save_delivery_note_weight(this)" class="fa fa-cloud button hide" aria-hidden="true"></i>

                </td>
            </tr>

            <tr id="edit_consignment_tr" class="hide">
                <td class="aright"> {t}Courier{/t}:</td>
                <td class="aright"><span id="formatted_consignment">{if $consignment==''}<span onclick="show_dialog_set_dn_data()"
                                                                                               style="font-style:italic;color:#777;cursor:pointer">{t}Set consignment{/t}</span>{else}{$consignment}{/if}</span>
                </td>
            </tr>

        </table>

    </div>

</div>


<div id="set_out_of_stock_items_dialog" class="hide" style="position:absolute;border:1px solid #ccc;background-color: white;padding:10px 20px;z-index: 100">
    <table>

        <tr>
            <td>{t}Out of stock{/t}

                <input class="picked_qty width_50" value="" ovalue=""/> <i onClick="save_item_out_of_stock_qty_change(this)" class="fa  fa-plus fa-fw button add_picked %s" aria-hidden="true"/>


            </td>
        </tr>

        <tr class="hide">
            <td class="out_of_stock_location_code"></td>
            <td class="out_of_stock_part_reference"></td>
            <td class="out_of_stock_part_stock"></td>

        </tr>

    </table>
</div>


<span id="dn_data" class="hide" dn_key="{$delivery_note->id}" picker_key="{$delivery_note->get('Delivery Note Assigned Picker Key')}" packer_key="{$delivery_note->get('Delivery Note Assigned Packer Key')}"
      no_picker_msg="{t}Please assign picker{/t}" no_packer_msg="{t}Please assign packer{/t}"></span>


<div class="table_new_fields delivery_note_handling_fields   {if $delivery_note->get('State Index')<=10    }hide{/if} " style="border-bottom:1px solid #ccc;">

    <div class="" style="align-items: stretch;flex: 1;padding:0px 20px;border-left:1px solid #ccc">

        <table style="width:100%;min-height: 100px;" border="0">

            <tr>
                <td style="width: 50%;padding:10px;border-right:1px solid whitesmoke">

                    <label>{t}Picker{/t}</label><br>

                    <input id="set_picker" type="hidden" class=" input_field" value="" has_been_valid="0"/>

                    <input id="set_picker_dropdown_select_label" field="set_picker" style="width:170px;margin-top:5px" scope="employee" parent="account" parent_key="1" class="dropdown_select"
                           data-metadata='{ "option":"only_working"}' value="{$delivery_note->get('Delivery Note Assigned Picker Alias')}" has_been_valid="0" placeholder="{t}Name{/t}"/>
                    <span id="set_picker_msg" class="msg"></span>
                    <i id="set_picker_save_button" class="fa fa-cloud save dropdown_select hide" onclick="save_this_field(this)"></i>
                    <div id="set_picker_results_container" class="search_results_container hide">

                        <table id="set_picker_results" border="0">

                            <tr class="hide" id="set_picker_search_result_template" field="" value="" formatted_value="" onClick="select_dropdown_handler('picker',this)">
                                <td class="code"></td>
                                <td style="width:85%" class="label"></td>

                            </tr>
                        </table>

                    </div>
                    <script>
                        $("#set_picker_dropdown_select_label").on("input propertychange", function (evt) {

                            var delay = 100;
                            if (window.event && event.type == "propertychange" && event.propertyName != "value") return;
                            delayed_on_change_dropdown_select_field($(this), delay)
                        });
                    </script>


                </td>


            </tr>


        </table>

    </div>
    <div class="" style="align-items: stretch;flex: 1;padding:0px 20px;border-left:1px solid #ccc">
        <table style="width:100%;min-height: 100px;" border="0">

            <tr>
                <td style="width: 50%;padding:10px;border-right:1px solid whitesmoke">

                    <label>{t}Packer{/t}</label>

                    <input id="set_packer" type="hidden" class=" input_field" value="" has_been_valid="0"/>

                    <input id="set_packer_dropdown_select_label" field="set_packer" style="width:170px;margin-top:5px" scope="employee" parent="account" parent_key="1" class="dropdown_select"
                           data-metadata='{ "option":"only_working"}' value="{$delivery_note->get('Delivery Note Assigned Packer Alias')}" has_been_valid="0" placeholder="{t}Name{/t}"/>
                    <span id="set_packer_msg" class="msg"></span>
                    <i id="set_packer_save_button" class="fa fa-cloud save dropdown_select hide" onclick="save_this_field(this)"></i>
                    <div id="set_packer_results_container" class="search_results_container hide">

                        <table id="set_packer_results" border="0">

                            <tr class="hide" id="set_packer_search_result_template" field="" value="" formatted_value="" onClick="select_dropdown_handler('packer',this)">

                                <td class="code"></td>
                                <td style="width:85%" class="label"></td>

                            </tr>
                        </table>

                    </div>
                    <script>
                        $("#set_packer_dropdown_select_label").on("input propertychange", function (evt) {

                            var delay = 100;
                            if (window.event && event.type == "propertychange" && event.propertyName != "value") return;
                            delayed_on_change_dropdown_select_field($(this), delay)
                        });
                    </script>


                </td>


            </tr>


        </table>
    </div>

    <div class="" style="align-items: stretch;flex: 1;padding:40px 20px;border-left:1px solid #ccc;text-align: center">

        <a class="pdf_link" target='_blank' href="/pdf/order_pick_aid.pdf.php?id={$delivery_note->id}"> <img style="width: 50px" src="/art/pdf.gif"></a>


    </div>

    <div class="" style="align-items: stretch;flex: 1;padding:40px 20px;border-left:1px solid #ccc;text-align: center">

        <div class="hide fast_track_packing_operation">

            <span class="button" onclick="copy_qty_to_all_offline_picking()"><i class="fa fa-magic button" style="margin-right:2.5px" aria-hidden="true"></i><i class="fa fa-plus " style="margin-right:20px"
                                                                                                                                                                aria-hidden="true"></i></span>


            <span class="button" onclick="mark_all_offline_picking_as_done()"><i class="fa fa-bolt " style="margin-right:2.5px" aria-hidden="true"></i><i class="fa fa-check-circle " style="margin-right:20px"
                                                                                                                                                          aria-hidden="true"></i></span>
        </div>
    </div>

    <div class="" style="align-items: stretch;flex: 1;padding:40px 20px;border-left:1px solid #ccc;text-align: center">
        <div class="hide fast_track_packing_operation">
            <span id="save_picking_offline" onclick="save_picking_offline()" class="save">{t}Pack order{/t} <i class="fa fa-cloud" aria-hidden="true"></i></span>
        </div>
    </div>
</div>


<script>

    var out_of_stock_dialog_open = false;


    function save_picking_offline() {


        if ($('#save_picking_offline').hasClass('wait') || !$('#save_picking_offline').hasClass('valid')) {
            return
        }

        $('#save_picking_offline').addClass('wait')
        $('#save_picking_offline i').removeClass('save').addClass('fa-spinner fa-spin')


        var items = [];

        $('#table .picked_offline').each(function (i, obj) {


            settings = $(obj).closest('.picked_quantity').data('settings')
            location_key = $(obj).closest('tr').find('.location').attr('location_key')

            items.push({
                qty: $(obj).val(), transaction_key: settings.transaction_key, location_key: location_key
            })


        });


        console.log(items)


        var ajaxData = new FormData();

        ajaxData.append("tipo", 'pick_order_offline')
        ajaxData.append("delivery_note_key", $('#dn_data').attr('dn_key'))
        ajaxData.append("items", JSON.stringify(items))


        $.ajax({
            url: "/ar_edit_orders.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false, complete: function () {
            }, success: function (data) {

                if (data.state == '200') {





                    console.log(data)


                    if (data.value == 'Cancelled') {
                        change_view(state.request, {
                            reload_showcase: true
                        })
                    }


                    for (var key in data.update_metadata.class_html) {
                        $('.' + key).html(data.update_metadata.class_html[key])
                    }


                    $('.order_operation').addClass('hide')
                    $('.items_operation').addClass('hide')

                    for (var key in data.update_metadata.operations) {
                        $('#' + data.update_metadata.operations[key]).removeClass('hide')
                    }


                    $('.timeline .li').removeClass('complete')




                    $('#order_node').addClass('complete')

                    if (data.update_metadata.state_index >= 20) {
                            $('#start_picking_node').addClass('complete')
                        }
                        if (data.update_metadata.state_index >= 30) {
                            $('#picked_node').addClass('complete')
                        }
                    if (data.update_metadata.state_index >= 70) {
                        $('#packed_node').addClass('complete')
                    }
                    if (data.update_metadata.state_index >= 80) {
                        $('#packed_done_node').addClass('complete')
                    }
                    if (data.update_metadata.state_index >= 90) {
                        $('#dispatch_approved_node').addClass('complete')
                    }
                    if (data.update_metadata.state_index >= 100) {
                        $('#dispatched_node').addClass('complete')
                    }

                    if (data.update_metadata.state_index >= 70 &&  data.update_metadata.state_index <= 90    ) {
                        $('.info_block').removeClass('hide')
                    }else{
                        $('.info_block').addClass('hide')
                    }




                        $('.order_operation').addClass('hide')
                    $('.items_operation').addClass('hide')

                    for (var key in data.update_metadata.operations) {
                        $('#' + data.update_metadata.operations[key]).removeClass('hide')
                    }



                    $('#Delivery_Note_State_Index').val(data.update_metadata.state_index)


                    change_tab('delivery_note.items', {
                        post_operations: 'delivery_note.fast_track_packing_off'
                    })


                    $('.fast_track_packing_operation').addClass('hide')



                } else if (data.state == '400') {
                    swal({
                        title: data.title, text: data.msg, confirmButtonText: "OK"
                    });
                }


            }, error: function () {

            }
        });


    }

    function copy_qty_to_all_offline_picking() {

        $('#table span.item_quantity').each(function (i, obj) {


            input = $(obj).closest('tr').find('.picked_offline');

            if (!input.is('[readonly]') && input.val() == '') {

                input.val($(obj).attr('qty')).trigger('propertychange')

            }


        });

    }


    function mark_all_offline_picking_as_done() {

        $('#table .picked_offline_status').each(function (i, obj) {


            set_picked_offline_item_as_done(obj)

            // $(obj).addClass('success').removeClass('super_discreet')
            // $(obj).closest('tr').find('.picked_offline_items_qty_change').addClass('invisible')

            //  $(obj).closest('tr').find('.picked_offline').prop('readonly', true);


        });

        validate_picked_offline()

    }


    function validate_picked_offline() {


        var done_items = true;

        $('#table .picked_offline_status').each(function (i, obj) {

            console.log('xxx');

            if (!$(obj).hasClass('success')) {

                console.log('caca');

                done_items = false;
                return false
            }
        });

        if (done_items) {
            $('#save_picking_offline').addClass(' valid changed')
        } else {
            $('#save_picking_offline').removeClass(' valid changed')

        }


    }


    function set_picked_offline_item_as_done(element) {

        if ($(element).hasClass('blocked')) {
            validate_picked_offline();
            return
        }

        if ($(element).hasClass('success')) {
            $(element).removeClass('success').addClass('super_discreet')
            $(element).closest('tr').find('.picked_offline_items_qty_change').removeClass('invisible').prop('readonly', false);

            var input = $(element).closest('tr').find('.picked_offline');
            input.prop('readonly', false);

            input.css({
                'background-color': 'rgba(255,255,255, 0.2)'
            })

        } else {
            $(element).addClass('success').removeClass('super_discreet')
            $(element).closest('tr').find('.picked_offline_items_qty_change').addClass('invisible')

            var input = $(element).closest('tr').find('.picked_offline');

            input.prop('readonly', true);

            if (input.val() == '') {
                input.val(0)
            }


            if (input.val() < input.attr('max')) {
                input.css({
                    'background-color': 'rgba(255,55,55, 0.2)'
                })
            } else {
                input.css({
                    'background-color': 'rgba(154,205,50, 0.2)'
                })

            }


        }

        validate_picked_offline()


    }


    $('#table').on('click', 'span.item_quantity', function () {
        if (out_of_stock_dialog_open) {
        } else {

            input = $(this).closest('tr').find('.picked_offline');

            if (!input.is('[readonly]')) {

                input.val($(this).attr('qty')).trigger('propertychange')

            }


        }

    });

    $('#table').on('click', 'i.no_stock_location', function () {

        if ($('#set_out_of_stock_items_dialog').hasClass('hide')) {

            var settings = $(this).closest('tr').find('.picking').parent().data('settings')
            var offset = $(this).offset()
            $('#set_out_of_stock_items_dialog').removeClass('hide').offset({
                top: offset.top - 15, left: offset.left - $('#set_out_of_stock_items_dialog').width() - 50.0
            }).attr('transaction_key', settings.transaction_key).attr('item_key', settings.item_key)
            out_of_stock_dialog_open = true;
        } else {
            $('#set_out_of_stock_items_dialog').addClass('hide')
            out_of_stock_dialog_open = false;
        }

    });


    $('#table').on('input propertychange', '.picking', function () {
        if ($(this).val() != $(this).attr('ovalue')) {
            $(this).next('i').removeClass('fa-plus').addClass('fa-cloud')
        }

    });


    function select_dropdown_handler(type, element) {


        field = $(element).attr('field')
        value = $(element).attr('value')

        if (value == 0) {
            return;
        }


        formatted_value = $(element).attr('formatted_value')
        //metadata = $(element).data('metadata')


        $('#' + field + '_dropdown_select_label').val(formatted_value)


        $('#' + field).val(value)

        $('#' + field + '_results_container').addClass('hide').removeClass('show')


        var request = '/ar_edit_orders.php?tipo=set_' + type + '&delivery_note_key=' + $('#dn_data').attr('dn_key') + '&staff_key=' + value
        console.log(request)


        $.getJSON(request, function (data) {

            if (data.state == 200) {

                $('#dn_data').attr(type + '_key', data.staff_key)


            }

        })


    }

    $("#start_picking").click(function () {

        var request = '/ar_edit_orders.php?tipo=set_state&object=delivery_note&key=' + $('#dn_data').attr('dn_key') + '&value=Picking'
        $.getJSON(request, function (data) {
            if (data.state == 200) {


                for (var key in data.metadata.class_html) {
                    $('.' + key).html(data.metadata.class_html[key])
                }


                for (var key in data.metadata.hide) {
                    $('#' + data.metadata.hide[key]).addClass('hide')
                }
                for (var key in data.metadata.show) {
                    $('#' + data.metadata.show[key]).removeClass('hide')
                }
            }
        })
    })

    $("#start_packing").click(function () {

        var request = '/ar_edit_orders.php?tipo=set_state&object=delivery_note&key=' + $('#dn_data').attr('dn_key') + '&value=Packing'
        $.getJSON(request, function (data) {
            if (data.state == 200) {


                for (var key in data.metadata.class_html) {
                    $('.' + key).html(data.metadata.class_html[key])
                }


                for (var key in data.metadata.hide) {
                    $('#' + data.metadata.hide[key]).addClass('hide')
                }
                for (var key in data.metadata.show) {
                    $('#' + data.metadata.show[key]).removeClass('hide')
                }
            }
        })
    })


    function show_fast_track_packing(element) {

        if ($(element).hasClass('success')) {
            $(element).removeClass('success').addClass('very_discreet')

            change_tab('delivery_note.items', {
                post_operations: 'delivery_note.fast_track_packing_off'
            })


            $('.fast_track_packing_operation').addClass('hide')


        } else {
            $(element).addClass('success').removeClass('very_discreet')
            change_tab('set_delivery_note.fast_track_packing', {
                post_operations: 'delivery_note.fast_track_packing'
            })


            $('.fast_track_packing_operation').removeClass('hide')
        }


    }


    $(document).on('input propertychange', '#weight_field', function () {


        console.log($(this).val())

        if ($(this).val() != $(this).attr('ovalue')) {
            $(this).closest('td').find('i').removeClass('hide')
        } else {
            $(this).closest('td').find('i').addClass('hide')

        }

        var validation = validate_number($(this).val(), 0, 999999999)

        if (validation) {

            $(this).closest('td').find('i').addClass('error').removeClass('valid save changed')

        } else {
            $(this).closest('td').find('i').removeClass('error').addClass('valid save changed')
        }


    })


    $(document).on('click', '#exit_fast_track_packing', function () {


        show_fast_track_packing($('#show_fast_track_packing_button'))
    })

    $(document).on('input propertychange', '#number_parcel_field', function () {


        if ($(this).val() != $(this).attr('ovalue')) {
            $(this).next(i).addClass('fa-cloud').removeClass('fa-plus')
        } else {
            $(this).next(i).removeClass('fa-cloud').addClass('fa-plus')

        }

    })


    function save_number_parcels(element) {

        $(element).addClass('fa-spinner fa-spin');

        var input = $(element).prev('input')
        var icon = $(element)

        if ($(element).hasClass('fa-plus')) {

            if (isNaN(input.val()) || input.val() == '') {
                var qty = 1
            } else {
                qty = parseFloat(input.val()) + 1
            }

            input.val(qty).addClass('discreet')

        } else if ($(element).hasClass('fa-minus')) {

            if (isNaN(input.val()) || input.val() == '' || input.val() == 0) {
                var qty = 0
            } else {
                qty = parseFloat(input.val()) - 1
            }

            input.val(qty).addClass('discreet')

        } else {
            qty = parseFloat(input.val())

        }

        if (qty == '') qty = 0;


        var request = '/ar_edit.php?tipo=edit_field&object=DeliveryNote&key=' + $('#delivery_note').attr('dn_key') + '&field=Delivery_Note_Number_Parcels&value=' + qty + '&metadata={}';
        console.log(request)

        var form_data = new FormData();

        form_data.append("tipo", 'edit_field')
        form_data.append("field", 'Delivery_Note_Number_Parcels')
        form_data.append("object", 'DeliveryNote')
        form_data.append("key", $('#delivery_note').attr('dn_key'))
        form_data.append("value", qty)
        var request = $.ajax({

            url: "/ar_edit.php", data: form_data, processData: false, contentType: false, type: 'POST', dataType: 'json'

        })


        request.done(function (data) {
            $(element).removeClass('fa-spinner fa-spin')
            if (data.state == 200) {
                input.attr('ovalue', data.value)
                icon.removeClass('fa-cloud').addClass('fa-plus')
            } else if (data.state == 400) {
                sweetAlert(data.msg);
                input.val(input.attr('ovalue'))
            }

        })


        request.fail(function (jqXHR, textStatus) {
            console.log(textStatus)

            console.log(jqXHR.responseText)


        });


    }


    function save_delivery_note_weight(element) {

        var input = $(element).closest('td').find('input')
        var icon = $(element)



        if (!icon.hasClass('save') || icon.hasClass('wait') ) {
            return
        }

        $(element).addClass('fa-spinner fa-spin');






        qty = parseFloat(input.val())


        if (qty == '') qty = 0;


        var request = '/ar_edit.php?tipo=edit_field&object=DeliveryNote&key=' + $('#delivery_note').attr('dn_key') + '&field=Delivery_Note_Weight&value=' + qty + '&metadata={}';
        console.log(request)

        var form_data = new FormData();

        form_data.append("tipo", 'edit_field')
        form_data.append("field", 'Delivery_Note_Weight')
        form_data.append("object", 'DeliveryNote')
        form_data.append("key", $('#delivery_note').attr('dn_key'))
        form_data.append("value", qty)
        var request = $.ajax({

            url: "/ar_edit.php", data: form_data, processData: false, contentType: false, type: 'POST', dataType: 'json'

        })


        request.done(function (data) {
            $(element).removeClass('fa-spinner fa-spin wait')
            if (data.state == 200) {
                input.attr('ovalue', data.value)
                icon.addClass('hide')
            } else if (data.state == 400) {
                sweetAlert(data.msg);
                input.val(input.attr('ovalue'))
            }

        })


        request.fail(function (jqXHR, textStatus) {
            console.log(textStatus)

            console.log(jqXHR.responseText)


        });


    }

    function dispatch_delivery_note() {


        var request = '/ar_edit_orders.php?tipo=set_state&object=delivery_note&key=' + $('#dn_data').attr('dn_key') + '&value=Dispatched'
        $.getJSON(request, function (data) {
            if (data.state == 200) {


            }
        })
    }

    function print_label() {

        $("#printframe").remove();

        // create new printframe
        var iFrame = $('<iframe></iframe>');
        iFrame
            .attr("id", "printframe")
            .attr("name", "printframe")
            .attr("src", "about:blank")
            .css("width", "0")
            .css("height", "0")
            .css("position", "absolute")
            .css("left", "-9999px")
            .appendTo($("body:first"));

        // load printframe
        var url = 'test'
        if (iFrame != null && url != null) {
            iFrame.attr('src', url);
            iFrame.load(function () {
                // nasty hack to be able to print the frame
                var tempFrame = $('#printframe')[0];
                var tempFrameWindow = tempFrame.contentWindow ? tempFrame.contentWindow : tempFrame.contentDocument.defaultView;
                tempFrameWindow.focus();
                tempFrameWindow.print();
            });
        }


    }

</script>