<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once '../../conf/dns.php';
include_once '../../class.Department.php';
include_once '../../class.Family.php';
include_once '../../class.Product.php';
include_once '../../class.Supplier.php';
include_once '../../class.Part.php';
include_once '../../class.Store.php';
include_once '../../class.Customer.php';
include_once '../../class.Site.php';
include_once '../../class.Image.php';

error_reporting(E_ALL);




date_default_timezone_set('UTC');

$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );

if (!$con) {print "Error can not connect with database server\n";exit;}
$db=@mysql_select_db($dns_db, $con);
if (!$db) {print "Error can not access the database\n";exit;}


require_once '../../common_functions.php';
require_once '../../common_detect_agent.php';

mysql_set_charset('utf8');
require_once '../../conf/conf.php';






$sql=sprintf("select `Page Store Key`,`Page Store Image Key`,P.`Page Key`,`Page Store Section` from `Page Dimension` P  left join `Page Store Dimension` PS on (P.`Page Key`=PS.`Page Key`)  where `Page Type`='Store' and `Page Store Source`!='' ");
$res=mysql_query($sql);
while ($row=mysql_fetch_array($res)) {
	$page=new Page($row['Page Key']);

	$page_image=new Image($row['Page Store Image Key']);
	if ($page_image->id) {
		$page_image_source=sprintf("images/%07d.%s",$page_image->data['Image Key'],$page_image->data['Image File Format']);

		$sql=sprintf("insert into `Image Bridge` (`Subject Type`,`Subject Key`,`Image Key`) values ('Page',%d,%d)",
			$page->id,
			$page_image->id
		);
		mysql_query($sql);
		//print "$sql\n";
	}else {
		$page_image_source='art/nopic.png';

	}
	unset($page_image);
	$sql=sprintf("update `Page Store Dimension` set `Page Store Image URL`=%s where `Page Key`=%d",
		prepare_mysql($page_image_source),
		$page->id
	);
	mysql_query($sql);


	$source=$page->data['Page Store Source'];

	preg_match_all('/\"public_image.php\?id=(\d+)\"/', $source, $matches);



	foreach ($matches[1] as $image_key) {

		$image=new Image($image_key);
		if ($image->id) {
			$replacement=sprintf("images/%07d.%s",$image->data['Image Key'],$image->data['Image File Format']);

			$source=preg_replace('/\"public_image.php\?id='.$image_key.'\"/','"'.$replacement.'"',$source);
		}



	}
	
	
		$pattern='/alt="http.*"/';
		
		$replacement='alt=""';
		$source=preg_replace($pattern,$replacement,$source);
	
	
	$pattern='/alt=".*\/.*"/';
		
		$replacement='alt=""';
		$source=preg_replace($pattern,$replacement,$source);
	
	
//	print $source;
	$pattern='/<a href="https?:\/\/www.ancientwisdom.biz\/pics\/(.+)\.jpg\s*"\s*target="_blank""?><img src="images\/(.+)" .* alt="(.+)" >/';

	preg_match_all($pattern, $source, $matches);

	//print_r($matches);
	
	foreach ($matches[1] as $key=>$possible_code) {

		
$possible_code=preg_replace('/_[a-z]+$/','',$possible_code);

		$code=false;
		$sql=sprintf("select `Product Code`,`Product Name` from `Product Dimension` where `Product Code`=%s and `Product Store Key`=%d",
			prepare_mysql($possible_code),
			$row['Page Store Key']
		);
		
		//print "$sql\n";
		$res2=mysql_query($sql);
		while ($row2=mysql_fetch_array($res2)) {
			$code=$row2['Product Code'];
			$description=$row2['Product Code'].', '.$row2['Product Name'];
		}
		//print "$sql\n";
		if (!$code) {
			$sql=sprintf("select `Product Family Code`,`Product Family Name` from  `Product Family Dimension`  where `Product Family Code`=%s and `Product Family Store Key`=%d",
				prepare_mysql($possible_code),
				$row['Page Store Key']
			);
			$res2=mysql_query($sql);
			while ($row2=mysql_fetch_array($res2)) {
				$code=$row2['Product Family Code'];
				$description=$row2['Product Family Code'].', '.$row2['Product Family Name'];
			}
		}
		if (!$code) {
			$sql=sprintf("select `Product Department Code`,`Product Department Name`  from `Product Department Dimension`  where `Product Department Code`=%s and `Product Department Store Key`=%d",
				prepare_mysql($possible_code),
				$row['Page Store Key']
			);
			$res2=mysql_query($sql);
			while ($row2=mysql_fetch_array($res2)) {
				$code=$row2['Product Department Code'];
				$description=$row2['Product Department Code'].', '.$row2['Product Department Name'];
			}
		}


		if (!$code) {
			$code=$possible_code;
			$description=$possible_code;

		}


		$pattern='/<a href="https?:\/\/www.ancientwisdom.biz\/pics\/('.$matches[1][$key].')\.jpg\s*"\s*target="_blank""?><img src="images\/('.$matches[2][$key].')"(.*) title=".*" alt="'.$matches[3][$key].'" >/';
		//print $pattern."\n";
		$replacement='<a href="https://www.ancientwisdom.biz/pics/${1}.jpg" target="_blank"><img src="images/${2}"${3} title="'.str_replace('"','',$description).'" alt="'.str_replace('"','',$code).'">';
		$source=preg_replace($pattern,$replacement,$source);
		//print $replacement."\n";












	}
	
	
	
	
	
	
	$pattern='/href="(.+)\s*"target="_blank""/';
		
		$replacement='href="${1}" target="_blank"';
		$source=preg_replace($pattern,$replacement,$source);


//	print $source;


	
	$sql=sprintf("update `Page Store Dimension` set `Page Store Source`=%s where `Page Key`=%d",
		prepare_mysql($source),
		$page->id
	);
	mysql_query($sql);

}

