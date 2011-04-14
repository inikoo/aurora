{include file='header.tpl'}
 
<script language="Javascript">
	window.onload = get_default({$v});
</script>

<div id="bd">
<div id="no_details_title" style="clear:left;{if $show_details}display:none;{/if}">

    <h1>Import Contacts From CSV File 
</h1>
  </div>
	<div align="right"><form><input type="button" onclick="history.go(-2)" value="Cancel"></form></div>
<br>

<div class="left3Quarters">
 <form id="form_data" name="form_data" method="post" action="insert_csv.php?subject={$subject}&subject_key={$subject_key}" enctype="multipart/form-data" onsubmit="return checkDropdown()">
<input type="hidden" name="form" value="form" />
                    <div class="unframedsection"><div id="form:j_id68">
    <div class="prop">
	<label  for="fileUpload" class="import_level" style="font-size:14px;">
	Step 2 - Verify fields</label>
        <span style="font-size:12px;">
                {t}After scanned your file we found the subsequent fields that are needed to verify. Please make sure that all of your contacts information is filled up properly to the associated fields in the website. Once you filled up the fields properly, the next task you have to do is to hit on the continue button.{/t}
        </span>
	    </div></div>    
                    </div>
                    <div class="clear"></div>
                    <ul class="formActions">
                        <li>
                           <div class="framedsection">
				<div id="call_table"></div>
    			 <span id="ignore_message" style="color:red;"></span>
		    <ul class="formActions">
                    		<li>
					
		     			<div class="bt"><input type="submit" value="Next To Continue" name="" id="">
					
					</div>
					
					<div id="show"></div>
					
				
                    		</li>
                	  </ul>	
                        </li>
                    </ul> 
</div>
 </form>  
         </div>
</div>
{include file='footer.tpl'}
