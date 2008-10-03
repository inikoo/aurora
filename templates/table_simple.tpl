<div {if $hide_cap==1} style="display:none"{/if}  id="autocomplete{$table_id}" class="tablecaption {$showtable}">
  <div id="tabletitle{$table_id}" class="title">{t name=$table_title}%1{/t}</div> 
  <div id="hide{$table_id}"  class="hide"><img id="hidder{$table_id}" align="absbottom" src="art/icons/control_eject.png"  state="1"  alt="{t}Hide items{/t}"></div>

  <div id="filter{$table_id}" class="filter"   >
    <input  maxlength="12" size="12"  id="f_input{$table_id}" type="text" value="{$filter_value}">
    <input id="f_field{$table_id}" type="hidden" value="{$filter}">
    <img   id="resetfilter{$table_id}" class="resetfield" src="art/icons/textfield_delete.png" align="absbottom" alt="reset"   />
  </div>
  <div id="filtercontainer{$table_id}"></div>
  <div id="filtertitle{$table_id}" class="filtertitle"   > 
    <span  class="filterselector" id="filterselector{$table_id}">{$filter_name}</span> {t}filter{/t}:</div> 




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
  
</div>
<div     {if $hide_table==1} style="display:none"{/if}     id="table{$table_id}" class="dtable btable {$showtable}"></div>
