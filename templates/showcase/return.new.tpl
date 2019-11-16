{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 23 November 2018 at 14:22:11 GMT+8, Kaula Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}

{assign deliveries $order->get_deliveries('objects')}
{assign invoices $order->get_invoices('objects')}
{assign payments $order->get_payments('objects','Completed')}

<div id="order" class="order" style="display: flex;" data-object='{$object_data}' order_key="{$order->id}">
    <div class="block" style=" align-items: stretch;flex: 1">
        <div class="data_container" style="padding:5px 10px">
            <div class="data_field  " style="margin-bottom:10px">

              <span class="button" onclick="change_view('customers/{$order->get('Order Store Key')}/{$order->get('Order Customer Key')}')">
                <i class="fa fa-user fa-fw" aria-hidden="true" title="{t}Customer{/t}"></i> <span

                          class="button Order_Customer_Name">{$order->get('Order Customer Name')}</span> <span

                          class="link Order_Customer_Key">{$order->get('Order Customer Key')|string_format:"%05d"}</span>
              </span>
            </div>

            <div class="data_field small {if $order->get('Telephone')==''}hide{/if}  " style="margin-top:5px">
                <div ><i class="fa fa-phone fa-fw" aria-hidden="true" title="{t}Customer{/t}"></i><span class="Order_Telephone">{$order->get('Telephone')}</span></div>


            </div>

            <div class="data_field small {if $order->get('Email')==''}hide{/if}" style="margin-top:5px">


                <div ><i class="fa fa-envelope fa-fw" aria-hidden="true" title="{t}Customer{/t}"></i><span class="Order_Email">{$order->get('Email')}</span></div>

            </div>

            <div class="data_field  " style="padding:10px 0px 20px 0px;">
                <div style="float:left;padding-bottom:20px;padding-right:20px" class="Delivery_Address">
                    <div style="margin-bottom:10px">
                        <span class="{if $order->get('Order For Collection')=='Yes'}hide{/if}"><i   class="   fa fa-truck fa-flip-horizontal button" aria-hidden="true""></i>{t}Deliver to{/t}</span>
                        <span class="{if $order->get('Order For Collection')=='No'}hide{/if}"><i   class="   far fa-hand-rock fa-flip-horizontal button" aria-hidden="true""></i>{t}Collection{/t}</span>

                    </div>

                    <div class="small Order_Delivery_Address " style="max-width: 140px;">{$order->get('Order Delivery Address Formatted')}</div>
                </div>

                <div style="clear:both"></div>
            </div>


        </div>
        <div style="clear:both"></div>
    </div>

    <div class="block " style="align-items: stretch;flex: 1 ">

        <div id="delivery_notes" class="delivery_notes {if $deliveries|@count == 0}hide{/if}" style="position:relative;top:-5px;">



        {foreach from=$deliveries item=dn}

            <div class="node" id="delivery_node_{$dn->id}">
                    <span class="node_label" >
                         <i class="fa fa-truck fa-flip-horizontal fa-fw " aria-hidden="true"></i> <span class="link" onClick="change_view('delivery_notes/{$dn->get('Delivery Note Store Key')}/{$dn->id}')">{$dn->get('ID')}</span>
                        (<span class="Delivery_Note_State">{$dn->get('Abbreviated State')}</span>)
                        <a class="pdf_link {if $dn->get('State Index')<90 }hide{/if}" target='_blank' href="/pdf/dn.pdf.php?id={$dn->id}"> <img style="width: 50px;height:16px;position: relative;top:2px" src="/art/pdf.gif"></a>
                    </span>
            </div>
        {/foreach}

        </div>

        <div style="clear:both"></div>
    </div>
    <div class="block " style="align-items: stretch;flex: 1 ">
    <table class="totals" style="position:relative;top:-5px">

        <tr class="total ">

            <td colspan="2" class="align_center ">{t}Original order{/t}</td>
        </tr>


        <tr>
            <td class="label">{t}Items{/t}</td>
            <td class="aright Items_Net_Amount">{$order->get('Items Net Amount')}</td>
        </tr>
       
        <tr>
            <td class="label"  id="Charges_Net_Amount_label" >{t}Charges{/t}</td>
            <td class="aright "><span id="Charges_Net_Amount_form" class="hide"><i id="set_charges_as_auto" class="fa fa-magic button" onClick="set_charges_as_auto()" aria-hidden="true"></i>  <input value="{$order->get('Order Charges Net Amount')}" ovalue="{$order->get('Order Charges Net Amount')}"  style="width: 100px" id="Charges_Net_Amount_input"  > <i id="Charges_Net_Amount_save" class="fa fa-cloud save" onClick="save_charges_value()" aria-hidden="true"></i> </span><span id="Charges_Net_Amount" class="Charges_Net_Amount button" >{$order->get('Charges Net Amount')}<span></td>
        </tr>
        <tr>
            <td class="label"  id="Shipping_Net_Amount_label" >{t}Shipping{/t}</td>
            <td class="aright "><span id="Shipping_Net_Amount_form" class="hide"><i id="set_shipping_as_auto" class="fa fa-magic button" onClick="set_shipping_as_auto()" aria-hidden="true"></i>  <input value="{$order->get('Order Shipping Net Amount')}" ovalue="{$order->get('Order Shipping Net Amount')}"  style="width: 100px" id="Shipping_Net_Amount_input"  > <i id="Shipping_Net_Amount_save" class="fa fa-cloud save" onClick="save_shipping_value()" aria-hidden="true"></i> </span><span id="Shipping_Net_Amount" class="Shipping_Net_Amount button" >{$order->get('Shipping Net Amount')}<span></td>
        </tr>
        <tr class="subtotal">
            <td class="label">{t}Net{/t}</td>
            <td class="aright Total_Net_Amount">{$order->get('Total Net Amount')}</td>
        </tr>

        <tr class="subtotal">
            <td class="label">{t}Tax{/t}</td>
            <td class="aright Total_Tax_Amount">{$order->get('Total Tax Amount')}</td>
        </tr>

        <tr class="total">
            <td class="label">{t}Total{/t}</td>
            <td class="aright Total_Amount  button " amount="{$order->get('Order To Pay Amount')}" onclick="try_to_pay(this)">{$order->get('Total Amount')}</td>
        </tr>




        </tbody>



    </table>
    <div style="clear:both"></div>
</div>
    <div class="block " style="align-items: stretch;flex: 1;">
        <div class="state" style="height:30px;margin-bottom:10px;position:relative;top:-5px">
            <div id="back_operations">

            </div>
            <span style="float:left;padding-left:10px;padding-top:5px" class="Order_State"> {t}Create return{/t} </span>
            <div id="forward_operations">

                <div id="create_return_operations" class="order_operation {if {$order->get('State Index')}<100  }hide{/if}">
                    <div  class="square_button right  " title="{t}Create return{/t}">
                        <i class="fa fa-cloud save   open_create_return_dialog_button" aria-hidden="true" onclick="save_return(this)"></i>

                    </div>
                </div>



            </div>
        </div>

        <table class="info_block acenter">

            <tr>

                <td class="hide">
                    <span ><i class="fa fa-cube fa-fw discreet" aria-hidden="true"></i> <span class="affected_items">0</span> / <span class="Order_Number_items">{$order->get('Number Items')}</span></span>


                </td>
            </tr>






        </table>

    </div>
<div style="clear:both"></div></div>
