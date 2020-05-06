{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  06 May 2020  13:29::21  +0800, Kuala Lumpur Malysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}

<style>


    .basket {
        font-size: 16px;
    }

    .order_header {
        padding: 0px 30px
    }

    .order_header .totals {
        padding-right: 20px;
        text-align: right;

    }

    .totals table {
        width: initial;
        float: right;
    }

    .totals table td {
        padding: 6px 20px 6px 50px;;
        border-bottom: 1px solid #ccc;
    }

    .totals table tr.total {
        font-weight: 800;
    }

    .totals table tr:first-child td {
        border-top: 1px solid #c5c5c5;
    }


    .totals table tr:last-child td {
        border-bottom: 2px solid #bbb;
    }

    .order table {
        margin: 40px 0px 30px 0px;
    }

    .order table td {
        border-top: 1px solid #ccc;
        padding: 4px 3px;
    }

    .order table tr:last-child td {
        border-bottom: 1px solid #c5c5c5;
    }

    @media only screen  and (max-width: 1240px) {

        #basket_continue_shopping {
            display: none
        }
    }

</style>


{if isset($data.top_margin)}{assign "top_margin" $data.top_margin}{else}{assign "top_margin" "20"}{/if}
{if isset($data.bottom_margin)}{assign "bottom_margin" $data.bottom_margin}{else}{assign "bottom_margin" "20"}{/if}


<div id="block_{$key}" data-block_key="{$key}" block="{$data.type}" class="{$data.type} _block {if !$data.show}hide{/if}" top_margin="{$top_margin}" bottom_margin="{$bottom_margin}"
     style="padding-top:{$top_margin}px;padding-bottom:{$bottom_margin}px">




    <table class="table">


        <thead>

        <tr>
            <th id="_items_code" class="text-left website_localized_label" contenteditable="true">
                {if !empty($labels._items_code)}{$labels._items_code}{else}{t}Code{/t}{/if}
            </th>
            <th id="_items_description" class="text-left website_localized_label" contenteditable="true">
                {if !empty($labels._items_description) }{$labels._items_description}{else}{t}Description{/t}{/if}
            </th>
            <th id="_items_quantity" class="text-right website_localized_label" contenteditable="true">
                {if !empty($labels._items_quantity)}{$labels._items_quantity}{else}{t}Quantity{/t}{/if}
            </th>
            <th id="_items_amount_net" class="text-right website_localized_label" contenteditable="true">
                {if !empty($labels._items_amount_net)}{$labels._items_amount_net}{else}{t}Amount net{/t}{/if}
            </th>


        </tr>
        </thead>
        <tbody>
        <tr>
            <td>ABB1</td>
            <td>Doe Spanner</td>
            <td class="text-right">2</td>
            <td class="text-right">£10.00</td>
        </tr>
        <tr>
            <td>HHT-04</td>
            <td>Moe Screwdriver</td>
            <td class="text-right">3</td>
            <td class="text-right">£6.00</td>
        </tr>
        <tr>
            <td>LLX-10a</td>
            <td>Dooley Hammer</td>
            <td class="text-right">1</td>
            <td class="text-right">£1.99</td>
        </tr>
        </tbody>
    </table>




</div>

