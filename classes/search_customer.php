<?
	function find_customer($method, $data) {
		
		switch ($method) {
			case ('email') :
			case ('email strict') :
				
				//    $email=$data['email'];
				

				//       if($email!=''){
				// 	$customer=new Customer('email',$email);
				// 	if($customer->id)
				// 	  return $customer->id;
				//       }
				

				$customer = new Customer ( 'new', $data );
				return $customer->id;
				break;
			
			case ('other id') :
				
				if ($data ['other id'] == '') {
					$customer = new Customer ( 'new', $data );
					return $customer->id;
				}
				
				$sql = sprintf ( "select `Customer Key` from `Customer Dimension`  where `Customer Other ID`=%s", prepare_mysql ( $data ['other id'] ) );
				$result = mysql_query ( $sql );
				if ($row = mysql_fetch_array ( $result, MYSQL_ASSOC )) {
					$customer = new Customer ( $row ['Customer Key'] );
					return $customer->id;
				}
			// 	$customer = new Customer ( 'new', $data );
// 				return $customer->id;
				
				break;
			//    case('auto'):
		

		//       //get list of posible customers (email);
		

		//       $email=$data['email'];
		//       if($email!=''){
		// 	$sql=sprintf("select `Customer Key`,(length(`Customer Email`)-levenshtein(`Customer Email`,'%s'))/length(`Customer Email`) as similarity from `Customer Dimension`  where similarity>0  order by similarity limit 500",add_slashes($email));
		//       $result =& $this->db->query($sql);
		//       $p_customer_by_email=array();
		//       while($row=$result->fetchRow()){
		// 	$p_customer_by_email[$row['customer key']]=$row['similarity'];
		// 	if(!isset($multiplicity[$row['similarity']]))
		// 	  $multiplicity[$row['similarity']]=1;
		// 	else
		// 	  $multiplicity[$row['similarity']];
		//       }
		

		//       break;
		

		}
	
		return false;
	}


?>