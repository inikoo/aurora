{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 2 March 2018 at 13:15:32 GMT+8, Kuala Lumpur, , Malaysia
 Copyright (c) 2018, Inikoo

 Version 3
-->
*}
<div class="subject_profile">
    <div style="float:left;width:500px;margin-right:40px">


        <div class="showcase">


            <table border=0>
                <tr class="top">
                    <td class="label">{t}Type{/t}</td>
                    <td class="aright"> {$list->get('Type')} <i class="{$list->get('Icon')}"></i></td>
                </tr>

                <tr>
                    <td class="label">{t}Created{/t}</td>
                    <td class="aright"> {$list->get('Creation Date')}</td>
                </tr>


            </table>
        </div>
        <div style="clear:both"></div>

    </div>
    <div style="float:left;width:600px">


        <div class="showcase">

            <ul>
                {foreach from=$list->get_formatted_conditions() item=condition}
                <li>{$condition}</li>

                {/foreach}
            </ul>

        </div>
        <div style="clear:both"></div>

    </div>

    <div style="clear:both"></div>
</div>


