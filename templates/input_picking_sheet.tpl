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


    .input_picking_sheet_table .parcels td{
        padding-top:0px;padding-left:0px;padding-right:0px;
    }

</style>

{if  isset($order) and  isset($dn)}{assign 'scope' 'data_entery_delivery_note' }{else}{assign 'scope' 'bulk_order_data_entry' }{/if}

{if  isset($order) and  $order->get('Order For Collection')=='No'}{assign 'with_shipping' false }{else}{assign 'with_shipping' true }{/if}

{if $parent->settings('data_entry_picking_aid_parcel_types')!=''}
    {assign number_parcel_types count($parent->settings('data_entry_picking_aid_parcel_types'))}
{else}
    {assign number_parcel_types 0}
{/if}

{if $parent->settings('data_entry_picking_aid_default_parcel')!=''}
    {assign parcel_default_dimensions $parent->settings('data_entry_picking_aid_default_parcel') }

{else}
    {assign parcel_default_dimensions ['','',''] }

{/if}
{if   $order->get('State Index')>=50 and $order->get('State Index')<100  }
    {assign is_packed 'Yes'}

{else}
    {assign is_packed 'No'}

{/if}



<div  style="border-bottom: 1px solid #ccc;">

<table class="input_picking_sheet_table"  data-order_key="{if isset($order)}{$order->id}{/if}" data-delivery_note_key="{if isset($dn)}{$dn->id}{/if}" data-labels='{
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
        <td class="label {if $is_packed=='Yes'}invisible{/if} " >



            <label>{t}Picker{/t}</label>

            <input id="set_picker" type="hidden" data-field="Delivery Note Assigned Picker Key" class=" input_field" value="{$parent->settings('data_entry_picking_aid_default_picker')}" has_been_valid="0"/>

            <input id="set_picker_dropdown_select_label" field="set_picker" style="width:170px"  name="picker" autocomplete="off"
                   scope="employee" parent="account"
                   parent_key="1" class="dropdown_select"
                   data-metadata='{ "option":"only_working"}'
                   value="{if isset($dn) and $dn->get('Delivery Note Assigned Picker Key')>0 }{$dn->get('Delivery Note Assigned Picker Name')}{else}{$parent->get('data entry picking aid default picker')}{/if}"
                   has_been_valid="0"
                   placeholder="{t}Name{/t}"/>
            <span id="set_picker_msg" class="msg"></span>

            <div id="set_picker_results_container" class="search_results_container hide">

                <table id="set_picker_results" >

                    <tr class="hide" id="set_picker_search_result_template" field="" value="" data-shortcut="select_picker_picking_aid"
                        formatted_value="" onClick="select_dropdown_handler('picker',this);validate_data_entry_picking_aid()">
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


        <td class="label {if $is_packed=='Yes'}invisible{/if} "  rowspan="2" style="padding-top:0px">


            <table class="parcels" >
                <tr>
                    <td style="padding-right:20px">
                        <i  onclick="add_parcel(this)" class="fa fa-plus add_parcel button parcel_dimension_inputs "></i>
                    </td>
                    <td><i class="fal fa-weight" title="{t}Weight{/t}"></i> Kg</td>
                    <td style="padding-left:20px" class="parcel_dimension_inputs"><i class="fal fa-ruler-triangle" title="{t}Dimensions{/t}"></i> cm</td>

                </tr>
                <tr class="parcel_tr">
                    <td style="padding-right:20px">
                        <i  onclick="delete_parcel(this)" class="fal fa-trash-alt delete_parcel hide button"></i>
                    </td>
                    <td>
                    <input  class=" parcel_weight width_40   field_to_check"  min="0" value="" has_been_valid="0"/>
                    </td>
                    <td  class="parcel_dimension_inputs" style="padding-left:20px">

                        <input  value="{$parcel_default_dimensions[0]}" class="dim_0 parcel_dimension width_30 input_field  field_to_check"   min="0" value="" has_been_valid="0"/>
                        <input  value="{$parcel_default_dimensions[1]}" class="dim_1 parcel_dimension width_30 input_field  field_to_check"   min="0" value="" has_been_valid="0"/>
                        <input  value="{$parcel_default_dimensions[2]}" class="dim_2 parcel_dimension width_30 input_field  field_to_check"   min="0" value="" has_been_valid="0"/>
                        <i onclick="show_parcel_types(this)" class="button fa fa-bars {if $number_parcel_types==0}hide{/if}"></i>
                    </td>



                </tr>
            </table>




        </td>




        <td class="label  {if $is_packed=='Yes'}invisible{/if}   {if !$with_shipping}hide{/if}" rowspan="2" style="padding: 0px 20px">


            <div >
                <input id="set_shipper" data-field="Delivery Note Shipper Key"  type="hidden" class="selected_shipper input_field" value="{if with_shipping}{if $parent->settings('data_entry_picking_aid_default_shipper')>0}{$parent->settings('data_entry_picking_aid_default_shipper')}{else}__none__{/if}{/if}">
                 <select class="shippers_options small" style="width: 200px">
                    <option data-display="{t}Select courier{/t}"   {if !$with_shipping}selected="selected"{/if}  value="">{t}No courier{/t}</option>
                    {foreach from=$shippers item=shipper}
                        <option data-api_key="{$shipper.api_key}"  data-code="{$shipper.code}"  value="{$shipper.key}" {if $parent->settings('data_entry_picking_aid_default_shipper')==$shipper.key  and $with_shipping }selected="selected"{/if} >{$shipper.code} {if $shipper.api_key!=''}(API){/if} </option>
                    {/foreach}


                </select>
                <div class="clear:both"></div>
            </div>
            <div class="tracking_number_container" style="clear: both;padding-top: 10px">
            <input id="set_tracking_number" data-field="Delivery Note Shipper Tracking" class="tracking_number input_field field_to_check" placeholder="{t}Tracking number{/t}">
            </div>

            <div class="apc_service_type hide" style="clear: both;padding-top: 10px"  >
                <div class="button">
                <span data-service="CP16" class="service_type CP16" onclick="change_shipper_service_type(this)"><i data-field="service_type" class="far fa-circle"></i>  {t}Courier pack{/t} </span>
                </div>
                <div class="button">
                    <span data-service="MP16" class="service_type MP16" onclick="change_shipper_service_type(this)"><i data-field="service_type" class="far fa-circle"></i>  {t}Mailpack{/t} </span>
                </div>
            </div>


        </td>
        <td class="label" rowspan="2" style="padding: 0px 20px">



            <input type="hidden"  id="is_order_packed" value="{$is_packed}" />

            <input type="hidden" class="order_data_entry_picking_aid_state_after_save"
                   value="{if $parent->settings('data_entry_picking_aid_state_after_save')=='' or   $parent->settings('data_entry_picking_aid_state_after_save')==0 }{if $is_packed=='Yes'}10{else}0{/if}{elseif $parent->settings('data_entry_picking_aid_state_after_save')=='5'}{if $is_packed=='Yes'}10{else}5{/if}{else}{$parent->settings('data_entry_picking_aid_state_after_save')}{/if}" >



            <div class="{if $is_packed=='Yes'}hide{/if}"><span data-level="L5" class=" L5" ><i class="far fa-check-square"></i> {t}Set as packed{/t} </span></div>

            <div style="margin-top:5px;margin-bottom: 5px"><span data-level="L10" class="{if $order->get('State Index')<50}button{/if} L10" {if $order->get('State Index')<50}onclick="change_order_data_entry_picking_aid_state_after_save(this)"{/if}><i class="far {if $parent->settings('data_entry_picking_aid_state_after_save')>=10 or $is_packed=='Yes'}fa-check-square{else}fa-square{/if}"></i>  {t}Mark out of stocks{/t} </span></div>


            {if  isset($dn)  and  !( $dn->get('Delivery Note Type')=='Order'  or  $dn->get('Delivery Note Type')=='Sample' or  $dn->get('Delivery Note Type')=='Donation' )    }
                <div class="hide"><span  data-level="L20" class="button L20" ><i class="far fa-square"></i>  </span></div>

            {else}
                <div style="margin-top:5px;margin-bottom: 5px"><span  data-level="L20" class="button L20" onclick="change_order_data_entry_picking_aid_state_after_save(this)"><i class="far {if $parent->settings('data_entry_picking_aid_state_after_save')>=20}fa-check-square{else}fa-square{/if}"></i>  {t}Create invoice{/t}  </span></div>


            {/if}
            <div><span class="button L30"  data-level="L30" onclick="change_order_data_entry_picking_aid_state_after_save(this)"><i class="far {if $parent->settings('data_entry_picking_aid_state_after_save')>=30}fa-check-square{else}fa-square{/if}"></i>  {t}Set as dispatched{/t} </span> </div>
        </td>

        <td class="label" rowspan="2" style="padding: 0px 20px">
            {if $scope=='data_entery_delivery_note'}
            <div  style="margin-bottom: 5px;font-size: x-small;position: relative;bottom: 10px">
                <span class="button" onclick="close_data_entry_delivery_note()"><i class="fa fa-sign-out fa-flip-horizontal fa-fw"></i>  {t}Cancel{/t} </span>
            </div>
            <div>
                <span class="save" onclick="confirm_save_data_entry_picking_aid(this)"> {t}Save{/t} <i class="save_data_entry_picking_aid_icon fas fa-cloud "></i>  </span>
            </div>
            {else}
                <div  style="margin-bottom: 5px;font-size: x-small;position: relative;bottom: 10px">
                    <span class="button" onclick="close_bulk_order_data_entry()"><i class="fa fa-sign-out fa-flip-horizontal fa-fw"></i>  {t}Close{/t} </span>
                </div>
                <div>
                    <span class="save" onclick="start_bulk_order_data_entry(this)"> {t}Start{/t} <i class="start_bulk_order_data_entry fas fa-arrow-right "></i>  </span>
                </div>
                

            {/if}
        </td>

    </tr>

    <tr class="_bottom">
        <td class=" {if $is_packed=='Yes'}invisible{/if}">



            <label>{t}Packer{/t}</label>

            <input id="set_packer" type="hidden" data-field="Delivery Note Assigned Packer Key" class=" input_field" value="{$parent->settings('data_entry_picking_aid_default_packer')}" has_been_valid="0"/>

            <input id="set_packer_dropdown_select_label" field="set_packer" style="width:170px" name="packer" autocomplete="off"
                   scope="employee" parent="account"
                   parent_key="1" class="dropdown_select"
                   data-metadata='{ "option":"only_working"}'
                   value="{if isset($dn) and  $dn->get('Delivery Note Assigned Packer Key')>0 }{$dn->get('Delivery Note Assigned Packer Name')}{else}{$parent->get('data entry picking aid default packer')}{/if}"
                   has_been_valid="0"
                   placeholder="{t}Name{/t}"/>
            <span id="set_packer_msg" class="msg"></span>

            <div id="set_packer_results_container" class="search_results_container hide">

                <table id="set_packer_results" >

                    <tr class="hide" id="set_packer_search_result_template" field="" value="" data-shortcut="select_packer_picking_aid"
                        formatted_value="" onClick="select_dropdown_handler('packer',this);validate_data_entry_picking_aid()">
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
    <div style="clear: both">

    </div>
