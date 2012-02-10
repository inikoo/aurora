{include file='header.tpl'}

<div id="bd" >
{include file='users_navigation.tpl'}
<div  class="branch"> 
<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home"/></a>&rarr; <a  href="users.php">{t}Users{/t}</a> &rarr; {t}Staff Users{/t} </span>
</div>
<div class="top_page_menu">
    <div class="buttons" style="float:right">
        {if $modify}
        <button  onclick="window.location='edit_users_supplier.php'" ><img src="art/icons/vcard_edit.png" alt=""> {t}Edit Users{/t}</button>
        {/if}
    </div>
    <div class="buttons" style="float:left">
        <button  onclick="window.location='users.php'" ><img src="art/icons/house.png" alt=""> {t}Users Home{/t}</button>
        </div>
    <div style="clear:both"></div>
</div>
<h1>{t}Supplier Users{/t}</h1>


  <div id="yui-main">
   
    <div class="data_table" style="margin-top:25px">
      <span class="clean_table_title">{t}Supplier Users{/t}</span>
      <div  class="clean_table_caption"  style="clear:both;">
	<div style="float:left;"><div id="table_info0" class="clean_table_info"><span id="rtext0"></span> <span class="rtext_rpp" id="rtext_rpp0"></span> <span class="filter_msg"  id="filter_msg0"></span></div></div>
	<div class="clean_table_filter" id="clean_table_filter0"><div class="clean_table_info"><span id="filter_name0" class="filter_name" >{$filter_name0}</span>: <input style="border-bottom:none" id='f_input0' value="{$filter_value0}" size=10/><div id='f_container0'></div></div></div>
	<div class="clean_table_controls"  ><div><span  style="margin:0 5px" id="paginator0"></span></div></div>
      </div>
      <div  id="table0"   class="data_table_container dtable btable "> </div>
    </div>
   
    
  </div>
</div> 

{include file='footer.tpl'}

