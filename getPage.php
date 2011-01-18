<?php
	require_once 'csvparser.php';
	$csv = new CSV_PARSER;
	//loading the CSV File
	$csv->load('uploads/Book1.csv');
	//extracting the HEADERS
	$h = $csv->getHeaders();
	$index = $_REQUEST['v'];
	$r = $csv->getRow($index);
	
?>
<div style="height:200px;overflow:auto; vertical-align:top;">
<table border=0 width=600 align="center" style="border:1px solid #000000">
	
	<?php
	for($j=0; $j<count($h)-1; $j++)
	{ 
	?>
	<tr>
		<td>
		<!-- Header Output -->
		<?php echo $h[$j];?>
		</td>
		<td align=center>
		<select name="assign_field" id="assign_field">
		    <option>Unknown Please Choose</option>
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
		</td>
		<td>
			<!-- Value Output -->
			<?php echo $r[$j];?>
		</td>
	</tr>
	<?php
	}
	?>
<tr>
<td colspan="3" align="right">
	
	<a href="#" id="prev" onclick="getPrev(<?php echo $index; ?>)">Previous</a>
	<a href="#" id="next" onclick="getNext(<?php echo $index; ?>)">Next</a>	
</td>
</tr>
</table>
</div>
			

