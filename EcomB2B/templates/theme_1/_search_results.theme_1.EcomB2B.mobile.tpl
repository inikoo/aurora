{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 4 September 2017 at 22:00:30 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}


<table>
    <thead>
    <tr >
        <th colspan="2" class="text-left padding_left_10">{t}Results{/t}</th>

    </tr>
    </thead>
    <tbody>
{foreach from=$results item="result" }
    <tr>


        <td style="padding: 5px;text-align: center" class="center"><img class="center" style="height: 60px" src="{$result.image}&r=60x60"></td>
        <td style="padding-left: 5px;text-align: left"><b>{$result.code}</b>  {$result.name}

            {if $result.scope=='Product'}
            <div style="clear:both">

            {if $logged_in}


                <div class="mobile_ordering"  data-settings='{ "pid":{$result.key}}'>
                    <i onclick="save_item_qty_change(this)" class="ordering_button one_less fa fa-fw  fa-minus-circle color-red-dark"></i>
                    <input  type="number" min="0" value="{$result.ordered}" class="needsclick order_qty">
                    <i onclick="save_item_qty_change(this)" style="display:none" class="ordering_button save fa fa-fw fa-floppy-o color-blue-dark"></i>
                    <i onclick="save_item_qty_change(this)" class="ordering_button add_one fa fa-fw  fa-plus-circle color-green-dark"></i>
                </div>


                <span style="float:left;margin-left: 20px;position: relative;top:10px">{$result.price}</span>

            {/if}

                <div  style="float:right;margin-right: 10px" class="mobile_ordering" >
                    <a href="{$result.url}" style="color:#555"> <i  class="fa fa-reply fa-flip-horizontal"></i></a>
                </div>
            </div>

            {elseif $result.scope=='Category'}
                <div style="clear:both" class="single_line_height">
                    <div  style="float:right;margin-right: 10px" class="mobile_ordering" >
                        <a href="{$result.url}" style="color:#555"> <i  class="fa fa-reply fa-flip-horizontal"></i></a>
                    </div>
                    <p style="margin: 0px">{$result.description|truncate:60:"...":true}</p>


                </div>
            {/if}

        </td>



    </tr>

{/foreach}
</table>
