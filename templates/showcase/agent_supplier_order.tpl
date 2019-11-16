{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:20 August 2018 at 17:37:22 GMT+8, Kuta, Bali, Indonesia
 Copyright (c) 2016, Inikoo

 Version 3
-->
*}

{assign deliveries $order->get_deliveries('objects')}
<div class="timeline_horizontal {if $order->get('Agent Supplier Purchase Order State')=='Cancelled'}hide{/if}">
    <ul class="timeline" id="timeline">
        <li id="submitted_node" class="li {if $order->get('State Index')>=30}complete{/if}">
            <div class="label">
                <span class="state" title="{t}Client's order received{/t}">{t}Order confirmed{/t}</span>
            </div>
            <div class="timestamp">
                <span class="Confirm_Date_or_Percentage">&nbsp;{$order->get('Confirm Date or Percentage')}</span>
                <span class="start_date">{$order->get('Creation Date')} </span>
            </div>
            <div class="dot">
            </div>
        </li>

        <li id="send_node" class="li  {if $order->get('State Index')>=60}complete{/if}">
            <div class="label">
                <span class="state" label="{t}Supplier's orders received{/t}">{t}Order received{/t} <span></i></span></span>
            </div>
            <div class="timestamp">
			<span class="Received_Date_or_Percentage">&nbsp;{$order->get('Received Date or Percentage')}&nbsp;</span>
            </div>
            <div class="dot">
            </div>
        </li>
        <li id="send_node" class="li {if $order->get('State Index')>=70}complete{/if}">
            <div class="label">
                <span class="state ">{t}Order in delivery{/t} <span></i></span></span>
            </div>
            <div class="timestamp">
			<span class="Purchase_Order_Dispatched_Date">&nbsp;&nbsp;</span>
            </div>
            <div class="dot">
            </div>
        </li>




    </ul>
</div>
<div class="timeline_horizontal  {if $order->get('Agent Supplier Purchase Order State')!='Cancelled'}hide{/if}">
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
<div class="order" style="display: flex;" data-object='{$object_data}'>
    <div class="block" style=" align-items: stretch;flex: 1">
        <div class="data_container" style="padding:5px 10px">


        </div>
        <div style="clear:both">
        </div>
    </div>
    <div class="block " style="align-items: stretch;flex: 1;padding-top: 0px">
        <div class="state" style="height:30px;">
            <div id="back_operations">


            </div>
            <span style="float:left;padding-left:10px;padding-top: 5px"
                  class="Purchase_Order_State"> {$order->get('State')} </span>


            <div id="forward_operations">


                <div id="create_spo_operations"
                     class="order_operation {if $order->get('Agent Supplier Purchase Order State')!='Submitted'}hide{/if}">
                    <div id="create_spo_operation"
                         class="square_button right {if $order->get('Agent Supplier Purchase Order Number Items')==0}hide{/if} "
                         title="{t}Create supplier's order{/t}">
                        <i class="fa fa-clipboard   " aria-hidden="true"
                           onclick="toggle_order_operation_dialog('create_spo')"></i>
                        <table id="create_spo_dialog" border="0" class="order_operation_dialog hide">
                            <tr class="top">
                                <td class="label" colspan="2">{t}Create supplier's order{/t}</td>
                            </tr>
                            <tr class="changed buttons">
                                <td><i class="fa fa-sign-out fa-flip-horizontal button" aria-hidden="true"
                                       onclick="close_dialog('create_spo')"></i></td>
                                <td class="aright"><span
                                            data-data='{  "field": "Create Agent Supplier Orders","value": "1","dialog_name":"create_spo"}'
                                            id="create_spo_save_buttons" class="valid save button"
                                            onclick="save_order_operation(this)"><span class="label">{t}Save{/t}</span> <i
                                                class="fa fa-cloud fa-fw  " aria-hidden="true"></i></span></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <table border="0" class=" ">
        <tr style="    border-bottom: 1px solid #ccc;">
            <td style="text-align: center;padding: 0px" colspan="2">
                <a href="/pdf/supplier.order.pdf.php?id={$order->id}" target="_blank"><img class="button pdf_link"  style="width: 50px;height:16px;position: relative;top:2px" src="/art/pdf.gif"></a>



            </td>
        </tr>
        </table>

        <div id="crete_delivery"
             class="delivery_node {if ({$order->get('State Index')|intval} < 35 or ($order->get('Agent Supplier Purchase Order Ordered Number Items')-$order->get('Agent Supplier Purchase Order Number Supplier Delivery Items'))==0)  }hide{/if}"
             style="height:30px;clear:both;border-top:1px solid #ccc;border-bottom:1px solid #ccc">
            <div id="back_operations">
            </div>
            <span style="float:left;padding-left:10px;padding-top:5px" class="very_discreet italic"><i
                        class="fa fa-truck" aria-hidden="true"></i> {t}Delivery{/t} </span>
            <div id="forward_operations">
                <div id="received_operations"
                     class="order_operation {if !($order->get('Agent Supplier Purchase Order State')=='Submitted' or  $order->get('Agent Supplier Purchase Order State')=='Send') }hide{/if}">
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
                                                                                      onclick="change_view('{$order->get('Agent Supplier Purchase Order Parent')|lower}/{$order->get('Agent Supplier Purchase Order Parent Key')}/delivery/{$dn->id}')"> <i
                                    class="fa fa-truck  "
                                    aria-hidden="true"></i> {$dn->get('Public ID')}</span> ({$dn->get('State')}) </span>
                </div>
            {/foreach}
        </div>
    </div>
    <div class="block " style="align-items: stretch;flex: 1 ">
        <table border="0" class="info_block center">

            <tr>
                <td style="text-align: center"title="{t}Weight{/t}"><i class="far fa-fw fa-weight-hanging" title="{t}Weight{/t}"></i> <span  class="Agent_Supplier_Purchase_Order_Weight " >{$order->get('Weight')}</span></td>

                <td style="text-align: center" class="Purchase_Order_CBM" title="{t}CBM{/t}">{$order->get('CBM')}</td>
                <td style="text-align: center" class="Agent_Supplier_Purchase_Order_Cartons" title="{t}Number of cartons{/t}"><i class="far fa-fw fa-boxes-alt" ></i>  {$order->get('Cartons')}</td>
            </tr>
        </table>
        <div style="clear:both">
        </div>
    </div>
    <div class="block " style="align-items: stretch;flex: 1 ">
        <table border="0" class="info_block">
            <tr>
                <td class="label">{t}Cost{/t} ({$order->get('Agent Supplier Purchase Order Currency Code')})</td>
                <td class="aright Purchase_Order_Amount">{$order->get('Amount')}</td>
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
        <i key="" class="far fa-square fa-fw button" aria-hidden="true"></i> <span>{t}Select all{/t}</span>
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


            var object_data = $('#object_showcase div.order').data("object")
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