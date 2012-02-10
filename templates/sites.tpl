{include file='header.tpl'}
<div id="bd" style="padding:0px">
<script type="text/javascript" src="external_libs/amstock/amstock/swfobject.js"></script>
<input type="hidden" id="site_key" value="{$site->id}"/>
<div style="padding:0 20px">
{include file='assets_navigation.tpl'}
<div  class="branch"> 
<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home"/></a>&rarr;  {t}Websites{/t}</span>
</div>



    <h1>{t}Websites{/t}</h1>


</div>

<ul class="tabs" id="chooser_ul" style="clear:both;margin-top:15px">
    <li> <span class="item {if $block_view=='sites'}selected{/if}"  id="sites">  <span> {t}Websites{/t}</span></span></li>
    <li > <span class="item {if $block_view=='pages'}selected{/if}"  id="pages">  <span> {t}Pages{/t}</span></span></li>
 
  </ul>
<div  style="clear:both;width:100%;border-bottom:1px solid #ccc"></div>

<div style="padding:0 20px">


<div id="block_sites" style="{if $block_view!='sites'}display:none;{/if}clear:both;margin:20px 0 40px 0">

  <span   class="clean_table_title" >{t}Website List{/t}</span>
    
 <div class="table_top_bar"></div>
    
   
 {include file='table_splinter.tpl' table_id=1 filter_name=$filter_name1 filter_value=$filter_value1 no_filter=1  }
<div  id="table1"   class="data_table_container dtable btable" style="font-size:85%"> </div>





</div>
<div id="block_pages" style="{if $block_view!='pages'}display:none;{/if}clear:both;margin:20px 0 40px 0">
   <span   class="clean_table_title" >{t}Pages{/t}</span>
 <div id="table_type">
     <span id="table_type_list" style="float:right" class="table_type state_details {if $table_type=='list'}selected{/if}">{t}List{/t}</span>
     <span id="table_type_thumbnail" style="float:right;margin-right:10px" class="table_type state_details {if $table_type=='thumbnails'}selected{/if}">{t}Thumbnails{/t}</span>
     </div>
   
 <div class="table_top_bar"></div>
    
   
 {include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0 no_filter=0  }
<div  id="table0"   class="data_table_container dtable btable" style="font-size:85%"> </div>


</div>



 




 

</div>


</div>
{include file='footer.tpl'}
<div id="rppmenu0" class="yuimenu" >
  <div class="bd">
    <ul class="first-of-type">
       <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Rows per Page{/t}:</li>
      {foreach from=$paginator_menu0 item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_rpp_with_totals({$menu},0)"> {$menu}</a></li>
      {/foreach}
    </ul>
  </div>
</div>
<div id="filtermenu0" class="yuimenu" >
  <div class="bd">
    <ul class="first-of-type">
      <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Filter options{/t}:</li>
      {foreach from=$filter_menu0 item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_filter('{$menu.db_key}','{$menu.label}',0)"> {$menu.menu_label}</a></li>
      {/foreach}
    </ul>
  </div>
</div>

