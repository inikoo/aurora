{include file='header.tpl'}
<div id="bd" >
 

<div id="no_details_title" style="clear:left;{if $show_details}display:none;{/if}">
    <h1>Import Contacts From CSV File
</h1>
  </div>
<br>

<div class="left3Quarters">
               

<form id="form" name="form" method="post" action="" enctype="multipart/form-data">
<input type="hidden" name="form" value="form" />
 
                    <div class="unframedsection"><div id="form:j_id68">

   
    <div class="prop">
    <label  for="fileUpload" class="import_level" style="font-size:14px;">
    Step 3</label>
       
        </div></div>   
                    </div>
                    <div class="clear"></div>
                    <ul class="formActions">
                        <li>
                            <div class="framedsection">
		
			 {php}
       
        echo "The Ignored array fields:<br>";
         $ignored_array = $this->get_template_vars('ignored_array');
                echo "<pre>";
                print_r($ignored_array);
                         {/php}      
               
		<br>
	<br>
	<br>

                {php}
         
                 echo"<br>";
		echo "The Final array<br>";
                  $final = $this->get_template_vars('arr');
                echo "<pre>";
                print_r($final);
               
                {/php}
                      
       
		
               

    

                            </div>
               
                        </li>
                    </ul>
                   
                   
</form> 
               
            </div>



</div>

{include file='footer.tpl'}
