{include file='header.tpl'}
<div id="bd">
 

<div id="no_details_title" style="clear:left;{if $show_details}display:none;{/if}">
    <h1>Import Contacts From CSV File
</h1>
  </div>
<br>
<h3>&nbsp;&nbsp;&nbsp;<img src="images/exclamation-red-frame.png"/>&nbsp;&nbsp;Please make a decision on all fields marked 'Unknown, Please Choose...' </h3>
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
					<div id="call_table"></div>

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
