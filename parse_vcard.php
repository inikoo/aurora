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
		'common.js.php',
		'table_common.js.php',
		
		'js/dropdown.js',
        'import_data.js.php'    
		);

		
		if(isset($_FILES['file']['tmp_name']))
		{
			if($_FILES['file']['name']=='') { header('location:parse_vcard.php?tipo=customers_store'); }
			$filename=basename( $_FILES['file']['name']);
			$dot_pos=strpos($filename, '.');
			$file_type=substr($filename, $dot_pos+1, strlen($filename));
			
			#if ( $_FILES['file']['tmp_name'] )
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
				$smarty->display('header.tpl');
				echo"<div id='bd' >";
				
					echo"<div id='no_details_title' style='clear:left;'>";
    					  echo"<h1>Import Contacts From vCard File</h1>";
  					  echo"</div>";
					  echo"<br>";
					
			
					  echo"<div class='prop'>";
		
					  echo"<label class='import_level' style='font-size:14px';>Step 2 - Check contacts prior to importing:</label>";
					  echo"<span style='font-size: 12px;'>We've scanned your file and found the following fields.It's important to select the fields, else all the fields will be imported.<br> When you're happy with the selected fields press the continue button. </span>";
					  echo"<div class='clear'></div>";				




				  	  echo"<form action='' method='POST'>";
					  echo"<div  style='width: 650px; height: 210px; overflow: auto; margin: auto; padding-right: 260px;'>";
					  	echo"<ul class='formActions'>";
					  	echo"<li>";
					 	 echo"<div class='framedsection'>";
				
							echo"<table>";
							echo"<tr>
							<th class='list-column-left' style='text-align: left; width: 50%; padding-left: 20px;'>Name</th>
							<th class='list-column-left' style='text-align: left; width: 20%; padding-left: 30px;'>Action</th>
							</tr>
							<tr>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							</tr>";
							$i=0;
							foreach($cardinfo as $card)
							{
								#==================
								$N='Customer Main Contact Name';
								$TITLE='Customer Type';
								$ORG='Customer Company Name';
								
								#=============================
								echo"<tr>";
								echo"<td style='text-align: left; width: 50%; padding-left: 20px;'>";
								echo"$card[$N],&nbsp; $card[$TITLE] at $card[$ORG]";
								echo"</td>";
								echo"<td style='text-align: left; width: 20%; padding-left: 30px;'>";
								echo"<select name='$i' id='$i' >
								<option value='import'>Import</option>
								<option value='ignore'>Ignore</option>
					     			</select>
						  	  	";
								echo"</td>";
								echo"</tr>";
								$i=$i+1;

				 			}
				 			echo"</table>";
						echo"</div>";
				
					echo"<input type='hidden' name='i' id='i' value='$i'>";
					echo"<div class='bt'><input type='submit' value='Import Now' name='import_now' id='import_now'></div>";
					echo"</li>";
					echo"</ul>";
					echo"</form>";
					echo"</div>";
				echo"</div>";
			echo"</div>";
				$smarty->display('footer.tpl');
				
				
				
			
			}
			
			
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
				
				
				#insert here, the $final_array into database
				#then move to another page to display all the data in the database.
					
				
				$smarty->assign('js_files',$js_files);
				$smarty->assign('css_files',$css_files);

				$smarty->assign('final_array',$final_array);
				$smarty->display('vcard_view.tpl');
				echo"<pre>";
				print_r($final_array);
				echo"</pre>";
			
			}

		//form for browse a file
		else
		{ 
			
			
			$smarty->assign('js_files',$js_files);
			$smarty->assign('css_files',$css_files);


			$smarty->display('upload_vcard.tpl');
		 }	
	?>
