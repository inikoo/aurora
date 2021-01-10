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



<div class="timeline_horizontal">

    <input type="hidden" id="Consignment_State_Index" value="{$consignment->get('State Index')}">

    <ul class="timeline" id="timeline">


        <li id="order_node" class="li complete">
            <div class="label">
                <span class="state ">{t}Shuddered dispatch{/t}</span>
                <span class="start Order_Public_ID button" >{t}Created{/t} </span>
            </div>
            <div class="timestamp">
                <span class="Date_Created">&nbsp;{$consignment->get('Scheduled Date')} x</span>
                <span class="start_date Order_Created_Date">{$consignment->get('Creation Date')} </span>
            </div>
            <div class="dot"></div>
        </li>


        <li id="start_picking_node"
            class="li  {if $consignment->get('State Index')>=20   }complete{/if} {if $consignment->get('State Index')<0 } {if   $consignment->get('Delivery Note Date Start Picking')=='' }hide{else}complete{/if}{/if}">
            <div class="label">
                <span class="state Consignment_Packed_Label">{t}Closed{/t}<span></i></span></span>
            </div>
            <div class="timestamp">
                <span class="Consignment_Packed_Percentage_or_Datetime">&nbsp{$consignment->get('Start Picking Datetime')}&nbsp;</span>
            </div>
            <div class="dot"></div>
        </li>




        <li id="dispatched_node" class="li  {if $consignment->get('State Index')>=100  }complete{/if}  {if $consignment->get('State Index')<0 }hide{/if}   ">
            <div class="label">
                <span class="state ">{t}Dispatched{/t} <span></i></span></span>
            </div>
            <div class="timestamp">
                <span class="Consignment_Dispatched_Datetime">&nbsp;{$consignment->get('Dispatched Datetime')}</span>
            </div>
            <div class="dot"></div>
        </li>


        {if $consignment->get('State Index')<0   }
            <li id="received_node" class="li  cancelled">
                <div class="label">
                    <span class="state ">{t}Cancelled{/t}</span>
                </div>
                <div class="timestamp">
                    <span >&nbsp;{$consignment->get('Cancelled Datetime')}</span>
                </div>
                <div class="dot"></div>
            </li>
        {/if}


    </ul>
</div>

