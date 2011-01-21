{include file='header.tpl'}
<script language="Javascript">
	window.onload = get_default({$v});
</script>
{literal}
<script language="Javascript">
	function checkDropdown() { 
	if(document.getElementById('assign_field').selectedIndex==0) 
	{
	
 	alert('Please assign the field');
	  return false;
	}
	return true;
}
</script>
{/literal}
<div id="bd">
<div id="no_details_title" style="clear:left;{if $show_details}display:none;{/if}">

    <h1>Import Contacts From CSV File 
</h1>
  </div>
<br>

<div class="left3Quarters">
 <form id="form" name="form" method="post" action="insert_csv.php" enctype="multipart/form-data" onsubmit="return checkDropdown()">
<input type="hidden" name="form" value="form" />
                    <div class="unframedsection"><div id="form:j_id68">
    <div class="prop">
	<label  for="fileUpload" class="import_level" style="font-size:14px;">
	Step 2 - Verify fields</label>
        <span style="font-size:12px;">
		We've scanned your file and found the following fields. It's important you verify that your contact information is assigned to the appropriate field in Capsule. When you're happy that the fields are assigned correctly press the continue button.
        </span>
	    </div></div>    
                    </div>
                    <div class="clear"></div>
                    <ul class="formActions">
                        <li>
                           <div class="framedsection">
				<div id="call_table"></div>
    			  </div>
		    <ul class="formActions">
                    		<li>
		     			<div class="bt"><input type="submit" value="Continue" name="" id=""></div>
                    		</li>
                	  </ul>	
                        </li>
                    </ul>
 </form>  
         </div>
</div>
{include file='footer.tpl'}
