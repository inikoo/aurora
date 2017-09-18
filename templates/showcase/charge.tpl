{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 14 September 2017 at 15:19:13 GMT+8, Kuala Lumpur, , Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}
<div class="subject_profile">
    <div style="float:left;width:600px">

        <h1 class="Charge_Description">{$charge->get('Description')}</h1>

        <div class="showcase">


            <table border=0>


                <tr>
                    <td class="label">{t}Customers{/t}</td>
                    <td class="aright"> {$charge->get('Customers')}</td>
                </tr>
                <tr>
                    <td class="label">{t}Orders{/t}</td>
                    <td class="aright"> {$charge->get('Orders')}</td>
                </tr>
                <tr>
                    <td class="label">{t}Amount{/t}</td>
                    <td class="aright"> {$charge->get('Amount')}</td>
                </tr>
            </table>
        </div>
        <div style="clear:both">
        </div>
        <div style="clear:both">
        </div>
    </div>

    <div style="clear:both">
    </div>
</div>


