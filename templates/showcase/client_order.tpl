{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:20 August 2016 at 15:47:06 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3
-->
*}
{$order->get('State Index')}
{assign deliveries $order->get_deliveries('objects')}
<div class="timeline_horizontal {if $order->get('Purchase Order State')=='Cancelled'}hide{/if}">
    <ul class="timeline" id="timeline">
        <li id="submitted_node" class="li {if $order->get('State Index')>=30}complete{/if}">
            <div class="label">
                <span class="state" title="{t}Client's order received{/t}">{t}CO received{/t}</span>
            </div>
            <div class="timestamp">
                <span class="Purchase_Order_Submitted_Date">&nbsp;{$order->get('Submitted Date')}</span> <span
                        class="start_date">{$order->get('Creation Date')} </span>
            </div>
            <div class="dot">
            </div>
        </li>

        <li id="send_node" class="li  {if $order->get('State Index')>=60}complete{/if}">
            <div class="label">
                <span class="state" label="{t}Supplier's orders created{/t}">{t}SOs created{/t} <span></i></span></span>
            </div>
            <div class="timestamp">
			<span class="Purchase_Order_Inputted_Date">&nbsp;{foreach from=$deliveries item=dn name=deliveries}
                <span i
                      class="{if $smarty.foreach.deliveries.index != 0}hide{/if} index_{$smarty.foreach.deliveries.index}">{$dn->get('Creation Date')}</span>
                {/foreach}&nbsp;</span>
            </div>
            <div class="dot">
            </div>
        </li>
        <li id="send_node" class="li {if $order->get('State Index')>=70}complete{/if}">
            <div class="label">
                <span class="state ">{t}Order received{/t} <span></i></span></span>
            </div>
            <div class="timestamp">
			<span class="Purchase_Order_Dispatched_Date">&nbsp;{foreach from=$deliveries item=dn name=deliveries}
                <span i
                      class="{if $smarty.foreach.deliveries.index != 0}hide{/if} index_{$smarty.foreach.deliveries.index}">{$dn->get('Dispatched Date')}</span>
                {/foreach}&nbsp;</span>
            </div>
            <div class="dot">
            </div>
        </li>


        <li id="send_node" class="li  {if $order->get('State Index')>=60}complete{/if} ">
            <div class="label">
                <span class="state" style="position:relative;left:5px">{t}Delivery{/t} <span></i></span></span>
            </div>
            <div class="timestamp">
			<span class="Deliveries_Public_IDs"
                  style="position:relative;left:5px">&nbsp;{foreach from=$deliveries item=dn name=deliveries}
                <span i
                      class="{if $smarty.foreach.deliveries.index != 0}hide{/if} index_{$smarty.foreach.deliveries.index}">{$dn->get('Public ID')}</span>
                {/foreach}&nbsp;</span>
            </div>
            <div class="truck">
            </div>
        </li>


    </ul>
</div>
<div class="timeline_horizontal  {if $order->get('Purchase Order State')!='Cancelled'}hide{/if}">
    <ul class="timeline" id="timeline">
        <li id="submitted_node" class="li complete">
            <div class="label">
                <span class="state ">{t}Submitted{/t}</span>
            </div>
            <div class="timestamp">
                <span class="Purchase_Order_Submitted_Date">&nbsp;{$order->get('Submitted Date')}</span> <span
                        class="start_date">{$order->get('Creation Date')} </span>
            </div>
            <div class="dot">
            </div>
        </li>

        <li id="send_node" class="li  cancelled">
            <div class="label">
                <span class="state ">{t}Cancelled{/t} <span></i></span></span>
            </div>
            <div class="timestamp">
                <span class="Cancelled_Date">{$order->get('Cancelled Date')} </span>
            </div>
            <div class="dot">
            </div>
        </li>


    </ul>
