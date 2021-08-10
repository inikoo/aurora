{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 8 July 2021 at 18:11 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3
-->
*}


<div class="timeline_horizontal">

    <ul class="timeline" id="timeline">




        {if $delivery->get('State Index')<0  and  $delivery->get('Received Date')==''  }
            <li id="received_node" class="li  cancelled">
                <div class="label">
                    <span class="state ">{t}Cancelled{/t}</span>
                </div>
                <div class="timestamp">
                    <span >&nbsp;{$delivery->get('Cancelled Date')}</span>
                </div>
                <div class="dot">
                </div>
            </li>
        {/if}



        <li id="received_node"
            class="li  {if $delivery->get('State Index')>=40  or ($delivery->get('State Index')<0 and  $delivery->get('Received Date')!='')  }complete{/if}">
            <div class="label">
                <span class="state ">{t}Received{/t}</span>
            </div>
            <div class="timestamp">
                <span class="Fulfilment_Delivery_Estimated_or_Received_Date">&nbsp;{$delivery->get('Estimated or Received Date')}</span> <span class="start_date">{$delivery->get('Creation Date')}
            </div>
            <div class="dot">
            </div>
        </li>

        {if $delivery->get('State Index')<0  and  $delivery->get('Received Date')!='' and  $delivery->get('Checked Date')==''   }
            <li id="received_node" class="li  cancelled">
                <div class="label">
                    <span class="state ">{t}Cancelled{/t}</span>
                </div>
                <div class="timestamp">
                    <span >&nbsp;{$delivery->get('Cancelled Date')}</span>
                </div>
                <div class="dot">
                </div>
            </li>
        {/if}

        <li id="checked_node"
            class="li  {if $delivery->get('State Index')>=50  or ($delivery->get('State Index')<0 and  $delivery->get('Checked Date')!='')   }complete{/if}">
            <div class="label">
                <span class="state">{t}Checked{/t}</span>
            </div>
            <div class="timestamp">
                <span class="Fulfilment_Delivery_Checked_Percentage_or_Date">&nbsp;{$delivery->get('Checked Percentage or Date')}</span>
            </div>
            <div class="dot">
            </div>
        </li>

        {if $delivery->get('State Index')<0  and  $delivery->get('Checked Date')!=''  }
            <li id="received_node" class="li  cancelled">
                <div class="label">
                    <span class="state ">{t}Cancelled{/t}</span>
                </div>
                <div class="timestamp">
                    <span >&nbsp;{$delivery->get('Cancelled Date')}</span>
                </div>
                <div class="dot">
                </div>
            </li>
        {/if}


        <li id="placed_node"
            class=" {if $delivery->get('State Index')<0}hide{/if} li {if $delivery->get('State Index')>=100}complete{/if}">
            <div class="label">
                <span class="state">{t}Booked in{/t}</span>
            </div>
            <div class="timestamp">
                <span class="Fulfilment_Delivery_Placed_Percentage_or_Date">&nbsp;{$delivery->get('Placed Percentage or Date')}</span>
            </div>
            <div class="dot">
            </div>
        </li>


    </ul>
