{include file='header.tpl'}
<div id="bd" >
 {include file='assets_navigation.tpl'}
<div  class="branch"> 
  <span  >{if $user->get_number_stores()>1}<a  href="stores.php">{t}Stores{/t}</a> &rarr; {else}<a href="store.php?id={$store->id}">{$store->get('Store Name')}</a> &rarr; {/if}  {$code}</span>
</div>

<div class="top_page_menu ">
			<div class="buttons" style="float:right">
				
			</div>
			<div class="buttons" style="float:left">
				<span class="main_title no_buttons">{t}Products with code{/t}: <span class="id">{$code}</span></span> 
			</div>
			<div style="clear:both">
			</div>
		</div>

 <div style="clear:both">

	  
 <div  id="orders_table" class="data_table" style="clear:both;margin-top:10px">
    <span class="clean_table_title">{t}Product ID List{/t}</span>
    <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999;margin-bottom:10px"></div>
    {include file='table_splinter.tpl' table_id=2 filter_name=$filter_name2 filter_value=$filter_value2  no_filter=1} 
    <div  id="table2"   class="data_table_container dtable btable" style="font-size:90%"> </div>
  </div>	  
	  
</div>

</div>{include file='footer.tpl'}

