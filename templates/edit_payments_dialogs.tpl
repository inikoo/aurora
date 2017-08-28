{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 28 August 2017 at 14:04:29 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3
-->
*}
<style>
    #payment_refund_dialog .operation_button{
        padding:4px 10px;border:1px solid #ccc;cursor: pointer;
    }

    #payment_refund_dialog .operation_button.selected{
        padding:4px 10px;border:1px solid #aaa
    }

    #payment_refund_dialog .operation_button.no_selected{
        opacity: .2;
    }
</style>


<div id="payment_refund_dialog"  style="position: absolute;background-color: #fff;z-index: 2000;border: 1px solid #ccc;padding:10px;width:350px" class="hide" >

    <input type="hidden"  id="payment_refund_max_amount" value=""  >
    <input type="hidden"  id="payment_refund_refund_type" value=""  >
    <input type="hidden"  id="payment_refund_submit_type" value=""  >
    <input type="hidden"  id="payment_refund_payment_key" value=""  >



    <table style="width: 100%;border-bottom: 1px solid #ccc;margin-bottom: 5px" border="0">
        <tr>
            <td>{t}Payment{/t}</td><td class="payment_reference"></td>
        </tr>
        <tr>
            <td>{t}Refundable amount{/t}</td><td onclick="copy_max_refundable_amount()" class="payment_refundable_amount button"></td>
        </tr>

    </table>

    <table style="width: 100%" border="0">

        <tr style=" height: 35px;">
            <td colspan="2" style="text-align: center">
                <span class="refund_type select_credit operation_button unselectable " onclick="payment_refund_credit_selected()" >{t}Credit{/t}</span>
                <span class="refund_type select_refund operation_button unselectable "  onclick="payment_refund_refund_selected()">{t}Refund{/t}</span>
            </td>
        </tr>




        <tbody class="fields  hide">


        <tr style=" height: 35px;" class="refund_submit_type hide">
            <td colspan="2" style="text-align: center">
                <span class="refund_submit_type select_manual operation_button unselectable " onclick="payment_submit_type_manual_selected()" >{t}Manual{/t}</span>
                <span class="refund_submit_type select_online operation_button unselectable "  onclick="payment_submit_type_online_selected()">{t}Online{/t}</span>

            </td>
        </tr>
        </tbody>

        <tbody class="lower_fields  hide">


        <tr style=" height: 5px;">
        </tr>
        <tr class="amount">
            <td>{t}Amount{/t}</td><td><input id="payment_refund_amount"></td>
        </tr>
        <tr class="reference">
            <td>{t}Reference{/t}</td><td><input id="payment_refund_reference"></td>
        </tr>
        <tr style=" height: 35px;">
            <td colspan="2" style="text-align: right" class="padding_right_20">
                <span class="save" onclick="save_refund(this)"><i class=" fa fa-cloud" aria-hidden="true"></i> {t}Save{/t}</span>
            </td>
        </tr>
        </tbody>
    </table>




</div>