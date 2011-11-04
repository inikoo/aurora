<?php

include_once('common.php');
include_once('class.Store.php');
include_once('assets_header_functions.php');


$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 $yui_path.'button/assets/skins/sam/button.css',
		 $yui_path.'assets/skins/sam/autocomplete.css',

		 //	 $yui_path.'assets/skins/sam/autocomplete.css',
		 'common.css',
		 'container.css',
		 'button.css',
		 'table.css',
		 'css/dropdown.css',
		 'css/import_data.css'
		 );
$css_files[]='theme.css.php';
$js_files=array(
		$yui_path.'utilities/utilities.js',
		$yui_path.'json/json-min.js',
		$yui_path.'paginator/paginator-min.js',
		$yui_path.'dragdrop/dragdrop-min.js',
		$yui_path.'datasource/datasource-min.js',
		$yui_path.'autocomplete/autocomplete-min.js',
		$yui_path.'datatable/datatable.js',
		$yui_path.'container/container-min.js',
		$yui_path.'menu/menu-min.js',
		$yui_path.'uploader/uploader-debug.js',
		'js/php.default.min.js',
		'js/common.js',
		'js/table_common.js',
		'js/dropdown.js',
        	'import_data.js.php'    
		);

		
		if(isset($_FILES['file']['tmp_name']))
		{
			if($_FILES['file']['name']=='') { header('location:parse_vcard.php'); }
			$filename=basename( $_FILES['file']['name']);
			$dot_pos=strpos($filename, '.');
			$file_type=substr($filename, $dot_pos+1, strlen($filename));
			
			
			
			#if ( $_FILES['file']['tmp_name'] )
			if (($file_type=="vcf") || ($file_type=="vcard"))
	 		{
				$target_path = "app_files/uploads/";

				$target_path = $target_path . basename( $_FILES['file']['name']); 

				if(move_uploaded_file($_FILES['file']['tmp_name'], $target_path)) 
				{
					require_once( 'vcardparser.php' );
					$parse = new importvcard();
					$file=$_FILES['file']['tmp_name'];
					
					$cardinfo = $parse->fromFile( $target_path);
					$_SESSION['cardinfo']=$cardinfo;
					#echo"<pre>";
					#print_r($cardinfo);
					#echo"</pre>";
					
					$smarty->assign('js_files',$js_files);
					$smarty->assign('css_files',$css_files);
					echo"<html>
						<head><title>Import Data</title></head><body>";
					$smarty->display('header.tpl');
					echo"<div id='bd' >";
					
						echo"<div id='no_details_title' style='clear:left;'>";
	    					  echo"<h1>Import Contacts From vCard File</h1>";
	  					  echo"</div>";
						  echo"<br>";
						
				
						  echo"<div class='prop'>";
			
						  echo"<label class='import_level' style='font-size:14px';>Check contacts prior to importing:</label>";
						  echo"<span style='font-size: 12px;'>We've scanned your file and found the following fields.It's important to select 	the fields, else all the fields will be imported.<br> When you're happy with the selected fields press the continue button. </span>";
					  	echo"<div class='clear'></div>";				
	
	
	
	
					  	  echo"<form action='' method='POST'>";
						  echo"<div class='framedsection' style='width:630px;'>";
						  	echo"<ul class='formActions'>";
						  	echo"<li>";
							echo"<table>";
								echo"<tr style=' border-bottom: 1px solid #CCCCCC;'>
								<th class='list-column-left' style='text-align: left; width: 400px; padding-left: 20px;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Name</th>
								<th class='list-column-left' style='text-align: left; width: 32%; padding-left: 70px;'>Action</th>
								</tr>";
							echo"</table>";
						 	 echo"<div style='width: 600px; height: 150px; overflow: auto; margin-left: 10px; '>";
								 
								echo"<table>";
								
								$i=0;
								foreach($cardinfo as $card)
								{
									#==================
									$N='Customer Main Contact Name';
									$TITLE='Customer Type';
									$ORG='Customer Company Name';
									
									#=============================
									echo"<tr style=' border-bottom: 1px solid #CCCCCC;'>";
									echo"<td style='text-align: left; width: 50%; padding-left: 20px;'>";
									echo"$card[$N],&nbsp; $card[$TITLE] at $card[$ORG]";
									echo"</td>";
									echo"<td style='text-align: left; width: 20%; padding-left: 30px;'>";
									echo"<input type='radio' name='$i' id='$i' value='import' checked='checked'/> Import 											&nbsp;&nbsp;&nbsp;&nbsp;
									     <input type='radio' name='$i' id='$i' value='ignore' /> Ignore
							  	  	";
									echo"</td>";
									echo"</tr>";
									$i=$i+1;
	
					 			}
								echo"<tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								</tr>";
					 			echo"</table>";
							echo"</div>";
						echo"<div class='clear'></div>";	
						echo"<input type='hidden' name='i' id='i' value='$i'>";

						
								

						echo"<table>
							
							<tr style='border-top: 1px solid rgb(204, 204, 204);' valign='bottom' heigth=100px;>
							<td>&nbsp;</td></tr>
							<tr>
							<td class='list-column-left' style='text-align: left; width: 10px; padding-left: 20px;'>
							<div class='bt'><input type='submit' value='Import Now' name='import_now' id='import_now'></div>
							</td>
							<td>&nbsp;&nbsp;</td>
							<td class='list-column-left' style='text-align: left; width: 470px; padding-left: 20px;'>
							<div class='bt'><a href='import_data.php?tipo=customers_store'><input type='button' value='Cancel' name='cancel' id='cancel' ></a>
							</div></td></tr></table>";
						//echo"<div class='bt'><input type='button' value='Cancel' name='cancel' id='cancel' onClick='history.go(-2)'></div>";
						echo"</li>";
						echo"</ul>";
						echo"</form>";
						echo"</div>";
					echo"</div>";
				echo"</div>";
				$smarty->display('footer.tpl');
				echo"</body></html>";
					
					
				}
			
				
				
			}
			else
			header('location:parse_vcard.php?error=Invalid File');	
				
			
			
			
			
		}
		elseif(isset($_POST['import_now']))
			{
				$final_array=array();
				$i=$_POST['i'];
				#echo"<pre>";
				#print_r($cardinfo);
				#echo"</pre>";
				for($j=0;$j<$i;$j++)
				{
					
					if($_POST[$j]=='import')
					{
						#echo"$j";
						$cardinfo=$_SESSION['cardinfo'];
						$final_array[$j]=$cardinfo[$j];
								
											
					}
					
				}
				$address=array();
				$final_full_array=array();
				$final_key=0;
				foreach($final_array as $final)
				{
				$C="Customer Main Office Address";
				#echo"address: $final[$C]";
				$right_explode = explode(";", $final[$C]);
				#echo"<br><pre>";
				#print_r($right_explode);
					$address['Customer Address Line 1']=$right_explode[0];
				$address['Customer Address Line 2']=$right_explode[1];
				$address['Customer Address Line 3']=$right_explode[2];
				$address['Customer Address Line 4']=$right_explode[3];
				$address['Customer Address Country Name']=$right_explode[4];
				$address['Customer Address Country First Division']=$right_explode[1];
				$address['Customer Address Postal Code']=$right_explode[3];
				$address['Customer Address Town']=$right_explode[1];
				$vcard_array=array_merge($final,$address);
				#echo"<br><pre> VCARD ARRAY";
				#print_r($vcard_array);
				#echo"</pre><br><br>";
					
					
						$final_full_array[$final_key]=$vcard_array;
						$final_key++;
					
				

				}
				$count_final_array=count($final_full_array);
				
				if($count_final_array==0)
				{
					$num_records="None of the record is imported";	
				}
				else
				{
					$num_records="$count_final_array record have been imported";	
				}
				$title='Import Data';
							
				$smarty->assign('js_files',$js_files);
				$smarty->assign('css_files',$css_files);

				$smarty->assign('num_records',$num_records);
				$smarty->assign('final_array',$final_array);
				$smarty->assign('title',$title);
				$smarty->display('vcard_view.tpl');
				
				
				echo"<br><pre><br>";
				print_r($final_full_array);    //This is the final array
				echo"</pre><br><br>";				
				#insert here, the $final_full_array into database
				#then move to another page to display all the data in the database.

			
			}

		//form for browse a file
		else
		{ 
			
			#==============	
			if(isset($_REQUEST['error']))
			{
				$showerror = $_REQUEST['error'];
				
			}	
			else
			{
				$showerror = '';
			}
			$smarty->assign('showerror',$showerror);
			unset($_REQUEST['error']);
			#====================	
			$title='Import Data';
			
			$smarty->assign('js_files',$js_files);
			$smarty->assign('css_files',$css_files);
			$smarty->assign('title',$title);

			$smarty->display('upload_vcard.tpl');
		 }	
	?>
