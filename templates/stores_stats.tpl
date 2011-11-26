{include file='header.tpl'}
<div id="bd" style="padding:0px">
<div style="padding:0 20px">
{include file='contacts_navigation.tpl'}
<div class="branch"> 
  <span><a href="stores.php">{t}Stores{/t}</a> &rarr; {t}Statistics{/t}</span>
</div>
 <div class="top_page_menu">
    <div class="buttons" style="float:left">
        <button  onclick="window.location='stores.php'" ><img src="art/icons/house.png" alt=""> {t}Stores{/t}</button>
    </div>
 
    <div style="clear:both"></div>
</div>
<h1>{t}Stores Statistics{/t}</h1>
</div>

<ul class="tabs" id="chooser_ul" style="clear:both;margin-top:5px">
    <li> <span class="item {if $view=='sales'}selected{/if}"  id="sales">  <span> {t}Sales{/t}</span></span></li>
    <li> <span class="item {if $view=='grown'}selected{/if}" id="grown"  ><span>  {t}Grown{/t}</span></span></li>
    <li> <span class="item {if $view=='customers'}selected{/if}"  id="customers">  <span> {t}Customers{/t}</span></span></li>
    <li> <span class="item {if $view=='orders'}selected{/if}"  id="orders">  <span> {t}Orders{/t}</span></span></li>
</ul>

<div  style="clear:both;width:100%;border-bottom:1px solid #ccc"></div>

<div id="block_correlations" style="{if $view!='correlations'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
<div id="the_table" class="data_table" style="clear:both;padding:0 10px">
      <span class="clean_table_title">{t}Customers Correlation (Possible Duplicates){/t}</span>
      
   <div  style="font-size:90%">
   

     </div>
  <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999;margin-bottom:15px"></div>
 
{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0  }
 <div  id="table0"  style="font-size:90%"  class="data_table_container dtable btable "> </div>
 </div>
</div>


<div id="block_customers" style="{if $view!='customers'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px"></div>
<div id="block_orders" style="{if $view!='orders'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px"></div>
<div id="block_sales" style="{if $view!='sales'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px"></div>
<div id="block_grown" style="{if $view!='grown'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px"></div>

</div> 


{include file='footer.tpl'}
