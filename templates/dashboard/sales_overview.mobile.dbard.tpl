<input type="hidden" id="order_overview_type" value="{$type}"><input type="hidden" id="order_overview_subtype" value="{$subtype}"><input type="hidden" id="order_overview_period" value="{$period}"><input type="hidden"
                                                                                                                                                                                                           id="order_overview_currency"
                                                                                                                                                                                                           value="{$currency}">
<input type="hidden" id="order_overview_orders_view_type" value="{$orders_view_type}">


<div style="padding:10px 5px">

    <div style="float:left">

        <button id="report_type" class="mdl-button mdl-js-button mdl-button--icon">
            <i class="material-icons">more_vert</i>
        </button>
        <ul class="mdl-menu  mdl-js-menu mdl-js-ripple-effect" for="report_type">
            <li class="mdl-menu__item" onclick="change_sales_overview_type('invoices','sales')">{t}Sales (Stores){/t}</li>
            <li class="mdl-menu__item" onclick="change_sales_overview_type('invoice_categories','sales')">{t}Sales (Categories){/t}</li>
            <li class="mdl-menu__item" onclick="change_sales_overview_type('invoices','invoices')">{t}Invoices (Stores){/t}</li>
            <li class="mdl-menu__item" onclick="change_sales_overview_type('invoice_categories','invoices')">{t}Invoices (Categories){/t}</li>


        </ul>
        <span id="$report_title">{$report_title}</span>
    </div>

    <div style="float:right">
        <span id="period_label">{$interval_label}</span>
        <button id="menu-speed" class="mdl-button mdl-js-button mdl-button--icon">
            <i class="material-icons">more_vert</i>
        </button>
        <ul class="mdl-menu  mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect" for="menu-speed">
            <li class="mdl-menu__item" onclick="change_sales_overview_period('all')">{t}All{/t}</li>
            <li class="mdl-menu__item" onclick="change_sales_overview_period('ytd')">{t}Year-to-date{/t}</li>
            <li class="mdl-menu__item" onclick="change_sales_overview_period('mtd')">{t}Month-to-date{/t}</li>
            <li class="mdl-menu__item" onclick="change_sales_overview_period('wtd')">{t}Week-to-date{/t}</li>
            <li class="mdl-menu__item" onclick="change_sales_overview_period('today')">{t}Today{/t}</li>
            <li class="mdl-menu__item" onclick="change_sales_overview_period('yesterday')">{t}Yesterday{/t}</li>
            <li class="mdl-menu__item" onclick="change_sales_overview_period('lastm')">{t}Last month{/t}</li>
            <li class="mdl-menu__item" onclick="change_sales_overview_period('lastw')">{t}Last week{/t}</li>
            <li class="mdl-menu__item" onclick="change_sales_overview_period('1y')">{t}1 Year{/t}</li>

        </ul>

    </div>


</div>
<table class=" mdl-data-table mdl-js-data-table  mdl-shadow--2dp" style="width:100%">
    <thead>
    <tr>
        <th class="mdl-data-table__cell--non-numeric">{t}Store{/t}</th>
        <th class="invoices {if  $subtype!='invoices'}hide{/if}">{t}Invoices{/t} </th>
        <th class="invoices {if  $subtype!='invoices'}hide{/if}">&Delta;{t}1y{/t}</th>
        <th class="sales  {if  $subtype!='sales'}hide{/if}">{t}Sales{/t} </th>
        <th class="sales {if  $subtype!='sales'}hide{/if}">&Delta;{t}1y{/t}</th>
    </tr>
    </thead>
    <tbody>

    {foreach from=$sales_overview item=record}
        <tr class="{$record.class} small_row">
            <td class="mdl-data-table__cell--non-numeric {if isset($record.label.view) and $record.label.view!='' }link{/if}"
                {if isset($record.label.view) and $record.label.view!='' }onclick="change_view('{$record.label.view}')" {/if}
                title="{if isset($record.label.title)}{$record.label.title}{else}{$record.label.label}{/if}  ">{$record.label.short_label}</td>
            <td id="orders_overview_invoices_{$record.id}" class="invoices width_200  {if ( !($type=='invoices' or  $type=='invoice_categories')) or ( $subtype!='invoices')  }hide{/if}"> {$record.invoices.value}</td>
            <td id="orders_overview_invoices_delta_{$record.id}" class="invoices width_100  {if ( !($type=='invoices' or  $type=='invoice_categories') ) or  ( $subtype!='invoices')  }hide{/if}" title="{$record.invoices_1yb}">{$record.invoices_delta}</td>

            <td id="orders_overview_sales_{$record.id}" class="sales width_200  {if ( !($type=='invoices' or  $type=='invoice_categories') ) or  ( $subtype!='sales')  }hide{/if}"> {$record.sales}</td>
            <td id="orders_overview_sales_delta_{$record.id}" class=" sales width_100  {if (!($type=='invoices' or  $type=='invoice_categories') ) or  ( $subtype!='sales') }hide{/if}" title="{$record.sales_1yb}">{$record.sales_delta}</td>


        </tr>
    {/foreach}


    </tbody>
