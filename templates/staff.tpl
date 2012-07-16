{include file='header.tpl'}
<div id="bd" style="padding:0px">
<div style="padding:0px 20px">
 
 <div  class="branch"> 
  <span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home"/></a>  &rarr; <a href="hr.php">{t}Staff{/t} </a>  &rarr; {$staff->get('Staff Name')} </span>
</div>


<div id="top_page_menu" class="top_page_menu" style="margin-top:10px">

    {if isset($parent_list)}<img onMouseover="this.src='art/previous_button.gif'"  onMouseout="this.src='art/previous_button.png'"   title="{t}Previous Customer{/t} {$prev.name}" onclick="window.location='customer.php?{$parent_info}id={$prev.id}{if $parent_list}&p={$parent_list}{/if}'"  src="art/previous_button.png" alt="<"  style="margin-right:10px;float:left;height:22px;cursor:pointer;position:relative;top:2px" />{/if}
    <div class="buttons" style="float:left">
<h1 style="padding-bottom:0px">{$staff->get('Staff Name')}, <span style="color:SteelBlue"> {$staff->get('Staff Alias')} <i>({$staff->get('Staff ID')})</i></span>
     </h1> 
    </div>
    {if isset($parent_list)}<img onMouseover="this.src='art/next_button.gif'"  onMouseout="this.src='art/next_button.png'"  title="{t}Next Customer{/t} {$next.name}"  onclick="window.location='customer.php?{$parent_info}id={$next.id}{if $parent_list}&p={$parent_list}{/if}'"   src="art/next_button.png" alt=">"  style="float:right;height:22px;cursor:pointer;position:relative;top:2px"/ >{/if}
    <div class="buttons" style="float:right">
        <button  onclick="window.location='edit_staff.php?id={$staff->id}{if isset($parent_list)}&p={$parent_list}{/if}'" ><img src="art/icons/vcard_edit.png" alt=""> {t}Edit{/t}</button>
      </div>
    <div style="clear:both"></div>
</div>

</div>
<input type='hidden' id="staff_key" value="{$staff->id}"/>

  <ul class="tabs" id="chooser_ul" style="clear:both;margin-top:25px">
      <li> <span class="item {if $view=='details'}selected{/if}"  id="details">  <span> {t}Details{/t}</span></span></li>

    <li> <span class="item {if $view=='history'}selected{/if}"  id="history">  <span> {t}History Notes{/t}</span></span></li>
   <li> <span class="item {if $view=='working_hours'}selected{/if}"  id="working_hours">  <span> {t}Working Hours{/t}</span></span></li>

  </ul>
  <div  style="clear:both;width:100%;border-bottom:1px solid #ccc"></div>
 
 
 
<div id="block_details" class="data_table" style="{if $view!='details'}display:none;{/if}margin:20px 0 40px 0;padding:0 20px">
   <div style="width:350px">
   <table  class="show_info_product" border=0 style="padding:0">
{if $staff->get('Staff Name')}<tr><td valign="top" class="aleft">{t}Name{/t}:</td><td valign="top"colspan=2  class="aleft">{$staff->get('Staff Name')}</td></tr>{/if}

{if $staff->get('Staff Alias')}<tr><td valign="top" class="aleft">{t}Code{/t}:</td><td valign="top"colspan=2  class="aleft">{$staff->get('Staff Alias')}</td></tr>{/if}
{if $staff->get('Staff ID')}<tr><td valign="top" class="aleft">{t}Staff ID{/t}:</td><td colspan=2  class="aleft">{$staff->get('Staff ID')}</td ></tr>{/if}
{if $staff->get('Staff Type')}<tr><td valign="top"  class="aleft">{t}Staff Type{/t}:</td><td colspan=2  class="aleft">{$staff->get('Type')}</td ></tr>{/if}
{if $staff->get('Staff Valid From')}<tr><td valign="top" class="aleft">{t}Employed since{/t}:</td><td colspan=2  class="aleft">{$staff->get('Staff Valid From')}</td ></tr>{/if}
{if $staff->get('Staff Currently Working')!='Yes'}<tr><td valign="top" class="aleft">{t}Employed Until{/t}:</td><td colspan=2  class="aleft">{$staff->get('Staff Valid To')}</td ></tr>{/if}

</table>
</div>
   </div>
<div id="block_history" class="data_table" style="{if $view!='history'}display:none;{/if}margin:20px 0 40px 0;padding:0 20px;">
      <span class="clean_table_title">{t}History/Notes{/t}</span>
 {include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0  }
      <div  id="table0"   class="data_table_container dtable btable "> </div>
    </div>
<div id="block_working_hours" class="data_table" style="{if $view!='working_hours'}display:none;{/if}margin:20px 0 40px 0;padding:0 20px">
      <span class="clean_table_title">{t}Working Hours Details{/t}</span>
 {include file='table_splinter.tpl' table_id=1 filter_name=$filter_name1 filter_value=$filter_value1  }
       <div  id="table1"   class="data_table_container dtable btable "> </div>
  </div>



    </div>
{include file='footer.tpl'}

