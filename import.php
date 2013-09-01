<?php
include_once 'common.php';



if (isset($_POST['submit'])) {
	$uploadedfile= $_FILES['uploadedfile']['name'];
	$ext = substr($uploadedfile, strrpos($uploadedfile, '.') + 1);
	if ($ext=='csv') {
		$target_path = "csv_upload/";

		$target_path = $target_path . basename( $_FILES['uploadedfile']['name']);

		if (move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) {
			echo "The file ".  basename( $_FILES['uploadedfile']['name'])." has been uploaded<br>";
			$v=basename( $_FILES['uploadedfile']['name']);


			//Code for finding the field name in the database and inserting the data into it
			function mysql_insert($table, $data) {

				$result = mysql_query("select * from `Customer Dimension`");
				if (!$result) {
					die('Query failed: ' . mysql_error());
				}

				$i = 0;
				$field=array();
				while ($i < mysql_num_fields($result)) {

					$meta = mysql_fetch_field($result, $i);
					if (!$meta) {
						echo "No information available<br />\n";
					}

					$field[]=$meta->name;
					$i++;

				}


				//print_r(array_values($field));
				echo"<br>";
				echo"<br>";
				mysql_free_result($result);


				$values = array_map('mysql_real_escape_string', array_values($data));
				echo"This is array value";
				//print_r(array_values($values));


				print_r(implode(",", $values));
				$keys = array_values($field);
				//$sql=('INSERT INTO `Customer Dimension` (`'.implode('`,`', $keys).'`) VALUES (\''.implode('\',\'', $values).'\')');
				$sql=('INSERT INTO `Customer Dimension` (`'.implode('`,`', $keys).'`) VALUES (\''.implode(",", $values).'\')');
				$query=mysql_query($sql);
				echo"<br>sql=<br>$sql";

				return $query;
			}


			$table=`Customer Dimension`;



			//read the csv file and store data in $data array
			chmod("$target_path", 0666);
			$handle = fopen($target_path, "r");


			(fgetcsv($handle));
			while (($data = fgetcsv($handle)) !== FALSE) {
				$q=mysql_insert($table,$data);
				if ($q)
					echo"inserted<br>";
				else {
					echo"not inserted<br>";
					mysql_query($q) or die(mysql_error());
				}

			}

			fclose($handle);

			print "Import done";
		}
	}
	else
		echo"Only csv files can be imported";
}
else {


	$smarty->display('import_csv.tpl');
}
?>