</div>
<div class="order" style="display: flex;" data-object="{$object_data}">
    <div class="block" style=" align-items: stretch;flex: 1">
        <div class="data_container" style="padding:5px 10px">

            <div class="data_field">
                <i class="fa fa-share fa-fw" aria-hidden="true" title="Incoterm"></i> <span
                        class="Purchase_Order_Incoterm">{$order->get('Purchase Order Incoterm')}</span>
            </div>
            <div class="data_field">
                <i class="fa fa-arrow-circle-right fa-fw" aria-hidden="true" title="{t}Port of export{/t}"></i> <span
                        class="Purchase_Order_Port_of_Export">{$order->get('Port of Export')}</span>
            </div>
            <div class="data_field">
                <i class="fa fa-arrow-circle-left fa-fw" aria-hidden="true" title="{t}Port of import{/t}"></i> <span
                        class="Purchase_Order_Port_of_Import">{$order->get('Port of Import')}</span>
            </div>
        </div>
        <div style="clear:both">
        </div>
    </div>
    <div class="block " style="align-items: stretch;flex: 1;">
        <div class="state" style="height:30px;margin-bottom:10px;position:relative;top:-5px">
            <div id="back_operations">


            </div>
            <span style="float:left;padding-left:10px;padding-top:5px"
                  class="Purchase_Order_State"> {$order->get('Agent State')} </span>


            <div id="forward_operations">


                <div id="create_spo_operations"
                     class="order_operation {if $order->get('Purchase Order State')!='Submitted'}hide{/if}">
                    <div id="create_spo_operation"
                         class="square_button right {if $order->get('Purchase Order Number Items')==0}hide{/if} "
                         title="{t}Create supplier's orders{/t}">
                        <i class="fa fa-clipboard   " aria-hidden="true"
                           onclick="toggle_order_operation_dialog('create_spo')"></i>
                        <table id="create_spo_dialog" border="0" class="order_operation_dialog hide">
                            <tr class="top">
                                <td class="label" colspan="2">{t}Create supplier's orders{/t}</td>
                            </tr>
                            <tr class="changed buttons">
                                <td><i class="fa fa-sign-out fa-flip-horizontal button" aria-hidden="true"
                                       onclick="close_dialog('create_spo')"></i></td>
                                <td class="aright"><span
                                            data-data='{  "field": "Purchase Order Agent State","value": "Submitted","dialog_name":"create_spo"}'
                                            id="create_spo_save_buttons" class="valid save button"
                                            onclick="save_order_operation(this)"><span class="label">{t}Save{/t}</span> <i
                                                class="fa fa-cloud fa-fw  " aria-hidden="true"></i></span></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div id="crete_delivery"
             class="delivery_node {if ({$order->get('State Index')|intval} < 35 or ($order->get('Purchase Order Ordered Number Items')-$order->get('Purchase Order Number Supplier Delivery Items'))==0)  }hide{/if}"
             style="height:30px;clear:both;border-top:1px solid #ccc;border-bottom:1px solid #ccc">
            <div id="back_operations">
            </div>
            <span style="float:left;padding-left:10px;padding-top:5px" class="very_discreet italic"><i
                        class="fa fa-truck" aria-hidden="true"></i> {t}Delivery{/t} </span>
            <div id="forward_operations">
                <div id="received_operations"
                     class="order_operation {if !($order->get('Purchase Order State')=='Submitted' or  $order->get('Purchase Order State')=='Send') }hide{/if}">
                    <div class="square_button right" style="padding:0;margin:0;position:relative;top:0px"
                         title="{t}Input delivery note{/t}">
                        <i class="fa fa-plus" aria-hidden="true" onclick="show_create_delivery()"></i>
                    </div>
                </div>
            </div>
        </div>
        <div>
            {foreach from=$deliveries item=dn}
                <div class="delivery_node"
                     style="height:30px;clear:both;border-top:1px solid #ccc;border-bottom:1px solid #ccc">
                    <span style="float:left;padding-left:10px;padding-top:5px"> <span class="button"
                                                                                      onclick="change_view('{$order->get('Purchase Order Parent')|lower}/{$order->get('Purchase Order Parent Key')}/delivery/{$dn->id}')"> <i
                                    class="fa fa-truck  "
                                    aria-hidden="true"></i> {$dn->get('Public ID')}</span> ({$dn->get('State')}) </span>
                </div>
            {/foreach}
        </div>
    </div>
    <div class="block " style="align-items: stretch;flex: 1 ">
        <table border="0" class="info_block acenter">
            <tr>
                <td>
                    <span style=""><i class="fa fa-ship fa-fw discreet" aria-hidden="true"></i> <span
                                class="Purchase_Order_Number_Suppliers">{$order->get('Number Suppliers')}</span></span>
                    <span style=""><i class="fa fa-stop fa-fw discreet padding_left_20" aria-hidden="true"></i> <span
                                class="Purchase_Order_Number_Items">{$order->get('Number Items')}</span></span>
                    <span class="{if $order->get('State Index')<60}super_discreet{/if} padding_left_20"><i
                                class="fa fa-arrow-circle-down  fa-fw discreet" aria-hidden="true"></i> <span
                                class="Purchase_Order_Number_Supplier_Delivery_Items">{$order->get('Number Supplier Delivery Items')}</span></span>
            </tr>
            <tr>
                <td class="Purchase_Order_Weight" title="{t}Weight{/t}">{$order->get('Weight')}</td>
            </tr>
            <tr>
                <td class="Purchase_Order_CBM" title="{t}CBM{/t}">{$order->get('CBM')}</td>
            </tr>
        </table>
        <div style="clear:both">
        </div>
    </div>
    <div class="block " style="align-items: stretch;flex: 1 ">
        <table border="0" class="info_block">
            <tr>
                <td class="label">{t}Cost{/t} ({$order->get('Purchase Order Currency Code')})</td>
                <td class="aright Purchase_Order_Total_Amount">{$order->get('Total Amount')}</td>
            </tr>

        </table>
        <div style="clear:both">
        </div>
    </div>
    <div style="clear:both">
    </div>
