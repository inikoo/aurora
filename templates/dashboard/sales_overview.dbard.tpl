<div id="dashboard_sales_overview" style="margin-top:20px;padding:0px" class="dashboard">
    <input type="hidden" id="order_overview_type" value="{$type}">
    <input type="hidden" id="order_overview_period" value="{$period}">
    <input type="hidden" id="order_overview_currency" value="{$currency}">
    <input type="hidden" id="order_overview_orders_view_type" value="{$orders_view_type}">
    <table class="dashboard_table"  style="width:100%">
        <tr class="main_title small_row">
            <td colspan="7">
                <div class="widget_types">
                    <div id="orders" onclick="change_sales_overview_type('orders')"
                         class="widget  left {if $type=='orders'}selected{/if}">
                        <i class="fa fa-shopping-cart"></i><span class="label"> {t}Orders{/t} </span>
                    </div>

                    <div id="delivery_notes" onclick="change_sales_overview_type('delivery_notes')"
                         class="widget  left {if $type=='delivery_notes'}selected{/if}">
                        <i class="fa fa-truck"></i> <span class="label">{t}Delivery Notes{/t} </span>
                    </div>
                    <div id="invoices" onclick="change_sales_overview_type('invoices')"
                         class="widget  left {if $type=='invoices'}selected{/if}">
                        <i class="fal fa-file-invoice-dollar"></i><span class="label"> {t}Invoices per store{/t} </span>
                    </div>
                    <div id="invoice_categories" onclick="change_sales_overview_type('invoice_categories')"
                         class=" widget  left {if $type=='invoice_categories'}selected{/if}">
                        <i class="fa fa-sitemap"></i><span class="label"> {t}Invoices' categories{/t} </span>
                    </div>
                    <div id="mailshots_sent" onclick="change_sales_overview_type('mailshots_sent')"
                         class=" widget  left {if $type=='mailshots_sent'}selected{/if}">
                        <i class="far fa-envelope"></i><span class="label"> {t}Mailshots sent{/t} </span>
                    </div>

                </div>

                <div id="sales_overview_orders_view_type_container" class="button  {if $type!='orders'}hide{/if} "
                     onclick="toggle_sales_overview_orders_view_type()"
                     style="font-size:90%;float:right;margin-left:20px;margin-right:10px">
                    <i class="fa fa-hashtag {if $orders_view_type=='numbers'}selected{/if}" aria-hidden="true"></i> | <i
                            class="fa fa-dollar-sign {if $orders_view_type=='amounts'}selected{/if}" aria-hidden="true"></i>


                </div>

                <div id="sales_overview_currency_container"
                     class="button  {if $type=='delivery_notes' or $type=='mailshots_sent' or ($type=='orders' and $orders_view_type=='numbers')  }hide{/if} "
                     onclick="toggle_sales_overview_currency()" style="float:right;margin-right:10px">
                    <i id="sales_overview_currency"
                       class="fa {if $currency=='store'}fa-toggle-on{else}fa-toggle-off{/if}"></i> {t}Store currency{/t}
                </div>


            </td>
        </tr>
        <tr class=" small_row   ">


            <td colspan="7">

                <div class="date_chooser  date_chooser_sales_overview  {if $type=='orders' }invisible{/if}">
                    <div  class="interval go_to_sales_report  {if $type=='orders' or  $type=='delivery_notes'}invisible{/if} "  data-type="{$type}" data-metadata='{ "parameters":{ "period":"{$period}", "currency":"{$currency}"}}' onclick="go_to_sales_report()" >
                        {t}Sales report{/t} <i class="far button fa-chart-line fa-fw"></i>
                    </div>
                    <div style="display:none" id="interval"
                         class="interval {if  $period=='interval'}selected{/if}">
                        <img src="/art/icons/mini-calendar_interval.png"/> {t}Interval{/t}
                    </div>
                    <div style="visibility:hidden" id="date" class="day {if  $period=='date'}selected{/if}">
                        <img src="/art/icons/mini-calendar.png"/> {t}Day{/t}
                    </div>
                    <div onclick="change_sales_overview_period('ytd')" period="ytd" id="ytd"
                         class="fixed_interval {if  $period=='ytd'}selected{/if}" title="{t}Year to day{/t}">
                        {t}YTD{/t}
                    </div>
                    <div onclick="change_sales_overview_period('mtd')" period="mtd" id="mtd"
                         class="fixed_interval {if  $period=='mtd'}selected{/if}" title="{t}Month to day{/t}">
                        {t}MTD{/t}
                    </div>
                    <div onclick="change_sales_overview_period('wtd')" period="wtd" id="wtd"
                         class="fixed_interval {if  $period=='wtd'}selected{/if}" title="{t}Week to day{/t}">
                        {t}WTD{/t}
                    </div>
                    <div onclick="change_sales_overview_period('today')" period="today" id="today"
                         class="fixed_interval {if  $period=='today'}selected{/if}" title="{t}Today{/t}">
                        {t}Today{/t}
                    </div>
                    <div onclick="change_sales_overview_period('yesterday')" period="yesterday" id="yesterday"
                         class="fixed_interval {if  $period=='yesterday'}selected{/if}" title="{t}Yesterday{/t}">
                        {t}Y'day{/t}
                    </div>
                    <div onclick="change_sales_overview_period('last_w')" period="last_w" id="last_w"
                         class="fixed_interval {if  $period=='last_w'}selected{/if}" title="{t}Last week{/t}">
                        {t}Last W{/t}
                    </div>
                    <div onclick="change_sales_overview_period('last_m')" period="last_m" id="last_m"
                         class="fixed_interval {if  $period=='last_m'}selected{/if}" title="{t}Last month{/t}">
                        {t}Last M{/t}
                    </div>
                    <div onclick="change_sales_overview_period('1w')" period="1w" id="1w"
                         class="fixed_interval {if  $period=='1w'}selected{/if}" title="{t}1 week{/t}">
                        {t}1W{/t}
                    </div>
                    <div onclick="change_sales_overview_period('1m')" period="1m" id="1m"
                         class="fixed_interval {if  $period=='1m'}selected{/if}" title="{t}1 month{/t}">
                        {t}1m{/t}
                    </div>
                    <div onclick="change_sales_overview_period('1q')" period="1q" id="1q"
                         class="fixed_interval {if  $period=='1q'}selected{/if}" title="{t}1 quarter{/t}">
                        {t}1q{/t}
                    </div>
                    <div onclick="change_sales_overview_period('1y')" period="1y" id="1y"
                         class="fixed_interval {if  $period=='1y'}selected{/if}" title="{t}1 year{/t}">
                        {t}1Y{/t}
                    </div>
                    <div onclick="change_sales_overview_period('all')" period="all" id="all"
                         class="fixed_interval {if  $period=='all'}selected{/if}">
                        {t}All{/t}
                    </div>
                </div>
            </td>
        </tr>
        <tr class="header ">
            <td class="label">{t}Store{/t}</td>


            <td class="refunds aright {if !($type=='invoices' or  $type=='invoice_categories'  )  }hide{/if}"
                title="{t}Refunds{/t}">{t}Refunds{/t}</td>
            <td class="refunds aright {if !($type=='invoices' or  $type=='invoice_categories'  )}hide{/if}"
                title="{t}Change compared with last year{/t}">&Delta; {t}1Y{/t}</td>
            <td class="delivery_notes aright {if $type!='delivery_notes'}hide{/if}"
                title="{t}Delivery notes{/t}">{t}Delivery notes{/t}</td>
            <td class="delivery_notes aright {if $type!='delivery_notes'}hide{/if}"
                title="{t}Change compared with last year{/t}">&Delta; {t}1Y{/t}</td>
            <td class="replacements aright {if $type!='delivery_notes'}hide{/if}"
                title="{t}Replacements{/t}">{t}Rpl{/t}</td>
            <td class="replacements aright {if $type!='delivery_notes'}hide{/if}"
                title="{t}Percentage of replacements{/t}">% {t}Rpl{/t}</td>
            <td class="last replacements aright {if $type!='delivery_notes'}hide{/if}"
                title="{t}Percentage of replacements same period last year{/t}">% {t}Rpl{/t} {t}1YB{/t}</td>
            <td class="invoices aright {if !($type=='invoices' or  $type=='invoice_categories')}hide{/if}">{t}Invoices{/t}</td>
            <td class="invoices aright {if !($type=='invoices' or  $type=='invoice_categories')}hide{/if}">&Delta; {t}1Y{/t}</td>
            <td class="sales aright {if !($type=='invoices' or  $type=='invoice_categories')}hide{/if}">{t}Sales{/t}</td>
            <td class="last sales aright {if !($type=='invoices' or  $type=='invoice_categories')}hide{/if}">&Delta; {t}1Y{/t}</td>

            <td class="orders aright {if $type!='orders' or $orders_view_type=='amounts'}hide{/if}">{t}In basket{/t}</td>
            <td class="orders_amount aright {if $type!='orders' or $orders_view_type=='numbers'}hide{/if}">{t}In basket{/t}</td>

            <td class="orders aright {if $type!='orders'  or $orders_view_type=='amounts'}hide{/if}">{t}In process{/t}</td>
            <td class="orders_amount aright {if $type!='orders' or $orders_view_type=='numbers'}hide{/if}">{t}In process{/t}</td>
            <td class="orders aright {if $type!='orders'  or $orders_view_type=='amounts'}hide{/if}">{t}In process (Paid){/t}</td>
            <td class="orders_amount aright {if $type!='orders' or $orders_view_type=='numbers'}hide{/if}">{t}In process (Paid){/t}</td>

            <td class="orders aright {if $type!='orders'  or $orders_view_type=='amounts'}hide{/if}">{t}In warehouse{/t}</td>
            <td class="orders_amount aright {if $type!='orders' or $orders_view_type=='numbers'}hide{/if}">{t}In warehouse{/t}</td>

            <td class="orders aright {if $type!='orders'  or $orders_view_type=='amounts'}hide{/if}">{t}Packed{/t}</td>
            <td class="orders_amount aright {if $type!='orders' or $orders_view_type=='numbers'}hide{/if}">{t}Packed{/t}</td>


            <td class="orders aright padding_right_10 {if $type!='orders'  or $orders_view_type=='amounts'}hide{/if}">{t}In dispatch area{/t}</td>
            <td class="orders_amount aright {if $type!='orders' or $orders_view_type=='numbers'}hide{/if}">{t}In dispatch area{/t}</td>

            <td class="mailshots_sent newsletter aright {if $type!='mailshots_sent'}hide{/if}">{t}Newsletters{/t}</td>
            <td class="mailshots_sent marketing aright {if $type!='mailshots_sent'}hide{/if}">{t}Marketing emails{/t}</td>
            <td class="mailshots_sent abandoned_cart aright {if $type!='mailshots_sent'}hide{/if}">{t}Abandoned carts{/t}</td>
            <td class="mailshots_sent total_mailshots aright {if $type!='mailshots_sent'}hide{/if}">{t}Total mailshots{/t}</td>
            <td class="mailshots_sent total_emails aright {if $type!='mailshots_sent'}hide{/if}">{t}Total emails{/t}</td>


        </tr>



        {foreach from=$sales_overview item=record}
            <tbody class="data">
            <tr class="{$record.class} small_row">
                <td class="label" ><span class=" {if isset($record.label.view) and $record.label.view!='' }link{/if}"
                            {if isset($record.label.view) and $record.label.view!='' }onclick="change_view('{$record.label.view}', {  parameters:{ period:'{$period}',elements_type:'type'} ,element:{ type:{ Refund:1,Invoice:1}}  {if $type=='invoice_categories'},tab:'category.invoices'{/if}  })" {/if}
                                         title="{if isset($record.label.title)}{$record.label.title}{else}{$record.label.label}{/if} ">{$record.label.label}</span>  {if !empty($record.label.representatives_link_label)}<span id="representatives_link_{$record.id}" title="{t}Sales representative reports{/t}" class=" margin_left_10">{$record.label.representatives_link_label}</span>{/if} </td>


                <td id="orders_overview_refunds_{$record.id}"
                    {if isset($record.refunds.view) and $record.label.view!='' }onclick="change_view('{$record.refunds.view}' , { parameters:{ period:'{$period}',elements_type:'type'} ,element:{ type:{ Refund:1,Invoice:''}} {if $type=='invoice_categories'},tab:'category.invoices'{/if} } )" {/if}
                    class="link refunds width_1500 aright {if !($type=='invoices' or  $type=='invoice_categories')}hide{/if}">{$record.refunds.value}</td>
                <td id="orders_overview_refunds_delta_{$record.id}"
                    class="refunds width_100 aright {if !($type=='invoices' or  $type=='invoice_categories')}hide{/if}"
                    title="{$record.refunds_1yb}">{if isset($record.refunds_delta)}{$record.refunds_delta}{/if}</td>
                <td id="orders_overview_delivery_notes_{$record.id}"
                    onclick="change_view('delivery_notes/{$record.id}',{ parameters: { period:'{$period}',elements_type:'type'},element:{ type:{ Order:1,Sample:1,Donation:1,Replacements:'',Shortages:''}} } )"
                    class="link delivery_notes width_150 aright {if $type!='delivery_notes'}hide{/if}">{if isset($record.delivery_notes)}{$record.delivery_notes}{/if}</td>
                <td id="orders_overview_delivery_notes_delta_{$record.id}"
                    class="delivery_notes width_100 aright {if $type!='delivery_notes'}hide{/if}"
                    title="{$record.delivery_notes_1yb}">{$record.delivery_notes_delta}</td>
                <td id="orders_overview_replacements_{$record.id}"
                    onclick="change_view('delivery_notes/{$record.id}',{ parameters: { period:'{$period}',elements_type:'type'},element:{ type:{ Order:'',Sample:'',Donation:'',Replacements:1,Shortages:1}} } )"
                    class="link  replacements width_150 aright {if $type!='delivery_notes'}hide{/if}">{if isset($record.replacements)}{$record.replacements}{/if}</td>
                <td id="orders_overview_replacements_percentage_{$record.id}"
                    class="replacements width_100 aright {if $type!='delivery_notes'}hide{/if}">{$record.replacements_percentage}</td>
                <td id="orders_overview_replacements_percentage_1yb_{$record.id}"
                    class="last replacements width_100 aright {if $type!='delivery_notes'}hide{/if}"
                    title="{$record.replacements_1yb}/{$record.delivery_notes_1yb}">{$record.replacements_percentage_1yb}</td>
                <td id="orders_overview_invoices_{$record.id}"
                    {if isset($record.invoices.view) and $record.invoices.view!='' }onclick="change_view('{$record.invoices.view}' , { parameters:{ period:'{$period}',elements_type:'type' } ,element:{ type:{ Refund:'',Invoice:1}} {if $type=='invoice_categories'},tab:'category.invoices'{/if} } )" {/if}
                    class="link invoices width_150 aright {if !($type=='invoices' or  $type=='invoice_categories')}hide{/if}">{$record.invoices.value}</td>
                <td id="orders_overview_invoices_delta_{$record.id}"
                    class="invoices width_100 aright {if !($type=='invoices' or  $type=='invoice_categories')}hide{/if}"
                    title="{$record.invoices_1yb}">{$record.invoices_delta}</td>
                <td id="orders_overview_sales_{$record.id}"
                    class="sales width_200 aright {if !($type=='invoices' or  $type=='invoice_categories')}hide{/if}"> {$record.sales}</td>
                <td id="orders_overview_sales_delta_{$record.id}"
                    class="last sales width_100 aright {if !($type=='invoices' or  $type=='invoice_categories')}hide{/if}"
                    title="{$record.sales_1yb}">{$record.sales_delta}</td>


                <td id="orders_overview_in_basket_{$record.id}"
                    onclick="change_view('orders/{$record.id}/dashboard/website' )"
                    class="link orders width_150 aright {if $type!='orders' or $orders_view_type=='amounts'}hide{/if}">{if isset($record.in_basket.value)}{$record.in_basket.value}{/if}</td>

                <td id="orders_overview_in_basket_amount_{$record.id}"
                    class="orders_amount width_150 aright {if $type!='orders'  or $orders_view_type=='numbers'}hide{/if}">{if isset($record.in_basket_amount.value)}{$record.in_basket_amount.value}{/if}</td>

                <td id="orders_overview_in_process_not_paid{$record.id}"
                    onclick="change_view('orders/{$record.id}/dashboard/submitted_not_paid' )"
                    class="link orders width_150 aright {if $type!='orders' or $orders_view_type=='amounts'}hide{/if}">{if isset($record.in_process_not_paid.value)}{$record.in_process_not_paid.value}{/if}</td>
                <td id="orders_overview_in_process_not_paid_amount_{$record.id}"
                    class="orders_amount width_150 aright {if $type!='orders'  or $orders_view_type=='numbers'}hide{/if}">{if isset($record.in_process_amount_not_paid.value)}{$record.in_process_amount_not_paid.value}{/if}</td>

                <td id="orders_overview_in_process_paid_{$record.id}"
                    onclick="change_view('orders/{$record.id}/dashboard/submitted' )"
                    class="link orders width_150 aright {if $type!='orders' or $orders_view_type=='amounts'}hide{/if}">{if isset($record.in_process_paid.value)}{$record.in_process_paid.value}{/if}</td>
                <td id="orders_overview_in_process_paid_amount_{$record.id}"
                    class="orders_amount width_150 aright {if $type!='orders' or $orders_view_type=='numbers'}hide{/if}">{if isset($record.in_process_amount_paid.value)}{$record.in_process_amount_paid.value}{/if}</td>

                <td id="orders_overview_in_warehouse_{$record.id}"
                    onclick="change_view('orders/{$record.id}/dashboard/in_warehouse' )"
                    class="link orders width_150 aright {if $type!='orders' or $orders_view_type=='amounts'}hide{/if}">{if isset($record.in_warehouse.value)}{$record.in_warehouse.value}{/if}</td>
                <td id="orders_overview_in_warehouse_amount_{$record.id}"
                    class="orders_amount width_150 aright {if $type!='orders' or $orders_view_type=='numbers'}hide{/if}">{if isset($record.in_warehouse_amount.value)}{$record.in_warehouse_amount.value}{/if}</td>
                <td id="orders_overview_packed_{$record.id}"
                    onclick="change_view('orders/{$record.id}/dashboard/packed_done' )"
                    class="link orders width_150 aright {if $type!='orders' or $orders_view_type=='amounts'}hide{/if}">{if isset($record.packed.value)}{$record.packed.value}{/if}</td>
                <td id="orders_overview_packed_amount_{$record.id}"
                    class="orders_amount width_150 aright {if $type!='orders' or $orders_view_type=='numbers'}hide{/if}">{if isset($record.packed_amount.value)}{$record.packed_amount.value}{/if}</td>


                <td id="orders_overview_in_dispatch_area_{$record.id}"
                    onclick="change_view('orders/{$record.id}/dashboard/approved' )"
                    class="link orders width_150 aright padding_right_10 {if $type!='orders' or $orders_view_type=='amounts'}hide{/if}">{if isset($record.in_dispatch_area.value)}{$record.in_dispatch_area.value}{/if}</td>
                <td id="orders_overview_in_dispatch_area_amount_{$record.id}"
                    class="orders_amount width_150 aright {if $type!='orders' or $orders_view_type=='numbers'}hide{/if}">{if isset($record.in_dispatch_area_amount.value)}{$record.in_dispatch_area_amount.value}{/if}</td>

                <td id="mailshots_sent_newsletters_{$record.id}"
                    class="mailshots_sent newsletters width_150 aright {if $type!='mailshots_sent' }hide{/if}">{if isset($record.newsletters.value)}{$record.newsletters.value}{/if}</td>
                <td id="mailshots_sent_marketing_{$record.id}"
                    class="mailshots_sent marketing width_150 aright {if $type!='mailshots_sent' }hide{/if}">{if isset($record.marketing.value)}{$record.marketing.value}{/if}</td>
                <td id="mailshots_sent_abandoned_carts_{$record.id}"
                    class="mailshots_sent abandoned_carts width_150 aright {if $type!='mailshots_sent' }hide{/if}">{if isset($record.abandoned_carts.value)}{$record.abandoned_carts.value}{/if}</td>
                <td id="mailshots_sent_total_mailshots_{$record.id}"
                    class="mailshots_sent total_mailshots width_150 aright {if $type!='mailshots_sent' }hide{/if}">{if isset($record.total_mailshots.value)}{$record.total_mailshots.value}{/if}</td>
                <td id="mailshots_sent_total_emails_{$record.id}"
                    class="mailshots_sent total_emails width_150 aright {if $type!='mailshots_sent' }hide{/if}">{if isset($record.total_emails.value)}{$record.total_emails.value}{/if}</td>
            </tr>
            </tbody>
        {/foreach}
    </table>
</div>

