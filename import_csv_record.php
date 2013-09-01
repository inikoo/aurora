<?php
	include('removeResult.php');

	require_once 'class.csv_parser.php';
	$csv = new CSV_PARSER;

	if(isset($_SESSION['file_path']))
	{
		//loading the CSV File
		$csv->load($_SESSION['file_path']);
	}

	//extracting the HEADERS
	$h = $csv->getHeaders();
	$count_rows = $csv->countRows();
	$index = $_REQUEST['v'];
	//echo '<pre>'; print_r($records_ignored_by_user);
	//$r =  $csv->getRow($index); 
	$raw = $csv->getrawArray();
	//$upper_value = count($raw);
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
			echo 'Value '.$index.' of '.$count_rows;

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
			       if($index < $count_rows)
			         {
					$i=0;
		       	   ?>
<a href="#" class="subtext" id="next" onclick="getNext(<?php echo $index; ?>,<?php echo count($h);?>)">Next</a>
		          <?php
					$i++;
			         }
		          ?>
		&nbsp;&nbsp;&nbsp;&nbsp; <a href="#" onclick="getIgnore(<?php echo $index; ?>)" id="result" class="subtext">Ignore Record</a>
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
		/*$selectBox = array('Ignore'=>'Ignore','Customer Main Contact Name'=>'Contact Name','Customer Name'=>'Name','Customer Type'=>'Type','Customer Company Name'=>'Company Name','Customer Main Plain Email'=>'Email','Contact Main Plain Mobile'=>'Mobile','Customer Main Plain Telephone'=>'Telephone','Customer Main Plain FAX'=>'FAX','Customer Main Plain Address'=>'Address','Customer Address Line 1'=>'Address Line1','Customer Address Line 2'=>'Address Line2','Customer Address Line 3'=>'Address Line3','Customer Address Town'=>'Town','Customer Address Postal Code'=>'Postal Code','Customer Address Country Name'=>'Country Name','Customer Address Country First Division'=>'First Division','Customer Address Country Second Division'=>'Second Division','Customer Tax Number'=>'Tax Number');*/

			if(isset($_REQUEST['subject']) && isset($_REQUEST['subject_key'])){
				$scope=$_REQUEST['subject'];
				$scope_args=$_REQUEST['subject_key'];
				$_SESSION['subject']=$scope;
				$_REQUEST['subject_key']=$scope_args;
			}else{
				$scope=$_SESSION['subject'];
				$scope_args=$_SESSION['subject_key'];
			}
			$selectBox = array('Ignore'=>'Ignore');

			switch($scope){
				case('customers_store'):
				$tbl = "Customer Dimension";
				$fld = "Customer Store Key";
				$pk = "Customer Key";
				break;

				case('supplier_products'):
				$tbl="Supplier Product Dimension";
				$fld = "Supplier Key";
				$pk = "Supplier Product ID";
				break;

				case('staff'):
				$tbl="Staff Dimension";
				$fld = "";
				$pk = "Staff Key";
				break;

				case('positions'):
				$tbl="Company Position Dimension";
				$fld = "";
				$pk = "Company Position Key";
				break;

				case('areas'):
				$tbl="Company Area Dimension";
				$fld = "";
				$pk = "Company Area Key";
				break;

				case('departments'):
				$tbl="Company Department Dimension";
				$fld = "";
				$pk = "Company Department Key";
				break;

				default:
				}
		$query = mysql_query("Select * from `$tbl` LIMIT 1");
		$res=mysql_fetch_assoc($query);
		foreach($res as $key=>$value){

			if($key==$fld || $key==$pk){
				continue;
			}else{
			$selectBox[$key]=$key; // generates associative array //
			}
		}

		foreach($selectBox as $key=>$value) {
			if((isset($tt[$j]) == TRUE))
			{
		?>
		<option value="<?php echo $key;?>" <?php if($tt[$j]==$key) { ?>selected="selected"<?php echo $tt[$j]; } ?> ><?php echo $value;?></option>
		<?php
		}
		else
			if((isset($prev[$j]) == TRUE))
			{
		?>
		<option value="<?php echo $key;?>" <?php if($prev[$j]==$key) { ?>selected="selected"<?php echo $prev[$j];} ?> ><?php echo $value;?></option>
		<?php
		}
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
			<h4 id="changecolor_<?php echo $j; ?>" ><?php echo $raw[$index][$j];?></h4>
		</td>
	</tr>
		<input type="hidden" name="values[]" value="<?php echo $raw[$index][$j]; ?>">
	<?php
	}
	?>
</table>

<!-- ------------------------------------------------------------------------------------------------------------------------------------ -->
<div id="display">
<?php
	//print_r($records_ignored_by_user);
  	$search = in_array($index,$records_ignored_by_user);
 
	if(isset($search) && $search>0)
	{
		echo "<span id=\"ignore_msg\" style=\"color:red;\">This data will be ignored</span>";
	}
?>

</div>
