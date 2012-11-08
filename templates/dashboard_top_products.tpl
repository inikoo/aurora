<div class="splinter_cell_products">
	<div id="the_table" class="data_table" >
      	<span class="clean_table_title">Top Products</span>
             <div class="home_splinter_options">
                    
                    
<span id="top_products_50" nr="50" {if $conf_data.top_products.nr==50}class="selected"{/if} style="float:right;margin-left:5px">50</span>
<span id="top_products_20" nr="20" {if $conf_data.top_products.nr==20}class="selected"{/if} style="float:right;margin-left:5px">20</span>
<span id="top_products_10" nr="10" {if $conf_data.top_products.nr==10}class="selected"{/if} style="float:right;margin-left:15px">10</span>
<span id="top_products_all" period="all" {if $conf_data.top_products.period=='all'}class="selected"{/if} style="float:right;margin-left:5px">{t}All times{/t}</span>
<span id="top_products_1y" period="1y" {if $conf_data.top_products.period=='1y'}class="selected"{/if} style="float:right;margin-left:5px">{t}1y{/t}</span>
<span id="top_products_1q" period="1q" {if $conf_data.top_products.period=='1q'}class="selected"{/if} style="float:right;margin-left:5px">{t}1q{/t}</span>
<span id="top_products_1m" period="1m" {if $conf_data.top_products.period=='1m'}class="selected"{/if} style="float:right;margin-left:5px">{t}1m{/t}</span>
	 	
        	</div>
            
 {include file='table_top_products.tpl' table_id=$index filter_name=$filter_name filter_value=$filter_value no_filter=1}
 
   <div  id="table{$index}"   class="data_table_container dtable btable"> </div>

 </div>
 </div>
