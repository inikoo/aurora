{include file='header.tpl'}
<div id="bd" >
 

<div id="no_details_title" style="clear:left;{if $show_details}display:none;{/if}">
    <h1>Import Contacts From XML File
</h1>
  </div>
<br>

<div class="left3Quarters">
                

<form id="form" name="form" method="post" action="" enctype="multipart/form-data">
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
						<th class="list-column-left" style="text-align: left; width: 20%; padding-left:5px;">
						            Column
						</th>
				                <th class="list-column-left" style="text-align: left; width: 40%;padding-left:10px;">
				                    Assigned Field
				                </th>
				                <th class="list-column-left" style="text-align: left; width: 30%;">
				                    <span style="float: left;">Sample Values</span>
				                    <span style="float: right;padding-right:5px;"> <a href="#" class="subtext">next sample</a>
				                    </span>
				                 </th>
		                          </tr>
		               	   </tbody>
			    	</table>
				<div style="height:200px;overflow:auto; vertical-align:top;">
				<table class="recordtext">
		                    <tbody>
					{foreach from=$success item=foo}
						 {foreach from=$foo key=k item=v}
				    	  <tr>
						<th style="text-align: left; width: 20%;">
						        {$k}
						</th>
				                <th style="text-align: left; width: 40%;">
				                    <select name="assign_field" id="assign_field">
						    <option>Unknown Please Choose</option>
						    <option value="ignore">Ignore</option>
						    <option value="Customer Main Contact Name">Customer Main Contact Name</option>
						    <option value="Customer Name">Customer Name</option>
						    <option value="Customer Type">Customer Type</option>
						    <option value="Customer Company Name">Customer Company Name</option>
						    <option value="Customer Main Contact Name">Customer Main Contact Name</option>
						    <option value="Customer Main Plain Email">Customer Main Plain Email</option>
						    <option value="Contact Main Plain Mobile">Contact Main Plain Mobile</option>
						    <option value="Customer Main Plain Telephone">Customer Main Plain Telephone</option>	
						    <option value="Customer Main Plain FAX">Customer Main Plain FAX</option>
						    <option value="Customer Main Plain Address">Customer Main Plain Address</option>
						    <option value="Customer Address Line 1">Customer Address Line 1</option>
						    <option value="Customer Address Line 2">Customer Address Line 2</option>
						    <option value="Customer Address Line 3">Customer Address Line 3</option>
						    <option value="Customer Address Line 2">Customer Address Line 2</option>
						    <option value="Customer Address Town">Customer Address Town</option>
						    <option value="Customer Address Postal Code">Customer Address Postal Code</option>
						    <option value="Customer Address Country Name">Customer Address Country Name</option>
						    <option value="Customer Address Country First Division">Customer Address Country First Division</option>
						    <option value="Customer Address Country Second Division">Customer Address Country Second Division</option>
						    <option value="Customer Tax Number">Customer Tax Number</option>
						    </select>

				                </th>
				                <th style="text-align: left; width:30%;">
				                    <span style="float: left;">
				         	 {$v}
							
						</span>
				                    
				                    </span>
				                 </th>
		                          </tr>
					 {/foreach}
						{/foreach}
					 
		               	   </tbody>
			    	</table>
                                </div>

                            </div>
			    <ul class="formActions">
                    		<li>
				        <div class="bt"><input type="submit" value="Continue" name="" id="">
				        </div>
                    		</li>
                	  </ul>	
                        </li>
                    </ul>
                    
                    
</form>  
                
            </div>



</div>

{include file='footer.tpl'}