$sql=sprintf("select *  from `Page Header Dimension` ");
$res=mysql_query($sql);
while ($row=mysql_fetch_array($res)) {
	$source=$row['Template'];
	preg_match_all('/\"public_image.php\?id=(\d+)\"/', $source, $matches);
	foreach ($matches[1] as $image_key) {
		$image=new Image($image_key);
		if ($image->id) {
			$replacement=sprintf("images/%07d.%s",$image->data['Image Key'],$image->data['Image File Format']);
			$source=preg_replace('/\"public_image.php\?id='.$image_key.'\"/','"'.$replacement.'"',$source);
		}
	}
	$sql=sprintf("update `Page Header Dimension` set `Template`=%s where `Page Header Key`=%d",
		prepare_mysql($source),
		$row['Page Header Key']
	);
	mysql_query($sql);
}

$sql=sprintf("select *  from `Page Footer Dimension` ");
$res=mysql_query($sql);
while ($row=mysql_fetch_array($res)) {
	$source=$row['Template'];
	preg_match_all('/\"public_image.php\?id=(\d+)\"/', $source, $matches);
	foreach ($matches[1] as $image_key) {
		$image=new Image($image_key);
		if ($image->id) {
			$replacement=sprintf("images/%07d.%s",$image->data['Image Key'],$image->data['Image File Format']);
			$source=preg_replace('/\"public_image.php\?id='.$image_key.'\"/','"'.$replacement.'"',$source);
		}
	}
	$sql=sprintf("update `Page Footer Dimension` set `Template`=%s where `Page Footer Key`=%d",
		prepare_mysql($source),
		$row['Page Footer Key']
	);
	mysql_query($sql);
}


$sql=sprintf("select *  from `Site Dimension` ");
$res=mysql_query($sql);
while ($row=mysql_fetch_array($res)) {

	$field='Site Menu HTML';
	$source=$row[$field];
	preg_match_all('/\"public_image.php\?id=(\d+)\"/', $source, $matches);
	foreach ($matches[1] as $image_key) {
		$image=new Image($image_key);
		if ($image->id) {
			$replacement=sprintf("images/%07d.%s",$image->data['Image Key'],$image->data['Image File Format']);
			$source=preg_replace('/\"public_image.php\?id='.$image_key.'\"/','"'.$replacement.'"',$source);
		}
	}




	$sql=sprintf("update `Site Dimension` set `%s`=%s where `Site Key`=%d",
		addslashes($field),
		prepare_mysql($source),
		$row['Site Key']
	);
	mysql_query($sql);

	$field='Site Menu CSS';
	$source=$row[$field];
	preg_match_all('/\"public_image.php\?id=(\d+)\"/', $source, $matches);
	foreach ($matches[1] as $image_key) {
		$image=new Image($image_key);
		if ($image->id) {
			$replacement=sprintf("images/%07d.%s",$image->data['Image Key'],$image->data['Image File Format']);
			$source=preg_replace('/\"public_image.php\?id='.$image_key.'\"/','"'.$replacement.'"',$source);
		}
	}
	$sql=sprintf("update `Site Dimension` set `%s`=%s where `Site Key`=%d",
		addslashes($field),
		prepare_mysql($source),
		$row['Site Key']
	);
	mysql_query($sql);



}

