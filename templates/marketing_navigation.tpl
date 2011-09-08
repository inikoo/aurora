<span class="nav2 onleft"><a href="#">{t}Create List{/t}</a></span>

<span class="nav2 onleft"><a href="customers_lists.php">{t}View List{/t}</a></span>
<span class="nav2 onleft"><a href="new_campaign.php">{t}Create Campaign{/t}</a></span>
<span class="nav2 onleft"><a href="campaign_builder.php">{t}View Campaign{/t}</a></span>




<div class="right_box">
  <div class="general_options">
    

    

  </div>
</div>

{if $search_scope}
<table class="search"  border=0>
<tr>
<td class="label" style="" >{$search_label}:</td>
<td class="form" style="">
<div id="search" class="asearch_container"  style=";float:left;{if !$search_scope}display:none{/if}">
  <input style="width:300px" class="search" id="{$search_scope}_search" value="" state="" name="search"/>
      <img style="position:relative;left:305px" align="absbottom" id="{$search_scope}_clean_search" class="submitsearch" src="art/icons/zoom.png">

    <div id="{$search_scope}_search_Container" style="display:none"></div>
</div>    
  
</td></tr>
</table>  
<div id="{$search_scope}_search_results" style="font-size:10px;float:right;background:#fff;border:1px solid #777;padding:10px;margin-top:0px;width:500px;position:absolute;z-index:20;top:-500px">
<table id="{$search_scope}_search_results_table"></table>
</div>
{/if}



