{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 2 October 2017 at 18:53:20 GMT+8, Kuala Lumpur, Malaydia
 Copyright (c) 2016, Inikoo

 Version 3
-->
*}
<style>
   #select_date_control_panel td{
       padding:0px}
</style>


<div class="timeline_horizontal with_time   {if $email_campaign->get('State Index')<0}hide{/if}  ">

    <ul class="timeline">
        <li id="submitted_node" class="li {if $email_campaign->get('State Index')>=20}complete{/if}">
            <div class="label">
                <span class="state ">{t}Setup mail list{/t}</span>
            </div>
            <div class="timestamp">
                <span class="">&nbsp;</span> <span class="start_date">{$email_campaign->get('Creation Date')}</span>
            </div>
            <div class="dot"></div>
        </li>

        <li id="in_warehouse_node" class="li {if $email_campaign->get('State Index')>=30}complete{/if} ">
            <div class="label">
                <span class="state" >&nbsp;{t}Compose email{/t}&nbsp;<span></i></span></span>
            </div>
            <div class="timestamp">
			<span class="Order_In_Warehouse" ">&nbsp;

                    <span class="">&nbsp;</span>

                &nbsp;</span>
            </div>
            <div class="dot"></div>
        </li>

        <li id="packed_done_node" class="li {if $email_campaign->get('State Index')>=80}complete{/if} ">
            <div class="label">
                <span class="state">{t}Schedule send{/t}</span>
            </div>
            <div class="timestamp">
                <span>&nbsp;<span class="Order_Packed_Done_Date">&nbsp;{$email_campaign->get('Packed Done Date')}</span></span>
            </div>
            <div class="dot"></div>
        </li>

        <li  id="approved_node"  class="li {if $email_campaign->get('State Index')>=90}complete{/if} ">
            <div class="label">
                <span class="state">{t}Start send{/t}</span>
            </div>
            <div class="timestamp">
                <span>&nbsp;<span class="Order_Invoiced_Date">&nbsp;{$email_campaign->get('Invoiced Date')}</span></span>
            </div>
            <div class="dot"></div>
        </li>

        <li  id="dispatched_node"  class="li  {if $email_campaign->get('State Index')>=100}complete{/if}">
            <div class="label">
                <span class="state">{t}Send{/t}</span>
            </div>
            <div class="timestamp">
                <span>&nbsp;<span class=""></span>
                    &nbsp;</span>
            </div>
            <div class="dot"></div>
        </li>

    </ul>


</div>


<div class="timeline_horizontal  {if $email_campaign->get('State Index')>0}hide{/if}">
    <ul class="timeline" id="timeline">
        <li id="submitted_node" class="li complete">
            <div class="label">
                <span class="state ">{t}Submitted{/t}</span>
            </div>
            <div class="timestamp">
                <span class="Purchase_Order_Submitted_Date">&nbsp;{$email_campaign->get('Submitted Date')}</span> <span class="start_date">{$email_campaign->get('Created Date')} </span>
            </div>
            <div class="dot"></div>
        </li>

        <li id="send_node" class="li  cancelled">
            <div class="label">
                <span class="state ">{t}Cancelled{/t} <span></i></span></span>
            </div>
            <div class="timestamp">
                <span class="Cancelled_Date">{$email_campaign->get('Cancelled Date')} </span>
            </div>
            <div class="dot"></div>
        </li>


    </ul>
</div>