$sql=sprintf("select *  from `Page Store External File Dimension` ");
$res=mysql_query($sql);
while ($row=mysql_fetch_array($res)) {

	$field='Page Store External File Content';
	$source=$row[$field];
	preg_match_all('/\"public_image.php\?id=(\d+)\"/', $source, $matches);
	foreach ($matches[1] as $image_key) {
		$image=new Image($image_key);
		if ($image->id) {
			$replacement=sprintf("../images/%07d.%s",$image->data['Image Key'],$image->data['Image File Format']);
			$source=preg_replace('/\"public_image.php\?id='.$image_key.'\"/','"'.$replacement.'"',$source);
		}
	}

	preg_match_all("/\'public_image.php\?id=(\d+)\'/", $source, $matches);
	foreach ($matches[1] as $image_key) {
		$image=new Image($image_key);
		if ($image->id) {
			$replacement=sprintf("../images/%07d.%s",$image->data['Image Key'],$image->data['Image File Format']);
			$source=preg_replace("/\'public_image.php\?id=".$image_key."\'/",'"'.$replacement.'"',$source);
		}
	}


	$sql=sprintf("update `Page Store External File Dimension` set `%s`=%s where `Page Store External File Key`=%d",
		addslashes($field),
		prepare_mysql($source),
		$row['Page Store External File Key']
	);
	mysql_query($sql);





}



exit;

$sql=sprintf("select P.`Page Key`,`Page Store Section` from `Page Dimension` P  left join `Page Store Dimension` PS on (P.`Page Key`=PS.`Page Key`)  where `Page Type`='Store'  ");
$res=mysql_query($sql);
while ($row=mysql_fetch_array($res)) {
	if (in_array($row['Page Store Section'],array('Information'))) {
		$section_type='Info';
	}elseif (in_array($row['Page Store Section'],array( 'Front Page Store','Search','Registration', 'Client Section', 'Checkout', 'Login', 'Welcome', 'Not Found', 'Reset', 'Basket', 'Login Help','Thanks'))) {
		$section_type='System';
	}elseif (in_array($row['Page Store Section'],array('Product Description'))) {
		$section_type='Product';
	}elseif (in_array($row['Page Store Section'],array('Product Category Catalogue'))) {
		$section_type='FamilyCategory';
	}elseif (in_array($row['Page Store Section'],array('Family Category Catalogue'))) {
		$section_type='FamilyCategory';
	}elseif (in_array($row['Page Store Section'],array('Family Catalogue'))) {
		$section_type='Family';
	}elseif (in_array($row['Page Store Section'],array('Department Catalogue'))) {
		$section_type='Department';
	}else {

		print "zz  ".$row['Page Store Section']."  \n";
		exit("caca");
	}

	$sql=sprintf("update `Page Store Dimension` set `Page Store Section Type`=%s where `Page Key`=%d",prepare_mysql($section_type),$row['Page Key']);
	mysql_query($sql);
}

exit;

fix_orphan_store_pages();




$sql="TRUNCATE `Page Product Button Dimension`;";
mysql_query($sql);
$sql="TRUNCATE `Page Product Dimension`;";
mysql_query($sql);


$sql="select * from `Page Dimension` ";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
	$page=new Page($row['Page Key']);
	$page->update_button_products();
	$page->update_list_products();


}




exit;



function fix_orphan_store_pages() {
	$sql="select * from `Page Dimension` ";
	$result=mysql_query($sql);
	while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
		$page=new Page($row['Page Key']);



		if ($page->data['Page Type']=='Store' and !array_key_exists('Page Store Source',$page->data)) {
			print $page->id.' '.$page->data['Page URL']."\n";
			$page->delete(false);

		}
	}

}



chdir('../../');

$sql="select * from `Page Store Dimension` PS  left join `Page Dimension` P on (P.`Page Key`=PS.`Page Key`) where  `Page Site Key`>0 and `Page Store Section` not in ('Login','Client Section','Registration'); ";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {

	$site=new Site($row['Page Site Key']);
	$page=new Page($row['Page Key']);

	if ($row['Page Store Section']=='Family Catalogue' ) {


		$old_url='www.aw-regalos.com/forms/'.strtolower($page->data['Page Code']).'/index.html';

		$redirect_key=$page->add_redirect($old_url);
	}

}



