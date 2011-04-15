<input type="hidden" value="{$sales_index}" id="sales_index"  />
<input type="hidden" value="{$sales_nr}" id="sales_nr"  />

<input type="hidden" value="{t}Store{/t}" id="label_Store"  />
<input type="hidden" value="{t}Invoices{/t}" id="label_Invoices"  />
<input type="hidden" value="% {t}Invoices{/t}" id="label_Invoices_Share"  />
<input type="hidden" value="&Delta;{t}Last Yr Invoices{/t}" id="label_Invoices_Delta"  />

<input type="hidden" value="{t}Sales{/t}" id="label_Sales"  />
<input type="hidden" value="% {t}Sales{/t}" id="label_Sales_Share"  />
<input type="hidden" value="&Delta;{t}Last Yr Sales{/t}" id="label_Sales_Delta"  />



<div class="splinter_cell" style="width:910px">
    <div id="the_table" class="data_table" >
    <div style="float:left;margin-right:10px">
       
        <div class="home_splinter_options">
         
          <span id="type_stores"  {if $conf_data.sales.currency=='corporate'}class="selected"{/if} style="float:right;margin-left:10px">{t}Corporate Currency{/t}</span>
            <span id="type_categories"  {if $conf_data.sales.currency=='store'}class="selected"{/if} style="float:right;margin-left:35px">{t}Store Currencies{/t}</span>
             <span id="type_stores"  {if $conf_data.sales.type=='stores'}class="selected"{/if} style="float:right;margin-left:10px">{t}Stores{/t}</span>
            <span id="type_categories"  {if $conf_data.sales.type=='categories'}class="selected"{/if} style="float:right;margin-left:35px">{t}Categories{/t}</span>
            <span id="ytd"  {if $conf_data.sales.period=='ytd'}class="selected"{/if} style="float:right;margin-left:10px">{t}YTD{/t}</span>
            <span id="mtd" {if $conf_data.sales.period=='mtd'}class="selected"{/if} style="float:right;margin-left:10px">{t}MTD{/t}</span>
            <span id="wtd" {if $conf_data.sales.period=='wtd'}class="selected"{/if} style="float:right;margin-left:10px">{t}WTD{/t}</span>
            <span id="today"  {if $conf_data.sales.period=='today'}class="selected"{/if} style="float:right;margin-left:10px">{t}today{/t}</span>
            <span id="yesterday" {if $conf_data.sales.period=='yesterday'}class="selected"{/if} style="float:right;margin-left:10px">{t}yesterday{/t}</span>
            <span id="last_w"  {if $conf_data.sales.period=='last_w'}class="selected"{/if} style="float:right;margin-left:10px">{t}last w{/t}</span>
            <span id="last_m"  {if $conf_data.sales.period=='last_m'}class="selected"{/if} style="float:right;margin-left:10px">{t}last m{/t}</span>
            <span id="sales_1y" {if $conf_data.sales.period=='1y'}class="selected"{/if} style="float:right;margin-left:10px">{t}1y{/t}</span>
            <span id="sales_1q"  {if $conf_data.sales.period=='1q'}class="selected"{/if} style="float:right;margin-left:10px">{t}1q{/t}</span>
            <span id="sales_1m" {if $conf_data.sales.period=='1m'}class="selected"{/if} style="float:right;margin-left:10px">{t}1m{/t}</span>
            <span id="sales_10d" {if $conf_data.sales.period=='10d'}class="selected"{/if} style="float:right;margin-left:10px">{t}10d{/t}</span>
            <span id="sales_1w" {if $conf_data.sales.period=='1m'}class="selected"{/if} style="float:right;margin-left:10px">{t}1w{/t}</span>

   
   </div>
        {include file='table_splinter.tpl' table_id=$index filter_name=$filter_name filter_value=$filter_value no_filter=1}
        <div  id="table{$index}"   class="data_table_container dtable btable "> </div>
       </div>
      
       
        
    </div>
</div>