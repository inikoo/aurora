{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 18 December 2018 at 15:00:57 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3
-->
*}

<style>
    span.button.option {
        border: 1px solid #ccc;
        padding: 2px 10px 2px 10px;
    }
    span.button.option.selected {
        border: 1px solid #000;
        color:#000;
    }


    .input_picking_sheet_table{
        width:100%;
        padding-bottom: 10px;
    }

    .input_picking_sheet_table td{
        padding:10px 10px 10px 10px;
    }

    .input_picking_sheet_table td.label{

        border-left: 1px solid #ccc;
    }

    .input_picking_sheet_table .top td{
        padding-bottom:0px;padding-top:15px;
    }



</style>




<div  style="border-bottom: 1px solid #ccc;">

<table class="input_picking_sheet_table"  data-order_key="{$order->id}" data-delivery_note_key="{$dn->id}" border="0" data-labels='{
"missing_fields":"{t}Missing required fields:{/t}",
"packer":"{t}Packer{/t}",
"picker":"{t}Picker{/t}",
"parcels":"{t}Number of parcels{/t}",
"shipper":"{t}Courier{/t}",
"are_you_sure":"{t}Are you sure you want to continue?{/t}",
"issues":"{t}There is some issues with this order{/t}",
"confirm_button_text":"{t}Yes, continue{/t}",
"out_of_stock":"{t}Some products where mark as out of stock{/t}",
"tracking_number":"{t}Tracking number is missing{/t}",

