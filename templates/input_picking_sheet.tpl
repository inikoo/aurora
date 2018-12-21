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

<table class="input_picking_sheet_table"  border="0">

    <tr class="top">
        <td class="label" >



            <label>{t}Picker{/t}</label>

            <input id="set_picker" type="hidden" class=" input_field" value="" has_been_valid="0"/>

            <input id="set_picker_dropdown_select_label" field="set_picker" style="width:170px"
                   scope="employee" parent="account"
                   parent_key="1" class="dropdown_select"
                   data-metadata='{ "option":"only_working"}'
                   value="{$dn->get('Delivery Note Assigned Picker Alias')}" has_been_valid="0"
                   placeholder="{t}Name{/t}"/>
            <span id="set_picker_msg" class="msg"></span>

            <div id="set_picker_results_container" class="search_results_container hide">

                <table id="set_picker_results" border="0"  >

                    <tr class="hide" id="set_picker_search_result_template" field="" value=""
                        formatted_value="" onClick="select_dropdown_handler('picker',this)">
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

            <input class="set_dn_weight width_50 " type="number" class=" input_field" value="" has_been_valid="0"/> Kg

        </td>

        <td class="label">
            <div id="shipper" class="{if $dn->get('Delivery Note Shipper Key')==''}hide{/if}">
                {assign "shipper" $dn->get('Shipper')}
                {if $shipper}
                    <span class="Shipper_Code" title="{$shipper->get('Name')}">{$shipper->get('Code')}</span>
                {else}
                    <span class="Shipper_Code" title=""></span>
                {/if}

                <i onclick="show_shipper_options()" class="fal fa-pen discreet button margin_left_10"></i>


            </div>

            <div id="shipper_options" class="{if $dn->get('Delivery Note Shipper Key')>0}hide{/if}">
                {if $number_shippers<=5}
                    {foreach from=$shippers item=shipper}
                        <span id="shipper_option_{$shipper.key}" onclick="select_courier({$shipper.key})" class="button option {if $dn->get('Delivery Note Shipper Key')==$shipper.key}selected{/if}" title="{$shipper.name}">{$shipper.code}</span>
                    {/foreach}
                    <span id="shipper_option_" onclick="select_courier('')" class="button option " title="{t}Skip set courier{/t}"><i class="error fa fa-ban"></i></span>
                {else}

                {/if}

            </div>
        </td>

        <td class="label" rowspan="2" style="padding: 0px 20px">

            <div><i class="far fa-square"></i>  {t}Set as closed{/t} </div>
            <div style="margin-top:5px;margin-bottom: 5px"><i class="far fa-square"></i>  {t}Create invoice{/t} </div>
            <div><i class="far fa-square"></i>  {t}Set as dispatched{/t} </div>
        </td>

        <td class="label" rowspan="2" style="padding: 0px 20px">

            <div  style="margin-bottom: 5px;font-size: x-small;position: relative;bottom: 10px"><i class="fa fa-sign-out fa-flip-horizontal fa-fw"></i>  {t}Cancel{/t} </div>

            <div><i class="save fa fa-cloud"></i>  {t}Save{/t} </div>
        </td>

    </tr>

    <tr class="_bottom">
        <td style="" >



            <label>{t}Picker{/t}</label>

            <input id="set_packer" type="hidden" class=" input_field" value="" has_been_valid="0"/>

            <input id="set_packer_dropdown_select_label" field="set_packer" style="width:170px"
                   scope="employee" parent="account"
                   parent_key="1" class="dropdown_select"
                   data-metadata='{ "option":"only_working"}'
                   value="{$dn->get('Delivery Note Assigned Picker Alias')}" has_been_valid="0"
                   placeholder="{t}Name{/t}"/>
            <span id="set_packer_msg" class="msg"></span>

            <div id="set_packer_results_container" class="search_results_container hide">

                <table id="set_packer_results" border="0"  >

                    <tr class="hide" id="set_packer_search_result_template" field="" value=""
                        formatted_value="" onClick="select_dropdown_handler('packer',this)">
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

            <label>{t}Parcels{/t}</label>

        </td>
        <td style="" >

            <input class="set_dn_parcels width_50" type="number" class=" input_field" value="" has_been_valid="0"/>

        </td>

<td class="label">
</td>


    </tr>




</table>
    <div style="clear: both"></div>
</div>