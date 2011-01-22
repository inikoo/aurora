{include file='header.tpl'}
<div id="bd" >
 

<div id="no_details_title" style="clear:left;{if $show_details}display:none;{/if}">
    <h1>Import Contacts From Xml File</h1>
  </div>
<br>
<h3>Upload your File:</h3>
<h4 style="color:red;">{$wrong}</h4>
<div class="left3Quarters">
                

<form id="form" name="form" method="post" action="import_xml_verify.php?tipo=customers_store" enctype="multipart/form-data">
<input type="hidden" name="form" value="form" />
 
                    <div class="unframedsection"><div id="form:j_id68">

    
    <div class="prop">
	
        
	<label  for="fileUpload" class="import_level">
	Xml File</label>
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
