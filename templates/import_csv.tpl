{include file='header.tpl'}
<div id="bd" >
 
 {*}
<h3>Upload from External site</h3>
<div class="left3Quarters">

<br/>
<form id="form" name="form" method="post" action="external_csv_verify.php?subject={$subject}&subject_key={$subject_key}" enctype="multipart/form-data">
You have {$records} Records to verify.
<input type="hidden" name="form" value="form" /> 
	<div class="clear"></div>
	<ul class="formActions">
		<li>
			<div class="bt"><input id="form:upload" type="submit" name="submit" value="Upload &amp; Preview" onclick="" />

			</div>
		</li>
	</ul>
                    
                    
</form>  
</div>
{/*}

<div id="no_details_title" style="clear:left;{if $show_details}display:none;{/if}">
    <h1>Import Contacts From CSV File</h1>
  </div>
<br>
{$showerror}


<div class="left3Quarters">
<h3>Upload your File:</h3>

<form id="form" name="form" method="post" action="import_csv_verify.php?subject={$subject}&subject_key={$subject_key}" enctype="multipart/form-data">
<input type="hidden" name="form" value="form" />
 
                    <div class="unframedsection"><div id="form:j_id68">

    
    <div class="prop">
	
	<label  for="fileUpload" class="import_level">
	CSV File</label>
        <span><input type="file" id="fileUpload" class="input2" name="fileUpload" size="50" />
        </span>
    </div></div>    
                    </div>
                    <div class="clear"></div>
                    <ul class="formActions">
                        <li>
                            <div class="bt"><input id="form:upload" type="submit" name="submit" value="Upload &amp; Preview" onclick="" />

                            </div>
                        </li>
                    </ul>
                    
                    
</form>  
                
            </div>



</div>

{include file='footer.tpl'}