exit;


//$sql="select * from kbase.`Country Dimension`";
//$result=mysql_query($sql);
//while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
//print "cp ../../examples/_countries/".strtolower(preg_replace('/\s/','_',$row['Country Name']))."/ammap_data.xml ".$row['Country Code'].".xml\n";
//}
//exit;

$sql="select * from `Page Store Dimension` PS  left join `Page Dimension` P on (P.`Page Key`=PS.`Page Key`) where  `Page Site Key`>0 and `Page Store Section` not in ('Login','Client Section','Registration'); ";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {

	$site=new Site($row['Page Site Key']);
	$page=new Page($row['Page Key']);

	if ($row['Page Store Section']=='Family Catalogue' ) {
		$quantity=3;

	}else
		$quantity=0;

	$page->update_field_switcher('Number See Also Links',$quantity);
	$page->update_see_also();
}




exit;

$sql="select * from `Page Store Dimension` PS  left join `Page Dimension` P on (P.`Page Key`=PS.`Page Key`) where  `Page Site Key`>0 and `Page Store Section` not in ('Login','Client Section','Registration'); ";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {


	if (in_array($row['Page Store Section'],array('Login','Client Section','Registration'))) {
		continue;
	}
	$site=new Site($row['Page Site Key']);
	$page=new Page($row['Page Key']);

	//$url=$row['Page URL'];


	$url=$site->data['Site URL'].'/'.strtolower($row['Page Code']);
	print $row['Page Site Key']." $url\n";
	$sql=sprintf("update `Page Dimension` set `Page URL`=%s where `Page Key`=%d",prepare_mysql($url),$row['Page Key']);

	print "$sql\n";
	mysql_query($sql);

}


$sql="select * from `Page Redirection Dimension`  ";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {

	// $page=new Page($row['Page Target Key']);
	//$redirection=preg_replace('/www\.ancietwisdom\.biz/','www.ancientwisdom.biz',$row['Page Target URL']);
	$tmp=_trim($row['Source File']);

	$sql=sprintf("update `Page Redirection Dimension` set `Source File`=%s where `Page Redirection Key`=%d",prepare_mysql($tmp),$row['Page Redirection Key']);


	mysql_query($sql);

}



$sql="select * from `Page Redirection Dimension`  ";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {

	$page=new Page($row['Page Target Key']);
	//$redirection=preg_replace('/www\.ancietwisdom\.biz/','www.ancientwisdom.biz',$row['Page Target URL']);


	$sql=sprintf("update `Page Redirection Dimension` set `Page Target URL`=%s where `Page Redirection Key`=%d",prepare_mysql($page->data['Page URL']),$row['Page Redirection Key']);


	mysql_query($sql);

}

exit;



exit;




exit;

$sql="select * from `Site Dimension`   ";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
	$site=new Site($row['Site Key']);

	$site->update_footers($site->data['Site Default Footer Key']);
	$site->update_headers($site->data['Site Default Header Key']);

}






$sql="select * from `Page Store Dimension` PS  left join `Page Dimension` P on (P.`Page Key`=PS.`Page Key`) where PS.`Page Key`=2205  ";

