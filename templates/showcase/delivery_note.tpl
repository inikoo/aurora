<style>
    span.button.option {
        border: 1px solid #ccc;
        padding: 2px 10px 2px 10px;
    }
    span.button.option.selected {
        border: 1px solid #000;
        color:#000;
    }
</style>


<div class="sticky_notes">
    <div  class="sticky_note_container delivery_note_sticky_note {if $order->get('Delivery Sticky Note')==''}hide{/if}"    >
        <div class="sticky_note" >{$order->get('Delivery Sticky Note')}</div>
    </div>
</div>

<div class="timeline_horizontal">

    <input type="hidden" id="Delivery_Note_State_Index" value="{$delivery_note->get('State Index')}">

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


        <li id="start_picking_node"
            class="li  {if $delivery_note->get('State Index')>=20   }complete{/if} {if $delivery_note->get('State Index')<0 } {if   $delivery_note->get('Delivery Note Date Start Picking')=='' }hide{else}complete{/if}{/if}">
            <div class="label">
                <span class="state Delivery_Note_Packed_Label">{t}Start picking{/t}<span></i></span></span>
            </div>
            <div class="timestamp">
                <span class="Delivery_Note_Packed_Percentage_or_Datetime">&nbsp{$delivery_note->get('Start Picking Datetime')}&nbsp;</span>
            </div>
            <div class="dot"></div>
        </li>


        <li id="picked_node"
            class="li  {if $delivery_note->get('State Index')>=30   }complete{/if} {if $delivery_note->get('State Index')<0 } {if   $delivery_note->get('Delivery Note Date Finish Picking')=='' }hide{else}complete{/if}{/if}">
            <div class="label">
                <span class="state Delivery_Note_Picked_Label">{if $delivery_note->get('State Index')==20 }{t}Picking{/t}{else}{t}Picked{/t}{/if}<span></i></span></span>
            </div>
            <div class="timestamp">
                <span class="Delivery_Note_Picked_Percentage_or_Datetime">&nbsp;{$delivery_note->get('Picked Percentage or Datetime')}&nbsp;</span>
            </div>
            <div class="dot"></div>
        </li>

        <li id="packed_node"
            class="li  {if $delivery_note->get('State Index')>=70   }complete{/if} {if $delivery_note->get('State Index')<0 } {if   $delivery_note->get('Delivery Note Date Finish Packing')=='' }hide{else}complete{/if}{/if}">
            <div class="label">
                <span class="state Delivery_Note_Packed_Label">{if $delivery_note->get('State Index')==40 }{t}Packing{/t}{else}{t}Packed{/t}{/if}<span></i></span></span>
            </div>
            <div class="timestamp">
                <span class="Delivery_Note_Packed_Percentage_or_Datetime">&nbsp{$delivery_note->get('Packed Percentage or Datetime')}&nbsp;</span>
            </div>
            <div class="dot"></div>
        </li>


        <li id="packed_done_node"
            class="li  {if $delivery_note->get('State Index')>=80   }complete{/if} {if $delivery_note->get('State Index')<0} {if  $delivery_note->get('Delivery Note Date Done Approved')=='' }hide{else}complete{/if}{/if}">
            <div class="label">
                <span class="state Delivery_Note_Packed_Label">{t}Packed & Closed{/t}<span></i></span></span>
            </div>
            <div class="timestamp">
                <span class="Delivery_Note_Packed_Done_Datetime">&nbsp{$delivery_note->get('Done Approved Datetime')}&nbsp;</span>


            </div>
            <div class="dot"></div>
        </li>



        <li id="dispatched_node" class="li  {if $delivery_note->get('State Index')>=100  }complete{/if}  {if $delivery_note->get('State Index')<0 }hide{/if}   ">
            <div class="label">
                <span class="state ">{if $order->get('Order For Collection')=='Yes' }{t}Collected{/t}{else}{t}Dispatched{/t}{/if} <span></i></span></span>
            </div>
            <div class="timestamp">
                <span class="Delivery_Note_Dispatched_Datetime">&nbsp;{$delivery_note->get('Dispatched Datetime')}</span>
            </div>
            <div class="dot"></div>
        </li>


        {if $delivery_note->get('State Index')<0   }
            <li id="received_node" class="li  cancelled">
                <div class="label">
                    <span class="state ">{t}Cancelled{/t}</span>
                </div>
                <div class="timestamp">
                    <span >&nbsp;{$delivery_note->get('Cancelled Datetime')}</span>
                </div>
                <div class="dot"></div>
            </li>
        {/if}


    </ul>
