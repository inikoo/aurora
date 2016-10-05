<div id="dashboard_sales_overview" style="margin-top:20px;padding:0px" class="dashboard">
	<input type="hidden" id="order_overview_type" value="{$type}"> 
	<input type="hidden" id="order_overview_period" value="{$period}"> 
	<input type="hidden" id="order_overview_currency" value="{$currency}"> 
	<table border="0" style="width:100%">
		<tr class="main_title small_row">
			<td colspan="9"> 
			<div class="widget_types">
				<div id="orders" onclick="change_sales_overview_type('orders')" class="hide widget  left {if $type=='orders'}selected{/if}">
					<i class="fa fa-shopping-cart"></i><span class="label"> {t}Orders{/t} </span> 
				</div>
				<div id="invoices" onclick="change_sales_overview_type('invoices')" class="widget  left {if $type=='invoices'}selected{/if}">
					<i class="fa fa-usd"></i><span class="label"> {t}Invoices{/t} </span> 
				</div>
				<div id="invoice_categories" onclick="change_sales_overview_type('invoice_categories')" class=" widget  left {if $type=='invoice_categories'}selected{/if}">
					<i class="fa fa-usd"></i><span class="label"> {t}Invoices' categories{/t} </span> 
				</div>
				<div id="delivery_notes" onclick="change_sales_overview_type('delivery_notes')" class="widget  left {if $type=='delivery_notes'}selected{/if}">
					<i class="fa fa-truck"></i> <span class="label">{t}Delivery Notes{/t} </span> 
				</div>
			</div>
			<div id="sales_overview_currency_container" class="button  {if $type=='delivery_notes'}hide{/if} " onclick="toggle_sales_overview_currency()" style="float:right;margin-right:10px">
				<i id="sales_overview_currency" class="fa {if $currency=='store'}fa-toggle-on{else}fa-toggle-off{/if}"></i> {t}Store currency{/t} 
			</div>
			</td>
		</tr>
		<tr class="small_row">
			<td colspan="7"> 
			<div class="date_chooser">
				<div style="visibility:hidden" id="interval" class="interval {if  $period=='interval'}selected{/if}">
					<img src="/art/icons/mini-calendar_interval.png" /> {t}Interval{/t}
				</div>
				<div style="visibility:hidden" id="date" class="day {if  $period=='date'}selected{/if}">
					<img src="/art/icons/mini-calendar.png" /> {t}Day{/t}
				</div>
				<div onclick="change_sales_overview_period('ytd')" period="ytd" id="ytd" class="fixed_interval {if  $period=='ytd'}selected{/if}" title="{t}Year to day{/t}">
					{t}YTD{/t}
				</div>
				<div onclick="change_sales_overview_period('mtd')" period="mtd" id="mtd" class="fixed_interval {if  $period=='mtd'}selected{/if}" title="{t}Month to day{/t}">
					{t}MTD{/t}
				</div>
				<div onclick="change_sales_overview_period('wtd')" period="wtd" id="wtd" class="fixed_interval {if  $period=='wtd'}selected{/if}" title="{t}Week to day{/t}">
					{t}WTD{/t}
				</div>
				<div onclick="change_sales_overview_period('today')" period="today" id="today" class="fixed_interval {if  $period=='today'}selected{/if}" title="{t}Today{/t}">
					{t}Today{/t}
				</div>
				<div onclick="change_sales_overview_period('yesterday')" period="yesterday" id="yesterday" class="fixed_interval {if  $period=='yesterday'}selected{/if}" title="{t}Yesterday{/t}">
					{t}Y'day{/t}
				</div>
				<div onclick="change_sales_overview_period('last_w')" period="last_w" id="last_w" class="fixed_interval {if  $period=='last_w'}selected{/if}" title="{t}Last week{/t}">
					{t}Last W{/t}
				</div>
				<div onclick="change_sales_overview_period('last_m')" period="last_m" id="last_m" class="fixed_interval {if  $period=='last_m'}selected{/if}" title="{t}Last month{/t}">
					{t}Last M{/t}
				</div>
				<div onclick="change_sales_overview_period('1w')" period="1w" id="1w" class="fixed_interval {if  $period=='1w'}selected{/if}" title="{t}1 week{/t}">
					{t}1W{/t}
				</div>
				<div onclick="change_sales_overview_period('1m')" period="1m" id="1m" class="fixed_interval {if  $period=='1m'}selected{/if}" title="{t}1 month{/t}">
					{t}1m{/t}
				</div>
				<div onclick="change_sales_overview_period('1q')" period="1q" id="1q" class="fixed_interval {if  $period=='1q'}selected{/if}" title="{t}1 quarter{/t}">
					{t}1q{/t}
				</div>
				<div onclick="change_sales_overview_period('1y')" period="1y" id="1y" class="fixed_interval {if  $period=='1y'}selected{/if}" title="{t}1 year{/t}">
					{t}1Y{/t}
				</div>
				<div onclick="change_sales_overview_period('all')" period="all" id="all" class="fixed_interval {if  $period=='all'}selected{/if}">
					{t}All{/t}
				</div>
			</div>
			</td>
		</tr>
		<tr class="header ">
			<td class="label">{t}Store{/t}</td>
			<td class="refunds aright {if !($type=='invoices' or  $type=='invoice_categories')  }hide{/if}" title="{t}Refunds{/t}">{t}Refunds{/t}</td>
			<td class="refunds aright {if !($type=='invoices' or  $type=='invoice_categories')}hide{/if}" title="{t}Change compared with last year{/t}">&Delta; {t}1Y{/t}</td>
			<td class="delivery_notes aright {if $type!='delivery_notes'}hide{/if}" title="{t}Delivery notes{/t}">{t}Delivery notes{/t}</td>
			<td class="delivery_notes aright {if $type!='delivery_notes'}hide{/if}" title="{t}Change compared with last year{/t}">&Delta; {t}1Y{/t}</td>
			<td class="replacements aright {if $type!='delivery_notes'}hide{/if}" title="{t}Replacements{/t}">{t}Rpl{/t}</td>
			<td class="replacements aright {if $type!='delivery_notes'}hide{/if}" title="{t}Percentage of replacements{/t}">% {t}Rpl{/t}</td>
			<td class="last replacements aright {if $type!='delivery_notes'}hide{/if}" title="{t}Percentage of replacements same period last year{/t}">% {t}Rpl{/t} {t}1YB{/t}</td>
			<td class="invoices aright {if !($type=='invoices' or  $type=='invoice_categories')}hide{/if}">{t}Invoices{/t}</td>
			<td class="invoices aright {if !($type=='invoices' or  $type=='invoice_categories')}hide{/if}">&Delta; {t}1Y{/t}</td>
			<td class="sales aright {if !($type=='invoices' or  $type=='invoice_categories')}hide{/if}">{t}Sales{/t}</td>
			<td class="last sales aright {if !($type=='invoices' or  $type=='invoice_categories')}hide{/if}">&Delta; {t}1Y{/t}</td>
		</tr>
		{foreach from=$sales_overview item=record} 
		<tbody class="data">
		<tr class="{$record.class} small_row">
			<td class="label {if isset($record.label.view) and $record.label.view!='' }link{/if}" {if isset($record.label.view) and $record.label.view!='' }onclick="change_view('{$record.label.view}')" {/if} title="{if isset($record.label.title)}{$record.label.title}{else}{$record.label.label}{/if}  ">{$record.label.label}</td>
			<td id="orders_overview_refunds_{$record.id}" {if isset($record.refunds.view) and $record.label.view!='' }onclick="change_view('{$record.refunds.view}' , { parameters:{ period:'{$period}',elements_type:'type' } ,element:{ type:{ Refund:1,Invoice:''}} } )" {/if} class="link refunds width_1500 aright {if !($type=='invoices' or  $type=='invoice_categories')}hide{/if}">{$record.refunds.value}</td>
			<td id="orders_overview_refunds_delta_{$record.id}" class="refunds width_100 aright {if !($type=='invoices' or  $type=='invoice_categories')}hide{/if}" title="{$record.refunds_1yb}">{$record.refunds_delta}</td>
			<td id="orders_overview_delivery_notes_{$record.id}" onclick="change_view('delivery_notes/{$record.id}',{ parameters: { period:'{$period}',elements_type:'type'},element:{ type:{ Order:1,Sample:1,Donation:1,Replacements:'',Shortages:''}} } )" class="link delivery_notes width_150 aright {if $type!='delivery_notes'}hide{/if}">{$record.delivery_notes}</td>
			<td id="orders_overview_delivery_notes_delta_{$record.id}" class="delivery_notes width_100 aright {if $type!='delivery_notes'}hide{/if}" title="{$record.delivery_notes_1yb}">{$record.delivery_notes_delta}</td>
			<td id="orders_overview_replacements_{$record.id}" onclick="change_view('delivery_notes/{$record.id}',{ parameters: { period:'{$period}',elements_type:'type'},element:{ type:{ Order:'',Sample:'',Donation:'',Replacements:1,Shortages:1}} } )" class="link  replacements width_150 aright {if $type!='delivery_notes'}hide{/if}">{$record.replacements}</td>
			<td id="orders_overview_replacements_percentage_{$record.id}" class="replacements width_100 aright {if $type!='delivery_notes'}hide{/if}">{$record.replacements_percentage}</td>
			<td id="orders_overview_replacements_percentage_1yb_{$record.id}" class="last replacements width_100 aright {if $type!='delivery_notes'}hide{/if}" title="{$record.replacements_1yb}/{$record.delivery_notes_1yb}">{$record.replacements_percentage_1yb}</td>
			<td id="orders_overview_invoices_{$record.id}" {if isset($record.invoices.view) and $record.invoices.view!='' }onclick="change_view('{$record.invoices.view}' , { parameters:{ period:'{$period}',elements_type:'type' } ,element:{ type:{ Refund:'',Invoice:1}} } )" {/if} class="link invoices width_150 aright {if !($type=='invoices' or  $type=='invoice_categories')}hide{/if}">{$record.invoices.value}</td>
			<td id="orders_overview_invoices_delta_{$record.id}" class="invoices width_100 aright {if !($type=='invoices' or  $type=='invoice_categories')}hide{/if}" title="{$record.invoices_1yb}">{$record.invoices_delta}</td>
			<td id="orders_overview_sales_{$record.id}" class="sales width_200 aright {if !($type=='invoices' or  $type=='invoice_categories')}hide{/if}"> {$record.sales}</td>
			<td id="orders_overview_sales_delta_{$record.id}" class="last sales width_100 aright {if !($type=='invoices' or  $type=='invoice_categories')}hide{/if}" title="{$record.sales_1yb}">{$record.sales_delta}</td>
		</tr>
		</tbody >
		{/foreach} 
	</table>
