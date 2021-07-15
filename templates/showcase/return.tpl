{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 26 November 2018 at 23:16:04 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3
-->
*}


<div class="timeline_horizontal">

    <ul class="timeline" id="timeline">

        {if $return->get('Supplier Delivery Key')}
            <li id="order_node" class="li complete">
                <div class="label">
                    <span class="state "><i class="far fa-shopping-cart fa-fw"></i> {t}Submitted{/t}</span>
                </div>
                <div class="timestamp">
                    <span class="Purchase_Order_Submitted_Date">&nbsp;{$order->get('Submitted by Customer Date')}</span> <span
                            class="start_date Purchase_Order_Creation_Date">{$order->get('Created Date')} </span>
                </div>
                <div class="dot">

                </div>
            </li>
            <li id="order_dispatched_node" class="li  complete">
                <div class="label">
                    <span class="state "><i class="far fa-shopping-cart fa-fw"></i> {t}Dispatched{/t}</span>
                </div>
                <div class="timestamp">
                    <span >&nbsp;{$order->get('Dispatched Date')}</span>
                </div>
                <div class="dot">
                </div>
            </li>


        {/if}





        {if $return->get('State Index')<0 and $return->get('Dispatched Date')!='' and  $return->get('Received Date')=='' and    $return->get('Checked Date')==''     }
            <li id="cancelled_after_received_node" class="li  cancelled">
                <div class="label">
                    <span class="state ">{t}Cancelled{/t}</span>
                </div>
                <div class="timestamp">
                    <span >&nbsp;{$return->get('Cancelled Date')}</span>
                </div>
                <div class="dot">
                </div>
            </li>
        {/if}

        <li id="dispatched_node"
            class="li  {if $return->get('State Index')>=30  or ($return->get('State Index')<0 and  $return->get('Dispatched Date')!='')  }complete{/if}">
            <div class="label">
                <span class="state ">{t}Sent back{/t}</span>
            </div>
            <div class="timestamp">
                <span class="Supplier_Delivery_Dispatched_Date">&nbsp;{$return->get('Dispatched Date')}</span>
            </div>
            <div class="dot">
            </div>
        </li>


        <li id="received_node"
            class="li  {if $return->get('State Index')>=40  or ($return->get('State Index')<0 and  $return->get('Received Date')!='')  }complete{/if}">
            <div class="label">
                <span class="state ">{t}Received{/t}</span>
            </div>
            <div class="timestamp">
                <span class="Supplier_Delivery_Received_Date">&nbsp;{$return->get('Received Date')}</span>
            </div>
            <div class="dot">
            </div>
        </li>

        {if $return->get('State Index')<0  and  $return->get('Received Date')!='' and  $return->get('Checked Date')==''   }
            <li  class="li  cancelled">
                <div class="label">
                    <span class="state ">{t}Cancelled{/t}</span>
                </div>
                <div class="timestamp">
                    <span >&nbsp;{$return->get('Cancelled Date')}</span>
                </div>
                <div class="dot">
                </div>
            </li>
        {/if}

        <li id="checked_node"
            class="li  {if $return->get('State Index')>=50  or ($return->get('State Index')<0 and  $return->get('Checked Date')!='')   }complete{/if}">
            <div class="label">
                <span class="state">{t}Checked{/t}</span>
            </div>
            <div class="timestamp">
                <span class="Supplier_Delivery_Checked_Percentage_or_Date">&nbsp;{$return->get('Checked Percentage or Date')}</span>
            </div>
            <div class="dot">
            </div>
        </li>

        {if $return->get('State Index')<0  and  $return->get('Checked Date')!=''  }
            <li id="received_node" class="li  cancelled">
                <div class="label">
                    <span class="state ">{t}Cancelled{/t}</span>
                </div>
                <div class="timestamp">
                    <span >&nbsp;{$return->get('Cancelled Date')}</span>
                </div>
                <div class="dot">
                </div>
            </li>
        {/if}


        <li id="placed_node"
            class=" {if $return->get('State Index')<0}hide{/if} li {if $return->get('State Index')>=100}complete{/if}">
            <div class="label">
                <span class="state">{t}Booked in{/t}</span>
            </div>
            <div class="timestamp">
                <span class="Supplier_Delivery_Placed_Percentage_or_Date">&nbsp;{$return->get('Placed Percentage or Date')}</span>
            </div>
            <div class="dot">
            </div>
        </li>


    </ul>
