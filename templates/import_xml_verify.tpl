{include file='header.tpl'}
<div id="bd" >
 

<div id="no_details_title" style="clear:left;{if $show_details}display:none;{/if}">
    <h1>Import Contacts From XML File</h1> <a href="import_data.php?tipo=customers_store"><div align="right"><form><input type="button" onclick="history.go(-2)" value="Cancel"></form></div></a>
  </div>
<br>

<div class="left3Quarters">
                

<form id="form" name="form" method="post" action="import_xml_verify_final.php?tipo=customers_store" enctype="multipart/form-data">
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
				<table class="recordList">
		                    <tbody>
				    	  <tr>
						
				                <th class="list-column-left" style="text-align: left; width: 40%;padding-left:20px;">
				                    Assigned Field
				                </th>
				                <th class="list-column-left" style="text-align: left; width: 35%;">
				                    <span style="float: left;">Sample Values</span>

				                    <span style="float: right;padding-right:5px;"> <a href="#" class="subtext">next sample</a>
							
				                    <span style="float: right;padding-right:5px;"> 
				                    </span>
				                 </th>
		                          </tr>
		               	   </tbody>
			    	</table>
				<div style="height:200px;overflow:auto; vertical-align:top;">
				<table class="recordtext">
		                    <tbody>
					{foreach from=$success item=foo}
						
						 {foreach from=$foo item=v}
				    	  <tr>
						
				                <th style="text-align: left; width: 40%;padding-left:10px;">
				                    <select name="assign_field[]" id="assign_field">
						    <option value="0">Ignore</option>
                   				   <option value="Customer Main Contact Name">Contact Name</option>
						   <option value="Customer Name">Name</option>
						   <option value="Customer Type">Type</option>
						   <option value="Customer Company Name">Company Name</option>
						   <option value="Customer Main Plain Email">Email</option>
						   <option value="Contact Main Plain Mobile">Mobile</option>
						   <option value="Customer Main Plain Telephone">Telephone</option>        
						   <option value="Customer Main Plain FAX">FAX</option>
						   <option value="Customer Main Plain Address">Address</option>
						   <option value="Customer Address Line 1">Address Line1</option>
						   <option value="Customer Address Line 2">Address Line2</option>
						   <option value="Customer Address Line 3">Address Line3</option>
						  <option value="Customer Address Town">Town</option>
						   <option value="Customer Address Postal Code">Postal Code</option>
						   <option value="Customer Address Country Name">Country Name</option>
						   <option value="Customer Address Country First Division">First Division</option>
						   <option value="Customer Address Country Second Division">Second Division</option>
						   <option value="Customer Tax Number">Tax Number</option>
						    </select>

				                </th>
				                <th style="text-align: left; width:30%;">
				                    <span style="float: left;">
                                                  <input type ="hidden" name="val[]" id="val[]" value="{$v}">
				         	 {$v}
							
						</span>
				                    
				                    </span>
				                 </th>
		                          </tr>
					 {/foreach}
						{/foreach}
					 
		               	   </tbody>
			    	</table>
					
                                </div><div class="bt"><input style="margin-top:8px;" type="submit" value="Next To Continue" name="final" id="submit">
			     </div>
			    <ul class="formActions">
                    		<li>
				        
				        </div>
                    		</li>
                	  </ul>	
                        </li>
                    </ul>
                    
                    
</form>  
                
            </div>



</div>

{include file='footer.tpl'}
