<?php
	include('common.php');
	require_once 'csvparser.php';
	$csv = new CSV_PARSER;
	//loading the CSV File
	$csv->load($_SESSION['file_path']);
	//extracting the HEADERS
	$h = $csv->getHeaders();
	$count_rows = $csv->countRows();
	$index = $_REQUEST['v'];
	$r = $csv->getRow($index);
	
?>
<table class="recordList">
		
		
		<!--<th class="list-column-left" style="text-align: left; width: 20%; padding-left:5px;">
		    Column
		</th>-->
		<th class="list-column-left" style="text-align: left; width: 20%;padding-left:10px;">
		Assigned Field
		</th>
		<th class="list-column-left" style="text-align: left; width: 30%;">
		<span style="float: left;">
		<?php 
			echo 'Value '.($index + 1).' of '.$count_rows;

		?>
		</span>
		<span style="float: right;padding-right:5px;"> 
			     <?php
              			 if($index > 0)
              			 {
       			    ?>
       				<a href="#" class="subtext" id="prev" onclick="getPrev(<?php echo $index; ?>)">Previous</a>&nbsp;|&nbsp;
		       	   <?php
			         }
			       if($index < $count_rows-1)        
			         {
		       	   ?>
       				<a href="#"  class="subtext" id="next" onclick="getNext(<?php echo $index; ?>)">Next</a>
		          <?php
			         }
		          ?>
		&nbsp;&nbsp;&nbsp;&nbsp; <a href="#" onclick="getIgnore(<?php echo $index; ?>)" id="result" class="subtext">Ignore Result</a>
		</span>
		</th>
		
<tr>&nbsp;</tr>
		
	<?php
	for($j=0; $j<count($h); $j++)
	{ 
	?>
	<tr>
		<!--<td width=150 align=center >
		
		<?php //echo $h[$j]; 	$leftColumnArray = $h; ?>
		</td>-->
		<td align=center>
		<select name="assign_field[]" id="assign_field">
		    <option value="0">Ignore</option>
		    <option value="Customer Main Contact Name">Contact Name</option>
		    <option value="Customer Name">Name</option>
		    <option value="Customer Type">Type</option>
		    <option value="Customer Company Name">Company Name</option>
		    <option value="Customer Main Plain Email">Email</option>$j
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
		</td>
		<td>
			<!-- Value Output -->
			<?php echo $r[$j]; ?>
		</td>
	</tr>
		<input type="hidden" name="values[]" value="<?php echo $r[$j]; ?>">
		
	<?php
		
	}
	
	?>
	
</table>