<div id="email_campaign" class="order" style="display: flex;" data-object="{$object_data}" email_campaign_key="{$email_campaign->id}">
    <div class="block" style="padding:10px 20px; align-items: stretch;flex: 1">


            <div class="{if $email_campaign->get('State Index')>=50}hide{/if}">
                <span class="hide Email_Campaign_Number_Estimated_Emails">{$email_campaign->get('Email Campaign Number Estimated Emails')}</span>
            <span>{t}Estimated recipients{/t}</span> <span class="strong Number_Estimated_Emails">{$email_campaign->get('Number Estimated Emails')}</span>
            </div>

        <div style="clear:both"></div>
    </div>
    <div class="block " style="align-items: stretch;flex: 1;">
        <div class="state" style="height:30px;margin-bottom:10px;position:relative;top:-5px">
            <div id="back_operations">

                <div id="delete_operations" class="email_campaign_operation {if $email_campaign->get('State Index')<0}hide{/if}">
                    <div class="square_button left" title="{t}Delete{/t}">


                        <i class="fa fa-trash discreet " aria-hidden="true" onclick="toggle_order_operation_dialog('delete')"></i>
                        <table id="delete_dialog" border="0" class="order_operation_dialog hide">
                            <tr class="top">
                                <td colspan="2" >{t}Delete mailshot{/t}</td>
                            </tr>
                            <tr class="changed buttons">
                                <td>
                                    <i class="fa fa-sign-out fa-flip-horizontal button" aria-hidden="true" onclick="close_dialog('delete')"></i>
                                </td>
                                <td class="aright">
                                    <span data-data='{ "object": "email_campaign", "key":"{$email_campaign->id}"  }' onClick="delete_object(this)">
                                    <span class="label">{t}Delete{/t}</span>
                                    <i class="fa fa-cloud fa-fw  " aria-hidden="true"></i>
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>

                </div>
                <div id="undo_submit_operations" class="email_campaign_operation {if $email_campaign->get('State Index')!=50}hide{/if}">
                    <div class="square_button left" title="{t}Stop sending emails{/t}">
												<span class="fa-stack" onclick="toggle_email_campaign_operation_dialog('undo_submit')">
						<i class="fa fa-paper-plane-o discreet " aria-hidden="true"></i>
						<i class="fa fa-ban fa-stack-1x discreet error"></i>
						</span>


                        <table id="undo_submit_dialog" border="0" class="order_operation_dialog hide">
                            <tr class="top">
                                <td class="label" colspan="2">{t}Send back to basket{/t}</td>
                            </tr>
                            <tr class="changed buttons">
                                <td><i class="fa fa-sign-out fa-flip-horizontal button" aria-hidden="true" onclick="close_dialog('undo_submit')"></i></td>
                                <td class="aright"><span data-data='{  "field": "Order State","value": "InBasket","dialog_name":"undo_submit"}' id="undo_submit_save_buttons" class="valid save button"
                                                         onclick="save_email_campaign_operation(this)"><span class="label">{t}Save{/t}</span> <i class="fa fa-cloud fa-fw  " aria-hidden="true"></i></span></td>
                            </tr>
                        </table>
                    </div>
                </div>

            </div>
            <span style="float:left;padding-left:10px;padding-top:5px" class="Order_State"> {$email_campaign->get('State')} </span>
            <div id="forward_operations">



                <div id="schedule_mailshot_operations" class="email_campaign_operation {if {$email_campaign->get('State Index')}!=30  }hide{/if}">
                    <div  class="square_button right  " title="{t}Schedule mailshot{/t}">
                        <i class="fa fa-clock-o  " aria-hidden="true" onclick="toggle_order_operation_dialog('schedule_mailshot')"></i>






                        <table id="schedule_mailshot_dialog" style="width: 650px" border="1" class="order_operation_dialog hide">
                            <tr class="top">
                                <td class="label" colspan="2"><i class="fa fa-sign-out fa-flip-horizontal button padding_left_20" aria-hidden="true" onclick="close_dialog('schedule_mailshot')"></i>{t}Schedule mailshot{/t}</td>
                            </tr>

                            <tr style="height: 50px">
                                <td style="text-align: center">
                                    <span class="square_button" style="border:1px solid #ccc;padding:5px">{t}Send now{/t}</span>
                                    <span class="square_button" style="border:1px solid #ccc;padding:5px;margin-right: 10px;margin-left: 10px">{t}Send in <em>n</em> hours{/t}</span>
                                    <span class="square_button" style="border:1px solid #ccc;padding:5px">{t}Choose time{/t}</span>

                                </td>
                            </tr>


                            <tr class="changed buttons">

                                <td style="text-align: center">{t}Send in{/t}  <input type="number"  style="width:3em"  > {t}hours{/t}  <span class="save "><i class="fa fa-cloud fa-fw  " aria-hidden="true"></i></span></td>
                            </tr>



                            <tr class="changed buttons">

                                <td style="text-align: center">

                                    <div id="select_date_control_panel" class="xhide">
                                        <div id="select_date_datepicker" class="datepicker" style="float:left">
                                        </div>
                                        <div class="date_chooser_form">

                                            <table>
                                                <tr  style="height: 20px"><td style="text-align: left;line-height:20px" class="small">{t}Date{/t}</td></tr>
                                                <tr><td> <input id="select_date_formatted"  style="width: 7em" class="" value=""/></td><td></td></tr>
                                                <tr style="height: 20px"><td  style="text-align: left;line-height:20px" class="small">{t}time{/t}</td></tr>
                                                <tr ><td style="text-align: left"> <input id="select_time_formatted"  style="width: 5em" class="" value=""/></td>
                                                    <td><i onclick="submit_date()" id="select_date_save" class="fa button fa-play save padding_left_20"></i></td>
                                                </tr>
                                            </table>



                                        </div>
                                        <div style="clear:both"></div>
                                    </div>

                                </td>
                            </tr>







                            <tr class="changed buttons">

                                <td class="aright"><span data-data='{  "field": "Order State","value": "Approved","dialog_name":"schedule_mailshot"}' id="schedule_mailshot_save_buttons" class="valid save button"
                                                         onclick="save_email_campaign_operation(this)"><span class="label">{t}Save{/t}</span> <i class="fa fa-cloud fa-fw  " aria-hidden="true"></i></span></td>
                            </tr>
                        </table>
                    </div>
                </div>


                <div id="compose_email_operations" class="email_campaign_operation {if $email_campaign->get('State Index')!=10   or  $email_campaign->get('Email Campaign Number Estimated Emails')==0 }hide{/if}">
                    <div  class="square_button right  " title="{t}Submit{/t}">
                        <i class="fa fa-arrow-right button"  id="compose_email_save_buttons" aria-hidden="true"  data-data='{  "field": "Email Campaign State","value": "ComposingEmail","dialog_name":"compose_email"}'   onclick="save_email_campaign_operation(this)" ></i>

                    </div>
                </div>


            </div>
        </div>

        <table border="0" class="hide info_block acenter">

            <tr>

                <td>
                    <span style=""><i class="fa fa-cube fa-fw discreet" aria-hidden="true"></i> <span class="Order_Number_items">{$email_campaign->get('Number Items')}</span></span>
                    <span style="padding-left:20px"><i class="fa fa-tag fa-fw  " aria-hidden="true"></i> <span class="Order_Number_Items_with_Deals">{$email_campaign->get('Number Items with Deals')}</span></span>
                    <span class="error {if $email_campaign->get('Order Number Items Out of Stock')==0}hide{/if}" style="padding-left:20px"><i class="fa fa-cube fa-fw  " aria-hidden="true"></i> <span
                                class="Order_Number_Items_with_Out_of_Stock">{$email_campaign->get('Number Items Out of Stock')}</span></span>
                    <span class="error {if $email_campaign->get('Order Number Items Returned')==0}hide{/if}" style="padding-left:20px"><i class="fa fa-thumbs-o-down fa-fw   " aria-hidden="true"></i> <span
                                class="Order_Number_Items_with_Returned">{$email_campaign->get('Number Items Returned')}</span></span>
                </td>
            </tr>






        </table>

    </div>
    <div class="block " style="align-items: stretch;flex: 1 ">









        <div class="payments {if $email_campaign->get('Order Number Items')==0  or $email_campaign->get('State Index')<0 }hide{/if}  "  >


            {assign expected_payment $email_campaign->get('Expected Payment')}


            <div id="expected_payment" class="payment node  {if $expected_payment==''}hide{/if} " >




                <span class="node_label   ">{$expected_payment}</span>







            </div>



            <div id="create_payment" class="payment node">


             <span class="node_label very_discreet italic">{t}Payments{/t}</span>


            <div class="payment_operation {if $email_campaign->get('Order To Pay Amount')<=0     }hide{/if}  ">
                <div class="square_button right" style="padding:0;margin:0;position:relative;top:0px" title="{t}add payment{/t}">
                    <i class="fa fa-plus" aria-hidden="true" onclick="show_add_payment_to_order()"></i>

                </div>
            </div>




    </div>




    </div>

