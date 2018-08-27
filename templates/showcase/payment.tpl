{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 28 August 2018 at 00:17:08 GMT+8, Kuala Lumpur, Malaysis
 Copyright (c) 2018, Inikoo

 Version 3
-->
*}
<div class="subject_profile">
    <div id="contact_data"></div>
    <div style="width: 400px;float: left">
        <div id="overviews">

            <table border="0" class="overview">
                <tr>
                    <td>{t}Status{/t}:</td>
                    <td class="aright strong"><span class="Payment_Transaction_status">{$payment->get('Transaction Status')}</span></td>
                </tr>
                <tr>
                    <td>{t}Amount{/t}:</td>
                    <td class="aright strong"><span class="Payment_Transaction_Amount">{$payment->get('Transaction Amount')}</span></td>
                </tr>


            </table>





        </div>
    </div>


    <div style="clear:both">
    </div>
</div>