</div>



<div id="select_parcel_type_dialog" class="hide" style="border:1px solid #ccc;position: absolute;background-color: white;padding: 10px 20px">

    <i onClick="close_select_parcel_type(this)" class="button fa fa-window-close" ></i>

    <table sytle="border-bottom: 1px solid #ddd">
        {if $number_parcel_types>0}

        {foreach  from=$parent->settings('data_entry_picking_aid_parcel_types') item=parcel_type}
        <tr class="button" onclick="select_parcel_type(this)" data-dim_0="{$parcel_type['dimensions'][0]}" data-dim_1="{$parcel_type['dimensions'][1]}"  data-dim_2="{$parcel_type['dimensions'][2]}"    >
            <td style="padding-right: 20px;border-bottom: 1px solid #ddd">{$parcel_type['label']}</td>
            <td style="border-bottom: 1px solid #ddd">{$parcel_type['dimensions'][0]} x {$parcel_type['dimensions'][1]} x {$parcel_type['dimensions'][2]} (cm)</td>
        </tr>
        {/foreach}
        {/if}
    </table>
</div>


<script>

    confirmButtonText: 'Yes, delete it!'

    $('.shippers_options').niceSelect();

    $( ".shippers_options" ).on('change',
        function() {

        var value=$( ".shippers_options option:selected" ).val();

            var api_key=  $( ".shippers_options option:selected" ).data('api_key')
            var api_code=  $( ".shippers_options option:selected" ).data('code')


            if(api_key>0){
                $('.tracking_number_container').addClass('hide invisible')

                if(api_code=='APC'){
                    $('.apc_service_type').removeClass('hide')
                }else{
                    $('.apc_service_type').addClass('hide')

                }


            }else{
                $('.tracking_number_container').removeClass('invisible hide')
                $('.apc_service_type').addClass('hide')

            }


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


    function change_shipper_service_type(element){

        var element_has_service=false;

        if($(element).find('i').hasClass('fa-dot-circle')){
            element_has_service=true;
        }

        $('.apc_service_type .service_type').each(function(i, obj) {

            $(obj).find('i').removeClass('fa-dot-circle').addClass('fa-circle')

        });


        if( !element_has_service){
            $(element).find('i').addClass('fa-dot-circle').removeClass('fa-circle')
            $('.parcel_dimension_inputs').addClass('hide')
        }else{
            $('.parcel_dimension_inputs').removeClass('hide')

        }






    }





</script>