</table>


<script>




    $("#content").on("swipeleft", function () {

        var next_period= {
            'all':'ytd','ytd':'mtd','mtd':'wtd','wtd':'totay','today':'yesterday','yesterday':'lastm','lastm':'lastw','lastw':'1y','1y':'all'}
                change_sales_overview_period(next_period[$('#order_overview_period').val()])

    });
    $("#content").on("swiperight", function () {
        var previous_period= {
            'all':'1y','ytd':'all','mtd':'ytd','wtd':'mtd','today':'mtd','yesterday':'today','lastm':'yesterday','lastw':'lastm','1y':'lastw'}
                change_sales_overview_period(previous_period[$('#order_overview_period').val()])
    });


    function change_sales_overview_type(type, subtype) {

        console.log(type, subtype)

        $('#order_overview_type').val(type)
        $('#order_overview_subtype').val(subtype)

        $('.replacements ,.delivery_notes,.orders ,.orders_amount,.category,.store,.sales,.invoices').addClass('hide')



        if (type == 'invoices') {


            $('.store').removeClass('hide')


            if (subtype == 'sales') {
                $('.sales').removeClass('hide')
            } else if (subtype == 'invoices') {
                $('.invoices').removeClass('hide')
            }


        } else if (type == 'invoice_categories') {

            $('.category').removeClass('hide')

            if (subtype == 'sales') {
                $('.sales').removeClass('hide')
            } else if (subtype == 'invoices') {
                $('.invoices').removeClass('hide')
            }


        } else if (type == 'delivery_notes') {
            $('.date_chooser').removeClass('invisible')

            $('.category').addClass('hide')
            $('.store').removeClass('hide')
            $('.refunds,.invoices,.sales,.orders ,.orders_amount').addClass('hide')
            $('.replacements ,.delivery_notes').removeClass('hide')

        } else if (type == 'orders') {

            $('.category').addClass('hide')
            $('.store').removeClass('hide')

            $('.refunds,.invoices,.sales').addClass('hide')
            $('.replacements ,.delivery_notes,.replacements ,.delivery_notes,.orders ,.orders_amount,#sales_overview_currency_container').addClass('hide')

            if ($('#order_overview_orders_view_type').val() == 'numbers') {
                $('.orders ').removeClass('hide')
            } else {
                $('.orders_amount,#sales_overview_currency_container').removeClass('hide')

            }


            $('#sales_overview_orders_view_type_container').removeClass('hide')
            // $('#sales_overview_currency_container').removeClass('hide')

        }

        get_order_overview_data($('#order_overview_type').val(), $('#order_overview_subtype').val(), $('#order_overview_period').val(), $('#order_overview_currency').val(), $('#order_overview_orders_view_type').val())


    }


    function change_sales_overview_period(period) {


        $('#order_overview_period').val(period)


        get_order_overview_data($('#order_overview_type').val(), $('#order_overview_subtype').val(), $('#order_overview_period').val(), $('#order_overview_currency').val(), $('#order_overview_orders_view_type').val())


    }


    function get_order_overview_data(type, subtype, period, currency, orders_view_type) {

        var request = "/ar_dashboard.php?tipo=sales_overview&type=" + type + "&subtype=" + subtype + "&period=" + period + '&currency=' + currency + '&orders_view_type=' + orders_view_type
        console.log(request)
        $.getJSON(request, function (r) {

            console.log(r.data)
            $('#period_label').html(r.period_label)

            $('#order_overview_type').val(type)

            for (var record in r.data) {

                $('#' + record).html(r.data[record].value)
                /*
                 if (r.data[record].request != undefined) {

                 if (r.data[record].special_type != undefined) {
                 if (r.data[record].special_type == 'invoice') {
                 $('#' + record).attr("onclick", "change_view('" + r.data[record].request + "',{ parameters:{ period:'" + period + "',elements_type:'type' } ,element:{ type:{ Refund:'',Invoice:1}} })")
                 } else {
                 $('#' + record).attr("onclick", "change_view('" + r.data[record].request + "',{ parameters:{ period:'" + period + "',elements_type:'type' } ,element:{ type:{ Refund:1,Invoice:''}} })")
                 }
                 } else {
                 $('#' + record).attr("onclick", "change_view('" + r.data[record].request + "',{ parameters:{ period:'" + period + "' }})")
                 }
                 }
                 */


                if (r.data[record].title != undefined) {
                    $('#' + record).attr('title', r.data[record].title)

                }


            }


        });

    }


</script>