$sql="select * from `Page Store Dimension` PS  left join `Page Dimension` P on (P.`Page Key`=PS.`Page Key`)   ";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {

	$site=new Site($row['Page Site Key']);
	$page=new Page($row['Page Key']);

	$url=$row['Page URL'];

	$url=preg_replace('|^http\:\/\/|','',$url);
	$url=preg_replace('/ancietwisdom/','ancientwisdom',$url);



	//$url=preg_replace('/^www.aw-geschenke.com/','',$url);

	if (preg_match('/^forms\//',$url)) {
		$url=$site->data['Site URL'].'/'.$url;

	}



	if (! (preg_match('/\.(php|html)$/',$url) or preg_match('/\.php/',$url) ) ) {
		$url=$url.'/index.php';
	}

	if (!preg_match('/^www/',$url)) {
		//$url=$site->data['Site URL'].'/'.$url;
	}
	$url=preg_replace('|^\/|','',$url);

	$url=str_replace('//','/',$url);
	$sql=sprintf("update `Page Dimension` set `Page URL`=%s where `Page Key`=%d",prepare_mysql($url),$row['Page Key']);
	//print "$sql\n";


	mysql_query($sql);


	if ($row['Page Store Section']=='Department Catalogue' ) {
		$department=new Department($row['Page Parent Key']);
		if ($department->id) {
			$sql=sprintf("update `Page Store Dimension` set `Page Parent Code`=%s where `Page Key`=%d",
				prepare_mysql($department->data['Product Department Code']),
				$row['Page Key']);
			mysql_query($sql);
		}
	}
	if ($row['Page Store Section']=='Family Catalogue' ) {
		$family=new Family($row['Page Parent Key']);
		if ($family->id) {
			$sql=sprintf("update `Page Store Dimension` set `Page Parent Code`=%s where `Page Key`=%d",
				prepare_mysql($family->data['Product Family Code']),
				$row['Page Key']);
			mysql_query($sql);
		}
		$sql=sprintf("update `Page Store Dimension` set `Number See Also Links`=%d where `Page Key`=%d",
			$site->data['Site Default Number See Also Links'],
			$row['Page Key']);
		mysql_query($sql);

		$department=new Department($family->data['Product Family Main Department Key']);
		if ($department->id) {
			$parent_pages_keys=$department->get_pages_keys();
			foreach ($parent_pages_keys as $parent_page_key) {
				$page->add_found_in_link($parent_page_key);
				break;
			}
		}



		//print $sql;
	}else {
		$sql=sprintf("update `Page Store Dimension` set `Number See Also Links`=%d where `Page Key`=%d",
			0,
			$row['Page Key']);
		mysql_query($sql);

	}


	$page->get_data('id',$page->id);

	$page->update_see_also();
	$page->update_number_found_in();
	//$page->update_preview_snapshot('aw');

	$old_url=$page->data['Page URL'];
	$sql=sprintf("update `Page Dimension` set `Page URL`=%s where `Page Key`=%d",
		prepare_mysql($site->data['Site URL'].'/'.strtolower($page->data['Page Code'])),
		$page->id);
	mysql_query($sql);
	$page->get_data('id',$page->id);

	//print_r($page);

	$redirect_key=$page->add_redirect($old_url);
	if ($redirect_key) {
		$redirect_data=$page->get_redirect_data($redirect_key);
		//print_r($redirect_data);
		//print $redirect_data['Source File'];
		if (preg_match('/\.html$/',$redirect_data['Source File'])) {
			$_source=preg_replace('/\.html$/','.php',$redirect_data['Source']);
			$page->add_redirect($_source);
		}elseif (preg_match('/\.php$/',$redirect_data['Source File'])) {
			$_source=preg_replace('/\.php$/','.html',$redirect_data['Source']);
			$page->add_redirect($_source);
		}
	}
	/*

//print "old:::  $url $old_url \n";
	if($url=preg_match('/^www.aw-geschenke.com/',$old_url)){
		$sql=sprintf("update `Page Redirection Dimension` set `Can Upload`='Yes' where `Page Redirection Key`=%d", $redirect_key);
		print "$sql\n";
		mysql_query($sql);
	}
	if($url=preg_match('/^www.ancientwisdom.biz/',$old_url)){
		$sql=sprintf("update `Page Redirection Dimension` set `Can Upload`='Yes' where `Page Redirection Key`=%d", $redirect_key);
//print "$sql\n";
		mysql_query($sql);
	}
	if($url=preg_match('/^www.aw-cadeux.com/',$old_url)){
		$sql=sprintf("update `Page Redirection Dimension` set `Can Upload`='Yes' where `Page Redirection Key`=%d", $redirect_key);
//print "$sql\n";
		mysql_query($sql);
	}
	if($url=preg_match('/^www.aw-regali.com/',$old_url)){
		$sql=sprintf("update `Page Redirection Dimension` set `Can Upload`='Yes' where `Page Redirection Key`=%d", $redirect_key);
//print "$sql\n";
		mysql_query($sql);
	}
	if($url=preg_match('/^www.aw-podarki.com/',$old_url)){
		$sql=sprintf("update `Page Redirection Dimension` set `Can Upload`='Yes' where `Page Redirection Key`=%d", $redirect_key);
//print "$sql\n";
		mysql_query($sql);
	}

*/
	if ($redirect_key) {


		//$page->upload_htaccess($redirect_key);

		//sleep ( 1 );

	}
	print $page->id."\n";


}




?>
