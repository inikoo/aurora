{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 2:43 pm Thursday, 2 July 2020 (MYT) Kuala Lumpur, Malaysia
 Copyright (c) 2020, Inikoo

 Version 3
-->
*}


<div style="padding:0px 20px;border-bottom:1px solid #ccc" class="control_panel">
    <table style="float: left;width: 100%;padding: 0px">
        <tr>
            <td style="text-align: right"><span class="button unselectable" onclick="toggle_show_production_parts_in_inventory(this)">
                    <i style="position: relative;top:.5px" class="fa {if $show_production=='Yes'}fa-toggle-on{else}fa-toggle-off{/if} fa-fw"></i>
                    {t}Include production parts{/t}

            </td>
        </tr>
    </table>


    <div style="clear: both"></div>

</div>