<div style="clear:both"></div></div>
<div class="block " style="align-items: stretch;flex: 1 ">
    <table border="0" class="totals {if $email_campaign->get('State Index')<30}hide{/if}" style="position:relative;top:-5px;margin-bottom:20px">

        <tr>
            <td class="label">{t}Send{/t}</td>
            <td class="aright Send_Emails">{$email_campaign->get('Send Email')}</td>
        </tr>
        <tr class="subtotal">
            <td class="label">{t}Soft bounced{/t}</td>
            <td class="aright Soft_Bounced_Emails">{$email_campaign->get('Soft Bounced Emails')}</td>
        </tr>
        <tr>
            <td class="label">{t}Hard bounced{/t}</td>
            <td class="aright Hard_Bounced_Emails">{$email_campaign->get('Hard Bounced Emails')}</td>
        </tr>


        <tr class="subtotal">
            <td class="label">{t}Read{/t}</td>
            <td class="aright Read_Emails">{$email_campaign->get('Read Emails')}</td>
        </tr>
        <tr class="subtotal">
            <td class="label">{t}Basket updated{/t}</td>
            <td class="aright Goal_A_Emails">{$email_campaign->get('Goal A Emails')}</td>
        </tr>
        <tr class="total">
            <td class="label">{t}Order placed{/t}</td>
            <td class="aright Goal_B_Emails">{$email_campaign->get('Goal B Emails')}</td>
        </tr>




    </table>
    <div style="clear:both"></div>
</div>
<div style="clear:both"></div></div>


<script>

    $("#select_date_datepicker").datepicker({
        showOtherMonths: true,
        selectOtherMonths: true,
        defaultDate: new Date(""),
        altField: "#select_date",
        altFormat: "yy-mm-dd",
        minDate: 0,
        onSelect: function () {
            $('#select_date').change();
            $('#select_date_formatted').val($.datepicker.formatDate("dd-mm-yy", $(this).datepicker("getDate")))
            validate_date()
        }
    });

    function validate_date() {
        $('#select_date_save').removeClass('possible_valid valid invalid')

        if ($("#select_date_formatted").val() == '') {
            validation = 'possible_valid';
        } else {
            validation = 'valid';

        }
        $('#select_date_save').addClass(validation)

    }



</script>
