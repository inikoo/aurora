{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 22 August 2018 at 15:04:22 GMT+8, Kuta, Bali, Indonesia
 Copyright (c) 2016, Inikoo

 Version 3
-->
*}

<style>
#item_problems_dialog tr._top td{
    border-bottom:1px solid #eee}

</style>

<div id="item_problems_dialog" class="hide" style="background-color: white;border: 1px solid #ccc;padding: 10px;position: absolute;z-index: 2000">
    <table class="item_problems" >
        <tr class="_top">
            <td colspan="2" class="item_problems_title">  </td>
            <td  class="aright"><i class="fa fa-window-close button" onclick="$('#item_problems_dialog').addClass('hide')"></i>  </td>
        </tr>
        <tr class="problem problem_price_increase"><td><i data-type="price_increase" onclick="select_item_problem(this)" class="far fa-square fa-fw padding_right_10 button checkbox"></i> <span class="button unselectable" onclick="select_item_problem_bis(this)">{t}Price Increase{/t}</span>  </td><td><input class="invisible margin_left_10" placeholder="{t}New unit price{/t}"></td></tr>
        <tr class="problem problem_low_stock"><td><i data-type="low_stock" onclick="select_item_problem(this)" class="far fa-square fa-fw padding_right_10 button checkbox"></i> <span class="button unselectable" onclick="select_item_problem_bis(this)" title="{t}Supplier can less than the ordered quantity{/t}">{t}Low stock{/t} </span> </td><td><input class="invisible margin_left_10" placeholder="{t}Cartons can order{/t}"></td></tr>
        <tr class="problem problem_long_wait"><td><i data-type="long_wait" onclick="select_item_problem(this)" class="far fa-square fa-fw padding_right_10 button checkbox"></i> <span class="button unselectable" onclick="select_item_problem_bis(this)" title="{t}Supplier can only delivery time to long{/t}">{t}Delay{/t} (Out of stock) </span></td><td><input class="invisible margin_left_10"  placeholder="{t}Delay (days){/t}"></td></tr>

        <tr class="problem problem_discontinued"><td><i data-type="discontinued" onclick="select_item_problem(this)" class="far fa-square fa-fw padding_right_10 button checkbox"  ></i> <span  class="button unselectable"onclick="select_item_problem_bis(this)" title="{t}Product no longer available{/t}">{t}Discontinued{/t}</span>  </td><td></td></tr>
        <tr class="problem problem_min_order"><td><i data-type="min_order" onclick="select_item_problem(this)" class="far fa-square fa-fw padding_right_10 button checkbox"></i> <span class="button unselectable"  onclick="select_item_problem_bis(this)" title="{t}Supplier minimum order not meet{/t}">{t}Minimum order{/t} </span> </td><td><input class="invisible margin_left_10" placeholder="{t}Minimum carton order{/t}"></td></tr>

        <tr class="problem problem_other"><td><i data-type="other" onclick="select_item_problem(this)" class="far fa-square fa-fw padding_right_10 button checkbox"></i> <span class="button unselectable" onclick="select_item_problem_bis(this)" title="{t}Other{/t}">{t}Other{/t}</span>  </td><td></td></tr>
        <tr>
            <td colspan="3"><textarea class="note" style="width: 400px" placeholder="{t}Notes{/t}"></textarea></td>
        </tr>
        <tr>
            <td colspan="3" class="aright"><span class="save" onclick="save_item_problems()"><i class="fa fa-cloud"></i> {t}Save{/t}</span>  </td>
        </tr>

    </table>
</div>