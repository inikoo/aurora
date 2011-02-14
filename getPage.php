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
	
	$tt = array();

	$prev = array();	

	if(isset($_REQUEST['myArray'])) { $tt = explode(',',$_REQUEST['myArray']); }

	if(isset($_REQUEST['prevArray'])) { $prev = explode(',',$_REQUEST['prevArray']); }
	
	$selectBox = array();

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
       				<a href="#" class="subtext" id="prev" onclick="getPrev(<?php echo $index; ?>,<?php echo count($h);?>)">Previous</a>&nbsp;|&nbsp;
		       	   <?php
			         }
			       if($index < $count_rows-1)        
			         {
					$i=0;
		       	   ?>
<a href="#" class="subtext" id="next" onclick="getNext(<?php echo $index; ?>,<?php echo count($h);?>)">Next</a>
		          <?php
					$i++;
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

		<td align=center id="middle_column">
		<select name="assign_field[]" id="assign_field_<?php echo $j; ?>">
		<?php 
			
			$selectBox = array(0=>'Ignore',1=>'Contact Name',2=>'Name',3=>'Type',4=>'Company Name',5=>'Email',6=>'Mobile',7=>'Telephone',8=>'FAX',9=>'Address',10=>'Address Line1',11=>'Address Line2',12=>'Address Line3',13=>'Town',14=>'Postal Code',15=>'Country Name',16=>'First Division',17=>'Second Division',18=>'Tax Number');

		foreach($selectBox as $key=>$value) { ?>

		   
	<option value="<?php echo $key;?>" <?php if($key==$tt[$j] || $key==$prev[$j]) { ?>selected="selected"<?php } ?> ><?php echo $value;?></option>
		
		<?php } ?>
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