</div>
<div class="order" style="display: flex;" data-object='{$object_data}'>
    <div class="block" style=" align-items: stretch;flex: 1">



        <div class="data_container" style="padding:5px 10px">
            <div class="data_field" style="margin:5px 0px 15px 0px ">
                <i class="fa fa-shopping-cart fa-fw" aria-hidden="true" title="{t}Order{/t}"></i> <span
                        onclick="change_view('orders/{$order->get('Order Store Key')}/{$order->id}')"
                        class="link ">{$order->get('Order Public ID')}</span>
            </div>

        </div>
        <div style="clear:both">
        </div>
    </div>




    <div class="block " style="align-items: stretch;flex: 1;">
        <div class="state" style="height:30px;margin-bottom:10px;position:relative;top:-5px">
            <div id="back_operations">
                <div id="delete_operations"
                     class="order_operation {if $return->get('Supplier Delivery State')!='InProcess'}hide{/if}">
                    <div class="square_button left" xstyle="padding:0;margin:0;position:relative;top:-5px"
                         title="{t}delete{/t}">
                        <i class="far fa-trash-alt very_discreet " aria-hidden="true"
                           onclick="toggle_order_operation_dialog('delete')"></i>
                        <table id="delete_dialog" class="order_operation_dialog hide">
                            <tr class="top">
                                <td colspan="2" class="label">{t}Delete delivery{/t}</td>
                            </tr>
                            <tr class="buttons changed">
                                <td><i class="fa fa-sign-out fa-flip-horizontal button" aria-hidden="true"
                                       onclick="close_dialog('delete')"></i></td>
                                <td class="aright"><span
                                            data-data='{ "object": "SupplierDelivery", "key":"{$return->id}"}'
                                            id="received_save_buttons" class="error save button"
                                            onClick="delete_object(this)"><span class="label">{t}Delete{/t}</span> <i
                                                class="fa fa-trash fa-fw  " aria-hidden="true"></i></span></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div id="cancel_operations"
                     class="order_operation {if $return->get('Supplier Delivery State')=='InProcess' or $return->get('Supplier Delivery State')=='Cancelled' or $return->get('Supplier Delivery Placed Items')=='Yes'   }hide{/if}">
                    <div class="square_button left" xstyle="padding:0;margin:0;position:relative;top:-5px"
                         title="{t}Cancel{/t}">
                        <i class="fa fa-minus-circle error " aria-hidden="true"
                           onclick="toggle_order_operation_dialog('cancel')"></i>
                        <table id="cancel_dialog" class="order_operation_dialog hide">
                            <tr class="top">
                                <td colspan="2" class="label">{t}Cancel delivery{/t}</td>
                            </tr>
                            <tr class="buttons changed">
                                <td><i class="fa fa-sign-out fa-flip-horizontal button" aria-hidden="true"
                                       onclick="close_dialog('cancel')"></i></td>
                                <td class="aright"><span
                                            data-data='{ "value": "Cancelled","dialog_name":"cancel", "field": "Supplier Delivery State"}'
                                            id="cancel_save_buttons" class="error save button"
                                            onclick="save_order_operation(this)"><span
                                                class="label">{t}Cancel{/t}</span> <i class="fa fa-cloud fa-fw  "
                                                                                      aria-hidden="true"></i></span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>


                <div id="undo_placed_operations"
                     class="order_operation {if $return->get('Supplier Delivery State')!='InvoiceChecked'   }hide{/if} hide">
                    <div class="square_button left" title="{t}Undo booked in{/t}">
                        <i class="far fa-undo-alt  "></i>
                        <table id="undo_placed_dialog" class="order_operation_dialog hide">
                            <tr class="top">
                                <td colspan="2" class="label">{t}Go back{/t}</td>
                            </tr>
                            <tr class="buttons changed">
                                <td><i class="fa fa-sign-out fa-flip-horizontal button" aria-hidden="true"
                                       onclick="close_dialog('undo_placed')"></i></td>
                                <td class="aright"><span
                                            data-data='{ "value": "RedoCosting","dialog_name":"go_back_to_pleced", "field": "Supplier Delivery State"}'
                                            id="undo_costing_save_buttons" class="valid save button"
                                            onclick="save_order_operation(this)"><span class="label">{t}Go back{/t}</span> <i
                                                class="fa fa-undo-alt fa-fw  " aria-hidden="true"></i></span></td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                
            </div>
            <span style="float:left;padding-left:10px;padding-top:5px"
                  class="Supplier_Delivery_State"> {$return->get('Return State')} </span>
            <div id="forward_operations">




                <div id="received_operations"
                     class=" order_operation {if !($return->get('Supplier Delivery State')=='InProcess' or  $return->get('Supplier Delivery State')=='Dispatched') }hide{/if}">
                    <div class="square_button right" title="{t}Mark delivery as received{/t}">
                        <i class="fa fa-arrow-circle-down fa-fw" aria-hidden="true"
                           onclick="toggle_order_operation_dialog('received')"></i>
                        <table id="received_dialog" class="order_operation_dialog hide">
                            <tr class="top">
                                <td class="label" colspan="2">{t}Mark return as received{/t}</td>
                            </tr>


                            <tr class="changed buttons">
                                <td><i class="fa fa-sign-out fa-flip-horizontal button" aria-hidden="true"
                                       onclick="close_dialog('received')"></i></td>
                                <td class="aright"><span
                                            data-data='{  "field": "Supplier Delivery State","value": "Received","dialog_name":"received"}'
                                            id="received_save_buttons" class="valid save button"
                                            onclick="save_order_operation(this)"><span class="label">{t}Save{/t}</span> <i
                                                class="fa fa-cloud fa-fw  " aria-hidden="true"></i></span></td>
                            </tr>
                        </table>
                    </div>
                </div>



                
            </div>
        </div>



        <table class="info_block acenter">

            <tr>

                <td style="text-align: center" colspan="2">

                    <span style="padding-right:20px"  title="{t}Number of items{/t}" ><i class="fa fa-bars fa-fw discreet"></i> <span class="Supplier_Delivery_Number_Items">{$return->get('Number Items')}</span></span>
                    <span><i class="fa fa-box-check fa-fw discreet" aria-hidden="true"></i>  <span
                                class="Supplier_Delivery_Number_Received_and_Checked_Items">{$return->get('Number Received and Checked Items')}</span></span>
                    <span style="padding-left:20px"><i class="fa fa-inventory fa-fw discreet" aria-hidden="true"></i>  <span
                                class="Supplier_Delivery_Number_Placed_Items">{$return->get('Number Placed Items')}</span></span>
                </td>
            </tr>

            <tr>

                <td style="width:50%;text-align: center" title="{t}Weight{/t}"><i class="far fa-weight-hanging {if $return->get('Supplier Delivery Weight')==''}hide{/if}"></i> <span class="Supplier_Delivery_Weight">{$return->get('Weight')}</span></td>


                <td style="text-align: center" class=" Supplier_Delivery_CBM" title="{t}CBM{/t}">{$return->get('CBM')}</td>
            </tr>


            <tr  class="Mismatched_Items  {if $return->get('Supplier Delivery Number Received and Checked Items')==0}hide{/if} ">
                <td style="width:50%;text-align: center;padding-top: 10px;padding-bottom: 10px"  title="{t}Items under delivered{/t}"><i class="far fa-box-open"></i>  <span class="Supplier_Delivery_Number_Under_Delivered_Items">{$return->get('Number Under Delivered Items')}</span></td>

                <td  style="text-align: center;padding-top: 10px;padding-bottom: 10px" title="{t}Items over delivered{/t}"><i class="fa fa-box-full"></i>  <span  class=" Supplier_Delivery_Number_Over_Delivered_Items">{$return->get('Number Over Delivered Items')}</span></td>


            </tr>

        </table>



    </div>
    <div class="block " style="align-items: stretch;flex: 1 ">

        <div style="clear:both">
        </div>
    </div>
    <div class="block " style="align-items: stretch;flex: 1;padding-top: 0px ">



        <table class="info_block">


            <tr class="hide" style="border-bottom:1px solid #ccc;" >
                <td class="label">{t}Purchase order amount{/t} </td>
                <td class="aright Supplier_Delivery_Items_Amount">{$return->get('Purchase Order Amount')}</td>
            </tr>


            <tr style="height: 16px" class="{if $account->get('Account Currency')==$return->get('Supplier Delivery Currency Code')}hide{/if}">
                <td class="small" colspan="2" style="border-bottom:1px solid #ccc;text-align: center">
                    {t}Supplier invoice currency{/t} <b>{$return->get('Supplier Delivery Currency Code')}</b>
                </td>



            </tr>


            <tr style="border-bottom:1px solid #eee;" >
                <td class="label">{t}Items{/t} </td>
                <td class="aright Supplier_Delivery_Items_Amount">{$return->get('Items Amount')}</td>
            </tr>

            <tr  >
                <td class="label">{t}Extra costs{/t} </td>
                <td class="aright Supplier_Delivery_Extra_Costs_Amount">{$return->get('Extra Costs Amount')}</td>
            </tr>

            <tr style="border-bottom:1px solid #ccc;border-top:1px solid #ccc;" >
                <td class="label">{t}Total{/t} </td>
                <td class="aright Supplier_Delivery_Total_Amount">{$return->get('Total Amount')}</td>
            </tr>


            <tbody class="{if $account->get('Account Currency')==$return->get('Supplier Delivery Currency Code')}hide{/if}">

            <tr style="height: 16px" class="{if $account->get('Account Currency')==$return->get('Supplier Delivery Currency Code')}hide{/if}">
                <td class="small" colspan="2" style="border-bottom:1px solid #ccc;text-align: center">
                    1 {$account->get('Currency Code')}={math equation="1/x" x=$return->get('Supplier Delivery Currency Exchange') format="%.5f"} {$return->get('Supplier Delivery Currency Code')}

                </td>



            </tr>

            <tr  >
                <td class="label">{t}Subtotal{/t}</td>
                <td class="aright Supplier_Delivery_AC_Subtotal_Amount">{$return->get('AC Subtotal Amount')}</td>
            </tr>
            <tr style="border-top:1px solid #eee;" >
                <td class="label">{t}Extra costs{/t}</td>
                <td class="aright Supplier_Delivery_AC_Extra_Costs_Amount">{$return->get('AC Extra Costs Amount')}</td>
            </tr>


            <tr style="border-top:1px solid #ccc;" >

                <td class="label">{t}Total{/t}</td>


                <td class="Supplier_Delivery_AC_Total_Amount aright ">{$return->get('AC Total Amount')}</td>
            </tr>

            </tbody>

        </table>
        <div style="clear:both">
        </div>
    </div>
    <div style="clear:both">
    </div>