<div id="delivery_note" class="order" data-object='{$object_data}'  dn_key="{$consignment->id}" style="display: flex;">
    <div class="block" style="padding:10px 20px;position: relative">


      





    </div>


    <div class="block ">
        <div class="state" style="height:30px;margin-bottom:0px;position:relative;top:-5px;min-width: 250px">
            <div id="back_operations">
                <div id="delete_operations" class="order_operation {if $consignment->get('Delivery Note Number Picked Items')>0       }hide{/if}">
                    <div class="square_button left" xstyle="padding:0;margin:0;position:relative;top:-5px" title="{t}delete{/t}">
                        <i class="far fa-trash-alt very_discreet " aria-hidden="true" onclick="toggle_order_operation_dialog('delete')"></i>
                        <table id="delete_dialog" class="order_operation_dialog hide">
                            <tr class="top">
                                <td colspan="2" class="label">{t}Delete delivery note{/t}</td>
                            </tr>
                            <tr class="changed buttons">
                                <td><i class="fa fa-sign-out fa-flip-horizontal button" aria-hidden="true" onclick="close_dialog('delete')"></i></td>
                                <td class="aright"><span data-data='{ "object": "DeliveryNote", "key":"{$consignment->id}"    }' id="delete_save_buttons" class="error save button" onclick="delete_object(this)"><span
                                                class="label">{t}Delete{/t}</span> <i class="fa fa-trash fa-fw" aria-hidden="true"></i></span>
                                </td>

                            </tr>
                        </table>
                    </div>
                </div>

                <div id="undo_picking_operations" class="order_operation {if $consignment->get('State Index')!=20}hide{/if}">
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
                     class="order_operation {if $consignment->get('Delivery Note Number Picked Items')==0  or  $consignment->get('State Index')<0 or  $consignment->get('State Index')>=80  }hide{/if}">
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

                <div id="undo_packed_done_operations" class="order_operation {if $consignment->get('State Index')!=80}hide{/if}">
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
            <span style="float:left;padding-left:10px;padding-top:5px" class="Consignment_State"> {$consignment->get('State')} </span>
            <div id="forward_operations">



                <div id="packed_done_operations" class="order_operation {if $consignment->get('State Index')!=70    }hide{/if}">
                    <div class="square_button right  " title="{t}Close boxes{/t}">
                        <i id="packed_done_save_buttons"
                           class="fa button fa-archive  fa-fw  {if $consignment->get('Delivery Note Weight Source')!='Given'  or  $consignment->get('Delivery Note Number Parcels')=='' }  very_discreet{/if} "
                           data-data='{  "field": "Delivery Note State","value": "Packed Done","dialog_name":"packed_done"}' aria-hidden="true" onclick="save_order_operation(this)"></i>

                    </div>
                </div>



            </div>
        </div>



        <div class="{if $consignment->get('State Index')<90 }hide{/if}" style="text-align: center;border-bottom: 1px solid #ccc;padding:5px 0px;">
            <a class="pdf_link" target='_blank' href="/pdf/dn.pdf.php?id={$consignment->id}"> <img style="width: 50px;height:16px" src="/art/pdf.gif"></a>

        </div>



        <div class="_items_cost" style="padding:5px 10px;margin-bottom:0px;{if  $consignment->get('State Index')!=100 }border-bottom: 1px solid #ccc{/if}">
            <table style="width: 100%;">


                <tr>
                    <td class="label">{t}Items cost{/t}:</td>
                    <td class="aright Items_Cost">{$consignment->get('Items Cost')}</td>
                </tr>
            </table>
        </div>

        <div class="state info_block  {if  $consignment->get('State Index')==100 }hide{/if}" style="text-align: center;padding:5px 10px;border-bottom:none;">
            <table style="width: 100%;">


                <tr>


                    <td>
                    <span ><i class="fal fa-bars fa-fw discreet" title="{t}Items{/t}"></i><i class="fal fa-box small fa-fw discreet" title="{t}Items{/t}"></i>
                          <span title="{t}Distinct items{/t}" class="Number_Ordered_Parts">{$consignment->get('Number Ordered Parts')}</span> (<span title="{t}Required{/t}" class="Number_Ordered_Items">{$consignment->get('Number Ordered Items')}</span>)

               <span class="error {if $consignment->get('Order Number Items Out of Stock')==0}hide{/if}" style="padding-left:20px"><i class="fa fa-cube fa-fw  " aria-hidden="true"></i> <span
                           class="Order_Number_Items_with_Out_of_Stock">{$consignment->get('Number Items Out of Stock')}</span></span>
                    <span class="error {if $consignment->get('Order Number Items Returned')==0}hide{/if}" style="padding-left:20px"><i class="fa fa-thumbs-o-down fa-fw   " aria-hidden="true"></i> <span
                                class="Order_Number_Items_with_Returned">{$consignment->get('Number Items Returned')}</span></span>
                    </td>

                    <td>
                        <span style="padding-left:20px"><i class="fal fa-weight fa-fw discreet " aria-hidden="true"></i> <span class="Weight_Details">{$consignment->get('Weight Details')}</span></span>
                    </td>
                    <td>

                    <span style="padding-left:20px" class="Consignment_Number_Parcels">
                        {$consignment->get('Number Parcels')}
                    </span>
                    </td>

                </tr>


            </table>
        </div>
    </div>


    <div class="block" style="padding:20px 10px 0px 10px">


        <table style="min-width:500px " class="info_block  {if $consignment->get('State Index')<70 or $consignment->get('State Index')>90 or $consignment->get('Delivery Note Using Shipper API')=='Yes'  }hide{/if} ">


            <tr>
                <td class="aleft" style="width: 80px">

                    <div style="position: relative;left:-8px;" >
                        <input id="set_parcel_type" data-field="Delivery Note Parcel Type"  type="hidden" class="selected_parcel_type input_field" value="{if $consignment->get('Delivery Note Parcel Type')!=''}{$consignment->get('Delivery Note Parcel Type')}{else}Box{/if}">
                        <select class="parcel_types_options small" style="width: 200px">
                            <option value="Box" {if $consignment->get('Delivery Note Parcel Type')=='Box' or   $consignment->get('Delivery Note Parcel Type')==''}selected="selected"{/if} >{t}Boxes{/t}</option>
                            <option value="Pallet" {if $consignment->get('Delivery Note Parcel Type')=='Pallet'}selected="selected"{/if} >{t}Pallets{/t}</option>
                            <option value="Envelope" {if $consignment->get('Delivery Note Parcel Type')=='Envelope'}selected="selected"{/if} >{t}Envelopes{/t}</option>
                            <option value="Small Parcel" {if $consignment->get('Delivery Note Parcel Type')=='Small Parcel'}selected="selected"{/if} >{t}Small parcels{/t}</option>



                        </select>
                        <div class="clear:both"></div>
                    </div>

                </td>
                <td >
                    <input id="number_parcel_field" style="width:75px" value="{$consignment->get('Delivery Note Number Parcels')}" ovalue="{$consignment->get('Delivery Note Number Parcels')}"
                           placeholder="{t}number{/t}"> <i onCLick="save_number_parcels(this)" class="fa fa-cloud button hide" aria-hidden="true"></i>

                </td>
                <td class="aright"> {t}Weight{/t}:</td>
                <td ><span><input id="weight_field" style="width:75px" value="{$consignment->get('Weight For Edit')}" ovalue="{$consignment->get('Weight For Edit')}" placeholder="{t}Kg{/t}"> <i
                                onCLick="save_delivery_note_weight(this)" class="fa fa-cloud button hide" aria-hidden="true"></i>

                </td>
            </tr>


            <tr id="edit_shipper_tracking_tr" class="{if !$consignment->get('Delivery Note Shipper Key')}hide{/if}">
                <td class="aleft">{t}Tracking{/t}:</td>
                <td class="aleft" colspan="3">
                    <div class="{if $consignment->get('State Index')!=100   }hide{/if}">

                        {$consignment->get('Delivery Note Shipper Tracking')}

                    </div>

                    <div id="shipper_tracking_form" >
                        <input id="tracking_field" style="width:275px" value="{$consignment->get('Delivery Note Shipper Tracking')}" ovalue="{$consignment->get('Delivery Note Shipper Tracking')}" placeholder="{t}Tracking code{/t}"> <i
                                onClick="save_delivery_note_tracking(this)" class="fa fa-cloud button hide" aria-hidden="true"></i>

                    </div>
                </td>
            </tr>

        </table>


        <table border="0" class="final_info_block  {if  !($consignment->get('State Index')==100  or $consignment->get('Delivery Note Using Shipper API')=='Yes') }hide{/if} ">


            <tr>


                <td>
                    <span ><i class="fal fa-dot-circle fa-fw discreet" aria-hidden="true"></i>
                          <span class="Number_Ordered_Parts">{$consignment->get('Number Ordered Parts')}</span> (<span class="Number_Ordered_Items">{$consignment->get('Number Ordered Items')}</span>)

               <span class="error {if $consignment->get('Order Number Items Out of Stock')==0}hide{/if}" style="padding-left:20px"><i class="fa fa-cube fa-fw  " aria-hidden="true"></i> <span
                           class="Order_Number_Items_with_Out_of_Stock">{$consignment->get('Number Items Out of Stock')}</span></span>
                    <span class="error {if $consignment->get('Order Number Items Returned')==0}hide{/if}" style="padding-left:20px"><i class="fa fa-thumbs-o-down fa-fw   " aria-hidden="true"></i> <span
                                class="Order_Number_Items_with_Returned">{$consignment->get('Number Items Returned')}</span></span>
                </td>

                <td>
                    <span style="padding-left:20px"><i class="fal fa-weight fa-fw discreet " aria-hidden="true"></i> <span class="Weight_Details">{$consignment->get('Weight Details')}</span></span>
                </td>
                <td>

                    <span style="padding-left:20px" class="Consignment_Number_Parcels">
                        {$consignment->get('Number Parcels')}
                    </span>
                </td>


            </tr>

            <tr>
                <td colspan="2">
                    <span style="padding-left:20px"><i class="far fa-truck-loading fa-fw padding_right_5 " aria-hidden="true"></i> <span class="Consignment">{$consignment->get('Consignment')}</span></span>
                </td>
                <td>
                    {if ( $consignment->get('Delivery Note Using Shipper API')=='Yes' and  ( $consignment->get('State Index')!=100 or ( $consignment->get('State Index')==100 and $consignment->get('dispatched_since')<86400   )  )  ) }
                        <i class="fa fa-barcode-read"></i> {t}Label{/t} </a>
                    {/if}
                </td>
            </tr>



        </table>


    </div>

