{include file='header.tpl'}
<div id="bd" >
 

    <h1>{t}Import Results{/t}</h1>
  
<table   class="report_sales1"  style="margin-top:20px">
<tr><td>{t}To do records{/t}</td><td id="records_todo"></td><td id="records_todo_comments"></td></tr>
<tr><td>{t}Imported Records{/t}</td><td id="records_imported"></td><td id="records_imported_comments"></td></tr>
<tr><td>{t}Ignored{/t}</td><td id="records_ignored"></td><td id="records_ignored_comments"></td></tr>
<tr><td>{t}Errors{/t}</td><td id="records_error"></td><td id="records_error_comments"></td></tr>

</table>



</div>

{include file='footer.tpl'}
