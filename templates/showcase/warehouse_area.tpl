{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 13 November 2018 at 18:54:02 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}

<div class="subject_profile">
    <div id="contact_data">
        <span class="Warehouse_Area_Name" title="{t}Warehouse area name{/t}">{$warehouse_area->get('Name')}</span>

    </div>
    <div id="info">


        <div id="overviews">

            <table class="overview">

                <tr>
                    <td>{t}Stock value{/t}:</td>
                    <td class="aright"><span class="Warehouse_Area_Stock_Value">{$warehouse_area->get('Stock Value')}</span></td>
                </tr>
                <tr>
                    <td>{t}Locations{/t}:</td>
                    <td class="aright"><span class="Warehouse_Area_Locations">{$warehouse_area->get('Number Locations')}</span></td>
                </tr>
                <tr>
                    <td>{t}Parts{/t}:</td>
                    <td class="aright"><span class="Warehouse_Area_Partse">{$warehouse_area->get('Distinct Parts')}</span></td>
                </tr>


            </table>





        </div>
    </div>
    <div style="clear:both">
    </div>
</div>


