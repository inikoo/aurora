{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 10 January 2019 at 15:39:43 MYT+0800, Kuala Lumpur, Malaysia
 Copyright (c) 2019, Inikoo

 Version 3
-->
*}
<div class="subject_profile">




    <div id="contact_data">
        <span class="title Shipper_Name">{$shipper->get('Name')}</span>
    </div>
    <div id="info">
        <div id="overviews">

            <table class="overview">

                <tr>
                    <td>{t}Consignments{/t}:</td>
                    <td class="aright"><span class="Stock_Value">{$shipper->get('Consignments')}</span></td>
                </tr>


            </table>





        </div>
    </div>
    <div style="clear:both">
    </div>
</div>



