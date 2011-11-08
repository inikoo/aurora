{include file='header.tpl'}
<div id="bd" style="padding:0px">
<div style="padding:0 20px">
{include file='assets_navigation.tpl'}
<div class="branch"> 
  <span   >{if $user->get_number_stores()>1}<a  href="stores.php">{t}Stores{/t}</a> &rarr; {/if}<a href="store.php?id={$store->id}">{$store->get('Store Name')}</a> &rarr; {t}Offers{/t}</span>
</div>
<div style="clear:both;width:100%;border-bottom:1px solid #ccc;padding-bottom:3px;margin-bottom:0px">

  <div class="buttons" style="float:left">
        <button  onclick="window.location='store.php?id={$store->id}'" ><img src="art/icons/house.png" alt=""> {t}Store{/t}</button>
    </div>


<div class="buttons">
<button  onclick="window.location='new_products_list.php?store={$store->id}'" ><img src="art/icons/add.png" alt=""> {t}Campaign{/t}</button>
</div>




<div style="clear:both"></div>
</div>



<h1 style="clear:left">{t}Offers{/t} <span class="id">{$store->get('Store Code')}</span></h1>

</div>

<div style="padding:0px">
<ul class="tabs" id="chooser_ul" style="clear:both;margin-top:5px">
    <li> <span class="item {if $block_view=='details'}selected{/if}"  id="details">  <span> {t}Overview{/t}</span></span></li>
    <li> <span class="item {if $block_view=='campaigns'}selected{/if}"  id="stores">  <span> {t}Campaigns{/t}</span></span></li>
    <li> <span class="item {if $block_view=='offers'}selected{/if}"  id="departments">  <span> {t}Offers{/t}</span></span></li>
   
  </ul>
<div  style="clear:both;width:100%;border-bottom:1px solid #ccc"></div>
</div>


<div style="padding:0 20px">

<div id="block_campaigns" style="{if $block_view!='campaigns'}display:none;{/if}clear:both;margin:20px 0 40px 0">

    
    <div id="the_table" class="data_table" style="margin-top:20px;clear:both;" >
    <span class="clean_table_title">Campaigns</span>



  <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999;margin-bottom:15px"></div>

   

</div>


      
 {include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0 no_filter=true }
     	<div  id="table0"   class="data_table_container dtable btable" style="font-size:85%"> </div>
      </div>
<div id="block_details" style="{if $block_view!='details'}display:none;{/if}clear:both;margin:10px 0 40px 0"></div>
<div id="block_offers" style="{if $block_view!='offers'}display:none;{/if}clear:both;margin:10px 0 40px 0"></div>

</div>

   </div> 

<div id="rppmenu0" class="yuimenu">
  <div class="bd">
    <ul class="first-of-type">
       <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Rows per Page{/t}:</li>
      {foreach from=$paginator_menu0 item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_rpp({$menu},0)"> {$menu}</a></li>
      {/foreach}
    </ul>
  </div>
</div>



 </div>

{include file='footer.tpl'}
