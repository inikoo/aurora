{include file='header.tpl'}
<div id="bd" >
 

<div id="no_details_title" style="clear:left;{if $show_details}display:none;{/if}">
    <h1>Import XLS file</h1>
  </div>
<br>
<h3>To get started, please make sure that you have an xls / xlsx format of the file:</h3>
<div class="ImportSection">
        <p>
		<b>Please follow the instruction given below to insert your information into the database : </b><br>
        </p>
		<p style="padding-left:50px;">
			<b>First</b>, open your xls / xlsx file ( in WINDOWS MS Office is required and in LINUX just double click to open it ).
		</p>
		<p style="padding-left:50px;">
			<b>Secondly</b>, go to file and click on save as.
		</p>
		<p style="padding-left:50px;">
			<b>Third</b>, search for CSV in file type.
		</p>
		<p style="padding-left:50px;">
			<b>Fourth</b>, save the file.
		</p>
		<p style="padding-left:50px;">
			<b>Fifth</b>, now you are done, just <a href="import.php?tipo=customers_store">click here</a> to upload the new file.
		</p>

        </div>
</div>

{include file='footer.tpl'}
