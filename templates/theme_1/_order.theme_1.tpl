{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 29 May 2017 at 17:51:00 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}


<table class="table">


    <thead>
    <tr class="operations">
        <td colspan=2 class="text-left"><i class="hide fa fa-upload"></i></td>

        <td colspan=2 class="text-right">


            <div style="padding-bottom: 3px">
                <span id="_add_product_to_basket" contenteditable="true"
                      class="discreet website_localized_label">{if !empty($labels._add_product_to_basket)}{$labels._add_product_to_basket}{else}{t}Add product{/t}{/if}</span>
                <span id="_add_product_to_basket_code_placeholder" class="very_discreet website_localized_label" style="margin-right:2px;margin-left: 5px;border: 1px solid #ccc;padding: 5px 10px" contenteditable="true">
                    {if !empty($labels._add_product_to_basket_code_placeholder)}{$labels._add_product_to_basket_code_placeholder}{else}{t}Product code{/t}{/if}
                </span>
                <span id="_add_product_to_basket_qty_placeholder" class="very_discreet website_localized_label" style="margin-right:2px;margin-left: 5px;border: 1px solid #ccc;padding: 5px 10px" contenteditable="true">
                {if !empty($labels._add_product_to_basket_qty_placeholder)}{$labels._add_product_to_basket_qty_placeholder}{else}{t}Quantity{/t}{/if}
                </span>


                <i class="add_item_save save fa fa-cloud super_discreet"></i>

            </div>

        </td>
    </tr>
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

