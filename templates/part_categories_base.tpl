{include file='header.tpl'}
<div id="bd" style="padding:0px">
<div style="padding:0px 20px">
 {include file='locations_navigation.tpl'}

<div class="branch"> 
  <span >{if $user->get_number_warehouses()>1}<a href="warehouses.php">{t}Warehouses{/t}</a> &rarr; {/if}<a href="warehouse_parts.php">{t}Inventory{/t}</a>  &rarr; {t}Parts Categories{/t}</span>
</div>
<div class="top_page_menu">
    <div class="buttons" style="float:left">
        <button  onclick="window.location='warehouse_parts.php?warehouse_id={$warehouse->id}'" ><img src="art/icons/house.png" alt=""> {t}Warehouse{/t}</button>
    </div>
    <div class="buttons" style="float:right">
        <button  onclick="window.location='edit_product_category.php?store_id={$warehouse->id}&id=0'" ><img src="art/icons/table_edit.png" alt=""> {t}Edit Categories{/t}</button>
        <button id="new_category" ><img src="art/icons/add.png" alt=""> {t}Main Category{/t}</button>
    </div>
    <div style="clear:both"></div>
</div>
 <div style="clear:left;">
  <h1>{t}Parts Categories Home{/t}</h1>
</div>
</div>

<ul class="tabs" id="chooser_ul" style="clear:both;margin-top:5px">
    <li> <span class="item {if $block_view=='subcategories'}selected{/if}"  id="subcategories">  <span> {t}Categories{/t}</span></span></li>
    <li> <span class="item {if $block_view=='history'}selected{/if}"  id="history">  <span> {t}History{/t}</span></span></li>
</ul>
<div  style="clear:both;width:100%;border-bottom:1px solid #ccc"></div>
<div id="block_subcategories" style="{if $block_view!='subcategories'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">

    <span class="clean_table_title">{t}Main Categories{/t}</span>
   
    {include file='table_splinter.tpl' table_id=1 filter_name=$filter_name1 filter_value=$filter_value1  }

       <div  id="table1"   class="data_table_container dtable btable "> </div>		
</div>
<div id="block_history" style="{if $block_view!='history'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">

  <span class="clean_table_title" >{t}History{/t}</span>
     {include file='table_splinter.tpl' table_id=2 filter_name=$filter_name2 filter_value=$filter_value2  }
  <div  id="table2"   class="data_table_container dtable btable "> </div>

</div>



  
</div> 
{include file='footer.tpl'}
{include file='new_category_splinter.tpl'}
