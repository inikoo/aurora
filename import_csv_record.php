<?php

	include('removeResult.php');

	require_once 'csvparser.php';
	$csv = new CSV_PARSER;

	//loading the CSV File
	$csv->load($_SESSION['file_path']);

	//extracting the HEADERS
	$h = $csv->getHeaders();
	$count_rows = $csv->countRows();
	$index = $_REQUEST['v'];
	
	//echo '<pre>'; print_r($colorArray);

	//$r =  $csv->getRow($index);
	$raw = $csv->getrawArray();
	
	
	$tt = array();

	$prev = array();	

	if(isset($_REQUEST['myArray'])) { $tt = explode(',',$_REQUEST['myArray']); }

	if(isset($_REQUEST['prevArray'])) { $prev = explode(',',$_REQUEST['prevArray']); }
	
	

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
		&nbsp;&nbsp;&nbsp;&nbsp; <a href="#" onclick="getIgnore(<?php echo $index; ?>,<?php echo count($h); ?>)" id="result" class="subtext">Ignore Record</a>
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
			
			$selectBox = array('Ignore'=>'Ignore','Customer Main Contact Name'=>'Contact Name','Customer Name'=>'Name','Customer Type'=>'Type','Customer Company Name'=>'Company Name','Customer Main Plain Email'=>'Email','Contact Main Plain Mobile'=>'Mobile','Customer Main Plain Telephone'=>'Telephone','Customer Main Plain FAX'=>'FAX','Customer Main Plain Address'=>'Address','Customer Address Line 1'=>'Address Line1','Customer Address Line 2'=>'Address Line2','Customer Address Line 3'=>'Address Line3','Customer Address Town'=>'Town','Customer Address Postal Code'=>'Postal Code','Customer Address Country Name'=>'Country Name','Customer Address Country First Division'=>'First Division','Customer Address Country Second Division'=>'Second Division','Customer Tax Number'=>'Tax Number');



		foreach($selectBox as $key=>$value) { 
			if((isset($tt[$j]) == TRUE))
			{
		?>
		<option value="<?php echo $key;?>" <?php if($tt[$j]==$key) { ?>selected="selected"<?php } ?> ><?php echo $value;?></option>
		<?php
			}
			else
				if((isset($prev[$j]) == TRUE))
				{
		?>
		<option value="<?php echo $key;?>" <?php if($prev[$j]==$key) { ?>selected="selected"<?php } ?> ><?php echo $value;?></option>
		<?php 		} 
				else
				{
		?>
		<option value="<?php echo $key;?>"><?php echo $value;?></option>
		<?php
				}

		}?>
		</select>
		</td>
		<td>
			<!-- Value Output -->
			<h4 id="changecolor_<?php echo $j; ?>" ><?php echo $raw[$index][$j]; ?></h4>
		</td>
	</tr>
		<input type="hidden" name="values[]" value="<?php echo $raw[$index][$j]; ?>">
		
	<?php
		
	}
	
	?>
	
</table>
<div id="display">
<?php 
  	$search = array_search($index,$colorArray);

	if(isset($search) && $search>0)
	{
		echo "<span style=\"color:red;\">This data will be ignored</span>";
	}
	



/*	for($p=0; $p<count($h); $p++)
	{
	  if(isset($color_array) && $index == $color_array[$p])
		{
			echo $_SESSION[$p];
		}

	  
	if(isset($_REQUEST['color_array']))

		{
			echo $_REQUEST['color_array'][$p];
		}
		
	} */
?>
</div>
