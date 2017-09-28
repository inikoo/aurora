{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 28 September 2017 at 23:03:22 GMT+8, Kuala Lumpur, , Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}
<div class="subject_profile">
    <div style="float:left;width:600px">

        <h1 class="hide">{$timeseries_record->get('Code')}</h1>

        <div class="showcase">


            <table border=0>


                <tr>
                    <td class="label">{t}Supplier deliveries{/t}</td>
                    <td class="aright"> {$timeseries_record->get('Supplier Deliveries')}</td>
                </tr>
                <tr>
                    <td class="label">{t}Purchased amount{/t}</td>
                    <td class="aright"> {$timeseries_record->get('Purchased Amount',$account)}</td>
                </tr>
                <tr>
                    <td class="label">{t}Sale deliveries{/t}</td>
                    <td class="aright"> {$timeseries_record->get('Deliveries')}</td>
                </tr>
                <tr>
                    <td class="label">{t}Dispatched SKOs{/t}</td>
                    <td class="aright"> {$timeseries_record->get('Dispatched')}</td>
                </tr>
                <tr>
                    <td class="label">{t}Sales amount{/t}</td>
                    <td class="aright"> {$timeseries_record->get('Sales',$account)}</td>
                </tr>
               
            </table>
        </div>
       
        <div style="clear:both">
        </div>
    </div>

    <div style="clear:both">
    </div>
</div>