</div>


<div id="new_delivery" class="table_new_fields hide">
    <div style="align-items: stretch;flex: 1;padding:20px 5px;border-right:1px solid #eee">
        <i key="" class="far fa-square fa-fw button" aria-hidden="true"></i> <span>{t}Select all{/t}</span>
    </div>
    <div style="align-items: stretch;flex: 1;padding:10px 20px;">
        <table style="width:50%;float:right;xborder-left:1px solid #ccc;width:100%;">
            <tr>
                <td class="label ">{t}Delivery Number{/t}</td>
                <td>
                    <input class="new_delivery_field" id="delivery_number" placeholder="{t}Delivery number{/t}"></td>
            </tr>
            <tr>
                <td><i class="fa fa-sign-out fa-flip-horizontal button" aria-hidden="true"
                       onclick="close_create_delivery()"></i></td>
                <td class="buttons save" onclick="save_create_delivery()"><span>{t}Save{/t}</span> <i
                            class=" fa fa-cloud fa-flip-horizontal " aria-hidden="true"></i></td>
            </tr>
        </table>
    </div>
</div>

<div id="location_results_container" class="search_results_container" style="width:220px;">
    <table id="location_results" style="background:white;">
        <tr class="hide" style=";" id="location_search_result_template" field="" value="" formatted_value=""
            onclick="select_location_option(this)">
            <td class="label" style="padding-left:5px;"></td>
        </tr>
    </table>
</div>


<div id="assign_barcode_to_part_results_container" class="search_results_container" style="width:420px;">
    <table id="assign_barcode_to_part_results" style="background:white;">
        <tr class="hide" style=";" id="assign_barcode_to_part_search_result_template" field="" value="" formatted_value=""
            onclick="select_assign_barcode_to_part_option(this)">
            <td class="code" style="padding-left:5px;"></td>
            <td class="label" style="padding-left:5px;"></td>
        </tr>
    </table>
</div>


<script>





</script>
