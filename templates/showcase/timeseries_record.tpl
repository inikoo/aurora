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

            {if isset($data_1yb)}
                <table border=0>


                    <tr>
                        <td class="label">{t}Supplier deliveries{/t}</td>
                        <td class="aright"> {$timeseries_record->get('Supplier Deliveries')}</td>
                        <td class="aright"> {$data_1y['Supplier Deliveries']['delta_percentage']}</td>
                        <td class="aright discreet {if $data_1y['Supplier Deliveries']['diff']<0}error{/if}"> {if $data_1y['Supplier Deliveries']['diff']>0}+{/if}{$data_1y['Supplier Deliveries']['delta']}</td>

                    </tr>
                    <tr>
                        <td class="label">{t}Purchased amount{/t}</td>
                        <td class="aright"> {$timeseries_record->get('Purchased Amount',$account)}</td>
                        <td class="aright"> {$data_1y['Purchased Amount']['delta_percentage']}</td>
                        <td class="aright discreet {if $data_1y['Purchased Amount']['diff']<0}error{/if}"> {if $data_1y['Purchased Amount']['diff']>0}+{/if}{$data_1y['Purchased Amount']['delta']}</td>

                    </tr>
                    <tr>
                        <td class="label">{t}Sale deliveries{/t}</td>
                        <td class="aright"> {$timeseries_record->get('Deliveries')}</td>
                        <td class="aright"> {$data_1y['Deliveries']['delta_percentage']}</td>
                        <td class="aright discreet {if $data_1y['Deliveries']['diff']<0}error{/if}"> {if $data_1y['Deliveries']['diff']>0}+{/if}{$data_1y['Deliveries']['delta']}</td>

                    </tr>
                    <tr>
                        <td class="label">{t}Dispatched SKOs{/t}</td>
                        <td class="aright"> {$timeseries_record->get('Dispatched')}</td>
                        <td class="aright"> {$data_1y['Dispatched']['delta_percentage']}</td>
                        <td class="aright discreet {if $data_1y['Dispatched']['diff']<0}error{/if}"> {if $data_1y['Dispatched']['diff']>0}+{/if}{$data_1y['Dispatched']['delta']}</td>
                    </tr>
                    <tr>
                        <td class="label">{t}Sales amount{/t}</td>
                        <td class="aright"> {$timeseries_record->get('Sales',$account)}</td>
                        <td class="aright"> {$data_1y['Sales']['delta_percentage']}</td>
                        <td class="aright discreet {if $data_1y['Sales']['diff']<0}error{/if}"> {if $data_1y['Sales']['diff']>0}+{/if}{$data_1y['Sales']['delta']}</td>
                    </tr>

                </table>
            {else}
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
            {/if}
        </div>

        <div style="clear:both"></div>
    </div>

    <div style="clear:both"></div>
</div>


