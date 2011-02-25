{include file='header.tpl'}
<div id="bd" >
{literal}
<script>
$(document).ready(function(){
$("#showr").click(function () {
  $("#display_part:eq(0)").show("fast", function () {
    /* use callee so don't have to name the function */
    $(this).next("#display_part").show("fast", arguments.callee);
  });
});
$("#hidr").click(function () {
  $("#display_part").hide("fast");
});
});
</script>
{/literal}
 
<div class="data_table" style="clear:both">
   <span class="clean_table_title">{t}campaign List{/t}</span>
	<div style="float:right; padding-right:300px;"><span style="font-size:11px; color:green;">{$msg}</span></div>
   <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999"></div>
   
  <form action="send_mail.php" method="post">
  {include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0  }
    <div  id="table0"   class="data_table_container dtable btable "> </div>
  </div>




<b id="showr" class="template_choose">Choose Template</b>
  <b id="hidr">Hide Option</b>
  <div id="display_part">
        		
  			
                            	<input type="radio" id="template1" name="template" value="1">Basic Template<br>
                               <input type="radio" id= "template2" name="template" value="2">Classic Newsletter Template<br>
                                <input type="radio" id="template3" name="template" value="3">Modern Newsletter Template<br>
                                <input type="radio" id="template4" name="template" value="4">Postcard Template<br>
                            
    	
  </div>
	<div><br><input type="submit" name="submit" value="Send Mail"></div>
</form>

</div>


  
  <div id="filtermenu0" class="yuimenu">
    <div class="bd">
      <ul class="first-of-type">
	<li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Filter options{/t}:</li>
	{foreach from=$filter_menu0 item=menu }
	<li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_filter('{$menu.db_key}','{$menu.label}',0)"> {$menu.menu_label}</a></li>
	{/foreach}
      </ul>
    </div>
  </div>
  
  <div id="rppmenu0" class="yuimenu">
    <div class="bd">
      <ul class="first-of-type">
	<li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Rows per Page{/t}:</li>
	{foreach from=$paginator_menu0 item=menu }
	<li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_rpp({$menu},0)"> {$menu}</a></li>
	{/foreach}
      </ul>
    </div>
  </div>

  
  {include file='footer.tpl'}