</div>
<script>

function change_sales_overview_type(type) {

    $('.widget_types .widget').removeClass('selected')
    $('#' + type).addClass('selected')

    if (type == 'orders') {

    } else if (type == 'invoices') {


        $('.category').addClass('hide')
        $('.store').removeClass('hide')

        $('.refunds,.invoices,.sales').removeClass('hide')
        $('#sales_overview_currency_container').removeClass('hide')
        $('.replacements ,.delivery_notes').addClass('hide')
        
        
    } else if (type == 'invoice_categories') {

        $('.category').removeClass('hide')
        $('.store').addClass('hide')

        $('.refunds,.invoices,.sales').removeClass('hide')
        $('#sales_overview_currency_container').removeClass('hide')
        $('.replacements ,.delivery_notes').addClass('hide')


    } else if (type == 'delivery_notes') {
        $('.category').addClass('hide')
        $('.store').removeClass('hide')
        $('.refunds,.invoices,.sales').addClass('hide')
        $('.replacements ,.delivery_notes').removeClass('hide')
        $('#sales_overview_currency_container').addClass('hide')

    }

console.log('caca')
    get_order_overview_data(type, $('#order_overview_period').val(), $('#order_overview_currency').val())


}


function toggle_sales_overview_currency() {
    if ($('#sales_overview_currency').hasClass('fa-toggle-off')) {
        var currency = 'store'
        $('#sales_overview_currency').removeClass('fa-toggle-off').addClass('fa-toggle-on')
    } else {
        var currency = 'account'
        $('#sales_overview_currency').addClass('fa-toggle-off').removeClass('fa-toggle-on')
    }

    get_order_overview_data($('#order_overview_type').val(), $('#order_overview_period').val(), currency)

    $('#order_overview_currency').val(currency)
}


function change_sales_overview_period(period) {

    $('.date_chooser .fixed_interval').removeClass('selected')
    $('#' + period).addClass('selected')

    $('#order_overview_period').val(period)


    get_order_overview_data($('#order_overview_type').val(), period, $('#order_overview_currency').val())
    
    console.log($('#order_overview_type').val()+' '+period)

    
}


function get_order_overview_data(type, period, currency) {

    var request = "/ar_dashboard.php?tipo=sales_overview&type=" + type + "&period=" + period + '&currency=' + currency

    $.getJSON(request, function(r) {


$('#order_overview_type').val(type)

        for (var record in r.data) {

            $('#' + record).html(r.data[record].value)

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
            if (r.data[record].title != undefined) {
                $('#' + record).attr('title', r.data[record].title)

            }

        }




    });

}


</script> 