</div>
<div class="order object_data" style="display: flex;" data-object='{$object_data}'>


    <div class="block" style=" align-items: stretch;flex: 1">
        <div class="data_container" style="padding:5px 10px">
            <div class="data_field  " >

              <span class="button" onclick="change_view('fulfilment/{$delivery->get('Fulfilment Delivery Warehouse Key')}/customers/{if $delivery->get('Fulfilment Delivery Type')=='Part'}dropshipping{else}asset_keeping{/if}/{$delivery->get('Fulfilment Delivery Customer Key')}')">
                <i class="fa fa-user fa-fw" aria-hidden="true" title="{t}Customer{/t}"></i> <span

                          class="button Order_Customer_Name">{$delivery->get('Fulfilment Delivery Customer Name')}</span> <span

                          class="link Order_Customer_Key">{$delivery->get('Fulfilment Delivery Customer Key')|string_format:"%05d"}</span>
              </span>
            </div>
            <div class="data_field {if $delivery->get('Fulfilment Delivery Customer Name')==$delivery->get('Fulfilment Delivery Customer Contact Name')}hide{/if} " >
                <i class="fa fa-fw  fa-male super_discreet" title="{t}Contact name{/t}"  ></i> <span  class=" Order_Customer_Contact_Name">{$delivery->get('Fulfilment Delivery Customer Contact Name')}</span>
            </div>


            <div class="data_field small  " style="margin-top:10px">

                <span id="display_telephones"></span>
                {if $customer->get('Customer Preferred Contact Number')=='Mobile'}
                    <div id="Customer_Main_Plain_Mobile_display"
                         class="data_field {if !$customer->get('Customer Main Plain Mobile')}hide{/if}">
                        <i class="fal fa-fw fa-mobile"></i> <span class="Customer_Main_Plain_Mobile">{$customer->get('Main XHTML Mobile')}</span>
                    </div>
                    <div id="Customer_Main_Plain_Telephone_display"
                         class="data_field {if !$customer->get('Customer Main Plain Telephone')}hide{/if}">
                        <i class="fal fa-fw fa-phone"></i> <span class="Customer_Main_Plain_Telephone">{$customer->get('Main XHTML Telephone')}</span>
                    </div>
                {else}
                    <div id="Customer_Main_Plain_Telephone_display"
                         class="data_field {if !$customer->get('Customer Main Plain Telephone')}hide{/if}">
                        <i title="Telephone" class="fa fa-fw fa-phone"></i> <span class="Customer_Main_Plain_Telephone">{$customer->get('Main XHTML Telephone')}</span>
                    </div>
                    <div id="Customer_Main_Plain_Mobile_display"
                         class="data_field {if !$customer->get('Customer Main Plain Mobile')}hide{/if}">
                        <i title="Mobile" class="fal fa-fw fa-mobile"></i> <span
                                class="Customer_Main_Plain_Mobile">{$customer->get('Main XHTML Mobile')}</span>
                    </div>
                {/if}

            </div>

            <div class="data_field small {if $customer->get('Customer Main Plain Email')==''}hide{/if}" style="margin-top:5px">
                <div >
                    <i class="fal fa-envelope fa-fw" title="{t}Email{/t}"></i> {if $customer->get('Customer Main Plain Email')!=''}{mailto address=$customer->get('Customer Main Plain Email')}{/if}
                </div>
            </div>
            <div class="data_field small Order_Tax_Number_display {if $delivery->get('Fulfilment Delivery Tax Number')==''}hide{/if} " style="margin-top:5px">
                <i class="fal fa-fw fa-passport" title="{t}Tax number{/t}"></i> <span class="Order_Tax_Number_Formatted">{$delivery->get('Tax Number Formatted')}</span>
            </div>

            <div class="data_field Order_Registration_Number_display {if $delivery->get('Fulfilment Delivery Registration Number')==''}hide{/if}  small" style="margin-top:5px">
                <i class="fal fa-fw fa-id-card" title="{t}Registration number{/t}"></i> <span class="Order_Registration_Number">{$delivery->get('Registration Number')}</span>
            </div>





        </div>
        <div style="clear:both"></div>
    </div>






    <div class="block " style="align-items: stretch;flex: 1;">
        <div class="state" style="height:30px;margin-bottom:10px;position:relative;top:-5px">
            <div id="back_operations">
                <div id="delete_operations"
                     class="order_operation {if $delivery->get('Fulfilment Delivery State')!='InProcess'}hide{/if}">
                    <div class="square_button left"
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
                                            data-data='{ "object": "Fulfilment_Delivery", "key":"{$delivery->id}"}'
                                            id="received_save_buttons" class="error save button"
                                            onClick="delete_object(this)"><span class="label">{t}Delete{/t}</span> <i
                                                class="fa fa-trash fa-fw  " aria-hidden="true"></i></span></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div id="cancel_operations"
                     class="order_operation {if $delivery->get('Fulfilment Delivery State')=='InProcess' or $delivery->get('Fulfilment Delivery State')=='Cancelled' or $delivery->get('Fulfilment Delivery Placed Items')=='Yes'   }hide{/if}">
                    <div class="square_button left"
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
                                            data-data='{ "value": "Cancelled","dialog_name":"cancel", "field": "Fulfilment Delivery State"}'
                                            id="cancel_save_buttons" class="error save button"
                                            onclick="save_order_operation(this)"><span
                                                class="label">{t}Cancel{/t}</span> <i class="fa fa-cloud fa-fw  "
                                                                                      aria-hidden="true"></i></span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>


                <div id="undo_received_operations"
                     class="order_operation {if $delivery->get('Fulfilment Delivery State')!='Received'  or $delivery->get('Fulfilment Delivery Placed Items')=='Yes' }hide{/if}">
                    <div class="square_button left" title="{t}Set as not received{/t}">
						<span class="fa-stack" style="position:relative;top:-1px;left:-5px"
                              onclick="toggle_order_operation_dialog('undo_received')">
						<i class="fa fa-arrow-circle-down discreet " aria-hidden="true"></i>
						<i class="fa fa-ban fa-stack-1x very_discreet error"></i>
						</span>
                        <table id="undo_received_dialog" class="order_operation_dialog hide">
                            <tr class="top">
                                <td colspan="2" class="label">{t}Set as not received{/t}</td>
                            </tr>
                            <tr class="buttons changed">
                                <td><i class="fa fa-sign-out fa-flip-horizontal button" aria-hidden="true"
                                       onclick="close_dialog('undo_received')"></i></td>
                                <td class="aright"><span
                                            data-data='{ "value": "InProcess","dialog_name":"undo_received", "field": "Fulfilment Delivery State"}'
                                            id="undo_received_save_buttons" class="valid save button"
                                            onclick="save_order_operation(this)"><span class="label">{t}Save{/t}</span> <i
                                                class="fa fa-cloud fa-fw  " aria-hidden="true"></i></span></td>
                            </tr>
                        </table>
                    </div>
                </div>



            </div>
            <span style="float:left;padding-left:10px;padding-top:5px"
                  class="Fulfilment_Delivery_State"> {$delivery->get('State')} </span>
            <div id="forward_operations">

                <div id="received_operations"
                     class=" order_operation {if $delivery->get('Fulfilment Delivery State')!='InProcess'  }hide{/if}">
                    <div class="square_button right" title="{t}Mark delivery as received{/t}">
                        <i class="fa fa-arrow-circle-down fa-fw" aria-hidden="true"
                           onclick="toggle_order_operation_dialog('received')"></i>
                        <table id="received_dialog" class="order_operation_dialog hide">
                            <tr class="top">
                                <td class="label" colspan="2">{t}Mark delivery as received{/t}</td>
                            </tr>

                            <tr class="changed buttons">
                                <td><i class="fa fa-sign-out fa-flip-horizontal button" aria-hidden="true"
                                       onclick="close_dialog('received')"></i></td>
                                <td class="aright"><span
                                            data-data='{  "field": "Fulfilment Delivery State","value": "Received","dialog_name":"received"}'
                                            id="received_save_buttons" class="valid save button"
                                            onclick="save_order_operation(this)"><span class="label">{t}Save{/t}</span> <i
                                                class="fa fa-cloud fa-fw  " aria-hidden="true"></i></span></td>
                            </tr>
                        </table>
                    </div>
                </div>




                <div id="costing_operations"
                     class="order_operation {if $delivery->get('Fulfilment Delivery State')!='Placed'}hide{/if}">
                    <div class="square_button right" title="{t}Start checking costs{/t}">

                        <i class="far fa-box-usd fa-fw" aria-hidden="true" data-data='{ "value": "Costing","dialog_name":"costing", "field": "Fulfilment Delivery State"}'
                           onclick="save_order_operation(this)"></i>


                        <table id="costing_dialog" class="order_operation_dialog hide">
                            <tr class="top">
                                <td class="label"
                                    colspan="2">{t}Start checking costs{/t}</td>
                            </tr>




                            <tr class="buttons">
                                <td><i class="fa fa-sign-out fa-flip-horizontal button" aria-hidden="true"
                                       onclick="close_dialog('costing')"></i></td>
                                <td class="aright">
								<span data-data='{ "value": "Costing","dialog_name":"costing", "field": "Fulfilment Delivery State"}'
                                      id="costing_save_buttons" class="valid save button changed"
                                      onclick="save_order_operation(this)">
								<span class="label">{t}Save{/t}</span> <i class="fa fa-cloud fa-fw  "
                                                                          aria-hidden="true"></i></span></td>
                            </tr>
                        </table>
                    </div>
                </div>

            </div>
        </div>



        <table class="info_block acenter">
            <tr class="info_in_process {if $delivery->get('Fulfilment Delivery State')!='InProcess'}hide{/if}">

                <td style="text-align: center" colspan="2">

                    <span><i class="fal fa-pallet-alt fa-fw discreet" aria-hidden="true"></i>  <span
                                class="Fulfilment_Delivery_Estimated_Pallets">{$delivery->get('Estimated Pallets')}</span></span>
                    <span style="padding-left:20px"><i class="fal fa-box-alt fa-fw discreet" aria-hidden="true"></i>  <span
                                class="Fulfilment_Delivery_Estimated_Boxes">{$delivery->get('Estimated Boxes')}</span></span>
                </td>
            </tr>
            <tr class="info_received  {if $delivery->get('Fulfilment Delivery State')=='InProcess'}hide{/if}">

                <td style="text-align: center" colspan="2">

                    <span style="padding-right:20px"  title="{t}Number of items{/t}" ><i class="fa fa-bars fa-fw discreet"></i> <span class="Fulfilment_Delivery_Number_Items">{$delivery->get('Number Items')}</span></span>
                    <span><i class="fa fa-box-check fa-fw discreet" aria-hidden="true"></i>  <span
                                class="Fulfilment_Delivery_Number_Received_and_Checked_Items">{$delivery->get('Number Received and Checked Items')}</span></span>
                    <span style="padding-left:20px"><i class="fa fa-inventory fa-fw discreet" aria-hidden="true"></i>  <span
                                class="Fulfilment_Delivery_Number_Placed_Items">{$delivery->get('Number Placed Items')}</span></span>
                </td>
            </tr>



            <tr  class="Mismatched_Items {if $delivery->get('Fulfilment Delivery Number Received and Checked Items')==0}hide{/if} ">
                <td style="text-align: center;padding-top: 20px"  title="{t}Items under delivered{/t}"><i class="far fa-box-open"></i>  <span class="Fulfilment_Delivery_Number_Under_Delivered_Items">{$delivery->get('Number Under Delivered Items')}</span></td>

                <td  style="text-align: center;padding-top: 20px" title="{t}Items over delivered{/t}"><i class="fa fa-box-full"></i>  <span  class=" Fulfilment_Delivery_Number_Over_Delivered_Items">{$delivery->get('Number Over Delivered Items')}</span></td>


            </tr>

        </table>



    </div>
    <div class="block " style="align-items: stretch;flex: 1 ">


        <div class="node  Invoice_Info {if $delivery->get('State Index')!=110}hide{/if} " >
                    <span class="node_label">
                        <i class="fal fa-file-invoice-dollar fa-fw" ></i>
                        <span class="Formatted_Invoice_Public_ID margin_right_10">{$delivery->get('Formatted Invoice Public ID')}</span>
                        <span class="italic Formatted_Invoice_Date">{$delivery->get('Formatted Invoice Date')}</span>

                    </span>


        </div>

        <div style="clear:both">

        </div>
    </div>
    <div class="block " style="align-items: stretch;flex: 1;padding-top: 0 ">




        <div style="clear:both">
        </div>
    </div>
    <div style="clear:both">
    </div>
</div>



<div id="asset_location_results_container" class="search_results_container" style="width:220px;">
    <table class="location_results" style="background:white;">
        <tr class="hide asset_location_search_result_template" data-field="" data-value="" data-formatted_value=""
            onclick="select_asset_location_option(this)">
            <td class="label" style="padding-left:5px;"></td>
        </tr>
    </table>
</div>


