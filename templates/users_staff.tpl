{include file='header.tpl'}

<div id="bd" style="padding:0px">
<div style="padding:0 20px">
{include file='users_navigation.tpl'}
<div  class="branch"> 
<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home"/></a>&rarr; <a  href="users.php">{t}Users{/t}</a> &rarr; {t}Staff Users{/t} </span>
</div>
<div class="top_page_menu">
    <div class="buttons" style="float:right">
        {if $modify}
        <button  onclick="window.location='edit_users_staff.php'" ><img src="art/icons/vcard_edit.png" alt=""> {t}Edit Users{/t}</button>
        {/if}
    </div>
    <div class="buttons" style="float:left">
        <button  onclick="window.location='users.php'" ><img src="art/icons/house.png" alt=""> {t}Users Home{/t}</button>
        </div>
    <div style="clear:both"></div>
</div>


<h1>{t}Staff Users{/t}</h1>
</div>



<ul class="tabs" id="chooser_ul" style="clear:both;margin-top:5px">
    <li> <span class="item {if $block_view=='users'}selected{/if}"  id="users">  <span> {t}Users List{/t}</span></span></li>
    <li> <span class="item {if $block_view=='categories'}groups{/if}"   id="groups">  <span> {t}Groups{/t}</span></span></li>
    <li> <span class="item {if $block_view=='login_history'}selected{/if}"  id="login_history">  <span> {t}Login History{/t}</span></span></li>
   
  </ul>
<div  style="clear:both;width:100%;border-bottom:1px solid #ccc"></div>

<div style="padding:0 20px">

 <div id="block_users" style="{if $block_view!='users'}display:none;{/if}clear:both;margin:10px 0 40px 0">

       <span class="clean_table_title">{t}Users List{/t}</span>
               <div  style="font-size:90%"   id="transaction_chooser" >
            <span style="float:right;margin-left:20px;" class=" table_type transaction_type state_details {if $elements.InactiveNotWorking}selected{/if} label_page_type"  id="elements_InactiveNotWorking"   >{t}Inactive Not Working{/t} (<span id="elements_InactiveNotWorking_number">{$elements_number.InactiveNotWorking}</span>)</span>
            <span style="float:right;margin-left:20px;" class=" table_type transaction_type state_details {if $elements.InactiveWorking}selected{/if} label_page_type"  id="elements_InactiveWorking"   >{t}Inactive Working{/t} (<span id="elements_InactiveWorking_number">{$elements_number.InactiveWorking}</span>)</span>
            <span style="float:right;margin-left:20px;" class=" table_type transaction_type state_details {if $elements.ActiveNotWorking}selected{/if} label_page_type"  id="elements_ActiveNotWorking"   >{t}Active Not Working{/t} (<span id="elements_ActiveNotWorking_number">{$elements_number.ActiveNotWorking}</span>)</span>
            <span style="float:right;margin-left:20px;" class=" table_type transaction_type state_details {if $elements.ActiveWorking}selected{/if} label_page_type"  id="elements_ActiveWorking"   >{t}Active Working{/t} (<span id="elements_ActiveWorking_number">{$elements_number.ActiveWorking}</span>)</span>
            </div>

       <div class="table_top_bar" style="margin-bottom:15px"></div>
    {include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0  }
    <div  id="table0"   class="data_table_container dtable btable" style="font-size:90%"> </div>
   
   </div>
   
 <div id="block_groups" style="{if $block_view!='groups'}display:none;{/if}clear:both;margin:10px 0 40px 0">
    
    <div class="data_table" style="margin-top:25px;width:600px">
      <span class="clean_table_title">{t}Groups{/t}</span>
        <div class="table_top_bar" style="margin-bottom:15px"></div>

  {include file='table_splinter.tpl' table_id=1 filter_name=$filter_name1 filter_value=$filter_value1  }
      <div  id="table1"   class="data_table_container dtable btable "> </div>
    </div>
    
    </div>
 
 <div id="block_login_history" style="{if $block_view!='login_history'}display:none;{/if}clear:both;margin:10px 0 40px 0">

       <div class="data_table" style="margin-top:25px">
      <span class="clean_table_title">{t}Staff User Login History{/t}</span>
            <div class="table_top_bar" style="margin-bottom:15px"></div>

         {include file='table_splinter.tpl' table_id=2 filter_name=$filter_name2 filter_value=$filter_value2  }
      <div  id="table2"   class="data_table_container dtable btable "> </div>
    </div>    
    
    </div>
</div>

</div> 

{include file='footer.tpl'}

