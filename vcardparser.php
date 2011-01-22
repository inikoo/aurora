<?php

class importvcard
{
    function fromFile($filename, $decode_qp = true)
    {
        $text = $this->fileGetContents($filename);
        if ($text === false) 
		{
            return false;
        }
        return $this->fromText($text, $decode_qp);
    }

    function fileGetContents($filename)
    {
		if (file_exists($filename) && is_readable($filename)) 
		{

			$text = '';
			$len  = filesize($filename);
			$fp = fopen($filename, 'r');
			while ($line = fread($fp, filesize($filename))) {
				$text .= $line;
			}
			fclose($fp);
			return $text;
		}
     return false;
	}


	function fromText($text, $decode_qp = true)
	{
		$this->convertLineEndings($text);
		$fold_regex = '(\n)([ |\t])';

		$text = preg_replace("/$fold_regex/i", "", $text);
		$text = str_replace("\x00", '', $text);
		$lines = explode("\n", $text);
		end($lines);         // move the internal pointer to the end of the array
		$last_array_key = key($lines);
	
       		return $this->_fromArray($lines, $decode_qp,$last_array_key);
		
	}


	function convertLineEndings(&$text)
	{
		// DOS
		$text = str_replace("\r\n", "\n", $text);
		// Mac
		$text = str_replace("\r", "\n", $text);
	 }


	function _fromArray($source, $decode_qp = true,$last_array_key)
	{
		
		$num_of_array=intval($last_array_key/15);
		$info = array();
		$begin = false;
		$version=false;
		$card = array();
		$contact_info=array();
		$key=0;
		
		for($i=0;$i<=count($source);$i=$i+16)
		{
			
			$contacts=array();    
			foreach ($source as $line) 
			{
				if (trim($line) == '') 
				{
					continue;
				}
				$pos = strpos($line, ':');

				if ($pos === false) 
				{
					continue;
				}
				$left  = trim(substr($line, 0, $pos));
				#$left=str_replace(';',' ',$left);
			
				$pos_semicolon = strpos($left,';');
				$pos_semicolon = strpos($left,';');
				if($pos_semicolon===false)
				{
					
					$delimeter=$left;
					if($delimeter=='N')
					{
						$delimeter='Customer Main Contact Name';
										
										
					}
					if($delimeter=='FN')
					{
						$delimeter='Customer Name';
										
										
					}
					if($delimeter=='ORG')
					{
						$delimeter='Customer Company Name';
										
										
					}
					if($delimeter=='TITLE')
					{
						$delimeter='Customer Type';
										
										
					}
					if($delimeter=='REV')
					{
						continue;
					}

				}
				else
				{
					$delimeter  = trim(substr($line, 0, $pos_semicolon));
					$type=substr($line, $pos_semicolon+1);
					$pos_semicolon2 = strpos($type,';');
					$type_only=substr($type,0, $pos_semicolon2);
					$type_only=str_replace(":","","$type_only");
					#echo"Type only of $delimeter= $type_only<br>";
					
					if($delimeter=='TEL')
					{
						if($type_only=='WORK')
						{
							$delimeter='Customer Main Plain Telephone';
										
						}
						if($type_only=='HOME')
						{
							$delimeter='Customer Main Plain Mobile';
						}				
					}

					if($delimeter=='ADR')
					{
						#$right_explode = explode(";", $right);
						#print_r($right_explode);
						if($type_only=='WORK')
						{
							$delimeter='Customer Main Office Address';
										
						}
						if($type_only=='HOME')
						{
							continue;
										
						}
						

					 }
					
	
					if($delimeter=='EMAIL')
					{
						$delimeter='Customer Main Plain Email';

					 }
					if($delimeter=='LABEL')
					{
						continue;
					}
					
					
				}
				#echo"<br>delimeter value: $delimeter<br><br><br>";
	
				$left=$delimeter;


				$right = trim(substr($line, $pos+1, strlen($line)));
				$right=str_replace(';;',' ',$right);
				#$right=str_replace('=0D=0A',' + ',$right);
				#$right=str_replace(',',' + ',$right);
				if (! $begin) 
				{
					if (strtoupper($left) == 'BEGIN' && strtoupper($right) == 'VCARD') 
					{
						$begin = true;
					}
					continue;
				}

				elseif (! $version) 
				{
					if (strtoupper($left) == 'VERSION') 
					{
						$version = true;
					}
					continue;
				}
				else 
				{
					if (strtoupper($left) == 'END' && strtoupper($right) == 'VCARD') 
					{
						$info[] = $card;
						$begin = false;
						$version=false;
						$card  = array();
						
						$contact_info[$key]=$contacts;
						$key = $key+1;
						/*echo"<pre>";
						print_r($contacts);
						echo"</pre>";*/
						if($key==$num_of_array)	
						return($contact_info);
						
						continue;
						
					}
					else 
					{
						$typedef = $this->_getTypeDef($left);
						if (strtoupper($left) == 'ORG')
						{
							$org=$right;
							
						}
						if (strtoupper($left) == 'N')
						{
							$name=$right;
						}
						$contacts['Customer Type']='Unknown';
						$contacts[$left]=$right;
						$i++;
					}
				}
				
	
			}//end foreach
		
			
			
			
		}//end of for
		
		
		 
   	
		
    }
   

	function _getTypeDef($text)
	{
		return strtoupper($text[0]);
	}
}
?>

	
