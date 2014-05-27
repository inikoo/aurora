<?php


function process_search($q,$site_key) {

	$candidates=array();
	$page_scores=array();
	if ($q=='') {

		return array('number_results'=>count($candidates),'results'=>$candidates);
	}


	$array_q=preg_split('/\s+/',$q);
	$number_query_words=count($array_q);


	$found_family=false;
	$found_product=false;




	$score_match_product_code=3.5;
	$score_match_family_code=5.5;
	$score_boolean_factor=6;

	foreach ($array_q as $_q) {

		$sql=sprintf('select PP.`Page Key` ,`Page Store Resume`,`Page Store Title`,`Page URL`,`Product Main Image Key`,PP.`Product ID`,`Product Code`,`Product Name` from `Page Product Dimension` PP left join `Product Dimension` P on (P.`Product ID`=PP.`Product ID`) left join `Page Dimension` PA on (PA.`Page Key`=PP.`Page Key`)  left join `Page Store Dimension` PAS on (PAS.`Page Key`=PP.`Page Key`)  where `Site Key`=%d and `Product Code`=%s  group by  PP.`Page Key` ',$site_key,prepare_mysql($_q));

		$res=mysql_query($sql);
		while ($row=mysql_fetch_array($res)) {

			if ($row['Product Main Image Key']>0) {
				$image=sprintf('public_image.php?size=small&id=%d',$row['Product Main Image Key']);
			}else {
				$image='art/nopic.png';
			}


			$page_scores[$row['Page Key']]=$score_match_product_code;
			$candidates[$row['Page Key']]=array();

			$candidates[$row['Page Key']]['scope']='Product';
			$candidates[$row['Page Key']]['image']=$image;
			$candidates[$row['Page Key']]['score']=$score_match_product_code;
			$candidates[$row['Page Key']]['url']='http://'.$row['Page URL'];
			$candidates[$row['Page Key']]['title']=$row['Page Store Title'];
			$candidates[$row['Page Key']]['description']=$row['Page Store Resume'];
			$candidates[$row['Page Key']]['asset_description']='<span class="code">'.$row['Product Code'].'</span> '.$row['Product Name'];


		}
	}
	
		if($number_query_words>1 ){
	
	$q_boolean='';
	foreach($array_q as $_q){
	$q_boolean.="+$_q ";
	}
	$q_boolean=_trim($q_boolean);
	$sql=sprintf('select  match (`Product Name`) AGAINST  (%s IN BOOLEAN MODE) as score,  PP.`Page Key` ,`Page Store Resume`,`Page Store Title`,`Page URL`,`Product Main Image Key`,PP.`Product ID`,`Product Code`,`Product Name` from `Page Product Dimension` PP left join `Product Dimension` P on (P.`Product ID`=PP.`Product ID`) left join `Page Dimension` PA on (PA.`Page Key`=PP.`Page Key`)  left join `Page Store Dimension` PAS on (PAS.`Page Key`=PP.`Page Key`)  where  `Site Key`=%d  and match (`Product Name`) AGAINST  (%s IN BOOLEAN MODE) group by  PP.`Page Key`   ',
		prepare_mysql($q_boolean),
		$site_key,prepare_mysql($q_boolean));
	//print "$sql\n";
	$res=mysql_query($sql);
	while ($row=mysql_fetch_array($res)) {
		if ($row['Product Main Image Key']>0) {
			$image=sprintf('public_image.php?size=small&id=%d',$row['Product Main Image Key']);
		}else {
			$image='art/nopic.png';
		}

		if (array_key_exists($row['Page Key'],$candidates)) {

			//print $row['Page Key']." ".$page_scores[$row['Page Key']]." +".$score_match_product_description;

			$candidates[$row['Page Key']]['score']+=$row['score']*$number_query_words*$score_boolean_factor;
			$page_scores[$row['Page Key']]+=$row['score']*$number_query_words*$score_boolean_factor;

			print $row['Product Name']." ".$page_scores[$row['Page Key']]."\n";

		}else {
			$candidates[$row['Page Key']]=array();
			$candidates[$row['Page Key']]['scope']='Product';
			$candidates[$row['Page Key']]['page_key']=$row['Page Key'];
			$candidates[$row['Page Key']]['image']=$image;
			$candidates[$row['Page Key']]['score']=$row['score']*$number_query_words*$score_boolean_factor;
			$page_scores[$row['Page Key']]=$row['score']*$number_query_words*$score_boolean_factor;
			$candidates[$row['Page Key']]['url']='http://'.$row['Page URL'];
			$candidates[$row['Page Key']]['title']=$row['Page Store Title'];
			$candidates[$row['Page Key']]['description']=$row['Page Store Resume'];
			$candidates[$row['Page Key']]['asset_description']='<span class="code">'.$row['Product Code'].'</span> '.$row['Product Name'];


		}


	}

}

	$sql=sprintf('select  match (`Product Name`) AGAINST  (%s) as score,  PP.`Page Key` ,`Page Store Resume`,`Page Store Title`,`Page URL`,`Product Main Image Key`,PP.`Product ID`,`Product Code`,`Product Name` from `Page Product Dimension` PP left join `Product Dimension` P on (P.`Product ID`=PP.`Product ID`) left join `Page Dimension` PA on (PA.`Page Key`=PP.`Page Key`)  left join `Page Store Dimension` PAS on (PAS.`Page Key`=PP.`Page Key`)  where  `Site Key`=%d  and match (`Product Name`) AGAINST  (%s) group by  PP.`Page Key`   ',
		prepare_mysql($q),
		$site_key,prepare_mysql($q));
	//print "$sql\n";
	$res=mysql_query($sql);
	while ($row=mysql_fetch_array($res)) {
		if ($row['Product Main Image Key']>0) {
			$image=sprintf('public_image.php?size=small&id=%d',$row['Product Main Image Key']);
		}else {
			$image='art/nopic.png';
		}

		if (array_key_exists($row['Page Key'],$candidates)) {

			//print $row['Page Key']." ".$page_scores[$row['Page Key']]." +".$score_match_product_description;

			$candidates[$row['Page Key']]['score']+=$row['score'];
			$page_scores[$row['Page Key']]+=$row['score'];

			//print " ".$page_scores[$row['Page Key']]."\n";

		}else {
			$candidates[$row['Page Key']]=array();
			$candidates[$row['Page Key']]['scope']='Product';
			$candidates[$row['Page Key']]['page_key']=$row['Page Key'];
			$candidates[$row['Page Key']]['image']=$image;
			$candidates[$row['Page Key']]['score']=$row['score'];
			$page_scores[$row['Page Key']]=$row['score'];
			$candidates[$row['Page Key']]['url']='http://'.$row['Page URL'];
			$candidates[$row['Page Key']]['title']=$row['Page Store Title'];
			$candidates[$row['Page Key']]['description']=$row['Page Store Resume'];
			$candidates[$row['Page Key']]['asset_description']='<span class="code">'.$row['Product Code'].'</span> '.$row['Product Name'];


		}


	}
	

	


//		array_multisort($page_scores,SORT_NUMERIC, SORT_DESC,$candidates);
//	print_r($candidates);

	/*

	foreach ($array_q as $_q) {

		$sql=sprintf('select    PP.`Page Key` ,`Page Store Resume`,`Page Store Title`,`Page URL`,`Product Main Image Key`,PP.`Product ID`,`Product Code`,`Product Name` from `Page Product Dimension` PP left join `Product Dimension` P on (P.`Product ID`=PP.`Product ID`) left join `Page Dimension` PA on (PA.`Page Key`=PP.`Page Key`)  left join `Page Store Dimension` PAS on (PAS.`Page Key`=PP.`Page Key`)  where  `Site Key`=%d and `Product Name` REGEXP "[[:<:]]%s"  group by  PP.`Page Key`   ',$site_key,addslashes($_q));
		//print "$sql\n";
		$res=mysql_query($sql);
		while ($row=mysql_fetch_array($res)) {
			if ($row['Product Main Image Key']>0) {
				$image=sprintf('public_image.php?id=%d',$row['Product Main Image Key']);
			}else {
				$image='art/nopic.png';
			}

			if (array_key_exists($row['Page Key'],$candidates)) {

				//print $row['Page Key']." ".$page_scores[$row['Page Key']]." +".$score_match_product_description;

				$candidates[$row['Page Key']]['score']+=$score_match_product_description;
				$page_scores[$row['Page Key']]+=$score_match_product_description;

				//print " ".$page_scores[$row['Page Key']]."\n";

			}else {
				$candidates[$row['Page Key']]=array();
				$candidates[$row['Page Key']]['scope']='Product';
				$candidates[$row['Page Key']]['page_key']=$row['Page Key'];
				$candidates[$row['Page Key']]['image']=$image;
				$candidates[$row['Page Key']]['score']=$score_match_product_description;
				$page_scores[$row['Page Key']]=$score_match_product_description;
				$candidates[$row['Page Key']]['url']='http://'.$row['Page URL'];
				$candidates[$row['Page Key']]['title']=$row['Page Store Title'];
				$candidates[$row['Page Key']]['description']=$row['Page Store Resume'];
				$candidates[$row['Page Key']]['asset_description']='<span class="code">'.$row['Product Code'].'</span> '.$row['Product Name'];


			}


		}

	}

*/




	foreach ($array_q as $_q) {

		$sql=sprintf('select    PP.`Page Key`, `Page Store Resume`,`Page Store Title`,`Page URL`,`Product Family Main Image Key`,`Product Family Key`,`Product Family Code`,`Product Family Name` from `Page Product Dimension` PP left join `Product Family Dimension` F on (`Product Family Key`=PP.`Family Key`)   left join `Page Dimension` PA on (PA.`Page Key`=PP.`Page Key`)  left join `Page Store Dimension` PAS on (PAS.`Page Key`=PP.`Page Key`)  where `Site Key`=%d and `Product Family Code`=%s  group by  PP.`Page Key` ',$site_key,prepare_mysql($_q));
		//print $sql;
		//print "$sql\n";
		$res=mysql_query($sql);
		while ($row=mysql_fetch_array($res)) {

			if ($row['Product Family Main Image Key']>0) {
				$image=sprintf('public_image.php?size=small&id=%d',$row['Product Family Main Image Key']);
			}else {
				$image='art/nopic.png';
			}


			if (array_key_exists($row['Page Key'],$candidates)) {
				$candidates[$row['Page Key']]['score']+=$score_match_family_code;
				$page_scores[$row['Page Key']]+=$score_match_family_code;
			}else {
				$candidates[$row['Page Key']]=array();
				$candidates[$row['Page Key']]['scope']='Family';


				$candidates[$row['Page Key']]['image']=$image;
				$candidates[$row['Page Key']]['score']=$score_match_family_code;
				$page_scores[$row['Page Key']]=$score_match_family_code;
				$candidates[$row['Page Key']]['url']='http://'.$row['Page URL'];
				$candidates[$row['Page Key']]['title']=$row['Page Store Title'];
				$candidates[$row['Page Key']]['description']=$row['Page Store Resume'];
				$candidates[$row['Page Key']]['asset_description']='<span class="code">'.$row['Product Family Code'].'</span> '.$row['Product Family Name'];

			}
		}
	}


	$sql=sprintf('select   match (`Product Family Name`) AGAINST  (%s) as score,  PP.`Page Key` ,`Page Store Resume`,`Page Store Title`,`Page URL`,`Product Family Main Image Key`,`Product Family Key`,`Product Family Code`,`Product Family Name` from `Page Product Dimension` PP left join `Product Family Dimension` F on (`Product Family Key`=PP.`Family Key`) left join `Page Dimension` PA on (PA.`Page Key`=PP.`Page Key`)  left join `Page Store Dimension` PAS on (PAS.`Page Key`=PP.`Page Key`)  where `Page Site Key`=%d and match (`Product Family Name`) AGAINST  (%s)  group by  PP.`Page Key`   ',

		prepare_mysql($q),
		$site_key,prepare_mysql($q));
	//print "$sql\n";
	$res=mysql_query($sql);
	while ($row=mysql_fetch_array($res)) {
		if ($row['Product Family Main Image Key']>0) {
			$image=sprintf('public_image.php?size=small&id=%d',$row['Product Family Main Image Key']);
		}else {
			$image='art/nopic.png';
		}

		if (array_key_exists($row['Page Key'],$candidates)) {

			//print $row['Page Key']." ".$page_scores[$row['Page Key']]." +".$score_match_product_description;

			$candidates[$row['Page Key']]['score']+=$row['score'];
			$page_scores[$row['Page Key']]+=$row['score'];

			//print " ".$page_scores[$row['Page Key']]."\n";

		}else {
			$candidates[$row['Page Key']]=array();
			$candidates[$row['Page Key']]['scope']='Family';
			$candidates[$row['Page Key']]['page_key']=$row['Page Key'];
			$candidates[$row['Page Key']]['image']=$image;
			$candidates[$row['Page Key']]['score']=$row['score'];
			$page_scores[$row['Page Key']]=$row['score'];
			$candidates[$row['Page Key']]['url']='http://'.$row['Page URL'];
			$candidates[$row['Page Key']]['title']=$row['Page Store Title'];
			$candidates[$row['Page Key']]['description']=$row['Page Store Resume'];
			$candidates[$row['Page Key']]['asset_description']='<span class="code">'.$row['Product Family Code'].'</span> '.$row['Product Family Name'];


		}


	}



	/*

//array_multisort($page_scores,SORT_NUMERIC, SORT_DESC,$candidates);
//	print_r($candidates);

	foreach ($array_q as $_q) {

		$sql=sprintf('select    PP.`Page Key` ,`Page Store Resume`,`Page Store Title`,`Page URL`,`Product Family Main Image Key`,`Product Family Key`,`Product Family Code`,`Product Family Name` from `Page Product Dimension` PP left join `Product Family Dimension` F on (`Product Family Key`=PP.`Family Key`) left join `Page Dimension` PA on (PA.`Page Key`=PP.`Page Key`)  left join `Page Store Dimension` PAS on (PAS.`Page Key`=PP.`Page Key`)  where `Page Site Key`=%d and `Product Family Name` REGEXP "[[:<:]]%s"  group by  PP.`Page Key`   ',
			$site_key,addslashes($_q));

		$res=mysql_query($sql);



		while ($row=mysql_fetch_array($res)) {

			if ($row['Product Family Main Image Key']>0) {
				$image=sprintf('public_image.php?id=%d',$row['Product Family Main Image Key']);
			}else {
				$image='art/nopic.png';
			}


			if (array_key_exists($row['Page Key'],$candidates)) {
				$candidates[$row['Page Key']]['score']+=$score_match_family_description;
				$page_scores[$row['Page Key']]+=$score_match_family_description;
			}else {
				$candidates[$row['Page Key']]=array();
				$candidates[$row['Page Key']]['scope']='Family';

				$candidates[$row['Page Key']]['image']=$image;
				$candidates[$row['Page Key']]['score']=$score_match_family_description;
				$page_scores[$row['Page Key']]=$score_match_family_description;

				$candidates[$row['Page Key']]['url']='http://'.$row['Page URL'];
				$candidates[$row['Page Key']]['title']=$row['Page Store Title'];
				$candidates[$row['Page Key']]['description']=$row['Page Store Resume'];
				$candidates[$row['Page Key']]['asset_description']='<span class="code">'.$row['Product Family Code'].'</span> '.$row['Product Family Name'];


			}


		}

	}
*/




	$sql=sprintf(" select `Page Key`,`Page URL`,`Page Store Title`,`Page Store Resume` , match (`Page Store Title`) AGAINST  (%s) as score1,match (`Page Store Resume`) AGAINST  (%s) as score2,match (`Page Store Content`) AGAINST  (%s) as score3  from `Page Store Search Dimension` where `Page Site Key`=%d and  match (`Page Store Title`,`Page Store Resume`,`Page Store Content`) AGAINST  (%s);",

		prepare_mysql($q),
		prepare_mysql($q),
		prepare_mysql($q),
		$site_key,
		prepare_mysql($q)
	);

	// print $sql;

	$res=mysql_query($sql);
	while ($row=mysql_fetch_array($res)) {


		$image='art/nopic.png';

		$score=($row['score1']*3+$row['score2']*2+$row['score3'])/6;

		if (array_key_exists($row['Page Key'],$candidates)) {
			$candidates[$row['Page Key']]['score']+=$score;
			$page_scores[$row['Page Key']]+=$score;
		}else {
			$candidates[$row['Page Key']]=array();
			$candidates[$row['Page Key']]['scope']='Store';

			$candidates[$row['Page Key']]['image']=$image;
			$candidates[$row['Page Key']]['score']=$score;
			$page_scores[$row['Page Key']]=$score;

			$candidates[$row['Page Key']]['url']='http://'.$row['Page URL'];
			$candidates[$row['Page Key']]['title']=$row['Page Store Title'];
			$candidates[$row['Page Key']]['description']=$row['Page Store Resume'];
			$candidates[$row['Page Key']]['asset_description']='';


		}


	}

	array_multisort($page_scores,SORT_NUMERIC, SORT_DESC,$candidates);
//	print_r($candidates);
	//exit;

	$number_results=count($candidates);

$did_you_mean='';
$alternative_found=false;
	if ($number_results==0) {
		
		foreach ($array_q as $_q) {
			$word_soundex=soundex($_q);
			$sql =sprintf('select `Word` from  `Site Content Word Dimension` where `Word Soundex`=%s order by `Multiplicity` desc limit 1',
				prepare_mysql($word_soundex));
			$res=mysql_query($sql);
			if ($row=mysql_fetch_array($res)) {
				$did_you_mean.=$row['Word'].' ';
				$alternative_found=true;
			}else {
				$did_you_mean.=$_q.' ';
			}
		}


	}

if(!$alternative_found)$did_you_mean='';

	return array('number_results'=>$number_results,'results'=>$candidates,'did_you_mean'=>$did_you_mean);

}

?>
