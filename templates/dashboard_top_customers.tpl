<div class="splinter_cell_dash_2">

<div id="the_table" class="data_table" >
 <span class="clean_table_title">Top Customers</span>
 <div class="home_splinter_options">
 <span id="top_customers_50" nr="50" {if $conf_data.top_customers.nr==50}class="selected"{/if} style="float:right;margin-left:5px">50</span>
 <span id="top_customers_20" nr="20" {if $conf_data.top_customers.nr==20}class="selected"{/if} style="float:right;margin-left:5px">20</span>
 <span id="top_customers_10" nr="10" {if $conf_data.top_customers.nr==10}class="selected"{/if} style="float:right;margin-left:15px">10</span>
 <span id="top_customers_all" period="all" {if $conf_data.top_customers.period=='all'}class="selected"{/if} style="float:right;margin-left:5px">{t}All times{/t}</span>
 <span id="top_customers_1y" period="1y" {if $conf_data.top_customers.period=='1y'}class="selected"{/if} style="float:right;margin-left:5px">{t}1y{/t}</span>
 <span id="top_customers_1q" period="1q" {if $conf_data.top_customers.period=='1q'}class="selected"{/if} style="float:right;margin-left:5px">{t}1q{/t}</span>
 <span id="top_customers_1m" period="1m" {if $conf_data.top_customers.period=='1m'}class="selected"{/if} style="float:right;margin-left:5px">{t}1m{/t}</span>
 </div>
  {include file='table_splinter.tpl' table_id=$index filter_name=$filter_name filter_value=$filter_value no_filter=1}
   <div  id="table{$index}"   class="data_table_container dtable btable"> </div>
 </div>
 </div>
