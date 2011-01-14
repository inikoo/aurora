{include file='header.tpl'}
<div id="bd" >
 <div class="search_box" style="margin-top:15px">
  <div class="general_options">
    {foreach from=$general_options_list item=options }
        {if $options.tipo=="url"}
            <span onclick="window.location.href='{$options.url}'" >{$options.label}</span>
        {else}
            <span  id="{$options.id}" state="{$options.state}">{$options.label}</span>
        {/if}
    {/foreach}
    </div>
</div>

 
 <div class="branch"> 
 <span ><a  href="categories.php?id=0">{t}Product Categories{/t}</a> &rarr; 
 </div> 
  
<div style="clear:left;">
  <h1>{$main_title}</h1>
</div>

<ul class="tabs" id="chooser_ul">
      <li> <span class="item {if $edit=='description'}selected{/if}"  id="description">  <span> {t}Categories{/t}</span></span></li>
    
    </ul>
<div  class="tabbed_container"> 
<div class="data_table" style="clear:both">
    <span class="clean_table_title">{$subcategories_title}</span>
 {*<div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999"></div>*}
 <span   style="float:right;margin-left:20px" class="state_details"  id="change_stores_mode" >{$display_stores_mode_label}</span>
 <span   style="float:right;margin-left:20px" class="state_details"  id="change_stores" >{$display_stores_label}</span>
 <span   style="float:right;margin-left:20px" class="state_details"  id="change_display_mode" >{$display_mode_label}</span>
 
    {include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0  }

       <div  id="table0"   class="data_table_container dtable btable "> </div>		
</div>
</div>

<div class="data_table" style="clear:both">
    <span class="clean_table_title">{t}History{/t}</span>
 {*<div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999"></div>*}
 <span   style="float:right;margin-left:80px" class="state_details"  id="change_display_mode" >{$display_mode_label}</span>
   
 
       
        {include file='table_splinter.tpl' table_id=1 filter_name=$filter_name1 filter_value=$filter_value1  }

       <div  id="table1"   class="data_table_container dtable btable with_total"> </div>		
</div>
  
</div> 
{include file='footer.tpl'}