</div>
<div id="new_delivery" class="table_new_fields hide">
    <div class="invisible" style="align-items: stretch;flex: 1;padding:20px 5px;">
        <i key="" class="fa fa-fw fa-square-o button" aria-hidden="true"></i> <span>{t}Select all{/t}</span>
    </div>
    <div style="align-items: stretch;flex: 1;padding:10px 20px;border-left:1px solid #eee">
        <table border="0" style="width:50%;float:right;xborder-left:1px solid #ccc;width:100%;">
            <tr>
                <td class="label ">{t}Delivery number{/t}</td>
                <td>
                    <input class="new_delivery_field" id="delivery_number" placeholder="{t}Delivery number{/t}">
                </td>
            </tr>
            <tr>
                <td><i class="fa fa-sign-out fa-flip-horizontal button" aria-hidden="true"
                       onclick="close_create_delivery()"></i></td>
                <td class="buttons save" onclick="save_create_delivery(this)"><span>{t}Save{/t}</span> <i
                            class=" fa fa-cloud fa-flip-horizontal " aria-hidden="true"></i></td>
            </tr>
        </table>
    </div>
</div>
<script>

    $('#new_delivery').on('input propertychange', '.new_delivery_field', function (evt) {


        var field_id = $(this).attr('id')

        if (field_id == 'delivery_number') {

            $(this).closest('table').find('td.buttons i').addClass('fa-spinner fa-spin').removeClass('fa-cloud')


            var object_data = JSON.parse(atob($('#object_showcase div.order').data("object")))
            console.log(object_data)
            var value = $(this).val()

            var parent = object_data.order_parent
            var parent_key = object_data.order_parent_key
            var object = 'Supplier Delivery'
            var key = ''
            var field = 'Supplier Delivery Public ID'

            if (value == '') {
                $(this).closest('table').find('td.buttons').removeClass('changed')
            } else {

                $(this).closest('table').find('td.buttons').addClass('changed')
                var request = '/ar_validation.php?tipo=check_for_duplicates&parent=' + parent + '&parent_key=' + parent_key + '&object=' + object + '&key=' + key + '&field=' + field + '&value=' + value + '&metadata=' + JSON.stringify({
                        option: 'creating_dn_from_po'})

                console.log(request)


                $.getJSON(request, function (data) {


                    $('#' + field_id).removeClass('waiting invalid valid')
                    $('#' + field_id).closest('table').find('td.buttons').removeClass('waiting invalid valid')

                    $('#' + field_id).closest('table').find('td.buttons i').removeClass('fa-spinner fa-spin').addClass('fa-cloud')


                    if (data.state == 200) {

                        var validation = data.validation
                        var msg = data.msg

                    } else {
                        var validation = 'invalid'
                        var msg = "Error, can't verify value on server"

                    }
                    $('#' + field_id).closest('table').find('td.buttons').addClass(validation)


                })
            }


        }

    });


</script> 