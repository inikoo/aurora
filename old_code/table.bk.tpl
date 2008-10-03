<div {if $hide_cap==1} style="display:none"{/if}  id="autocomplete{$table_id}" class="tablecaption {$showtable}">
  <div id="tabletitle{$table_id}" class="title">{t name=$table_title}%1{/t}</div> 
  <div id="hide{$table_id}"  class="hide"><img id="hidder{$table_id}" align="absbottom" src="art/icons/control_eject.png"  state="1"  alt="{t}Hide items{/t}"></div>


  <div id="filter{$table_id}" class="filter"   {if !$filter}style="display:none"{/if}  >
    <input  maxlength="10" size="10"  id="f_input{$table_id}" type="text" value="{$filter_value}">
    <input id="f_field{$table_id}" type="hidden" value="{$filter}">
    <img   id="resetfilter{$table_id}" class="resetfield" src="art/icons/textfield_delete.png" align="absbottom" alt="reset"   />
  </div>
  <div id="filtercontainer{$table_id}"></div>
  <div id="filtertitle{$table_id}" class="filtertitle"   > 
    <span  class="filterselector" id="filterselector{$table_id}">{$filter_name}</span>{if !options} {t}filter{/t}{/if}:</div> 




  <div id="paginatormenu{$table_id}" class="paginatormenu"> 
    <img id="paginatormenuselector{$table_id}" src="art/icons/application_view_columns.png"  />
    <img  class="loadingicon" id="loadingicon{$table_id}"   src="art/loading_icon.gif" alt=""  /> 
  </div>
  <div id="results{$table_id}" ></div> 
  <div id="paginator{$table_id}" class="paginator"> 
    <span   class="paginatorprev" id="paginator_prev{$table_id}"  >&lArr;</span> 
    <span id="pag{$table_id}">{t}Loadding...{/t}</span>
    <span  class="paginatornext"  id="paginator_next{$table_id}" style="margin-right:5px" >&rArr;</span> [<span id="paginator_rpp{$table_id}">{$rpp}</span>] 
  </div>
  
  {if $options}
  <div id="show_options{$table_id}" class="show_options" >    
    {foreach from=$options item=option key=key}
    <span id="option{$table_id}_{$key}"  {if $options_status[$key]  }class="selected"{/if}  >{$option}</span>
    {/foreach}
  </div>
  {/if}

 <div {if !$dates}style="display:none"{/if}   ><img id="dates{$table_id}" style="position:relative;z-index:10;margin-right:10px;cursor:pointer" src="art/icons/time.png" /></div>
 
 <div  id="input_dates{$table_id}"   class="dates" style="display:none;margin-right:0px">
   <span style="position:relative;left:18px">{t}From{/t}:
     <input  style="vertical-align: middle" type="text" id="v_calpop{$table_id}_1" size="11" /> 
     <img  id="calpop{$table_id}_1" style="vertical-align: middle;position:relative;right:21px" src="art/icons/calendar_view_month.png" />
   </span>
   <span>{t}to{/t}:
     <input  style="vertical-align: middle" type="text" id="v_calpop{$table_id}_2" size="11" /> 
     <img  id="calpop{$table_id}_2" style="vertical-align: middle;position:relative;right:21px" src="art/icons/calendar_view_month.png" />
   </span>
 </div>
 
 <div  {if !$dates}style="display:none"{/if}  id="text_dates{$table_id}" >{t}All{/t}</div>

</div>
<div     {if $hide_table==1} style="display:none"{/if}     id="table{$table_id}" class="dtable btable {$showtable}"></div>
