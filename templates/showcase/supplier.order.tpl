{assign deliveries $order->get_deliveries('objects')}
<div class="timeline_horizontal {if $order->get('Purchase Order State')=='Cancelled'}hide{/if}">
    <ul class="timeline" id="timeline">
        <li id="submitted_node" class="li {if $order->get('State Index')>=30}complete{/if}">
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
        <li id="send_node" class="li  {if $order->get('State Index')>=60}complete{/if}">
            <div class="label">
                <span class="state ">{t}Inputted{/t} <span></i></span></span>
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
                <span class="state ">{t}Dispatched{/t} <span></i></span></span>
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
        <li class="li {if $order->get('State Index')>=80}complete{/if}">
            <div class="label">
                <span class="state ">{t}Received{/t}</span>
            </div>
            <div class="timestamp">
			
			<span class="Purchase_Order_Received_Date">&nbsp;<span
                        class="Purchase_Order_Received_Date">{$order->get('Received Date')}</span>{foreach from=$deliveries item=dn name=deliveries}
			<span i
                  class="{if $smarty.foreach.deliveries.index != 0}hide{/if} index_{$smarty.foreach.deliveries.index}">{$dn->get('Received Date')}</span>
                {/foreach}&nbsp;</span>

            </div>
            <div class="dot">
            </div>
        </li>
        <li class="li {if $order->get('State Index')>=90}complete{/if}">
            <div class="label">
                <span class="state">{t}Checked{/t}</span>
            </div>
            <div class="timestamp">
		<span class="Purchase_Order_Checked_Date">&nbsp;{foreach from=$deliveries item=dn name=deliveries}
            <span i
                  class="{if $smarty.foreach.deliveries.index != 0}hide{/if} index_{$smarty.foreach.deliveries.index}">{$dn->get('Checked Percentage or Date')}</span>
            {/foreach}&nbsp;</span>
            </div>
            <div class="dot">
            </div>
        </li>
        <li class="li {if $order->get('State Index')==100}complete{/if}">
            <div class="label">
                <span class="state">{t}Placed{/t}</span>
            </div>
            <div class="timestamp">
		<span class="Purchase_Order_Placed_Date">&nbsp;{foreach from=$deliveries item=dn name=deliveries}
            <span i
                  class="{if $smarty.foreach.deliveries.index != 0}hide{/if} index_{$smarty.foreach.deliveries.index}">{$dn->get('Placed Percentage or Date')}</span>
            {/foreach}&nbsp;</span>
            </div>
            <div class="dot">
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
                <i class="fa fa-ship fa-fw" aria-hidden="true" title="{t}Supplier{/t}"></i> <span
                        onclick="change_view('{if $order->get('Purchase Order Parent')=='Supplier'}supplier{else}agent{/if}/{$order->get('Purchase Order Parent Key')}')"
                        class="link Purchase_Order_Parent_Name">{$order->get('Purchase Order Parent Name')}</span>
            </div>
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
                <div id="delete_operations"
                     class="order_operation {if $order->get('Purchase Order State')!='InProcess'}hide{/if}">
                    <div class="square_button left" xstyle="padding:0;margin:0;position:relative;top:-5px"
                         title="{t}delete{/t}">
                        <i class="fa fa-trash very_discreet " aria-hidden="true"
                           onclick="toggle_order_operation_dialog('delete')"></i>
                        <table id="delete_dialog" border="0" class="order_operation_dialog hide">
                            <tr class="top">
                                <td colspan="2" class="label">{t}Delete purchase order{/t}</td>
                            </tr>
                            <tr class="changed buttons">
                                <td><i class="fa fa-sign-out fa-flip-horizontal button" aria-hidden="true"
                                       onclick="close_dialog('delete')"></i></td>
                                <td class="aright"><span data-data='{ "object": "PurchaseOrder", "key":"{$order->id}"}'
                                                         id="delete_save_buttons" class="error save button"
                                                         onclick="delete_object(this)"><span
                                                class="label">{t}Delete{/t}</span> <i class="fa fa-trash fa-fw  "
                                                                                      aria-hidden="true"></i></span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div id="cancel_operations"
                     class="order_operation {if $order->get('Purchase Order State')!='Submitted'  }hide{/if}">
                    <div class="square_button left" title="{t}Cancel{/t}">
                        <i class="fa fa-minus-circle error " aria-hidden="true"
                           onclick="toggle_order_operation_dialog('cancel')"></i>
                        <table id="cancel_dialog" border="0" class="order_operation_dialog hide">
                            <tr class="top ">
                                <td class="label" colspan="2">{t}Cancel purchase order{/t}</td>
                            </tr>
                            <tr class="changed buttons">
                                <td><i class="fa fa-sign-out fa-flip-horizontal button" aria-hidden="true"
                                       onclick="close_dialog('cancel')"></i></td>
                                <td class="aright"><span
                                            data-data='{  "field": "Purchase Order State","value": "Cancelled","dialog_name":"cancel"}'
                                            id="cancel_save_buttons" class="error save button"
                                            onclick="save_order_operation(this)"><span
                                                class="label">{t}Cancel{/t}</span> <i class="fa fa-cloud fa-fw  "
                                                                                      aria-hidden="true"></i></span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div id="undo_submit_operations"
                     class="order_operation {if $order->get('Purchase Order State')!='Submitted'}hide{/if}">
                    <div class="square_button left" title="{t}Undo submit{/t}">
                        <span class="fa-stack" onclick="toggle_order_operation_dialog('undo_submit')"> <i
                                    class="fa fa-paper-plane-o discreet " aria-hidden="true"></i> <i
                                    class="fa fa-ban fa-stack-1x discreet error"></i> </span>
                        <table id="undo_submit_dialog" border="0" class="order_operation_dialog hide">
                            <tr class="top">
                                <td class="label" colspan="2">{t}Undo submition{/t}</td>
                            </tr>
                            <tr class="changed buttons">
                                <td><i class="fa fa-sign-out fa-flip-horizontal button" aria-hidden="true"
                                       onclick="close_dialog('undo_submit')"></i></td>
                                <td class="aright"><span
                                            data-data='{  "field": "Purchase Order State","value": "InProcess","dialog_name":"undo_submit"}'
                                            id="undo_submit_save_buttons" class="valid save button"
                                            onclick="save_order_operation(this)"><span class="label">{t}Save{/t}</span> <i
                                                class="fa fa-cloud fa-fw  " aria-hidden="true"></i></span></td>
                            </tr>
                        </table>
                    </div>
                </div>

            </div>
            <span style="float:left;padding-left:10px;padding-top:5px"
                  class="Purchase_Order_State"> {$order->get('State')} </span>
            <div id="forward_operations">


                <div id="submit_operations"
                     class="order_operation {if $order->get('Purchase Order State')!='InProcess'}hide{/if}">
                    <div id="submit_operation"
                         class="square_button right {if $order->get('Purchase Order Number Items')==0}hide{/if} "
                         title="{t}Submit{/t}">
                        <i class="fa fa-paper-plane-o   " aria-hidden="true"
                           onclick="toggle_order_operation_dialog('submit')"></i>
                        <table id="submit_dialog" border="0" class="order_operation_dialog hide">
                            <tr class="top">
                                <td class="label" colspan="2">{t}Submit purchase order{/t}</td>
                            </tr>
                            <tr class="changed buttons">
                                <td><i class="fa fa-sign-out fa-flip-horizontal button" aria-hidden="true"
                                       onclick="close_dialog('submit')"></i></td>
                                <td class="aright"><span
                                            data-data='{  "field": "Purchase Order State","value": "Submitted","dialog_name":"submit"}'
                                            id="submit_save_buttons" class="valid save button"
                                            onclick="save_order_operation(this)"><span class="label">{t}Save{/t}</span> <i
                                                class="fa fa-cloud fa-fw  " aria-hidden="true"></i></span></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div id="quick_create_delivery_operations"
                     class="order_operation {if ({$order->get('State Index')|intval} < 30 or ($order->get('Purchase Order Ordered Number Items')-$order->get('Purchase Order Number Supplier Delivery Items'))==0) or $parent->get('Parent Skip Inputting')=='No' }hide{/if}">
                    <div id="quick_create_delivery_operation" class="square_button right  "
                         title="{t}Create delivery{/t}">
                        <i class="fa fa-truck   " aria-hidden="true" onclick="quick_create_delivery()"></i>
                    </div>
                </div>


            </div>
        </div>
        <div id="create_delivery"
             class="delivery_node {if ({$order->get('State Index')|intval} < 30 or ($order->get('Purchase Order Ordered Number Items')-$order->get('Purchase Order Number Supplier Delivery Items'))==0) or $parent->get('Parent Skip Inputting')=='Yes' }hide{/if}"
             style="height:30px;clear:both;border-top:1px solid #ccc;border-bottom:1px solid #ccc">
            <div id="back_operations">
            </div>
            <span style="float:left;padding-left:10px;padding-top:5px" class="very_discreet italic"><i
                        class="fa fa-truck" aria-hidden="true"></i> {t}Delivery{/t}</span>
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
                    <span style=""><i class="fa fa-stop fa-fw discreet" aria-hidden="true"></i> <span
                                class="Purchase_Order_Number_Items">{$order->get('Number Items')}</span></span>
                    <span class="{if $order->get('State Index')<60}super_discreet{/if}" style="padding-left:20px"><i
                                class="fa fa-arrow-circle-down  fa-fw discreet" aria-hidden="true"></i> <span
                                class="Purchase_Order_Number_Supplier_Delivery_Items">{$order->get('Number Supplier Delivery Items')}</span></span>
                    <span class="{if $order->get('State Index')<80}super_discreet{/if}" style="padding-left:20px"><i
                                class="fa fa-map-marker fa-fw discreet" aria-hidden="true"></i> <span
                                class="Purchase_Order_Number_Placed_Items">{$order->get('Number Placed Items')}</span></span>
                </td>
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
            <tr class="{if $account->get('Account Currency')==$order->get('Purchase Order Currency Code')}hide{/if}">
                <td colspan="2"
                    class="Purchase_Order_Total_Amount_Account_Currency aright ">{$order->get('Total Amount Account Currency')}</td>
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


    $('#purchase_order_pdf').click(function () {

        window.open('/pdf/supplier.order.pdf.php?id={$order->id}');
    });


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
                            option: 'creating_dn_from_po'
                        })

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