</div>

<div id="set_out_of_stock_items_dialog" class="hide" style="position:absolute;border:1px solid #ccc;background-color: white;padding:10px 20px;z-index: 100">
    <table>

        <tr>
            <td>{t}Out of stock{/t}

                <input class="picked_qty width_50" value="" ovalue=""/> <i onClick="save_item_out_of_stock_qty_change(this)" class="fa  fa-plus fa-fw button add_picked %s" aria-hidden="true"></i>


            </td>
        </tr>

        <tr class="hide">
            <td class="out_of_stock_location_code"></td>
            <td class="out_of_stock_part_reference"></td>
            <td class="out_of_stock_part_stock"></td>

        </tr>

    </table>
</div>

<span id="dn_data" class="hide" dn_key="{$consignment->id}" picker_key="{$consignment->get('Delivery Note Assigned Picker Key')}" packer_key="{$consignment->get('Delivery Note Assigned Packer Key')}"
      no_picker_msg="{t}Please assign picker{/t}" no_packer_msg="{t}Please assign packer{/t}"></span>

<div class="table_new_fields delivery_note_handling_fields   {if $consignment->get('State Index')<=10    }hide{/if} " style="border-bottom:1px solid #ccc;">

    <div style="align-items: stretch;flex: 1;padding:0px 20px;border-left:1px solid #ccc">

        <table style="width:100%;min-height: 100px;" >

            <tr>
                <td style="width: 50%;padding:10px;border-right:1px solid whitesmoke">

                    <label>{t}Picker{/t}</label><br>

                    <input id="set_picker" type="hidden" class=" input_field" value="" has_been_valid="0"/>

                    <input id="set_picker_dropdown_select_label" field="set_picker" style="width:170px;margin-top:5px" scope="employee" parent="account" parent_key="1" class="dropdown_select"
                           data-metadata='{ "option":"only_working"}' value="{$consignment->get('Delivery Note Assigned Picker Alias')}" has_been_valid="0" placeholder="{t}Name{/t}"/>
                    <span id="set_picker_msg" class="msg"></span>
                    <i id="set_picker_save_button" class="fa fa-cloud save dropdown_select hide" onclick="save_this_field(this)"></i>
                    <div id="set_picker_results_container" class="search_results_container hide">

                        <table id="set_picker_results" >

                            <tr class="hide" id="set_picker_search_result_template" field="" value="" formatted_value="" data-shortcut="select_picker" onClick="select_dropdown_handler('picker',this);post_select_dropdown_picker_packer_handler('picker',this)">
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
                           data-metadata='{ "option":"only_working"}' value="{$consignment->get('Delivery Note Assigned Packer Alias')}" has_been_valid="0" placeholder="{t}Name{/t}"/>
                    <span id="set_packer_msg" class="msg"></span>
                    <i id="set_packer_save_button" class="fa fa-cloud save dropdown_select hide" onclick="save_this_field(this)"></i>
                    <div id="set_packer_results_container" class="search_results_container hide">

                        <table id="set_packer_results" >

                            <tr class="hide" id="set_packer_search_result_template" field="" value="" formatted_value="" data-shortcut="select_packer" onClick="select_dropdown_handler('packer',this);post_select_dropdown_picker_packer_handler('packer',this)">

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
        <a style="position: relative;top:2.5px;" class="pdf_link" target='_blank' href="/pdf/order_pick_aid.pdf.php?id={$consignment->id}"> <img style="width: 50px" src="/art/pdf.gif"></a>


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