</div>

<div id="delivery_note" class="order" data-object='{$object_data}' data-number_shippers="{$number_shippers}" dn_key="{$delivery_note->id}" style="display: flex;">
    <div class="block" style="padding:10px 20px;position: relative">


        <div style="margin-left:10px;">
             <span class="button" onclick="change_view('/customers/{$delivery_note->get('Delivery Note Store Key')}/{$delivery_note->get('Delivery Note Customer Key')}')">
                 <i class="fa fa-user " title="{$delivery_note->get('Delivery Note Customer Name')}"></i> <span class="marked_link">{$delivery_note->get('Delivery Note Customer Key')|string_format:"%05d"}</span>

        </div>

        <div style="margin-left:10px;min-width:250px;min-height:50px;margin-top:5px">
            {if $delivery_note->get('Delivery Note Dispatch Method')=='Collection'}
                {t}For collection{/t}
                {else}
            {$delivery_note->get('Delivery Note Address Formatted')}
            {/if}
        </div>







    </div>


    <div class="block ">
        <div class="state" style="height:30px;margin-bottom:0px;position:relative;top:-5px;min-width: 250px">
            <div id="back_operations">
                <div id="delete_operations" class="order_operation {if $delivery_note->get('Delivery Note Number Picked Items')>0       }hide{/if}">
                    <div class="square_button left" xstyle="padding:0;margin:0;position:relative;top:-5px" title="{t}delete{/t}">
                        <i class="far fa-trash-alt very_discreet " aria-hidden="true" onclick="toggle_order_operation_dialog('delete')"></i>
                        <table id="delete_dialog" class="order_operation_dialog hide">
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
						<i class="fas fa-hand-holding-box   very_discreet " aria-hidden="true"></i>
						<i class="fa fa-ban fa-stack-1x discreet error"></i>
						</span>


                        <table id="undo_picking_dialog" class="order_operation_dialog hide">
                            <tr class="top">
                                <td class="label" colspan="2">{t}Set back to waiting to be picked{/t}</td>
                            </tr>
                            <tr class="changed buttons">
                                <td><i class="fa fa-sign-out fa-flip-horizontal button" aria-hidden="true" onclick="close_dialog('undo_picking')"></i></td>
                                <td class="aright">
                                    <span data-data='{  "field": "Delivery Note State","value": "Ready to be Picked","dialog_name":"undo_picking"}' id="undo_picking_save_buttons" class="valid save button"
                                          onclick="save_order_operation(this)"><span class="label">{t}Save{/t}</span> <i class="fa fa-cloud fa-fw  " aria-hidden="true"></i></span></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div id="cancel_operations"
                     class="order_operation {if $delivery_note->get('Delivery Note Number Picked Items')==0  or  $delivery_note->get('State Index')<0 or  $delivery_note->get('State Index')>=80  }hide{/if}">
                    <div class="square_button left" title="{t}Cancel{/t}">
                        <i class="fa fa-minus-circle error " aria-hidden="true" onclick="toggle_order_operation_dialog('cancel')"></i>
                        <table id="cancel_dialog" class="order_operation_dialog hide">
                            <tr class="top">
                                <td colspan="2">{t}Cancel delivery note{/t}</td>
                            </tr>
                            <tr class="changed buttons">
                                <td><i class="fa fa-sign-out fa-flip-horizontal button" aria-hidden="true" onclick="close_dialog('cancel')"></i></td>
                                <td class="aright"><span data-data='{  "field": "Delivery Note State","value": "Cancelled","dialog_name":"cancel"}' id="cancel_save_buttons" class="error save button"

                                                         onclick="save_order_operation(this)"><span class="label">{t}Cancel{/t}</span> <i class="fa fa-cloud fa-fw  " aria-hidden="true"></i></span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div id="undo_packed_done_operations" class="order_operation {if $delivery_note->get('State Index')!=80}hide{/if}">
                    <div class="square_button left" title="{t}Open boxes{/t}" >
						<i class="fal fa-box-open  error discreet "  aria-hidden="true" onclick="toggle_order_operation_dialog('undo_packed_done')"></i>



                        <table id="undo_packed_done_dialog" class="order_operation_dialog hide">
                            <tr class="top">
                                <td class="label" colspan="2">{t}Open boxes{/t}</td>
                            </tr>
                            <tr class="changed buttons">
                                <td><i class="fa fa-sign-out fa-flip-horizontal button" aria-hidden="true" onclick="close_dialog('undo_packed_done')"></i></td>
                                <td class="aright">
                                    <span data-data='{  "field": "Delivery Note State","value": "Undo Packed Done","dialog_name":"undo_packed_done"}' id="undo_packed_done_save_buttons" class="valid save button"
                                          onclick="save_order_operation(this)"><span class="label">{t}Open{/t}</span> <i class="fa fa-box-open fa-fw  " aria-hidden="true"></i></span></td>
                            </tr>
                        </table>
                    </div>
                </div>

            </div>
            <span style="float:left;padding-left:10px;padding-top:5px" class="Delivery_Note_State"> {$delivery_note->get('State')} </span>
            <div id="forward_operations">

                <div id="assign_picker_operations" class="order_operation {if $delivery_note->get('State Index')!=10}hide{/if}">
                    <div class="square_button right" title="{t}Assign picker{/t}" >
                        <i class="fal fa-chalkboard-teacher  "  aria-hidden="true" onclick="toggle_order_operation_dialog('assign_picker')"></i>



                        <table id="assign_picker_dialog" class="order_operation_dialog hide">
                            <tr class="top">
                                <td class="label" colspan="2">{t}Assign picker{/t}</td>
                            </tr>
                            <tr class="top">
                                <td class="label" colspan="2">
                                    <input id="set_picker" type="hidden" data-field="Delivery Note Assigned Picker Key" class=" input_field" value="{$store->settings('data_entry_picking_aid_default_picker')}" has_been_valid="0"/>

                                    <input id="set_picker_dropdown_select_label" field="set_picker" style="width:170px"  name="picker" autocomplete="off"
                                           scope="employee" parent="account"
                                           parent_key="1" class="dropdown_select"
                                           data-metadata='{ "option":"only_working"}'
                                           value="{if $delivery_note->get('Delivery Note Assigned Picker Key')>0 }{$delivery_note->get('Delivery Note Assigned Picker Name')}{else}{$store->get('data entry picking aid default picker')}{/if}"
                                           has_been_valid="0"
                                           placeholder="{t}Name{/t}"/>
                                    <span id="set_picker_msg" class="msg"></span>

                                    <div id="set_picker_results_container" class="search_results_container hide">

                                        <table id="set_picker_results" >

                                            <tr class="hide" id="set_picker_search_result_template" field="" value=""
                                                formatted_value="" onClick="select_dropdown_handler_for_fast_track_packing('picker',this)">
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
                            <tr class="changed buttons">
                                <td><i class="fa fa-sign-out fa-flip-horizontal button" aria-hidden="true" onclick="close_dialog('assign_picker')"></i></td>
                                <td class="aright">
                                    <span data-data='{  "field": "Delivery Note State","value": "Undo Packed Done","dialog_name":"assign_picker"}' id="assign_picker_save_buttons" class="valid save button"
                                          onclick="save_order_operation(this)"><span class="label">{t}Save{/t}</span> <i class="fa fa-cloud fa-fw  " aria-hidden="true"></i></span></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div id="packed_done_operations" class="order_operation {if $delivery_note->get('State Index')!=70    }hide{/if}">
                    <div class="square_button right  " title="{t}Close boxes{/t}">
                        <i id="packed_done_save_buttons"
                           class="fa button fa-archive  fa-fw  {if $delivery_note->get('Delivery Note Weight Source')!='Given'  or  $delivery_note->get('Delivery Note Number Parcels')=='' }  very_discreet{/if} "
                           data-data='{  "field": "Delivery Note State","value": "Packed Done","dialog_name":"packed_done"}' aria-hidden="true" onclick="save_order_operation(this)"></i>

                    </div>
                </div>


                <div id="dispatch_operations" class="order_operation {if $delivery_note->get('State Index')!=90    } hide{/if}">
                    <div id="dispatch_operation" class="square_button right  " title="{if $order->get('Order For Collection')=='Yes' }{t}Set as collected{/t}{else}{t}Dispatched{/t}{/if}">


                        <i id="dispatch_save_buttons" class="fa button fa-paper-plane fa-fw {if $number_shippers>0 and  $order->get('Order For Collection')=='No'  and  $delivery_note->get('Delivery Note Shipper Key')==''}very_discreet{/if}  " data-data='{  "field": "Delivery Note State","value": "Dispatched","dialog_name":"dispatch"}' aria-hidden="true"
                           onclick="save_dispatch_dn(this)"></i>


                    </div>
                </div>
            </div>
        </div>

        <script>

            function show_temporal_message() {
                swal({
                    html: 'Picking delivery moved to order ( <i class=\'fa fa-keyboard\'></i> button). <div style=\'margin-top:10px\' class=\'small\'>Click <i class=\'fas fa-headset fa-fw\' style=\'color:cornflowerblue;opacity:.75\'></i> on left menu for help</div>'
                })
            }


            </script>

        <div class="{if $delivery_note->get('State Index')<90 }hide{/if}" style="text-align: center;border-bottom: 1px solid #ccc;padding:5px 0px;">
            <a class="pdf_link" target='_blank' href="/pdf/dn.pdf.php?id={$delivery_note->id}"> <img style="width: 50px;height:16px" src="/art/pdf.gif"></a>

        </div>


        <div class="date" style="padding:10px;margin-bottom:0px;text-align: center; border-bottom: 1px solid #ccc ">
            <span class="button" onclick="change_view('orders/{$order->get('Order Store Key')}/{$order->id}')"><i class="fa fa-shopping-cart" aria-hidden="true"></i> {$order->get('Public ID')}</span>
        </div>
        <div class="_items_cost" style="padding:5px 10px;margin-bottom:0px;{if  $delivery_note->get('State Index')!=100 }border-bottom: 1px solid #ccc{/if}">
            <table style="width: 100%;">


                <tr>
                    <td class="label">{t}Items cost{/t}:</td>
                    <td class="aright Items_Cost">{$delivery_note->get('Items Cost')}</td>
                </tr>
            </table>
        </div>

        <div class="state info_block  {if  $delivery_note->get('State Index')==100 }hide{/if}" style="text-align: center;padding:5px 10px;border-bottom:none;">
            <table style="width: 100%;">


                <tr>


                    <td>
                    <span ><i class="fa fa-square fa-fw discreet" aria-hidden="true"></i>
                          <span class="Number_Ordered_Parts">{$delivery_note->get('Number Ordered Parts')}</span> (<span class="Number_Ordered_Items">{$delivery_note->get('Number Ordered Items')}</span>)

               <span class="error {if $delivery_note->get('Order Number Items Out of Stock')==0}hide{/if}" style="padding-left:20px"><i class="fa fa-cube fa-fw  " aria-hidden="true"></i> <span
                           class="Order_Number_Items_with_Out_of_Stock">{$delivery_note->get('Number Items Out of Stock')}</span></span>
                    <span class="error {if $delivery_note->get('Order Number Items Returned')==0}hide{/if}" style="padding-left:20px"><i class="fa fa-thumbs-o-down fa-fw   " aria-hidden="true"></i> <span
                                class="Order_Number_Items_with_Returned">{$delivery_note->get('Number Items Returned')}</span></span>
                    </td>

                    <td>
                        <span style="padding-left:20px"><i class="fal fa-weight fa-fw discreet " aria-hidden="true"></i> <span class="Weight_Details">{$delivery_note->get('Weight Details')}</span></span>
                    </td>
                    <td>

                    <span style="padding-left:20px" class="Delivery_Note_Number_Parcels">
                        {$delivery_note->get('Number Parcels')}
                    </span>
                    </td>

                </tr>


            </table>
        </div>
    </div>


    <div class="block" style="padding:20px 10px 0px 10px">


        <table style="min-width:500px " class="info_block  {if $delivery_note->get('State Index')<70 or $delivery_note->get('State Index')>90   }hide{/if} ">


            <tr>
                <td class="aleft" style="width: 80px">

                    <div style="position: relative;left:-8px;" >
                        <input id="set_parcel_type" data-field="Delivery Note Parcel Type"  type="hidden" class="selected_parcel_type input_field" value="{if $delivery_note->get('Delivery Note Parcel Type')!=''}{$delivery_note->get('Delivery Note Parcel Type')}{else}Box{/if}">
                        <select class="parcel_types_options small" style="width: 200px">
                            <option value="Box" {if $delivery_note->get('Delivery Note Parcel Type')=='Box' or   $delivery_note->get('Delivery Note Parcel Type')==''}selected="selected"{/if} >{t}Boxes{/t}</option>
                            <option value="Pallet" {if $delivery_note->get('Delivery Note Parcel Type')=='Pallet'}selected="selected"{/if} >{t}Pallets{/t}</option>
                            <option value="Envelope" {if $delivery_note->get('Delivery Note Parcel Type')=='Envelope'}selected="selected"{/if} >{t}Envelopes{/t}</option>
                            <option value="Small Parcel" {if $delivery_note->get('Delivery Note Parcel Type')=='Small Parcel'}selected="selected"{/if} >{t}Small parcels{/t}</option>



                        </select>
                        <div class="clear:both"></div>
                    </div>

                </td>
                <td >
                    <input id="number_parcel_field" style="width:75px" value="{$delivery_note->get('Delivery Note Number Parcels')}" ovalue="{$delivery_note->get('Delivery Note Number Parcels')}"
                           placeholder="{t}number{/t}"> <i onCLick="save_number_parcels(this)" class="fa fa-cloud button hide" aria-hidden="true"></i>

                </td>
                <td class="aright"> {t}Weight{/t}:</td>
                <td ><span><input id="weight_field" style="width:75px" value="{$delivery_note->get('Weight For Edit')}" ovalue="{$delivery_note->get('Weight For Edit')}" placeholder="{t}Kg{/t}"> <i
                                onCLick="save_delivery_note_weight(this)" class="fa fa-cloud button hide" aria-hidden="true"></i>

                </td>
            </tr>

            <tr id="edit_shipper_tr" class="{if $number_shippers==0  or $order->get('Order For Collection')=='Yes'  }hide{/if}">
                <td class="aleft">{t}Courier{/t}:</td>
                <td class="aleft" colspan="3">
                    <div id="shipper" class="{if $delivery_note->get('Delivery Note Shipper Key')==''}hide{/if}">
                        {assign "shipper" $delivery_note->get('Shipper')}
                        {if $shipper}
                            <span class="Shipper_Code" title="{$shipper->get('Name')}">{$shipper->get('Code')}</span>
                        {else}
                            <span class="Shipper_Code" title=""></span>
                        {/if}

                        <i onclick="show_shipper_options()" class="fal fa-pen discreet button margin_left_10"></i>


                    </div>

                    <div id="shipper_options" class="{if $delivery_note->get('Delivery Note Shipper Key')>0}hide{/if}">

                            {foreach from=$shippers item=shipper}
                                <span id="shipper_option_{$shipper.key}" onclick="select_courier({$shipper.key})" class="button option {if $delivery_note->get('Delivery Note Shipper Key')==$shipper.key}selected{/if}" title="{$shipper.name}">{$shipper.code}</span>
                            {/foreach}
                            <span id="shipper_option_" onclick="select_courier('')" class="button option " title="{t}Skip set courier{/t}"><i class="error fa fa-ban"></i></span>


                    </div>
                </td>
            </tr>
            <tr id="edit_shipper_tracking_tr" class="{if !$delivery_note->get('Delivery Note Shipper Key')}hide{/if}">
                <td class="aleft">{t}Tracking{/t}:</td>
                <td class="aleft" colspan="3">
                    <div class="{if $delivery_note->get('State Index')!=100}hide{/if}">

                        {$delivery_note->get('Delivery Note Shipper Tracking')}

                    </div>

                    <div id="shipper_tracking_form" >
                        <input id="tracking_field" style="width:275px" value="{$delivery_note->get('Delivery Note Shipper Tracking')}" ovalue="{$delivery_note->get('Delivery Note Shipper Tracking')}" placeholder="{t}Tracking code{/t}"> <i
                                onClick="save_delivery_note_tracking(this)" class="fa fa-cloud button hide" aria-hidden="true"></i>

                    </div>
                </td>
            </tr>

        </table>


        <table class="final_info_block  {if  $delivery_note->get('State Index')!=100 }hide{/if} ">


            <tr>


                <td>
                    <span ><i class="fal fa-dot-circle fa-fw discreet" aria-hidden="true"></i>
                          <span class="Number_Ordered_Parts">{$delivery_note->get('Number Ordered Parts')}</span> (<span class="Number_Ordered_Items">{$delivery_note->get('Number Ordered Items')}</span>)

               <span class="error {if $delivery_note->get('Order Number Items Out of Stock')==0}hide{/if}" style="padding-left:20px"><i class="fa fa-cube fa-fw  " aria-hidden="true"></i> <span
                           class="Order_Number_Items_with_Out_of_Stock">{$delivery_note->get('Number Items Out of Stock')}</span></span>
                    <span class="error {if $delivery_note->get('Order Number Items Returned')==0}hide{/if}" style="padding-left:20px"><i class="fa fa-thumbs-o-down fa-fw   " aria-hidden="true"></i> <span
                                class="Order_Number_Items_with_Returned">{$delivery_note->get('Number Items Returned')}</span></span>
                </td>

                <td>
                    <span style="padding-left:20px"><i class="fal fa-weight fa-fw discreet " aria-hidden="true"></i> <span class="Weight_Details">{$delivery_note->get('Weight Details')}</span></span>
                </td>
                <td>

                    <span style="padding-left:20px" class="Delivery_Note_Number_Parcels">
                        {$delivery_note->get('Number Parcels')}
                    </span>
                </td>
                <td>
                    <span style="padding-left:20px"><i class="far fa-truck-loading fa-fw padding_right_5 " aria-hidden="true"></i> <span class="Consignment">{$delivery_note->get('Consignment')}</span></span>
                </td

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

    <div style="align-items: stretch;flex: 1;padding:0px 20px;border-left:1px solid #ccc">

        <table style="width:100%;min-height: 100px;" >

            <tr>
                <td style="width: 50%;padding:10px;border-right:1px solid whitesmoke">

                    <label>{t}Picker{/t}</label><br>

                    <input id="set_picker" type="hidden" class=" input_field" value="" has_been_valid="0"/>

                    <input id="set_picker_dropdown_select_label" field="set_picker" style="width:170px;margin-top:5px" scope="employee" parent="account" parent_key="1" class="dropdown_select"
                           data-metadata='{ "option":"only_working"}' value="{$delivery_note->get('Delivery Note Assigned Picker Alias')}" has_been_valid="0" placeholder="{t}Name{/t}"/>
                    <span id="set_picker_msg" class="msg"></span>
                    <i id="set_picker_save_button" class="fa fa-cloud save dropdown_select hide" onclick="save_this_field(this)"></i>
                    <div id="set_picker_results_container" class="search_results_container hide">

                        <table id="set_picker_results" >

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
    <div style="align-items: stretch;flex: 1;padding:0px 20px;border-left:1px solid #ccc">
        <table style="width:100%;min-height: 100px;" >

            <tr>
                <td style="width: 50%;padding:10px;border-right:1px solid whitesmoke">

                    <label>{t}Packer{/t}</label>

                    <input id="set_packer" type="hidden" class=" input_field" value="" has_been_valid="0"/>

                    <input id="set_packer_dropdown_select_label" field="set_packer" style="width:170px;margin-top:5px" scope="employee" parent="account" parent_key="1" class="dropdown_select"
                           data-metadata='{ "option":"only_working"}' value="{$delivery_note->get('Delivery Note Assigned Packer Alias')}" has_been_valid="0" placeholder="{t}Name{/t}"/>
                    <span id="set_packer_msg" class="msg"></span>
                    <i id="set_packer_save_button" class="fa fa-cloud save dropdown_select hide" onclick="save_this_field(this)"></i>
                    <div id="set_packer_results_container" class="search_results_container hide">

                        <table id="set_packer_results" >

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

    <div style="align-items: stretch;flex: 1;padding:40px 20px;border-left:1px solid #ccc;text-align: center">
        <span>{t}Picking aid{/t}</span>
        <a style="position: relative;top:2.5px;" class="pdf_link" target='_blank' href="/pdf/order_pick_aid.pdf.php?id={$delivery_note->id}"> <img style="width: 50px" src="/art/pdf.gif"></a>


    </div>

    <div style="align-items: stretch;flex: 1;padding:40px 20px;border-left:1px solid #ccc;text-align: center">

        <div class="hide fast_track_packing_operation">

            <span class="button" onclick="copy_qty_to_all_offline_picking()"><i class="fa fa-magic button" style="margin-right:2.5px" aria-hidden="true"></i><i class="fa fa-plus " style="margin-right:20px"
                                                                                                                                                                aria-hidden="true"></i></span>


            <span class="button" onclick="mark_all_offline_picking_as_done()"><i class="fa fa-bolt " style="margin-right:2.5px" aria-hidden="true"></i><i class="fa fa-check-circle " style="margin-right:20px"
                                                                                                                                                          aria-hidden="true"></i></span>
        </div>
    </div>

    <div style="align-items: stretch;flex: 1;padding:40px 20px;border-left:1px solid #ccc;text-align: center">
        <div class="hide fast_track_packing_operation">
            <span id="save_picking_offline" onclick="save_picking_offline()" class="save">{t}Pack order{/t} <i class="fa fa-cloud" aria-hidden="true"></i></span>
        </div>
    </div>
