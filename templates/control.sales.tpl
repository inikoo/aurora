{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 24 October 2018 at 15:09:05 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3
-->
*}


<div style="padding:0px 20px;border-bottom:1px solid #ccc" class="control_panel">
    <table style="float: left;width: 100%;padding: 0px">
        <tr>
            <td style="text-align: right"><span class="button unselectable" onclick="change_sales_report_currency(this,'currency')">
                    <i style="position: relative;top:.5px" class="fa {if $table_state['currency']=='account'}fa-toggle-off{else}fa-toggle-on{/if} fa-fw"></i>
                    {t}Store currency{/t}

            </td>
        </tr>
    </table>


    <div style="clear: both"></div>

</div>

<script>


    function change_sales_report_currency(element, key) {


        var icon = $(element).find('i')

        if (icon.hasClass('fa-toggle-on')) {


            icon.removeClass('fa-toggle-on').addClass('fa-toggle-off')
            var value = 'account'
            grid.columns.findWhere({ name: 'refunds_amount_oc'}).set("renderable", true)
            grid.columns.findWhere({ name: 'refunds_amount_oc_delta_1yb'}).set("renderable", true)
            grid.columns.findWhere({ name: 'revenue_oc'}).set("renderable", true)
            grid.columns.findWhere({ name: 'revenue_oc_delta_1yb'}).set("renderable", true)
            //grid.columns.findWhere({ name: 'profit_oc'}).set("renderable", true)
            //grid.columns.findWhere({ name: 'profit_oc_delta_1yb'}).set("renderable", true)

            grid.columns.findWhere({ name: 'refunds_amount'}).set("renderable", false)
            grid.columns.findWhere({ name: 'refunds_amount_delta_1yb'}).set("renderable", false)
            grid.columns.findWhere({ name: 'revenue'}).set("renderable", false)
            grid.columns.findWhere({ name: 'revenue_delta_1yb'}).set("renderable", false)
            //grid.columns.findWhere({ name: 'profit'}).set("renderable", false)
            //grid.columns.findWhere({ name: 'profit_delta_1yb'}).set("renderable", false)


        } else {
            icon.removeClass('fa-toggle-off').addClass('fa-toggle-on')

            var value = 'store'

            grid.columns.findWhere({ name: 'refunds_amount_oc'}).set("renderable", false)
            grid.columns.findWhere({ name: 'refunds_amount_oc_delta_1yb'}).set("renderable", false)
            grid.columns.findWhere({ name: 'revenue_oc'}).set("renderable", false)
            grid.columns.findWhere({ name: 'revenue_oc_delta_1yb'}).set("renderable", false)
            //grid.columns.findWhere({ name: 'profit_oc'}).set("renderable", false)
            //grid.columns.findWhere({ name: 'profit_oc_delta_1yb'}).set("renderable", false)

            grid.columns.findWhere({ name: 'refunds_amount'}).set("renderable", true)
            grid.columns.findWhere({ name: 'refunds_amount_delta_1yb'}).set("renderable", true)
            grid.columns.findWhere({ name: 'revenue'}).set("renderable", true)
            grid.columns.findWhere({ name: 'revenue_delta_1yb'}).set("renderable", true)
            //grid.columns.findWhere({ name: 'profit'}).set("renderable", true)
            //grid.columns.findWhere({ name: 'profit_delta_1yb'}).set("renderable", true)


        }

        console.log(value)

        var request = "/ar_state.php?tipo=update_table_state&table=sales&key=currency&value=" + value

        $.getJSON(request, function (data) {


            var request = "/ar_state.php?tipo=update_table_state&table=sales_invoice_category&key=currency&value=" + value

            $.getJSON(request, function (data) {

            });


        });


    }


</script>