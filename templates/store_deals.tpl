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
<button  id="new_offer" ><img src="art/icons/add.png" alt=""> {t}Add Offer{/t}</button>
</div>




<div style="clear:both"></div>
</div>



<h1 style="clear:left">{t}Offers{/t} <span class="id">{$store->get('Store Code')}</span></h1>

</div>

<div style="padding:0px">
<ul class="tabs" id="chooser_ul" style="clear:both;margin-top:5px">
    <li> <span class="item {if $block_view=='details'}selected{/if}"  id="details">  <span> {t}Overview{/t}</span></span></li>
    <li> <span class="item {if $block_view=='campaigns'}selected{/if}"  id="campaigns">  <span> {t}Campaigns{/t}</span></span></li>
    <li> <span class="item {if $block_view=='offers'}selected{/if}"  id="offers">  <span> {t}Offers{/t}</span></span></li>
   
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
<div id="block_offers" style="{if $block_view!='offers'}display:none;{/if}clear:both;margin:10px 0 40px 0">

       <div id="the_table" class="data_table" style="margin-top:20px;clear:both;" >
    <span class="clean_table_title">Offers</span>
    
      <div id="table_type" class="table_type">
        <div  style="font-size:90%"   id="transaction_chooser" >
            <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements.Product}selected{/if} label_tarms_objectproduct"  id="elements_product" table_type="product"   >{t}Product{/t} (<span id="elements_product_number">{$elements_number.Product}</span>)</span>
            <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements.Family}selected{/if} label_tarms_objectfamily"  id="elements_family" table_type="family"   >{t}Family{/t} (<span id="elements_family_number">{$elements_number.Family}</span>)</span>
            <span style="float:right;margin-left:20px;" class=" table_type transaction_type state_details {if $elements.Department}selected{/if} label_tarms_objectdepartment"  id="elements_department" table_type="department"   >{t}Department{/t} (<span id="elements_department_number">{$elements_number.Department}</span>)</span>
            <span style="float:right;margin-left:20px;" class=" table_type transaction_type state_details {if $elements.Order}selected{/if} label_tarms_objectorder"  id="elements_order" table_type="order"   >{t}Order{/t} (<span id="elements_order_number">{$elements_number.Order}</span>)</span>

        </div>
     </div>
    
    
  <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999;margin-bottom:15px"></div>
</div>
       {include file='table_splinter.tpl' table_id=1 filter_name=$filter_name1 filter_value=$filter_value1 no_filter=true }
     	<div  id="table1"   class="data_table_container dtable btable" style="font-size:85%"> </div>
      


</div>

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