</div>

<script>

    var out_of_stock_dialog_open = false;




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






    $("#start_picking").on( 'click',function () {

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

    $("#start_packing").on( 'click',function () {

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


    $(document).on('input propertychange', '#number_parcel_field', function () {



        if ($(this).val() != $(this).attr('ovalue')) {
            $(this).closest('td').find('i').removeClass('hide')
        } else {
            $(this).closest('td').find('i').addClass('hide')

        }

        var validation = validate_number($(this).val(), 0, 1000)

        if (validation) {

            $(this).closest('td').find('i').addClass('error').removeClass('valid save changed')

        } else {
            $(this).closest('td').find('i').removeClass('error').addClass('valid save changed')
        }


    })


    $(document).on('input propertychange', '#tracking_field', function () {



        if ($(this).val() != $(this).attr('ovalue')) {
            $(this).closest('td').find('i').removeClass('hide')
        } else {
            $(this).closest('td').find('i').addClass('hide')

        }



        $(this).closest('td').find('i').removeClass('error').addClass('valid save changed')



    })


    $(document).on('click', '#exit_fast_track_packing', function () {


        show_fast_track_packing($('#show_fast_track_packing_button'))
    })


   $('.parcel_types_options').niceSelect();

    $( ".parcel_types_options" ).on('change',
        function() {

            var value=$( ".parcel_types_options option:selected" ).val();



            var request = '/ar_edit.php?tipo=edit_field&object=DeliveryNote&key=' + $('#delivery_note').attr('dn_key') + '&field=Delivery_Note_Parcel_Type&value=' + value + '&metadata={}';
            console.log(request)

            var form_data = new FormData();

            form_data.append("tipo", 'edit_field')
            form_data.append("field", 'Delivery_Note_Parcel_Type')
            form_data.append("object", 'DeliveryNote')
            form_data.append("key", $('#delivery_note').attr('dn_key'))
            form_data.append("value", value)
            var request = $.ajax({

                url: "/ar_edit.php", data: form_data, processData: false, contentType: false, type: 'POST', dataType: 'json'

            })


            request.done(function (data) {
                if (data.state == 200) {

                    for (var key in data.update_metadata.class_html) {
                        $('.' + key).html(data.update_metadata.class_html[key])
                    }



                } else if (data.state == 400) {
                    sweetAlert(data.msg);
                }

            })


            request.fail(function (jqXHR, textStatus) {
                console.log(textStatus)

                console.log(jqXHR.responseText)


            });


        });





</script>