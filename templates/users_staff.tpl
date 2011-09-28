{include file='header.tpl'}

<div id="bd" style="padding:0px">
<div style="padding:0 20px">
{include file='users_navigation.tpl'}
<div > 
  <span   class="branch"><a  href="users.php">{t}Users{/t}</a> &rarr; {t}Staff Users{/t} </span>
</div>
<h1>Staff Users</h1>
</div>

<ul class="tabs" id="chooser_ul" style="clear:both;margin-top:25px">
    <li> <span class="item {if $block_view=='users'}selected{/if}"  id="users">  <span> {t}Users List{/t}</span></span></li>
    <li> <span class="item {if $block_view=='categories'}groups{/if}"   id="groups">  <span> {t}Groups{/t}</span></span></li>
    <li> <span class="item {if $block_view=='login_history'}selected{/if}"  id="login_history">  <span> {t}Login History{/t}</span></span></li>
   
  </ul>
<div  style="clear:both;width:100%;border-bottom:1px solid #ccc"></div>

<div style="padding:0 20px">

 <div id="block_users" style="{if $block_view!='users'}display:none;{/if}clear:both;margin:10px 0 40px 0">

   
  
      <span class="clean_table_title">{t}Users List{/t}</span>
      <div  class="clean_table_caption"  style="clear:both;">
	<div style="float:left;"><div id="table_info0" class="clean_table_info"><span id="rtext0"></span> <span class="rtext_rpp" id="rtext_rpp0"></span> <span class="filter_msg"  id="filter_msg0"></span></div></div>
	<div class="clean_table_filter" id="clean_table_filter0"><div class="clean_table_info"><span id="filter_name0" class="filter_name" >{$filter_name0}</span>: <input style="border-bottom:none" id='f_input0' value="{$filter_value0}" size=10/><div id='f_container0'></div></div></div>
	<div class="clean_table_controls" style="" ><div><span  style="margin:0 5px" id="paginator0"></span></div></div>
      </div>
      <div  id="table0"   class="data_table_container dtable btable "> </div>
   
   </div>
   
 <div id="block_groups" style="{if $block_view!='groups'}display:none;{/if}clear:both;margin:10px 0 40px 0">
    
    <div class="data_table" style="margin-top:25px;width:600px">
      <span class="clean_table_title">{t}Groups{/t}</span>
      <div  class="clean_table_caption"  style="clear:both;">
	<div style="float:left;"><div class="clean_table_info">{$table_info} <span class="filter_msg"  id="filter_msg1"></span></div></div>
	<div class="clean_table_filter" style="display:none"><div class="clean_table_info">{$filter_name}: <input style="border-bottom:none" id='f_input1' value="{$filter_value}" size=10/><div id='f_container1'></div></div></div>
	<div class="clean_table_controls" style="" ><div><span  style="margin:0 5px" id="paginator1"></span></div></div>
      </div>
      <div  id="table1"   class="data_table_container dtable btable "> </div>
    </div>
    
    </div>
 
 <div id="block_login_history" style="{if $block_view!='login_history'}display:none;{/if}clear:both;margin:10px 0 40px 0">

       <div class="data_table" style="margin-top:25px">
      <span class="clean_table_title">{t}Staff User Login History{/t}</span>
         {include file='table_splinter.tpl' table_id=2 filter_name=$filter_name2 filter_value=$filter_value2  }
      <div  id="table2"   class="data_table_container dtable btable "> </div>
    </div>    
    
    </div>
</div>

</div> 

{include file='footer.tpl'}