"title_no_stock":"{t}There is not enough stock in this location{/t}",
"text_no_stock":"{t}Are you sure you want to proceed?{/t}",
"yes_text_no_stock":"{t}Yes unlock it{/t}",
"no_text_no_stock":"{t}No, I replenish the location{/t}"



}'>

    <tr class="top">
        <td class="label" >



            <label>{t}Picker{/t}</label>

            <input id="set_picker" type="hidden" data-field="Delivery Note Assigned Picker Key" class=" input_field" value="{$store->settings('data_entry_picking_aid_default_picker')}" has_been_valid="0"/>

            <input id="set_picker_dropdown_select_label" field="set_picker" style="width:170px"  name="picker" autocomplete="off"
                   scope="employee" parent="account"
                   parent_key="1" class="dropdown_select"
                   data-metadata='{ "option":"only_working"}'
                   value="{if $dn->get('Delivery Note Assigned Picker Key')>0 }{$dn->get('Delivery Note Assigned Picker Name')}{else}{$store->get('data entry picking aid default picker')}{/if}"
                   has_been_valid="0"
                   placeholder="{t}Name{/t}"/>
            <span id="set_picker_msg" class="msg"></span>

            <div id="set_picker_results_container" class="search_results_container hide">

                <table id="set_picker_results" border="0"  >

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

        <td class="label" style="width: 1%" >

            <label>{t}Weight{/t}</label>

        </td>

        <td  >

            <input id="set_dn_weight" data-field="Delivery Note Weight" class=" width_50 input_field  field_to_check" type="number"  min="0" value="" has_been_valid="0"/> Kg

        </td>


        <td class="label {if $order->get('Order For Collection')=='Yes'}hide{/if}" rowspan="2" style="padding: 0px 20px">


            <div >
                <input id="set_shipper" data-field="Delivery Note Shipper Key"  type="hidden" class="selected_shipper input_field" value="{if $order->get('Order For Collection')=='No'}{if $store->settings('data_entry_picking_aid_default_shipper')>0}{$store->settings('data_entry_picking_aid_default_shipper')}{else}__none__{/if}{/if}">
                 <select class="shippers_options small" style="width: 200px">
                    <option data-display="{t}Select courier{/t}"   {if $order->get('Order For Collection')=='Yes'}selected="selected"{/if}  value="">{t}No courier{/t}</option>
                    {foreach from=$shippers item=shipper}
                        <option value="{$shipper.key}" {if $store->settings('data_entry_picking_aid_default_shipper')==$shipper.key  and $order->get('Order For Collection')=='No'  }selected="selected"{/if} >{$shipper.code}</option>
                    {/foreach}


                </select>
                <div class="clear:both"></div>
            </div>
            <div style="clear: both;padding-top: 10px">
            <input id="set_tracking_number" data-field="Delivery Note Shipper Tracking" class="tracking_number input_field field_to_check" placeholder="{t}Tracking number{/t}">
            </div>

        </td>

        <td class="label" rowspan="2" style="padding: 0px 20px">
            <input type="hidden" class="order_data_entry_picking_aid_state_after_save"  value="{if $store->settings('data_entry_picking_aid_state_after_save')==''  }0{else}{$store->settings('data_entry_picking_aid_state_after_save')}{/if}" >
            <div><span data-level="L10" class="button L10" onclick="change_order_data_entry_picking_aid_state_after_save(this)"><i class="far {if $store->settings('data_entry_picking_aid_state_after_save')>=10}fa-check-square{else}fa-square{/if}"></i>  {t}Set as closed{/t} </span></div>
            {if $dn->get('Delivery Note Type')!='Replacement'}
            <div style="margin-top:5px;margin-bottom: 5px"><span  data-level="L20" class="button L20" onclick="change_order_data_entry_picking_aid_state_after_save(this)"><i class="far {if $store->settings('data_entry_picking_aid_state_after_save')>=20}fa-check-square{else}fa-square{/if}"></i>  {t}Create invoice{/t}  </span></div>
            {else}
                <div class="hide"><span  data-level="L20" class="button L20" ><i class="far fa-square"></i>  </span></div>

            {/if}
            <div><span class="button L30"  data-level="L30" onclick="change_order_data_entry_picking_aid_state_after_save(this)"><i class="far {if $store->settings('data_entry_picking_aid_state_after_save')>=30}fa-check-square{else}fa-square{/if}"></i>  {t}Set as dispatched{/t} </span> </div>
        </td>

        <td class="label" rowspan="2" style="padding: 0px 20px">

            <div  style="margin-bottom: 5px;font-size: x-small;position: relative;bottom: 10px"><span class="button" onclick="close_data_entry_delivery_note()"><i class="fa fa-sign-out fa-flip-horizontal fa-fw"></i>  {t}Cancel{/t} </span></div>

            <div>
                <span class="save" onclick="confirm_save_data_entry_picking_aid(this)"> {t}Save{/t} <i class="save_data_entry_picking_aid_icon fas fa-cloud "></i>  </span>
            </div>
        </td>

    </tr>

    <tr class="_bottom">
        <td style="" >



            <label>{t}Packer{/t}</label>

            <input id="set_packer" type="hidden" data-field="Delivery Note Assigned Packer Key" class=" input_field" value="{$store->settings('data_entry_picking_aid_default_packer')}" has_been_valid="0"/>

            <input id="set_packer_dropdown_select_label" field="set_packer" style="width:170px" name="packer" autocomplete="off"
                   scope="employee" parent="account"
                   parent_key="1" class="dropdown_select"
                   data-metadata='{ "option":"only_working"}'
                   value="{if $dn->get('Delivery Note Assigned Packer Key')>0 }{$dn->get('Delivery Note Assigned Packer Name')}{else}{$store->get('data entry picking aid default packer')}{/if}"
                   has_been_valid="0"
                   placeholder="{t}Name{/t}"/>
            <span id="set_packer_msg" class="msg"></span>

            <div id="set_packer_results_container" class="search_results_container hide">

                <table id="set_packer_results" border="0"  >

                    <tr class="hide" id="set_packer_search_result_template" field="" value=""
                        formatted_value="" onClick="select_dropdown_handler_for_fast_track_packing('packer',this)">
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
        <td class="label" style="width: 1%" >


            <div >
                <input id="set_parcel_type" data-field="Delivery Note Parcel Type"  type="hidden" class="selected_parcel_type input_field" value="{if $store->settings('data_entry_picking_aid_default_parcel_type')!=''}{$store->settings('data_entry_picking_aid_default_parcel_type')}{else}Box{/if}">
                <select class="parcel_types_options small" style="width: 200px">
                    <option value="Box" {if $store->settings('data_entry_picking_aid_default_parcel_type')=='Box' or $store->settings('data_entry_picking_aid_default_parcel_type')==''}selected="selected"{/if} >{t}Boxes{/t}</option>
                    <option value="Pallet" {if $store->settings('data_entry_picking_aid_default_parcel_type')=='Pallet'}selected="selected"{/if} >{t}Pallets{/t}</option>
                    <option value="Envelope" {if $store->settings('data_entry_picking_aid_default_parcel_type')=='Envelope'}selected="selected"{/if} >{t}Envelope{/t}</option>
                    <option value="Small Parcel" {if $store->settings('data_entry_picking_aid_default_parcel_type')=='Small Parcel'}selected="selected"{/if} >{t}Small parcel{/t}</option>



                </select>
                <div class="clear:both"></div>
            </div>



        </td>
        <td style="" >

            <input id="set_dn_parcels" data-field="Delivery Note Number Parcels" class=" width_50 field_to_check input_field " type="number"  min="0"  value="{$store->settings('data_entry_picking_aid_default_number_boxes')}" has_been_valid="0"/>

        </td>



    </tr>




</table>
    <div style="clear: both">

    </div>
</div>


<script>

    confirmButtonText: 'Yes, delete it!'

    $('.shippers_options').niceSelect();

    $( ".shippers_options" ).on('change',
        function() {

        var value=$( ".shippers_options option:selected" ).val();
        $('.selected_shipper').val(value)

        if(value==''){
            $( ".shippers_options .current" ).html('{t}No courier{/t}')
            $('.tracking_number').addClass('invisible')
        }else{
            $('.tracking_number').removeClass('invisible')
        }
            validate_data_entry_picking_aid()

    });

    $('.parcel_types_options').niceSelect();

    $( ".parcel_types_options" ).on('change',
        function() {

            var value=$( ".parcel_types_options option:selected" ).val();
            $('.selected_parcel_type').val(value)


            validate_data_entry_picking_aid()

        });


    var check_list = {
        'picker':          { filled:false,valid:true},
        'packer':          { filled:false,valid:true},
        'weight':         { filled:false,valid:true},
        'parcels':         { filled:false,valid:true},
        'shipper':        { filled:false,valid:true},
        'tracking_number': { filled:false,valid:true},
        'items':           { filled:true,valid:true},
    };





</script>