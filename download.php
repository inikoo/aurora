<?php

/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 30 December 2015 at 17:00:00 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2015 Inikoo

 Version 3.0
*/

require_once 'common.php';


// hide notices
//@ini_set('error_reporting', E_ALL & ~ E_NOTICE);

//- turn off compression on the server
@apache_setenv('no-gzip', 1);
@ini_set('zlib.output_compression', 'Off');

if (!isset($_REQUEST['file']) || empty($_REQUEST['file'])) {
	header("HTTP/1.0 400 Bad Request");
	exit;
}


$download_id=$_REQUEST['file'];



$sql=sprintf('select `Download Filename`,`Download Data` from `Download Dimension` where `Download Key`=%d',
	$download_id
);

if ($result=$db->query($sql)) {
	if ($row = $result->fetch()) {
		$file_path  = $row['Download Filename'];
		$blob_data= $row['Download Data'];
	}else {
		header("HTTP/1.0 404 Not Found");
		exit;
	}
}else {
	print_r($error_info=$db->errorInfo());
	exit;
}





// sanitize the file request, keep just the name and extension
// also, replaces the file location with a preset one ('./myfiles/' in this example)

$path_parts = pathinfo($file_path);
$file_name  = $path_parts['basename'];
$file_ext   = $path_parts['extension'];
$file_path  = 'server_files/tmp/' . $file_name;


	
file_put_contents( 'server_files/tmp/' . $file_name, $blob_data);



// allow a file to be streamed instead of sent as an attachment
$is_attachment = isset($_REQUEST['stream']) ? false : true;

// make sure the file exists
if (is_file($file_path)) {
	$file_size  = filesize($file_path);
	$file = @fopen($file_path, "rb");
	if ($file) {
		// set the headers, prevent caching


		header("Pragma: public");
		header("Expires: -1");
		header("Cache-Control: public, must-revalidate, post-check=0, pre-check=0");
		header("Content-Disposition: attachment; filename=\"$file_name\"");

		// set appropriate headers for attachment or streamed file
		if ($is_attachment)
			header("Content-Disposition: attachment; filename=\"$file_name\"");
		else
			header('Content-Disposition: inline;');

		// set the mime type based on extension, add yours if needed.
		$ctype_default = "application/octet-stream";

		$content_types = array(
			"exe" => "application/octet-stream",
			"zip" => "application/zip",
			"mp3" => "audio/mpeg",
			"mpg" => "video/mpeg",
			"avi" => "video/x-msvideo",
		);
		$ctype = isset($content_types[$file_ext]) ? $content_types[$file_ext] : $ctype_default;
		header("Content-Type: " . $ctype);

		//check if http_range is sent by browser (or download manager)
		if (isset($_SERVER['HTTP_RANGE'])) {
			list($size_unit, $range_orig) = explode('=', $_SERVER['HTTP_RANGE'], 2);
			if ($size_unit == 'bytes') {
				//multiple ranges could be specified at the same time, but for simplicity only serve the first range
				//http://tools.ietf.org/id/draft-ietf-http-range-retrieval-00.txt
				list($range, $extra_ranges) = explode(',', $range_orig, 2);
			}
			else {
				$range = '';
				unlink( 'server_files/tmp/' . $file_name);
				header('HTTP/1.1 416 Requested Range Not Satisfiable');
				exit;
			}
		}
		else {
			$range = '';
		}

		//figure out download piece from range (if set)
		list($seek_start, $seek_end) = explode('-', $range, 2);

		//set start and end based on range (if set), else set defaults
		//also check for invalid ranges.
		$seek_end   = (empty($seek_end)) ? ($file_size - 1) : min(abs(intval($seek_end)), ($file_size - 1));
		$seek_start = (empty($seek_start) || $seek_end < abs(intval($seek_start))) ? 0 : max(abs(intval($seek_start)), 0);




		//Only send partial content header if downloading a piece of the file (IE workaround)
		if ($seek_start > 0 || $seek_end < ($file_size - 1)) {
			header('HTTP/1.1 206 Partial Content');
			header('Content-Range: bytes '.$seek_start.'-'.$seek_end.'/'.$file_size);
			header('Content-Length: '.($seek_end - $seek_start + 1));
		}
		else
			header("Content-Length: $file_size");

		header('Accept-Ranges: bytes');
		$sql=sprintf('insert into  `Download Attempt Dimension` (`Download Attempt Download Key`,`Download Attempt User Key`,`Download Attempt Date`)  values (%d,%d,%s)',
			$download_id,
			$user->id,
			prepare_mysql(gmdate('Y-m-d H:i:s'))

		);
		$db->exec($sql);

		$sql=sprintf('update `Download Dimension` set `Download Attempts`=`Download Attempts`+1 ,`Download Attempt Last Date`=%s where `Download Key`=%d',
			prepare_mysql(gmdate('Y-m-d H:i:s')),
			$download_id
		);

		$db->exec($sql);



		set_time_limit(0);
		fseek($file, $seek_start);

		while (!feof($file)) {
			print(@fread($file, 1024*8));
			ob_flush();
			flush();
			if (connection_status()!=0) {
				@fclose($file);
				exit;
			}
		}

		// file save was a success
		@fclose($file);

		unlink( 'server_files/tmp/' . $file_name);

		exit;
	}
	else {
		// file couldn't be opened
		unlink( 'server_files/tmp/' . $file_name);
		header("HTTP/1.0 500 Internal Server Error");

		exit;
	}
}
else {
	// file does not exist
	header("HTTP/1.0 404 Not Found");
	unlink( 'server_files/tmp/' . $file_name);
	exit;
}




?>
