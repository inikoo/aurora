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
    {counter start=-1 print=false assign="counter"}

    {foreach from=$results item="result" }
    <tr>



        <td style="padding: 5px;text-align: center" class="center"><img class="center" style="height: 80px;display: block;margin-left: auto; margin-right: auto;" src="{$result.image_mobile}"></td>
        <td style="padding-left: 5px;text-align: left"><b>{$result.code}</b>  {$result.name}

            {if $result.scope=='Product'}
            <div style="clear:both">

            {if $logged_in }

                {if $result.web_state=='Out of Stock'}



                    <div style="margin-top:10px;"><span style="padding:5px 10px" class="{if $result.out_of_stock_class=='launching_soon'}highlight-green color-white{else}highlight-red color-white{/if}">{$result.out_of_stock_label}</span></div>
                {elseif $result.web_state=='For Sale'}

                <div class="mobile_ordering {if $website->get('Website Type')=='EcomDS'}hide{/if}"  data-settings='{ "pid":{$result.key}}'>
                    <i onclick="save_item_qty_change(this)" class="ordering_button one_less fa fa-fw  fa-minus-circle color-red-dark"></i>
                    <input  type="number" min="0" value="{$result.ordered}" class="needsclick order_qty">
                    <i onclick="save_item_qty_change(this)" class="hide ordering_button save fa fa-save fa-fw color-blue-dark"></i>
                    <i onclick="save_item_qty_change(this)" class="ordering_button add_one fa fa-fw  fa-plus-circle color-green-dark"></i>
                </div>
{/if}

                <span style="float:left;margin-left: 20px;position: relative;top:10px">{$result.price}</span>

            {/if}

                <div  style="float:right;margin-right: 10px" class="mobile_ordering" >
                    {if $result.scope=='Product'}
                    {counter print=false assign="counter"}
                    <a href="{$result.url}"
                       data-analytics='{
                   "id":"{$result.code}",
                   "name":"{$result.name|escape:quotes}",
                   "category":"{$result.family_code}",
                    {if isset($result.raw_price)}"price":"{$result.raw_price}",{/if}
                    "position":{$counter}
                    }'
                       data-list="Search"
                       onclick="go_product(this); return !ga.loaded;"
                            {/if}
                       style="color:#555"> <i  class="fa fa-reply fa-flip-horizontal"></i></a>
                </div>
            </div>

            {elseif $result.scope=='Category'}
                <div style="clear:both" class="single_line_height">
                    <div  style="float:right;margin-right: 10px" class="mobile_ordering" >
                        <a href="{$result.url}" style="color:#555">  <i  class="fa fa-reply fa-flip-horizontal"></i></a>
                    </div>
                    <p style="margin: 0px;margin-right:40px;">{$result.description|truncate:60:"...":true}</p>


                </div>
            {/if}

        </td>



    </tr>

{/foreach}
</table>
