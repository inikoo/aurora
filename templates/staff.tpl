{include file='header.tpl'}
<div id="bd" style="padding:0px">
<div style="padding:0px 20px">
 {include file='hr_navigation.tpl'}
  <div id="no_details_title"  style="clear:left;xmargin:0 20px;{if $details!=0}display:none{/if}">
    <h1 style="padding-bottom:0px">{$staff->get('Staff Name')} <span style="color:SteelBlue">{$staff_id}</span>
      {if $next.id>0}<a class="prev" href="staff.php?id={$prev.id}" ><img src="art/icons/previous.png" alt="<" title="{$prev.name}"  /></a>{/if}
      {if $next.id>0}<a class="next" href="staff.php?id={$next.id}" ><img src="art/icons/next.png" alt=">" title="{$next.name}"  /></a>{/if}
      
    </h1> 

   
  </div>
  
  
     
  
 
     
     
     

<table border=0 style="padding:0">
{if $staff->get('Staff Alias')}<tr><td valign="top"colspan=2  class="aleft">{$staff->get('Staff Alias')}</td></tr>{/if}
{if $staff->get('Staff ID')}<tr><td valign="top" class="aleft">Staff ID</td><td colspan=2  class="aleft">: {$staff->get('Staff ID')}</td ></tr>{/if}
{if $staff->get('Staff Type')}<tr><td valign="top"  class="aleft">Staff Type</td><td colspan=2  class="aleft">: {$staff->get('Staff Type')}</td ></tr>{/if}
{if $staff->get('Staff Valid from')}<tr><td valign="top" class="aleft">Valid From</td><td colspan=2  class="aleft">: {$staff->get('Staff Valid from')}</td ></tr>{/if}
{if $staff->get('Staff Valid To')}<tr><td valign="top" class="aleft">Valid To</td><td colspan=2  class="aleft">: {$staff->get('Staff Valid To')}</td ></tr>{/if}

</table>

</div>

  <ul class="tabs" id="chooser_ul" style="clear:both;margin-top:25px">
    <li> <span class="item {if $view=='history'}selected{/if}"  id="details">  <span> {t}History Notes{/t}</span></span></li>
   

  </ul>
  <div  style="clear:both;width:100%;border-bottom:1px solid #ccc">
  </div>
 
  
  
 <div id="block_history" class="data_table" style="margin:20px 0 40px 0;padding:0 20px">
      <span class="clean_table_title">{t}History/Notes{/t}</span>
 {include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0  }
      <div  id="table0"   class="data_table_container dtable btable "> </div>
    </div>


</div>
{include file='footer.tpl'}

